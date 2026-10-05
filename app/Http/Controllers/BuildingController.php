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
    /*
     * =========================================================
     * BUILDING INDEX
     * =========================================================
     */

    public function index(Request $request): View
    {
        $search = $request
            ->string('search')
            ->trim()
            ->toString();

        $filter = $request
            ->string('filter')
            ->toString();

        $buildings = Building::query()
            ->withCount([
                'rooms',
                'trainingRooms',
            ])
            ->with([
                'images',
            ])
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $query->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    );
                }
            )
            ->when(
                $filter === 'with_rooms',
                function ($query) {
                    $query->has('rooms');
                }
            )
            ->when(
                $filter === 'with_training_rooms',
                function ($query) {
                    $query->has('trainingRooms');
                }
            )
            ->when(
                $filter === 'empty',
                function ($query) {
                    $query
                        ->doesntHave('rooms')
                        ->doesntHave('trainingRooms');
                }
            )
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('buildings.index', [
            'buildings' => $buildings,
            'search' => $search,
            'filter' => $filter,
        ]);
    }


    /*
     * =========================================================
     * CREATE
     * =========================================================
     */

    public function create(): View
    {
        return view('buildings.create');
    }


    /*
     * =========================================================
     * STORE
     * =========================================================
     */

    public function store(
        Request $request
    ): RedirectResponse {

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique(
                    'buildings',
                    'name'
                )->whereNull('deleted_at'),
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


        /*
         * =====================================================
         * CREATE BUILDING
         * =====================================================
         */

        $building = Building::create([
            'name' => $validated['name'],
        ]);


        /*
         * =====================================================
         * SAVE BUILDING IMAGES
         * =====================================================
         */

        if (
            $request->hasFile('images')
        ) {

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


    /*
     * =========================================================
     * EDIT
     * =========================================================
     */

    public function edit(
        Building $building
    ): View {

        $building->load([
            'images',
        ]);

        return view('buildings.edit', [
            'building' => $building,
        ]);
    }


    /*
     * =========================================================
     * UPDATE
     * =========================================================
     */

    public function update(
        Request $request,
        Building $building
    ): RedirectResponse {

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique(
                    'buildings',
                    'name'
                )
                    ->whereNull('deleted_at')
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
         * CHECK IMAGE LIMIT
         * =====================================================
         */

        $existingImageCount =
            $building->images()->count();

        $newImages =
            $request->file('images', []);

        $newImageCount =
            count($newImages);


        if (
            $existingImageCount +
            $newImageCount >
            10
        ) {

            return back()
                ->withErrors([
                    'images' =>
                        'A building can have a maximum of 10 images.',
                ])
                ->withInput();
        }


        /*
         * =====================================================
         * SAVE NEW IMAGES
         * =====================================================
         */

        if (
            $newImageCount > 0
        ) {

            $nextSortOrder =
                $building->images()
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
     *
     * This permanently deletes only the selected image.
     * The Building itself is NOT deleted.
     * =========================================================
     */

    public function destroyImage(
        Building $building,
        $image
    ): RedirectResponse {

        $building->load([
            'images',
        ]);


        /*
         * =====================================================
         * FIND BUILDING IMAGE
         * =====================================================
         */

        $buildingImage =
            $building->images()
                ->where(
                    'id',
                    $image
                )
                ->firstOrFail();


        /*
         * =====================================================
         * DELETE PHYSICAL IMAGE FILE
         * =====================================================
         */

        if (
            Storage::disk('public')
                ->exists(
                    $buildingImage->file
                )
        ) {

            Storage::disk('public')
                ->delete(
                    $buildingImage->file
                );
        }


        /*
         * =====================================================
         * DELETE IMAGE DATABASE RECORD
         * =====================================================
         */

        $buildingImage->delete();


        /*
         * =====================================================
         * REORDER REMAINING IMAGES
         * =====================================================
         */

        $remainingImages =
            $building->images()
                ->orderBy('sort_order')
                ->get();


        foreach (
            $remainingImages
            as $index => $remainingImage
        ) {

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


    /*
     * =========================================================
     * SOFT DELETE BUILDING
     *
     * The Building record remains in database.
     * Existing reservations and images remain intact.
     * =========================================================
     */

    public function destroy(
        Building $building
    ): RedirectResponse {

        $building->delete();

        return redirect()
            ->route(
                'buildings.index'
            )
            ->with(
                'success',
                'Building has been successfully deleted.'
            );
    }
}