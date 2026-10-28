<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrainingScheduleReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $role = $user->role->name;

        $buildingsQuery = Building::query()->orderBy('name');

        if ($role === 'Building Coordinator') {
            $buildingsQuery->whereIn(
                'id',
                $user->buildings()->select('buildings.id')
            );
        }

        $buildings = $buildingsQuery->get();

        $selectedBuildingId = $request->query('building_id');

        if ($selectedBuildingId !== null) {
            $selectedBuilding = $buildings->firstWhere(
                'id',
                (int) $selectedBuildingId
            );

            abort_unless($selectedBuilding, 403);
        } else {
            $selectedBuilding = $buildings->first();
        }

        return view('training-schedule.reports', [
            'buildings' => $buildings,
            'selectedBuilding' => $selectedBuilding,
            'month' => (int) $request->query('month', now()->month),
            'year' => (int) $request->query('year', now()->year),
        ]);
    }
}