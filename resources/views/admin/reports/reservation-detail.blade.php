@extends('layouts.admin')

@section('title', 'Reservation Detail')
@section('page_title', 'Reservation Detail')

@section('content')

    <div class="content-header detail-header">

        <div class="detail-header-content">
            <h2>Reservation Detail</h2>

            <p>
                Review reservation details and activity information.
            </p>
        </div>

        <a
            href="{{ route('coordinator.reports.reservations') }}"
            class="button button-secondary"
        >
            Back
        </a>

    </div>

    <div class="card reservation-detail-card">

        <div class="reservation-detail-header">

            <div>
                <h3>Reservation Information</h3>

                <p>
                    {{ $reservation->reservation_number }}
                </p>
            </div>

            @php
                $statusClass = match ($reservation->status) {
                    'PENDING' => 'status-pending',
                    'APPROVED' => 'status-approved',
                    'REJECTED' => 'status-rejected',
                    'CANCELLED' => 'status-cancelled',
                    default => 'status-default',
                };
            @endphp

            <span class="status-badge {{ $statusClass }}">
                {{ $reservation->status }}
            </span>

        </div>

        <div class="detail-grid">

            <div class="detail-item">
                <div class="detail-label">
                    Reservation Number
                </div>

                <div class="detail-value">
                    {{ $reservation->reservation_number }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    Requester
                </div>

                <div class="detail-value">
                    {{ $reservation->user?->name ?? '—' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    Employee Number
                </div>

                <div class="detail-value">
                    {{ $reservation->user?->employee_number ?? '—' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    Booker Name
                </div>

                <div class="detail-value">
                    {{ $reservation->booker_name }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    Event Name
                </div>

                <div class="detail-value">
                    {{ $reservation->event_name }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    Total Person
                </div>

                <div class="detail-value">
                    {{ $reservation->total_person }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    Instructor
                </div>

                <div class="detail-value">
                    {{ $reservation->instructor ?: '—' }}
                </div>
            </div>

            @php
                if ($reservation->room !== null) {
                    $resourceType = 'Room';
                    $resourceName = $reservation->room->name;
                    $buildingName = $reservation->room->building?->name ?? '—';
                } elseif ($reservation->trainingRoom !== null) {
                    $resourceType = 'Media Training';
                    $resourceName = $reservation->trainingRoom->name;
                    $buildingName = $reservation->trainingRoom->building?->name ?? '—';
                } elseif ($reservation->field !== null) {
                    $resourceType = 'Field';
                    $resourceName = $reservation->field->name;
                    $buildingName = '—';
                } else {
                    $resourceType = '—';
                    $resourceName = '—';
                    $buildingName = '—';
                }
            @endphp

            <div class="detail-item">
                <div class="detail-label">
                    Resource Type
                </div>

                <div class="detail-value">
                    {{ $resourceType }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    Resource
                </div>

                <div class="detail-value">
                    {{ $resourceName }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    Building
                </div>

                <div class="detail-value">
                    {{ $buildingName }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    Start
                </div>

                <div class="detail-value">
                    {{ $reservation->starts_at?->format('d M Y H:i') ?? '—' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    End
                </div>

                <div class="detail-value">
                    {{ $reservation->ends_at?->format('d M Y H:i') ?? '—' }}
                </div>
            </div>

        </div>

    </div>

    <div class="card additional-info-card">

        <div class="additional-info-header">
            <h3>Additional Information</h3>
        </div>

        <div class="additional-info-body">

            <div class="detail-label">
                Additional Info
            </div>

            <div class="additional-info-text">
                {{ $reservation->description ?: '—' }}
            </div>

            @if($reservation->status === 'REJECTED')

                <div class="rejection-section">

                    <div class="detail-label">
                        Rejection Reason
                    </div>

                    <div class="additional-info-text rejection-text">
                        {{ $reservation->rejection_reason ?: '—' }}
                    </div>

                </div>

            @endif

        </div>

    </div>

@endsection

@push('styles')
<style>

    .detail-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
    }

    .detail-header-content {
        min-width: 0;
    }

    .detail-header-content h2 {
        margin: 0;
    }

    .detail-header-content p {
        margin: 5px 0 0;
    }

    .detail-header > .button {
        flex-shrink: 0;
        margin-top: 2px;
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

    .button-secondary {
        background: #ffffff;
        border-color: var(--border);
        color: var(--text);
    }

    .button-secondary:hover {
        background: #f3f7fa;
        border-color: #c6d7e2;
    }

    .reservation-detail-card,
    .additional-info-card {
        margin-top: 20px;
        overflow: hidden;
    }

    .reservation-detail-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
    }

    .reservation-detail-header h3,
    .additional-info-header h3 {
        margin: 0;
        color: var(--navy);
        font-size: 16px;
        font-weight: 700;
    }

    .reservation-detail-header p {
        margin: 5px 0 0;
        color: var(--muted);
        font-size: 12px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        min-height: 28px;
        padding: 0 10px;
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

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .detail-item {
        padding: 20px 24px;
        border-bottom: 1px solid #edf2f6;
    }

    .detail-item:nth-child(odd) {
        border-right: 1px solid #edf2f6;
    }

    .detail-label {
        color: #52718d;
        font-size: 12px;
        font-weight: 500;
    }

    .detail-value {
        margin-top: 8px;
        color: var(--text);
        font-size: 14px;
        font-weight: 600;
        word-break: break-word;
    }

    .additional-info-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
    }

    .additional-info-body {
        padding: 20px 24px;
    }

    .additional-info-text {
        margin-top: 8px;
        color: var(--text);
        font-size: 13px;
        line-height: 1.7;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .rejection-section {
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
    }

    .rejection-text {
        color: #b42318;
    }

    @media (max-width: 700px) {

        .detail-header {
            align-items: stretch;
            flex-direction: column;
        }

        .detail-header > .button {
            align-self: flex-end;
        }

        .reservation-detail-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .detail-item:nth-child(odd) {
            border-right: 0;
        }

    }

</style>
@endpush