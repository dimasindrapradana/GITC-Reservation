<?php

namespace App\Http\Controllers;

use App\Exports\BuildingCoordinatorReservationReportExport;
use App\Models\Building;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BuildingCoordinatorReportController extends Controller
{
    public function reservationReport(Request $request): View
    {
        $buildingIds = $this->buildingIds();

        $buildings = Building::query()
            ->whereIn('id', $buildingIds)
            ->orderBy('name')
            ->get();

        $month = $request->integer(
            'month',
            now()->month
        );

        $year = $request->integer(
            'year',
            now()->year
        );

        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }

        if ($year < 2020 || $year > 2100) {
            $year = now()->year;
        }

        $status = $request
            ->string('status')
            ->toString();

        $resourceType = $request
            ->string('resource_type')
            ->toString();

        $building = $request
            ->string('building')
            ->toString();

        $search = trim(
            $request
                ->string('search')
                ->toString()
        );

        $query = $this->reservationQuery(
            $buildingIds
        );

        $this->applyFilters(
            $query,
            $request,
            $month,
            $year
        );

        $reservations = $query
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $statisticsQuery = $this->reservationQuery(
            $buildingIds
        );

        $this->applyFilters(
            $statisticsQuery,
            $request,
            $month,
            $year
        );

        $total = (clone $statisticsQuery)->count();

        $pending = (clone $statisticsQuery)
            ->where('status', 'PENDING')
            ->count();

        $approved = (clone $statisticsQuery)
            ->where('status', 'APPROVED')
            ->count();

        $rejected = (clone $statisticsQuery)
            ->where('status', 'REJECTED')
            ->count();

        $cancelled = (clone $statisticsQuery)
            ->where('status', 'CANCELLED')
            ->count();

        return view(
            'building-coordinator.reports.reservations',
            [
                'reservations' => $reservations,
                'buildings' => $buildings,
                'month' => $month,
                'year' => $year,
                'status' => $status,
                'resourceType' => $resourceType,
                'building' => $building,
                'search' => $search,
                'total' => $total,
                'pending' => $pending,
                'approved' => $approved,
                'rejected' => $rejected,
                'cancelled' => $cancelled,
            ]
        );
    }

    public function exportReservationReport(
        Request $request
    ): BinaryFileResponse {
        $buildingIds = $this->buildingIds();

        $month = $request->integer(
            'month',
            now()->month
        );

        $year = $request->integer(
            'year',
            now()->year
        );

        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }

        if ($year < 2020 || $year > 2100) {
            $year = now()->year;
        }

        $status = $request
            ->string('status')
            ->toString();

        $resourceType = $request
            ->string('resource_type')
            ->toString();

        $building = $request
            ->string('building')
            ->toString();

        $search = $request
            ->string('search')
            ->trim()
            ->toString();

        return Excel::download(
            new BuildingCoordinatorReservationReportExport(
                buildingIds: $buildingIds,
                month: $month,
                year: $year,
                status: $status,
                resourceType: $resourceType,
                building: $building,
                search: $search,
            ),
            'GITC_Building_Coordinator_Reservation_Report_' .
                $year .
                '_' .
                str_pad(
                    (string) $month,
                    2,
                    '0',
                    STR_PAD_LEFT
                ) .
                '.xlsx'
        );
    }

    public function showReservationReport(
        Reservation $reservation
    ): View {
        $buildingIds = $this->buildingIds();

        $reservation->load([
            'user',
            'room.building',
            'room.images',
            'trainingRoom.building',
            'trainingRoom.images',
            'field.building',
            'field.images',
        ]);

        abort_unless(
            $this->reservationBelongsToBuildings(
                $reservation,
                $buildingIds
            ),
            404
        );

        return view(
            'building-coordinator.reports.reservation-detail',
            [
                'reservation' => $reservation,
            ]
        );
    }

    private function buildingIds()
    {
        return auth()
            ->user()
            ->buildings()
            ->pluck('buildings.id');
    }

    private function reservationQuery(
        $buildingIds
    ): Builder {
        return Reservation::query()
            ->with([
                'user',
                'room.building',
                'trainingRoom.building',
                'field.building',
                'field.images',
            ])
            ->where(function (Builder $query) use (
                $buildingIds
            ) {
                $query
                    ->whereHas(
                        'room',
                        function (Builder $query) use (
                            $buildingIds
                        ) {
                            $query->whereIn(
                                'building_id',
                                $buildingIds
                            );
                        }
                    )
                    ->orWhereHas(
                        'trainingRoom',
                        function (Builder $query) use (
                            $buildingIds
                        ) {
                            $query->whereIn(
                                'building_id',
                                $buildingIds
                            );
                        }
                    )
                    ->orWhereHas(
                        'field',
                        function (Builder $query) use (
                            $buildingIds
                        ) {
                            $query->whereIn(
                                'building_id',
                                $buildingIds
                            );
                        }
                    );
            });
    }

    private function applyFilters(
        Builder $query,
        Request $request,
        int $month,
        int $year
    ): void {
        $search = trim(
            $request
                ->string('search')
                ->toString()
        );

        $status = $request
            ->string('status')
            ->toString();

        $resourceType = $request
            ->string('resource_type')
            ->toString();

        $building = $request
            ->string('building')
            ->toString();

        if ($month >= 1 && $month <= 12) {
            $query->whereMonth(
                'starts_at',
                $month
            );
        }

        if ($year >= 2000 && $year <= 2100) {
            $query->whereYear(
                'starts_at',
                $year
            );
        }

        if ($search !== '') {
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
                        function (
                            Builder $query
                        ) use ($search) {
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
                        function (
                            Builder $query
                        ) use ($search) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    )
                    ->orWhereHas(
                        'trainingRoom',
                        function (
                            Builder $query
                        ) use ($search) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    )
                    ->orWhereHas(
                        'field',
                        function (
                            Builder $query
                        ) use ($search) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    );
            });
        }

        if (
            $status !== '' &&
            in_array(
                $status,
                [
                    'PENDING',
                    'APPROVED',
                    'REJECTED',
                    'CANCELLED',
                ],
                true
            )
        ) {
            $query->where(
                'status',
                $status
            );
        }

        if ($resourceType === 'room') {
            $query->whereNotNull('room_id');
        }

        if ($resourceType === 'training_room') {
            $query->whereNotNull(
                'training_room_id'
            );
        }

        if ($resourceType === 'field') {
            $query->whereNotNull('field_id');
        }

        if (
            $building !== '' &&
            ctype_digit($building)
        ) {
            $buildingId = (int) $building;

            $query->where(function (
                Builder $query
            ) use ($buildingId) {
                $query
                    ->whereHas(
                        'room',
                        function (
                            Builder $query
                        ) use ($buildingId) {
                            $query->where(
                                'building_id',
                                $buildingId
                            );
                        }
                    )
                    ->orWhereHas(
                        'trainingRoom',
                        function (
                            Builder $query
                        ) use ($buildingId) {
                            $query->where(
                                'building_id',
                                $buildingId
                            );
                        }
                    )
                    ->orWhereHas(
                        'field',
                        function (
                            Builder $query
                        ) use ($buildingId) {
                            $query->where(
                                'building_id',
                                $buildingId
                            );
                        }
                    );
            });
        }
    }

    private function reservationBelongsToBuildings(
        Reservation $reservation,
        $buildingIds
    ): bool {
        if (
            $reservation->room &&
            $buildingIds->contains(
                $reservation->room->building_id
            )
        ) {
            return true;
        }

        if (
            $reservation->trainingRoom &&
            $buildingIds->contains(
                $reservation->trainingRoom->building_id
            )
        ) {
            return true;
        }

        if (
            $reservation->field &&
            $buildingIds->contains(
                $reservation->field->building_id
            )
        ) {
            return true;
        }

        return false;
    }
}