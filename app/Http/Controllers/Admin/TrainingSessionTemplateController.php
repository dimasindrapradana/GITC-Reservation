<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\TrainingSessionTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TrainingSessionTemplateController extends Controller
{
    public function index(Request $request): View
    {
        $buildings = Building::query()
            ->orderBy('name')
            ->get();

        $selectedBuildingId = $request->query('building_id');

        $selectedBuilding = null;
        $templates = collect();

        if ($selectedBuildingId) {
            $selectedBuilding = Building::find($selectedBuildingId);

            if ($selectedBuilding) {
                $templates = $selectedBuilding
                    ->trainingSessionTemplates()
                    ->orderBy('session_number')
                    ->get();
            }
        }

        return view('admin.training-session-settings.index', [
            'buildings' => $buildings,
            'selectedBuilding' => $selectedBuilding,
            'selectedBuildingId' => $selectedBuildingId,
            'templates' => $templates,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'building_id' => [
                'required',
                'integer',
                'exists:buildings,id',
            ],
            'sessions' => [
                'required',
                'array',
                'min:1',
            ],
            'sessions.*.session_number' => [
                'required',
                'integer',
                'min:1',
                'distinct',
            ],
            'sessions.*.start_time' => [
                'required',
                'date_format:H:i',
            ],
            'sessions.*.end_time' => [
                'required',
                'date_format:H:i',
            ],
        ]);

        $sessions = collect($validated['sessions'])
            ->sortBy('session_number')
            ->values();

        foreach ($sessions as $index => $session) {
            if ($session['end_time'] <= $session['start_time']) {
                throw ValidationException::withMessages([
                    "sessions.{$index}.end_time" =>
                        'End time must be later than start time.',
                ]);
            }
        }

        foreach ($sessions as $index => $session) {
            if ($index > 0) {
                $previous = $sessions[$index - 1];

                if ($session['start_time'] < $previous['end_time']) {
                    throw ValidationException::withMessages([
                        "sessions.{$index}.start_time" =>
                            'Waktu sesi tidak boleh bertumpang tindih.',
                    ]);
                }
            }
        }

        DB::transaction(function () use ($validated, $sessions) {
            $building = Building::findOrFail(
                $validated['building_id']
            );

            $building->trainingSessionTemplates()->delete();

            foreach ($sessions as $session) {
                $building->trainingSessionTemplates()->create([
                    'session_number' => $session['session_number'],
                    'start_time' => $session['start_time'],
                    'end_time' => $session['end_time'],
                ]);
            }
        });

        return redirect()
            ->route('training-session-settings.index', [
                'building_id' => $validated['building_id'],
            ])
            ->with('success', 'Konfigurasi sesi berhasil disimpan.');
    }
}
