@extends('layouts.admin')

@section('title', 'Create Reservation')

@section('content')

@php
    $availableBuildings = collect();

    foreach ($rooms as $room) {
        if ($room->building) {
            $availableBuildings->push($room->building);
        }
    }

    foreach ($trainingRooms as $trainingRoom) {
        if ($trainingRoom->building) {
            $availableBuildings->push($trainingRoom->building);
        }
    }

    $availableBuildings = $availableBuildings
        ->unique('id')
        ->sortBy('name')
        ->values();

    $oldResourceType = old('resource_type');
    $oldResourceId = old('resource_id');
    $oldBuildingId = '';

    if ($oldResourceType === 'room' && $oldResourceId) {
        $oldRoom = $rooms->firstWhere('id', (int) $oldResourceId);

        if ($oldRoom?->building) {
            $oldBuildingId = $oldRoom->building->id;
        }
    }

    if ($oldResourceType === 'training_room' && $oldResourceId) {
        $oldTrainingRoom = $trainingRooms->firstWhere(
            'id',
            (int) $oldResourceId
        );

        if ($oldTrainingRoom?->building) {
            $oldBuildingId = $oldTrainingRoom->building->id;
        }
    }
@endphp

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

    .card-description {
        margin: 5px 0 0;
        color: #8a9aaa;
        font-size: 11px;
        line-height: 1.5;
    }

    .form-body {
        padding: 20px;
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
        color: #40576d;
        font-size: 12px;
        font-weight: 600;
    }

    .required {
        color: #a33b3b;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        border: 1px solid #d7e1e8;
        border-radius: 7px;
        background: #ffffff;
        color: #40576d;
        font-family: inherit;
        font-size: 12px;
        outline: none;
        box-sizing: border-box;
    }

    .form-input,
    .form-select {
        min-height: 40px;
        padding: 0 12px;
    }

    .form-textarea {
        min-height: 110px;
        padding: 10px 12px;
        resize: vertical;
        line-height: 1.6;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: #9bc9df;
        box-shadow: 0 0 0 3px rgba(0, 111, 174, 0.08);
    }

    .form-help {
        margin-top: 6px;
        color: #8a9aaa;
        font-size: 11px;
        line-height: 1.5;
    }

    .field-error {
        margin-top: 6px;
        color: #a33b3b;
        font-size: 11px;
    }

    .resource-section {
        padding: 16px;
        border: 1px solid #dcecf3;
        border-radius: 9px;
        background: #f8fbfc;
    }

    .resource-section-header {
        margin-bottom: 14px;
    }

    .resource-section-title {
        margin: 0;
        color: #19324a;
        font-size: 13px;
        font-weight: 700;
    }

    .resource-section-description {
        margin: 5px 0 0;
        color: #71869a;
        font-size: 11px;
        line-height: 1.5;
    }

    .resource-notice {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 16px;
        padding: 11px 13px;
        border: 1px solid #cfe5ef;
        border-radius: 7px;
        background: #eef8fc;
        color: #45677b;
        font-size: 11px;
        line-height: 1.6;
    }

    .resource-notice-icon {
        flex: 0 0 auto;
        color: #006fae;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.4;
    }

    .resource-option {
        display: none;
    }

    .resource-option.visible {
        display: block;
    }

    .resource-building {
        margin-bottom: 16px;
    }

    .resource-building.hidden {
        display: none;
    }

    .resource-select.hidden {
        display: none;
    }

    .schedule-note {
        padding: 12px 14px;
        border: 1px solid #dcecf3;
        border-radius: 7px;
        background: #f5fafc;
        color: #4f6680;
        font-size: 11px;
        line-height: 1.6;
    }

    .form-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        padding: 16px 20px;
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
        box-sizing: border-box;
    }

    .button-secondary {
        border: 1px solid #d0dce5;
        background: #ffffff;
        color: #4f6680;
    }

    .button-secondary:hover {
        background: #f6f9fb;
    }

    .button-primary {
        border: 1px solid #006fae;
        background: #006fae;
        color: #ffffff;
    }

    .button-primary:hover {
        border-color: #005f95;
        background: #005f95;
    }

    .error-summary {
        margin-bottom: 18px;
        padding: 12px 14px;
        border: 1px solid #edc4c4;
        border-radius: 7px;
        background: #fff7f7;
        color: #9b2c2c;
        font-size: 12px;
    }

    .error-summary strong {
        display: block;
        margin-bottom: 6px;
    }

    .error-summary ul {
        margin: 0;
        padding-left: 18px;
    }

    .client-error {
        display: none;
        margin-top: 8px;
        color: #a33b3b;
        font-size: 11px;
        line-height: 1.5;
    }

    .client-error.visible {
        display: block;
    }

    @media (max-width: 700px) {

        .page-header {
            flex-direction: column;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full {
            grid-column: auto;
        }

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

    <div>

        <h1 class="page-title">
            Create Reservation
        </h1>

        <p class="page-description">
            Create a new reservation request for an available resource.
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

@if ($errors->any())

    <div class="error-summary">

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
            Enter the requester, resource, schedule, and reservation details.
        </p>

    </div>

    <form
        action="{{ route('reservations.store') }}"
        method="POST"
        id="reservation-form"
    >

        @csrf

        <div class="form-body">

            <div class="form-grid">

                {{-- Requester --}}

                <div class="form-group">

                    <label
                        for="user_id"
                        class="form-label"
                    >
                        Requester <span class="required">*</span>
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
                                    (string) old('user_id')
                                    === (string) $user->id
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

                {{-- Resource Type --}}

                <div class="form-group">

                    <label
                        for="resource_type"
                        class="form-label"
                    >
                        Resource Type <span class="required">*</span>
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
                            @selected(old('resource_type') === 'room')
                        >
                            Room
                        </option>

                        <option
                            value="training_room"
                            @selected(
                                old('resource_type')
                                === 'training_room'
                            )
                        >
                            Media Training
                        </option>

                        <option
                            value="field"
                            @selected(old('resource_type') === 'field')
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

                {{-- Resource Selection --}}

                <div class="form-group full">

                    <div class="resource-section">

                        <div class="resource-section-header">

                            <h3 class="resource-section-title">
                                Resource Selection
                            </h3>

                            <p class="resource-section-description">
                                Select the building first, then choose the resource you want to reserve.
                            </p>

                        </div>

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

                        {{-- Room --}}

                        <div
                            class="resource-option"
                            id="room-resource"
                        >

                            <div class="resource-building">

                                <label
                                    for="room_building_id"
                                    class="form-label"
                                >
                                    Building <span class="required">*</span>
                                </label>

                                <select
                                    id="room_building_id"
                                    class="form-select building-select"
                                    data-type="room"
                                >

                                    <option value="">
                                        Select building
                                    </option>

                                    @foreach ($availableBuildings as $building)

                                        <option
                                            value="{{ $building->id }}"
                                            @selected(
                                                (string) $oldBuildingId
                                                === (string) $building->id
                                                && old('resource_type') === 'room'
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
                                Room <span class="required">*</span>
                            </label>

                            <select
                                id="room_id"
                                class="form-select resource-select"
                                data-type="room"
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
                                            old('resource_type') === 'room'
                                            && (string) old('resource_id')
                                                === (string) $room->id
                                        )
                                    >

                                        {{ $room->name }}

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
                            class="resource-option"
                            id="training-room-resource"
                        >

                            <div class="resource-building">

                                <label
                                    for="training_room_building_id"
                                    class="form-label"
                                >
                                    Building <span class="required">*</span>
                                </label>

                                <select
                                    id="training_room_building_id"
                                    class="form-select building-select"
                                    data-type="training_room"
                                >

                                    <option value="">
                                        Select building
                                    </option>

                                    @foreach ($availableBuildings as $building)

                                        <option
                                            value="{{ $building->id }}"
                                            @selected(
                                                (string) $oldBuildingId
                                                === (string) $building->id
                                                && old('resource_type')
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
                                Media Training <span class="required">*</span>
                            </label>

                            <select
                                id="training_room_id"
                                class="form-select resource-select"
                                data-type="training_room"
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
                                            old('resource_type')
                                                === 'training_room'
                                            && (string) old('resource_id')
                                                === (string) $trainingRoom->id
                                        )
                                    >

                                        {{ $trainingRoom->name }}

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
                            class="resource-option"
                            id="field-resource"
                        >

                            <label
                                for="field_id"
                                class="form-label"
                            >
                                Field <span class="required">*</span>
                            </label>

                            <select
                                id="field_id"
                                class="form-select resource-select"
                                data-type="field"
                            >

                                <option value="">
                                    Select field
                                </option>

                                @foreach ($fields as $field)

                                    <option
                                        value="{{ $field->id }}"
                                        @selected(
                                            old('resource_type') === 'field'
                                            && (string) old('resource_id')
                                                === (string) $field->id
                                        )
                                    >

                                        {{ $field->name }}

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

                    <input
                        type="hidden"
                        name="resource_id"
                        id="resource_id"
                        value="{{ old('resource_id') }}"
                    >

                    @error('resource_id')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

                {{-- Schedule --}}

                <div class="form-group">

                    <label
                        for="starts_at"
                        class="form-label"
                    >
                        Start Date & Time <span class="required">*</span>
                    </label>

                    <input
                        type="datetime-local"
                        name="starts_at"
                        id="starts_at"
                        class="form-input"
                        value="{{ old('starts_at') }}"
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
                        End Date & Time <span class="required">*</span>
                    </label>

                    <input
                        type="datetime-local"
                        name="ends_at"
                        id="ends_at"
                        class="form-input"
                        value="{{ old('ends_at') }}"
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

                        Reservations use the Asia/Jakarta timezone.
                        Adjacent time ranges are allowed, but overlapping
                        PENDING or APPROVED reservations are not allowed.

                    </div>

                </div>

                {{-- Instructor --}}

                <div class="form-group full">

                    <label
                        for="instructor"
                        class="form-label"
                    >
                        Instructor
                    </label>

                    <textarea
                        name="instructor"
                        id="instructor"
                        class="form-textarea"
                        placeholder="Enter instructor name(s)"
                    >{{ old('instructor') }}</textarea>

                    <div class="form-help">
                        Optional. Multiple instructor names can be entered.
                    </div>

                    @error('instructor')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

                {{-- Description --}}

                <div class="form-group full">

                    <label
                        for="description"
                        class="form-label"
                    >
                        Description <span class="required">*</span>
                    </label>

                    <textarea
                        name="description"
                        id="description"
                        class="form-textarea"
                        placeholder="Enter reservation description"
                        required
                    >{{ old('description') }}</textarea>

                    @error('description')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>

        </div>

        <div class="form-footer">

            <a
                href="{{ route('reservations.index') }}"
                class="button button-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="button button-primary"
            >
                Create Reservation
            </button>

        </div>

    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('reservation-form');
        const resourceType = document.getElementById('resource_type');
        const resourceId = document.getElementById('resource_id');

        const resourceGroups = {
            room: document.getElementById('room-resource'),
            training_room: document.getElementById(
                'training-room-resource'
            ),
            field: document.getElementById('field-resource'),
        };

        const resourceSelects = document.querySelectorAll(
            '.resource-select'
        );

        const buildingSelects = document.querySelectorAll(
            '.building-select'
        );

        const errorElements = {
            room: document.getElementById('room-error'),
            training_room: document.getElementById(
                'training-room-error'
            ),
            field: document.getElementById('field-error'),
        };

        const buildingErrorElements = {
            room: document.getElementById(
                'room-building-error'
            ),
            training_room: document.getElementById(
                'training-room-building-error'
            ),
        };

        function clearClientErrors() {

            Object.values(errorElements).forEach(function (error) {

                if (error) {
                    error.classList.remove('visible');
                }

            });

            Object.values(buildingErrorElements).forEach(
                function (error) {

                    if (error) {
                        error.classList.remove('visible');
                    }

                }
            );
        }

        function getBuildingSelect(type) {

            return Array.from(buildingSelects).find(
                function (select) {
                    return select.dataset.type === type;
                }
            );

        }

        function getResourceSelect(type) {

            return Array.from(resourceSelects).find(
                function (select) {
                    return select.dataset.type === type;
                }
            );

        }

        function filterResourcesByBuilding(type) {

            const buildingSelect = getBuildingSelect(type);
            const resourceSelect = getResourceSelect(type);

            if (!buildingSelect || !resourceSelect) {
                return;
            }

            const selectedBuilding = buildingSelect.value;

            Array.from(resourceSelect.options).forEach(
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

                    if (!matches && option.selected) {
                        option.selected = false;
                    }

                }
            );

            if (!selectedBuilding) {
                resourceSelect.value = '';
                resourceId.value = '';
            }

        }

        function updateResourceSelection() {

            const selectedType = resourceType.value;

            clearClientErrors();

            Object.entries(resourceGroups).forEach(
                function ([type, group]) {

                    if (type === selectedType) {
                        group.classList.add('visible');
                    } else {
                        group.classList.remove('visible');
                    }

                }
            );

            resourceSelects.forEach(function (select) {

                if (select.dataset.type !== selectedType) {
                    select.value = '';
                }

            });

            buildingSelects.forEach(function (select) {

                if (select.dataset.type !== selectedType) {
                    select.value = '';
                }

            });

            if (
                selectedType === 'room'
                || selectedType === 'training_room'
            ) {

                filterResourcesByBuilding(selectedType);

            }

            const activeSelect =
                getResourceSelect(selectedType);

            resourceId.value = activeSelect
                ? activeSelect.value
                : '';
        }

        resourceType.addEventListener(
            'change',
            updateResourceSelection
        );

        buildingSelects.forEach(function (select) {

            select.addEventListener(
                'change',
                function () {

                    const type = this.dataset.type;

                    clearClientErrors();

                    const resourceSelect =
                        getResourceSelect(type);

                    if (resourceSelect) {
                        resourceSelect.value = '';
                    }

                    resourceId.value = '';

                    filterResourcesByBuilding(type);

                }
            );

        });

        resourceSelects.forEach(function (select) {

            select.addEventListener(
                'change',
                function () {

                    if (
                        this.dataset.type
                        === resourceType.value
                    ) {
                        resourceId.value = this.value;
                    }

                    clearClientErrors();

                }
            );

        });

        form.addEventListener(
            'submit',
            function (event) {

                clearClientErrors();

                const selectedType =
                    resourceType.value;

                if (!selectedType) {
                    return;
                }

                if (
                    selectedType === 'room'
                    || selectedType === 'training_room'
                ) {

                    const buildingSelect =
                        getBuildingSelect(selectedType);

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
                            error.classList.add('visible');
                        }

                        buildingSelect?.focus();

                        return;

                    }

                }

                const activeSelect =
                    getResourceSelect(selectedType);

                if (
                    !activeSelect
                    || !activeSelect.value
                ) {

                    event.preventDefault();

                    const error =
                        errorElements[selectedType];

                    if (error) {
                        error.classList.add('visible');
                    }

                    activeSelect?.focus();

                    return;

                }

                resourceId.value =
                    activeSelect.value;

            }
        );

        if (
            resourceType.value === 'room'
            || resourceType.value === 'training_room'
        ) {

            filterResourcesByBuilding(
                resourceType.value
            );

        }

        updateResourceSelection();

        if (
            resourceType.value === 'room'
            || resourceType.value === 'training_room'
        ) {

            const selectedType = resourceType.value;
            const buildingSelect =
                getBuildingSelect(selectedType);

            const activeSelect =
                getResourceSelect(selectedType);

            if (
                buildingSelect
                && buildingSelect.value
                && activeSelect
            ) {

                filterResourcesByBuilding(selectedType);

                resourceId.value =
                    activeSelect.value;

            }

        }

    });
</script>

@endsection