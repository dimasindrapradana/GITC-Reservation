@extends('layouts.admin')

@section('title', 'Rooms')
@section('page_title', 'Rooms')

@section('content')

<div class="page-header">

    <div>
        <h2>Rooms</h2>

        <p class="page-description">
            Manage rooms and their availability.
        </p>
    </div>

    <a
        href="{{ route('rooms.create') }}"
        class="btn btn-primary"
    >
        Add Room
    </a>

</div>

@if (session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif

@if (session('error'))

    <div class="alert alert-error">
        {{ session('error') }}
    </div>

@endif

<div class="content-card">

    <div class="card-header">

        <div>
            <h3>Room List</h3>

            <p>
                All registered rooms.
            </p>
        </div>

    </div>

    {{-- Search & Filter --}}

    <form
        action="{{ route('rooms.index') }}"
        method="GET"
        class="filter-form"
    >

        <div class="search-group">

            <label for="search">
                Search
            </label>

            <input
                type="text"
                id="search"
                name="search"
                value="{{ $search }}"
                class="filter-control search-control"
                placeholder="Search room or building..."
            >

        </div>

        <div class="filter-group">

            <label for="building">
                Building
            </label>

            <select
                id="building"
                name="building"
                class="filter-control"
            >

                <option
                    value=""
                    @selected($building === '')
                >
                    All Buildings
                </option>

                @foreach ($buildings as $item)

                    <option
                        value="{{ $item->id }}"
                        @selected((string) $building === (string) $item->id)
                    >
                        {{ $item->name }}
                    </option>

                @endforeach

            </select>

        </div>

        <div class="filter-group">

            <label for="filter">
                Status
            </label>

            <select
                id="filter"
                name="filter"
                class="filter-control"
            >

                <option
                    value=""
                    @selected($filter === '')
                >
                    All Rooms
                </option>

                <option
                    value="available"
                    @selected($filter === 'available')
                >
                    Available
                </option>

                <option
                    value="maintenance"
                    @selected($filter === 'maintenance')
                >
                    Maintenance
                </option>

            </select>

        </div>

        <div class="filter-group">

            <label for="sort">
                Sort By
            </label>

            <select
                id="sort"
                name="sort"
                class="filter-control"
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
                class="btn btn-primary"
            >
                Search
            </button>

            <a
                href="{{ route('rooms.index') }}"
                class="btn btn-secondary"
            >
                Reset
            </a>

        </div>

    </form>

    <div class="table-wrapper">

        <table class="data-table">

            <thead>

                <tr>

                    <th>No.</th>
                    <th>Room</th>
                    <th>Building</th>
                    <th>Capacity</th>
                    <th>LCD</th>
                    <th>Whiteboard</th>
                    <th>Status</th>
                    <th>Reservations</th>
                    <th>Actions</th>

                </tr>

            </thead>

            <tbody>

                @forelse ($rooms as $room)

                    <tr>

                        <td>
                            {{ $rooms->firstItem() + $loop->index }}
                        </td>

                        <td>

                            <strong>
                                {{ $room->name }}
                            </strong>

                        </td>

                        <td>
                            {{ $room->building->name }}
                        </td>

                        <td>
                            {{ $room->capacity }}
                        </td>

                        <td>
                            {{ $room->lcd_count }}
                        </td>

                        <td>
                            {{ $room->whiteboard_count }}
                        </td>

                        <td>

                            <span
                                class="status-badge status-{{ strtolower($room->status) }}"
                            >
                                {{ $room->status }}
                            </span>

                        </td>

                        <td>
                            {{ $room->reservations_count }}
                        </td>

                        <td>

                            <div class="action-group">

                                <a
                                    href="{{ route('rooms.show', $room) }}"
                                    class="action-link detail"
                                >
                                    Detail
                                </a>

                                <a
                                    href="{{ route('rooms.edit', $room) }}"
                                    class="action-link edit"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('rooms.destroy', $room) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this room?');"
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
                            colspan="9"
                            class="empty-table"
                        >
                            No rooms available.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if ($rooms->hasPages())

        <div class="pagination-wrapper">

            <div class="pagination-info">

                Showing
                {{ $rooms->firstItem() }}
                to
                {{ $rooms->lastItem() }}
                of
                {{ $rooms->total() }}
                results

            </div>

            <div class="pagination-pages">

                @for (
                    $page = 1;
                    $page <= $rooms->lastPage();
                    $page++
                )

                    @if ($page === $rooms->currentPage())

                        <span class="pagination-page active">
                            {{ $page }}
                        </span>

                    @else

                        <a
                            href="{{ $rooms->url($page) }}"
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

@endsection

@push('styles')

<style>

    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-header h2 {
        margin: 0 0 6px;
        color: #12304a;
        font-size: 24px;
    }

    .page-description {
        margin: 0;
        color: #668096;
        font-size: 14px;
    }

    .content-card {
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #d9e5ed;
        border-radius: 12px;
    }

    .card-header {
        padding: 20px;
        border-bottom: 1px solid #edf2f5;
    }

    .card-header h3 {
        margin: 0 0 5px;
        color: #12304a;
        font-size: 16px;
    }

    .card-header p {
        margin: 0;
        color: #668096;
        font-size: 13px;
    }

    /* Search & Filter */

    .filter-form {
        display: flex;
        align-items: flex-end;
        gap: 14px;
        padding: 18px 20px;
        border-bottom: 1px solid #edf2f5;
        background: #fbfcfd;
    }

    .search-group,
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .search-group {
        flex: 1;
        min-width: 220px;
    }

    .filter-group {
        width: 170px;
    }

    .search-group label,
    .filter-group label {
        color: #12304a;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
    }

    .filter-control {
        width: 100%;
        min-height: 38px;
        padding: 8px 11px;
        border: 1px solid #d0dce5;
        border-radius: 7px;
        outline: none;
        background: #ffffff;
        color: #4f6680;
        font-family: inherit;
        font-size: 12px;
        box-sizing: border-box;
    }

    .filter-control:focus {
        border-color: #006fae;
        box-shadow: 0 0 0 3px rgba(0, 111, 174, .08);
    }

    .filter-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-actions .btn {
        min-height: 38px;
    }

    /* Table */

    .table-wrapper {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        padding: 13px 16px;
        background: #f8fafb;
        border-bottom: 1px solid #d9e5ed;
        color: #668096;
        font-size: 11px;
        font-weight: 700;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .03em;
        white-space: nowrap;
    }

    .data-table td {
        padding: 15px 16px;
        border-bottom: 1px solid #edf2f5;
        color: #4f6680;
        font-size: 13px;
        white-space: nowrap;
    }

    .data-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .data-table strong {
        color: #12304a;
        font-weight: 700;
    }

    /* Status */

    .status-badge {
        display: inline-flex;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
    }

    .status-available {
        background: #eaf5fb;
        color: #006fae;
    }

    .status-maintenance {
        background: #fff3d6;
        color: #8a6200;
    }

    /* Actions */

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

    /* Empty */

    .empty-table {
        padding: 50px 20px !important;
        color: #668096 !important;
        text-align: center;
    }

    /* Pagination */

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

    /* Buttons */

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 17px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        box-sizing: border-box;
    }

    .btn-primary {
        border: 1px solid #006fae;
        background: #006fae;
        color: #ffffff;
    }

    .btn-primary:hover {
        border-color: #003b6f;
        background: #003b6f;
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

    /* Alerts */

    .alert {
        margin-bottom: 20px;
        padding: 14px 16px;
        border-radius: 8px;
        font-size: 13px;
    }

    .alert-success {
        border: 1px solid #b9dfc8;
        background: #f3fbf6;
        color: #267344;
    }

    .alert-error {
        border: 1px solid #edc4c4;
        background: #fff7f7;
        color: #9b2c2c;
    }

    /* Responsive */

    @media (max-width: 1100px) {

        .filter-form {
            flex-wrap: wrap;
        }

        .search-group {
            flex: 1 1 100%;
        }

        .filter-group {
            flex: 1;
            width: auto;
        }

    }

    @media (max-width: 700px) {

        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }

        .search-group,
        .filter-group {
            width: 100%;
        }

        .filter-actions {
            width: 100%;
        }

        .filter-actions .btn {
            flex: 1;
        }

    }

    @media (max-width: 600px) {

        .page-header {
            flex-direction: column;
        }

        .page-header .btn {
            width: 100%;
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

@endpush