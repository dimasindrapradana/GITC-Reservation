@extends('layouts.admin')

@section('title', 'Edit Reservation')
@section('page_title', 'Edit Reservation')

@section('content')

@php
    $oldStartsAt = old(
        'starts_at',
        optional($reservation->starts_at)->format('Y-m-d\TH:i')
    );

    $oldEndsAt = old(
        'ends_at',
        optional($reservation->ends_at)->format('Y-m-d\TH:i')
    );

    $oldStartDate = $oldStartsAt
        ? substr($oldStartsAt, 0, 10)
        : '';

    $oldStartTime = $oldStartsAt
        ? substr($oldStartsAt, 11, 5)
        : '';

    $oldEndDate = $oldEndsAt
        ? substr($oldEndsAt, 0, 10)
        : '';

    $oldEndTime = $oldEndsAt
        ? substr($oldEndsAt, 11, 5)
        : '';

    $currentResourceType = old(
        'resource_type',
        $reservation->room_id !== null
            ? 'room'
            : ($reservation->training_room_id !== null
                ? 'training_room'
                : '')
    );

    $currentResourceId = old(
        'resource_id',
        $reservation->room_id
            ?? $reservation->training_room_id
            ?? ''
    );

    $currentBuildingId = null;

    if ($reservation->room !== null) {
        $currentBuildingId = $reservation->room->building_id;
    } elseif ($reservation->trainingRoom !== null) {
        $currentBuildingId = $reservation->trainingRoom->building_id;
    }
@endphp

<div class="reservation-edit-page">

    <div class="edit-page-header">

        <div>
            <h1>Edit Reservation</h1>

            <p>
                Update reservation details and schedule.
            </p>
        </div>

        <div class="edit-header-actions">

            <a
                href="{{ route('building-coordinator.reservations.show', $reservation) }}"
                class="edit-header-button edit-header-button-dark"
            >
                View Details
            </a>

            <a
                href="{{ route('building-coordinator.reservations.index') }}"
                class="edit-header-button edit-header-button-light"
            >
                Back
            </a>

        </div>

    </div>

    @if ($errors->any())

        <div class="edit-alert">

            <div class="edit-alert-icon">
                <svg
                    class="edit-alert-svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.5a.75.75 0 0 0-1.5 0v4a.75.75 0 0 0 1.5 0v-4Zm0 6a.75.75 0 0 0-1.5 0v.01a.75.75 0 0 0 1.5 0V12.5Z"
                        clip-rule="evenodd"
                    />
                </svg>
            </div>

            <div>

                <p class="edit-alert-title">
                    Please correct the following errors:
                </p>

                <ul class="edit-alert-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        </div>

    @endif

    <form
        id="reservation-edit-form"
        action="{{ route('building-coordinator.reservations.update', $reservation) }}"
        method="POST"
    >
        @csrf
        @method('PUT')

        {{-- Reservation Information --}}
        <div class="edit-section">

            <div class="edit-section-header">

                <h2>
                    Reservation Information
                </h2>

                <p>
                    Review the requester and reservation information.
                </p>

            </div>

            <div class="edit-section-body edit-grid edit-grid-2">

                <div class="edit-field">

                    <label class="edit-label">
                        Reservation Number
                    </label>

                    <div class="edit-readonly">
                        {{ $reservation->reservation_number }}
                    </div>

                </div>

                <div class="edit-field">

                    <label class="edit-label">
                        Requester
                    </label>

                    <div class="edit-readonly">

                        {{ $reservation->user?->name ?? '-' }}

                        @if ($reservation->user?->employee_number)

                            <span class="edit-muted">
                                · {{ $reservation->user->employee_number }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

        {{-- Reserved Resource --}}
        <div class="edit-section">

            <div class="edit-section-header">

                <h2>
                    Reserved Resource
                </h2>

                <p>
                    Select a resource within your assigned building.
                </p>

            </div>

            <div class="edit-section-body edit-grid edit-grid-2">

                <div class="edit-field">

                    <label
                        for="building_id"
                        class="edit-label"
                    >
                        Building
                        <span class="edit-required">*</span>
                    </label>

                    <select
                        id="building_id"
                        name="building_id"
                        class="edit-select"
                    >

                        <option value="">
                            Select Building
                        </option>

                        @foreach ($buildings as $building)

                            <option
                                value="{{ $building->id }}"
                                @selected(
                                    (string) old('building_id', $currentBuildingId)
                                    ===
                                    (string) $building->id
                                )
                            >
                                {{ $building->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('building_id')
                        <p class="edit-error">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div class="edit-field">

                    <label
                        for="resource_type"
                        class="edit-label"
                    >
                        Resource Type
                        <span class="edit-required">*</span>
                    </label>

                    <select
                        id="resource_type"
                        name="resource_type"
                        class="edit-select"
                    >

                        <option value="">
                            Select Resource Type
                        </option>

                        <option
                            value="room"
                            @selected($currentResourceType === 'room')
                        >
                            Room
                        </option>

                        <option
                            value="training_room"
                            @selected($currentResourceType === 'training_room')
                        >
                            Media Training
                        </option>

                    </select>

                    @error('resource_type')
                        <p class="edit-error">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div class="edit-field edit-field-full">

                    <label
                        for="resource_id"
                        class="edit-label"
                    >
                        Resource
                        <span class="edit-required">*</span>
                    </label>

                    <select
                        id="resource_id"
                        name="resource_id"
                        class="edit-select"
                    >

                        <option value="">
                            Select Resource
                        </option>

                        @foreach ($rooms as $room)

                            <option
                                value="{{ $room->id }}"
                                data-resource-type="room"
                                data-building-id="{{ $room->building_id }}"
                                data-status="{{ $room->status }}"
                                @selected(
                                    $currentResourceType === 'room'
                                    &&
                                    (string) $currentResourceId
                                    ===
                                    (string) $room->id
                                )
                            >
                                {{ $room->name }}

                                @if ($room->status !== 'AVAILABLE')
                                    — {{ str_replace('_', ' ', $room->status) }}
                                @endif

                            </option>

                        @endforeach

                        @foreach ($trainingRooms as $trainingRoom)

                            <option
                                value="{{ $trainingRoom->id }}"
                                data-resource-type="training_room"
                                data-building-id="{{ $trainingRoom->building_id }}"
                                data-status="{{ $trainingRoom->status }}"
                                @selected(
                                    $currentResourceType === 'training_room'
                                    &&
                                    (string) $currentResourceId
                                    ===
                                    (string) $trainingRoom->id
                                )
                            >
                                {{ $trainingRoom->name }}

                                @if ($trainingRoom->status !== 'AVAILABLE')
                                    — {{ str_replace('_', ' ', $trainingRoom->status) }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                    <p
                        id="resource-help"
                        class="edit-help"
                    >
                        Select a building and resource type first.
                    </p>

                    @error('resource_id')
                        <p class="edit-error">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>

        </div>

        {{-- Schedule --}}
        <div class="edit-section">

            <div class="edit-section-header">

                <h2>
                    Schedule
                </h2>

                <p>
                    Set the reservation start and end date and time.
                </p>

            </div>

            <div class="edit-section-body edit-grid edit-grid-2">

                <div class="edit-field">

                    <label
                        for="start_date"
                        class="edit-label"
                    >
                        Start Date
                        <span class="edit-required">*</span>
                    </label>

                    <input
                        type="date"
                        id="start_date"
                        value="{{ $oldStartDate }}"
                        class="edit-input"
                    >

                    @error('starts_at')
                        <p class="edit-error">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div class="edit-field">

                    <label
                        for="start_time"
                        class="edit-label"
                    >
                        Start Time
                        <span class="edit-required">*</span>
                    </label>

                    <input
                        type="time"
                        id="start_time"
                        step="60"
                        value="{{ $oldStartTime }}"
                        class="edit-input"
                    >

                    @error('starts_at')
                        <p class="edit-error">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div class="edit-field">

                    <label
                        for="end_date"
                        class="edit-label"
                    >
                        End Date
                        <span class="edit-required">*</span>
                    </label>

                    <input
                        type="date"
                        id="end_date"
                        value="{{ $oldEndDate }}"
                        class="edit-input"
                    >

                    @error('ends_at')
                        <p class="edit-error">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div class="edit-field">

                    <label
                        for="end_time"
                        class="edit-label"
                    >
                        End Time
                        <span class="edit-required">*</span>
                    </label>

                    <input
                        type="time"
                        id="end_time"
                        step="60"
                        value="{{ $oldEndTime }}"
                        class="edit-input"
                    >

                    @error('ends_at')
                        <p class="edit-error">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <input
                    type="hidden"
                    name="starts_at"
                    id="starts_at"
                    value="{{ $oldStartsAt }}"
                >

                <input
                    type="hidden"
                    name="ends_at"
                    id="ends_at"
                    value="{{ $oldEndsAt }}"
                >

            </div>

        </div>

        {{-- Additional Details --}}
        <div class="edit-section">

            <div class="edit-section-header">

                <h2>
                    Additional Details
                </h2>

                <p>
                    Update the information related to this reservation.
                </p>

            </div>

            <div class="edit-section-body edit-grid edit-grid-2">

                <div class="edit-field">

                    <label
                        for="total_person"
                        class="edit-label"
                    >
                        Total Person
                        <span class="edit-required">*</span>
                    </label>

                    <input
                        type="number"
                        id="total_person"
                        name="total_person"
                        min="1"
                        value="{{ old('total_person', $reservation->total_person) }}"
                        class="edit-input"
                    >

                    @error('total_person')
                        <p class="edit-error">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div class="edit-field">

                    <label
                        for="event_name"
                        class="edit-label"
                    >
                        Event Name
                        <span class="edit-required">*</span>
                    </label>

                    <input
                        type="text"
                        id="event_name"
                        name="event_name"
                        value="{{ old('event_name', $reservation->event_name) }}"
                        class="edit-input"
                    >

                    @error('event_name')
                        <p class="edit-error">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div class="edit-field">

                    <label
                        for="booker_name"
                        class="edit-label"
                    >
                        Booker Name
                        <span class="edit-required">*</span>
                    </label>

                    <input
                        type="text"
                        id="booker_name"
                        name="booker_name"
                        value="{{ old('booker_name', $reservation->booker_name) }}"
                        class="edit-input"
                    >

                    @error('booker_name')
                        <p class="edit-error">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div class="edit-field">

                    <label
                        for="instructor"
                        class="edit-label"
                    >
                        Instructor
                    </label>

                    <input
                        type="text"
                        id="instructor"
                        name="instructor"
                        value="{{ old('instructor', $reservation->instructor) }}"
                        class="edit-input"
                    >

                    @error('instructor')
                        <p class="edit-error">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div class="edit-field edit-field-full">

                    <label
                        for="description"
                        class="edit-label"
                    >
                        Additional Info
                        <span class="edit-required">*</span>
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        class="edit-textarea"
                    >{{ old('description', $reservation->description) }}</textarea>

                    @error('description')
                        <p class="edit-error">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>

        </div>

        {{-- Actions --}}
        <div class="edit-form-actions">

            <a
                href="{{ route('building-coordinator.reservations.show', $reservation) }}"
                class="edit-button edit-button-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="edit-button edit-button-primary"
            >
                Update Reservation
            </button>

        </div>

    </form>

</div>

@endsection

@push('styles')
<style>

    .reservation-edit-page {
        color: #243b53;
    }

    .edit-page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 20px;
    }

    .edit-page-header h1 {
        margin: 0;
        color: #102a43;
        font-size: 20px;
        font-weight: 700;
    }

    .edit-page-header p {
        margin: 5px 0 0;
        color: #6b7c93;
        font-size: 13px;
        line-height: 1.5;
    }

    .edit-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .edit-header-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 8px 15px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        box-sizing: border-box;
        transition: background 0.15s ease, border-color 0.15s ease;
    }

    .edit-header-button-dark {
        background: #334155;
        border: 1px solid #334155;
        color: #ffffff;
    }

    .edit-header-button-dark:hover {
        background: #1e293b;
        border-color: #1e293b;
    }

    .edit-header-button-light {
        background: #ffffff;
        border: 1px solid #cbd5df;
        color: #334e68;
    }

    .edit-header-button-light:hover {
        background: #f5f8fa;
    }

    .edit-alert {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 20px;
        padding: 14px 16px;
        border: 1px solid #f2c4c0;
        border-radius: 9px;
        background: #fceeee;
        color: #b42318;
    }

    .edit-alert-icon {
        flex-shrink: 0;
        margin-top: 1px;
    }

    .edit-alert-svg {
        width: 20px;
        height: 20px;
    }

    .edit-alert-title {
        margin: 0;
        font-size: 13px;
        font-weight: 700;
    }

    .edit-alert-list {
        margin: 6px 0 0;
        padding-left: 18px;
        font-size: 12px;
        line-height: 1.6;
    }

    #reservation-edit-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .edit-section {
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e7edf2;
        border-radius: 12px;
        box-shadow: 0 1px 2px rgba(16, 42, 67, 0.04);
    }

    .edit-section-header {
        padding: 20px 22px;
        border-bottom: 1px solid #e7edf2;
    }

    .edit-section-header h2 {
        margin: 0;
        color: #102a43;
        font-size: 16px;
        font-weight: 700;
    }

    .edit-section-header p {
        margin: 5px 0 0;
        color: #6b7c93;
        font-size: 12px;
        line-height: 1.5;
    }

    .edit-section-body {
        padding: 22px;
    }

    .edit-grid {
        display: grid;
        gap: 20px;
    }

    .edit-grid-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .edit-field {
        min-width: 0;
    }

    .edit-field-full {
        grid-column: 1 / -1;
    }

    .edit-label {
        display: block;
        margin-bottom: 7px;
        color: #334e68;
        font-size: 13px;
        font-weight: 600;
    }

    .edit-required {
        color: #b42318;
    }

    .edit-input,
    .edit-select,
    .edit-textarea {
        width: 100%;
        border: 1px solid #cbd5df;
        border-radius: 7px;
        background: #ffffff;
        color: #243b53;
        font-family: inherit;
        font-size: 13px;
        box-sizing: border-box;
        outline: none;
        transition:
            border-color 0.15s ease,
            box-shadow 0.15s ease,
            background 0.15s ease;
    }

    .edit-input,
    .edit-select {
        min-height: 40px;
        padding: 9px 12px;
    }

    .edit-textarea {
        min-height: 120px;
        padding: 10px 12px;
        line-height: 1.5;
        resize: vertical;
    }

    .edit-input:focus,
    .edit-select:focus,
    .edit-textarea:focus {
        border-color: #00a6d6;
        box-shadow: 0 0 0 3px rgba(0, 166, 214, 0.10);
    }

    .edit-readonly {
        min-height: 40px;
        padding: 9px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 7px;
        background: #f8fafc;
        color: #475569;
        font-size: 13px;
        line-height: 20px;
        box-sizing: border-box;
    }

    .edit-muted {
        color: #829ab1;
    }

    .edit-help {
        margin: 6px 0 0;
        color: #829ab1;
        font-size: 11px;
        line-height: 1.5;
    }

    .edit-error {
        margin: 6px 0 0;
        color: #b42318;
        font-size: 12px;
        line-height: 1.4;
    }

    .edit-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 2px;
    }

    .edit-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 9px 17px;
        border-radius: 7px;
        border: 1px solid transparent;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        box-sizing: border-box;
        transition:
            background 0.15s ease,
            border-color 0.15s ease;
    }

    .edit-button-secondary {
        background: #ffffff;
        border-color: #cbd5df;
        color: #334e68;
    }

    .edit-button-secondary:hover {
        background: #f5f8fa;
    }

    .edit-button-primary {
        background: #334155;
        border-color: #334155;
        color: #ffffff;
    }

    .edit-button-primary:hover {
        background: #1e293b;
        border-color: #1e293b;
    }

    @media (max-width: 900px) {

        .edit-grid-2 {
            grid-template-columns: 1fr;
        }

        .edit-field-full {
            grid-column: auto;
        }

    }

    @media (max-width: 768px) {

        .edit-page-header {
            flex-direction: column;
        }

        .edit-header-actions {
            width: 100%;
        }

        .edit-header-button {
            flex: 1;
        }

        .edit-section-body {
            padding: 18px;
        }

        .edit-section-header {
            padding: 18px;
        }

        .edit-form-actions {
            flex-direction: column-reverse;
        }

        .edit-button {
            width: 100%;
        }

    }

    @media (max-width: 480px) {

        .edit-header-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .edit-header-button {
            width: 100%;
        }

    }

</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form =
            document.getElementById('reservation-edit-form');

        const buildingSelect =
            document.getElementById('building_id');

        const resourceTypeSelect =
            document.getElementById('resource_type');

        const resourceSelect =
            document.getElementById('resource_id');

        const resourceHelp =
            document.getElementById('resource-help');

        const startDate =
            document.getElementById('start_date');

        const startTime =
            document.getElementById('start_time');

        const endDate =
            document.getElementById('end_date');

        const endTime =
            document.getElementById('end_time');

        const startsAt =
            document.getElementById('starts_at');

        const endsAt =
            document.getElementById('ends_at');

        const originalResourceId =
            @json((string) $currentResourceId);

        const originalResourceType =
            @json($currentResourceType);

        function updateScheduleValues() {

            if (startDate.value && startTime.value) {

                startsAt.value =
                    startDate.value + 'T' + startTime.value;

            } else {

                startsAt.value = '';

            }

            if (endDate.value && endTime.value) {

                endsAt.value =
                    endDate.value + 'T' + endTime.value;

            } else {

                endsAt.value = '';

            }

        }

        function filterResources() {

            const buildingId =
                buildingSelect.value;

            const resourceType =
                resourceTypeSelect.value;

            let visibleCount = 0;

            Array.from(resourceSelect.options)
                .forEach(function (option) {

                    if (!option.value) {

                        option.hidden = false;

                        return;

                    }

                    const optionBuildingId =
                        option.dataset.buildingId || '';

                    const optionResourceType =
                        option.dataset.resourceType || '';

                    const optionStatus =
                        option.dataset.status || '';

                    const isCurrentResource =
                        option.value === originalResourceId &&
                        optionResourceType === originalResourceType;

                    const matchesBuilding =
                        buildingId !== '' &&
                        optionBuildingId === buildingId;

                    const matchesType =
                        resourceType !== '' &&
                        optionResourceType === resourceType;

                    const allowedStatus =
                        optionStatus === 'AVAILABLE' ||
                        isCurrentResource;

                    const visible =
                        matchesBuilding &&
                        matchesType &&
                        allowedStatus;

                    option.hidden = !visible;

                    if (visible) {
                        visibleCount++;
                    }

                });

            const selectedOption =
                resourceSelect.options[
                    resourceSelect.selectedIndex
                ];

            const selectedVisible =
                selectedOption &&
                selectedOption.value &&
                !selectedOption.hidden;

            if (!selectedVisible) {
                resourceSelect.value = '';
            }

            if (!buildingId || !resourceType) {

                resourceHelp.textContent =
                    'Select a building and resource type first.';

            } else if (visibleCount === 0) {

                resourceHelp.textContent =
                    'No available resources were found for the selected building and resource type.';

            } else {

                resourceHelp.textContent =
                    'Only available resources are shown. The current resource remains selectable.';

            }

        }

        buildingSelect.addEventListener(
            'change',
            filterResources
        );

        resourceTypeSelect.addEventListener(
            'change',
            filterResources
        );

        [
            startDate,
            startTime,
            endDate,
            endTime
        ].forEach(function (field) {

            field.addEventListener(
                'change',
                updateScheduleValues
            );

        });

        form.addEventListener(
            'submit',
            function () {
                updateScheduleValues();
            }
        );

        filterResources();
        updateScheduleValues();

    });
</script>
@endpush