<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Room;
use App\Services\ResourceImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoomController extends Controller
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

        if (!in_array($sort, ['asc', 'desc'], true)) {
            $sort = 'desc';
        }

        $rooms = Room::query()
            ->with('building')
            ->withCount('reservations')

            ->when($search !== '', function ($query) use ($search) {

                $query->where(function ($query) use ($search) {

                    $query
                        ->where(
                            'rooms.name',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhereHas('building', function ($query) use ($search) {

                            $query->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            );

                        });

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

            ->orderBy(
                'created_at',
                $sort
            )
            ->orderBy(
                'name',
                'asc'
            )
            ->paginate(10)
            ->withQueryString();

        $buildings = Building::query()
            ->orderBy('name')
            ->get();

        return view('rooms.index', [
            'rooms' => $rooms,
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

        return view('rooms.create', [
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
                'exists:buildings,id',
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
            'lcd_count' => [
                'required',
                'integer',
                'min:0',
            ],
            'whiteboard_count' => [
                'required',
                'integer',
                'min:0',
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

        $room = Room::create([
            'building_id' => $validated['building_id'],
            'name' => $validated['name'],
            'capacity' => $validated['capacity'],
            'lcd_count' => $validated['lcd_count'],
            'whiteboard_count' => $validated['whiteboard_count'],
            'status' => $validated['status'],
        ]);

        foreach ($request->file('images', []) as $index => $image) {

            $imageService->store(
                $image,
                'rooms',
                $room->id,
                $index
            );

        }

        return redirect()
            ->route('rooms.index')
            ->with(
                'success',
                'Room has been successfully added.'
            );
    }

    public function show(Room $room): View
    {
        $room->load([
            'building',
            'images' => function ($query) {
                $query->orderBy('sort_order');
            },
        ]);

        return view('rooms.show', [
            'room' => $room,
        ]);
    }

    public function edit(Room $room): View
    {
        $buildings = Building::query()
            ->orderBy('name')
            ->get();

        $room->load([
            'images' => function ($query) {
                $query->orderBy('sort_order');
            },
        ]);

        return view('rooms.edit', [
            'room' => $room,
            'buildings' => $buildings,
        ]);
    }

    public function update(
        Request $request,
        Room $room,
        ResourceImageService $imageService
    ): RedirectResponse {
        $validated = $request->validate([
            'building_id' => [
                'required',
                'integer',
                'exists:buildings,id',
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
            'lcd_count' => [
                'required',
                'integer',
                'min:0',
            ],
            'whiteboard_count' => [
                'required',
                'integer',
                'min:0',
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

        $room->update([
            'building_id' => $validated['building_id'],
            'name' => $validated['name'],
            'capacity' => $validated['capacity'],
            'lcd_count' => $validated['lcd_count'],
            'whiteboard_count' => $validated['whiteboard_count'],
            'status' => $validated['status'],
        ]);

        $lastSortOrder = $room->images()->max('sort_order');

        foreach ($request->file('images', []) as $index => $image) {

            $imageService->store(
                $image,
                'rooms',
                $room->id,
                ($lastSortOrder ?? -1) + $index + 1
            );

        }

        return redirect()
            ->route('rooms.index')
            ->with(
                'success',
                'Room has been successfully updated.'
            );
    }

    public function destroy(
        Room $room,
        ResourceImageService $imageService
    ): RedirectResponse {
        if ($room->reservations()->exists()) {

            return redirect()
                ->route('rooms.index')
                ->with(
                    'error',
                    'This room cannot be deleted because it has existing reservations.'
                );
        }

        foreach ($room->images as $image) {
            $imageService->delete($image);
        }

        $room->delete();

        return redirect()
            ->route('rooms.index')
            ->with(
                'success',
                'Room has been successfully deleted.'
            );
    }

    public function destroyImage(
        Room $room,
        int $image,
        ResourceImageService $imageService
    ): RedirectResponse {
        $resourceImage = $room->images()
            ->whereKey($image)
            ->firstOrFail();

        $imageService->delete($resourceImage);

        return redirect()
            ->route('rooms.edit', $room)
            ->with(
                'success',
                'Room image has been successfully deleted.'
            );
    }
}