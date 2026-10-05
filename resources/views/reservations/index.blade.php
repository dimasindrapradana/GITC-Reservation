@extends('layouts.admin')

@section('title', 'Reservations')

@section('content')
    @php
        $isFieldCoordinator =
            auth()->user()?->role?->name === 'Field Coordinator';
    @endphp

    <div class="page-header">
        <div>
            <h1>Reservations</h1>
            <p>
                Manage reservation requests and schedules.
            </p>
        </div>

        <a
            href="{{ route('reservations.create') }}"
            class="primary-button"
        >
            + Add Reservation
        </a>
    </div>

    @if (session('success'))
        <div class="alert success-alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert error-alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="content-card">

        {{-- <div class="card-header">
            <div>
                <h2>Reservation List</h2>
                <p>
                    View and manage reservation records.
                </p>
            </div>
        </div> --}}


        {{-- ==========================================================
            FILTER
        =========================================================== --}}

        <form
            method="GET"
            action="{{ route('reservations.index') }}"
            class="filter-form"
        >

            {{-- SEARCH --}}
            <div class="filter-row search-row">

                <div class="filter-group search-group">
                    <label for="search">
                        Search
                    </label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search reservations..."
                    >
                </div>

            </div>


            {{-- OTHER FILTERS --}}
            <div class="filter-row">

                @if (!$isFieldCoordinator)

                    <div class="filter-group">
                        <label for="building">
                            Building
                        </label>

                        <select
                            id="building"
                            name="building"
                        >
                            <option value="">
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

                @endif


                {{-- STATUS --}}
                <div class="filter-group">
                    <label for="status">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
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
                            value="APPROVED"
                            @selected($status === 'APPROVED')
                        >
                            Approved
                        </option>

                        <option
                            value="REJECTED"
                            @selected($status === 'REJECTED')
                        >
                            Rejected
                        </option>

                        <option
                            value="CANCELLED"
                            @selected($status === 'CANCELLED')
                        >
                            Cancelled
                        </option>
                    </select>
                </div>


                {{-- RESOURCE TYPE --}}
                <div class="filter-group">
                    <label for="resource_type">
                        Resource Type
                    </label>

                    @if ($isFieldCoordinator)

                        <select
                            id="resource_type"
                            name="resource_type"
                        >
                            <option
                                value="field"
                                selected
                            >
                                Fields
                            </option>
                        </select>

                    @else

                        <select
                            id="resource_type"
                            name="resource_type"
                        >
                            <option value="">
                                All Resources
                            </option>

                            <option
                                value="room"
                                @selected($resourceType === 'room')
                            >
                                Rooms
                            </option>

                            <option
                                value="training_room"
                                @selected($resourceType === 'training_room')
                            >
                                Media Training
                            </option>

                            <option
                                value="field"
                                @selected($resourceType === 'field')
                            >
                                Fields
                            </option>
                        </select>

                    @endif
                </div>


                {{-- DATE FROM --}}
                <div class="filter-group">
                    <label for="date_from">
                        Date From
                    </label>

                    <input
                        type="date"
                        id="date_from"
                        name="date_from"
                        value="{{ request('date_from') }}"
                    >
                </div>


                {{-- DATE TO --}}
                <div class="filter-group">
                    <label for="date_to">
                        Date To
                    </label>

                    <input
                        type="date"
                        id="date_to"
                        name="date_to"
                        value="{{ request('date_to') }}"
                    >
                </div>


                {{-- SORT --}}
                <div class="filter-group">
                    <label for="sort">
                        Sort By
                    </label>

                    <select
                        id="sort"
                        name="sort"
                    >
                        <option
                            value="created_desc"
                            @selected($sort === 'created_desc')
                        >
                            Newest
                        </option>

                        <option
                            value="created_asc"
                            @selected($sort === 'created_asc')
                        >
                            Oldest
                        </option>
                    </select>
                </div>

            </div>


            {{-- FILTER BUTTONS --}}
            <div class="filter-row filter-button-row">

                <div class="filter-actions">

                    <button
                        type="submit"
                        class="search-button"
                    >
                        Search
                    </button>

                    <a
                        href="{{ route('reservations.index') }}"
                        class="reset-button"
                    >
                        Reset
                    </a>

                </div>

            </div>

        </form>


        {{-- ==========================================================
            TABLE
        =========================================================== --}}

        <div class="table-wrapper">

            <table>

                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Reservation</th>
                        <th>Requester</th>
                        <th>Resource</th>
                        <th>Schedule</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>


                <tbody>

                    @forelse ($reservations as $index => $reservation)

                        @php

                            if ($reservation->room) {

                                $resourceTypeLabel = 'Room';

                                $resourceName =
                                    $reservation->room->name;

                                $resourceLocation =
                                    $reservation->room->building->name
                                    ?? '—';

                            } elseif ($reservation->trainingRoom) {

                                $resourceTypeLabel =
                                    'Media Training';

                                $resourceName =
                                    $reservation->trainingRoom->name;

                                $resourceLocation =
                                    $reservation->trainingRoom->building->name
                                    ?? '—';

                            } elseif ($reservation->field) {

                                $resourceTypeLabel =
                                    'Field';

                                $resourceName =
                                    $reservation->field->name;

                                $resourceLocation = '—';

                            } else {

                                $resourceTypeLabel = '—';

                                $resourceName = '—';

                                $resourceLocation = '—';

                            }

                        @endphp


                        <tr>

                            {{-- NO --}}
                            <td>
                                {{ $reservations->firstItem() + $index }}
                            </td>


                            {{-- RESERVATION --}}
                            <td>

                                <div class="reservation-number">
                                    {{ $reservation->reservation_number }}
                                </div>

                                <div class="secondary-text">
                                    {{ $reservation->created_at->format('d M Y') }}
                                </div>

                            </td>


                            {{-- REQUESTER --}}
                            <td>

                                <div class="primary-text">
                                    {{ $reservation->user->name ?? '—' }}
                                </div>

                                <div class="secondary-text">
                                    {{ $reservation->user->employee_number ?? '—' }}
                                </div>

                            </td>


                            {{-- RESOURCE --}}
                            <td>

                                <div class="primary-text">
                                    {{ $resourceName }}
                                </div>

                                <div class="secondary-text">

                                    {{ $resourceTypeLabel }}

                                    @if ($resourceLocation !== '—')
                                        · {{ $resourceLocation }}
                                    @endif

                                </div>

                            </td>


                            {{-- SCHEDULE --}}
                            <td>

                                <div class="primary-text">
                                    {{ $reservation->starts_at->format('d M Y') }}
                                </div>

                                <div class="secondary-text">

                                    {{ $reservation->starts_at->format('H:i') }}

                                    –

                                    {{ $reservation->ends_at->format('H:i') }}

                                </div>

                            </td>


                            {{-- STATUS --}}
                            <td>

                                @if ($reservation->status === 'PENDING')

                                    <span class="status-badge pending">
                                        Pending
                                    </span>

                                @elseif ($reservation->status === 'APPROVED')

                                    <span class="status-badge approved">
                                        Approved
                                    </span>

                                @elseif ($reservation->status === 'REJECTED')

                                    <span class="status-badge rejected">
                                        Rejected
                                    </span>

                                @elseif ($reservation->status === 'CANCELLED')

                                    <span class="status-badge cancelled">
                                        Cancelled
                                    </span>

                                @endif

                            </td>


                            {{-- ACTIONS --}}
                            <td>

                                <div class="action-group">

                                    <a
                                        href="{{ route('reservations.show', $reservation) }}"
                                        class="action-link detail"
                                    >
                                        Detail
                                    </a>


                                    @if (
                                        in_array(
                                            $reservation->status,
                                            ['PENDING', 'APPROVED'],
                                            true
                                        )
                                    )

                                        <a
                                            href="{{ route('reservations.edit', $reservation) }}"
                                            class="action-link edit"
                                        >
                                            Edit
                                        </a>


                                        <form
                                            action="{{ route('reservations.destroy', $reservation) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to cancel this reservation?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="action-link delete"
                                            >
                                                Cancel
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="empty-state"
                            >
                                No reservations found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ==========================================================
            PAGINATION
        =========================================================== --}}

        @if ($reservations->hasPages())

            <div class="pagination-wrapper">

                <div class="pagination-info">

                    Showing
                    {{ $reservations->firstItem() }}
                    to
                    {{ $reservations->lastItem() }}
                    of
                    {{ $reservations->total() }}
                    results

                </div>


                <div class="pagination-pages">

                    @for (
                        $page = 1;
                        $page <= $reservations->lastPage();
                        $page++
                    )

                        @if (
                            $page === $reservations->currentPage()
                        )

                            <span class="pagination-page active">
                                {{ $page }}
                            </span>

                        @else

                            <a
                                href="{{ $reservations->url($page) }}"
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

    /* ==========================================================
       PAGE HEADER
    ========================================================== */

    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .page-header h1 {
        margin: 0 0 6px;
        color: #17324d;
        font-size: 24px;
        font-weight: 700;
    }

    .page-header p {
        margin: 0;
        color: #71869a;
        font-size: 13px;
    }


    .primary-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 0 16px;
        border: 1px solid #006fae;
        border-radius: 7px;
        background: #006fae;
        color: #ffffff;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .primary-button:hover {
        background: #005f95;
    }


    /* ==========================================================
       ALERT
    ========================================================== */

    .alert {
        margin-bottom: 18px;
        padding: 12px 14px;
        border-radius: 7px;
        font-size: 12px;
    }

    .success-alert {
        border: 1px solid #b9dfe8;
        background: #eef9fb;
        color: #176276;
    }

    .error-alert {
        border: 1px solid #edc4c4;
        background: #fff7f7;
        color: #9b2c2c;
    }


    /* ==========================================================
       CARD
    ========================================================== */

    .content-card {
        overflow: hidden;
        border: 1px solid #e1e9ee;
        border-radius: 9px;
        background: #ffffff;
    }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 18px 20px;
        border-bottom: 1px solid #edf2f5;
    }

    .card-header h2 {
        margin: 0 0 4px;
        color: #29445d;
        font-size: 15px;
        font-weight: 700;
    }

    .card-header p {
        margin: 0;
        color: #7a8ea1;
        font-size: 11px;
    }


    /* ==========================================================
       FILTER
    ========================================================== */

    .filter-form {
        padding: 16px 20px;
        border-bottom: 1px solid #edf2f5;
        background: #fbfcfd;
    }

    .filter-row {
        display: grid;
        grid-template-columns:
            repeat(6, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 12px;
    }

    .filter-row:last-child {
        margin-bottom: 0;
    }

    .search-row {
        grid-template-columns: 1fr;
    }

    .filter-button-row {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-bottom: 0;
    }


    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 0;
    }

    .search-group {
        width: 100%;
    }


    .filter-group label {
        color: #526b82;
        font-size: 11px;
        font-weight: 700;
    }


    .filter-group input,
    .filter-group select {
        width: 100%;
        min-height: 36px;
        padding: 0 10px;
        border: 1px solid #d5e0e7;
        border-radius: 6px;
        background: #ffffff;
        color: #344f67;
        font-family: inherit;
        font-size: 12px;
        outline: none;
        box-sizing: border-box;
    }


    .filter-group input:focus,
    .filter-group select:focus {
        border-color: #8fc6dc;
        box-shadow: 0 0 0 2px #edf8fc;
    }


    .filter-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }


    /* ==========================================================
       SEARCH / RESET BUTTON
    ========================================================== */

    .search-button,
    .reset-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 36px;
        padding: 0 18px;
        border-radius: 6px;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        box-sizing: border-box;
    }


    .search-button {
        border: 1px solid #006fae;
        background: #006fae;
        color: #ffffff;
    }

    .search-button:hover {
        background: #005f95;
        border-color: #005f95;
    }


    .reset-button {
        border: 1px solid #d0dce5;
        background: #ffffff;
        color: #4f6680;
    }

    .reset-button:hover {
        background: #f7fafc;
    }


    /* ==========================================================
       TABLE
    ========================================================== */

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 950px;
    }


    thead th {
        padding: 12px 14px;
        border-bottom: 1px solid #e3ebef;
        background: #f8fafb;
        color: #6c8195;
        font-size: 10px;
        font-weight: 700;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .04em;
        white-space: nowrap;
    }


    tbody td {
        padding: 14px;
        border-bottom: 1px solid #edf2f5;
        color: #405a70;
        font-size: 12px;
        vertical-align: middle;
    }


    tbody tr:last-child td {
        border-bottom: none;
    }

    tbody tr:hover {
        background: #fcfdfe;
    }


    .reservation-number {
        color: #29445d;
        font-size: 12px;
        font-weight: 700;
    }


    .primary-text {
        color: #405a70;
        font-size: 12px;
        font-weight: 600;
    }


    .secondary-text {
        margin-top: 3px;
        color: #8294a5;
        font-size: 10px;
    }


    /* ==========================================================
       STATUS
    ========================================================== */

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 24px;
        padding: 0 9px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }


    .status-badge.pending {
        background: #fff3d6;
        color: #8a6200;
    }


    .status-badge.approved {
        background: #eaf5fb;
        color: #15803d;
    }


    .status-badge.rejected {
        background: #fff0f0;
        color: #a33b3b;
    }


    .status-badge.cancelled {
        background: #edf1f4;
        color: #65798a;
    }


    /* ==========================================================
       ACTIONS
    ========================================================== */

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
        font-family: inherit;
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


    /* ==========================================================
       EMPTY
    ========================================================== */

    .empty-state {
        padding: 40px 20px !important;
        color: #8294a5 !important;
        text-align: center;
        font-size: 12px !important;
    }


    /* ==========================================================
       PAGINATION
    ========================================================== */

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


    /* ==========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 1200px) {

        .filter-row {
            grid-template-columns:
                repeat(3, minmax(0, 1fr));
        }

        .search-row {
            grid-template-columns: 1fr;
        }

    }


    @media (max-width: 700px) {

        .page-header {
            flex-direction: column;
            align-items: stretch;
        }


        .primary-button {
            width: 100%;
        }


        .filter-row {
            grid-template-columns: 1fr;
        }


        .filter-button-row {
            justify-content: stretch;
        }


        .filter-actions {
            width: 100%;
        }


        .search-button,
        .reset-button {
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

@endpush