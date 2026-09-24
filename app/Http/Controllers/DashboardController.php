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
        $stats = [
            'buildings' => Building::count(),
            'rooms' => Room::count(),
            'training_rooms' => TrainingRoom::count(),
            'fields' => Field::count(),
            'pending_reservations' => Reservation::where('status', 'PENDING')->count(),
            'pending_news' => News::where('status', 'PENDING')->count(),
        ];

        $upcomingReservations = Reservation::query()
            ->with(['user', 'room.building', 'trainingRoom.building', 'field'])
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
}