<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\TrainingRoom;
use App\Services\ResourceImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TrainingRoomController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')
            ->trim()
            ->toString();

        $building = $request->string('building')
            ->toString();

        $filter = $request->string('filter')
            ->toString();

        $sort = $request->string('sort')
            ->toString();

        if (!in_array($sort, ['newest', 'oldest'], true)) {
            $sort = 'newest';
        }

        $trainingRooms = TrainingRoom::query()
            ->with([
                'building' => function ($query) {
                    $query->withTrashed();
                },
            ])
            ->withCount('reservations')

            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where(
                            'training_rooms.name',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhereHas(
                            'building',
                            function ($query) use ($search) {
                                $query
                                    ->withTrashed()
                                    ->where(
                                        'name',
                                        'like',
                                        '%' . $search . '%'
                                    );
                            }
                        );
                });
            })

            ->when($building !== '', function ($query) use ($building) {
                $query->where(
                    'building_id',
                    $building
                );
            })

            ->when($filter === 'available', function ($query) {
                $query->where(
                    'status',
                    'AVAILABLE'
                );
            })

            ->when($filter === 'maintenance', function ($query) {
                $query->where(
                    'status',
                    'MAINTENANCE'
                );
            })

            ->when($sort === 'oldest', function ($query) {
                $query
                    ->orderBy('created_at', 'asc')
                    ->orderBy('id', 'asc');
            })

            ->when($sort === 'newest', function ($query) {
                $query
                    ->orderBy('created_at', 'desc')
                    ->orderBy('id', 'desc');
            })

            ->paginate(10)
            ->withQueryString();

        $buildings = Building::query()
            ->orderBy('name')
            ->get();

        return view('training_rooms.index', [
            'trainingRooms' => $trainingRooms,
            'buildings' => $buildings,
            'search' => $search,
            'building' => $building,
            'filter' => $filter,
            'sort' => $sort,
        ]);
    }

    public function create(): View
    {
        $buildings = Building::query()
            ->orderBy('name')
            ->get();

        return view('training_rooms.create', [
            'buildings' => $buildings,
        ]);
    }

    public function store(
        Request $request,
        ResourceImageService $imageService
    ): RedirectResponse {
        $validated = $request->validate([
            'building_id' => [
                'required',
                'integer',
                Rule::exists('buildings', 'id')
                    ->whereNull('deleted_at'),
            ],
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'capacity' => [
                'required',
                'integer',
                'min:0',
            ],
            'simulation_type' => [
                'required',
                'string',
                'max:150',
            ],
            'simulation_facilities' => [
                'required',
                'string',
            ],
            'status' => [
                'required',
                Rule::in([
                    'AVAILABLE',
                    'MAINTENANCE',
                ]),
            ],
            'images' => [
                'nullable',
                'array',
                'max:10',
            ],
            'images.*' => [
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $trainingRoom = TrainingRoom::create([
            'building_id' => $validated['building_id'],
            'name' => $validated['name'],
            'capacity' => $validated['capacity'],
            'simulation_type' => $validated['simulation_type'],
            'simulation_facilities' => $validated['simulation_facilities'],
            'status' => $validated['status'],
        ]);

        foreach ($request->file('images', []) as $index => $image) {
            $imageService->store(
                $image,
                'training_rooms',
                $trainingRoom->id,
                $index
            );
        }

        return redirect()
            ->route('training-rooms.index')
            ->with(
                'success',
                'Training room has been successfully added.'
            );
    }

    public function show(TrainingRoom $trainingRoom): View
    {
        $trainingRoom->load([
            'building' => function ($query) {
                $query->withTrashed();
            },
            'images' => function ($query) {
                $query->orderBy('sort_order');
            },
        ]);

        return view('training_rooms.show', [
            'trainingRoom' => $trainingRoom,
        ]);
    }

    public function edit(TrainingRoom $trainingRoom): View
    {
        $buildings = Building::query()
            ->orderBy('name')
            ->get();

        $trainingRoom->load([
            'building' => function ($query) {
                $query->withTrashed();
            },
            'images' => function ($query) {
                $query->orderBy('sort_order');
            },
        ]);

        return view('training_rooms.edit', [
            'trainingRoom' => $trainingRoom,
            'buildings' => $buildings,
        ]);
    }

    public function update(
        Request $request,
        TrainingRoom $trainingRoom,
        ResourceImageService $imageService
    ): RedirectResponse {
        $validated = $request->validate([
            'building_id' => [
                'required',
                'integer',
                Rule::exists('buildings', 'id')
                    ->whereNull('deleted_at'),
            ],
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'capacity' => [
                'required',
                'integer',
                'min:0',
            ],
            'simulation_type' => [
                'required',
                'string',
                'max:150',
            ],
            'simulation_facilities' => [
                'required',
                'string',
            ],
            'status' => [
                'required',
                Rule::in([
                    'AVAILABLE',
                    'MAINTENANCE',
                ]),
            ],
            'images' => [
                'nullable',
                'array',
            ],
            'images.*' => [
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $trainingRoom->update([
            'building_id' => $validated['building_id'],
            'name' => $validated['name'],
            'capacity' => $validated['capacity'],
            'simulation_type' => $validated['simulation_type'],
            'simulation_facilities' => $validated['simulation_facilities'],
            'status' => $validated['status'],
        ]);

        $lastSortOrder = $trainingRoom->images()->max('sort_order');

        foreach ($request->file('images', []) as $index => $image) {
            $imageService->store(
                $image,
                'training_rooms',
                $trainingRoom->id,
                ($lastSortOrder ?? -1) + $index + 1
            );
        }

        return redirect()
            ->route('training-rooms.index')
            ->with(
                'success',
                'Training room has been successfully updated.'
            );
    }

    public function destroy(
        TrainingRoom $trainingRoom
    ): RedirectResponse {
        // Soft delete only.
        // Existing images and reservations are preserved.
        $trainingRoom->delete();

        return redirect()
            ->route('training-rooms.index')
            ->with(
                'success',
                'Training room has been successfully deleted.'
            );
    }

    public function destroyImage(
        TrainingRoom $trainingRoom,
        int $image,
        ResourceImageService $imageService
    ): RedirectResponse {
        $resourceImage = $trainingRoom->images()
            ->whereKey($image)
            ->firstOrFail();

        // Only the selected image is permanently deleted.
        $imageService->delete($resourceImage);

        $remainingImages = $trainingRoom->images()
            ->orderBy('sort_order')
            ->get();

        foreach ($remainingImages as $index => $remainingImage) {
            if ($remainingImage->sort_order !== $index) {
                $remainingImage->update([
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()
            ->route('training-rooms.edit', $trainingRoom)
            ->with(
                'success',
                'Training room image has been successfully deleted.'
            );
    }
}