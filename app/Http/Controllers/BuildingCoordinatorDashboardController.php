<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\View\View;

class BuildingCoordinatorDashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $buildings = $user->buildings()
            ->with(['rooms', 'trainingRooms'])
            ->get();

        abort_if($buildings->isEmpty(), 403);

        $buildingIds = $buildings->pluck('id');

        $stats = [
            'pending_reservations' => Reservation::query()
                ->where('status', 'PENDING')
                ->where(function ($query) use ($buildingIds) {
                    $query
                        ->whereHas('room', function ($query) use ($buildingIds) {
                            $query->whereIn('building_id', $buildingIds);
                        })
                        ->orWhereHas('trainingRoom', function ($query) use ($buildingIds) {
                            $query->whereIn('building_id', $buildingIds);
                        });
                })
                ->count(),

            'approved_reservations' => Reservation::query()
                ->where('status', 'APPROVED')
                ->where(function ($query) use ($buildingIds) {
                    $query
                        ->whereHas('room', function ($query) use ($buildingIds) {
                            $query->whereIn('building_id', $buildingIds);
                        })
                        ->orWhereHas('trainingRoom', function ($query) use ($buildingIds) {
                            $query->whereIn('building_id', $buildingIds);
                        });
                })
                ->count(),

            'today_reservations' => Reservation::query()
                ->whereIn('status', ['PENDING', 'APPROVED'])
                ->where('starts_at', '<=', now()->endOfDay())
                ->where('ends_at', '>=', now()->startOfDay())
                ->where(function ($query) use ($buildingIds) {
                    $query
                        ->whereHas('room', function ($query) use ($buildingIds) {
                            $query->whereIn('building_id', $buildingIds);
                        })
                        ->orWhereHas('trainingRoom', function ($query) use ($buildingIds) {
                            $query->whereIn('building_id', $buildingIds);
                        });
                })
                ->count(),

            'upcoming_reservations' => Reservation::query()
                ->where('status', 'APPROVED')
                ->where('starts_at', '>=', now())
                ->where(function ($query) use ($buildingIds) {
                    $query
                        ->whereHas('room', function ($query) use ($buildingIds) {
                            $query->whereIn('building_id', $buildingIds);
                        })
                        ->orWhereHas('trainingRoom', function ($query) use ($buildingIds) {
                            $query->whereIn('building_id', $buildingIds);
                        });
                })
                ->count(),
        ];

        $pendingReservations = Reservation::query()
            ->with([
                'user',
                'room.building',
                'trainingRoom.building',
            ])
            ->where('status', 'PENDING')
            ->where(function ($query) use ($buildingIds) {
                $query
                    ->whereHas('room', function ($query) use ($buildingIds) {
                        $query->whereIn('building_id', $buildingIds);
                    })
                    ->orWhereHas('trainingRoom', function ($query) use ($buildingIds) {
                        $query->whereIn('building_id', $buildingIds);
                    });
            })
            ->orderBy('starts_at')
            ->limit(5)
            ->get();

        $upcomingReservations = Reservation::query()
            ->with([
                'user',
                'room.building',
                'trainingRoom.building',
            ])
            ->where('status', 'APPROVED')
            ->where('starts_at', '>=', now())
            ->where(function ($query) use ($buildingIds) {
                $query
                    ->whereHas('room', function ($query) use ($buildingIds) {
                        $query->whereIn('building_id', $buildingIds);
                    })
                    ->orWhereHas('trainingRoom', function ($query) use ($buildingIds) {
                        $query->whereIn('building_id', $buildingIds);
                    });
            })
            ->orderBy('starts_at')
            ->limit(5)
            ->get();

        return view('building-coordinator.dashboard', [
            'buildings' => $buildings,
            'stats' => $stats,
            'pendingReservations' => $pendingReservations,
            'upcomingReservations' => $upcomingReservations,
        ]);
    }
}