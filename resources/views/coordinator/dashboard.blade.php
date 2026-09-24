@extends('layouts.admin')

@section('title', 'Coordinator Dashboard')

@section('page_title', 'Coordinator Dashboard')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        min-height: 125px;
    }

    .stat-info {
        min-width: 0;
    }

    .stat-label {
        color: var(--muted);
        font-size: 12px;
        font-weight: 600;
    }

    .stat-value {
        margin-top: 8px;
        color: var(--navy);
        font-size: 28px;
        font-weight: 700;
    }

    .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: var(--cyan-light);
        color: var(--blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(300px, 1fr);
        gap: 20px;
    }

    .section-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }

    .section-header {
        padding: 18px 20px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .section-header h3 {
        margin: 0;
        color: var(--navy);
        font-size: 15px;
    }

    .section-header span {
        color: var(--muted);
        font-size: 12px;
    }

    .section-body {
        padding: 0;
    }

    .reservation-row {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
    }

    .reservation-row:last-child {
        border-bottom: 0;
    }

    .reservation-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .reservation-number {
        color: var(--navy);
        font-size: 13px;
        font-weight: 700;
    }

    .reservation-resource {
        margin-top: 5px;
        color: var(--text);
        font-size: 13px;
    }

    .reservation-meta {
        margin-top: 6px;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.6;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 8px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-pending {
        background: #fff3d6;
        color: #8a6200;
    }

    .status-approved {
        background: #e9faf5;
        color: #187a5d;
    }

    .empty-state {
        padding: 35px 20px;
        text-align: center;
        color: var(--muted);
        font-size: 13px;
    }

    .quick-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
    }

    .quick-item:last-child {
        border-bottom: 0;
    }

    .quick-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: var(--cyan-light);
        color: var(--blue);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .quick-info {
        min-width: 0;
    }

    .quick-label {
        color: var(--muted);
        font-size: 11px;
    }

    .quick-value {
        margin-top: 2px;
        color: var(--navy);
        font-size: 14px;
        font-weight: 700;
    }

    .section-action {
        color: var(--blue);
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
    }

    .section-action:hover {
        color: var(--navy);
    }

    @media (max-width: 1100px) {
        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .reservation-top {
            align-items: flex-start;
        }

        .section-header {
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')

    <div class="content-header">
        <h2>Overview</h2>

        <p>
            Reservation activity and approval summary.
        </p>
    </div>


    {{-- STATISTICS --}}
    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">
                    Pending Reservations
                </div>

                <div class="stat-value">
                    {{ $stats['pending_reservations'] }}
                </div>
            </div>

            <div class="stat-icon">
                ◷
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">
                    Approved Reservations
                </div>

                <div class="stat-value">
                    {{ $stats['approved_reservations'] }}
                </div>
            </div>

            <div class="stat-icon">
                ✓
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">
                    Today's Reservations
                </div>

                <div class="stat-value">
                    {{ $stats['today_reservations'] }}
                </div>
            </div>

            <div class="stat-icon">
                ▣
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">
                    Upcoming Reservations
                </div>

                <div class="stat-value">
                    {{ $stats['upcoming_reservations'] }}
                </div>
            </div>

            <div class="stat-icon">
                ◫
            </div>
        </div>

    </div>


    {{-- LOWER CONTENT --}}
    <div class="dashboard-grid">

        {{-- PENDING RESERVATIONS --}}
        <section class="section-card">

            <div class="section-header">
                <h3>Pending Reservations</h3>

                <a
                    href="{{ route('coordinator.reservations.index', ['status' => 'PENDING']) }}"
                    class="section-action"
                >
                    View All
                </a>
            </div>

            <div class="section-body">

                @forelse($pendingReservations as $reservation)

                    @php
                        $resource = $reservation->room
                            ?? $reservation->trainingRoom
                            ?? $reservation->field;

                        $building = $reservation->room?->building
                            ?? $reservation->trainingRoom?->building;
                    @endphp

                    <div class="reservation-row">

                        <div class="reservation-top">

                            <div>
                                <div class="reservation-number">
                                    {{ $reservation->reservation_number }}
                                </div>

                                <div class="reservation-resource">
                                    {{ $resource?->name ?? 'Resource unavailable' }}
                                </div>
                            </div>

                            <span class="status-badge status-pending">
                                PENDING
                            </span>

                        </div>

                        <div class="reservation-meta">

                            {{ $reservation->user->name }}

                            ·

                            {{ $building?->name ?? 'Field' }}

                            ·

                            {{ $reservation->starts_at->format('d M Y, H:i') }}

                            -

                            {{ $reservation->ends_at->format('H:i') }}

                        </div>

                    </div>

                @empty

                    <div class="empty-state">
                        No pending reservations at the moment.
                    </div>

                @endforelse

            </div>

        </section>


        {{-- RESERVATION SUMMARY --}}
        <section class="section-card">

            <div class="section-header">
                <h3>Reservation Summary</h3>

                <span>
                    Current
                </span>
            </div>

            <div class="section-body">

                <div class="quick-item">

                    <div class="quick-icon">
                        ◷
                    </div>

                    <div class="quick-info">
                        <div class="quick-label">
                            Pending Reservations
                        </div>

                        <div class="quick-value">
                            {{ $stats['pending_reservations'] }}
                        </div>
                    </div>

                </div>


                <div class="quick-item">

                    <div class="quick-icon">
                        ✓
                    </div>

                    <div class="quick-info">
                        <div class="quick-label">
                            Approved Reservations
                        </div>

                        <div class="quick-value">
                            {{ $stats['approved_reservations'] }}
                        </div>
                    </div>

                </div>


                <div class="quick-item">

                    <div class="quick-icon">
                        ▣
                    </div>

                    <div class="quick-info">
                        <div class="quick-label">
                            Today's Reservations
                        </div>

                        <div class="quick-value">
                            {{ $stats['today_reservations'] }}
                        </div>
                    </div>

                </div>


                <div class="quick-item">

                    <div class="quick-icon">
                        ◫
                    </div>

                    <div class="quick-info">
                        <div class="quick-label">
                            Upcoming Reservations
                        </div>

                        <div class="quick-value">
                            {{ $stats['upcoming_reservations'] }}
                        </div>
                    </div>

                </div>

            </div>

        </section>


        {{-- UPCOMING RESERVATIONS --}}
        <section class="section-card">

            <div class="section-header">
                <h3>Upcoming Reservations</h3>

                <span>
                    Approved
                </span>
            </div>

            <div class="section-body">

                @forelse($upcomingReservations as $reservation)

                    @php
                        $resource = $reservation->room
                            ?? $reservation->trainingRoom
                            ?? $reservation->field;
                    @endphp

                    <div class="reservation-row">

                        <div class="reservation-top">

                            <div>
                                <div class="reservation-number">
                                    {{ $reservation->reservation_number }}
                                </div>

                                <div class="reservation-resource">
                                    {{ $resource?->name ?? 'Resource unavailable' }}
                                </div>
                            </div>

                            <span class="status-badge status-approved">
                                APPROVED
                            </span>

                        </div>

                        <div class="reservation-meta">

                            {{ $reservation->starts_at->format('d M Y, H:i') }}

                            -

                            {{ $reservation->ends_at->format('H:i') }}

                            ·

                            {{ $reservation->user->name }}

                        </div>

                    </div>

                @empty

                    <div class="empty-state">
                        No upcoming approved reservations.
                    </div>

                @endforelse

            </div>

        </section>

    </div>

@endsection