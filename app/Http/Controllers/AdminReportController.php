<?php

namespace App\Http\Controllers;

use App\Exports\AdminReservationReportExport;
use App\Models\Building;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminReportController extends Controller
{
    /**
     * Reservation report for Admin.
     */
    public function reservationReport(Request $request): View
    {
        /*
         * Month can be:
         * - "all"
         * - 1 to 12
         */
        $month = $request->input(
            'month',
            'all'
        );

        if ($month !== 'all') {
            $month = (int) $month;

            if ($month < 1 || $month > 12) {
                $month = 'all';
            }
        }

        /*
         * Year.
         */
        $year = $request->integer(
            'year',
            now()->year
        );

        if ($year < 2020 || $year > 2100) {
            $year = now()->year;
        }

        /*
         * Other filters.
         */
        $status = $request->string('status')
            ->toString();

        $resourceType = $request->string('resource_type')
            ->toString();

        $building = $request->string('building')
            ->toString();

        $search = $request->string('search')
            ->trim()
            ->toString();

        /*
         * Base reservation query.
         */
        $query = Reservation::query()
            ->with([
                'user',
                'room.building',
                'trainingRoom.building',
                'field',
            ])

            /*
             * Always filter by selected year.
             */
            ->whereYear(
                'starts_at',
                $year
            )

            /*
             * Only filter month when
             * a specific month is selected.
             */
            ->when(
                $month !== 'all',
                function ($query) use ($month) {
                    $query->whereMonth(
                        'starts_at',
                        $month
                    );
                }
            )

            /*
             * Status filter.
             */
            ->when(
                $status !== '',
                function ($query) use ($status) {
                    $query->where(
                        'status',
                        $status
                    );
                }
            )

            /*
             * Resource type filter.
             */
            ->when(
                $resourceType === 'room',
                function ($query) {
                    $query->whereNotNull(
                        'room_id'
                    );
                }
            )

            ->when(
                $resourceType === 'training_room',
                function ($query) {
                    $query->whereNotNull(
                        'training_room_id'
                    );
                }
            )

            ->when(
                $resourceType === 'field',
                function ($query) {
                    $query->whereNotNull(
                        'field_id'
                    );
                }
            )

            /*
             * Building filter.
             */
            ->when(
                $building !== '',
                function ($query) use ($building) {
                    $query->where(function ($query) use ($building) {
                        $query
                            ->whereHas(
                                'room',
                                function ($query) use ($building) {
                                    $query->where(
                                        'building_id',
                                        $building
                                    );
                                }
                            )
                            ->orWhereHas(
                                'trainingRoom',
                                function ($query) use ($building) {
                                    $query->where(
                                        'building_id',
                                        $building
                                    );
                                }
                            );
                    });
                }
            )

            /*
             * Search filter.
             */
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {

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
                                function ($query) use ($search) {
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
                                function ($query) use ($search) {
                                    $query->where(
                                        'name',
                                        'like',
                                        '%' . $search . '%'
                                    );
                                }
                            )

                            ->orWhereHas(
                                'trainingRoom',
                                function ($query) use ($search) {
                                    $query->where(
                                        'name',
                                        'like',
                                        '%' . $search . '%'
                                    );
                                }
                            )

                            ->orWhereHas(
                                'field',
                                function ($query) use ($search) {
                                    $query->where(
                                        'name',
                                        'like',
                                        '%' . $search . '%'
                                    );
                                }
                            );
                    });
                }
            );

        /*
         * Summary statistics.
         */
        $total = (clone $query)
            ->count();

        $pending = (clone $query)
            ->where(
                'status',
                'PENDING'
            )
            ->count();

        $approved = (clone $query)
            ->where(
                'status',
                'APPROVED'
            )
            ->count();

        $rejected = (clone $query)
            ->where(
                'status',
                'REJECTED'
            )
            ->count();

        $cancelled = (clone $query)
            ->where(
                'status',
                'CANCELLED'
            )
            ->count();

        /*
         * Reservation list.
         */
        $reservations = $query
            ->orderBy(
                'starts_at',
                'asc'
            )
            ->orderBy(
                'id',
                'asc'
            )
            ->paginate(10)
            ->withQueryString();

        /*
         * Buildings for filter.
         */
        $buildings = Building::query()
            ->orderBy('name')
            ->get();

        return view(
            'admin.reports.reservations',
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

    /**
     * Show reservation report detail.
     */
    public function showReservationReport(
        Reservation $reservation
    ): View {
        $reservation->load([
            'user',
            'room.building',
            'room.images',
            'trainingRoom.building',
            'trainingRoom.images',
            'field.images',
        ]);

        return view(
            'admin.reports.reservation-detail',
            [
                'reservation' => $reservation,
            ]
        );
    }

    /**
     * Export reservation report.
     */
    public function exportReservationReport(
        Request $request
    ): BinaryFileResponse {
        /*
         * IMPORTANT:
         * Month can be "all" or 1-12.
         *
         * Do not use:
         * $request->integer('month')
         *
         * because "all" must remain a string.
         */
        $month = $request->input(
            'month',
            'all'
        );

        /*
         * Convert only specific month values
         * into integers.
         */
        if ($month !== 'all') {
            $month = (int) $month;

            if ($month < 1 || $month > 12) {
                $month = 'all';
            }
        }

        /*
         * Year.
         */
        $year = $request->integer(
            'year',
            now()->year
        );

        if ($year < 2020 || $year > 2100) {
            $year = now()->year;
        }

        /*
         * Other filters.
         */
        $status = $request->string('status')
            ->toString();

        $resourceType = $request->string('resource_type')
            ->toString();

        $building = $request->string('building')
            ->toString();

        $search = $request->string('search')
            ->trim()
            ->toString();

        /*
         * Filename period.
         */
        $periodName = $month === 'all'
            ? 'All_Months'
            : str_pad(
                (string) $month,
                2,
                '0',
                STR_PAD_LEFT
            );

        /*
         * Export using exactly the
         * same filters as the report page.
         */
        return Excel::download(
            new AdminReservationReportExport(
                month: $month,
                year: $year,
                status: $status,
                resourceType: $resourceType,
                building: $building,
                search: $search,
            ),
            'GITC_Reservation_Report_' .
                $year .
                '_' .
                $periodName .
                '.xlsx'
        );
    }
}