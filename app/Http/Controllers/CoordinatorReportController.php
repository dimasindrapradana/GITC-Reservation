<?php

namespace App\Http\Controllers;

use App\Exports\CoordinatorReservationReportExport;
use App\Models\Building;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CoordinatorReportController extends Controller
{
    public function reservationReport(Request $request): View
    {
        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);

        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }

        if ($year < 2020 || $year > 2100) {
            $year = now()->year;
        }

        $status = $request->string('status')
            ->toString();

        $resourceType = $request->string('resource_type')
            ->toString();

        $building = $request->string('building')
            ->toString();

        $search = $request->string('search')
            ->trim()
            ->toString();

        $query = Reservation::query()
            ->with([
                'user',
                'room.building',
                'trainingRoom.building',
                'field',
            ])
            ->whereYear('starts_at', $year)
            ->whereMonth('starts_at', $month)
            ->when($status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($resourceType === 'room', function ($query) {
                $query->whereNotNull('room_id');
            })
            ->when($resourceType === 'training_room', function ($query) {
                $query->whereNotNull('training_room_id');
            })
            ->when($resourceType === 'field', function ($query) {
                $query->whereNotNull('field_id');
            })
            ->when($building !== '', function ($query) use ($building) {
                $query->where(function ($query) use ($building) {
                    $query
                        ->whereHas('room', function ($query) use ($building) {
                            $query->where('building_id', $building);
                        })
                        ->orWhereHas('trainingRoom', function ($query) use ($building) {
                            $query->where('building_id', $building);
                        });
                });
            })
            ->when($search !== '', function ($query) use ($search) {
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
                        ->orWhereHas('user', function ($query) use ($search) {
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
                        })
                        ->orWhereHas('room', function ($query) use ($search) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            );
                        })
                        ->orWhereHas('trainingRoom', function ($query) use ($search) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            );
                        })
                        ->orWhereHas('field', function ($query) use ($search) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            );
                        });
                });
            });

        $total = (clone $query)->count();

        $pending = (clone $query)
            ->where('status', 'PENDING')
            ->count();

        $approved = (clone $query)
            ->where('status', 'APPROVED')
            ->count();

        $rejected = (clone $query)
            ->where('status', 'REJECTED')
            ->count();

        $cancelled = (clone $query)
            ->where('status', 'CANCELLED')
            ->count();

        $reservations = $query
            ->orderBy('starts_at', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        $buildings = Building::query()
            ->orderBy('name')
            ->get();

        return view('coordinator.reports.reservations', [
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
        ]);
    }

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
            'coordinator.reports.reservation-detail',
            [
                'reservation' => $reservation,
            ]
        );
    }

    public function exportReservationReport(
        Request $request
    ): BinaryFileResponse {
        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);

        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }

        if ($year < 2020 || $year > 2100) {
            $year = now()->year;
        }

        return Excel::download(
            new CoordinatorReservationReportExport(
                month: $month,
                year: $year,
                status: $request->string('status')->toString(),
                resourceType: $request->string('resource_type')->toString(),
                building: $request->string('building')->toString(),
                search: $request->string('search')->trim()->toString(),
            ),
            'GITC_Reservation_Report_' .
                $year .
                '_' .
                str_pad((string) $month, 2, '0', STR_PAD_LEFT) .
                '.xlsx'
        );
    }
}