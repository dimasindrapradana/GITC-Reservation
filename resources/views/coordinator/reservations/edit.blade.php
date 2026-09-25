@extends('layouts.admin')

@section('title', 'Edit Reservation')
@section('page_title', 'Edit Reservation')

@section('content')

@php
    $currentResourceType = match (true) {
        $reservation->room_id !== null => 'room',
        $reservation->training_room_id !== null => 'training_room',
        $reservation->field_id !== null => 'field',
        default => '',
    };

    $currentResourceId = match ($currentResourceType) {
        'room' => $reservation->room_id,
        'training_room' => $reservation->training_room_id,
        'field' => $reservation->field_id,
        default => '',
    };

    $currentBuildingId = match ($currentResourceType) {
        'room' => $reservation->room?->building_id,
        'training_room' => $reservation->trainingRoom?->building_id,
        'field' => null,
        default => '',
    };
@endphp

<div class="reservation-edit-page">

    <div class="edit-page-header">

        <div>
            <h2>Edit Reservation</h2>
            <p>Update reservation information, resource, and schedule.</p>
        </div>

        <div class="edit-header-actions">

            <a
                href="{{ route('coordinator.reservations.show', $reservation) }}"
                class="edit-header-button edit-header-button-dark"
            >
                View Details
            </a>

            <a
                href="{{ route('coordinator.reservations.show', $reservation) }}"
                class="edit-header-button edit-header-button-light"
            >
                Back
            </a>

        </div>

    </div>

    @if ($errors->any())

        <div class="edit-alert edit-alert-error">

            <div class="edit-alert-title">
                Please correct the following errors:
            </div>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif

    <form
        method="POST"
        action="{{ route('coordinator.reservations.update', $reservation) }}"
        id="reservation-edit-form"
    >

        @csrf
        @method('PUT')

        <div class="edit-section">

            <div class="edit-section-header">
                <div>
                    <h3>Reservation Information</h3>
                    <p>Basic information for this reservation.</p>
                </div>
            </div>

            <div class="edit-section-body">

                <div class="edit-grid edit-grid-2">

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

                            <div>
                                {{ $reservation->user?->name ?? '—' }}
                            </div>

                            @if ($reservation->user?->employee_number)
                                <div class="edit-muted">
                                    {{ $reservation->user->employee_number }}
                                </div>
                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="edit-section">

            <div class="edit-section-header">
                <div>
                    <h3>Reserved Resource</h3>
                    <p>Select the building, resource type, and resource.</p>
                </div>
            </div>

            <div class="edit-section-body">

                <div class="edit-grid edit-grid-3">

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
                            class="edit-select"
                            @disabled($currentResourceType === 'field')
                        >

                            <option value="">
                                Select Building
                            </option>

                            @foreach ($rooms->pluck('building')->filter()->unique('id')->sortBy('name') as $building)
                                <option
                                    value="{{ $building->id }}"
                                    @selected((string) $currentBuildingId === (string) $building->id)
                                >
                                    {{ $building->name }}
                                </option>
                            @endforeach

                        </select>

                        @if ($currentResourceType === 'field')
                            <div class="edit-help">
                                Building selection is not required for fields.
                            </div>
                        @endif

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
                            required
                        >

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

                            <option
                                value="field"
                                @selected($currentResourceType === 'field')
                            >
                                Field
                            </option>

                        </select>

                    </div>

                    <div class="edit-field">

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
                            required
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
                                        && (int) $currentResourceId === (int) $room->id
                                    )
                                >
                                    {{ $room->name }}
                                    @if ($room->building)
                                        — {{ $room->building->name }}
                                    @endif
                                    @if ($room->status !== 'AVAILABLE')
                                        — {{ $room->status }}
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
                                        && (int) $currentResourceId === (int) $trainingRoom->id
                                    )
                                >
                                    {{ $trainingRoom->name }}
                                    @if ($trainingRoom->building)
                                        — {{ $trainingRoom->building->name }}
                                    @endif
                                    @if ($trainingRoom->status !== 'AVAILABLE')
                                        — {{ $trainingRoom->status }}
                                    @endif
                                </option>

                            @endforeach

                            @foreach ($fields as $field)

                                <option
                                    value="{{ $field->id }}"
                                    data-resource-type="field"
                                    data-building-id=""
                                    data-status="{{ $field->status }}"
                                    @selected(
                                        $currentResourceType === 'field'
                                        && (int) $currentResourceId === (int) $field->id
                                    )
                                >
                                    {{ $field->name }}
                                    @if ($field->status !== 'AVAILABLE')
                                        — {{ $field->status }}
                                    @endif
                                </option>

                            @endforeach

                        </select>

                        <div
                            id="resource-help"
                            class="edit-help"
                        >
                            Select a building and resource type first.
                        </div>

                        @error('resource_id')
                            <div class="edit-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>

        <div class="edit-section">

            <div class="edit-section-header">
                <div>
                    <h3>Schedule</h3>
                    <p>Set the reservation start and end date and time.</p>
                </div>
            </div>

            <div class="edit-section-body">

                <div class="edit-grid edit-grid-2">

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
                            class="edit-input"
                            value="{{ old('starts_at', $reservation->starts_at?->format('Y-m-d\TH:i')) ? \Carbon\Carbon::parse(old('starts_at', $reservation->starts_at?->format('Y-m-d\TH:i')))->format('Y-m-d') : '' }}"
                            required
                        >

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
                            class="edit-input"
                            step="60"
                            value="{{ old('starts_at', $reservation->starts_at?->format('Y-m-d\TH:i')) ? \Carbon\Carbon::parse(old('starts_at', $reservation->starts_at?->format('Y-m-d\TH:i')))->format('H:i') : '' }}"
                            required
                        >

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
                            class="edit-input"
                            value="{{ old('ends_at', $reservation->ends_at?->format('Y-m-d\TH:i')) ? \Carbon\Carbon::parse(old('ends_at', $reservation->ends_at?->format('Y-m-d\TH:i')))->format('Y-m-d') : '' }}"
                            required
                        >

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
                            class="edit-input"
                            step="60"
                            value="{{ old('ends_at', $reservation->ends_at?->format('Y-m-d\TH:i')) ? \Carbon\Carbon::parse(old('ends_at', $reservation->ends_at?->format('Y-m-d\TH:i')))->format('H:i') : '' }}"
                            required
                        >

                    </div>

                </div>

                <input
                    type="hidden"
                    name="starts_at"
                    id="starts_at"
                    value="{{ old('starts_at', $reservation->starts_at?->format('Y-m-d\TH:i')) }}"
                >

                <input
                    type="hidden"
                    name="ends_at"
                    id="ends_at"
                    value="{{ old('ends_at', $reservation->ends_at?->format('Y-m-d\TH:i')) }}"
                >

                <div class="edit-schedule-note">
                    The reservation uses the system timezone: Asia/Jakarta.
                </div>

            </div>

        </div>

        <div class="edit-section">

            <div class="edit-section-header">
                <div>
                    <h3>Additional Details</h3>
                    <p>Provide the information required for this reservation.</p>
                </div>
            </div>

            <div class="edit-section-body">

                <div class="edit-grid edit-grid-2">

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
                            class="edit-input"
                            min="1"
                            value="{{ old('total_person', $reservation->total_person) }}"
                            required
                        >

                        @error('total_person')
                            <div class="edit-error">
                                {{ $message }}
                            </div>
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
                            class="edit-input"
                            value="{{ old('event_name', $reservation->event_name) }}"
                            required
                        >

                        @error('event_name')
                            <div class="edit-error">
                                {{ $message }}
                            </div>
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
                            class="edit-input"
                            value="{{ old('booker_name', $reservation->booker_name) }}"
                            required
                        >

                        @error('booker_name')
                            <div class="edit-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="edit-field">

                        <label
                            for="instructor"
                            class="edit-label"
                        >
                            Instructor
                            <span class="edit-optional">
                                Optional
                            </span>
                        </label>

                        <input
                            type="text"
                            id="instructor"
                            name="instructor"
                            class="edit-input"
                            value="{{ old('instructor', $reservation->instructor) }}"
                        >

                        @error('instructor')
                            <div class="edit-error">
                                {{ $message }}
                            </div>
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
                            class="edit-textarea"
                            rows="5"
                            required
                        >{{ old('description', $reservation->description) }}</textarea>

                        @error('description')
                            <div class="edit-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>

        <div class="edit-form-actions">

            <a
                href="{{ route('coordinator.reservations.show', $reservation) }}"
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
        width: 100%;
    }

    .edit-page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 20px;
    }

    .edit-page-header h2 {
        margin: 0;
        color: #102a43;
        font-size: 22px;
        font-weight: 700;
        line-height: 1.3;
    }

    .edit-page-header p {
        margin: 6px 0 0;
        color: #6b7c93;
        font-size: 13px;
        line-height: 1.5;
    }

    .edit-header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .edit-header-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 36px;
        padding: 0 13px;
        border-radius: 7px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid transparent;
        transition:
            background 0.15s ease,
            border-color 0.15s ease,
            color 0.15s ease;
    }

    .edit-header-button-dark {
        background: #243b53;
        border-color: #243b53;
        color: #ffffff;
    }

    .edit-header-button-dark:hover {
        background: #102a43;
        border-color: #102a43;
    }

    .edit-header-button-light {
        background: #ffffff;
        border-color: #cbd5df;
        color: #334e68;
    }

    .edit-header-button-light:hover {
        background: #f5f8fa;
    }

    .edit-alert {
        margin-bottom: 20px;
        padding: 14px 16px;
        border-radius: 8px;
        font-size: 13px;
    }

    .edit-alert-error {
        background: #fceeee;
        border: 1px solid #f2c4c0;
        color: #b42318;
    }

    .edit-alert-title {
        font-weight: 700;
        margin-bottom: 6px;
    }

    .edit-alert ul {
        margin: 0;
        padding-left: 20px;
    }

    .edit-alert li + li {
        margin-top: 3px;
    }

    .edit-section {
        margin-bottom: 20px;
        background: #ffffff;
        border: 1px solid #e7edf2;
        border-radius: 12px;
        overflow: hidden;
    }

    .edit-section-header {
        padding: 18px 22px;
        background: #f9fbfc;
        border-bottom: 1px solid #e7edf2;
    }

    .edit-section-header h3 {
        margin: 0;
        color: #243b53;
        font-size: 15px;
        font-weight: 700;
    }

    .edit-section-header p {
        margin: 5px 0 0;
        color: #829ab1;
        font-size: 12px;
        line-height: 1.5;
    }

    .edit-section-body {
        padding: 22px;
    }

    .edit-grid {
        display: grid;
        gap: 18px;
    }

    .edit-grid-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .edit-grid-3 {
        grid-template-columns:
            minmax(0, 1fr)
            minmax(0, 1fr)
            minmax(0, 1.4fr);
    }

    .edit-field {
        min-width: 0;
    }

    .edit-field-full {
        grid-column: 1 / -1;
    }

    .edit-label {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 7px;
        color: #334e68;
        font-size: 12px;
        font-weight: 700;
    }

    .edit-required {
        color: #b42318;
    }

    .edit-optional {
        margin-left: 3px;
        color: #829ab1;
        font-size: 10px;
        font-weight: 500;
    }

    .edit-input,
    .edit-select,
    .edit-textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #cbd5df;
        border-radius: 7px;
        background: #ffffff;
        color: #243b53;
        font-size: 13px;
        outline: none;
        transition:
            border-color 0.15s ease,
            box-shadow 0.15s ease;
    }

    .edit-input,
    .edit-select {
        min-height: 40px;
        padding: 8px 11px;
    }

    .edit-textarea {
        min-height: 120px;
        padding: 10px 11px;
        resize: vertical;
        line-height: 1.5;
    }

    .edit-input:focus,
    .edit-select:focus,
    .edit-textarea:focus {
        border-color: #00a6d6;
        box-shadow: 0 0 0 3px rgba(0, 166, 214, 0.10);
    }

    .edit-select:disabled {
        background: #f3f6f8;
        color: #829ab1;
        cursor: not-allowed;
    }

    .edit-readonly {
        min-height: 40px;
        box-sizing: border-box;
        padding: 9px 11px;
        border: 1px solid #e1e8ed;
        border-radius: 7px;
        background: #f7fafc;
        color: #334e68;
        font-size: 13px;
        line-height: 1.4;
    }

    .edit-muted {
        margin-top: 3px;
        color: #829ab1;
        font-size: 11px;
    }

    .edit-help {
        margin-top: 5px;
        color: #829ab1;
        font-size: 11px;
        line-height: 1.4;
    }

    .edit-error {
        margin-top: 5px;
        color: #b42318;
        font-size: 11px;
    }

    .edit-schedule-note {
        margin-top: 14px;
        padding: 10px 12px;
        border-radius: 7px;
        background: #f3f8fb;
        color: #486581;
        font-size: 11px;
        line-height: 1.5;
    }

    .edit-form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 9px;
        margin-top: 4px;
        padding-bottom: 10px;
    }

    .edit-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 16px;
        border-radius: 7px;
        border: 1px solid transparent;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        box-sizing: border-box;
        transition:
            background 0.15s ease,
            border-color 0.15s ease,
            color 0.15s ease;
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
        background: #243b53;
        border-color: #243b53;
        color: #ffffff;
    }

    .edit-button-primary:hover {
        background: #102a43;
        border-color: #102a43;
    }

    @media (max-width: 1000px) {

        .edit-grid-3 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .edit-grid-3 .edit-field:last-child {
            grid-column: 1 / -1;
        }

    }

    @media (max-width: 768px) {

        .edit-page-header {
            flex-direction: column;
        }

        .edit-header-actions {
            width: 100%;
        }

        .edit-grid-2,
        .edit-grid-3 {
            grid-template-columns: 1fr;
        }

        .edit-grid-3 .edit-field:last-child {
            grid-column: auto;
        }

        .edit-section-header,
        .edit-section-body {
            padding: 18px;
        }

        .edit-form-actions {
            justify-content: stretch;
            flex-direction: column-reverse;
        }

        .edit-form-actions .edit-button {
            width: 100%;
        }

    }

    @media (max-width: 480px) {

        .edit-page-header h2 {
            font-size: 19px;
        }

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

        const form = document.getElementById('reservation-edit-form');

        const buildingSelect = document.getElementById('building_id');
        const resourceTypeSelect = document.getElementById('resource_type');
        const resourceSelect = document.getElementById('resource_id');
        const resourceHelp = document.getElementById('resource-help');

        const startDate = document.getElementById('start_date');
        const startTime = document.getElementById('start_time');
        const endDate = document.getElementById('end_date');
        const endTime = document.getElementById('end_time');

        const startsAt = document.getElementById('starts_at');
        const endsAt = document.getElementById('ends_at');

        const currentResourceId = @json((string) $currentResourceId);
        const currentResourceType = @json($currentResourceType);

        function updateScheduleFields() {

            if (
                startDate.value !== ''
                && startTime.value !== ''
            ) {
                startsAt.value =
                    startDate.value
                    + 'T'
                    + startTime.value;
            }

            if (
                endDate.value !== ''
                && endTime.value !== ''
            ) {
                endsAt.value =
                    endDate.value
                    + 'T'
                    + endTime.value;
            }
        }

        function filterResources() {

            const selectedBuilding =
                buildingSelect.value;

            const selectedType =
                resourceTypeSelect.value;

            let visibleCount = 0;
            let currentVisible = false;

            Array.from(resourceSelect.options).forEach(function (option) {

                if (option.value === '') {
                    option.hidden = false;
                    return;
                }

                const optionType =
                    option.dataset.resourceType || '';

                const optionBuilding =
                    option.dataset.buildingId || '';

                const optionStatus =
                    option.dataset.status || '';

                const isCurrent =
                    option.value === currentResourceId
                    && optionType === currentResourceType;

                let visible = false;

                if (selectedType === 'field') {

                    visible =
                        optionType === 'field'
                        && (
                            optionStatus === 'AVAILABLE'
                            || isCurrent
                        );

                } else {

                    visible =
                        optionType === selectedType
                        && optionBuilding === selectedBuilding
                        && (
                            optionStatus === 'AVAILABLE'
                            || isCurrent
                        );

                }

                option.hidden = !visible;

                if (visible) {
                    visibleCount++;

                    if (isCurrent) {
                        currentVisible = true;
                    }
                }

            });

            const selectedOption =
                resourceSelect.options[
                    resourceSelect.selectedIndex
                ];

            if (
                !selectedOption
                || selectedOption.hidden
            ) {
                resourceSelect.value = '';
            }

            if (selectedType === 'field') {

                buildingSelect.disabled = true;
                buildingSelect.value = '';

            } else {

                buildingSelect.disabled = false;

            }

            if (visibleCount === 0) {

                if (selectedType === 'field') {
                    resourceHelp.textContent =
                        'No available fields are currently available.';
                } else if (selectedBuilding === '') {
                    resourceHelp.textContent =
                        'Select a building to view available resources.';
                } else {
                    resourceHelp.textContent =
                        'No available resources found for the selected building.';
                }

            } else if (currentVisible) {

                resourceHelp.textContent =
                    'The current resource remains selectable while editing this reservation.';

            } else {

                resourceHelp.textContent =
                    'Only available resources are shown.';
            }

        }

        resourceTypeSelect.addEventListener(
            'change',
            function () {

                if (resourceTypeSelect.value === 'field') {

                    buildingSelect.value = '';

                }

                filterResources();

            }
        );

        buildingSelect.addEventListener(
            'change',
            filterResources
        );

        startDate.addEventListener(
            'change',
            updateScheduleFields
        );

        startTime.addEventListener(
            'change',
            updateScheduleFields
        );

        endDate.addEventListener(
            'change',
            updateScheduleFields
        );

        endTime.addEventListener(
            'change',
            updateScheduleFields
        );

        form.addEventListener(
            'submit',
            function () {
                updateScheduleFields();
            }
        );

        updateScheduleFields();
        filterResources();

    });

</script>
@endpush