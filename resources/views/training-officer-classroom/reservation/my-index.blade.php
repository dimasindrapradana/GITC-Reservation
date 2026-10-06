@extends('layouts.training-officer-classroom')

@section('title', 'My Reservations')

@section('content')

    <div class="page-header">
    <div>
        <h1>My Reservations</h1>
        <p>
            View and track your classroom reservation requests.
        </p>
    </div>

    <div class="page-header-actions">

        @if (!empty($unreadReservationIds))
            <form
                action="{{ route('training-officer.classroom.my-reservations.mark-all-as-read') }}"
                method="POST"
            >
                @csrf

                <button
                    type="submit"
                    class="mark-all-read-button"
                >
                    Mark All as Read
                </button>
            </form>
        @endif

         <a
                href="{{ route('training-officer.classroom.home') }}"
                class="back-button"
            >
                ← Back
            </a>
    </div>
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

        <div class="card-header">
            <div>
                <h2>Classroom Reservation History</h2>
                <p>
                    View the status and details of your classroom reservations.
                </p>
            </div>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Reservation</th>
                        <th>Resource</th>
                        <th>Schedule</th>
                        <th>Event</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($reservations as $index => $reservation)

                        @php
                            if ($reservation->room) {
                                $resourceTypeLabel = 'Room';
                                $resourceName = $reservation->room->name;
                                $resourceLocation = $reservation->room->building->name ?? '—';
                            } elseif ($reservation->trainingRoom) {
                                $resourceTypeLabel = 'Media Training';
                                $resourceName = $reservation->trainingRoom->name;
                                $resourceLocation = $reservation->trainingRoom->building->name ?? '—';
                            } elseif ($reservation->field) {
                                $resourceTypeLabel = 'Field';
                                $resourceName = $reservation->field->name;
                                $resourceLocation = '—';
                            } else {
                                $resourceTypeLabel = '—';
                                $resourceName = '—';
                                $resourceLocation = '—';
                            }
                        @endphp

                        <tr>

                            <td>
                                {{ $reservations->firstItem() + $index }}
                            </td>

                            <td>
                                <div class="reservation-number">
                                    {{ $reservation->reservation_number }}

                                    @if (in_array($reservation->id, $unreadReservationIds, true))
                                        <span class="updated-indicator">
                                            <span class="updated-dot"></span>
                                            Updated
                                        </span>
                                    @endif
                                </div>
                            </td>

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

                            <td>
                                <div class="primary-text event-name">
                                    {{ $reservation->event_name }}
                                </div>

                                <div class="secondary-text">
                                    {{ $reservation->total_person }}
                                    {{ $reservation->total_person == 1 ? 'person' : 'people' }}
                                </div>
                            </td>

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

                            <td>
                                <div class="action-group">

                                    {{-- Detail is always available --}}
                                   <a
                                        href="{{ route('training-officer.classroom.my-reservations.show', $reservation) }}"
                                        class="action-link detail"
                                    >
                                        Detail
                                    </a>

                                    {{-- Only PENDING reservations can be edited or cancelled --}}
                                    @if ($reservation->status === 'PENDING')


                                        <form
                                            action="{{ route('reservations.destroy', $reservation) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to cancel this reservation?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            
                                        </form>

                                    @elseif ($reservation->status === 'APPROVED')


                                    @elseif ($reservation->status === 'REJECTED')

                                        <span class="action-note">
                                            Closed
                                        </span>

                                    @elseif ($reservation->status === 'CANCELLED')

                                        <span class="action-note">
                                            Cancelled
                                        </span>

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
                                <div class="empty-icon">
                                    📋
                                </div>

                                <div class="empty-title">
                                    No reservations yet
                                </div>

                                <div class="empty-description">
                                    Your reservation requests will appear here.
                                </div>
                            </td>
                        </tr>

                    @endforelse
                </tbody>
            </table>
        </div>

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

                        @if ($page === $reservations->currentPage())

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
    /* =========================================================
       PAGE HEADER
    ========================================================== */

    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-header-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.page-header-actions form {
    margin: 0;
}

.mark-all-read-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 34px;
    padding: 0 12px;
    border: 1px solid #d5dee7;
    border-radius: 6px;
    background: #ffffff;
    color: #4f6680;
    font-family: inherit;
    font-size: 11px;
    font-weight: 700;
    line-height: 1;
    cursor: pointer;
    white-space: nowrap;
    transition:
        background 0.15s ease,
        border-color 0.15s ease,
        color 0.15s ease,
        transform 0.15s ease;
    }

    .mark-all-read-button:hover {
        border-color: #b9c9d6;
        background: #f8fafc;
        color: #344f67;
        transform: translateY(-1px);
    }

    .page-header h1 {
        margin: 0 0 6px;
        color: #0f2747;
        font-size: 25px;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .page-header p {
        margin: 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.5;
    }

    .primary-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 17px;
        border: 1px solid #1e5aa8;
        border-radius: 8px;
        background: #1e5aa8;
        color: #ffffff;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        transition:
            background 0.18s ease,
            border-color 0.18s ease,
            transform 0.18s ease,
            box-shadow 0.18s ease;
    }

    .primary-button:hover {
        background: #17498a;
        border-color: #17498a;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(30, 90, 168, 0.16);
    }

    /* =========================================================
       ALERT
    ========================================================== */

    .alert {
        margin-bottom: 18px;
        padding: 12px 14px;
        border-radius: 8px;
        font-size: 12px;
        line-height: 1.5;
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

    /* =========================================================
       CONTENT CARD
    ========================================================== */

    .content-card {
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(15, 39, 71, 0.025);
    }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 19px 21px;
        border-bottom: 1px solid #edf2f5;
    }

    .card-header h2 {
        margin: 0 0 4px;
        color: #17324d;
        font-size: 15px;
        font-weight: 800;
    }

    .card-header p {
        margin: 0;
        color: #7a8ea1;
        font-size: 11px;
        line-height: 1.5;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 34px;
        padding: 0 12px;
        border: 1px solid #d5dee7;
        border-radius: 6px;
        background: #ffffff;
        color: #4f6680;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        line-height: 1;
        text-decoration: none;
        white-space: nowrap;
        transition:
            background 0.15s ease,
            border-color 0.15s ease,
            color 0.15s ease,
            transform 0.15s ease;
    }

    .back-button:hover {
        border-color: #b9c9d6;
        background: #f8fafc;
        color: #344f67;
        transform: translateY(-1px);
    }

    /* =========================================================
       TABLE
    ========================================================== */

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    table {
        width: 100%;
        min-width: 1020px;
        border-collapse: collapse;
    }

    thead th {
        padding: 12px 14px;
        border-bottom: 1px solid #e3ebef;
        background: #f8fafb;
        color: #6c8195;
        font-size: 10px;
        font-weight: 800;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .045em;
        white-space: nowrap;
    }

    tbody td {
        padding: 15px 14px;
        border-bottom: 1px solid #edf2f5;
        color: #405a70;
        font-size: 12px;
        vertical-align: middle;
    }

    tbody tr:last-child td {
        border-bottom: none;
    }

    tbody tr {
        transition: background 0.15s ease;
    }

    tbody tr:hover {
        background: #fbfdff;
    }

    /* =========================================================
       TEXT
    ========================================================== */

    .reservation-number {
        color: #17324d;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .updated-indicator {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-left: 7px;
        padding: 3px 7px;
        border-radius: 999px;
        background: #fff1f2;
        color: #dc2626;
        font-size: 9px;
        font-weight: 800;
        line-height: 1;
        vertical-align: middle;
        white-space: nowrap;
    }

    .updated-dot {
        width: 6px;
        height: 6px;
        flex: 0 0 6px;
        border-radius: 50%;
        background: #ef4444;
    }
    .primary-text {
        color: #405a70;
        font-size: 12px;
        font-weight: 600;
    }

    .event-name {
        max-width: 220px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .secondary-text {
        margin-top: 4px;
        color: #8294a5;
        font-size: 10px;
        line-height: 1.4;
    }

    /* =========================================================
       STATUS
    ========================================================== */

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 25px;
        padding: 0 10px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-badge.pending {
        background: #fff3d6;
        color: #8a6200;
    }

    .status-badge.approved {
        background: #dcfce7;
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

    /* =========================================================
       ACTIONS
    ========================================================== */

    .action-group {
        display: flex;
        align-items: center;
        gap: 7px;
        white-space: nowrap;
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
        border: 1px solid transparent;
        border-radius: 6px;
        background: transparent;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        line-height: 1;
        text-decoration: none;
        cursor: pointer;
        white-space: nowrap;
        transition:
            background 0.15s ease,
            border-color 0.15s ease,
            color 0.15s ease,
            transform 0.15s ease;
    }

    .action-link:hover {
        transform: translateY(-1px);
    }

    .action-link.detail {
        border-color: #c9dfe9;
        background: #f2f9fc;
        color: #006fae;
    }

    .action-link.detail:hover {
        border-color: #a9cfdd;
        background: #eaf6fb;
        color: #005f95;
    }

    .action-link.edit {
        border-color: #d5dee7;
        background: #ffffff;
        color: #4f6680;
    }

    .action-link.edit:hover {
        border-color: #b9c9d6;
        background: #f8fafc;
        color: #344f67;
    }

    .action-link.delete {
        border-color: #e2b8b8;
        background: #fff7f7;
        color: #b33a3a;
    }

    .action-link.delete:hover {
        border-color: #d69a9a;
        background: #fff0f0;
        color: #9f2f2f;
    }

    .action-note {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 30px;
        padding: 0 10px;
        border: 1px solid #e5eaf0;
        border-radius: 6px;
        background: #f8fafc;
        color: #94a3b8;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================== */

    .empty-state {
        padding: 54px 20px !important;
        color: #8294a5 !important;
        text-align: center;
    }

    .empty-icon {
        margin-bottom: 10px;
        font-size: 25px;
        line-height: 1;
        opacity: .75;
    }

    .empty-title {
        margin-bottom: 5px;
        color: #526b82;
        font-size: 13px;
        font-weight: 700;
    }

    .empty-description {
        color: #94a3b8;
        font-size: 11px;
    }

    /* =========================================================
       PAGINATION
    ========================================================== */

    .pagination-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 30px;
        padding: 16px 20px;
        border-top: 1px solid #edf2f5;
    }

    .pagination-info {
        color: #668096;
        font-size: 11px;
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
        font-size: 11px;
        font-weight: 700;
        line-height: 1;
        text-decoration: none;
        box-sizing: border-box;
        transition:
            background 0.15s ease,
            border-color 0.15s ease,
            color 0.15s ease;
    }

    .pagination-page:hover {
        border-color: #b8d5e2;
        background: #f2f9fc;
        color: #006fae;
    }

    .pagination-page.active {
        border-color: #1e5aa8;
        background: #1e5aa8;
        color: #ffffff;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 800px) {

        .page-header {
            flex-direction: column;
            align-items: stretch;
        }
        .page-header-actions {
            align-self: flex-start;
        }

        .back-button {
            align-self: flex-start;
        }

        .primary-button {
            width: 100%;
        }

        .card-header {
            padding: 17px;
        }

        .pagination-wrapper {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .pagination-pages {
            margin-left: 0;
        }
    }

    @media (max-width: 480px) {

        .page-header h1 {
            font-size: 22px;
        }

        .page-header p {
            font-size: 12px;
        }

        .content-card {
            border-radius: 8px;
        }

        .card-header h2 {
            font-size: 14px;
        }

        .pagination-pages {
            width: 100%;
            overflow-x: auto;
            padding-bottom: 2px;
        }
    }
</style>
@endpush