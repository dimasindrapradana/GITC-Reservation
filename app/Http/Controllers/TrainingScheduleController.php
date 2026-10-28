<?php

namespace App\Http\Controllers;

use App\Models\TrainingProgram;
use App\Models\TrainingRoom;
use App\Models\TrainingSession;
use App\Services\TrainingScheduleConflictService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Building;
use App\Models\TrainingSchedule;
use App\Models\TrainingSessionTemplate;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TrainingScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $query = TrainingSession::query()
            ->with(['program', 'trainingRoom', 'creator'])
            ->orderBy('session_date')
            ->orderBy('start_time');

        if ($request->filled('date')) {
            $query->whereDate('session_date', $request->date);
        }

        if ($request->filled('training_room_id')) {
            $query->where(
                'training_room_id',
                $request->integer('training_room_id')
            );
        }

        if ($request->filled('program')) {
            $query->whereHas('program', function ($programQuery) use ($request) {
                $programQuery->where(
                    'program_name',
                    'like',
                    '%' . $request->string('program')->toString() . '%'
                );
            });
        }

        $sessions = (clone $query)->paginate(15)->withQueryString();

        $calendarSessions = TrainingSession::query()
            ->with(['program', 'trainingRoom', 'creator'])
            ->orderBy('session_date')
            ->orderBy('start_time')
            ->get();

        return view('training-schedule.index', [
            'sessions' => $sessions,
            'calendarSessions' => $calendarSessions,
            'programs' => TrainingProgram::query()
                ->with('sessions')
                ->orderBy('program_name')
                ->get(),
            'trainingRooms' => TrainingRoom::query()
                ->where('status', 'AVAILABLE')
                ->orderBy('name')
                ->get(),
            'filters' => $request->only([
                'date',
                'training_room_id',
                'program',
            ]),
        ]);
    }

    public function store(
        Request $request,
        TrainingScheduleConflictService $conflicts
    ): JsonResponse|RedirectResponse {
        $validated = $request->validate([
            'program_name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'sessions' => ['required', 'array', 'min:1'],
            'sessions.*.subject' => ['required', 'string', 'max:200'],
            'sessions.*.class_name' => ['required', 'string', 'max:150'],
            'sessions.*.instructor_name' => ['required', 'string', 'max:150'],
            'sessions.*.participant_count' => ['required', 'integer', 'min:1'],
            'sessions.*.training_room_id' => [
                'required',
                'integer',
                'exists:training_rooms,id',
            ],
            'sessions.*.session_date' => ['required', 'date'],
            'sessions.*.start_time' => ['required', 'date_format:H:i'],
            'sessions.*.end_time' => ['required', 'date_format:H:i'],
            'sessions.*.notes' => ['nullable', 'string'],
        ]);

        $preparedSessions = [];

        foreach ($validated['sessions'] as $index => $session) {
            $room = TrainingRoom::query()
                ->where('status', 'AVAILABLE')
                ->find($session['training_room_id']);

            if (!$room) {
                throw ValidationException::withMessages([
                    "sessions.$index.training_room_id" =>
                        'The selected Training Media is not available.',
                ]);
            }

            if ($session['participant_count'] > $room->capacity) {
                throw ValidationException::withMessages([
                    "sessions.$index.participant_count" =>
                        "The participant count exceeds {$room->name}'s capacity.",
                ]);
            }

            $start = Carbon::parse(
                $session['session_date'] . ' ' . $session['start_time']
            );

            $end = Carbon::parse(
                $session['session_date'] . ' ' . $session['end_time']
            );

            if (!$start->lt($end)) {
                throw ValidationException::withMessages([
                    "sessions.$index.end_time" =>
                        'The end time must be later than the start time.',
                ]);
            }

            if ($conflicts->hasConflict(
                (int) $session['training_room_id'],
                $start,
                $end
            )) {
                throw ValidationException::withMessages([
                    "sessions.$index.training_room_id" =>
                        'This Training Media already has an overlapping approved reservation or training session.',
                ]);
            }

            foreach ($preparedSessions as $previous) {
                if (
                    (int) $previous['training_room_id']
                        !== (int) $session['training_room_id']
                    || $previous['session_date'] !== $session['session_date']
                ) {
                    continue;
                }

                $overlaps =
                    $session['start_time'] < $previous['end_time']
                    && $session['end_time'] > $previous['start_time'];

                if ($overlaps) {
                    throw ValidationException::withMessages([
                        "sessions.$index.training_room_id" =>
                            'Two sessions in this program cannot use the same Training Media at overlapping times.',
                    ]);
                }
            }

            $preparedSessions[] = $session;
        }

        DB::transaction(function () use ($validated, $preparedSessions) {
            $program = TrainingProgram::create([
                'program_name' => $validated['program_name'],
                'description' => $validated['description'] ?? null,
                'status' => 'ACTIVE',
                'created_by' => auth()->id(),
            ]);

            foreach ($preparedSessions as $session) {
                $program->sessions()->create([
                    'subject' => $session['subject'],
                    'class_name' => $session['class_name'],
                    'instructor_name' => $session['instructor_name'],
                    'participant_count' => $session['participant_count'],
                    'training_room_id' => $session['training_room_id'],
                    'session_date' => $session['session_date'],
                    'start_time' => $session['start_time'],
                    'end_time' => $session['end_time'],
                    'notes' => $session['notes'] ?? null,
                    'created_by' => auth()->id(),
                ]);
            }
        });

        $redirectRoute = $this->scheduleIndexRoute();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Training program and sessions created successfully.',
                'redirect' => route($redirectRoute),
            ]);
        }

        return redirect()
            ->route($redirectRoute)
            ->with(
                'success',
                'Training program and sessions created successfully.'
            );
    }

    public function edit(TrainingProgram $program): JsonResponse
    {
        $program->load(['sessions.trainingRoom']);

        return response()->json([
            'program' => [
                'id' => $program->id,
                'program_name' => $program->program_name,
                'description' => $program->description,
            ],
            'sessions' => $program->sessions->map(function ($session) {
                return [
                    'id' => $session->id,
                    'subject' => $session->subject,
                    'class_name' => $session->class_name,
                    'instructor_name' => $session->instructor_name,
                    'participant_count' => $session->participant_count,
                    'training_room_id' => $session->training_room_id,
                    'session_date' => $session->session_date?->format('Y-m-d'),
                    'start_time' => substr((string) $session->start_time, 0, 5),
                    'end_time' => substr((string) $session->end_time, 0, 5),
                    'notes' => $session->notes,
                ];
            })->values(),
        ]);
    }

    public function update(
        Request $request,
        TrainingProgram $program,
        TrainingScheduleConflictService $conflicts
    ): JsonResponse|RedirectResponse {
        $validated = $request->validate([
            'program_name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'sessions' => ['required', 'array', 'min:1'],
            'sessions.*.id' => ['nullable', 'integer'],
            'sessions.*.subject' => ['required', 'string', 'max:200'],
            'sessions.*.class_name' => ['required', 'string', 'max:150'],
            'sessions.*.instructor_name' => ['required', 'string', 'max:150'],
            'sessions.*.participant_count' => ['required', 'integer', 'min:1'],
            'sessions.*.training_room_id' => [
                'required',
                'integer',
                'exists:training_rooms,id',
            ],
            'sessions.*.session_date' => ['required', 'date'],
            'sessions.*.start_time' => ['required', 'date_format:H:i'],
            'sessions.*.end_time' => ['required', 'date_format:H:i'],
            'sessions.*.notes' => ['nullable', 'string'],
        ]);

        $existingSessions = $program->sessions()->get()->keyBy('id');
        $preparedSessions = [];
        $submittedIds = [];

        foreach ($validated['sessions'] as $index => $session) {
            if (!empty($session['id'])) {
                $sessionId = (int) $session['id'];

                if (!$existingSessions->has($sessionId)) {
                    throw ValidationException::withMessages([
                        "sessions.$index.id" =>
                            'This session does not belong to the selected program.',
                    ]);
                }

                $submittedIds[] = $sessionId;
            }

            $room = TrainingRoom::query()
                ->where('status', 'AVAILABLE')
                ->find($session['training_room_id']);

            if (!$room) {
                throw ValidationException::withMessages([
                    "sessions.$index.training_room_id" =>
                        'The selected Training Media is not available.',
                ]);
            }

            if ($session['participant_count'] > $room->capacity) {
                throw ValidationException::withMessages([
                    "sessions.$index.participant_count" =>
                        "The participant count exceeds {$room->name}'s capacity.",
                ]);
            }

            $start = Carbon::parse(
                $session['session_date'] . ' ' . $session['start_time']
            );

            $end = Carbon::parse(
                $session['session_date'] . ' ' . $session['end_time']
            );

            if (!$start->lt($end)) {
                throw ValidationException::withMessages([
                    "sessions.$index.end_time" =>
                        'The end time must be later than the start time.',
                ]);
            }

            $currentSessionId = !empty($session['id'])
                ? (int) $session['id']
                : null;

            if ($conflicts->hasConflict(
                (int) $session['training_room_id'],
                $start,
                $end,
                $currentSessionId
            )) {
                throw ValidationException::withMessages([
                    "sessions.$index.training_room_id" =>
                        'This Training Media already has an overlapping approved reservation or training session.',
                ]);
            }

            foreach ($preparedSessions as $previous) {
                if (
                    (int) $previous['training_room_id']
                        !== (int) $session['training_room_id']
                    || $previous['session_date'] !== $session['session_date']
                ) {
                    continue;
                }

                $overlaps =
                    $session['start_time'] < $previous['end_time']
                    && $session['end_time'] > $previous['start_time'];

                if ($overlaps) {
                    throw ValidationException::withMessages([
                        "sessions.$index.training_room_id" =>
                            'Two sessions in this program cannot use the same Training Media at overlapping times.',
                    ]);
                }
            }

            $preparedSessions[] = $session;
        }

        DB::transaction(function () use (
            $validated,
            $program,
            $preparedSessions,
            $existingSessions,
            $submittedIds
        ) {
            $program->update([
                'program_name' => $validated['program_name'],
                'description' => $validated['description'] ?? null,
            ]);

            foreach ($preparedSessions as $session) {
                $attributes = [
                    'subject' => $session['subject'],
                    'class_name' => $session['class_name'],
                    'instructor_name' => $session['instructor_name'],
                    'participant_count' => $session['participant_count'],
                    'training_room_id' => $session['training_room_id'],
                    'session_date' => $session['session_date'],
                    'start_time' => $session['start_time'],
                    'end_time' => $session['end_time'],
                    'notes' => $session['notes'] ?? null,
                ];

                if (!empty($session['id'])) {
                    $existingSessions->get((int) $session['id'])
                        ->update($attributes);
                } else {
                    $program->sessions()->create($attributes + [
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            $idsToDelete = $existingSessions->keys()
                ->map(fn ($id) => (int) $id)
                ->diff($submittedIds);

            if ($idsToDelete->isNotEmpty()) {
                $program->sessions()
                    ->whereIn('id', $idsToDelete)
                    ->delete();
            }
        });

        $redirectRoute = $this->scheduleIndexRoute();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Training program updated successfully.',
                'redirect' => route($redirectRoute),
            ]);
        }

        return redirect()
            ->route($redirectRoute)
            ->with('success', 'Training program updated successfully.');
    }

    public function updateMedia(
        Request $request,
        TrainingSession $session,
        TrainingScheduleConflictService $conflicts
    ): RedirectResponse {
        $validated = $request->validate([
            'training_room_id' => [
                'required',
                'integer',
                'exists:training_rooms,id',
            ],
        ]);

        $newRoom = TrainingRoom::query()
            ->where('status', 'AVAILABLE')
            ->find($validated['training_room_id']);

        if (!$newRoom) {
            throw ValidationException::withMessages([
                'training_room_id' =>
                    'The selected Training Media is not available.',
            ]);
        }

        if ($session->participant_count > $newRoom->capacity) {
            throw ValidationException::withMessages([
                'training_room_id' =>
                    'The selected Training Media does not have enough capacity.',
            ]);
        }

        $start = Carbon::parse(
            $session->session_date->toDateString()
            . ' ' . $session->start_time
        );

        $end = Carbon::parse(
            $session->session_date->toDateString()
            . ' ' . $session->end_time
        );

        if (
            (int) $session->training_room_id !== (int) $newRoom->id
            && $conflicts->hasConflict(
                (int) $newRoom->id,
                $start,
                $end,
                $session->id
            )
        ) {
            throw ValidationException::withMessages([
                'training_room_id' =>
                    'The selected Training Media has a schedule conflict.',
            ]);
        }

        $session->update([
            'training_room_id' => $newRoom->id,
        ]);

        return redirect()
            ->route($this->scheduleIndexRoute())
            ->with(
                'success',
                'Training Media updated successfully.'
            );
    }

    private function scheduleIndexRoute(): string
    {
        return request()->routeIs('building-coordinator.*')
            ? 'building-coordinator.training-schedule.index'
            : 'coordinator.training-schedule.index';
    }
}
