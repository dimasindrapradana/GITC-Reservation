@extends('layouts.admin')

@section('title', 'Buildings')
@section('page_title', 'Buildings')

@section('content')

<div class="page-header">

    <div>
        <h2>Buildings</h2>

        <p class="page-description">
            Manage buildings and their assigned resources.
        </p>
    </div>

    <a
        href="{{ route('buildings.create') }}"
        class="btn btn-primary"
    >
        Add Building
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
            <h3>Building List</h3>

            <p>
                All registered buildings.
            </p>
        </div>

    </div>

    <form
        action="{{ route('buildings.index') }}"
        method="GET"
        class="filter-bar"
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
                placeholder="Search building name..."
                class="form-control"
            >

        </div>

        <div class="filter-group">

            <label for="filter">
                Filter
            </label>

            <select
                id="filter"
                name="filter"
                class="form-control"
            >

                <option value="">
                    All Buildings
                </option>

                <option
                    value="with_rooms"
                    @selected($filter === 'with_rooms')
                >
                    With Rooms
                </option>

                <option
                    value="with_training_rooms"
                    @selected($filter === 'with_training_rooms')
                >
                    With Media Training
                </option>

                <option
                    value="empty"
                    @selected($filter === 'empty')
                >
                    Empty Buildings
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
                href="{{ route('buildings.index') }}"
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
                    <th>Building</th>
                    <th>Rooms</th>
                    <th>Media Training</th>
                    <th>Total Resources</th>
                    <th>Actions</th>
                </tr>

            </thead>

            <tbody>

                @forelse ($buildings as $building)

                    <tr>

                        <td>
                            {{ $buildings->firstItem() + $loop->index }}
                        </td>

                        <td>
                            <strong>
                                {{ $building->name }}
                            </strong>
                        </td>

                        <td>
                            {{ $building->rooms_count }}
                        </td>

                        <td>
                            {{ $building->training_rooms_count }}
                        </td>

                        <td>
                            {{ $building->rooms_count + $building->training_rooms_count }}
                        </td>

                        <td>

                            <div class="action-group">

                                <a
                                    href="{{ route('buildings.edit', $building) }}"
                                    class="action-link edit"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('buildings.destroy', $building) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this building?');"
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
                            colspan="6"
                            class="empty-table"
                        >

                            @if ($search !== '' || $filter !== '')

                                No buildings match the current search or filter.

                            @else

                                No buildings available.

                            @endif

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if ($buildings->hasPages())

        <div class="pagination-wrapper">

            <div class="pagination-info">
                Showing
                {{ $buildings->firstItem() }}
                to
                {{ $buildings->lastItem() }}
                of
                {{ $buildings->total() }}
                results
            </div>

            <div class="pagination-pages">

                @for (
                    $page = 1;
                    $page <= $buildings->lastPage();
                    $page++
                )

                    @if ($page === $buildings->currentPage())

                        <span class="pagination-page active">
                            {{ $page }}
                        </span>

                    @else

                        <a
                            href="{{ $buildings->url($page) }}"
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
        justify-content: space-between;
        align-items: flex-start;
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

    .filter-bar {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        padding: 18px 20px;
        background: #fbfdfe;
        border-bottom: 1px solid #edf2f5;
    }

    .search-group {
        flex: 1;
        min-width: 220px;
    }

    .filter-group {
        width: 210px;
    }

    .search-group,
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .search-group label,
    .filter-group label {
        color: #4f6680;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .form-control {
        width: 100%;
        min-height: 40px;
        padding: 0 12px;
        border: 1px solid #cfdde6;
        border-radius: 7px;
        background: #ffffff;
        color: #12304a;
        font-family: inherit;
        font-size: 13px;
        outline: none;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #006fae;
        box-shadow: 0 0 0 3px rgba(0, 111, 174, 0.08);
    }

    .filter-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

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
        letter-spacing: 0.03em;
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
        box-sizing: border-box;
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
        filter: brightness(0.97);
    }

    .empty-table {
        padding: 50px 20px !important;
        color: #668096 !important;
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

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 17px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
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
        background: #003b6f;
        border-color: #003b6f;
    }

    .btn-secondary {
        border: 1px solid #d0dce5;
        background: #ffffff;
        color: #4f6680;
    }

    .btn-secondary:hover {
        background: #f7fafc;
    }

    @media (max-width: 800px) {

        .filter-bar {
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

    @media (max-width: 700px) {

        .page-header {
            flex-direction: column;
        }

        .page-header .btn {
            width: 100%;
        }

    }

    @media (max-width: 600px) {

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