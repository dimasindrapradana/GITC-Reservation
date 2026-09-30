<?php

namespace App\Exports;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class CoordinatorReservationReportExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithEvents
{
    public function __construct(
        private string|int $month,
        private int $year,
        private string $status = '',
        private string $resourceType = '',
        private string $building = '',
        private string $search = '',
    ) {
    }

    public function query(): Builder
    {
        return $this->baseQuery()
            ->orderBy('starts_at', 'asc');
    }

    public function headings(): array
    {
        return [
            'Reservation Number',
            'User',
            'Employee Number',
            'Resource Type',
            'Resource',
            'Building',
            'Starts At',
            'Ends At',
            'Instructor',
            'Status',
            'Description',
        ];
    }

    public function map($reservation): array
    {
        $resourceType = '-';
        $resourceName = '-';
        $buildingName = '-';

        if ($reservation->room !== null) {
            $resourceType = 'Room';
            $resourceName = $reservation->room->name;
            $buildingName =
                $reservation->room->building?->name ?? '-';
        } elseif ($reservation->trainingRoom !== null) {
            $resourceType = 'Media Training';
            $resourceName = $reservation->trainingRoom->name;
            $buildingName =
                $reservation->trainingRoom->building?->name ?? '-';
        } elseif ($reservation->field !== null) {
            $resourceType = 'Field';
            $resourceName = $reservation->field->name;
        }

        return [
            $reservation->reservation_number,
            $reservation->user?->name ?? '-',
            $reservation->user?->employee_number ?? '-',
            $resourceType,
            $resourceName,
            $buildingName,
            $reservation->starts_at?->format('d M Y H:i'),
            $reservation->ends_at?->format('d M Y H:i'),
            $reservation->instructor ?: '-',
            $reservation->status,
            $reservation->description ?: '-',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $baseQuery = $this->baseQuery();

                $total = (clone $baseQuery)->count();

                $pending = (clone $baseQuery)
                    ->where('status', 'PENDING')
                    ->count();

                $approved = (clone $baseQuery)
                    ->where('status', 'APPROVED')
                    ->count();

                $rejected = (clone $baseQuery)
                    ->where('status', 'REJECTED')
                    ->count();

                $cancelled = (clone $baseQuery)
                    ->where('status', 'CANCELLED')
                    ->count();

                if ($this->month === 'all') {
                    $periodName = 'All Months ' . $this->year;
                    $periodRange =
                        '01 January ' . $this->year .
                        ' — 31 December ' . $this->year;
                } else {
                    $monthNumber = (int) $this->month;

                    $periodName = date(
                        'F',
                        strtotime(
                            sprintf(
                                '%04d-%02d-01',
                                $this->year,
                                $monthNumber
                            )
                        )
                    ) . ' ' . $this->year;

                    $periodRange = date(
                        'd F Y',
                        strtotime(
                            sprintf(
                                '%04d-%02d-01',
                                $this->year,
                                $monthNumber
                            )
                        )
                    ) . ' — ' . date(
                        'd F Y',
                        strtotime(
                            sprintf(
                                '%04d-%02d-01 +1 month -1 day',
                                $this->year,
                                $monthNumber
                            )
                        )
                    );
                }

                /*
                 * Add report information above the table.
                 */
                $sheet->insertNewRowBefore(1, 5);

                $sheet->setCellValue(
                    'A1',
                    'GITC INFO — RESERVATION REPORT'
                );

                $sheet->setCellValue(
                    'A2',
                    $periodName
                );

                $sheet->setCellValue(
                    'A3',
                    $periodRange
                );

                $sheet->setCellValue(
                    'A4',
                    'Total: ' . $total .
                    ' | Pending: ' . $pending .
                    ' | Approved: ' . $approved .
                    ' | Rejected: ' . $rejected .
                    ' | Cancelled: ' . $cancelled
                );

                $sheet->getStyle('A1')
                    ->getFont()
                    ->setBold(true)
                    ->setSize(14);

                $sheet->getStyle('A2:A4')
                    ->getFont()
                    ->setBold(true);

                $sheet->getStyle('A1:A4')
                    ->getAlignment()
                    ->setVertical(
                        Alignment::VERTICAL_CENTER
                    );

                /*
                 * Detail table header is row 6.
                 */
                $sheet->getStyle('A6:K6')
                    ->getFont()
                    ->setBold(true);

                $sheet->getStyle('A6:K6')
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('EAF5FB');

                $highestRow = $sheet->getHighestRow();

                if ($highestRow >= 7) {
                    $sheet->setAutoFilter(
                        'A6:K' . $highestRow
                    );
                }

                $widths = [
                    'A' => 24,
                    'B' => 22,
                    'C' => 18,
                    'D' => 18,
                    'E' => 25,
                    'F' => 20,
                    'G' => 20,
                    'H' => 20,
                    'I' => 25,
                    'J' => 14,
                    'K' => 35,
                ];

                foreach ($widths as $column => $width) {
                    $sheet
                        ->getColumnDimension($column)
                        ->setWidth($width);
                }

                $sheet->freezePane('A7');

                $sheet->getPageSetup()
                    ->setOrientation(
                        PageSetup::ORIENTATION_LANDSCAPE
                    )
                    ->setPaperSize(
                        PageSetup::PAPERSIZE_A4
                    )
                    ->setFitToWidth(1)
                    ->setFitToHeight(0);
            },
        ];
    }

    private function baseQuery(): Builder
    {
        return Reservation::query()
            ->with([
                'user',
                'room.building',
                'trainingRoom.building',
                'field',
            ])
            ->whereYear(
                'starts_at',
                $this->year
            )
            ->when(
                $this->month !== 'all',
                function (Builder $query) {
                    $query->whereMonth(
                        'starts_at',
                        (int) $this->month
                    );
                }
            )
            ->when(
                $this->status !== '',
                function (Builder $query) {
                    $query->where(
                        'status',
                        $this->status
                    );
                }
            )
            ->when(
                $this->resourceType !== '',
                function (Builder $query) {
                    if ($this->resourceType === 'room') {
                        $query->whereNotNull('room_id');
                    }

                    if ($this->resourceType === 'training_room') {
                        $query->whereNotNull(
                            'training_room_id'
                        );
                    }

                    if ($this->resourceType === 'field') {
                        $query->whereNotNull('field_id');
                    }
                }
            )
            ->when(
                $this->building !== '',
                function (Builder $query) {
                    $query->where(function (
                        Builder $query
                    ) {
                        $query
                            ->whereHas(
                                'room.building',
                                function (
                                    Builder $query
                                ) {
                                    $query->where(
                                        'id',
                                        $this->building
                                    );
                                }
                            )
                            ->orWhereHas(
                                'trainingRoom.building',
                                function (
                                    Builder $query
                                ) {
                                    $query->where(
                                        'id',
                                        $this->building
                                    );
                                }
                            );
                    });
                }
            )
            ->when(
                $this->search !== '',
                function (Builder $query) {
                    $query->where(function (
                        Builder $query
                    ) {
                        $query
                            ->where(
                                'reservation_number',
                                'like',
                                '%' .
                                $this->search .
                                '%'
                            )
                            ->orWhere(
                                'event_name',
                                'like',
                                '%' .
                                $this->search .
                                '%'
                            )
                            ->orWhere(
                                'booker_name',
                                'like',
                                '%' .
                                $this->search .
                                '%'
                            )
                            ->orWhere(
                                'instructor',
                                'like',
                                '%' .
                                $this->search .
                                '%'
                            )
                            ->orWhere(
                                'description',
                                'like',
                                '%' .
                                $this->search .
                                '%'
                            )
                            ->orWhereHas(
                                'user',
                                function (
                                    Builder $query
                                ) {
                                    $query
                                        ->where(
                                            'name',
                                            'like',
                                            '%' .
                                            $this->search .
                                            '%'
                                        )
                                        ->orWhere(
                                            'employee_number',
                                            'like',
                                            '%' .
                                            $this->search .
                                            '%'
                                        );
                                }
                            )
                            ->orWhereHas(
                                'room',
                                function (
                                    Builder $query
                                ) {
                                    $query->where(
                                        'name',
                                        'like',
                                        '%' .
                                        $this->search .
                                        '%'
                                    );
                                }
                            )
                            ->orWhereHas(
                                'trainingRoom',
                                function (
                                    Builder $query
                                ) {
                                    $query->where(
                                        'name',
                                        'like',
                                        '%' .
                                        $this->search .
                                        '%'
                                    );
                                }
                            )
                            ->orWhereHas(
                                'field',
                                function (
                                    Builder $query
                                ) {
                                    $query->where(
                                        'name',
                                        'like',
                                        '%' .
                                        $this->search .
                                        '%'
                                    );
                                }
                            );
                    });
                }
            );
    }
}