@extends('layouts.training-officer')

@section('title', 'GITC Reservation')

@section('content')

@if (session('success'))
    <div class="to-success-message">
        <div class="to-success-icon">
            ✓
        </div>

        <div class="to-success-content">
            <strong>Reservation Submitted</strong>
            <span>{{ session('success') }}</span>
        </div>

        <button
            type="button"
            class="to-success-close"
            onclick="this.parentElement.remove()"
            aria-label="Close notification"
        >
            ×
        </button>
    </div>
@endif

@php
    $totalRooms = $buildings->sum(fn ($building) => $building->rooms->count());
    $totalTrainingRooms = $buildings->sum(fn ($building) => $building->trainingRooms->count());
    $totalFields = $fields->count();
    $totalResources = $totalRooms + $totalTrainingRooms + $totalFields;

    $trainingOfficerCart = session('training_officer_cart', [
        'rooms' => [],
        'training_rooms' => [],
        'fields' => [],
    ]);

    $initialCartItems = collect([
        ...collect($trainingOfficerCart['rooms'] ?? [])
            ->map(fn ($id) => [
                'id' => (int) $id,
                'type' => 'room',
            ])
            ->values()
            ->all(),

        ...collect($trainingOfficerCart['training_rooms'] ?? [])
            ->map(fn ($id) => [
                'id' => (int) $id,
                'type' => 'training_room',
            ])
            ->values()
            ->all(),

        ...collect($trainingOfficerCart['fields'] ?? [])
            ->map(fn ($id) => [
                'id' => (int) $id,
                'type' => 'field',
            ])
            ->values()
            ->all(),
    ])->values();
@endphp


{{-- =========================================================
     HERO
========================================================= --}}
<section class="to-hero">

    <div class="to-hero-content">

        <div class="to-hero-eyebrow">
            <span class="to-hero-dot"></span>
            GITC Reservation
        </div>

        <h1 class="to-hero-title">
            Find the right space<br>
            <span>for your next activity.</span>
        </h1>

        <p class="to-hero-description">
            Browse available rooms, training media, and fields across GITC.
            Select the resources you need and continue to your reservation.
        </p>

        <div class="to-hero-actions">

            <a
                href="#available-resources"
                class="to-primary-button"
            >
                Browse Available Resources
                <span>↓</span>
            </a>

            <button
                type="button"
                class="to-secondary-button"
                id="hero-cart-button"
            >
                View Reservation List
            </button>

        </div>

    </div>


    <div class="to-hero-summary">

        <div class="to-summary-label">
            AVAILABLE NOW
        </div>

        <div
            class="to-summary-number"
            id="hero-resource-count"
        >
            {{ $totalResources }}
        </div>

        <div class="to-summary-text">
            resources ready<br>
            for reservation
        </div>

        <div class="to-summary-line"></div>

        <div class="to-summary-items">

            <div>
                <strong>{{ $totalRooms }}</strong>
                <span>Rooms</span>
            </div>

            <div>
                <strong>{{ $totalTrainingRooms }}</strong>
                <span>Training Media</span>
            </div>

            <div>
                <strong>{{ $totalFields }}</strong>
                <span>Fields</span>
            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     PAGE INTRO
========================================================= --}}
<section
    id="available-resources"
    class="to-section-intro"
>

    <div>

        <span class="to-section-eyebrow">
            AVAILABLE RESOURCES
        </span>

        <h2>
            Choose a resource
        </h2>

        <p>
            Explore available facilities by building and add the resources
            you need to your Booking List.
        </p>

    </div>

    <div class="to-resource-total">

        <span
            class="to-resource-total-number"
            id="resource-result-count"
        >
            {{ $totalResources }}
        </span>

        <span class="to-resource-total-label">
            Available resources
        </span>

    </div>

</section>


{{-- =========================================================
     SEARCH + FILTER
========================================================= --}}
<section class="to-resource-filters">

    <div class="to-filter-search">

        <span class="to-filter-search-icon">
            SEARCH
        </span>

        <input
            type="search"
            id="resource-search"
            class="to-search-input"
            placeholder="Search rooms, training media, or fields..."
            autocomplete="off"
        >

    </div>


    <div class="to-filter-group">

        <label
            for="resource-type-filter"
            class="to-filter-label"
        >
            Resource
        </label>

        <select
            id="resource-type-filter"
            class="to-filter-select"
        >
            <option value="all">
                All Resources
            </option>

            <option value="room">
                Rooms
            </option>

            <option value="training_room">
                Training Media
            </option>

            <option value="field">
                Fields
            </option>
        </select>

    </div>


    <div class="to-filter-group">

        <label
            for="building-filter"
            class="to-filter-label"
        >
            Building
        </label>

        <select
            id="building-filter"
            class="to-filter-select"
        >

            <option value="all">
                All Buildings
            </option>

            @foreach ($buildings as $building)

                <option value="building-{{ $building->id }}">
                    {{ $building->name }}
                </option>

            @endforeach

            @if ($fields->isNotEmpty())
                <option value="field-facility">
                    Field
                </option>
            @endif

        </select>

    </div>


    <button
        type="button"
        id="clear-resource-filters"
        class="to-clear-filter-button"
    >
        Clear Filters
    </button>

</section>


{{-- =========================================================
     FILTER RESULT INFO
========================================================= --}}
<div
    id="resource-filter-result"
    class="to-filter-result"
    aria-live="polite"
>
    Showing all available resources.
</div>


{{-- =========================================================
     BUILDINGS
========================================================= --}}
<div class="to-buildings-list">

    @foreach ($buildings as $building)

        @php
            $rooms = $building->rooms;
            $trainingRooms = $building->trainingRooms;

            $resources = $rooms->concat($trainingRooms);

            $buildingImage = $building->images
                ->sortBy('sort_order')
                ->first();
        @endphp

        @if ($resources->isNotEmpty())

            <section
                class="to-building-section"
                data-building-section="building-{{ $building->id }}"
            >

                {{-- =================================================
                     BUILDING CARD
                ================================================= --}}

                <article
                    class="to-building-card"
                    data-building-card
                    data-building-id="{{ $building->id }}"
                >

                    <div class="to-building-image">

                        @if ($buildingImage)

                            <img
                                src="{{ asset('storage/' . $buildingImage->file) }}"
                                alt="{{ $building->name }}"
                            >

                        @else

                            <div class="to-building-placeholder">

                                <span>
                                    BUILDING
                                </span>

                                <small>
                                    No image available
                                </small>

                            </div>

                        @endif

                        <div class="to-building-image-overlay"></div>

                        <span class="to-building-available">
                            <span></span>
                            Available
                        </span>

                    </div>


                    <div class="to-building-card-content">

                        <div class="to-building-card-top">

                            <div>

                                <span class="to-section-eyebrow">
                                    BUILDING
                                </span>

                                <h3>
                                    {{ $building->name }}
                                </h3>

                            </div>

                            <span class="to-building-number">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>

                        </div>


                        <p class="to-building-description">

                            @if ($building->description)

                                {{ $building->description }}

                            @else

                                Available facilities in this building.

                            @endif

                        </p>


                        <div class="to-building-summary">

                            <div>
                                <strong>
                                    {{ $rooms->count() }}
                                </strong>

                                <span>
                                    {{ $rooms->count() === 1 ? 'Room' : 'Rooms' }}
                                </span>
                            </div>

                            <div>
                                <strong>
                                    {{ $trainingRooms->count() }}
                                </strong>

                                <span>
                                    Training Media
                                </span>
                            </div>

                        </div>


                        <button
                            type="button"
                            class="to-building-toggle"
                            data-building-toggle
                            aria-expanded="false"
                            aria-controls="building-resources-{{ $building->id }}"
                        >

                            <span>
                                View Rooms
                            </span>

                            <span class="to-building-toggle-arrow">
                                ↓
                            </span>

                        </button>

                    </div>

                </article>


                {{-- =================================================
                     BUILDING RESOURCES
                ================================================= --}}

                <div
                    id="building-resources-{{ $building->id }}"
                    class="to-building-resources"
                    data-building-resources
                    data-building-id="{{ $building->id }}"
                    hidden
                >

                    <div class="to-building-resources-header">

                        <div>

                            <span class="to-section-eyebrow">
                                AVAILABLE RESOURCES
                            </span>

                            <h4>
                                Resources in {{ $building->name }}
                            </h4>

                            <p>
                                Select the room or training media you need.
                            </p>

                        </div>

                        <span class="to-building-count">
                            {{ $resources->count() }}
                            {{ $resources->count() === 1 ? 'resource' : 'resources' }}
                        </span>

                    </div>


                    <div class="to-resource-grid">

                        @foreach ($resources as $resource)

                            @php

                                $isRoom =
                                    $resource instanceof \App\Models\Room;

                                $resourceType =
                                    $isRoom
                                        ? 'Room'
                                        : 'Training Media';

                                $resourceTypeClass =
                                    $isRoom
                                        ? 'room'
                                        : 'training';

                                $cartType =
                                    $isRoom
                                        ? 'room'
                                        : 'training_room';

                                $isInCart = in_array(
                                    (int) $resource->id,
                                    array_map(
                                        'intval',
                                        $trainingOfficerCart[
                                            $isRoom
                                                ? 'rooms'
                                                : 'training_rooms'
                                        ] ?? []
                                    ),
                                    true
                                );

                                $resourceImage =
                                    $resource->images
                                        ->sortBy('sort_order')
                                        ->first();

                            @endphp


                            <article
                                class="to-resource-card"
                                data-resource-card
                                data-resource-id="{{ $resource->id }}"
                                data-resource-type="{{ $resourceTypeClass }}"
                                data-filter-type="{{ $cartType }}"
                                data-cart-type="{{ $cartType }}"
                                data-resource-name="{{ $resource->name }}"
                                data-building-id="{{ $building->id }}"
                                data-building-filter="building-{{ $building->id }}"
                                data-building-name="{{ $building->name }}"
                            >

                                <div class="to-resource-image">

                                    @if ($resourceImage)

                                        <img
                                            src="{{ asset('storage/' . $resourceImage->file) }}"
                                            alt="{{ $resource->name }}"
                                        >

                                    @else

                                        <div class="to-resource-placeholder">

                                            <div class="to-placeholder-icon">
                                                {{ $isRoom ? 'ROOM' : 'MEDIA' }}
                                            </div>

                                            <span>
                                                No image available
                                            </span>

                                        </div>

                                    @endif

                                    <div class="to-image-overlay"></div>

                                    <span class="to-availability-badge">
                                        <span></span>
                                        Available
                                    </span>

                                </div>


                                <div class="to-resource-content">

                                    <div class="to-resource-meta">

                                        <span
                                            class="to-resource-type {{ $resourceTypeClass }}"
                                        >
                                            {{ $resourceType }}
                                        </span>

                                    </div>


                                    <h4 class="to-resource-name">
                                        {{ $resource->name }}
                                    </h4>


                                    <div class="to-resource-details">

                                        <div class="to-resource-detail">

                                            <small>
                                                Capacity
                                            </small>

                                            <strong>
                                                {{ $resource->capacity }}
                                            </strong>

                                        </div>


                                        @if ($isRoom)

                                            <div class="to-resource-detail">

                                                <small>
                                                    LCD
                                                </small>

                                                <strong>
                                                    {{ $resource->lcd_count }}
                                                </strong>

                                            </div>


                                            <div class="to-resource-detail">

                                                <small>
                                                    Board
                                                </small>

                                                <strong>
                                                    {{ $resource->whiteboard_count }}
                                                </strong>

                                            </div>

                                        @else

                                            <div class="to-resource-detail">

                                                <small>
                                                    Simulation
                                                </small>

                                                <strong>
                                                    {{ $resource->simulation_type ?: 'Standard' }}
                                                </strong>

                                            </div>

                                        @endif

                                    </div>


                                    <button
                                        type="button"
                                        class="to-add-cart-button"
                                        data-add-cart
                                        data-in-cart="{{ $isInCart ? 'true' : 'false' }}"
                                    >

                                        <span>
                                            {{ $isInCart
                                                ? 'Remove from Cart'
                                                : 'Add to Booking List'
                                            }}
                                        </span>

                                        <span class="to-add-cart-arrow">
                                            {{ $isInCart ? '×' : '→' }}
                                        </span>

                                    </button>

                                </div>

                            </article>

                        @endforeach

                    </div>

                </div>

            </section>

        @endif

    @endforeach

</div>
{{-- =========================================================
     FIELDS
========================================================= --}}
@if ($fields->isNotEmpty())

    <section
        class="to-field-section"
        data-field-section
    >

        <div class="to-field-header">

            <div>

                <span class="to-section-eyebrow">
                    OUTDOOR FACILITIES
                </span>

                <h2>
                    Fields
                </h2>

                <p>
                    Available fields for your next activity or event.
                </p>

            </div>

            <div class="to-field-accent">
                {{ $totalFields }}
                {{ $totalFields === 1 ? 'Field' : 'Fields' }}
            </div>

        </div>


        <div class="to-field-grid">

            @foreach ($fields as $field)

                @php
                    $isInCart = in_array(
                        (int) $field->id,
                        array_map(
                            'intval',
                            $trainingOfficerCart['fields'] ?? []
                        ),
                        true
                    );
                @endphp

                <article
                    class="to-field-card"
                    data-resource-card
                    data-resource-id="{{ $field->id }}"
                    data-resource-type="field"
                    data-filter-type="field"
                    data-cart-type="field"
                    data-resource-name="{{ $field->name }}"
                    data-building-filter="field-facility"
                    data-building-name="GITC Facility"
                >

                    <div class="to-field-image">

                        @if ($field->images->first())

                            <img
                                src="{{ asset('storage/' . $field->images->first()->file) }}"
                                alt="{{ $field->name }}"
                            >

                        @else

                            <div class="to-field-placeholder">
                                <span>
                                    FIELD
                                </span>
                            </div>

                        @endif

                        <div class="to-image-overlay"></div>

                        <span class="to-availability-badge">
                            <span></span>
                            Available
                        </span>

                    </div>


                    <div class="to-field-content">

                        <span class="to-resource-type field">
                            Field
                        </span>

                        <h3>
                            {{ $field->name }}
                        </h3>

                        <div class="to-field-capacity">
                            Capacity:

                            <strong>
                                {{ $field->capacity }}
                            </strong>
                        </div>

                        <button
                            type="button"
                            class="to-add-cart-button"
                            data-add-cart
                            data-in-cart="{{ $isInCart ? 'true' : 'false' }}"
                        >

                            <span>
                                {{ $isInCart
                                    ? 'Remove from Cart'
                                    : 'Add to Booking List'
                                }}
                            </span>

                            <span class="to-add-cart-arrow">
                                {{ $isInCart ? '×' : '→' }}
                            </span>

                        </button>

                    </div>

                </article>

            @endforeach

        </div>

    </section>

@endif


{{-- =========================================================
     NO SEARCH RESULT
========================================================= --}}
<div
    id="resource-empty-state"
    class="to-resource-empty-state"
    style="display: none;"
>

    <div class="to-resource-empty-icon">
        SEARCH
    </div>

    <h3>
        No resources found
    </h3>

    <p>
        Try changing your search keyword or filters.
    </p>

    <button
        type="button"
        id="empty-clear-filters"
        class="to-empty-clear-button"
    >
        Clear Search & Filters
    </button>

</div>


{{-- =========================================================
     CART TEASER
========================================================= --}}
<section class="to-cart-teaser">

    <div class="to-cart-teaser-icon">
        🛒
    </div>

    <div class="to-cart-teaser-content">

        <span>
            YOUR BOOKING LIST
        </span>

        <h2>
            Build your reservation in one place.
        </h2>

        <p>
            Select multiple resources first. You can provide the booking
            information for each building before submitting.
        </p>

    </div>

    <button
        type="button"
        class="to-cart-teaser-button"
        id="teaser-cart-button"
    >
        Open Booking List
        <span>→</span>
    </button>

</section>


{{-- =========================================================
     PAGE STYLES
========================================================= --}}
<style>

    /* =========================================================
       SUCCESS MESSAGE
    ========================================================= */

    .to-success-message {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 24px;
        padding: 15px 17px;
        border: 1px solid #b7e3d2;
        border-radius: 12px;
        background: #f0faf6;
        color: #145c43;
    }

    .to-success-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 36px;
        border-radius: 50%;
        background: #16805b;
        color: white;
        font-size: 18px;
        font-weight: 800;
    }

    .to-success-content {
        display: flex;
        flex-direction: column;
        gap: 2px;
        flex: 1;
    }

    .to-success-content strong {
        color: #145c43;
        font-size: 13px;
        font-weight: 800;
    }

    .to-success-content span {
        color: #477565;
        font-size: 12px;
        line-height: 1.5;
    }

    .to-success-close {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 0;
        background: transparent;
        color: #477565;
        font-size: 20px;
        cursor: pointer;
        border-radius: 7px;
    }

    .to-success-close:hover {
        background: rgba(20, 92, 67, 0.08);
    }


    /* =========================================================
       HERO
    ========================================================= */

    .to-hero {
        position: relative;
        display: grid;
        grid-template-columns: minmax(0, 1.55fr) minmax(280px, 0.45fr);
        min-height: 390px;
        overflow: hidden;
        margin-bottom: 48px;
        border-radius: 20px;
        background:
            linear-gradient(
                120deg,
                #0a203b 0%,
                #0f2747 58%,
                #12375f 100%
            );
        box-shadow:
            0 16px 40px rgba(15, 39, 71, 0.14);
    }

    .to-hero::before {
        content: "";
        position: absolute;
        width: 420px;
        height: 420px;
        right: 170px;
        bottom: -260px;
        border: 70px solid rgba(14, 165, 164, 0.12);
        border-radius: 50%;
    }

    .to-hero::after {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        right: -90px;
        top: -100px;
        border: 50px solid rgba(255, 255, 255, 0.035);
        border-radius: 50%;
    }

    .to-hero-content {
        position: relative;
        z-index: 2;
        padding: 48px 50px;
    }

    .to-hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #8ee8e6;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.13em;
        text-transform: uppercase;
    }

    .to-hero-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #27c5c3;
        box-shadow:
            0 0 0 4px rgba(39, 197, 195, 0.12);
    }

    .to-hero-title {
        max-width: 760px;
        margin: 20px 0 0;
        color: white;
        font-size: clamp(34px, 4vw, 52px);
        line-height: 1.05;
        letter-spacing: -0.04em;
        font-weight: 800;
    }

    .to-hero-title span {
        color: #65d8d5;
    }

    .to-hero-description {
        max-width: 650px;
        margin: 20px 0 0;
        color: #c8d5e4;
        font-size: 15px;
        line-height: 1.75;
    }

    .to-hero-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 30px;
    }

    .to-primary-button,
    .to-secondary-button {
        min-height: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 0 17px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition:
            transform 0.18s ease,
            background 0.18s ease,
            border-color 0.18s ease;
    }

    .to-primary-button {
        background: #16a9a7;
        color: white;
        border: 1px solid #16a9a7;
    }

    .to-primary-button:hover {
        background: #109391;
        transform: translateY(-1px);
    }

    .to-secondary-button {
        background: rgba(255, 255, 255, 0.08);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.22);
    }

    .to-secondary-button:hover {
        background: rgba(255, 255, 255, 0.13);
        transform: translateY(-1px);
    }

    .to-hero-summary {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 42px;
        background: rgba(255, 255, 255, 0.045);
        border-left: 1px solid rgba(255, 255, 255, 0.09);
    }

    .to-summary-label {
        color: #91a7bf;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.15em;
    }

    .to-summary-number {
        margin-top: 8px;
        color: white;
        font-size: 64px;
        line-height: 1;
        font-weight: 800;
        letter-spacing: -0.05em;
    }

    .to-summary-text {
        margin-top: 7px;
        color: #b6c7d8;
        font-size: 13px;
        line-height: 1.5;
    }

    .to-summary-line {
        height: 1px;
        margin: 28px 0;
        background: rgba(255, 255, 255, 0.12);
    }

    .to-summary-items {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .to-summary-items div {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .to-summary-items strong {
        color: white;
        font-size: 18px;
        font-weight: 800;
    }

    .to-summary-items span {
        color: #91a7bf;
        font-size: 10px;
    }


    /* =========================================================
       SECTION INTRO
    ========================================================= */

    .to-section-intro {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 30px;
        margin-bottom: 20px;
    }

    .to-section-eyebrow {
        color: var(--gitc-teal);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.15em;
    }

    .to-section-intro h2,
    .to-field-header h2 {
        margin: 6px 0 0;
        color: var(--gitc-navy);
        font-size: 28px;
        line-height: 1.2;
        letter-spacing: -0.025em;
        font-weight: 800;
    }

    .to-section-intro p,
    .to-field-header p {
        max-width: 620px;
        margin: 7px 0 0;
        color: var(--gitc-muted);
        font-size: 13px;
        line-height: 1.6;
    }

    .to-resource-total {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 13px;
        border: 1px solid var(--gitc-border);
        border-radius: 9px;
        background: white;
    }

    .to-resource-total-number {
        color: var(--gitc-navy);
        font-size: 17px;
        font-weight: 800;
    }

    .to-resource-total-label {
        color: var(--gitc-muted);
        font-size: 11px;
    }


    /* =========================================================
       SEARCH + FILTER
    ========================================================= */

    .to-resource-filters {
        display: grid;
        grid-template-columns: minmax(260px, 1.7fr) minmax(170px, 0.65fr) minmax(190px, 0.75fr) auto;
        align-items: end;
        gap: 12px;
        margin-bottom: 10px;
        padding: 15px;
        border: 1px solid var(--gitc-border);
        border-radius: 13px;
        background: white;
        box-shadow: 0 5px 18px rgba(15, 39, 71, 0.04);
    }

    .to-filter-search {
        position: relative;
        min-width: 0;
    }

    .to-filter-search-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gitc-blue);
        font-size: 8px;
        font-weight: 900;
        letter-spacing: 0.04em;
        pointer-events: none;
    }

    .to-search-input,
    .to-filter-select {
        width: 100%;
        min-height: 44px;
        border: 1px solid #d5dfe9;
        border-radius: 8px;
        background: #f9fbfd;
        color: var(--gitc-text);
        font-family: inherit;
        font-size: 12px;
        outline: none;
        transition:
            border-color 0.18s ease,
            background 0.18s ease,
            box-shadow 0.18s ease;
    }

    .to-search-input {
        padding: 0 13px 0 67px;
    }

    .to-search-input::placeholder {
        color: #8a98a8;
    }

    .to-search-input:focus,
    .to-filter-select:focus {
        border-color: var(--gitc-teal);
        background: white;
        box-shadow: 0 0 0 3px rgba(22, 169, 167, 0.09);
    }

    .to-filter-group {
        min-width: 0;
    }

    .to-filter-label {
        display: block;
        margin: 0 0 6px;
        color: var(--gitc-muted);
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .to-filter-select {
        padding: 0 12px;
        cursor: pointer;
    }

    .to-clear-filter-button {
        min-height: 44px;
        padding: 0 14px;
        border: 1px solid #d5dfe9;
        border-radius: 8px;
        background: white;
        color: var(--gitc-navy);
        font-size: 11px;
        font-weight: 800;
        cursor: pointer;
        transition:
            background 0.18s ease,
            border-color 0.18s ease,
            color 0.18s ease;
    }

    .to-clear-filter-button:hover {
        border-color: var(--gitc-navy);
        background: var(--gitc-navy);
        color: white;
    }

    .to-filter-result {
        min-height: 25px;
        margin-bottom: 19px;
        color: var(--gitc-muted);
        font-size: 11px;
    }


    /* =========================================================
       BUILDINGS
    ========================================================= */

    .to-building-section {
        margin-bottom: 44px;
    }

    .to-building-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 18px 0;
        border-top: 1px solid #dbe3ec;
        border-bottom: 1px solid #dbe3ec;
        margin-bottom: 18px;
    }

    .to-building-heading {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .to-building-number {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 38px;
        border-radius: 9px;
        background: var(--gitc-navy);
        color: white;
        font-size: 11px;
        font-weight: 800;
    }

    .to-building-heading h3 {
        margin: 0;
        color: var(--gitc-navy);
        font-size: 19px;
        line-height: 1.25;
        font-weight: 800;
    }

    .to-building-heading p {
        margin: 4px 0 0;
        color: var(--gitc-muted);
        font-size: 12px;
    }

    .to-building-count {
        color: var(--gitc-muted);
        font-size: 12px;
        font-weight: 600;
    }


    /* =========================================================
       RESOURCE CARDS
    ========================================================= */

    .to-resource-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .to-resource-card {
        overflow: hidden;
        display: flex;
        flex-direction: column;
        border: 1px solid var(--gitc-border);
        border-radius: 13px;
        background: white;
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            border-color 0.2s ease;
    }

    .to-resource-card:hover {
        transform: translateY(-3px);
        border-color: #cbd7e5;
        box-shadow:
            0 12px 28px rgba(15, 39, 71, 0.09);
    }

    .to-resource-card.to-resource-hidden,
    .to-field-card.to-resource-hidden {
        display: none;
    }

    .to-resource-image {
        position: relative;
        height: 185px;
        overflow: hidden;
        background: #e9eef4;
    }

    .to-resource-image img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        transition: transform 0.35s ease;
    }

    .to-resource-card:hover .to-resource-image img {
        transform: scale(1.035);
    }

    .to-image-overlay {
        position: absolute;
        inset: 0;
        background:
            linear-gradient(
                to bottom,
                rgba(15, 39, 71, 0.03),
                rgba(15, 39, 71, 0.18)
            );
        pointer-events: none;
    }

    .to-resource-placeholder,
    .to-field-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background:
            linear-gradient(
                135deg,
                #e8eef5,
                #f5f8fb
            );
        color: #8a9aab;
    }

    .to-placeholder-icon {
        color: var(--gitc-navy);
        font-size: 17px;
        font-weight: 900;
        letter-spacing: 0.1em;
    }

    .to-resource-placeholder span {
        font-size: 11px;
    }

    .to-availability-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 9px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.94);
        color: #166534;
        font-size: 10px;
        font-weight: 800;
        box-shadow:
            0 3px 10px rgba(0, 0, 0, 0.08);
    }

    .to-availability-badge span {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #16a34a;
    }

    .to-resource-content {
        display: flex;
        flex-direction: column;
        flex: 1;
        padding: 18px;
    }

    .to-resource-meta {
        min-height: 20px;
    }

    .to-resource-type {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 5px;
        background: #edf3f9;
        color: var(--gitc-blue);
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 0.07em;
        text-transform: uppercase;
    }

    .to-resource-type.training {
        background: #e8f7f6;
        color: #087f7d;
    }

    .to-resource-type.field {
        background: #eef5ee;
        color: #39713e;
    }

    .to-resource-name {
        margin: 10px 0 0;
        color: var(--gitc-navy);
        font-size: 17px;
        line-height: 1.3;
        font-weight: 800;
    }

    .to-resource-details {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
        margin-top: 17px;
    }

    .to-resource-detail {
        display: flex;
        align-items: center;
        gap: 7px;
        min-width: 0;
        padding: 8px;
        border-radius: 7px;
        background: #f6f8fa;
    }

    .to-detail-icon {
        width: 26px;
        height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 26px;
        border-radius: 6px;
        background: white;
        color: var(--gitc-blue);
        font-size: 7px;
        font-weight: 900;
        border: 1px solid #e5eaf0;
    }

    .to-resource-detail div {
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .to-resource-detail small {
        color: #8290a0;
        font-size: 8px;
    }

    .to-resource-detail strong {
        overflow: hidden;
        color: var(--gitc-text);
        font-size: 11px;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .to-add-cart-button {
        width: 100%;
        min-height: 42px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 17px;
        padding: 0 13px;
        border: 1px solid #cdd9e6;
        border-radius: 8px;
        background: white;
        color: var(--gitc-navy);
        font-size: 11px;
        font-weight: 800;
        cursor: pointer;
        transition:
            background 0.18s ease,
            border-color 0.18s ease,
            color 0.18s ease,
            transform 0.18s ease;
    }

    .to-add-cart-button:hover {
        background: var(--gitc-navy);
        border-color: var(--gitc-navy);
        color: white;
        transform: translateY(-1px);
    }

    .to-add-cart-button.in-cart {
        background: #0f766e;
        border-color: #0f766e;
        color: white;
    }

    .to-add-cart-button.in-cart:hover {
        background: #115e59;
        border-color: #115e59;
    }

    .to-add-cart-button:disabled {
        opacity: 0.6;
        cursor: wait;
        transform: none;
    }

    .to-add-cart-arrow {
        color: var(--gitc-teal);
        font-size: 17px;
        transition: transform 0.18s ease;
    }

    .to-add-cart-button:hover .to-add-cart-arrow {
        color: #6ee7e5;
        transform: translateX(3px);
    }

    .to-add-cart-button.in-cart .to-add-cart-arrow {
        color: white;
    }

/* =========================================================
   BUILDING CARDS
========================================================= */

.to-building-card {
    overflow: hidden;
    display: grid;
    grid-template-columns: minmax(280px, 0.9fr) minmax(0, 1.1fr);
    border: 1px solid var(--gitc-border);
    border-radius: 16px;
    background: white;
    box-shadow: 0 5px 18px rgba(15, 39, 71, 0.04);
    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease,
        border-color 0.2s ease;
}

.to-building-card:hover {
    border-color: #cbd7e5;
    box-shadow: 0 12px 30px rgba(15, 39, 71, 0.08);
    transform: translateY(-2px);
}

.to-building-image {
    position: relative;
    width: 100%;
    height: 280px;
    overflow: hidden;
    background: #e9eef4;
}

.to-building-image img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    transition: transform 0.35s ease;
}

.to-building-card:hover .to-building-image img {
    transform: scale(1.035);
}

.to-building-image-overlay {
    position: absolute;
    inset: 0;
    background:
        linear-gradient(
            to bottom,
            rgba(15, 39, 71, 0.02),
            rgba(15, 39, 71, 0.28)
        );
    pointer-events: none;
}

.to-building-placeholder {
    width: 100%;
    height: 100%;
    min-height: 260px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background:
        linear-gradient(
            135deg,
            #e8eef5,
            #f5f8fb
        );
    color: #8495a7;
}

.to-building-placeholder span {
    color: var(--gitc-navy);
    font-size: 20px;
    font-weight: 900;
    letter-spacing: 0.1em;
}

.to-building-placeholder small {
    font-size: 11px;
}

.to-building-available {
    position: absolute;
    top: 14px;
    right: 14px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.94);
    color: #166534;
    font-size: 10px;
    font-weight: 800;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
}

.to-building-available span {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #16a34a;
}

.to-building-card-content {
    display: flex;
    flex-direction: column;
    padding: 28px;
}

.to-building-card-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
}

.to-building-card-top h3 {
    margin: 7px 0 0;
    color: var(--gitc-navy);
    font-size: 27px;
    line-height: 1.2;
    font-weight: 800;
    letter-spacing: -0.025em;
}

.to-building-number {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    border-radius: 9px;
    background: var(--gitc-navy);
    color: white;
    font-size: 11px;
    font-weight: 800;
}

.to-building-description {
    max-width: 520px;
    margin: 14px 0 0;
    color: var(--gitc-muted);
    font-size: 13px;
    line-height: 1.65;
}

.to-building-summary {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
    margin-top: 24px;
}

.to-building-summary > div {
    display: flex;
    flex-direction: column;
    gap: 3px;
    padding: 12px;
    border-radius: 9px;
    background: #f6f8fa;
}

.to-building-summary strong {
    color: var(--gitc-navy);
    font-size: 20px;
    font-weight: 800;
}

.to-building-summary span {
    color: var(--gitc-muted);
    font-size: 10px;
}

.to-building-toggle {
    width: 100%;
    min-height: 46px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: auto;
    padding: 0 15px;
    border: 1px solid #cdd9e6;
    border-radius: 8px;
    background: white;
    color: var(--gitc-navy);
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
    transition:
        background 0.18s ease,
        border-color 0.18s ease,
        color 0.18s ease;
}

.to-building-toggle:hover,
.to-building-toggle.is-open {
    border-color: var(--gitc-navy);
    background: var(--gitc-navy);
    color: white;
}

.to-building-toggle-arrow {
    color: var(--gitc-teal);
    font-size: 17px;
    transition: transform 0.2s ease;
}

.to-building-toggle.is-open .to-building-toggle-arrow {
    color: white;
    transform: rotate(180deg);
}


/* =========================================================
   BUILDING RESOURCES
========================================================= */

.to-building-resources {
    margin-top: 14px;
    padding: 24px;
    border: 1px solid var(--gitc-border);
    border-radius: 14px;
    background: #f8fafc;
}

.to-building-resources[hidden] {
    display: none;
}

.to-building-resources-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 18px;
}

.to-building-resources-header h4 {
    margin: 6px 0 0;
    color: var(--gitc-navy);
    font-size: 19px;
    font-weight: 800;
}

.to-building-resources-header p {
    margin: 5px 0 0;
    color: var(--gitc-muted);
    font-size: 12px;
}

.to-building-resources .to-building-count {
    padding: 7px 10px;
    border: 1px solid #dce5ed;
    border-radius: 7px;
    background: white;
    color: var(--gitc-navy);
    font-size: 10px;
    font-weight: 800;
}


/* =========================================================
   RESPONSIVE BUILDING
========================================================= */

@media (max-width: 900px) {

    .to-building-card {
        grid-template-columns: 1fr;
    }

    .to-building-image {
        min-height: 230px;
    }

    .to-building-placeholder {
        min-height: 230px;
    }

}

@media (max-width: 700px) {

    .to-building-card-content {
        padding: 22px;
    }

    .to-building-card-top h3 {
        font-size: 23px;
    }

    .to-building-resources {
        padding: 18px;
    }

    .to-building-resources-header {
        align-items: flex-start;
        flex-direction: column;
    }

}

@media (max-width: 480px) {

    .to-building-summary {
        grid-template-columns: 1fr;
    }

}
    /* =========================================================
       FIELD SECTION
    ========================================================= */

    .to-field-section {
        margin-top: 20px;
        margin-bottom: 42px;
        padding: 28px;
        border-radius: 16px;
        background:
            linear-gradient(
                135deg,
                #edf3f8,
                #f8fafc
            );
        border: 1px solid #dce5ed;
    }

    .to-field-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .to-field-accent {
        padding: 8px 11px;
        border-radius: 7px;
        background: white;
        color: var(--gitc-navy);
        border: 1px solid #dce5ed;
        font-size: 11px;
        font-weight: 800;
    }

    .to-field-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .to-field-card {
        overflow: hidden;
        border: 1px solid #dbe4ec;
        border-radius: 12px;
        background: white;
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .to-field-card:hover {
        transform: translateY(-3px);
        box-shadow:
            0 10px 25px rgba(15, 39, 71, 0.08);
    }

    .to-field-image {
        position: relative;
        height: 165px;
        overflow: hidden;
        background: #e7edf2;
    }

    .to-field-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .to-field-placeholder {
        background:
            linear-gradient(
                135deg,
                #dfe9e5,
                #f1f6f3
            );
    }

    .to-field-placeholder span {
        color: #52715d;
        font-size: 20px;
        font-weight: 900;
        letter-spacing: 0.12em;
    }

    .to-field-content {
        padding: 18px;
    }

    .to-field-content h3 {
        margin: 9px 0 0;
        color: var(--gitc-navy);
        font-size: 17px;
        font-weight: 800;
    }

    .to-field-capacity {
        margin-top: 9px;
        color: var(--gitc-muted);
        font-size: 12px;
    }

    .to-field-capacity strong {
        color: var(--gitc-text);
    }


    /* =========================================================
       EMPTY SEARCH RESULT
    ========================================================= */

    .to-resource-empty-state {
        padding: 55px 25px;
        margin: 5px 0 42px;
        border: 1px dashed #cbd7e3;
        border-radius: 14px;
        background: #f8fafc;
        text-align: center;
    }

    .to-resource-empty-icon {
        width: 58px;
        height: 58px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        border-radius: 12px;
        background: #e8f2f5;
        color: var(--gitc-blue);
        font-size: 9px;
        font-weight: 900;
        letter-spacing: 0.08em;
    }

    .to-resource-empty-state h3 {
        margin: 0;
        color: var(--gitc-navy);
        font-size: 19px;
        font-weight: 800;
    }

    .to-resource-empty-state p {
        margin: 7px 0 18px;
        color: var(--gitc-muted);
        font-size: 12px;
    }

    .to-empty-clear-button {
        min-height: 40px;
        padding: 0 15px;
        border: 1px solid #cdd9e6;
        border-radius: 8px;
        background: white;
        color: var(--gitc-navy);
        font-size: 11px;
        font-weight: 800;
        cursor: pointer;
    }

    .to-empty-clear-button:hover {
        background: var(--gitc-navy);
        border-color: var(--gitc-navy);
        color: white;
    }


    /* =========================================================
       CART TEASER
    ========================================================= */

    .to-cart-teaser {
        position: relative;
        display: flex;
        align-items: center;
        gap: 22px;
        overflow: hidden;
        margin-top: 12px;
        padding: 25px 28px;
        border-radius: 15px;
        background: var(--gitc-navy);
        box-shadow:
            0 12px 30px rgba(15, 39, 71, 0.12);
    }

    .to-cart-teaser::after {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        right: 80px;
        top: -120px;
        border: 40px solid rgba(14, 165, 164, 0.13);
        border-radius: 50%;
    }

    .to-cart-teaser-icon {
        position: relative;
        z-index: 2;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 48px;
        border-radius: 11px;
        background: rgba(255, 255, 255, 0.1);
        font-size: 21px;
    }

    .to-cart-teaser-content {
        position: relative;
        z-index: 2;
        flex: 1;
    }

    .to-cart-teaser-content > span {
        color: #75d9d7;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 0.14em;
    }

    .to-cart-teaser-content h2 {
        margin: 4px 0 0;
        color: white;
        font-size: 18px;
        font-weight: 800;
    }

    .to-cart-teaser-content p {
        max-width: 680px;
        margin: 5px 0 0;
        color: #b8c8d8;
        font-size: 11px;
        line-height: 1.55;
    }

    .to-cart-teaser-button {
        position: relative;
        z-index: 2;
        min-height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 0 15px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.09);
        color: white;
        font-size: 11px;
        font-weight: 800;
        cursor: pointer;
        transition:
            background 0.18s ease,
            transform 0.18s ease;
    }

    .to-cart-teaser-button:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-1px);
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1100px) {

        .to-hero {
            grid-template-columns: 1fr;
        }

        .to-hero-summary {
            padding: 25px 42px;
            border-top: 1px solid rgba(255, 255, 255, 0.09);
            border-left: 0;
        }

        .to-summary-line {
            margin: 18px 0;
        }

        .to-resource-filters {
            grid-template-columns: 1fr 1fr;
        }

        .to-filter-search {
            grid-column: 1 / -1;
        }

        .to-resource-grid,
        .to-field-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

    }


    @media (max-width: 700px) {

        .to-hero-content {
            padding: 32px 25px;
        }

        .to-hero-title {
            font-size: 34px;
        }

        .to-hero-summary {
            padding: 24px 25px;
        }

        .to-section-intro,
        .to-field-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .to-resource-filters {
            grid-template-columns: 1fr;
        }

        .to-filter-search {
            grid-column: auto;
        }

        .to-clear-filter-button {
            width: 100%;
        }

        .to-resource-grid,
        .to-field-grid {
            grid-template-columns: 1fr;
        }

        .to-building-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .to-resource-total {
            width: 100%;
        }

        .to-field-section {
            padding: 20px;
        }

        .to-cart-teaser {
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .to-cart-teaser-button {
            width: 100%;
        }

    }


    @media (max-width: 480px) {

        .to-hero-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .to-primary-button,
        .to-secondary-button {
            width: 100%;
        }

        .to-resource-details {
            grid-template-columns: 1fr;
        }

        .to-summary-items {
            gap: 10px;
        }

    }

</style>


{{-- =========================================================
     PAGE SCRIPT
========================================================= --}}
@section('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
     * =========================================================
     * ELEMENTS
     * =========================================================
     */

    const cartCount =
        document.querySelector('.to-cart-count');

    const heroCartButton =
        document.getElementById('hero-cart-button');

    const teaserCartButton =
        document.getElementById('teaser-cart-button');

    const searchInput =
        document.getElementById('resource-search');

    const resourceTypeFilter =
        document.getElementById('resource-type-filter');

    const buildingFilter =
        document.getElementById('building-filter');

    const clearFiltersButton =
        document.getElementById('clear-resource-filters');

    const emptyClearFiltersButton =
        document.getElementById('empty-clear-filters');

    const filterResult =
        document.getElementById('resource-filter-result');

    const resultCount =
        document.getElementById('resource-result-count');

    const heroResourceCount =
        document.getElementById('hero-resource-count');

    const emptyState =
        document.getElementById('resource-empty-state');

    const csrfToken =
        '{{ csrf_token() }}';


    /*
     * =========================================================
     * INITIAL CART STATE
     * =========================================================
     */

    let cartItems =
        @json($initialCartItems);


    /*
     * =========================================================
     * RESOURCE CARDS
     * =========================================================
     */

    const resourceCards =
        Array.from(
            document.querySelectorAll('[data-resource-card]')
        );

    const buildingSections =
        Array.from(
            document.querySelectorAll('[data-building-section]')
        );

    const fieldSection =
        document.querySelector('[data-field-section]');


            /*
        * =========================================================
        * BUILDING TOGGLE
        * =========================================================
        */

        const buildingToggles =
            document.querySelectorAll('[data-building-toggle]');


        buildingToggles.forEach(function (toggle) {

            toggle.addEventListener('click', function () {

                const targetId =
                    toggle.getAttribute('aria-controls');

                const target =
                    document.getElementById(targetId);

                if (!target) {
                    return;
                }


                const isOpen =
                    toggle.getAttribute('aria-expanded') === 'true';


                /*
                * Tutup semua building lain.
                */
                document
                    .querySelectorAll('[data-building-toggle]')
                    .forEach(function (otherToggle) {

                        const otherTargetId =
                            otherToggle.getAttribute('aria-controls');

                        const otherTarget =
                            document.getElementById(otherTargetId);

                        if (
                            otherToggle !== toggle &&
                            otherTarget
                        ) {

                            otherToggle.setAttribute(
                                'aria-expanded',
                                'false'
                            );

                            otherToggle.classList.remove(
                                'is-open'
                            );

                            otherTarget.hidden = true;

                            const label =
                                otherToggle.querySelector(
                                    'span:first-child'
                                );

                            if (label) {
                                label.textContent =
                                    'View Rooms';
                            }
                        }

                    });


                /*
                * Toggle building yang dipilih.
                */
                if (isOpen) {

                    toggle.setAttribute(
                        'aria-expanded',
                        'false'
                    );

                    toggle.classList.remove(
                        'is-open'
                    );

                    target.hidden = true;

                    const label =
                        toggle.querySelector(
                            'span:first-child'
                        );

                    if (label) {
                        label.textContent =
                            'View Rooms';
                    }

                } else {

                    toggle.setAttribute(
                        'aria-expanded',
                        'true'
                    );

                    toggle.classList.add(
                        'is-open'
                    );

                    target.hidden = false;

                    const label =
                        toggle.querySelector(
                            'span:first-child'
                        );

                    if (label) {
                        label.textContent =
                            'Hide Rooms';
                    }

                    /*
                    * Scroll sedikit agar resource
                    * yang baru dibuka terlihat.
                    */
                    setTimeout(function () {

                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });

                    }, 80);

                }

            });

});


    /*
     * =========================================================
     * UPDATE NAVBAR COUNT
     * =========================================================
     */

    function updateCartCount() {

        if (!cartCount) {
            return;
        }

        cartCount.textContent =
            cartItems.length;

    }


    /*
     * =========================================================
     * FIND CART ITEM
     * =========================================================
     */

    function findCartItem(id, type) {

        return cartItems.find(function (item) {

            return (
                String(item.id) === String(id) &&
                item.type === type
            );

        });

    }


    /*
     * =========================================================
     * UPDATE BUTTON
     * =========================================================
     */

    function updateButton(button, added) {

        if (!button) {
            return;
        }

        const label =
            button.querySelector('span:first-child');

        const arrow =
            button.querySelector('.to-add-cart-arrow');


        if (!label) {
            return;
        }


        if (added) {

            label.textContent =
                'Remove from Booking List';

            button.classList.add('in-cart');

            button.dataset.inCart =
                'true';

            if (arrow) {

                arrow.textContent =
                    '×';

            }

        } else {

            label.textContent =
                'Add to Booking List';

            button.classList.remove('in-cart');

            button.dataset.inCart =
                'false';

            if (arrow) {

                arrow.textContent =
                    '→';

            }

        }

    }


    /*
     * =========================================================
     * ADD TO CART
     * =========================================================
     */

    async function addToCart(button, card) {

        const resourceId =
            card.dataset.resourceId;

        const type =
            card.dataset.cartType;


        button.disabled = true;


        try {

            const response =
                await fetch(
                    '{{ route('training-officer.cart.add') }}',
                    {
                        method: 'POST',

                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'text/html, application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },

                        body: JSON.stringify({
                            type: type,
                            id: resourceId
                        })
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'Unable to add resource to cart.'
                );

            }


            if (!findCartItem(resourceId, type)) {

                cartItems.push({
                    id: Number(resourceId),
                    type: type
                });

            }


            updateCartCount();

            updateButton(
                button,
                true
            );


        } catch (error) {

            console.error(error);

            alert(
                'Unable to add this resource to your booking list.'
            );

        } finally {

            button.disabled = false;

        }

    }


    /*
     * =========================================================
     * REMOVE FROM CART
     * =========================================================
     */

    async function removeFromCart(button, card) {

        const resourceId =
            card.dataset.resourceId;

        const type =
            card.dataset.cartType;


        button.disabled = true;


        try {

            const response =
                await fetch(
                    '{{ route('training-officer.cart.remove') }}',
                    {
                        method: 'POST',

                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'text/html, application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },

                        body: JSON.stringify({
                            _method: 'DELETE',
                            type: type,
                            id: resourceId
                        })
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'Unable to remove resource from cart.'
                );

            }


            cartItems =
                cartItems.filter(function (item) {

                    return !(
                        String(item.id) === String(resourceId) &&
                        item.type === type
                    );

                });


            updateCartCount();

            updateButton(
                button,
                false
            );


        } catch (error) {

            console.error(error);

            alert(
                'Unable to remove this resource from your Booking List.'
            );

        } finally {

            button.disabled = false;

        }

    }


    /*
     * =========================================================
     * ADD / REMOVE BUTTONS
     * =========================================================
     */

    const cartButtons =
        document.querySelectorAll('[data-add-cart]');


    cartButtons.forEach(function (button) {

        const card =
            button.closest('[data-resource-card]');


        if (!card) {
            return;
        }


        const initialState =
            button.dataset.inCart === 'true';


        updateButton(
            button,
            initialState
        );


        button.addEventListener(
            'click',
            async function () {

                const resourceId =
                    card.dataset.resourceId;

                const type =
                    card.dataset.cartType;


                const existing =
                    findCartItem(
                        resourceId,
                        type
                    );


                if (existing) {

                    await removeFromCart(
                        button,
                        card
                    );

                } else {

                    await addToCart(
                        button,
                        card
                    );

                }

            }
        );

    });


    /*
     * =========================================================
     * RESOURCE SEARCH + FILTER
     * =========================================================
     */

    function applyResourceFilters() {

        const searchTerm =
            searchInput
                ? searchInput.value
                    .trim()
                    .toLowerCase()
                : '';

        const selectedType =
            resourceTypeFilter
                ? resourceTypeFilter.value
                : 'all';

        const selectedBuilding =
            buildingFilter
                ? buildingFilter.value
                : 'all';


        let visibleCount = 0;


        /*
         * -----------------------------------------------------
         * FILTER RESOURCE CARDS
         * -----------------------------------------------------
         */

        resourceCards.forEach(function (card) {

            const resourceName =
                (
                    card.dataset.resourceName || ''
                ).toLowerCase();

            const resourceType =
                card.dataset.filterType || '';

            const building =
                card.dataset.buildingFilter || '';


            const matchesSearch =
                !searchTerm ||
                resourceName.includes(searchTerm);

            const matchesType =
                selectedType === 'all' ||
                resourceType === selectedType;

            const matchesBuilding =
                selectedBuilding === 'all' ||
                building === selectedBuilding;


            const shouldShow =
                matchesSearch &&
                matchesType &&
                matchesBuilding;


            if (shouldShow) {

                card.classList.remove(
                    'to-resource-hidden'
                );

                visibleCount++;

            } else {

                card.classList.add(
                    'to-resource-hidden'
                );

            }

        });


        /*
         * -----------------------------------------------------
         * HIDE EMPTY BUILDING SECTIONS
         * -----------------------------------------------------
         */

        buildingSections.forEach(function (section) {

            const cards =
                Array.from(
                    section.querySelectorAll(
                        '[data-resource-card]'
                    )
                );

            const visibleCards =
                cards.filter(function (card) {

                    return !card.classList.contains(
                        'to-resource-hidden'
                    );

                });


            const countElement =
                section.querySelector(
                    '[data-building-count]'
                );


            if (countElement) {

                countElement.textContent =
                    visibleCards.length +
                    (
                        visibleCards.length === 1
                            ? ' resource'
                            : ' resources'
                    );

            }


            if (visibleCards.length === 0) {

                section.style.display =
                    'none';

            } else {

                section.style.display =
                    '';

            }

        });


        /*
         * -----------------------------------------------------
         * FIELD SECTION
         * -----------------------------------------------------
         */

        if (fieldSection) {

            const fieldCards =
                Array.from(
                    fieldSection.querySelectorAll(
                        '[data-resource-card]'
                    )
                );

            const visibleFieldCards =
                fieldCards.filter(function (card) {

                    return !card.classList.contains(
                        'to-resource-hidden'
                    );

                });


            if (visibleFieldCards.length === 0) {

                fieldSection.style.display =
                    'none';

            } else {

                fieldSection.style.display =
                    '';

            }

        }


        /*
         * -----------------------------------------------------
         * UPDATE COUNTERS
         * -----------------------------------------------------
         */

        if (resultCount) {

            resultCount.textContent =
                visibleCount;

        }


        if (heroResourceCount) {

            heroResourceCount.textContent =
                visibleCount;

        }


        /*
         * -----------------------------------------------------
         * EMPTY STATE
         * -----------------------------------------------------
         */

        if (emptyState) {

            if (visibleCount === 0) {

                emptyState.style.display =
                    'block';

            } else {

                emptyState.style.display =
                    'none';

            }

        }


        /*
         * -----------------------------------------------------
         * RESULT MESSAGE
         * -----------------------------------------------------
         */

        if (filterResult) {

            const hasFilters =
                Boolean(searchTerm) ||
                selectedType !== 'all' ||
                selectedBuilding !== 'all';


            if (!hasFilters) {

                filterResult.textContent =
                    'Showing all available resources.';

            } else {

                filterResult.textContent =
                    'Showing ' +
                    visibleCount +
                    (
                        visibleCount === 1
                            ? ' matching resource.'
                            : ' matching resources.'
                    );

            }

        }

    }


    /*
     * =========================================================
     * CLEAR FILTERS
     * =========================================================
     */

    function clearResourceFilters() {

        if (searchInput) {

            searchInput.value =
                '';

        }

        if (resourceTypeFilter) {

            resourceTypeFilter.value =
                'all';

        }

        if (buildingFilter) {

            buildingFilter.value =
                'all';

        }


        applyResourceFilters();


        /*
         * Kembalikan focus ke search.
         */
        if (searchInput) {

            searchInput.focus();

        }

    }


    /*
     * =========================================================
     * FILTER EVENTS
     * =========================================================
     */

    if (searchInput) {

        searchInput.addEventListener(
            'input',
            applyResourceFilters
        );

    }


    if (resourceTypeFilter) {

        resourceTypeFilter.addEventListener(
            'change',
            applyResourceFilters
        );

    }


    if (buildingFilter) {

        buildingFilter.addEventListener(
            'change',
            applyResourceFilters
        );

    }


    if (clearFiltersButton) {

        clearFiltersButton.addEventListener(
            'click',
            clearResourceFilters
        );

    }


    if (emptyClearFiltersButton) {

        emptyClearFiltersButton.addEventListener(
            'click',
            clearResourceFilters
        );

    }


    /*
     * =========================================================
     * OPEN CART
     * =========================================================
     */

    function openCart() {

        window.location.href =
            '{{ route('training-officer.cart') }}';

    }


    if (heroCartButton) {

        heroCartButton.addEventListener(
            'click',
            openCart
        );

    }


    if (teaserCartButton) {

        teaserCartButton.addEventListener(
            'click',
            openCart
        );

    }


    /*
     * =========================================================
     * INITIAL STATE
     * =========================================================
     */

    updateCartCount();

    applyResourceFilters();

});
</script>

@endsection

@endsection