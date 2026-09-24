@extends('layouts.admin')

@section('title', 'Reservation Details')

@section('content')

<style>
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-title {
        margin: 0;
        color: #19324a;
        font-size: 24px;
        font-weight: 700;
        line-height: 1.2;
    }

    .page-description {
        margin: 8px 0 0;
        color: #71869a;
        font-size: 13px;
        line-height: 1.6;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .header-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 36px;
        padding: 0 14px;
        border: 1px solid #d0dce5;
        border-radius: 7px;
        background: #ffffff;
        color: #4f6680;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
    }

    .header-button:hover {
        background: #f6f9fb;
    }

    .content-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(280px, 360px);
        gap: 18px;
    }

    .content-card {
        overflow: hidden;
        border: 1px solid #e4ebf0;
        border-radius: 10px;
        background: #ffffff;
    }

    .card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf2f5;
    }

    .card-title {
        margin: 0;
        color: #19324a;
        font-size: 15px;
        font-weight: 700;
    }

    .detail-list {
        margin: 0;
    }

    .detail-row {
        display: grid;
        grid-template-columns: 180px minmax(0, 1fr);
        gap: 20px;
        padding: 15px 20px;
        border-bottom: 1px solid #edf2f5;
    }

    .detail-row:last-child {
        border-bottom: 0;
    }

    .detail-label {
        color: #7b8fa0;
        font-size: 12px;
        font-weight: 600;
    }

    .detail-value {
        color: #19324a;
        font-size: 12px;
        line-height: 1.6;
        word-break: break-word;
    }

    .detail-value.muted {
        color: #7b8fa0;
    }

    .reservation-number {
        font-weight: 700;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 82px;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
    }

    .status-pending {
        background: #fff3d6;
        color: #8a6200;
    }

    .status-approved {
        background: #eaf5fb;
        color: #006fae;
    }

    .status-rejected {
        background: #fff0f0;
        color: #a33b3b;
    }

    .status-cancelled {
        background: #edf1f4;
        color: #65798a;
    }

    .resource-card {
        padding: 20px;
    }

    .resource-type {
        color: #7b8fa0;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .resource-name {
        margin-top: 6px;
        color: #19324a;
        font-size: 18px;
        font-weight: 700;
    }

    .resource-location {
        margin-top: 6px;
        color: #71869a;
        font-size: 12px;
    }

    .resource-divider {
        height: 1px;
        margin: 18px 0;
        background: #edf2f5;
    }

    .resource-meta {
        display: grid;
        gap: 12px;
    }

    .meta-item {
        display: flex;
        justify-content: space-between;
        gap: 16px;
    }

    .meta-label {
        color: #7b8fa0;
        font-size: 11px;
    }

    .meta-value {
        color: #40576d;
        font-size: 11px;
        font-weight: 600;
        text-align: right;
    }

    @media (max-width: 800px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .page-header {
            flex-direction: column;
        }

        .detail-row {
            grid-template-columns: 1fr;
            gap: 5px;
        }

        .header-actions {
            width: 100%;
        }
    }
</style>

<div class="page-header">

    <div>
        <h1 class="page-title">
            Reservation Details
        </h1>

        <p class="page-description">
            View reservation information and booking details.
        </p>
    </div>

    <div class="header-actions">

        <a
            href="{{ route('reservations.index') }}"
            class="header-button"
        >
            Back to Reservations
        </a>

    </div>

</div>

<div class="content-grid">

    <div class="content-card">

        <div class="card-header">
            <h2 class="card-title">
                Reservation Information
            </h2>
        </div>

        <div class="detail-list">

            <div class="detail-row">
                <div class="detail-label">
                    Reservation Number
                </div>

                <div class="detail-value reservation-number">
                    {{ $reservation->reservation_number }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">
                    Requester
                </div>

                <div class="detail-value">
                    {{ $reservation->user->name ?? '-' }}

                    @if ($reservation->user?->employee_number)
                        <br>
                        <span class="detail-value muted">
                            {{ $reservation->user->employee_number }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">
                    Schedule
                </div>

                <div class="detail-value">
                    {{ $reservation->starts_at->format('d M Y, H:i') }}
                    -
                    {{ $reservation->ends_at->format('H:i') }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">
                    Instructor
                </div>

                <div class="detail-value">
                    {{ $reservation->instructor ?: '-' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">
                    Description
                </div>

                <div class="detail-value">
                    {{ $reservation->description ?: '-' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">
                    Status
                </div>

                <div class="detail-value">
                    <span
                        class="status-badge status-{{ strtolower($reservation->status) }}"
                    >
                        {{ $reservation->status }}
                    </span>
                </div>
            </div>

            @if ($reservation->status === 'REJECTED')

                <div class="detail-row">
                    <div class="detail-label">
                        Rejection Reason
                    </div>

                    <div class="detail-value">
                        {{ $reservation->rejection_reason ?: '-' }}
                    </div>
                </div>

            @endif

            <div class="detail-row">
                <div class="detail-label">
                    Created At
                </div>

                <div class="detail-value">
                    {{ $reservation->created_at?->format('d M Y, H:i') ?? '-' }}
                </div>
            </div>

        </div>

    </div>

    <div class="content-card">

        <div class="card-header">
            <h2 class="card-title">
                Reserved Resource
            </h2>
        </div>

        @php
            if ($reservation->room) {
                $resourceName = $reservation->room->name;
                $resourceType = 'Room';
                $resourceLocation = $reservation->room->building->name ?? '-';
                $capacity = $reservation->room->capacity;
            } elseif ($reservation->trainingRoom) {
                $resourceName = $reservation->trainingRoom->name;
                $resourceType = 'Training Room';
                $resourceLocation = $reservation->trainingRoom->building->name ?? '-';
                $capacity = $reservation->trainingRoom->capacity;
            } elseif ($reservation->field) {
                $resourceName = $reservation->field->name;
                $resourceType = 'Field';
                $resourceLocation = null;
                $capacity = $reservation->field->capacity;
            } else {
                $resourceName = '-';
                $resourceType = '-';
                $resourceLocation = null;
                $capacity = 0;
            }
        @endphp

        <div class="resource-card">

            <div class="resource-type">
                {{ $resourceType }}
            </div>

            <div class="resource-name">
                {{ $resourceName }}
            </div>

            @if ($resourceLocation)
                <div class="resource-location">
                    {{ $resourceLocation }}
                </div>
            @endif

            <div class="resource-divider"></div>

            <div class="resource-meta">

                <div class="meta-item">
                    <span class="meta-label">
                        Capacity
                    </span>

                    <span class="meta-value">
                        {{ $capacity }}
                    </span>
                </div>

                @if ($reservation->room)
                    <div class="meta-item">
                        <span class="meta-label">
                            LCD
                        </span>

                        <span class="meta-value">
                            {{ $reservation->room->lcd_count }}
                        </span>
                    </div>

                    <div class="meta-item">
                        <span class="meta-label">
                            Whiteboard
                        </span>

                        <span class="meta-value">
                            {{ $reservation->room->whiteboard_count }}
                        </span>
                    </div>
                @endif

                @if ($reservation->trainingRoom)
                    <div class="meta-item">
                        <span class="meta-label">
                            Simulation Type
                        </span>

                        <span class="meta-value">
                            {{ $reservation->trainingRoom->simulation_type }}
                        </span>
                    </div>
                @endif

            </div>

        </div>

    </div>

</div>

@endsection