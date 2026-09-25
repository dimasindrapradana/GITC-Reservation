@extends('layouts.admin')

@section('title', 'Reservation Report')
@section('page_title', 'Reservation Report')

@section('content')

    <div class="content-header">
        <h2>Reservation Report</h2>
        <p>Review reservation activity by month and export filtered data.</p>
    </div>

    <div class="card">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('building-coordinator.reports.reservations') }}"
                class="filter-form"
            >

                <div class="filter-grid">

                    <div class="field-group">
                        <label for="month">
                            Month
                        </label>

                        <select id="month" name="month">

                            @foreach(range(1, 12) as $monthNumber)

                                <option
                                    value="{{ $monthNumber }}"
                                    {{ $month === $monthNumber ? 'selected' : '' }}
                                >
                                    {{ \Carbon\Carbon::create()->month($monthNumber)->format('F') }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="field-group">
                        <label for="year">
                            Year
                        </label>

                        <select id="year" name="year">

                            @foreach(range(now()->year - 2, now()->year + 2) as $yearNumber)

                                <option
                                    value="{{ $yearNumber }}"
                                    {{ $year === $yearNumber ? 'selected' : '' }}
                                >
                                    {{ $yearNumber }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="field-group">
                        <label for="status">
                            Status
                        </label>

                        <select id="status" name="status">

                            <option value="">
                                All Statuses
                            </option>

                            @foreach([
                                'PENDING',
                                'APPROVED',
                                'REJECTED',
                                'CANCELLED',
                            ] as $statusOption)

                                <option
                                    value="{{ $statusOption }}"
                                    {{ $status === $statusOption ? 'selected' : '' }}
                                >
                                    {{ $statusOption }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="field-group">
                        <label for="resource_type">
                            Resource Type
                        </label>

                        <select
                            id="resource_type"
                            name="resource_type"
                        >

                            <option value="">
                                All Resources
                            </option>

                            <option
                                value="room"
                                {{ $resourceType === 'room' ? 'selected' : '' }}
                            >
                                Room
                            </option>

                            <option
                                value="training_room"
                                {{ $resourceType === 'training_room' ? 'selected' : '' }}
                            >
                                Media Training
                            </option>

                            <option
                                value="field"
                                {{ $resourceType === 'field' ? 'selected' : '' }}
                            >
                                Field
                            </option>

                        </select>
                    </div>

                    <div class="field-group">
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

                            @foreach($buildings as $buildingOption)

                                <option
                                    value="{{ $buildingOption->id }}"
                                    {{ $building === (string) $buildingOption->id ? 'selected' : '' }}
                                >
                                    {{ $buildingOption->name }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="field-group field-search">
                        <label for="search">
                            Search
                        </label>

                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search reservation, booker..."
                        >
                    </div>

                </div>

                <div class="filter-actions">

                    <button
                        type="submit"
                        class="button button-primary"
                    >
                        Apply Filter
                    </button>

                    <a
                        href="{{ route('building-coordinator.reports.reservations') }}"
                        class="button button-secondary"
                    >
                        Reset
                    </a>

                    <a
                        href="{{ route(
                            'building-coordinator.reports.reservations.export',
                            request()->query()
                        ) }}"
                        class="button button-export"
                    >
                        Export Excel
                    </a>

                </div>

            </form>

        </div>

    </div>

    <div class="statistics-grid">

        <div class="stat-card">
            <div class="stat-label">
                Total Reservations
            </div>

            <div class="stat-value">
                {{ $total }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">
                Pending
            </div>

            <div class="stat-value">
                {{ $pending }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">
                Approved
            </div>

            <div class="stat-value">
                {{ $approved }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">
                Rejected
            </div>

            <div class="stat-value">
                {{ $rejected }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-label">
                Cancelled
            </div>

            <div class="stat-value">
                {{ $cancelled }}
            </div>
        </div>

    </div>

    <div class="card table-card">

        <div class="table-wrapper">

            <table class="data-table">

                <thead>
                    <tr>
                        <th>Reservation</th>
                        <th>User</th>
                        <th>Booker Name</th>
                        <th>Total Person</th>
                        <th>Resource</th>
                        <th>Schedule</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($reservations as $reservation)

                        @php
                            if ($reservation->room !== null) {
                                $resourceTypeLabel = 'Room';
                                $resourceName = $reservation->room->name;
                            } elseif ($reservation->trainingRoom !== null) {
                                $resourceTypeLabel = 'Media Training';
                                $resourceName = $reservation->trainingRoom->name;
                            } elseif ($reservation->field !== null) {
                                $resourceTypeLabel = 'Field';
                                $resourceName = $reservation->field->name;
                            } else {
                                $resourceTypeLabel = '—';
                                $resourceName = '—';
                            }

                            $statusClass = match ($reservation->status) {
                                'PENDING' => 'status-pending',
                                'APPROVED' => 'status-approved',
                                'REJECTED' => 'status-rejected',
                                'CANCELLED' => 'status-cancelled',
                                default => 'status-default',
                            };
                        @endphp

                        <tr>

                            <td>
                                <div class="reservation-number">
                                    {{ $reservation->reservation_number }}
                                </div>
                            </td>

                            <td>
                                <div class="user-main">
                                    {{ $reservation->user?->name ?? '—' }}
                                </div>

                                @if($reservation->user?->employee_number)
                                    <div class="user-sub">
                                        {{ $reservation->user->employee_number }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="booker-name">
                                    {{ $reservation->booker_name }}
                                </div>
                            </td>

                            <td>
                                <div class="total-person">
                                    {{ $reservation->total_person }}
                                </div>
                            </td>

                            <td>
                                <div class="resource-name">
                                    {{ $resourceName }}
                                </div>

                                <div class="resource-type">
                                    {{ $resourceTypeLabel }}
                                </div>
                            </td>

                            <td>
                                <div class="date-main">
                                    {{ $reservation->starts_at?->format('d M Y') }}
                                </div>

                                <div class="date-sub">
                                    {{ $reservation->starts_at?->format('H:i') }}
                                    –
                                    {{ $reservation->ends_at?->format('H:i') }}
                                </div>
                            </td>

                            <td>
                                <span class="status-badge {{ $statusClass }}">
                                    {{ $reservation->status }}
                                </span>
                            </td>

                            <td class="action-column">

                                <a
                                    href="{{ route(
                                        'building-coordinator.reports.reservations.show',
                                        $reservation
                                    ) }}"
                                    class="button button-detail"
                                >
                                    View Detail
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="8"
                                class="empty-state"
                            >
                                No reservations found for the selected period.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="pagination-wrapper">

            <div class="pagination-info">
                Showing
                {{ $reservations->firstItem() ?? 0 }}
                to
                {{ $reservations->lastItem() ?? 0 }}
                of
                {{ $reservations->total() }}
                results
            </div>

            @if($reservations->hasPages())

                <div class="pagination">

                    @foreach($reservations->getUrlRange(
                        max(1, $reservations->currentPage() - 2),
                        min(
                            $reservations->lastPage(),
                            $reservations->currentPage() + 2
                        )
                    ) as $page => $url)

                        <a
                            href="{{ $url }}"
                            class="page-button {{ $page == $reservations->currentPage() ? 'active' : '' }}"
                        >
                            {{ $page }}
                        </a>

                    @endforeach

                </div>

            @endif

        </div>

    </div>

@endsection

@push('styles')
<style>

    .filter-form {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 14px;
    }

    .field-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
        min-width: 0;
    }

    .field-search {
        grid-column: span 2;
    }

    .field-group label {
        color: var(--text);
        font-size: 12px;
        font-weight: 600;
    }

    .field-group input,
    .field-group select {
        width: 100%;
        height: 40px;
        padding: 0 11px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: var(--white);
        color: var(--text);
        font-size: 13px;
        outline: none;
    }

    .field-group input:focus,
    .field-group select:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(0, 111, 174, 0.08);
    }

    .filter-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 0 14px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        border: 1px solid transparent;
        transition:
            background 0.15s ease,
            border-color 0.15s ease,
            color 0.15s ease;
    }

    .button-primary {
        background: #007fae;
        border-color: #007fae;
        color: #ffffff;
    }

    .button-primary:hover {
        background: #006f99;
        border-color: #006f99;
    }

    .button-secondary {
        background: #ffffff;
        border-color: var(--border);
        color: var(--text);
    }

    .button-secondary:hover {
        background: #f3f7fa;
    }

    .button-export {
        background: #176b3a;
        border-color: #176b3a;
        color: #ffffff;
    }

    .button-export:hover {
        background: #12562e;
        border-color: #12562e;
    }

    .statistics-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 14px;
        margin-top: 20px;
    }

    .stat-card {
        padding: 18px;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
    }

    .stat-label {
        color: var(--muted);
        font-size: 11px;
        font-weight: 600;
    }

    .stat-value {
        margin-top: 8px;
        color: var(--navy);
        font-size: 24px;
        font-weight: 700;
    }

    .table-card {
        margin-top: 20px;
        overflow: hidden;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        min-width: 1120px;
        border-collapse: collapse;
    }

    .data-table th {
        padding: 13px 16px;
        background: #f7fafc;
        border-bottom: 1px solid var(--border);
        color: var(--muted);
        font-size: 11px;
        font-weight: 700;
        text-align: left;
        white-space: nowrap;
    }

    .data-table td {
        padding: 15px 16px;
        border-bottom: 1px solid #edf2f6;
        color: var(--text);
        font-size: 12px;
        vertical-align: middle;
    }

    .data-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .data-table tbody tr:hover {
        background: #fbfdfe;
    }

    .reservation-number,
    .booker-name,
    .user-main,
    .resource-name,
    .date-main {
        font-weight: 600;
    }

    .reservation-number {
        color: var(--navy);
        white-space: nowrap;
    }

    .booker-name {
        min-width: 130px;
    }

    .total-person {
        font-weight: 700;
        text-align: center;
    }

    .user-main {
        white-space: nowrap;
    }

    .user-sub,
    .resource-type,
    .date-sub {
        margin-top: 3px;
        color: var(--muted);
        font-size: 11px;
    }

    .resource-name,
    .date-main {
        white-space: nowrap;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        min-height: 26px;
        padding: 0 9px;
        border-radius: 999px;
        font-size: 10px;
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
        background: #fff1f1;
        color: #b42318;
    }

    .status-cancelled {
        background: #f0f2f4;
        color: #596773;
    }

    .status-default {
        background: #eef4f8;
        color: #36566d;
    }

    .action-column {
        width: 120px;
        text-align: right !important;
    }

    .button-detail {
        min-height: 34px;
        padding: 0 12px;
        background: #ffffff;
        border-color: var(--border);
        color: var(--navy);
    }

    .button-detail:hover {
        background: #f3f7fa;
        border-color: #c6d7e2;
    }

    .empty-state {
        padding: 40px 20px !important;
        color: var(--muted) !important;
        text-align: center !important;
    }

    .pagination-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 16px 18px;
        border-top: 1px solid var(--border);
    }

    .pagination-info {
        color: var(--muted);
        font-size: 12px;
    }

    .pagination {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .page-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 8px;
        border: 1px solid var(--border);
        border-radius: 6px;
        background: #ffffff;
        color: var(--text);
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
    }

    .page-button:hover {
        background: #f3f7fa;
    }

    .page-button.active {
        background: var(--blue);
        border-color: var(--blue);
        color: #ffffff;
    }

    @media (max-width: 1100px) {

        .filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .field-search {
            grid-column: 1 / -1;
        }

        .statistics-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

    }

    @media (max-width: 700px) {

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .field-search {
            grid-column: auto;
        }

        .statistics-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .pagination-wrapper {
            align-items: flex-start;
            flex-direction: column;
        }

        .pagination {
            width: 100%;
            justify-content: flex-end;
        }

    }

</style>
@endpush