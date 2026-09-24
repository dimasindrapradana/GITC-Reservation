<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BuildingController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $filter = $request->string('filter')->toString();

        $buildings = Building::query()
            ->withCount([
                'rooms',
                'trainingRooms',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(
                    'name',
                    'like',
                    '%' . $search . '%'
                );
            })
            ->when($filter === 'with_rooms', function ($query) {
                $query->has('rooms');
            })
            ->when($filter === 'with_training_rooms', function ($query) {
                $query->has('trainingRooms');
            })
            ->when($filter === 'empty', function ($query) {
                $query
                    ->doesntHave('rooms')
                    ->doesntHave('trainingRooms');
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('buildings.index', [
            'buildings' => $buildings,
            'search' => $search,
            'filter' => $filter,
        ]);
    }

    public function create(): View
    {
        return view('buildings.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                'unique:buildings,name',
            ],
        ]);

        Building::create($validated);

        return redirect()
            ->route('buildings.index')
            ->with(
                'success',
                'Building has been successfully added.'
            );
    }

    public function edit(Building $building): View
    {
        return view('buildings.edit', [
            'building' => $building,
        ]);
    }

    public function update(
        Request $request,
        Building $building
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('buildings', 'name')
                    ->ignore($building->id),
            ],
        ]);

        $building->update($validated);

        return redirect()
            ->route('buildings.index')
            ->with(
                'success',
                'Building has been successfully updated.'
            );
    }

    public function destroy(Building $building): RedirectResponse
    {
        if (
            $building->rooms()->exists()
            || $building->trainingRooms()->exists()
        ) {
            return redirect()
                ->route('buildings.index')
                ->with(
                    'error',
                    'This building cannot be deleted because it still has rooms or training rooms.'
                );
        }

        $building->delete();

        return redirect()
            ->route('buildings.index')
            ->with(
                'success',
                'Building has been successfully deleted.'
            );
    }
}