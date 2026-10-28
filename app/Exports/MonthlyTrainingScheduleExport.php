<?php

namespace App\Exports;

use App\Models\TrainingSchedule;
use App\Models\TrainingSessionTemplate;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class MonthlyTrainingScheduleExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithTitle
{
    protected int $buildingId;
    protected int $month;
    protected int $year;
    protected array $sessionNumbers = [];

    public function __construct(int $buildingId, int $month, int $year)
    {
        $this->buildingId = $buildingId;
        $this->month = $month;
        $this->year = $year;

        $templates = TrainingSessionTemplate::query()
            ->where('building_id', $buildingId)
            ->get();

        foreach ($templates as $template) {
            $key = substr((string) $template->start_time, 0, 5)
                . '|'
                . substr((string) $template->end_time, 0, 5);

            $this->sessionNumbers[$key] = $template->session_number;
        }
    }

    public function collection(): Collection
    {
        $schedules = TrainingSchedule::query()
            ->with(['building', 'sessions.trainingRoom'])
            ->where('building_id', $this->buildingId)
            ->whereYear('schedule_date', $this->year)
            ->whereMonth('schedule_date', $this->month)
            ->orderBy('schedule_date')
            ->get();

        return $schedules
            ->flatMap(function ($schedule) {
                return $schedule->sessions
                    ->sortBy('start_time')
                    ->map(function ($session) use ($schedule) {
                        $session->setRelation('schedule', $schedule);

                        return $session;
                    });
            })
            ->values();
    }

    public function headings(): array
    {
        return [
            'Schedule Date',
            'Building',
            'Session Number',
            'Start Time',
            'End Time',
            'Event Name',
            'Training Media',
            'Group',
            'Instructor',
            'Participants',
            'Notes',
        ];
    }

    public function map($session): array
    {
        $startTime = substr((string) $session->start_time, 0, 5);
        $endTime = substr((string) $session->end_time, 0, 5);

        $key = $startTime . '|' . $endTime;

        return [
            $session->schedule?->schedule_date?->format('Y-m-d') ?? '',
            $session->schedule?->building?->name ?? '',
            $this->sessionNumbers[$key] ?? '',
            $startTime,
            $endTime,
            $session->subject ?? '',
            $session->trainingRoom?->name ?? '',
            $session->class_name ?? '',
            $session->instructor_name ?? '',
            $session->participant_count ?? 0,
            $session->notes ?? '',
        ];
    }

    public function title(): string
    {
        return 'Monthly Training Schedule';
    }
}