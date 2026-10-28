<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Reservation;
use App\Models\TrainingRoom;
use App\Models\TrainingSchedule;
use App\Models\TrainingSession;
use App\Models\TrainingSessionTemplate;
use App\Services\TrainingScheduleConflictService;
use App\Exports\DailyTrainingScheduleExport;
use App\Exports\MonthlyTrainingScheduleExport;
use App\Exports\YearlyTrainingScheduleExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DailyTrainingScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $validated = $request->validate([
            'building_id' => ['nullable', 'integer'],
            'date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $date = $validated['date'] ?? now()->toDateString();

        $buildingsQuery = Building::query()->orderBy('name');

        if ($request->routeIs('building-coordinator.*')) {
            $buildingsQuery->whereIn(
                'id',
                $user->buildings()->select('buildings.id')
            );
        }

        $buildings = $buildingsQuery->get();

        $selectedBuilding = null;

        if (isset($validated['building_id'])) {
            $selectedBuilding = $buildings->firstWhere(
                'id',
                (int) $validated['building_id']
            );

            if (!$selectedBuilding) {
                abort(403, 'You are not allowed to manage this building.');
            }
        } else {
            $selectedBuilding = $buildings->first();
        }

        $templates = collect();
        $trainingRooms = collect();
        $schedule = null;
        $previousSchedule = null;
        $sessionsByTime = collect();

        if ($selectedBuilding) {
            $templates = TrainingSessionTemplate::query()
                ->where('building_id', $selectedBuilding->id)
                ->orderBy('session_number')
                ->get();

            $trainingRooms = TrainingRoom::query()
                ->where('building_id', $selectedBuilding->id)
                ->where('status', 'AVAILABLE')
                ->orderBy('name')
                ->get();

            $schedule = TrainingSchedule::query()
                ->with('sessions.trainingRoom')
                ->where('building_id', $selectedBuilding->id)
                ->whereDate('schedule_date', $date)
                ->first();

            if ($schedule) {
                $sessionsByTime = $schedule->sessions->keyBy(
                    fn (TrainingSession $session) =>
                        $this->timeKey(
                            $session->start_time,
                            $session->end_time
                        )
                );
            }

            $previousDate = Carbon::parse($date)->subDay()->toDateString();

                $previousSchedule = TrainingSchedule::query()
                    ->with(['sessions' => function ($query) {
                        $query->orderBy('start_time')
                            ->orderBy('end_time');
                    }])
                    ->where('building_id', $selectedBuilding->id)
                    ->whereDate('schedule_date', $previousDate)
                    ->first();
        }

        return view('training-schedule.daily', compact(
            'buildings',
            'selectedBuilding',
            'date',
            'templates',
            'trainingRooms',
            'schedule',
            'sessionsByTime',
            'previousSchedule'
        ));
    }
   
public function store(
    Request $request,
    TrainingScheduleConflictService $conflictService
) {
    $validated = $request->validate([
        'building_id' => ['required', 'integer', 'exists:buildings,id'],
        'schedule_date' => ['required', 'date_format:Y-m-d'],
        'sessions' => ['nullable', 'array'],
        'sessions.*.session_number' => ['required', 'integer', 'min:1'],
        'sessions.*.subject' => ['nullable', 'string', 'max:200'],
        'sessions.*.training_room_id' => [
            'nullable',
            'integer',
            'exists:training_rooms,id',
        ],
        'sessions.*.class_name' => ['nullable', 'string', 'max:150'],
        'sessions.*.instructor_name' => ['nullable', 'string', 'max:150'],
        'sessions.*.participant_count' => ['nullable', 'integer', 'min:0'],
        'sessions.*.notes' => ['nullable', 'string'],
    ]);

    $user = $request->user();

    $buildingQuery = Building::query()
        ->whereKey($validated['building_id']);

    if ($request->routeIs('building-coordinator.*')) {
        $buildingQuery->whereIn(
            'id',
            $user->buildings()->select('buildings.id')
        );
    }

    $building = $buildingQuery->firstOrFail();
    $date = $validated['schedule_date'];
    $submitted = collect($validated['sessions'] ?? []);

    $templates = TrainingSessionTemplate::query()
        ->where('building_id', $building->id)
        ->orderBy('session_number')
        ->get()
        ->keyBy('session_number');

    if ($templates->isEmpty()) {
        throw ValidationException::withMessages([
            'building_id' => 'This building has no session templates.',
        ]);
    }

    DB::transaction(function () use (
        $user,
        $building,
        $date,
        $submitted,
        $templates,
        $conflictService
    ) {
        $schedule = TrainingSchedule::query()
            ->withTrashed()
            ->where('building_id', $building->id)
            ->whereDate('schedule_date', $date)
            ->first();

        if ($schedule && $schedule->trashed()) {
            throw ValidationException::withMessages([
                'schedule_date' => 'A deleted schedule already exists for this building and date. Please contact an administrator before recreating it.',
            ]);
        }

        if (!$schedule) {
            $schedule = new TrainingSchedule();
            $schedule->building_id = $building->id;
            $schedule->schedule_date = $date;
            $schedule->created_by = $user->id;
            $schedule->save();
        }

        $processedTimes = [];
        $submittedTimes = [];

        foreach ($submitted as $item) {
            $number = (int) $item['session_number'];
            $template = $templates->get($number);

            if (!$template) {
                throw ValidationException::withMessages([
                    'sessions' => "Session {$number} is not configured for this building.",
                ]);
            }

            $startTime = $template->start_time;
            $endTime = $template->end_time;
            $key = $this->timeKey($startTime, $endTime);

            if (isset($processedTimes[$key])) {
                throw ValidationException::withMessages([
                    'sessions' => 'A time template was submitted more than once.',
                ]);
            }

            $processedTimes[$key] = true;

            $subject = trim((string) ($item['subject'] ?? ''));
            $roomId = $item['training_room_id'] ?? null;

            // A checked but empty slot is allowed and remains unused.
            if ($subject === '' && !$roomId) {
                continue;
            }

            if ($subject === '' || !$roomId) {
                throw ValidationException::withMessages([
                    'sessions' => "Session {$number} needs both an activity name and Training Media.",
                ]);
            }

            $room = TrainingRoom::query()
                ->whereKey($roomId)
                ->where('building_id', $building->id)
                ->where('status', 'AVAILABLE')
                ->first();

            if (!$room) {
                throw ValidationException::withMessages([
                    'sessions' => "Training Media selected for session {$number} is unavailable.",
                ]);
            }

            $start = Carbon::parse("$date $startTime");
            $end = Carbon::parse("$date $endTime");

            if ($end->lessThanOrEqualTo($start)) {
                throw ValidationException::withMessages([
                    'sessions' => "Session {$number} has an invalid time range.",
                ]);
            }

            $participants = (int) ($item['participant_count'] ?? 0);

            if (
                $room->capacity !== null
                && $participants > $room->capacity
            ) {
                throw ValidationException::withMessages([
                    'sessions' => "Participant count exceeds the capacity of {$room->name}.",
                ]);
            }

            $existing = $schedule->sessions()
                ->where('start_time', $startTime)
                ->where('end_time', $endTime)
                ->first();

            if ($conflictService->hasConflict(
                (int) $room->id,
                $start,
                $end,
                $existing?->id
            )) {
                throw ValidationException::withMessages([
                    'sessions' => "Training Media {$room->name} conflicts with an approved reservation or another session.",
                ]);
            }

            // Check overlapping sessions submitted in this request.
            foreach ($submitted as $other) {
                $otherNumber = (int) ($other['session_number'] ?? 0);

                if ($otherNumber === $number) {
                    continue;
                }

                $otherTemplate = $templates->get($otherNumber);

                if (
                    !$otherTemplate
                    || (int) ($other['training_room_id'] ?? 0) !== (int) $room->id
                    || trim((string) ($other['subject'] ?? '')) === ''
                ) {
                    continue;
                }

                $otherStart = Carbon::parse(
                    "$date {$otherTemplate->start_time}"
                );

                $otherEnd = Carbon::parse(
                    "$date {$otherTemplate->end_time}"
                );

                if (
                    $start->lessThan($otherEnd)
                    && $end->greaterThan($otherStart)
                ) {
                    throw ValidationException::withMessages([
                        'sessions' => "Sessions {$number} and {$otherNumber} overlap in the same Training Media.",
                    ]);
                }
            }

            $data = [
                'training_program_id' => null,
                'training_room_id' => $room->id,
                'subject' => $subject,
                'class_name' => $item['class_name'] ?? '',
                'instructor_name' => $item['instructor_name'] ?? '',
                'participant_count' => $participants,
                'session_date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'notes' => $item['notes'] ?? null,
                'created_by' => $user->id,
            ];

            if ($existing) {
                $existing->update($data);
            } else {
                $schedule->sessions()->create($data);
            }

            $submittedTimes[$key] = true;
        }

        // Reload from the database so every previously saved session is considered.
        $schedule->load('sessions');

        // Delete sessions that were unchecked, omitted, or cleared.
        $schedule->sessions->each(function (TrainingSession $session) use (
            $submittedTimes
        ) {
            $key = $this->timeKey(
                $session->start_time,
                $session->end_time
            );

            if (!isset($submittedTimes[$key])) {
                $session->delete();
            }
        });
    });

    if ($request->routeIs('admin.*')) {
        $routeName = 'admin.training-schedule.daily';
    } elseif ($request->routeIs('building-coordinator.*')) {
        $routeName = 'building-coordinator.training-schedule.daily';
    } else {
        $routeName = 'coordinator.training-schedule.daily';
    }

    return redirect()
        ->route($routeName, [
            'building_id' => $building->id,
            'date' => $date,
        ])
        ->with('success', 'Daily training schedule saved successfully.');
}


    private function timeKey(string $start, string $end): string
    {
        return substr($start, 0, 8) . '|' . substr($end, 0, 8);
    }

    public function export(Request $request)
{
    $validated = $request->validate([
        'building_id' => ['required', 'integer', 'exists:buildings,id'],
        'date' => ['required', 'date_format:Y-m-d'],
    ]);

    $building = Building::query()
        ->findOrFail($validated['building_id']);

    $date = $validated['date'];

    $filename = sprintf(
        'daily-training-schedule-building-%d-%s.xlsx',
        $building->id,
        $date
    );

    return Excel::download(
        new DailyTrainingScheduleExport(
            (int) $building->id,
            $date
        ),
        $filename
    );
}
public function exportMonthly(Request $request)
{
    $validated = $request->validate([
        'building_id' => ['required', 'integer', 'exists:buildings,id'],
        'month' => ['required', 'integer', 'between:1,12'],
        'year' => ['required', 'integer', 'between:2000,2100'],
    ]);

    $building = $this->authorizedReportBuilding(
        $request,
        (int) $validated['building_id']
    );

    $month = (int) $validated['month'];
    $year = (int) $validated['year'];

    $filename = sprintf(
        'monthly-training-schedule-%s-%04d-%02d.xlsx',
        \Illuminate\Support\Str::slug($building->name),
        $year,
        $month
    );

    return Excel::download(
        new MonthlyTrainingScheduleExport(
            (int) $building->id,
            $month,
            $year
        ),
        $filename
    );
}

public function exportYearly(Request $request)
{
    $validated = $request->validate([
        'building_id' => ['required', 'integer', 'exists:buildings,id'],
        'year' => ['required', 'integer', 'between:2000,2100'],
    ]);

    $building = $this->authorizedReportBuilding(
        $request,
        (int) $validated['building_id']
    );

    $year = (int) $validated['year'];

    $filename = sprintf(
        'yearly-training-schedule-%s-%04d.xlsx',
        \Illuminate\Support\Str::slug($building->name),
        $year
    );

    return Excel::download(
        new YearlyTrainingScheduleExport(
            (int) $building->id,
            $year
        ),
        $filename
    );
}

private function authorizedReportBuilding(
    Request $request,
    int $buildingId
): Building {
    $user = $request->user();

    $query = Building::query()->whereKey($buildingId);

    if ($user->role->name === 'Building Coordinator') {
        $query->whereIn(
            'id',
            $user->buildings()->select('buildings.id')
        );
    }

    return $query->firstOrFail();
}

}
