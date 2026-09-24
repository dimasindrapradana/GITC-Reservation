<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Services\NewsImageService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')
            ->trim()
            ->toString();

        $status = $request->string('status')
            ->toString();

        $sort = $request->string('sort')
            ->toString();

        if (!in_array($sort, ['asc', 'desc'], true)) {
            $sort = 'desc';
        }

        $news = News::query()
            ->with('creator')
            ->withCount('images')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where(
                            'title',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'content',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhereHas('creator', function ($query) use ($search) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            );
                        });
                });
            })
            ->when($status !== '', function ($query) use ($status) {
                $query->where(
                    'status',
                    $status
                );
            })
            ->orderBy('created_at', $sort)
            ->paginate(10)
            ->withQueryString();

        return view('news.index', [
            'news' => $news,
            'search' => $search,
            'status' => $status,
            'sort' => $sort,
        ]);
    }

    public function create(): View
    {
        return view('news.create');
    }

    public function store(
        Request $request,
        NewsImageService $imageService
    ): RedirectResponse {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'content' => [
                'required',
                'string',
            ],
            'starts_at' => [
                'required',
                'date',
            ],
            'ends_at' => [
                'required',
                'date',
                'after:starts_at',
            ],
            'status' => [
                'required',
                Rule::in([
                    'PENDING',
                    'SCHEDULED',
                    'PUBLISHED',
                    'EXPIRED',
                    'CANCELLED',
                    'REJECTED',
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

        $startsAt = Carbon::parse(
            $validated['starts_at'],
            'Asia/Jakarta'
        );

        $endsAt = Carbon::parse(
            $validated['ends_at'],
            'Asia/Jakarta'
        );

        $news = News::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => $validated['status'],
            'created_by' => $request->user()->id,
        ]);

        foreach (
            $request->file('images', [])
            as $index => $image
        ) {
            $imageService->store(
                $image,
                $news->id,
                $index
            );
        }

        return redirect()
            ->route('news.index')
            ->with(
                'success',
                'News has been successfully added.'
            );
    }

    public function show(News $news): View
    {
        $news->load([
            'creator',
            'images' => function ($query) {
                $query->orderBy('sort_order');
            },
        ]);

        return view('news.show', [
            'news' => $news,
        ]);
    }

    public function edit(News $news): View
    {
        $news->load([
            'creator',
            'images' => function ($query) {
                $query->orderBy('sort_order');
            },
        ]);

        return view('news.edit', [
            'news' => $news,
        ]);
    }

    public function update(
        Request $request,
        News $news,
        NewsImageService $imageService
    ): RedirectResponse {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'content' => [
                'required',
                'string',
            ],
            'starts_at' => [
                'required',
                'date',
            ],
            'ends_at' => [
                'required',
                'date',
                'after:starts_at',
            ],
            'status' => [
                'required',
                Rule::in([
                    'PENDING',
                    'SCHEDULED',
                    'PUBLISHED',
                    'EXPIRED',
                    'CANCELLED',
                    'REJECTED',
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

        $startsAt = Carbon::parse(
            $validated['starts_at'],
            'Asia/Jakarta'
        );

        $endsAt = Carbon::parse(
            $validated['ends_at'],
            'Asia/Jakarta'
        );

        $news->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => $validated['status'],
        ]);

        $lastSortOrder = $news->images()->max('sort_order');

        foreach (
            $request->file('images', [])
            as $index => $image
        ) {
            $imageService->store(
                $image,
                $news->id,
                ($lastSortOrder ?? -1) + $index + 1
            );
        }

        return redirect()
            ->route('news.index')
            ->with(
                'success',
                'News has been successfully updated.'
            );
    }

    public function destroyImage(
        News $news,
        int $image,
        NewsImageService $imageService
    ): RedirectResponse {
        $newsImage = $news->images()
            ->whereKey($image)
            ->firstOrFail();

        $imageService->delete($newsImage);

        return redirect()
            ->route('news.edit', $news)
            ->with(
                'success',
                'News image has been successfully deleted.'
            );
    }

    public function destroy(
        News $news,
        NewsImageService $imageService
    ): RedirectResponse {
        $news->load('images');

        foreach ($news->images as $image) {
            $imageService->delete($image);
        }

        $news->delete();

        return redirect()
            ->route('news.index')
            ->with(
                'success',
                'News has been successfully deleted.'
            );
    }
}