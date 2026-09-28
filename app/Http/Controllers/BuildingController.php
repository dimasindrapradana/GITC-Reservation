<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            ->with([
                'images',
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

            'images' => [
                'nullable',
                'array',
                'max:10',
            ],

            'images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $building = Building::create([
            'name' => $validated['name'],
        ]);

        /*
         * =====================================================
         * SAVE BUILDING IMAGES
         * =====================================================
         */

        if ($request->hasFile('images')) {

            foreach (
                $request->file('images')
                as $index => $image
            ) {

                $path = $image->store(
                    'buildings',
                    'public'
                );

                $building->images()->create([
                    'file' => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()
            ->route('buildings.index')
            ->with(
                'success',
                'Building has been successfully added.'
            );
    }

    public function edit(Building $building): View
    {
        $building->load([
            'images',
        ]);

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

            'images' => [
                'nullable',
                'array',
            ],

            'images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        /*
         * =====================================================
         * UPDATE BUILDING INFORMATION
         * =====================================================
         */

        $building->update([
            'name' => $validated['name'],
        ]);

        /*
         * =====================================================
         * SAVE NEW IMAGES
         * =====================================================
         */

        $existingImageCount = $building->images()->count();

        $newImages = $request->file('images', []);

        $newImageCount = count($newImages);

        if (
            $existingImageCount + $newImageCount > 10
        ) {
            return back()
                ->withErrors([
                    'images' =>
                        'A building can have a maximum of 10 images.'
                ])
                ->withInput();
        }

        if ($newImageCount > 0) {

            $nextSortOrder = $building->images()
                ->max('sort_order');

            $nextSortOrder =
                is_null($nextSortOrder)
                    ? 0
                    : $nextSortOrder + 1;

            foreach (
                $newImages
                as $image
            ) {

                $path = $image->store(
                    'buildings',
                    'public'
                );

                $building->images()->create([
                    'file' => $path,
                    'sort_order' => $nextSortOrder,
                ]);

                $nextSortOrder++;
            }
        }

        return redirect()
            ->route(
                'buildings.edit',
                $building
            )
            ->with(
                'success',
                'Building has been successfully updated.'
            );
    }

    /*
     * =========================================================
     * DELETE BUILDING IMAGE
     * =========================================================
     */

    public function destroyImage(
        Building $building,
        $image
    ): RedirectResponse {

    $building->load('images');

        $buildingImage = $building->images()
            ->where('id', $image)
            ->firstOrFail();

        /*
         * Delete physical image file
         */

        if (
            Storage::disk('public')
                ->exists($buildingImage->file)
        ) {
            Storage::disk('public')
                ->delete($buildingImage->file);
        }

        /*
         * Delete image database record
         */

        $buildingImage->delete();

           $remainingImages = $building->images()
        ->orderBy('sort_order')
        ->get();

    foreach ($remainingImages as $index => $remainingImage) {

        $remainingImage->update([
            'sort_order' => $index,
        ]);
    }

    return redirect()
        ->route(
            'buildings.edit',
            $building
        )
        ->with(
            'success',
            'Building image has been successfully deleted.'
        );
}

    public function destroy(
        Building $building
    ): RedirectResponse {

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

        /*
         * =====================================================
         * DELETE BUILDING IMAGES
         * =====================================================
         */

        $building->load('images');

        foreach ($building->images as $image) {

            if (
                Storage::disk('public')
                    ->exists($image->file)
            ) {
                Storage::disk('public')
                    ->delete($image->file);
            }

            $image->delete();
        }

        /*
         * =====================================================
         * DELETE BUILDING
         * =====================================================
         */

        $building->delete();

        return redirect()
            ->route('buildings.index')
            ->with(
                'success',
                'Building has been successfully deleted.'
            );
    }
}