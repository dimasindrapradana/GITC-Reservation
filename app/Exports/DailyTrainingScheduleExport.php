<?php

namespace App\Exports;

use App\Models\TrainingSchedule;
use App\Models\TrainingSessionTemplate;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DailyTrainingScheduleExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithTitle,
    WithColumnFormatting
{
    protected int $buildingId;

    protected string $date;

    protected array $sessionNumbers = [];

    public function __construct(int $buildingId, string $date)
    {
        $this->buildingId = $buildingId;
        $this->date = $date;

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
        $schedule = TrainingSchedule::query()
            ->with([
                'building',
                'sessions.trainingRoom',
            ])
            ->where('building_id', $this->buildingId)
            ->whereDate('schedule_date', $this->date)
            ->first();

        if (! $schedule) {
            return collect();
        }

        return $schedule->sessions
            ->sortBy('start_time')
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
            $session->session_date?->format('Y-m-d') ?? $this->date,
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

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'C' => NumberFormat::FORMAT_GENERAL,
            'J' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function title(): string
    {
        return 'Daily Training Schedule';
    }
}
