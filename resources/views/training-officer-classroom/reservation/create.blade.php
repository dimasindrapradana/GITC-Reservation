@extends('layouts.training-officer-classroom')

@section('title', 'Create Reservation')

@section('content')

<style>
    /* =========================================================
       CLASSROOM RESERVATION CREATE
    ========================================================= */

    .reservation-page {
        width: 100%;
    }

    /* =========================================================
       HEADER
    ========================================================= */

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
       PROGRESS
    ========================================================= */

    .reservation-progress {
        display: flex;
        align-items: center;

        gap: 12px;

        margin-bottom: 22px;

        padding: 15px 18px;

        border: 1px solid var(--gitc-border);
        border-radius: 11px;

        background: white;
    }

    .reservation-progress-number {
        display: flex;
        align-items: center;
        justify-content: center;

        width: 34px;
        height: 34px;

        flex-shrink: 0;

        border-radius: 9px;

        background: var(--gitc-navy);

        color: white;

        font-size: 12px;
        font-weight: 800;
    }

    .reservation-progress-content {
        min-width: 0;

        flex: 1;
    }

    .reservation-progress-label {
        margin: 0;

        color: var(--gitc-muted);

        font-size: 10px;
        font-weight: 700;

        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .reservation-progress-title {
        margin: 3px 0 0;

        overflow: hidden;

        color: var(--gitc-navy);

        font-size: 13px;
        font-weight: 800;

        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .reservation-progress-track {
        width: 100%;

        height: 4px;

        margin-top: 8px;

        overflow: hidden;

        border-radius: 999px;

        background: #e8edf2;
    }

    .reservation-progress-bar {
        height: 100%;

        border-radius: inherit;

        background: var(--gitc-teal);

        transition: width 0.2s ease;
    }


    /* =========================================================
       VALIDATION ERROR
    ========================================================= */

    .reservation-alert {
        display: flex;
        align-items: flex-start;

        gap: 12px;

        margin-bottom: 22px;

        padding: 15px 17px;

        border: 1px solid #f0c4c4;
        border-radius: 11px;

        background: #fff7f7;

        color: #991b1b;
    }

    .reservation-alert-icon {
        flex-shrink: 0;

        width: 20px;
        height: 20px;

        margin-top: 1px;
    }

    .reservation-alert-content {
        min-width: 0;
    }

    .reservation-alert-title {
        margin: 0;

        color: #991b1b;

        font-size: 13px;
        font-weight: 800;
    }

    .reservation-alert-list {
        margin: 7px 0 0;

        padding-left: 18px;

        color: #b42323;

        font-size: 12px;
        line-height: 1.7;
    }


    /* =========================================================
       MAIN GRID
    ========================================================= */

    .reservation-layout {
        display: grid;

        grid-template-columns:
            minmax(0, 1.65fr)
            minmax(300px, 0.8fr);

        gap: 20px;

        align-items: start;
    }


    /* =========================================================
       FORM CARD
    ========================================================= */

    .reservation-card {
        overflow: hidden;

        border: 1px solid var(--gitc-border);
        border-radius: 14px;

        background: white;

        box-shadow:
            0 6px 18px rgba(15, 39, 71, 0.035);
    }

    .reservation-card-header {
        padding: 20px 22px;

        border-bottom: 1px solid var(--gitc-border);

        background:
            linear-gradient(
                90deg,
                #f8fbff 0%,
                #ffffff 75%
            );
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

    .reservation-form {
        padding: 22px;
    }


    /* =========================================================
       FORM FIELDS
    ========================================================= */

    .reservation-field {
        margin-bottom: 20px;
    }

    .reservation-field:last-child {
        margin-bottom: 0;
    }

    .reservation-label {
        display: block;

        margin-bottom: 7px;

        color: var(--gitc-text);

        font-size: 12px;
        font-weight: 800;
    }

    .reservation-required {
        color: #c53030;
    }

    .reservation-input,
    .reservation-textarea {
        width: 100%;

        border: 1px solid var(--gitc-border);
        border-radius: 9px;

        background: white;

        color: var(--gitc-text);

        font-family: inherit;
        font-size: 13px;

        outline: none;

        transition:
            border-color 0.18s ease,
            box-shadow 0.18s ease,
            background 0.18s ease;
    }

    .reservation-input {
        min-height: 44px;

        padding: 0 13px;
    }

    .reservation-textarea {
        min-height: 110px;

        padding: 12px 13px;

        line-height: 1.6;

        resize: vertical;
    }

    .reservation-input::placeholder,
    .reservation-textarea::placeholder {
        color: #9aa8b7;
    }

    .reservation-input:hover,
    .reservation-textarea:hover {
        border-color: #c6d3df;
    }

    .reservation-input:focus,
    .reservation-textarea:focus {
        border-color: var(--gitc-teal);

        box-shadow:
            0 0 0 3px rgba(24, 157, 150, 0.10);
    }

    .reservation-field-help {
        margin: 6px 0 0;

        color: var(--gitc-muted);

        font-size: 11px;
        line-height: 1.5;
    }

    .reservation-field-error {
        margin: 6px 0 0;

        color: #b42323;

        font-size: 11px;
        font-weight: 600;
    }


    /* =========================================================
       SCHEDULE
    ========================================================= */

    .schedule-section {
        margin: 4px 0 24px;

        padding: 18px;

        border: 1px solid var(--gitc-border);
        border-radius: 11px;

        background: #fbfcfe;
    }

    .schedule-section-header {
        margin-bottom: 18px;
    }

    .schedule-section-title {
        margin: 0;

        color: var(--gitc-navy);

        font-size: 14px;
        font-weight: 800;
    }

    .schedule-section-description {
        margin: 5px 0 0;

        color: var(--gitc-muted);

        font-size: 11px;
        line-height: 1.6;
    }

    .schedule-row {
        display: grid;

        grid-template-columns:
            minmax(0, 1fr)
            minmax(0, 1fr);

        gap: 16px;

        margin-bottom: 16px;
    }

    .schedule-row:last-of-type {
        margin-bottom: 0;
    }

    .schedule-field {
        min-width: 0;
    }

    .schedule-field-label {
        display: block;

        margin-bottom: 7px;

        color: var(--gitc-text);

        font-size: 11px;
        font-weight: 800;
    }

    .schedule-field-label .required {
        color: #c53030;
    }

    .schedule-input {
        width: 100%;

        min-height: 44px;

        padding: 0 12px;

        border: 1px solid var(--gitc-border);
        border-radius: 9px;

        background: white;

        color: var(--gitc-text);

        font-family: inherit;
        font-size: 13px;

        outline: none;

        transition:
            border-color 0.18s ease,
            box-shadow 0.18s ease;
    }

    .schedule-input:hover {
        border-color: #c6d3df;
    }

    .schedule-input:focus {
        border-color: var(--gitc-teal);

        box-shadow:
            0 0 0 3px rgba(24, 157, 150, 0.10);
    }

    .schedule-input.schedule-invalid {
        border-color: #c53030;

        box-shadow:
            0 0 0 3px rgba(197, 48, 48, 0.08);
    }

    .schedule-note {
        margin-top: 16px;

        padding: 10px 12px;

        border-left: 3px solid var(--gitc-teal);

        background: #f1f8f7;

        color: var(--gitc-muted);

        font-size: 10px;
        line-height: 1.6;
    }

    .schedule-field-error {
        margin: 6px 0 0;

        color: #b42323;

        font-size: 11px;
        font-weight: 600;
    }


    /* =========================================================
       FORM ACTIONS
    ========================================================= */

    .reservation-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 14px;

        margin-top: 24px;
        padding-top: 20px;

        border-top: 1px solid var(--gitc-border);
    }

    .reservation-action-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-height: 43px;

        padding: 0 17px;

        border-radius: 9px;

        font-size: 12px;
        font-weight: 800;

        text-decoration: none;

        transition:
            background 0.18s ease,
            border-color 0.18s ease,
            color 0.18s ease,
            transform 0.18s ease,
            box-shadow 0.18s ease;
    }

    .reservation-cancel-button {
        border: 1px solid var(--gitc-border);

        background: white;

        color: var(--gitc-navy);
    }

    .reservation-cancel-button:hover {
        border-color: #b8cbe0;

        background: #f8fbff;

        color: var(--gitc-blue);

        transform: translateY(-1px);
    }

    .reservation-submit-button {
        border: 1px solid var(--gitc-navy);

        background: var(--gitc-navy);

        color: white;

        box-shadow:
            0 5px 12px rgba(15, 39, 71, 0.10);

        cursor: pointer;
    }

    .reservation-submit-button:hover {
        border-color: var(--gitc-navy-dark);

        background: var(--gitc-navy-dark);

        color: white;

        transform: translateY(-1px);

        box-shadow:
            0 8px 18px rgba(15, 39, 71, 0.15);
    }


    /* =========================================================
       CURRENT RESOURCE
    ========================================================= */

    .reservation-resource-card {
        position: sticky;

        top: 92px;

        overflow: hidden;

        border: 1px solid var(--gitc-border);
        border-radius: 14px;

        background: white;

        box-shadow:
            0 6px 18px rgba(15, 39, 71, 0.035);
    }

    .reservation-resource-header {
        padding: 19px 20px;

        border-bottom: 1px solid var(--gitc-border);

        background:
            linear-gradient(
                90deg,
                #f8fbff 0%,
                #ffffff 80%
            );
    }

    .reservation-resource-eyebrow {
        margin: 0 0 5px;

        color: var(--gitc-teal);

        font-size: 10px;
        font-weight: 800;

        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .reservation-resource-title {
        margin: 0;

        color: var(--gitc-navy);

        font-size: 18px;
        font-weight: 800;

        line-height: 1.4;
    }

    .reservation-resource-type {
        display: inline-flex;
        align-items: center;

        min-height: 21px;

        margin-top: 9px;

        padding: 0 8px;

        border-radius: 999px;

        background: var(--gitc-blue-light);

        color: var(--gitc-blue);

        font-size: 9px;
        font-weight: 800;
    }

    .reservation-resource-image {
        width: 100%;
        height: 180px;

        overflow: hidden;

        background: #eef3f7;
    }

    .reservation-resource-image img {
        width: 100%;
        height: 100%;

        display: block;

        object-fit: cover;
    }

    .reservation-no-image {
        width: 100%;
        height: 100%;

        display: flex;
        align-items: center;
        justify-content: center;

        color: #94a3b8;

        font-size: 11px;
        font-weight: 700;
    }

    .reservation-resource-info {
        padding: 17px 19px;
    }

    .reservation-resource-detail {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;

        gap: 16px;

        padding: 10px 0;

        border-bottom: 1px solid #edf1f5;
    }

    .reservation-resource-detail:first-child {
        padding-top: 0;
    }

    .reservation-resource-detail:last-child {
        padding-bottom: 0;

        border-bottom: 0;
    }

    .reservation-resource-detail-label {
        color: var(--gitc-muted);

        font-size: 10px;
        font-weight: 700;
    }

    .reservation-resource-detail-value {
        color: var(--gitc-navy);

        font-size: 11px;
        font-weight: 800;

        text-align: right;
    }


    /* =========================================================
       FOOTER INFO
    ========================================================= */

    .reservation-resource-footer {
        padding: 15px 18px;

        border-top: 1px solid #dcebe8;

        background:
            linear-gradient(
                100deg,
                #eef8f7 0%,
                #f6fbfa 100%
            );
    }

    .reservation-resource-footer-title {
        margin: 0;

        color: var(--gitc-navy);

        font-size: 11px;
        font-weight: 800;
    }

    .reservation-resource-footer-text {
        margin: 4px 0 0;

        color: var(--gitc-muted);

        font-size: 10px;
        line-height: 1.6;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 980px) {

        .reservation-layout {
            grid-template-columns: 1fr;
        }

        .reservation-resource-card {
            position: static;
        }
    }

    @media (max-width: 700px) {

        .reservation-header-row {
            align-items: flex-start;

            flex-direction: column;
        }

        .reservation-back-button {
            width: 100%;
        }

        .schedule-row {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 560px) {

        .reservation-title {
            font-size: 25px;
        }

        .reservation-description {
            font-size: 13px;
        }

        .reservation-form {
            padding: 17px;
        }

        .reservation-card-header {
            padding: 17px;
        }

        .schedule-section {
            padding: 15px;
        }

        .reservation-actions {
            align-items: stretch;

            flex-direction: column-reverse;
        }

        .reservation-action-button {
            width: 100%;
        }

        .reservation-resource-image {
            height: 155px;
        }
    }
</style>


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
                    Create Reservation
                </h1>

                <p class="reservation-description">
                    Complete the reservation details for each selected room.
                </p>

            </div>

            <a
                href="{{ route('training-officer.classroom.cart') }}"
                class="reservation-back-button"
            >
                Back to Booking List
            </a>

        </div>

    </div>


    {{-- =====================================================
         PROGRESS
    ====================================================== --}}

    <div class="reservation-progress">

        <div class="reservation-progress-number">
            {{ $step }}
        </div>

        <div class="reservation-progress-content">

            <p class="reservation-progress-label">
                Reservation Step {{ $step }} of {{ $totalSteps }}
            </p>

            <p class="reservation-progress-title">
                {{ $resource['name'] }}
            </p>

            <div class="reservation-progress-track">

                <div
                    class="reservation-progress-bar"
                    style="width: {{ $totalSteps > 0 ? (($step / $totalSteps) * 100) : 0 }}%;"
                ></div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         VALIDATION ERRORS
    ====================================================== --}}

    @if ($errors->any())

        <div class="reservation-alert">

            <svg
                class="reservation-alert-icon"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 9v3.5m0 3.5h.01M10.3 3.7l-7.4 13a2 2 0 001.74 3h14.72a2 2 0 001.74-3l-7.4-13a2 2 0 00-3.4 0z"
                />
            </svg>

            <div class="reservation-alert-content">

                <p class="reservation-alert-title">
                    Please check the following:
                </p>

                <ul class="reservation-alert-list">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        </div>

    @endif


    {{-- =====================================================
         MAIN FORM
    ====================================================== --}}

    <form
        method="POST"
        action="{{ route(
            'training-officer.classroom.reservation.resource.store',
            [
                'room' => $roomId,
            ]
        ) }}"
        id="classroom-reservation-form"
    >

        @csrf

        @if ($isEdit)

            <input
                type="hidden"
                name="edit"
                value="1"
            >

        @endif


        <div class="reservation-layout">

            {{-- =================================================
                 FORM
            ================================================== --}}

            <div>

                <div class="reservation-card">

                    <div class="reservation-card-header">

                        <h2 class="reservation-card-title">
                            Reservation Details
                        </h2>

                        <p class="reservation-card-description">
                            Provide the information required for this classroom reservation.
                        </p>

                    </div>


                    <div class="reservation-form">

                        {{-- =================================================
                             EVENT NAME
                        ================================================== --}}

                        <div class="reservation-field">

                            <label
                                for="event_name"
                                class="reservation-label"
                            >
                                Event Name
                                <span class="reservation-required">*</span>
                            </label>

                            <input
                                type="text"
                                id="event_name"
                                name="event_name"
                                value="{{ old(
                                    'event_name',
                                    $currentData['event_name'] ?? ''
                                ) }}"
                                required
                                maxlength="255"
                                placeholder="Enter the name of your event"
                                class="reservation-input"
                            >

                            @error('event_name')

                                <p class="reservation-field-error">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- =================================================
                             BOOKER NAME
                        ================================================== --}}

                        <div class="reservation-field">

                            <label
                                for="booker_name"
                                class="reservation-label"
                            >
                                Booker Name
                                <span class="reservation-required">*</span>
                            </label>

                            <input
                                type="text"
                                id="booker_name"
                                name="booker_name"
                                value="{{ old(
                                    'booker_name',
                                    $currentData['booker_name']
                                    ?? auth()->user()->name
                                ) }}"
                                required
                                maxlength="255"
                                placeholder="Enter the booker's name"
                                class="reservation-input"
                            >

                            @error('booker_name')

                                <p class="reservation-field-error">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- =================================================
                             SCHEDULE
                        ================================================== --}}

                        <div class="schedule-section">

                            <div class="schedule-section-header">

                                <h3 class="schedule-section-title">
                                    Schedule
                                </h3>

                                <p class="schedule-section-description">
                                    Select the reservation start and end date and time.
                                </p>

                            </div>


                            {{-- START --}}

                            <div class="schedule-row">

                                <div class="schedule-field">

                                    <label
                                        for="start_date"
                                        class="schedule-field-label"
                                    >
                                        Start Date
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="date"
                                        id="start_date"
                                        class="schedule-input"
                                        value="{{ old(
                                            'start_date',
                                            $currentData['start_date'] ?? ''
                                        ) }}"
                                        required
                                    >

                                </div>


                                <div class="schedule-field">

                                    <label
                                        for="start_time"
                                        class="schedule-field-label"
                                    >
                                        Start Time
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="time"
                                        id="start_time"
                                        class="schedule-input"
                                        value="{{ old(
                                            'start_time',
                                            $currentData['start_time'] ?? ''
                                        ) }}"
                                        step="60"
                                        required
                                    >

                                </div>

                            </div>


                            {{-- END --}}

                            <div class="schedule-row">

                                <div class="schedule-field">

                                    <label
                                        for="end_date"
                                        class="schedule-field-label"
                                    >
                                        End Date
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="date"
                                        id="end_date"
                                        class="schedule-input"
                                        value="{{ old(
                                            'end_date',
                                            $currentData['end_date'] ?? ''
                                        ) }}"
                                        required
                                    >

                                </div>


                                <div class="schedule-field">

                                    <label
                                        for="end_time"
                                        class="schedule-field-label"
                                    >
                                        End Time
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="time"
                                        id="end_time"
                                        class="schedule-input"
                                        value="{{ old(
                                            'end_time',
                                            $currentData['end_time'] ?? ''
                                        ) }}"
                                        step="60"
                                        required
                                    >

                                </div>

                            </div>


                            {{-- HIDDEN VALUES --}}

                            <input
                                type="hidden"
                                name="starts_at"
                                id="starts_at"
                                value="{{ old(
                                    'starts_at',
                                    $currentData['starts_at'] ?? ''
                                ) }}"
                            >

                            <input
                                type="hidden"
                                name="ends_at"
                                id="ends_at"
                                value="{{ old(
                                    'ends_at',
                                    $currentData['ends_at'] ?? ''
                                ) }}"
                            >


                            @error('starts_at')

                                <div class="schedule-field-error">
                                    {{ $message }}
                                </div>

                            @enderror


                            @error('ends_at')

                                <div class="schedule-field-error">
                                    {{ $message }}
                                </div>

                            @enderror


                            <div
                                id="schedule-client-error"
                                class="schedule-field-error"
                                style="display: none;"
                            ></div>


                            <div class="schedule-note">

                                Reservations use the Asia/Jakarta timezone.
                                Adjacent time ranges are allowed, but overlapping
                                PENDING or APPROVED reservations are not allowed.

                            </div>

                        </div>


                        {{-- =================================================
                             TOTAL PERSON
                        ================================================== --}}

                        <div class="reservation-field">

                            <label
                                for="total_person"
                                class="reservation-label"
                            >
                                Total Person
                                <span class="reservation-required">*</span>
                            </label>

                            <input
                                type="number"
                                id="total_person"
                                name="total_person"
                                value="{{ old(
                                    'total_person',
                                    $currentData['total_person'] ?? ''
                                ) }}"
                                required
                                min="1"
                                placeholder="Number of participants"
                                class="reservation-input"
                            >

                            <p class="reservation-field-help">

                                Maximum capacity for this room:
                                {{ $resource['capacity'] ?? 'Not specified' }}.

                            </p>

                            @error('total_person')

                                <p class="reservation-field-error">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- =================================================
                             INSTRUCTOR
                        ================================================== --}}

                        <div class="reservation-field">

                            <label
                                for="instructor"
                                class="reservation-label"
                            >
                                Instructor
                            </label>

                            <textarea
                                id="instructor"
                                name="instructor"
                                rows="3"
                                placeholder="Enter instructor name or information"
                                class="reservation-textarea"
                            >{{ old(
                                'instructor',
                                $currentData['instructor'] ?? ''
                            ) }}</textarea>

                            @error('instructor')

                                <p class="reservation-field-error">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- =================================================
                             DESCRIPTION
                        ================================================== --}}

                        <div class="reservation-field">

                            <label
                                for="description"
                                class="reservation-label"
                            >
                                Additional Info
                                <span class="reservation-required">*</span>
                            </label>

                            <textarea
                                id="description"
                                name="description"
                                rows="5"
                                required
                                placeholder="Describe the purpose or details of your event"
                                class="reservation-textarea"
                            >{{ old(
                                'description',
                                $currentData['description'] ?? ''
                            ) }}</textarea>

                            @error('description')

                                <p class="reservation-field-error">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- =================================================
                             ACTIONS
                        ================================================== --}}

                        <div class="reservation-actions">

                            <a
                                href="{{ route(
                                    'training-officer.classroom.cart'
                                ) }}"
                                class="reservation-action-button reservation-cancel-button"
                            >
                                Back to Booking List
                            </a>


                            <button
                                type="submit"
                                class="reservation-action-button reservation-submit-button"
                            >

                                @if ($step < $totalSteps)

                                    Continue

                                @else

                                    Continue to Review

                                @endif

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 CURRENT RESOURCE
            ================================================== --}}

            <aside>

                <div class="reservation-resource-card">

                    <div class="reservation-resource-header">

                        <p class="reservation-resource-eyebrow">
                            Selected Resource
                        </p>

                        <h2 class="reservation-resource-title">
                            {{ $resource['name'] }}
                        </h2>

                        <span class="reservation-resource-type">
                            Room
                        </span>

                    </div>


                    {{-- RESOURCE IMAGE --}}

                    @php
                        $model = $resource['model'];
                        $image = $model->images->first();
                    @endphp


                    <div class="reservation-resource-image">

                        @if ($image)

                            <img
                                src="{{ asset(
                                    'storage/' . $image->file
                                ) }}"
                                alt="{{ $resource['name'] }}"
                            >

                        @else

                            <div class="reservation-no-image">
                                No image available
                            </div>

                        @endif

                    </div>


                    {{-- RESOURCE INFORMATION --}}

                    <div class="reservation-resource-info">

                        <div class="reservation-resource-detail">

                            <span class="reservation-resource-detail-label">
                                Location
                            </span>

                            <span class="reservation-resource-detail-value">
                                {{ $resource['building'] }}
                            </span>

                        </div>


                        <div class="reservation-resource-detail">

                            <span class="reservation-resource-detail-label">
                                Capacity
                            </span>

                            <span class="reservation-resource-detail-value">
                                {{ $resource['capacity'] ?? 'Not specified' }}
                            </span>

                        </div>


                        <div class="reservation-resource-detail">

                            <span class="reservation-resource-detail-label">
                                Step
                            </span>

                            <span class="reservation-resource-detail-value">
                                {{ $step }} of {{ $totalSteps }}
                            </span>

                        </div>

                    </div>


                    {{-- RESOURCE FOOTER --}}

                    <div class="reservation-resource-footer">

                        <p class="reservation-resource-footer-title">
                            Reservation Process
                        </p>

                        <p class="reservation-resource-footer-text">
                            Complete this room first. You will continue to the next selected room before reviewing the final reservation.
                        </p>

                    </div>

                </div>

            </aside>

        </div>

    </form>


    {{-- =========================================================
         SCHEDULE JAVASCRIPT
    ========================================================== --}}

    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const form =
                    document.getElementById(
                        'classroom-reservation-form'
                    );

                const startDate =
                    document.getElementById(
                        'start_date'
                    );

                const startTime =
                    document.getElementById(
                        'start_time'
                    );

                const endDate =
                    document.getElementById(
                        'end_date'
                    );

                const endTime =
                    document.getElementById(
                        'end_time'
                    );

                const startsAt =
                    document.getElementById(
                        'starts_at'
                    );

                const endsAt =
                    document.getElementById(
                        'ends_at'
                    );

                const clientError =
                    document.getElementById(
                        'schedule-client-error'
                    );


                if (
                    !form ||
                    !startDate ||
                    !startTime ||
                    !endDate ||
                    !endTime ||
                    !startsAt ||
                    !endsAt ||
                    !clientError
                ) {
                    return;
                }
                            /*
            * =========================================================
            * RESERVATION DATE LIMIT
            *
            * User can only select dates from today
            * until exactly 3 calendar months from today.
            *
            * Example:
            * 01 October 2026 → maximum 01 January 2027
            * 02 October 2026 → maximum 02 January 2027
            * =========================================================
            */

            function formatDateForInput(date) {

                const year =
                    date.getFullYear();

                const month =
                    String(date.getMonth() + 1).padStart(2, '0');

                const day =
                    String(date.getDate()).padStart(2, '0');

                return `${year}-${month}-${day}`;
            }


            function getReservationDateLimit() {

                const today = new Date();

                today.setHours(
                    0,
                    0,
                    0,
                    0
                );


                const maxDate = new Date(today);

                maxDate.setMonth(
                    maxDate.getMonth() + 3
                );


                return {
                    min: formatDateForInput(today),
                    max: formatDateForInput(maxDate)
                };
            }


            function applyReservationDateLimit() {

                const limits =
                    getReservationDateLimit();


                /*
                * Start Date
                */
                startDate.min =
                    limits.min;

                startDate.max =
                    limits.max;


                /*
                * End Date
                *
                * Initially follows today's minimum.
                * It will be updated again when
                * Start Date is selected.
                */
                endDate.min =
                    limits.min;

                endDate.max =
                    limits.max;
            }


            /*
            * Apply initial date limits.
            */
            applyReservationDateLimit();


            /*
            * Prevent manual typing / paste / drag & drop.
            *
            * User must choose the date
            * through the calendar picker.
            */
            [startDate, endDate].forEach(function (input) {

                input.addEventListener(
                    'keydown',
                    function (event) {

                        event.preventDefault();

                    }
                );


                input.addEventListener(
                    'paste',
                    function (event) {

                        event.preventDefault();

                    }
                );


                input.addEventListener(
                    'drop',
                    function (event) {

                        event.preventDefault();

                    }
                );

            });


            /*
            * Keep End Date at or after Start Date.
            */
            startDate.addEventListener(
                'change',
                function () {

                    const limits =
                        getReservationDateLimit();


                    if (startDate.value) {

                        endDate.min =
                            startDate.value;

                    } else {

                        endDate.min =
                            limits.min;

                    }

                    endDate.max =
                        limits.max;


                    /*
                    * If the currently selected
                    * End Date becomes invalid,
                    * clear it so the user chooses
                    * again from the valid range.
                    */
                    if (
                        endDate.value &&
                        endDate.value < endDate.min
                    ) {

                        endDate.value = '';

                    }

                }
            );

                /*
                 * =====================================================
                 * BUILD DATETIME
                 * =====================================================
                 */

                function buildDateTime(
                    date,
                    time
                ) {

                    if (
                        !date ||
                        !time
                    ) {
                        return '';
                    }

                    return (
                        date +
                        ' ' +
                        time +
                        ':00'
                    );
                }


                function getStartDateTime() {

                    return buildDateTime(
                        startDate.value,
                        startTime.value
                    );

                }


                function getEndDateTime() {

                    return buildDateTime(
                        endDate.value,
                        endTime.value
                    );

                }


                /*
                 * =====================================================
                 * VALIDATE SCHEDULE
                 * =====================================================
                 */

                function validateSchedule() {

                    const start =
                        getStartDateTime();

                    const end =
                        getEndDateTime();


                    startDate.classList.remove(
                        'schedule-invalid'
                    );

                    startTime.classList.remove(
                        'schedule-invalid'
                    );

                    endDate.classList.remove(
                        'schedule-invalid'
                    );

                    endTime.classList.remove(
                        'schedule-invalid'
                    );


                    clientError.style.display =
                        'none';

                    clientError.textContent =
                        '';


                    if (
                        !start ||
                        !end
                    ) {
                        return true;
                    }


                    const startDateTime =
                        new Date(
                            start.replace(
                                ' ',
                                'T'
                            )
                        );

                    const endDateTime =
                        new Date(
                            end.replace(
                                ' ',
                                'T'
                            )
                        );


                    if (
                        Number.isNaN(
                            startDateTime.getTime()
                        ) ||
                        Number.isNaN(
                            endDateTime.getTime()
                        )
                    ) {

                        clientError.textContent =
                            'Please select a valid reservation schedule.';

                        clientError.style.display =
                            'block';

                        return false;
                    }


                    if (
                        endDateTime <=
                        startDateTime
                    ) {

                        endDate.classList.add(
                            'schedule-invalid'
                        );

                        endTime.classList.add(
                            'schedule-invalid'
                        );

                        clientError.textContent =
                            'End date and time must be later than the start date and time.';

                        clientError.style.display =
                            'block';

                        return false;
                    }


                    return true;
                }


                /*
                 * =====================================================
                 * SYNC HIDDEN VALUES
                 * =====================================================
                 */

                function syncSchedule() {

                    startsAt.value =
                        getStartDateTime();

                    endsAt.value =
                        getEndDateTime();

                    validateSchedule();

                }


                /*
                 * =====================================================
                 * INPUT EVENTS
                 * =====================================================
                 */

                [
                    startDate,
                    startTime,
                    endDate,
                    endTime
                ].forEach(
                    function (input) {

                        input.addEventListener(
                            'change',
                            syncSchedule
                        );

                        input.addEventListener(
                            'input',
                            syncSchedule
                        );

                    }
                );


                /*
                 * =====================================================
                 * FORM SUBMIT
                 * =====================================================
                 */

                form.addEventListener(
                    'submit',
                    function (event) {

                        syncSchedule();


                        if (
                            !validateSchedule()
                        ) {

                            event.preventDefault();

                            clientError.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });

                            return;
                        }


                        if (
                            !startsAt.value ||
                            !endsAt.value
                        ) {

                            event.preventDefault();

                            clientError.textContent =
                                'Please select the complete reservation schedule.';

                            clientError.style.display =
                                'block';

                            clientError.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });

                            return;
                        }

                    }
                );


                /*
                 * =====================================================
                 * RESTORE START DATE/TIME
                 * =====================================================
                 */

                if (
                    startsAt.value &&
                    !startDate.value
                ) {

                    const restoredStart =
                        startsAt.value.replace(
                            ' ',
                            'T'
                        );


                    if (
                        restoredStart.length >= 16
                    ) {

                        startDate.value =
                            restoredStart.substring(
                                0,
                                10
                            );

                        startTime.value =
                            restoredStart.substring(
                                11,
                                16
                            );

                    }

                }


                /*
                 * =====================================================
                 * RESTORE END DATE/TIME
                 * =====================================================
                 */

                if (
                    endsAt.value &&
                    !endDate.value
                ) {

                    const restoredEnd =
                        endsAt.value.replace(
                            ' ',
                            'T'
                        );


                    if (
                        restoredEnd.length >= 16
                    ) {

                        endDate.value =
                            restoredEnd.substring(
                                0,
                                10
                            );

                        endTime.value =
                            restoredEnd.substring(
                                11,
                                16
                            );

                    }

                }


                /*
                 * Initial synchronization.
                 */

                syncSchedule();

            }
        );
    </script>

</div>

@endsection