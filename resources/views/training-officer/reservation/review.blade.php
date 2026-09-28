@extends('layouts.training-officer')

@section('title', 'Review Reservation')

@section('head')
<style>
    /* =========================================================
       RESERVATION REVIEW
    ========================================================= */

    .reservation-page {
        width: 100%;
    }

    .reservation-header {
        margin-bottom: 28px;
    }

    .reservation-eyebrow {
        margin: 0 0 7px;
        color: var(--gitc-teal);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .reservation-header-row {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 24px;
    }

    .reservation-title {
        margin: 0;
        color: var(--gitc-navy);
        font-size: 30px;
        font-weight: 800;
        letter-spacing: -0.025em;
    }

    .reservation-description {
        margin: 8px 0 0;
        color: var(--gitc-muted);
        font-size: 14px;
        line-height: 1.6;
    }

    .reservation-back-button {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 0 16px;
        border: 1px solid var(--gitc-border);
        border-radius: 9px;
        background: white;
        color: var(--gitc-navy);
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition:
            border-color 0.18s ease,
            background 0.18s ease,
            color 0.18s ease,
            transform 0.18s ease;
    }

    .reservation-back-button:hover {
        border-color: #b8cbe0;
        background: #f8fbff;
        color: var(--gitc-blue);
        transform: translateY(-1px);
    }

    /* =========================================================
       NOTICE
    ========================================================= */

    .reservation-notice {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 22px;
        padding: 15px 17px;
        border: 1px solid #cfe4e1;
        border-radius: 11px;
        background: #f3faf9;
        color: var(--gitc-text);
    }

    .reservation-notice-icon {
        flex-shrink: 0;
        width: 20px;
        height: 20px;
        margin-top: 1px;
        color: var(--gitc-teal);
    }

    .reservation-notice-content {
        min-width: 0;
    }

    .reservation-notice-title {
        margin: 0;
        color: var(--gitc-navy);
        font-size: 13px;
        font-weight: 800;
    }

    .reservation-notice-text {
        margin: 5px 0 0;
        color: var(--gitc-muted);
        font-size: 12px;
        line-height: 1.6;
    }

    /* =========================================================
       MAIN CARD
    ========================================================= */

    .reservation-card {
        overflow: hidden;
        border: 1px solid var(--gitc-border);
        border-radius: 14px;
        background: white;
        box-shadow: 0 6px 18px rgba(15, 39, 71, 0.035);
    }

    .reservation-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 20px 22px;
        border-bottom: 1px solid var(--gitc-border);
        background: linear-gradient(90deg, #f8fbff 0%, #ffffff 75%);
    }

    .reservation-card-title {
        margin: 0;
        color: var(--gitc-navy);
        font-size: 17px;
        font-weight: 800;
    }

    .reservation-card-description {
        margin: 5px 0 0;
        color: var(--gitc-muted);
        font-size: 12px;
        line-height: 1.6;
    }

    .reservation-count {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 30px;
        padding: 0 11px;
        border-radius: 999px;
        background: var(--gitc-blue-light);
        color: var(--gitc-blue);
        font-size: 10px;
        font-weight: 800;
    }

    /* =========================================================
       RESERVATION ITEM
    ========================================================= */

    .reservation-list {
        width: 100%;
    }

    .reservation-item {
        padding: 22px;
    }

    .reservation-item + .reservation-item {
        border-top: 1px solid #edf1f5;
    }

    .reservation-item-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 18px;
    }

    .reservation-item-title-wrap {
        min-width: 0;
    }

    .reservation-item-number {
        margin: 0 0 5px;
        color: var(--gitc-teal);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .reservation-item-title {
        margin: 0;
        color: var(--gitc-navy);
        font-size: 18px;
        font-weight: 800;
        line-height: 1.4;
    }

    .reservation-resource-type {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 25px;
        padding: 0 9px;
        border-radius: 999px;
        background: var(--gitc-blue-light);
        color: var(--gitc-blue);
        font-size: 9px;
        font-weight: 800;
    }

    /* =========================================================
       DETAILS GRID
    ========================================================= */

    .reservation-details {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1px;
        overflow: hidden;
        border: 1px solid var(--gitc-border);
        border-radius: 10px;
        background: var(--gitc-border);
    }

    .reservation-detail {
        min-width: 0;
        padding: 14px 15px;
        background: white;
    }

    .reservation-detail-label {
        margin: 0 0 5px;
        color: var(--gitc-muted);
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .reservation-detail-value {
        margin: 0;
        overflow-wrap: anywhere;
        color: var(--gitc-text);
        font-size: 13px;
        font-weight: 700;
        line-height: 1.5;
    }

    .reservation-detail-value.primary {
        color: var(--gitc-navy);
    }

    /* =========================================================
       DESCRIPTION
    ========================================================= */

    .reservation-description-block {
        margin-top: 14px;
        padding: 15px;
        border: 1px solid var(--gitc-border);
        border-radius: 10px;
        background: #fbfcfd;
    }

    .reservation-description-label {
        margin: 0 0 6px;
        color: var(--gitc-muted);
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .reservation-description-value {
        margin: 0;
        color: var(--gitc-text);
        font-size: 12px;
        line-height: 1.7;
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    /* =========================================================
       ITEM EDIT ACTION
    ========================================================= */

    .reservation-item-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 14px;
    }

    .reservation-edit-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 36px;
        padding: 0 13px;
        border: 1px solid var(--gitc-border);
        border-radius: 8px;
        background: white;
        color: var(--gitc-navy);
        font-size: 11px;
        font-weight: 800;
        text-decoration: none;
        transition:
            border-color 0.18s ease,
            background 0.18s ease,
            color 0.18s ease,
            transform 0.18s ease;
    }

    .reservation-edit-button:hover {
        border-color: #b8cbe0;
        background: #f8fbff;
        color: var(--gitc-blue);
        transform: translateY(-1px);
    }

    .reservation-edit-button svg {
        width: 14px;
        height: 14px;
        margin-right: 6px;
    }

    /* =========================================================
       BOTTOM ACTIONS
    ========================================================= */

    .reservation-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-top: 22px;
        padding-top: 20px;
        border-top: 1px solid var(--gitc-border);
    }

    .reservation-action-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        padding: 0 18px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
        cursor: pointer;
        transition:
            background 0.18s ease,
            border-color 0.18s ease,
            color 0.18s ease,
            transform 0.18s ease,
            box-shadow 0.18s ease;
    }

    .reservation-back-action {
        border: 1px solid var(--gitc-border);
        background: white;
        color: var(--gitc-navy);
    }

    .reservation-back-action:hover {
        border-color: #b8cbe0;
        background: #f8fbff;
        color: var(--gitc-blue);
        transform: translateY(-1px);
    }

    .reservation-submit-action {
        border: 1px solid var(--gitc-navy);
        background: var(--gitc-navy);
        color: white;
        box-shadow: 0 5px 12px rgba(15, 39, 71, 0.10);
    }

    .reservation-submit-action:hover {
        border-color: var(--gitc-navy-dark);
        background: var(--gitc-navy-dark);
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(15, 39, 71, 0.15);
    }

    .reservation-submit-action svg,
    .reservation-back-action svg {
        width: 16px;
        height: 16px;
        margin-right: 7px;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .reservation-empty {
        padding: 50px 24px;
        text-align: center;
    }

    .reservation-empty-icon {
        width: 44px;
        height: 44px;
        margin: 0 auto 13px;
        color: #94a3b8;
    }

    .reservation-empty-title {
        margin: 0;
        color: var(--gitc-navy);
        font-size: 16px;
        font-weight: 800;
    }

    .reservation-empty-text {
        max-width: 420px;
        margin: 7px auto 0;
        color: var(--gitc-muted);
        font-size: 12px;
        line-height: 1.6;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 700px) {
        .reservation-header-row {
            align-items: flex-start;
            flex-direction: column;
        }

        .reservation-back-button {
            width: 100%;
        }

        .reservation-details {
            grid-template-columns: 1fr;
        }

        .reservation-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .reservation-item-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .reservation-resource-type {
            align-self: flex-start;
        }

        .reservation-item-actions {
            justify-content: flex-start;
        }
    }

    @media (max-width: 560px) {
        .reservation-title {
            font-size: 25px;
        }

        .reservation-description {
            font-size: 13px;
        }

        .reservation-item {
            padding: 17px;
        }

        .reservation-card-header {
            padding: 17px;
        }

        .reservation-actions {
            align-items: stretch;
            flex-direction: column-reverse;
        }

        .reservation-action-button {
            width: 100%;
        }

        .reservation-edit-button {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')

<div class="reservation-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="reservation-header">

        <p class="reservation-eyebrow">
            GITC Reservation
        </p>

        <div class="reservation-header-row">

            <div>
                <h1 class="reservation-title">
                    Review Reservation
                </h1>

                <p class="reservation-description">
                    Review all reservation details before submitting your request.
                </p>
            </div>

            <a
                href="{{ route('training-officer.cart') }}"
                class="reservation-back-button"
            >
                Back to Cart
            </a>

        </div>

    </div>

    {{-- =====================================================
         NOTICE
    ====================================================== --}}
    <div class="reservation-notice">

        <svg
            class="reservation-notice-icon"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="1.8"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18 9 9 0 000 18z"
            />
        </svg>

        <div class="reservation-notice-content">

            <p class="reservation-notice-title">
                Final Review
            </p>

            <p class="reservation-notice-text">
                Please make sure all reservation details are correct.
                You can edit any reservation before submitting your request.
            </p>

        </div>

    </div>

    {{-- =====================================================
         RESERVATION CARD
    ====================================================== --}}
    <div class="reservation-card">

        <div class="reservation-card-header">

            <div>

                <h2 class="reservation-card-title">
                    Reservation Summary
                </h2>

                <p class="reservation-card-description">
                    {{ $totalItems }} resource(s) will be submitted as separate reservation requests.
                </p>

            </div>

            <span class="reservation-count">
                {{ $totalItems }} {{ $totalItems === 1 ? 'Resource' : 'Resources' }}
            </span>

        </div>

        {{-- =================================================
             RESERVATION LIST
        ================================================== --}}
        @if (count($reservations) > 0)

            <div class="reservation-list">

                @foreach ($reservations as $index => $reservation)

                    <div class="reservation-item">

                        {{-- =================================================
                             ITEM HEADER
                        ================================================== --}}
                        <div class="reservation-item-header">

                            <div class="reservation-item-title-wrap">

                                <p class="reservation-item-number">
                                    Reservation {{ $index + 1 }}
                                </p>

                                <h3 class="reservation-item-title">
                                    {{ $reservation['resource_name'] }}
                                </h3>

                            </div>

                            <span class="reservation-resource-type">
                                {{ match ($reservation['resource_type']) {
                                    'room' => 'Room',
                                    'training_room' => 'Training Media',
                                    'field' => 'Field',
                                    default => 'Resource',
                                } }}
                            </span>

                        </div>

                        {{-- =================================================
                             DETAILS
                        ================================================== --}}
                        <div class="reservation-details">

                            <div class="reservation-detail">

                                <p class="reservation-detail-label">
                                    Event Name
                                </p>

                                <p class="reservation-detail-value primary">
                                    {{ $reservation['event_name'] }}
                                </p>

                            </div>

                            <div class="reservation-detail">

                                <p class="reservation-detail-label">
                                    Booker Name
                                </p>

                                <p class="reservation-detail-value">
                                    {{ $reservation['booker_name'] }}
                                </p>

                            </div>

                            <div class="reservation-detail">

                                <p class="reservation-detail-label">
                                    Start Date & Time
                                </p>

                                <p class="reservation-detail-value">
                                    {{ \Carbon\Carbon::parse($reservation['starts_at'])->format('d M Y, H:i') }}
                                </p>

                            </div>

                            <div class="reservation-detail">

                                <p class="reservation-detail-label">
                                    End Date & Time
                                </p>

                                <p class="reservation-detail-value">
                                    {{ \Carbon\Carbon::parse($reservation['ends_at'])->format('d M Y, H:i') }}
                                </p>

                            </div>

                            <div class="reservation-detail">

                                <p class="reservation-detail-label">
                                    Total Person
                                </p>

                                <p class="reservation-detail-value">
                                    {{ number_format($reservation['total_person']) }}
                                    {{ $reservation['total_person'] == 1 ? 'Person' : 'People' }}
                                </p>

                            </div>

                            <div class="reservation-detail">

                                <p class="reservation-detail-label">
                                    Instructor
                                </p>

                                <p class="reservation-detail-value">
                                    {{ $reservation['instructor'] ?: 'Not specified' }}
                                </p>

                            </div>

                        </div>

                        {{-- =================================================
                             DESCRIPTION
                        ================================================== --}}
                        <div class="reservation-description-block">

                            <p class="reservation-description-label">
                                Description
                            </p>

                            <p class="reservation-description-value">
                                {{ $reservation['description'] }}
                            </p>

                        </div>

                        {{-- =================================================
                             EDIT ACTION
                        ================================================== --}}
                        <div class="reservation-item-actions">

                            <a
                                href="{{ route(
                                    'training-officer.reservation.resource.create',
                                    [
                                        'type' => $reservation['resource_type'],
                                        'resource' => $reservation['resource_id'],
                                    ]
                                ) }}"
                                class="reservation-edit-button"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M16.862 3.487a2.1 2.1 0 013.651 2.1L8.5 17.6 4 19l1.4-4.5L16.862 3.487z"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15.5 5l3.5 3.5"
                                    />
                                </svg>

                                Edit Reservation

                            </a>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="reservation-empty">

                <svg
                    class="reservation-empty-icon"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.6"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M13 3v5h5"
                    />
                </svg>

                <h3 class="reservation-empty-title">
                    No Reservation Data
                </h3>

                <p class="reservation-empty-text">
                    There are no reservation details available to review.
                    Please return to your cart and start the reservation process again.
                </p>

            </div>

        @endif

        {{-- =================================================
             ACTIONS
        ================================================== --}}
        @if (count($reservations) > 0)

            <div class="reservation-actions">

                <a
                    href="{{ route('training-officer.reservation.create') }}"
                    class="reservation-action-button reservation-back-action"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"
                        />
                    </svg>

                    Back to Reservation

                </a>

                <form
                    method="POST"
                    action="{{ route('training-officer.reservation.submit') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="reservation-action-button reservation-submit-action"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 12l2 2 4-4"
                            />
                        </svg>

                        Confirm & Submit Reservation

                    </button>

                </form>

            </div>

        @endif

    </div>

</div>

@endsection