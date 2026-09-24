@extends('layouts.admin')

@section('title', 'Edit Reservation')

@section('content')

@php
    $currentResourceType = old(
        'resource_type',
        $reservation->room_id
            ? 'room'
            : (
                $reservation->training_room_id
                    ? 'training_room'
                    : 'field'
            )
    );

    $currentResourceId = old(
        'resource_id',
        $reservation->room_id
            ?: (
                $reservation->training_room_id
                    ?: $reservation->field_id
            )
    );

    $currentBuildingId = '';

    if (
        $currentResourceType === 'room'
        && $reservation->room
    ) {
        $currentBuildingId = $reservation->room->building_id;
    }

    if (
        $currentResourceType === 'training_room'
        && $reservation->trainingRoom
    ) {
        $currentBuildingId =
            $reservation->trainingRoom->building_id;
    }

    if (
        old('resource_type') === 'room'
        && old('resource_id')
    ) {
        $oldRoom = $rooms->firstWhere(
            'id',
            (int) old('resource_id')
        );

        if ($oldRoom) {
            $currentBuildingId = $oldRoom->building_id;
        }
    }

    if (
        old('resource_type') === 'training_room'
        && old('resource_id')
    ) {
        $oldTrainingRoom = $trainingRooms->firstWhere(
            'id',
            (int) old('resource_id')
        );

        if ($oldTrainingRoom) {
            $currentBuildingId =
                $oldTrainingRoom->building_id;
        }
    }

    $availableBuildings = collect();

    foreach ($rooms as $room) {
        if ($room->building) {
            $availableBuildings->push(
                $room->building
            );
        }
    }

    foreach ($trainingRooms as $trainingRoom) {
        if ($trainingRoom->building) {
            $availableBuildings->push(
                $trainingRoom->building
            );
        }
    }

    $availableBuildings = $availableBuildings
        ->unique('id')
        ->sortBy('name')
        ->values();
@endphp

<style>
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-header-content {
        min-width: 0;
    }

    .page-title {
        margin: 0;
        color: #17324d;
        font-size: 24px;
        font-weight: 700;
        line-height: 1.3;
    }

    .page-description {
        margin: 6px 0 0;
        color: #6d8296;
        font-size: 13px;
        line-height: 1.6;
    }

    .page-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .header-button {
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
        font-weight: 600;
        text-decoration: none;
        transition: 0.15s ease;
    }

    .header-button:hover {
        border-color: #c9dfe9;
        background: #f5fafc;
        color: #006fae;
    }

    .content-card {
        overflow: hidden;
        border: 1px solid #e2eaf0;
        border-radius: 9px;
        background: #ffffff;
    }

    .card-header {
        padding: 20px 22px;
        border-bottom: 1px solid #edf2f5;
    }

    .card-title {
        margin: 0;
        color: #17324d;
        font-size: 15px;
        font-weight: 700;
    }

    .card-description {
        margin: 5px 0 0;
        color: #7890a4;
        font-size: 12px;
        line-height: 1.6;
    }

    .form-body {
        padding: 22px;
    }

    .form-section {
        margin-bottom: 28px;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        margin: 0 0 14px;
        color: #28445e;
        font-size: 13px;
        font-weight: 700;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .form-group {
        min-width: 0;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        color: #425b72;
        font-size: 12px;
        font-weight: 600;
    }

    .required {
        color: #c44a4a;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #d0dce5;
        border-radius: 7px;
        background: #ffffff;
        color: #243d54;
        font-family: inherit;
        font-size: 13px;
        outline: none;
        transition: 0.15s ease;
    }

    .form-input,
    .form-select {
        height: 40px;
        padding: 0 12px;
    }

    .form-textarea {
        min-height: 110px;
        padding: 11px 12px;
        resize: vertical;
        line-height: 1.6;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: #7ebbd5;
        box-shadow: 0 0 0 3px #eaf5fb;
    }

    .form-input::placeholder,
    .form-textarea::placeholder {
        color: #a2b1bf;
    }

    .form-help {
        margin-top: 6px;
        color: #8295a6;
        font-size: 11px;
        line-height: 1.5;
    }

    .field-error {
        margin-top: 6px;
        color: #a33b3b;
        font-size: 11px;
        line-height: 1.5;
    }

    .alert {
        margin-bottom: 18px;
        padding: 11px 14px;
        border-radius: 7px;
        font-size: 12px;
        line-height: 1.5;
    }

    .alert-error {
        border: 1px solid #edc4c4;
        background: #fff7f7;
        color: #9b2c2c;
    }

    .alert-error ul {
        margin: 6px 0 0;
        padding-left: 18px;
    }

    .resource-section {
        padding: 18px;
        border: 1px solid #e2eaf0;
        border-radius: 8px;
        background: #fbfdfe;
    }

    .resource-notice {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 16px;
        padding: 11px 12px;
        border: 1px solid #dcecf4;
        border-radius: 7px;
        background: #f3f9fc;
        color: #587187;
        font-size: 11px;
        line-height: 1.5;
    }

    .resource-notice-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        flex: 0 0 18px;
        border-radius: 50%;
        background: #dceff7;
        color: #006fae;
        font-size: 11px;
        font-weight: 700;
    }

    .resource-notice strong {
        color: #35536c;
    }

    .resource-option {
        display: none;
    }

    .resource-option.active {
        display: block;
    }

    .resource-building {
        margin-bottom: 16px;
    }

    .resource-select option {
        color: #243d54;
    }

    .resource-select option[hidden] {
        display: none;
    }

    .schedule-note {
        margin-top: 8px;
        padding: 10px 12px;
        border-left: 3px solid #9bc9dc;
        background: #f5fafc;
        color: #647b8e;
        font-size: 11px;
        line-height: 1.6;
    }

    .client-error {
        display: none;
        margin-top: 7px;
        color: #a33b3b;
        font-size: 11px;
        line-height: 1.5;
    }

    .client-error.visible {
        display: block;
    }

    .maintenance-note {
        margin-top: 7px;
        color: #8a6200;
        font-size: 11px;
        line-height: 1.5;
    }

    .form-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        padding: 16px 22px;
        border-top: 1px solid #edf2f5;
        background: #fbfcfd;
    }

    .button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 0 16px;
        border-radius: 7px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: 0.15s ease;
    }

    .button-secondary {
        border: 1px solid #d0dce5;
        background: #ffffff;
        color: #4f6680;
    }

    .button-secondary:hover {
        border-color: #c9dfe9;
        background: #f5fafc;
        color: #006fae;
    }

    .button-primary {
        border: 1px solid #006fae;
        background: #006fae;
        color: #ffffff;
    }

    .button-primary:hover {
        border-color: #005d92;
        background: #005d92;
    }

    .button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    @media (max-width: 700px) {

        .page-header {
            flex-direction: column;
        }

        .page-actions {
            width: 100%;
        }

        .header-button {
            flex: 1;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full {
            grid-column: auto;
        }

        .form-body {
            padding: 18px;
        }

        .form-footer {
            padding: 14px 18px;
        }

    }

    @media (max-width: 480px) {

        .form-footer {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .button {
            width: 100%;
        }

    }
</style>

<div class="page-header">

    <div class="page-header-content">

        <h1 class="page-title">
            Edit Reservation
        </h1>

        <p class="page-description">
            Update the requester, resource, schedule, and reservation details.
        </p>

    </div>

    <div class="page-actions">

        <a
            href="{{ route('reservations.show', $reservation) }}"
            class="header-button"
        >
            View Details
        </a>

        <a
            href="{{ route('reservations.index') }}"
            class="header-button"
        >
            Back to Reservations
        </a>

    </div>

</div>

@if ($errors->any())

    <div class="alert alert-error">

        <strong>
            Please correct the following errors:
        </strong>

        <ul>

            @foreach ($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif

<div class="content-card">

    <div class="card-header">

        <h2 class="card-title">
            Reservation Information
        </h2>

        <p class="card-description">
            Update the reservation information below.
        </p>

    </div>

    <form
        action="{{ route('reservations.update', $reservation) }}"
        method="POST"
        id="reservation-form"
    >

        @csrf
        @method('PUT')

        <div class="form-body">

            {{-- Requester --}}

            <div class="form-section">

                <h3 class="section-title">
                    Requester
                </h3>

                <div class="form-grid">

                    <div class="form-group full">

                        <label
                            for="user_id"
                            class="form-label"
                        >
                            Requester
                            <span class="required">*</span>
                        </label>

                        <select
                            name="user_id"
                            id="user_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select requester
                            </option>

                            @foreach ($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    @selected(
                                        old(
                                            'user_id',
                                            $reservation->user_id
                                        ) == $user->id
                                    )
                                >

                                    {{ $user->name }}

                                    @if ($user->employee_number)
                                        — {{ $user->employee_number }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('user_id')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

            {{-- Resource --}}

            <div class="form-section">

                <h3 class="section-title">
                    Resource
                </h3>

                <div class="resource-section">

                    <div class="resource-notice">

                        <span class="resource-notice-icon">
                            i
                        </span>

                        <div>

                            <strong>
                                Select one resource only.
                            </strong>

                            A reservation can be made for one Room,
                            one Media Training, or one Field.

                        </div>

                    </div>

                    <div class="form-grid">

                        {{-- Resource Type --}}

                        <div class="form-group full">

                            <label
                                for="resource_type"
                                class="form-label"
                            >
                                Resource Type
                                <span class="required">*</span>
                            </label>

                            <select
                                name="resource_type"
                                id="resource_type"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select resource type
                                </option>

                                <option
                                    value="room"
                                    @selected(
                                        $currentResourceType === 'room'
                                    )
                                >
                                    Room
                                </option>

                                <option
                                    value="training_room"
                                    @selected(
                                        $currentResourceType
                                        === 'training_room'
                                    )
                                >
                                    Media Training
                                </option>

                                <option
                                    value="field"
                                    @selected(
                                        $currentResourceType === 'field'
                                    )
                                >
                                    Field
                                </option>

                            </select>

                            @error('resource_type')

                                <div class="field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                        {{-- Room --}}

                        <div
                            class="form-group full resource-option"
                            id="room-resource"
                        >

                            <div class="resource-building">

                                <label
                                    for="room_building_id"
                                    class="form-label"
                                >
                                    Building
                                    <span class="required">*</span>
                                </label>

                                <select
                                    id="room_building_id"
                                    class="form-select building-select"
                                    data-resource-type="room"
                                >

                                    <option value="">
                                        Select building
                                    </option>

                                    @foreach (
                                        $availableBuildings
                                        as $building
                                    )

                                        <option
                                            value="{{ $building->id }}"
                                            @selected(
                                                (string) $currentBuildingId
                                                === (string) $building->id
                                                && $currentResourceType
                                                    === 'room'
                                            )
                                        >
                                            {{ $building->name }}
                                        </option>

                                    @endforeach

                                </select>

                                <div
                                    class="client-error"
                                    id="room-building-error"
                                >
                                    Please select a building.
                                </div>

                            </div>

                            <label
                                for="room_id"
                                class="form-label"
                            >
                                Room
                                <span class="required">*</span>
                            </label>

                            <select
                                name="resource_id"
                                id="room_id"
                                class="form-select resource-select"
                                data-resource-type="room"
                                data-placeholder="Select room"
                            >

                                <option value="">
                                    Select room
                                </option>

                                @foreach ($rooms as $room)

                                    <option
                                        value="{{ $room->id }}"
                                        data-building-id="{{ $room->building_id }}"
                                        @selected(
                                            $currentResourceType
                                                === 'room'
                                            && (string) $currentResourceId
                                                === (string) $room->id
                                        )
                                    >

                                        {{ $room->name }}

                                        @if ($room->status !== 'AVAILABLE')
                                            — Maintenance
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            <div
                                class="client-error"
                                id="room-error"
                            >
                                Please select a room.
                            </div>

                        </div>

                        {{-- Media Training --}}

                        <div
                            class="form-group full resource-option"
                            id="training-room-resource"
                        >

                            <div class="resource-building">

                                <label
                                    for="training_room_building_id"
                                    class="form-label"
                                >
                                    Building
                                    <span class="required">*</span>
                                </label>

                                <select
                                    id="training_room_building_id"
                                    class="form-select building-select"
                                    data-resource-type="training_room"
                                >

                                    <option value="">
                                        Select building
                                    </option>

                                    @foreach (
                                        $availableBuildings
                                        as $building
                                    )

                                        <option
                                            value="{{ $building->id }}"
                                            @selected(
                                                (string) $currentBuildingId
                                                === (string) $building->id
                                                && $currentResourceType
                                                    === 'training_room'
                                            )
                                        >
                                            {{ $building->name }}
                                        </option>

                                    @endforeach

                                </select>

                                <div
                                    class="client-error"
                                    id="training-room-building-error"
                                >
                                    Please select a building.
                                </div>

                            </div>

                            <label
                                for="training_room_id"
                                class="form-label"
                            >
                                Media Training
                                <span class="required">*</span>
                            </label>

                            <select
                                name="resource_id"
                                id="training_room_id"
                                class="form-select resource-select"
                                data-resource-type="training_room"
                                data-placeholder="Select Media Training"
                            >

                                <option value="">
                                    Select Media Training
                                </option>

                                @foreach ($trainingRooms as $trainingRoom)

                                    <option
                                        value="{{ $trainingRoom->id }}"
                                        data-building-id="{{ $trainingRoom->building_id }}"
                                        @selected(
                                            $currentResourceType
                                                === 'training_room'
                                            && (string) $currentResourceId
                                                === (string) $trainingRoom->id
                                        )
                                    >

                                        {{ $trainingRoom->name }}

                                        @if (
                                            $trainingRoom->status
                                                !== 'AVAILABLE'
                                        )
                                            — Maintenance
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            <div
                                class="client-error"
                                id="training-room-error"
                            >
                                Please select a Media Training.
                            </div>

                        </div>

                        {{-- Field --}}

                        <div
                            class="form-group full resource-option"
                            id="field-resource"
                        >

                            <label
                                for="field_id"
                                class="form-label"
                            >
                                Field
                                <span class="required">*</span>
                            </label>

                            <select
                                name="resource_id"
                                id="field_id"
                                class="form-select resource-select"
                                data-resource-type="field"
                            >

                                <option value="">
                                    Select field
                                </option>

                                @foreach ($fields as $field)

                                    <option
                                        value="{{ $field->id }}"
                                        @selected(
                                            $currentResourceType
                                                === 'field'
                                            && (string) $currentResourceId
                                                === (string) $field->id
                                        )
                                    >

                                        {{ $field->name }}

                                        @if ($field->status !== 'AVAILABLE')
                                            — Maintenance
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            <div
                                class="client-error"
                                id="field-error"
                            >
                                Please select a field.
                            </div>

                        </div>

                    </div>

                </div>

                @error('resource_id')

                    <div class="field-error">
                        {{ $message }}
                    </div>

                @enderror

            </div>

            {{-- Schedule --}}

            <div class="form-section">

                <h3 class="section-title">
                    Schedule
                </h3>

                <div class="form-grid">

                    <div class="form-group">

                        <label
                            for="starts_at"
                            class="form-label"
                        >
                            Start Date & Time
                            <span class="required">*</span>
                        </label>

                        <input
                            type="datetime-local"
                            name="starts_at"
                            id="starts_at"
                            class="form-input"
                            value="{{ old(
                                'starts_at',
                                $reservation->starts_at
                                    ->format('Y-m-d\TH:i')
                            ) }}"
                            required
                        >

                        @error('starts_at')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                    <div class="form-group">

                        <label
                            for="ends_at"
                            class="form-label"
                        >
                            End Date & Time
                            <span class="required">*</span>
                        </label>

                        <input
                            type="datetime-local"
                            name="ends_at"
                            id="ends_at"
                            class="form-input"
                            value="{{ old(
                                'ends_at',
                                $reservation->ends_at
                                    ->format('Y-m-d\TH:i')
                            ) }}"
                            required
                        >

                        @error('ends_at')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                    <div class="form-group full">

                        <div class="schedule-note">

                            All schedules use Asia/Jakarta timezone.
                            The selected resource will be checked for
                            overlapping pending or approved reservations.

                        </div>

                    </div>

                </div>

            </div>

            {{-- Additional Details --}}

            <div class="form-section">

                <h3 class="section-title">
                    Additional Details
                </h3>

                <div class="form-grid">

                    <div class="form-group full">

                        <label
                            for="instructor"
                            class="form-label"
                        >
                            Instructor
                        </label>

                        <input
                            type="text"
                            name="instructor"
                            id="instructor"
                            class="form-input"
                            value="{{ old(
                                'instructor',
                                $reservation->instructor
                            ) }}"
                            placeholder="Enter instructor name(s)"
                        >

                        <div class="form-help">
                            Optional. Multiple instructor names can be entered.
                        </div>

                        @error('instructor')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                    <div class="form-group full">

                        <label
                            for="description"
                            class="form-label"
                        >
                            Description
                            <span class="required">*</span>
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            class="form-textarea"
                            placeholder="Enter reservation details"
                            required
                        >{{ old(
                            'description',
                            $reservation->description
                        ) }}</textarea>

                        @error('description')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>

        <div class="form-footer">

            <a
                href="{{ route('reservations.show', $reservation) }}"
                class="button button-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="button button-primary"
                id="submit-button"
            >
                Update Reservation
            </button>

        </div>

    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form =
            document.getElementById('reservation-form');

        const resourceType =
            document.getElementById('resource_type');

        const resourceOptions = {
            room:
                document.getElementById('room-resource'),

            training_room:
                document.getElementById(
                    'training-room-resource'
                ),

            field:
                document.getElementById('field-resource'),
        };

        const resourceSelects =
            document.querySelectorAll('.resource-select');

        const buildingSelects =
            document.querySelectorAll('.building-select');

        const errorElements = {
            room:
                document.getElementById('room-error'),

            training_room:
                document.getElementById(
                    'training-room-error'
                ),

            field:
                document.getElementById('field-error'),
        };

        const buildingErrorElements = {
            room:
                document.getElementById(
                    'room-building-error'
                ),

            training_room:
                document.getElementById(
                    'training-room-building-error'
                ),
        };

        function getResourceSelect(type) {

            return Array.from(resourceSelects).find(
                function (select) {
                    return (
                        select.dataset.resourceType
                        === type
                    );
                }
            );

        }

        function getBuildingSelect(type) {

            return Array.from(buildingSelects).find(
                function (select) {
                    return (
                        select.dataset.resourceType
                        === type
                    );
                }
            );

        }

        function clearClientErrors() {

            Object.values(errorElements).forEach(
                function (error) {

                    if (error) {
                        error.classList.remove(
                            'visible'
                        );
                    }

                }
            );

            Object.values(buildingErrorElements)
                .forEach(
                    function (error) {

                        if (error) {
                            error.classList.remove(
                                'visible'
                            );
                        }

                    }
                );

        }

        function filterResourcesByBuilding(type) {

            const buildingSelect =
                getBuildingSelect(type);

            const resourceSelect =
                getResourceSelect(type);

            if (
                !buildingSelect
                || !resourceSelect
            ) {
                return;
            }

            const selectedBuilding =
                buildingSelect.value;

            Array.from(
                resourceSelect.options
            ).forEach(
                function (option, index) {

                    if (index === 0) {
                        option.hidden = false;
                        return;
                    }

                    const matches =
                        selectedBuilding !== ''
                        && option.dataset.buildingId
                            === selectedBuilding;

                    option.hidden = !matches;

                    if (
                        !matches
                        && option.selected
                    ) {
                        option.selected = false;
                    }

                }
            );

            if (!selectedBuilding) {
                resourceSelect.value = '';
            }

        }

        function resetInactiveResources(
            selectedType
        ) {

            resourceSelects.forEach(
                function (select) {

                    const active =
                        select.dataset.resourceType
                        === selectedType;

                    select.disabled = !active;

                    select.required = active;

                    if (!active) {
                        select.value = '';
                    }

                }
            );

            buildingSelects.forEach(
                function (select) {

                    const active =
                        select.dataset.resourceType
                        === selectedType;

                    select.disabled =
                        !active;

                    if (!active) {
                        select.value = '';
                    }

                }
            );

        }

        function updateResourceSelection() {

            const selectedType =
                resourceType.value;

            clearClientErrors();

            Object.entries(resourceOptions)
                .forEach(
                    function ([type, option]) {

                        option.classList.toggle(
                            'active',
                            type === selectedType
                        );

                    }
                );

            resetInactiveResources(
                selectedType
            );

            if (
                selectedType === 'room'
                || selectedType === 'training_room'
            ) {

                filterResourcesByBuilding(
                    selectedType
                );

            }

        }

        resourceType.addEventListener(
            'change',
            function () {

                const selectedType =
                    resourceType.value;

                if (
                    selectedType === 'room'
                    || selectedType === 'training_room'
                ) {

                    const buildingSelect =
                        getBuildingSelect(
                            selectedType
                        );

                    const resourceSelect =
                        getResourceSelect(
                            selectedType
                        );

                    if (buildingSelect) {
                        buildingSelect.value = '';
                    }

                    if (resourceSelect) {
                        resourceSelect.value = '';
                    }

                }

                updateResourceSelection();

            }
        );

        buildingSelects.forEach(
            function (select) {

                select.addEventListener(
                    'change',
                    function () {

                        clearClientErrors();

                        const type =
                            this.dataset.resourceType;

                        const resourceSelect =
                            getResourceSelect(type);

                        if (resourceSelect) {
                            resourceSelect.value = '';
                        }

                        filterResourcesByBuilding(
                            type
                        );

                    }
                );

            }
        );

        resourceSelects.forEach(
            function (select) {

                select.addEventListener(
                    'change',
                    function () {

                        clearClientErrors();

                    }
                );

            }
        );

        form.addEventListener(
            'submit',
            function (event) {

                clearClientErrors();

                const selectedType =
                    resourceType.value;

                if (!selectedType) {
                    event.preventDefault();
                    resourceType.focus();
                    return;
                }

                if (
                    selectedType === 'room'
                    || selectedType === 'training_room'
                ) {

                    const buildingSelect =
                        getBuildingSelect(
                            selectedType
                        );

                    if (
                        !buildingSelect
                        || !buildingSelect.value
                    ) {

                        event.preventDefault();

                        const error =
                            buildingErrorElements[
                                selectedType
                            ];

                        if (error) {
                            error.classList.add(
                                'visible'
                            );
                        }

                        buildingSelect?.focus();

                        return;

                    }

                }

                const activeSelect =
                    getResourceSelect(
                        selectedType
                    );

                if (
                    !activeSelect
                    || !activeSelect.value
                ) {

                    event.preventDefault();

                    const error =
                        errorElements[
                            selectedType
                        ];

                    if (error) {
                        error.classList.add(
                            'visible'
                        );
                    }

                    activeSelect?.focus();

                    return;

                }

            }
        );

        updateResourceSelection();

        const initialType =
            resourceType.value;

        if (
            initialType === 'room'
            || initialType === 'training_room'
        ) {

            const initialBuilding =
                getBuildingSelect(initialType);

            if (initialBuilding) {

                filterResourcesByBuilding(
                    initialType
                );

            }

        }

    });
</script>

@endsection