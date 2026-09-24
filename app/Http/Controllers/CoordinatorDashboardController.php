<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\View\View;

class CoordinatorDashboardController extends Controller
{
    public function index(): View
    {
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();

        $stats = [
            'pending_reservations' => Reservation::query()
                ->where('status', 'PENDING')
                ->count(),

            'approved_reservations' => Reservation::query()
                ->where('status', 'APPROVED')
                ->count(),

            'today_reservations' => Reservation::query()
                ->whereIn('status', [
                    'PENDING',
                    'APPROVED',
                ])
                ->where('starts_at', '<=', $todayEnd)
                ->where('ends_at', '>=', $todayStart)
                ->count(),

            'upcoming_reservations' => Reservation::query()
                ->where('status', 'APPROVED')
                ->where('starts_at', '>=', now())
                ->count(),
        ];

        $pendingReservations = Reservation::query()
            ->with([
                'user',
                'room.building',
                'trainingRoom.building',
                'field',
            ])
            ->where('status', 'PENDING')
            ->orderBy('starts_at')
            ->limit(5)
            ->get();

        $upcomingReservations = Reservation::query()
            ->with([
                'user',
                'room.building',
                'trainingRoom.building',
                'field',
            ])
            ->where('status', 'APPROVED')
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at')
            ->limit(5)
            ->get();

        return view('coordinator.dashboard', [
            'stats' => $stats,
            'pendingReservations' => $pendingReservations,
            'upcomingReservations' => $upcomingReservations,
        ]);
    }
}