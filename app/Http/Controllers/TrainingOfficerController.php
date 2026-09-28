<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Field;
use Illuminate\View\View;

class TrainingOfficerController extends Controller
{
    public function index(): View
    {
        $buildings = Building::query()
            ->with([
                'rooms' => function ($query) {
                    $query->where('status', 'AVAILABLE')
                        ->with('images');
                },
                'trainingRooms' => function ($query) {
                    $query->where('status', 'AVAILABLE')
                        ->with('images');
                },
            ])
            ->orderBy('name')
            ->get();

        $fields = Field::query()
            ->where('status', 'AVAILABLE')
            ->with('images')
            ->orderBy('name')
            ->get();

        return view('training-officer.index', [
            'buildings' => $buildings,
            'fields' => $fields,
        ]);
    }
}