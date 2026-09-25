@extends('layouts.admin')

@section('title', 'Reservation Detail')
@section('page_title', 'Reservation Detail')

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

<div class="page-header">

    <div>
        <h2>Reservation Detail</h2>
        <p>Review reservation information and take the required action.</p>
    </div>

    <a
        href="{{ route('building-coordinator.reservations.index') }}"
        class="button button-secondary"
    >
        Back to Reservations
    </a>

</div>

<div class="detail-grid">

    <div class="detail-card">

        <div class="detail-card-header">

            <h3>Reservation Information</h3>

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

            @endif

        </div>

        <div class="detail-list">

            <div class="detail-item">

                <span class="detail-label">
                    Reservation Number
                </span>

                <span class="detail-value">
                    {{ $reservation->reservation_number }}
                </span>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Requester
                </span>

                <span class="detail-value">
                    {{ $reservation->user?->name ?? '-' }}
                </span>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Employee Number
                </span>

                <span class="detail-value">
                    {{ $reservation->user?->employee_number ?? '-' }}
                </span>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Total Person
                </span>

                <span class="detail-value">
                    {{ $reservation->total_person ?? '-' }}
                </span>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Event Name
                </span>

                <span class="detail-value">
                    {{ $reservation->event_name ?: '-' }}
                </span>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Booker Name
                </span>

                <span class="detail-value">
                    {{ $reservation->booker_name ?: '-' }}
                </span>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Instructor
                </span>

                <span class="detail-value">
                    {{ $reservation->instructor ?: '-' }}
                </span>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Start
                </span>

                <span class="detail-value">
                    {{ $reservation->starts_at?->format('d M Y, H:i') }}
                </span>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    End
                </span>

                <span class="detail-value">
                    {{ $reservation->ends_at?->format('d M Y, H:i') }}
                </span>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Resource Type
                </span>

                <span class="detail-value">

                    @if ($reservation->room)

                        Room

                    @elseif ($reservation->trainingRoom)

                        Media Training

                    @else

                        -

                    @endif

                </span>

            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Resource
                </span>

                <span class="detail-value">

                    @if ($reservation->room)

                        {{ $reservation->room->name }}

                        @if ($reservation->room->building)

                            <span class="detail-subvalue">
                                {{ $reservation->room->building->name }}
                            </span>

                        @endif

                    @elseif ($reservation->trainingRoom)

                        {{ $reservation->trainingRoom->name }}

                        @if ($reservation->trainingRoom->building)

                            <span class="detail-subvalue">
                                {{ $reservation->trainingRoom->building->name }}
                            </span>

                        @endif

                    @else

                        -

                    @endif

                </span>

            </div>

            <div class="detail-item detail-item-full">

                <span class="detail-label">
                    Additional Info
                </span>

                <span class="detail-value detail-description">
                    {{ $reservation->description ?: '-' }}
                </span>

            </div>

            @if ($reservation->status === 'REJECTED')

                <div class="detail-item detail-item-full">

                    <span class="detail-label">
                        Rejection Reason
                    </span>

                    <span class="detail-value detail-description">
                        {{ $reservation->rejection_reason ?: '-' }}
                    </span>

                </div>

            @endif

        </div>

    </div>

    @if ($reservation->status === 'PENDING')

        <div class="action-card">

            <div id="normal-action-mode">

                <div class="action-card-header">

                    <h3>Reservation Action</h3>

                    <p>
                        Choose an action for this pending reservation.
                    </p>

                </div>

                <div class="action-buttons">

                    <form
                        method="POST"
                        action="{{ route('building-coordinator.reservations.approve', $reservation) }}"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="button button-primary button-full"
                        >
                            Approve
                        </button>

                    </form>

                    <a
                        href="{{ route('building-coordinator.reservations.edit', $reservation) }}"
                        class="button button-dark button-full"
                    >
                        Edit Reservation
                    </a>

                    <button
                        type="button"
                        class="button button-danger button-full"
                        id="show-reject-form"
                    >
                        Reject
                    </button>

                </div>

            </div>

            <div
                id="reject-action-mode"
                style="display: none;"
            >

                <div class="action-card-header">

                    <h3>Reject Reservation</h3>

                    <p>
                        Provide a reason before rejecting this reservation.
                    </p>

                </div>

                <form
                    method="POST"
                    action="{{ route('building-coordinator.reservations.reject', $reservation) }}"
                >
                    @csrf

                    <div class="form-group">

                        <label for="rejection_reason">
                            Rejection Reason
                        </label>

                        <textarea
                            id="rejection_reason"
                            name="rejection_reason"
                            rows="6"
                            maxlength="2000"
                            required
                            placeholder="Enter the reason for rejecting this reservation..."
                        ></textarea>

                        @error('rejection_reason')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                    <div class="action-buttons">

                        <button
                            type="submit"
                            class="button button-danger button-full"
                        >
                            Confirm Reject
                        </button>

                        <button
                            type="button"
                            class="button button-secondary button-full"
                            id="cancel-reject"
                        >
                            Back
                        </button>

                    </div>

                </form>

            </div>

        </div>

    @elseif ($reservation->status === 'APPROVED')

        <div class="action-card">

            <div class="action-card-header">

                <h3>Reservation Action</h3>

                <p>
                    This reservation is currently approved.
                </p>

            </div>

            <div class="action-buttons">

                <a
                    href="{{ route('building-coordinator.reservations.edit', $reservation) }}"
                    class="button button-dark button-full"
                >
                    Edit Reservation
                </a>

                <form
                    method="POST"
                    action="{{ route('building-coordinator.reservations.cancel', $reservation) }}"
                    onsubmit="return confirm('Are you sure you want to cancel this reservation?');"
                >
                    @csrf

                    <button
                        type="submit"
                        class="button button-danger button-full"
                    >
                        Cancel Reservation
                    </button>

                </form>

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
        margin-bottom: 20px;
    }

    .page-header h2 {
        margin: 0;
        color: #102a43;
        font-size: 20px;
        font-weight: 700;
    }

    .page-header p {
        margin: 5px 0 0;
        color: #6b7c93;
        font-size: 13px;
    }

    .button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 8px 15px;
        border: 1px solid transparent;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        box-sizing: border-box;
    }

    .button-primary {
        background: #007fae;
        border-color: #007fae;
        color: #ffffff;
    }

    .button-primary:hover {
        background: #006d96;
        border-color: #006d96;
    }

    .button-dark {
        background: #334155;
        border-color: #334155;
        color: #ffffff;
    }

    .button-dark:hover {
        background: #1e293b;
        border-color: #1e293b;
    }

    .button-secondary {
        background: #ffffff;
        border-color: #cbd5df;
        color: #334e68;
    }

    .button-secondary:hover {
        background: #f5f8fa;
    }

    .button-danger {
        background: #b42318;
        border-color: #b42318;
        color: #ffffff;
    }

    .button-danger:hover {
        background: #912018;
        border-color: #912018;
    }

    .button-full {
        width: 100%;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 320px;
        gap: 20px;
        align-items: start;
    }

    .detail-card,
    .action-card {
        background: #ffffff;
        border: 1px solid #e7edf2;
        border-radius: 12px;
    }

    .detail-card-header,
    .action-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 20px 22px;
        border-bottom: 1px solid #e7edf2;
    }

    .detail-card-header h3,
    .action-card-header h3 {
        margin: 0;
        color: #102a43;
        font-size: 16px;
        font-weight: 700;
    }

    .action-card-header {
        display: block;
    }

    .action-card-header p {
        margin: 5px 0 0;
        color: #6b7c93;
        font-size: 12px;
        line-height: 1.5;
    }

    .detail-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 7px;
        min-width: 0;
        padding: 18px 22px;
        border-bottom: 1px solid #edf2f7;
    }

    .detail-item:nth-last-child(-n + 2) {
        border-bottom: none;
    }

    .detail-item-full {
        grid-column: 1 / -1;
    }

    .detail-label {
        color: #829ab1;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .detail-value {
        color: #243b53;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.5;
    }

    .detail-subvalue {
        display: block;
        margin-top: 3px;
        color: #829ab1;
        font-size: 12px;
        font-weight: 400;
    }

    .detail-description {
        white-space: pre-wrap;
        font-weight: 400;
    }

    .action-card {
        overflow: hidden;
    }

    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 20px 22px;
    }

    .form-group {
        padding: 20px 22px 0;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        color: #334e68;
        font-size: 13px;
        font-weight: 600;
    }

    .form-group textarea {
        width: 100%;
        min-height: 130px;
        padding: 10px 12px;
        border: 1px solid #cbd5df;
        border-radius: 7px;
        background: #ffffff;
        color: #243b53;
        font-family: inherit;
        font-size: 13px;
        line-height: 1.5;
        resize: vertical;
        box-sizing: border-box;
    }

    .form-group textarea:focus {
        outline: none;
        border-color: #00a6d6;
        box-shadow: 0 0 0 3px rgba(0, 166, 214, 0.10);
    }

    .field-error {
        margin-top: 6px;
        color: #b42318;
        font-size: 12px;
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

    @media (max-width: 900px) {

        .detail-grid {
            grid-template-columns: 1fr;
        }

    }

    @media (max-width: 768px) {

        .page-header {
            flex-direction: column;
        }

        .detail-list {
            grid-template-columns: 1fr;
        }

        .detail-item {
            border-bottom: 1px solid #edf2f7 !important;
        }

        .detail-item:last-child {
            border-bottom: none !important;
        }

    }

</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const showRejectForm =
            document.getElementById('show-reject-form');

        const cancelReject =
            document.getElementById('cancel-reject');

        const normalActionMode =
            document.getElementById('normal-action-mode');

        const rejectActionMode =
            document.getElementById('reject-action-mode');

        const rejectionReason =
            document.getElementById('rejection_reason');

        if (
            !showRejectForm ||
            !cancelReject ||
            !normalActionMode ||
            !rejectActionMode
        ) {
            return;
        }

        showRejectForm.addEventListener('click', function () {

            normalActionMode.style.display = 'none';
            rejectActionMode.style.display = 'block';

            if (rejectionReason) {
                rejectionReason.focus();
            }

        });

        cancelReject.addEventListener('click', function () {

            rejectActionMode.style.display = 'none';
            normalActionMode.style.display = 'block';

            if (rejectionReason) {
                rejectionReason.value = '';
            }

        });

    });
</script>
@endpush