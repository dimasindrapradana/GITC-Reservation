<?php

namespace App\Exports;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BuildingCoordinatorReservationReportExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    public function __construct(
        private readonly $buildingIds,
        private readonly int $month,
        private readonly int $year,
        private readonly string $status = '',
        private readonly string $resourceType = '',
        private readonly string $building = '',
        private readonly string $search = '',
    ) {
    }

    public function query(): Builder
    {
        $query = Reservation::query()
            ->with([
                'user',
                'room.building',
                'trainingRoom.building',
                'field.building',
            ])
            ->where(function (Builder $query) {
                $query
                    ->whereHas(
                        'room',
                        function (Builder $query) {
                            $query->whereIn(
                                'building_id',
                                $this->buildingIds
                            );
                        }
                    )
                    ->orWhereHas(
                        'trainingRoom',
                        function (Builder $query) {
                            $query->whereIn(
                                'building_id',
                                $this->buildingIds
                            );
                        }
                    )
                    ->orWhereHas(
                        'field',
                        function (Builder $query) {
                            $query->whereIn(
                                'building_id',
                                $this->buildingIds
                            );
                        }
                    );
            })
            ->whereMonth(
                'starts_at',
                $this->month
            )
            ->whereYear(
                'starts_at',
                $this->year
            );

        if ($this->status !== '') {
            $query->where(
                'status',
                $this->status
            );
        }

        if ($this->resourceType === 'room') {
            $query->whereNotNull('room_id');
        }

        if ($this->resourceType === 'training_room') {
            $query->whereNotNull('training_room_id');
        }

        if ($this->resourceType === 'field') {
            $query->whereNotNull('field_id');
        }

        if (
            $this->building !== '' &&
            ctype_digit($this->building)
        ) {
            $buildingId = (int) $this->building;

            $query->where(function (
                Builder $query
            ) use ($buildingId) {
                $query
                    ->whereHas(
                        'room',
                        function (Builder $query) use (
                            $buildingId
                        ) {
                            $query->where(
                                'building_id',
                                $buildingId
                            );
                        }
                    )
                    ->orWhereHas(
                        'trainingRoom',
                        function (Builder $query) use (
                            $buildingId
                        ) {
                            $query->where(
                                'building_id',
                                $buildingId
                            );
                        }
                    )
                    ->orWhereHas(
                        'field',
                        function (Builder $query) use (
                            $buildingId
                        ) {
                            $query->where(
                                'building_id',
                                $buildingId
                            );
                        }
                    );
            });
        }

        if ($this->search !== '') {
            $search = $this->search;

            $query->where(function (
                Builder $query
            ) use ($search) {
                $query
                    ->where(
                        'reservation_number',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'event_name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'booker_name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'instructor',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'description',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhereHas(
                        'user',
                        function (Builder $query) use (
                            $search
                        ) {
                            $query
                                ->where(
                                    'name',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'employee_number',
                                    'like',
                                    '%' . $search . '%'
                                );
                        }
                    )
                    ->orWhereHas(
                        'room',
                        function (Builder $query) use (
                            $search
                        ) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    )
                    ->orWhereHas(
                        'trainingRoom',
                        function (Builder $query) use (
                            $search
                        ) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    )
                    ->orWhereHas(
                        'field',
                        function (Builder $query) use (
                            $search
                        ) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    );
            });
        }

        return $query
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');
    }

    public function headings(): array
    {
        return [
            'Reservation Number',
            'User',
            'Employee Number',
            'Event Name',
            'Booker Name',
            'Total Person',
            'Resource Type',
            'Resource',
            'Building',
            'Starts At',
            'Ends At',
            'Instructor',
            'Status',
            'Additional Info',
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
            $resourceName =
                $reservation->trainingRoom->name;
            $buildingName =
                $reservation->trainingRoom->building?->name
                ?? '-';
        } elseif ($reservation->field !== null) {
            $resourceType = 'Field';
            $resourceName = $reservation->field->name;
            $buildingName =
                $reservation->field->building?->name ?? '-';
        }

        return [
            $reservation->reservation_number,
            $reservation->user?->name ?? '-',
            $reservation->user?->employee_number ?? '-',
            $reservation->event_name,
            $reservation->booker_name,
            $reservation->total_person,
            $resourceType,
            $resourceName,
            $buildingName,
            $reservation->starts_at?->format(
                'Y-m-d H:i:s'
            ) ?? '-',
            $reservation->ends_at?->format(
                'Y-m-d H:i:s'
            ) ?? '-',
            $reservation->instructor ?: '-',
            $reservation->status,
            $reservation->description ?: '-',
        ];
    }
}