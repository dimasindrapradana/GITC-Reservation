<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Services\ResourceImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FieldController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')
            ->trim()
            ->toString();

        $filter = $request->string('filter')
            ->toString();

        $fields = Field::query()
            ->withCount('reservations')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(
                    'name',
                    'like',
                    '%' . $search . '%'
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
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('fields.index', [
            'fields' => $fields,
            'search' => $search,
            'filter' => $filter,
        ]);
    }

    public function create(): View
    {
        return view('fields.create');
    }

    public function store(
        Request $request,
        ResourceImageService $imageService
    ): RedirectResponse {
        $validated = $request->validate([
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

        $field = Field::create([
            'name' => $validated['name'],
            'capacity' => $validated['capacity'],
            'status' => $validated['status'],
        ]);

        foreach ($request->file('images', []) as $index => $image) {
            $imageService->store(
                $image,
                'fields',
                $field->id,
                $index
            );
        }

        return redirect()
            ->route('fields.index')
            ->with(
                'success',
                'Field has been successfully added.'
            );
    }

    public function show(Field $field): View
    {
        $field->load([
            'images' => function ($query) {
                $query->orderBy('sort_order');
            },
        ]);

        return view('fields.show', [
            'field' => $field,
        ]);
    }

    public function edit(Field $field): View
    {
        $field->load([
            'images' => function ($query) {
                $query->orderBy('sort_order');
            },
        ]);

        return view('fields.edit', [
            'field' => $field,
        ]);
    }

    public function update(
        Request $request,
        Field $field,
        ResourceImageService $imageService
    ): RedirectResponse {
        $validated = $request->validate([
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

        $field->update([
            'name' => $validated['name'],
            'capacity' => $validated['capacity'],
            'status' => $validated['status'],
        ]);

        $lastSortOrder = $field->images()->max('sort_order');

        foreach ($request->file('images', []) as $index => $image) {
            $imageService->store(
                $image,
                'fields',
                $field->id,
                ($lastSortOrder ?? -1) + $index + 1
            );
        }

        return redirect()
            ->route('fields.index')
            ->with(
                'success',
                'Field has been successfully updated.'
            );
    }

    public function destroy(
        Field $field,
        ResourceImageService $imageService
    ): RedirectResponse {
        if ($field->reservations()->exists()) {
            return redirect()
                ->route('fields.index')
                ->with(
                    'error',
                    'This field cannot be deleted because it has existing reservations.'
                );
        }

        $field->load('images');

        foreach ($field->images as $image) {
            $imageService->delete($image);
        }

        $field->delete();

        return redirect()
            ->route('fields.index')
            ->with(
                'success',
                'Field has been successfully deleted.'
            );
    }

    public function destroyImage(
        Field $field,
        int $image,
        ResourceImageService $imageService
    ): RedirectResponse {
        $resourceImage = $field->images()
            ->whereKey($image)
            ->firstOrFail();

        $imageService->delete($resourceImage);

        $remainingImages = $field->images()
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
            ->route('fields.edit', $field)
            ->with(
                'success',
                'Field image has been successfully deleted.'
            );
    }
}