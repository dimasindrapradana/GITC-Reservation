@extends('layouts.admin')

@section('title', 'News')
@section('page_title', 'News')

@section('content')

<div class="page-header">
    <div>
        <h1>News</h1>
        <p>Manage news content and publication schedules.</p>
    </div>

    <a
        href="{{ route('news.create') }}"
        class="btn-primary"
    >
        + Add News
    </a>
</div>

@if (session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert-error">
        {{ session('error') }}
    </div>
@endif

<div class="content-card">

    <div class="card-header">
        <div>
            <h2>News List</h2>
            <p>Search, filter, and manage news content.</p>
        </div>
    </div>

    <form
        action="{{ route('news.index') }}"
        method="GET"
        class="filter-form"
    >

        <div class="filter-group search-group">
            <label for="search">
                Search
            </label>

            <input
                type="text"
                name="search"
                id="search"
                value="{{ $search }}"
                placeholder="Search news..."
            >
        </div>

        <div class="filter-group">
            <label for="status">
                Status
            </label>

            <select
                name="status"
                id="status"
            >
                <option value="">
                    All Statuses
                </option>

                <option
                    value="PENDING"
                    @selected($status === 'PENDING')
                >
                    Pending
                </option>

                <option
                    value="SCHEDULED"
                    @selected($status === 'SCHEDULED')
                >
                    Scheduled
                </option>

                <option
                    value="PUBLISHED"
                    @selected($status === 'PUBLISHED')
                >
                    Published
                </option>

                <option
                    value="EXPIRED"
                    @selected($status === 'EXPIRED')
                >
                    Expired
                </option>

                <option
                    value="CANCELLED"
                    @selected($status === 'CANCELLED')
                >
                    Cancelled
                </option>

                <option
                    value="REJECTED"
                    @selected($status === 'REJECTED')
                >
                    Rejected
                </option>
            </select>
        </div>

        <div class="filter-group">
            <label for="sort">
                Sort By
            </label>

            <select
                name="sort"
                id="sort"
            >
                <option
                    value="desc"
                    @selected($sort === 'desc')
                >
                    Newest First
                </option>

                <option
                    value="asc"
                    @selected($sort === 'asc')
                >
                    Oldest First
                </option>
            </select>
        </div>

        <div class="filter-actions">
            <button
                type="submit"
                class="btn-primary"
            >
                Search
            </button>

            <a
                href="{{ route('news.index') }}"
                class="btn-secondary"
            >
                Reset
            </a>
        </div>

    </form>

    <div class="table-wrapper">

        <table>

            <thead>
                <tr>
                    <th>No.</th>
                    <th>News</th>
                    <th>Schedule</th>
                    <th>Created By</th>
                    <th>Images</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($news as $item)

                    @php
                        $statusClass = match ($item->status) {
                            'PENDING' => 'pending',
                            'SCHEDULED' => 'scheduled',
                            'PUBLISHED' => 'published',
                            'EXPIRED' => 'expired',
                            'CANCELLED' => 'cancelled',
                            'REJECTED' => 'rejected',
                            default => 'cancelled',
                        };
                    @endphp

                    <tr>

                        <td>
                            {{ $news->firstItem() + $loop->index }}
                        </td>

                        <td>
                            <div class="news-title">
                                {{ $item->title }}
                            </div>

                            <div class="news-preview">
                                {{ \Illuminate\Support\Str::limit(strip_tags($item->content), 90) }}
                            </div>
                        </td>

                        <td>
                            <div class="schedule-date">
                                {{ $item->starts_at?->format('d M Y') ?? '—' }}
                            </div>

                            <div class="schedule-time">
                                {{ $item->starts_at?->format('H:i') ?? '—' }}
                                -
                                {{ $item->ends_at?->format('H:i') ?? '—' }}
                            </div>
                        </td>

                        <td>
                            {{ $item->creator?->name ?? '—' }}
                        </td>

                        <td>
                            {{ $item->images_count }}
                        </td>

                        <td>
                            <span class="status-badge {{ $statusClass }}">
                                {{ $item->status }}
                            </span>
                        </td>

                        <td>
                            <div class="action-group">

                                <a
                                    href="{{ route('news.show', $item) }}"
                                    class="action-link detail"
                                >
                                    Detail
                                </a>

                                <a
                                    href="{{ route('news.edit', $item) }}"
                                    class="action-link edit"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('news.destroy', $item) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this news? This will also delete all attached images.');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="action-link delete"
                                    >
                                        Delete
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="7"
                            class="empty-state"
                        >
                            No news found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if ($news->hasPages())
        <div class="pagination-wrapper">

            <div class="pagination-info">
                Showing
                {{ $news->firstItem() }}
                to
                {{ $news->lastItem() }}
                of
                {{ $news->total() }}
                results
            </div>

            <div class="pagination-pages">

                @for (
                    $page = 1;
                    $page <= $news->lastPage();
                    $page++
                )

                    @if ($page === $news->currentPage())

                        <span class="pagination-page active">
                            {{ $page }}
                        </span>

                    @else

                        <a
                            href="{{ $news->url($page) }}"
                            class="pagination-page"
                        >
                            {{ $page }}
                        </a>

                    @endif

                @endfor

            </div>

        </div>
    @endif

</div>

<style>
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 22px;
    }

    .page-header h1 {
        margin: 0 0 6px;
        color: #183b56;
        font-size: 22px;
        font-weight: 700;
    }

    .page-header p {
        margin: 0;
        color: #668096;
        font-size: 13px;
    }

    .content-card {
        background: #ffffff;
        border: 1px solid #e5edf2;
        border-radius: 9px;
        overflow: hidden;
    }

    .card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf2f5;
    }

    .card-header h2 {
        margin: 0 0 4px;
        color: #23445d;
        font-size: 15px;
        font-weight: 700;
    }

    .card-header p {
        margin: 0;
        color: #7a8fa1;
        font-size: 12px;
    }

    .filter-form {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        padding: 16px 20px;
        border-bottom: 1px solid #edf2f5;
        background: #fbfcfd;
    }

    .filter-group {
        min-width: 170px;
    }

    .search-group {
        flex: 1;
    }

    .filter-group label {
        display: block;
        margin-bottom: 6px;
        color: #668096;
        font-size: 11px;
        font-weight: 700;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        height: 36px;
        padding: 0 10px;
        border: 1px solid #d5e0e7;
        border-radius: 6px;
        background: #ffffff;
        color: #29465d;
        font-family: inherit;
        font-size: 12px;
        box-sizing: border-box;
        outline: none;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        border-color: #8bc6dd;
        box-shadow: 0 0 0 3px rgba(0, 111, 174, .08);
    }

    .filter-actions {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .btn-primary,
    .btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 34px;
        padding: 0 13px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        box-sizing: border-box;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-primary {
        border: 1px solid #006fae;
        background: #006fae;
        color: #ffffff;
    }

    .btn-primary:hover {
        background: #005f96;
        border-color: #005f96;
    }

    .btn-secondary {
        border: 1px solid #d0dce5;
        background: #ffffff;
        color: #4f6680;
    }

    .btn-secondary:hover {
        border-color: #c9dfe9;
        background: #f2f9fc;
        color: #006fae;
    }

    .alert-success,
    .alert-error {
        margin-bottom: 18px;
        padding: 13px 15px;
        border-radius: 8px;
        font-size: 12px;
    }

    .alert-success {
        border: 1px solid #c8e2cf;
        background: #f4fbf6;
        color: #2e7040;
    }

    .alert-error {
        border: 1px solid #edc4c4;
        background: #fff7f7;
        color: #9b2c2c;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        padding: 12px 14px;
        border-bottom: 1px solid #e5edf2;
        background: #fbfcfd;
        color: #668096;
        font-size: 10px;
        font-weight: 700;
        text-align: left;
        white-space: nowrap;
    }

    td {
        padding: 13px 14px;
        border-bottom: 1px solid #edf2f5;
        color: #456176;
        font-size: 12px;
        vertical-align: middle;
    }

    tbody tr:last-child td {
        border-bottom: 0;
    }

    tbody tr:hover {
        background: #fcfdfe;
    }

    .news-title {
        margin-bottom: 4px;
        color: #29465d;
        font-size: 12px;
        font-weight: 700;
    }

    .news-preview {
        max-width: 320px;
        color: #8195a5;
        font-size: 10px;
        line-height: 1.5;
    }

    .schedule-date {
        color: #456176;
        font-size: 11px;
        font-weight: 600;
    }

    .schedule-time {
        margin-top: 3px;
        color: #8195a5;
        font-size: 10px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        min-height: 24px;
        padding: 0 9px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
    }

    .status-badge.pending {
        background: #fff3d6;
        color: #8a6200;
    }

    .status-badge.scheduled {
        background: #f0edfb;
        color: #6650a3;
    }

    .status-badge.published {
        background: #eaf5fb;
        color: #006fae;
    }

    .status-badge.expired,
    .status-badge.cancelled {
        background: #edf1f4;
        color: #65798a;
    }

    .status-badge.rejected {
        background: #fff0f0;
        color: #a33b3b;
    }

    .action-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .action-group form {
        margin: 0;
    }

    .action-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 30px;
        padding: 0 10px;
        border-radius: 6px;
        border: 1px solid transparent;
        background: transparent;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        white-space: nowrap;
    }

    .action-link.detail {
        border-color: #c9dfe9;
        background: #f2f9fc;
        color: #006fae;
    }

    .action-link.edit {
        border-color: #d0dce5;
        background: #ffffff;
        color: #4f6680;
    }

    .action-link.delete {
        border-color: #e2b8b8;
        background: #fff7f7;
        color: #b33a3a;
    }

    .action-link:hover {
        filter: brightness(.97);
    }

    .empty-state {
        padding: 40px 20px;
        color: #8195a5;
        text-align: center;
    }

    .pagination-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 40px;
        padding: 16px 20px;
        border-top: 1px solid #edf2f5;
    }

    .pagination-info {
        color: #668096;
        font-size: 12px;
        white-space: nowrap;
    }

    .pagination-pages {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-left: auto;
    }

    .pagination-page {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border: 1px solid #d0dce5;
        border-radius: 6px;
        background: #ffffff;
        color: #4f6680;
        font-size: 12px;
        font-weight: 600;
        line-height: 1;
        text-decoration: none;
        box-sizing: border-box;
    }

    .pagination-page:hover {
        border-color: #c9dfe9;
        background: #f2f9fc;
        color: #006fae;
    }

    .pagination-page.active {
        border-color: #006fae;
        background: #006fae;
        color: #ffffff;
    }

    @media (max-width: 900px) {
        .filter-form {
            flex-wrap: wrap;
        }

        .search-group {
            flex: 1 1 100%;
        }

        .filter-group {
            flex: 1;
        }
    }

    @media (max-width: 600px) {
        .page-header {
            flex-direction: column;
        }

        .page-header .btn-primary {
            width: 100%;
        }

        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-group {
            width: 100%;
        }

        .filter-actions {
            width: 100%;
        }

        .filter-actions .btn-primary,
        .filter-actions .btn-secondary {
            flex: 1;
        }

        .pagination-wrapper {
            flex-direction: column;
            justify-content: center;
            gap: 12px;
        }

        .pagination-pages {
            margin-left: 0;
        }
    }
</style>

@endsection