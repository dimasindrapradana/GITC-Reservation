@extends('layouts.training-officer')

@section('title', 'Reservation Detail')

@section('content')

    <div class="page-header">

        <div>
            <h1>Reservation Detail</h1>

            <p>
                View the details and current status of your reservation.
            </p>
        </div>

        <a
            href="{{ route('training-officer.my-reservations.index') }}"
            class="back-button"
        >
            ← My Reservations
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


    @php

        if ($reservation->room) {

            $resourceType = 'Room';
            $resourceName = $reservation->room->name;
            $buildingName = $reservation->room->building?->name ?? 'Building deleted';

        } elseif ($reservation->trainingRoom) {

            $resourceType = 'Training Media';
            $resourceName = $reservation->trainingRoom->name;
            $buildingName = $reservation->trainingRoom->building?->name ?? 'Building deleted';

        } elseif ($reservation->field) {

            $resourceType = 'Field';
            $resourceName = $reservation->field->name;
            $buildingName = '—';

        } else {

            $resourceType = '—';
            $resourceName = '—';
            $buildingName = '—';

        }

    @endphp


    <div class="detail-card">

        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <div class="detail-header">

            <div>

                <div class="detail-label">
                    RESERVATION NUMBER
                </div>

                <div class="reservation-number">
                    {{ $reservation->reservation_number }}
                </div>

            </div>


            <div>

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

            </div>

        </div>


        {{-- =====================================================
             RESOURCE
        ====================================================== --}}

        <div class="detail-section">

            <div class="section-title">
                Reservation Resource
            </div>


            <div class="detail-grid">

                <div class="detail-item">

                    <span class="detail-item-label">
                        Resource Type
                    </span>

                    <span class="detail-item-value">
                        {{ $resourceType }}
                    </span>

                </div>


                <div class="detail-item">

                    <span class="detail-item-label">
                        Resource
                    </span>

                    <span class="detail-item-value">
                        {{ $resourceName }}
                    </span>

                </div>


                <div class="detail-item">

                    <span class="detail-item-label">
                        Building
                    </span>

                    <span class="detail-item-value">
                        {{ $buildingName }}
                    </span>

                </div>

            </div>

        </div>


        {{-- =====================================================
             SCHEDULE
        ====================================================== --}}

        <div class="detail-section">

            <div class="section-title">
                Schedule
            </div>


            <div class="detail-grid">

                <div class="detail-item">

                    <span class="detail-item-label">
                        Start
                    </span>

                    <span class="detail-item-value">
                        {{ $reservation->starts_at->format('d M Y, H:i') }}
                    </span>

                </div>


                <div class="detail-item">

                    <span class="detail-item-label">
                        End
                    </span>

                    <span class="detail-item-value">
                        {{ $reservation->ends_at->format('d M Y, H:i') }}
                    </span>

                </div>


                <div class="detail-item">

                    <span class="detail-item-label">
                        Duration
                    </span>

                    <span class="detail-item-value">

                        {{ $reservation->starts_at->diffInHours($reservation->ends_at) }}
                        hour{{ $reservation->starts_at->diffInHours($reservation->ends_at) == 1 ? '' : 's' }}

                    </span>

                </div>

            </div>

        </div>


        {{-- =====================================================
             EVENT INFORMATION
        ====================================================== --}}

        <div class="detail-section">

            <div class="section-title">
                Event Information
            </div>


            <div class="detail-grid">

                <div class="detail-item">

                    <span class="detail-item-label">
                        Event Name
                    </span>

                    <span class="detail-item-value">
                        {{ $reservation->event_name }}
                    </span>

                </div>


                <div class="detail-item">

                    <span class="detail-item-label">
                        Booker
                    </span>

                    <span class="detail-item-value">
                        {{ $reservation->booker_name }}
                    </span>

                </div>


                <div class="detail-item">

                    <span class="detail-item-label">
                        Total Participants
                    </span>

                    <span class="detail-item-value">
                        {{ $reservation->total_person }}
                        {{ $reservation->total_person == 1 ? 'person' : 'people' }}
                    </span>

                </div>


                @if ($reservation->instructor)

                    <div class="detail-item">

                        <span class="detail-item-label">
                            Instructor
                        </span>

                        <span class="detail-item-value">
                            {{ $reservation->instructor }}
                        </span>

                    </div>

                @endif

            </div>


            @if ($reservation->description)

                <div class="description-box">

                    <span class="detail-item-label">
                        Description
                    </span>

                    <div class="description-text">
                        {{ $reservation->description }}
                    </div>

                </div>

            @endif

        </div>


        {{-- =====================================================
             STATUS INFORMATION
        ====================================================== --}}

        @if ($reservation->status === 'REJECTED' && $reservation->rejection_reason)

            <div class="detail-section rejection-section">

                <div class="section-title">
                    Rejection Reason
                </div>

                <div class="rejection-text">
                    {{ $reservation->rejection_reason }}
                </div>

            </div>

        @endif


        {{-- =====================================================
             META
        ====================================================== --}}

        <div class="detail-section">

            <div class="section-title">
                Reservation Information
            </div>


            <div class="detail-grid">

                <div class="detail-item">

                    <span class="detail-item-label">
                        Created
                    </span>

                    <span class="detail-item-value">
                        {{ $reservation->created_at->format('d M Y, H:i') }}
                    </span>

                </div>


                <div class="detail-item">

                    <span class="detail-item-label">
                        Last Updated
                    </span>

                    <span class="detail-item-value">
                        {{ $reservation->updated_at->format('d M Y, H:i') }}
                    </span>

                </div>

            </div>

        </div>


        {{-- =====================================================
             FOOTER ACTION
        ====================================================== --}}

        <div class="detail-footer">

            <a
                href="{{ route('training-officer.my-reservations.index') }}"
                class="back-button"
            >
                Back to My Reservations
            </a>

        </div>

    </div>


@endsection


<style>

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


    .back-button {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        min-height: 38px;

        padding: 0 15px;

        border: 1px solid #d0dce5;

        border-radius: 7px;

        background: #ffffff;

        color: #4f6680;

        font-size: 12px;

        font-weight: 700;

        text-decoration: none;

        white-space: nowrap;

    }


    .back-button:hover {

        background: #f7fafc;

        border-color: #b9cbd8;

    }


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


    .detail-card {

        overflow: hidden;

        border: 1px solid #e1e9ee;

        border-radius: 9px;

        background: #ffffff;

    }


    .detail-header {

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 20px;

        padding: 20px;

        border-bottom: 1px solid #edf2f5;

        background: #ffffff;

    }


    .detail-label {

        margin-bottom: 5px;

        color: #8294a5;

        font-size: 10px;

        font-weight: 700;

        letter-spacing: .05em;

    }


    .reservation-number {

        color: #29445d;

        font-size: 17px;

        font-weight: 700;

    }


    .detail-section {

        padding: 20px;

        border-bottom: 1px solid #edf2f5;

    }


    .detail-section:last-of-type {

        border-bottom: none;

    }


    .section-title {

        margin-bottom: 16px;

        color: #29445d;

        font-size: 14px;

        font-weight: 700;

    }


    .detail-grid {

        display: grid;

        grid-template-columns: repeat(3, minmax(0, 1fr));

        gap: 14px;

    }


    .detail-item {

        min-width: 0;

        padding: 14px;

        border: 1px solid #edf2f5;

        border-radius: 7px;

        background: #fbfcfd;

    }


    .detail-item-label {

        display: block;

        margin-bottom: 6px;

        color: #8294a5;

        font-size: 10px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: .03em;

    }


    .detail-item-value {

        display: block;

        color: #405a70;

        font-size: 12px;

        font-weight: 600;

        line-height: 1.5;

        word-break: break-word;

    }


    .description-box {

        margin-top: 14px;

        padding: 14px;

        border: 1px solid #edf2f5;

        border-radius: 7px;

        background: #fbfcfd;

    }


    .description-text {

        color: #405a70;

        font-size: 12px;

        line-height: 1.6;

        white-space: pre-line;

    }


    .status-badge {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        min-height: 25px;

        padding: 0 10px;

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


    .rejection-section {

        background: #fffafa;

    }


    .rejection-text {

        padding: 12px 14px;

        border: 1px solid #edc4c4;

        border-radius: 7px;

        background: #fff7f7;

        color: #9b2c2c;

        font-size: 12px;

        line-height: 1.5;

    }


    .detail-footer {

        display: flex;

        justify-content: flex-end;

        padding: 16px 20px;

        background: #fafcfd;

    }


    @media (max-width: 800px) {

        .detail-grid {

            grid-template-columns: repeat(2, minmax(0, 1fr));

        }

    }


    @media (max-width: 600px) {

        .page-header {

            flex-direction: column;

            align-items: stretch;

        }


        .back-button {

            width: 100%;

        }


        .detail-header {

            align-items: flex-start;

            flex-direction: column;

        }


        .detail-grid {

            grid-template-columns: 1fr;

        }


        .detail-footer {

            justify-content: stretch;

        }


        .detail-footer .back-button {

            width: 100%;

        }

    }

</style>