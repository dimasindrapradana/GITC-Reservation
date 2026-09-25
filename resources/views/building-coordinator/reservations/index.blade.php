@extends('layouts.admin')

@section('title', 'Reservations')
@section('page_title', 'Reservations')

@section('content')

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

<div class="page-card">

    <div class="page-card-header">
        <div>
            <h2>Reservation Management</h2>
            <p>Review and manage reservation requests for your assigned building.</p>
        </div>
    </div>

    <form
        method="GET"
        action="{{ route('building-coordinator.reservations.index') }}"
        class="filter-form"
    >

        <div class="filter-grid">

            <div class="form-group">
                <label for="search">Search</label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Reservation number, requester, resource..."
                >
            </div>

            <div class="form-group">
                <label for="status">Status</label>

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

            <div class="form-group">
                <label for="resource_type">Resource Type</label>

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
                        Room
                    </option>

                    <option
                        value="training_room"
                        @selected($resourceType === 'training_room')
                    >
                        Media Training
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="sort">Sort</label>

                <select
                    id="sort"
                    name="sort"
                >
                    <option
                        value="newest"
                        @selected($sort === 'newest')
                    >
                        Newest
                    </option>

                    <option
                        value="latest"
                        @selected($sort === 'latest')
                    >
                        Latest
                    </option>
                </select>
            </div>

        </div>

        <div class="filter-actions">

            <button
                type="submit"
                class="button button-primary"
            >
                Apply Filters
            </button>

            <a
                href="{{ route('building-coordinator.reservations.index') }}"
                class="button button-secondary"
            >
                Reset
            </a>

        </div>

    </form>

</div>

<div class="page-card table-card">

    <div class="table-wrapper">

        <table class="data-table">

            <thead>
                <tr>
                    <th>Reservation Number</th>
                    <th>Requester</th>
                    <th>Resource</th>
                    <th>Schedule</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($reservations as $reservation)

                    @php
                        if ($reservation->room) {
                            $resourceName = $reservation->room->name;
                            $resourceTypeLabel = 'Room';
                            $buildingName = $reservation->room->building?->name;
                        } elseif ($reservation->trainingRoom) {
                            $resourceName = $reservation->trainingRoom->name;
                            $resourceTypeLabel = 'Media Training';
                            $buildingName = $reservation->trainingRoom->building?->name;
                        } else {
                            $resourceName = 'Unknown Resource';
                            $resourceTypeLabel = '-';
                            $buildingName = null;
                        }
                    @endphp

                    <tr>

                        <td>
                            <div class="primary-text">
                                {{ $reservation->reservation_number }}
                            </div>
                        </td>

                        <td>
                            <div class="primary-text">
                                {{ $reservation->user?->name ?? '-' }}
                            </div>

                            <div class="secondary-text">
                                {{ $reservation->user?->employee_number ?? '-' }}
                            </div>
                        </td>

                        <td>
                            <div class="primary-text">
                                {{ $resourceName }}
                            </div>

                            <div class="secondary-text">
                                {{ $resourceTypeLabel }}

                                @if ($buildingName)
                                    · {{ $buildingName }}
                                @endif
                            </div>
                        </td>

                        <td>
                            <div class="primary-text">
                                {{ $reservation->starts_at?->format('d M Y') }}
                            </div>

                            <div class="secondary-text">
                                {{ $reservation->starts_at?->format('H:i') }}
                                -
                                {{ $reservation->ends_at?->format('H:i') }}
                            </div>
                        </td>

                        <td>

                            @if ($reservation->status === 'PENDING')

                                <span class="status-badge status-pending">
                                    Pending
                                </span>

                            @elseif ($reservation->status === 'APPROVED')

                                <span class="status-badge status-approved">
                                    Approved
                                </span>

                            @elseif ($reservation->status === 'REJECTED')

                                <span class="status-badge status-rejected">
                                    Rejected
                                </span>

                            @elseif ($reservation->status === 'CANCELLED')

                                <span class="status-badge status-cancelled">
                                    Cancelled
                                </span>

                            @else

                                <span class="status-badge">
                                    {{ $reservation->status }}
                                </span>

                            @endif

                        </td>

                        <td>
                            <div class="secondary-text">
                                {{ $reservation->created_at?->format('d M Y H:i') }}
                            </div>
                        </td>

                        <td>

                            @if ($reservation->status === 'PENDING')

                                <a
                                    href="{{ route('building-coordinator.reservations.show', $reservation) }}"
                                    class="button take-action button-small"
                                >
                                    TAKE ACTION!
                                </a>

                            @else

                                <a
                                    href="{{ route('building-coordinator.reservations.show', $reservation) }}"
                                    class="button button-secondary button-small"
                                >
                                    Detail
                                </a>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                No reservations found.
                            </div>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if ($reservations->hasPages())

        <div class="pagination-row">

            <div class="pagination-info">
                Showing
                {{ $reservations->firstItem() ?? 0 }}
                to
                {{ $reservations->lastItem() ?? 0 }}
                of
                {{ $reservations->total() }}
                results
            </div>

            <div class="pagination-links">

                @foreach ($reservations->getUrlRange(1, $reservations->lastPage()) as $page => $url)

                    <a
                        href="{{ $url }}"
                        class="pagination-button {{ $page == $reservations->currentPage() ? 'active' : '' }}"
                    >
                        {{ $page }}
                    </a>

                @endforeach

            </div>

        </div>

    @endif

</div>

@endsection

@push('styles')
<style>

    .page-card {
        background: #ffffff;
        border: 1px solid #e7edf2;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .page-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 22px 24px;
        border-bottom: 1px solid #e7edf2;
    }

    .page-card-header h2 {
        margin: 0;
        color: #102a43;
        font-size: 18px;
        font-weight: 700;
    }

    .page-card-header p {
        margin: 5px 0 0;
        color: #6b7c93;
        font-size: 13px;
    }

    .filter-form {
        padding: 20px 24px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .form-group label {
        color: #334e68;
        font-size: 13px;
        font-weight: 600;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        min-height: 40px;
        padding: 9px 12px;
        border: 1px solid #cbd5df;
        border-radius: 7px;
        background: #ffffff;
        color: #243b53;
        font-size: 13px;
        box-sizing: border-box;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #00a6d6;
        box-shadow: 0 0 0 3px rgba(0, 166, 214, 0.10);
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        margin-top: 18px;
    }

    .button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 8px 15px;
        border-radius: 7px;
        border: 1px solid transparent;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        box-sizing: border-box;
    }

    .button-primary {
        background: #007fae;
        color: #ffffff;
    }

    .button-primary:hover {
        background: #006d96;
    }

    .button-secondary {
        background: #ffffff;
        color: #334e68;
        border-color: #cbd5df;
    }

    .button-secondary:hover {
        background: #f5f8fa;
    }

    .button-small {
        min-height: 34px;
        padding: 6px 12px;
        font-size: 12px;
    }

    .take-action {
        background: #f59e0b;
        border-color: #f59e0b;
        color: #ffffff;
        font-weight: 700;
        padding: 6px 8px;
        font-size: 10px;
    }

    .take-action:hover {
        background: #d97706;
        border-color: #d97706;
    }

    .table-card {
        overflow: hidden;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }

    .data-table th {
        padding: 13px 16px;
        background: #f7fafc;
        border-bottom: 1px solid #e7edf2;
        color: #486581;
        font-size: 11px;
        font-weight: 700;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        white-space: nowrap;
    }

    .data-table td {
        padding: 15px 16px;
        border-bottom: 1px solid #edf2f7;
        color: #243b53;
        font-size: 13px;
        vertical-align: middle;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .data-table tbody tr:hover {
        background: #fbfdfe;
    }

    .primary-text {
        color: #243b53;
        font-weight: 600;
        line-height: 1.4;
    }

    .secondary-text {
        margin-top: 3px;
        color: #829ab1;
        font-size: 12px;
        line-height: 1.4;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        min-height: 26px;
        padding: 4px 9px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-pending {
        background: #fff8e6;
        color: #9a6700;
    }

    .status-approved {
        background: #eaf7ee;
        color: #15803d;
    }

    .status-rejected {
        background: #fceeee;
        color: #b42318;
    }

    .status-cancelled {
        background: #f1f3f5;
        color: #667085;
    }

    .empty-state {
        padding: 50px 20px;
        color: #829ab1;
        text-align: center;
        font-size: 13px;
    }

    .pagination-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 16px 20px;
        border-top: 1px solid #e7edf2;
    }

    .pagination-info {
        color: #6b7c93;
        font-size: 12px;
    }

    .pagination-links {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .pagination-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0 9px;
        border: 1px solid #cbd5df;
        border-radius: 6px;
        background: #ffffff;
        color: #486581;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
    }

    .pagination-button:hover {
        background: #f5f8fa;
    }

    .pagination-button.active {
        background: #007fae;
        border-color: #007fae;
        color: #ffffff;
    }

    .alert {
        margin-bottom: 20px;
        padding: 13px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
    }

    .alert-success {
        background: #edf8f1;
        border: 1px solid #b7e0c3;
        color: #176b36;
    }

    .alert-error {
        background: #fceeee;
        border: 1px solid #f2c4c0;
        color: #b42318;
    }

    @media (max-width: 1100px) {

        .filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

    }

    @media (max-width: 768px) {

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .page-card-header,
        .filter-form {
            padding: 18px;
        }

        .pagination-row {
            align-items: flex-start;
            flex-direction: column;
        }

    }

</style>
@endpush