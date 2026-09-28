<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Field;
use App\Models\News;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\TrainingRoom;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $userRole = auth()->user()?->role?->name;

        if ($userRole === 'Field Coordinator') {
            return $this->fieldCoordinatorDashboard();
        }

        $stats = [
            'buildings' => Building::count(),

            'rooms' => Room::count(),

            'training_rooms' => TrainingRoom::count(),

            'fields' => Field::count(),

            'pending_reservations' => Reservation::query()
                ->where('status', 'PENDING')
                ->count(),

            'pending_news' => News::query()
                ->where('status', 'PENDING')
                ->count(),
        ];

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

        return view('dashboard.index', [
            'stats' => $stats,
            'upcomingReservations' => $upcomingReservations,
        ]);
    }

    private function fieldCoordinatorDashboard(): View
    {
        $todayStart = now()->startOfDay();

        $todayEnd = now()->endOfDay();

        $fieldReservations = Reservation::query()
            ->whereNotNull('field_id');

        $stats = [
            'pending_reservations' => (clone $fieldReservations)
                ->where('status', 'PENDING')
                ->count(),

            'approved_reservations' => (clone $fieldReservations)
                ->where('status', 'APPROVED')
                ->count(),

            'today_reservations' => (clone $fieldReservations)
                ->whereIn('status', [
                    'PENDING',
                    'APPROVED',
                ])
                ->where('starts_at', '<=', $todayEnd)
                ->where('ends_at', '>=', $todayStart)
                ->count(),

            'upcoming_reservations' => (clone $fieldReservations)
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
            ->whereNotNull('field_id')
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
            ->whereNotNull('field_id')
            ->where('status', 'APPROVED')
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at')
            ->limit(5)
            ->get();

        return view('coordinator.field-dashboard', [
            'stats' => $stats,
            'pendingReservations' => $pendingReservations,
            'upcomingReservations' => $upcomingReservations,
        ]);
    }
}