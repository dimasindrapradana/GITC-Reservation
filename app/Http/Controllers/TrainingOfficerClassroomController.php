<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\View\View;

class TrainingOfficerClassroomController extends Controller
{
    public function index(): View
    {
        $buildings = Building::query()
            ->with([
                'rooms' => function ($query) {
                    $query->where('status', 'AVAILABLE')
                        ->with('images');
                },
            ])
            ->whereHas('rooms', function ($query) {
                $query->where('status', 'AVAILABLE');
            })
            ->orderBy('name')
            ->get();

        return view('training-officer-classroom.index', [
            'buildings' => $buildings,
        ]);
    }
}