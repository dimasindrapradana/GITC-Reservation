@extends('layouts.training-officer-classroom')

@section('title', 'GITC Reservation')

@section('content')

@php
    /*
     * =========================================================
     * CLASSROOM RESOURCE SUMMARY
     * =========================================================
     */

    $totalRooms = $buildings->sum(
        fn ($building) => $building->rooms->count()
    );

    $totalTrainingRooms = $buildings->sum(
        fn ($building) => $building->trainingRooms->count()
    );

    $totalFields = 0;

    $totalResources =
        $totalRooms +
        $totalTrainingRooms;


    /*
     * =========================================================
     * CLASSROOM BOOKING CART
     * =========================================================
     */

    $trainingOfficerCart = session(
        'training_officer_classroom_cart',
        [
            'rooms' => [],
        ]
    );


    /*
     * =========================================================
     * INITIAL CART ITEMS FOR JAVASCRIPT
     * =========================================================
     */

    $initialCartItems = collect(
        $trainingOfficerCart['rooms'] ?? []
    )
        ->map(fn ($id) => [
            'id' => (int) $id,
            'type' => 'room',
        ])
        ->values();
@endphp


{{-- =========================================================
     HERO
========================================================= --}}
<section class="to-hero">

    <div class="to-hero-background" aria-hidden="true">

        <div
            class="to-hero-background-slide active"
            style="background-image: url('{{ asset('assets/images/slider-01.jpg') }}');"
        ></div>

        <div
            class="to-hero-background-slide"
            style="background-image: url('{{ asset('assets/images/slider-02.jpg') }}');"
        ></div>

        <div
            class="to-hero-background-slide"
            style="background-image: url('{{ asset('assets/images/slider-03.jpg') }}');"
        ></div>

        <div
            class="to-hero-background-slide"
            style="background-image: url('{{ asset('assets/images/slider-04.jpg') }}');"
        ></div>

        <div
            class="to-hero-background-slide"
            style="background-image: url('{{ asset('assets/images/slider-05.jpg') }}');"
        ></div>

    </div>


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
            Browse available rooms across GITC.
            Select the rooms you need and continue to your reservation.
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
            Explore available facilities by building.
            Only rooms can currently be added to your Booking List.
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
            placeholder="Search rooms..."
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
     BUILDINGS
========================================================= --}}
<div class="to-buildings-list">

    @foreach ($buildings as $building)

        @php
            $rooms = $building->rooms;
            $trainingRooms = $building->trainingRooms;

            $resources = $rooms;

            $buildingImage = $building->images
                ->sortBy('sort_order')
                ->first();
        @endphp


        @if ($resources->isNotEmpty())

            <section
                class="to-building-section"
                data-building-section="building-{{ $building->id }}"
            >

                {{-- BUILDING CARD --}}
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

                        </div>


                        <button
                            type="button"
                            class="to-building-toggle"
                            data-building-toggle
                            aria-expanded="false"
                            aria-controls="building-resources-{{ $building->id }}"
                        >

                            <span>
                                Show Rooms
                            </span>

                            <span class="to-building-toggle-arrow">
                                ↓
                            </span>

                        </button>

                    </div>

                </article>


                {{-- BUILDING RESOURCES --}}
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
                                Select the room you need for your reservation.
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

                                $isInCart = $isRoom
                                    ? in_array(
                                        (int) $resource->id,
                                        array_map(
                                            'intval',
                                            $trainingOfficerCart['rooms'] ?? []
                                        ),
                                        true
                                    )
                                    : false;

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

                                {{-- RESOURCE IMAGE --}}
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


                                {{-- RESOURCE CONTENT --}}
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


                                    {{-- ROOM CART ACTION --}}
                                    @if ($isRoom)

                                        <button
                                            type="button"
                                            class="to-add-cart-button {{ $isInCart ? 'in-cart' : '' }}"
                                            data-add-cart
                                            data-resource-id="{{ $resource->id }}"
                                            data-cart-type="room"
                                            data-in-cart="{{ $isInCart ? 'true' : 'false' }}"
                                        >

                                            <span>
                                                {{ $isInCart
                                                    ? 'Remove from Booking List'
                                                    : 'Add to Booking List'
                                                }}
                                            </span>

                                            <span class="to-add-cart-arrow">
                                                {{ $isInCart ? '×' : '→' }}
                                            </span>

                                        </button>

                                    @else

                                        <div class="to-resource-unavailable-action">
                                            Training Media is currently
                                            view-only.
                                        </div>

                                    @endif

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
            Select multiple rooms first. You can provide the booking
            information before submitting your reservation.
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
     BOOKING LIST SUCCESS TOAST
========================================================= --}}
<div
    id="cart-toast"
    class="to-cart-toast"
    role="status"
    aria-live="polite"
>

    <div class="to-cart-toast-icon">
        ✓
    </div>

    <div class="to-cart-toast-content">

        <p class="to-cart-toast-title">
            Room added to Booking List.
        </p>

        <p class="to-cart-toast-message">
            Please check your Booking List to continue.
        </p>

    </div>

</div>


{{-- =========================================================
     PAGE STYLES
========================================================= --}}
<style>

    /* =========================================================
       GITC FONT SYSTEM
       =========================================================
       Garuda Sans  = UI, labels, descriptions, buttons
       Garuda Serif = headings and prominent titles
    ========================================================= */

    .to-hero,
    .to-section-intro,
    .to-resource-filters,
    .to-filter-result,
    .to-buildings-list,
    .to-resource-empty-state,
    .to-cart-teaser,
    #cart-toast {
        font-family: 'Garuda Sans', sans-serif;
    }


    .to-hero-title,
    .to-section-intro h2,
    .to-building-card-top h3,
    .to-building-resources-header h4,
    .to-resource-name,
    .to-field-header h2,
    .to-field-content h3,
    .to-resource-empty-state h3,
    .to-cart-teaser-content h2 {
        font-family: 'Garuda Serif', serif;
    }


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
        font-family: 'Garuda Sans', sans-serif;
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
        grid-template-columns:
            minmax(0, 1.55fr)
            minmax(280px, 0.45fr);
        min-height: 390px;
        overflow: hidden;
        margin-bottom: 48px;
        border-radius: 20px;
        background: #0a203b;
        box-shadow:
            0 16px 40px rgba(15, 39, 71, 0.14);
    }


    /* =========================================================
       HERO FONT
    ========================================================= */

    .to-hero-eyebrow {
        font-family: 'Garuda Sans', sans-serif;
        font-weight: 700;
    }

    .to-hero-title,
    .to-hero-title span {
        font-family: 'Garuda Serif', serif;
        font-weight: 700;
    }

    .to-hero-description {
        font-family: 'Garuda Sans', sans-serif;
        font-weight: 400;
    }

    .to-primary-button,
    .to-secondary-button {
        font-family: 'Garuda Sans', sans-serif;
        font-weight: 700;
    }

    .to-summary-label {
        font-family: 'Garuda Sans', sans-serif;
        font-weight: 700;
    }

    .to-summary-number {
        font-family: 'Garuda Serif', serif;
        font-weight: 700;
    }

    .to-summary-text {
        font-family: 'Garuda Sans', sans-serif;
        font-weight: 400;
    }

    .to-summary-items strong,
    .to-summary-items span {
        font-family: 'Garuda Sans', sans-serif;
    }


    /* =========================================================
       HERO BACKGROUND SLIDESHOW
    ========================================================= */

    .to-hero-background {
        position: absolute;
        inset: 0;
        z-index: 0;
        overflow: hidden;
    }

    .to-hero-background-slide {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        opacity: 0;
        transform: scale(1.02);
        transition:
            opacity 1.2s ease,
            transform 6s ease;
    }

    .to-hero-background-slide.active {
        opacity: 1;
        transform: scale(1);
    }


    /* =========================================================
       DARK OVERLAY
    ========================================================= */

    .to-hero-background::after {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(
                90deg,
                rgba(5, 25, 46, 0.94) 0%,
                rgba(5, 25, 46, 0.82) 34%,
                rgba(5, 25, 46, 0.55) 58%,
                rgba(5, 25, 46, 0.28) 78%,
                rgba(5, 25, 46, 0.35) 100%
            );
        pointer-events: none;
    }

    .to-hero::before,
    .to-hero::after {
        content: none;
    }

    .to-hero-content {
        position: relative;
        z-index: 3;
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
        font-weight: 700;
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
        z-index: 3;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 42px;
        background: rgba(5, 25, 46, 0.34);
        border-left: 1px solid rgba(255, 255, 255, 0.16);
        backdrop-filter: blur(2px);
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
        font-weight: 700;
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
        font-weight: 700;
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
        font-family: 'Garuda Sans', sans-serif;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.15em;
    }

    .to-section-intro h2,
    .to-field-header h2 {
        margin: 6px 0 0;
        color: var(--gitc-navy);
        font-family: 'Garuda Sans', sans-serif;
        font-size: 28px;
        line-height: 1.2;
        letter-spacing: -0.025em;
        font-weight: 700;
    }

    .to-section-intro p,
    .to-field-header p {
        max-width: 620px;
        margin: 7px 0 0;
        color: var(--gitc-muted);
        font-family: 'Garuda Sans', sans-serif;
        font-size: 13px;
        line-height: 1.6;
        font-weight: 400;
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
        font-family: 'Garuda Sans', sans-serif;
        font-size: 17px;
        font-weight: 800;
    }

    .to-resource-total-label {
        color: var(--gitc-muted);
        font-family: 'Garuda Sans', sans-serif;
        font-size: 11px;
        font-weight: 400;
    }


    /* =========================================================
       SEARCH + FILTER
    ========================================================= */

    .to-resource-filters {
        display: grid;
        grid-template-columns:
            minmax(260px, 1.7fr)
            minmax(170px, 0.65fr)
            minmax(190px, 0.75fr)
            auto;
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
        font-family: 'Garuda Sans', sans-serif;
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
        font-family: 'Garuda Sans', sans-serif;
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
        font-family: 'Garuda Sans', sans-serif;
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
        font-family: 'Garuda Sans', sans-serif;
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
        font-family: 'Garuda Sans', sans-serif;
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
        font-family: 'Garuda Sans', sans-serif;
        font-size: 11px;
        font-weight: 400;
    }


    /* =========================================================
       BUILDING
    ========================================================= */

    .to-building-section {
        margin-bottom: 44px;
    }

    .to-building-card {
        overflow: hidden;
        display: grid;
        grid-template-columns:
            minmax(280px, 0.9fr)
            minmax(0, 1.1fr);
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
        font-family: 'Garuda Sans', sans-serif;
        font-size: 20px;
        font-weight: 900;
        letter-spacing: 0.1em;
    }

    .to-building-placeholder small {
        font-family: 'Garuda Sans', sans-serif;
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
        font-family: 'Garuda Sans', sans-serif;
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
        font-family: 'Garuda Serif', serif;
        font-size: 27px;
        line-height: 1.2;
        font-weight: 700;
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
        font-family: 'Garuda Sans', sans-serif;
        font-size: 11px;
        font-weight: 800;
    }

    .to-building-description {
        max-width: 520px;
        margin: 14px 0 0;
        color: var(--gitc-muted);
        font-family: 'Garuda Sans', sans-serif;
        font-size: 13px;
        line-height: 1.65;
        font-weight: 400;
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
        font-family: 'Garuda Sans', sans-serif;
        font-size: 20px;
        font-weight: 800;
    }

    .to-building-summary span {
        color: var(--gitc-muted);
        font-family: 'Garuda Sans', sans-serif;
        font-size: 10px;
        font-weight: 400;
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
        font-family: 'Garuda Sans', sans-serif;
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
        font-family: 'Garuda Sans', sans-serif;
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
        font-family: 'Garuda Serif', serif;
        font-size: 19px;
        font-weight: 700;
    }

    .to-building-resources-header p {
        margin: 5px 0 0;
        color: var(--gitc-muted);
        font-family: 'Garuda Sans', sans-serif;
        font-size: 12px;
        font-weight: 400;
    }

    .to-building-resources .to-building-count {
        padding: 7px 10px;
        border: 1px solid #dce5ed;
        border-radius: 7px;
        background: white;
        color: var(--gitc-navy);
        font-family: 'Garuda Sans', sans-serif;
        font-size: 10px;
        font-weight: 800;
    }


    /* =========================================================
       RESOURCE GRID
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

    .to-resource-image img,
    .to-field-image img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        transition: transform 0.35s ease;
    }

    .to-resource-card:hover .to-resource-image img,
    .to-field-card:hover .to-field-image img {
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
        font-family: 'Garuda Sans', sans-serif;
    }

    .to-placeholder-icon {
        color: var(--gitc-navy);
        font-family: 'Garuda Sans', sans-serif;
        font-size: 17px;
        font-weight: 900;
        letter-spacing: 0.1em;
    }

    .to-resource-placeholder span {
        font-family: 'Garuda Sans', sans-serif;
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
        font-family: 'Garuda Sans', sans-serif;
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
        font-family: 'Garuda Sans', sans-serif;
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
        font-family: 'Garuda Serif', serif;
        font-size: 17px;
        line-height: 1.3;
        font-weight: 700;
    }

    .to-resource-details {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
        margin-top: 17px;
    }

    .to-resource-detail {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 3px;
        min-width: 0;
        padding: 8px;
        border-radius: 7px;
        background: #f6f8fa;
    }

    .to-resource-detail small {
        color: #8290a0;
        font-family: 'Garuda Sans', sans-serif;
        font-size: 8px;
        font-weight: 400;
    }

    .to-resource-detail strong {
        overflow: hidden;
        color: var(--gitc-text);
        font-family: 'Garuda Sans', sans-serif;
        font-size: 11px;
        font-weight: 800;
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
        font-family: 'Garuda Sans', sans-serif;
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
        font-family: 'Garuda Sans', sans-serif;
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

    .to-resource-unavailable-action {
        width: 100%;
        min-height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 17px;
        padding: 8px 12px;
        border: 1px dashed #d2dce7;
        border-radius: 8px;
        background: #f7f9fb;
        color: #7b8998;
        font-family: 'Garuda Sans', sans-serif;
        font-size: 10px;
        font-weight: 700;
        text-align: center;
    }


    /* =========================================================
       FIELDS
    ========================================================= */

    .to-field-section {
        margin: 60px 0 44px;
        padding-top: 30px;
        border-top: 1px solid #dbe3ec;
    }

    .to-field-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 25px;
        margin-bottom: 20px;
    }

    .to-field-accent {
        padding: 9px 12px;
        border: 1px solid var(--gitc-border);
        border-radius: 8px;
        background: white;
        color: var(--gitc-navy);
        font-family: 'Garuda Sans', sans-serif;
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
        display: flex;
        flex-direction: column;
        border: 1px solid var(--gitc-border);
        border-radius: 13px;
        background: white;
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .to-field-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(15, 39, 71, 0.08);
    }

    .to-field-image {
        position: relative;
        height: 185px;
        overflow: hidden;
        background: #e9eef4;
    }

    .to-field-content {
        display: flex;
        flex-direction: column;
        padding: 18px;
    }

    .to-field-content h3 {
        margin: 10px 0 0;
        color: var(--gitc-navy);
        font-family: 'Garuda Serif', serif;
        font-size: 18px;
        line-height: 1.3;
        font-weight: 700;
    }

    .to-field-capacity {
        margin-top: 14px;
        color: var(--gitc-muted);
        font-family: 'Garuda Sans', sans-serif;
        font-size: 11px;
        font-weight: 400;
    }

    .to-field-capacity strong {
        color: var(--gitc-navy);
        font-family: 'Garuda Sans', sans-serif;
        font-size: 13px;
        font-weight: 800;
    }


    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .to-resource-empty-state {
        margin: 30px 0;
        padding: 50px 25px;
        border: 1px dashed #ccd8e4;
        border-radius: 14px;
        background: #f8fafc;
        text-align: center;
    }

    .to-resource-empty-icon {
        color: var(--gitc-blue);
        font-family: 'Garuda Sans', sans-serif;
        font-size: 10px;
        font-weight: 900;
        letter-spacing: 0.12em;
    }

    .to-resource-empty-state h3 {
        margin: 10px 0 0;
        color: var(--gitc-navy);
        font-family: 'Garuda Serif', serif;
        font-size: 20px;
        font-weight: 700;
    }

    .to-resource-empty-state p {
        margin: 7px 0 20px;
        color: var(--gitc-muted);
        font-family: 'Garuda Sans', sans-serif;
        font-size: 12px;
        font-weight: 400;
    }

    .to-empty-clear-button {
        min-height: 42px;
        padding: 0 15px;
        border: 1px solid var(--gitc-navy);
        border-radius: 8px;
        background: var(--gitc-navy);
        color: white;
        font-family: 'Garuda Sans', sans-serif;
        font-size: 11px;
        font-weight: 800;
        cursor: pointer;
    }


    /* =========================================================
       CART TEASER
    ========================================================= */

    .to-cart-teaser {
        display: grid;
        grid-template-columns: auto minmax(0, 1fr) auto;
        align-items: center;
        gap: 20px;
        margin: 60px 0 20px;
        padding: 25px;
        border: 1px solid #cbd9e7;
        border-radius: 15px;
        background:
            linear-gradient(
                135deg,
                #f5fafc,
                #edf7f8
            );
    }

    .to-cart-teaser-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: var(--gitc-navy);
        font-size: 21px;
    }

    .to-cart-teaser-content > span {
        color: var(--gitc-teal);
        font-family: 'Garuda Sans', sans-serif;
        font-size: 9px;
        font-weight: 900;
        letter-spacing: 0.14em;
    }

    .to-cart-teaser-content h2 {
        margin: 5px 0 0;
        color: var(--gitc-navy);
        font-family: 'Garuda Serif', serif;
        font-size: 21px;
        font-weight: 700;
    }

    .to-cart-teaser-content p {
        max-width: 650px;
        margin: 6px 0 0;
        color: var(--gitc-muted);
        font-family: 'Garuda Sans', sans-serif;
        font-size: 11px;
        line-height: 1.55;
        font-weight: 400;
    }

    .to-cart-teaser-button {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 0 16px;
        border: 1px solid var(--gitc-navy);
        border-radius: 8px;
        background: var(--gitc-navy);
        color: white;
        font-family: 'Garuda Sans', sans-serif;
        font-size: 11px;
        font-weight: 800;
        cursor: pointer;
        transition:
            background 0.18s ease,
            transform 0.18s ease;
    }

    .to-cart-teaser-button:hover {
        background: #183b63;
        transform: translateY(-1px);
    }


    /* =========================================================
       RESERVATION CART TOAST
    ========================================================= */

    #cart-toast {
        position: fixed;
        top: 80px;
        left: 50%;
        transform: translateX(-50%) translateY(-15px);

        z-index: 9999;

        display: flex;
        align-items: center;
        gap: 14px;

        width: max-content;
        min-width: 335px;
        max-width: 420px;

        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);

        padding: 16px 20px;

        opacity: 0;
        visibility: hidden;

        transition:
            opacity 0.3s ease,
            transform 0.3s ease,
            visibility 0.3s ease;
    }

    #cart-toast.show {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }

    .to-cart-toast-icon {
        flex: 0 0 42px;
        width: 42px;
        height: 42px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;
        background: #e6f7f7;

        color: #16a6a6;
        font-family: 'Garuda Sans', sans-serif;
        font-size: 24px;
        font-weight: 700;
    }

    .to-cart-toast-content {
        flex: 1;
        min-width: 0;
    }

    .to-cart-toast-title {
        margin: 0 0 4px;

        color: #19304d;
        font-family: 'Garuda Sans', sans-serif;
        font-size: 16px;
        font-weight: 700;
        line-height: 1.4;
    }

    .to-cart-toast-message {
        margin: 0;

        color: #64748b;
        font-family: 'Garuda Sans', sans-serif;
        font-size: 14px;
        line-height: 1.5;
        font-weight: 400;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1100px) {

        .to-resource-grid,
        .to-field-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .to-resource-filters {
            grid-template-columns: 1fr 1fr;
        }

        .to-filter-search {
            grid-column: 1 / -1;
        }

    }


    @media (max-width: 850px) {

        .to-hero {
            grid-template-columns: 1fr;
        }

        .to-hero-summary {
            border-left: 0;
            border-top: 1px solid rgba(255,255,255,0.09);
        }

        .to-building-card {
            grid-template-columns: 1fr;
        }

        .to-building-image {
            height: 230px;
        }

        .to-cart-teaser {
            grid-template-columns: auto 1fr;
        }

        .to-cart-teaser-button {
            grid-column: 1 / -1;
            width: 100%;
            justify-content: center;
        }

    }


    @media (max-width: 650px) {

        .to-hero-content {
            padding: 35px 25px;
        }

        .to-hero-summary {
            padding: 30px 25px;
        }

        .to-hero-title {
            font-size: 36px;
        }

        .to-section-intro {
            align-items: flex-start;
            flex-direction: column;
        }

        .to-resource-total {
            width: 100%;
        }

        .to-resource-filters {
            grid-template-columns: 1fr;
        }

        .to-filter-search {
            grid-column: auto;
        }

        .to-resource-grid,
        .to-field-grid {
            grid-template-columns: 1fr;
        }

        .to-building-resources {
            padding: 17px;
        }

        .to-building-resources-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .to-field-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .to-cart-teaser {
            grid-template-columns: 1fr;
        }

        .to-cart-teaser-icon {
            width: 45px;
            height: 45px;
        }

        .to-cart-toast {
            top: 16px;
            right: 16px;
            left: 16px;
            width: auto;
        }

    }

</style>


{{-- =========================================================
     JAVASCRIPT
========================================================= --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
     * =========================================================
     * INITIAL STATE
     * =========================================================
     */

    let cartItems = @json($initialCartItems);


    /*
     * =========================================================
     * CSRF
     * =========================================================
     */

    const csrfToken =
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content');


    /*
     * =========================================================
     * DOM ELEMENTS
     * =========================================================
     */

    const searchInput =
        document.getElementById('resource-search');

    const typeFilter =
        document.getElementById('resource-type-filter');

    const buildingFilter =
        document.getElementById('building-filter');

    const clearFiltersButton =
        document.getElementById('clear-resource-filters');

    const emptyClearButton =
        document.getElementById('empty-clear-filters');

    const resultCount =
        document.getElementById('resource-result-count');

    const resultInfo =
        document.getElementById('resource-filter-result');

    const emptyState =
        document.getElementById('resource-empty-state');


    /*
     * =========================================================
     * CLASSROOM CART URLS
     * =========================================================
     */

    const cartAddUrl =
        @json(url('/training-officer/classroom/cart/add'));

    const cartRemoveUrl =
        @json(url('/training-officer/classroom/cart/remove'));

    const cartIndexUrl =
        @json(url('/training-officer/classroom/cart'));


    /*
     * =========================================================
     * CART HELPERS
     * =========================================================
     */

    function normalizeCart(items) {

        if (!Array.isArray(items)) {
            return [];
        }

        return items
            .map(function (item) {

                return {
                    id: Number(item.id),
                    type: item.type || 'room'
                };

            })
            .filter(function (item) {

                return (
                    Number.isFinite(item.id) &&
                    item.id > 0 &&
                    item.type === 'room'
                );

            });

    }


    cartItems =
        normalizeCart(cartItems);


    function isInCart(id, type = 'room') {

        return cartItems.some(function (item) {

            return (
                Number(item.id) === Number(id) &&
                item.type === type
            );

        });

    }


    function getRoomCartCount() {

        return cartItems.filter(function (item) {

            return item.type === 'room';

        }).length;

    }


    /*
     * =========================================================
     * CART TOAST
     * =========================================================
     */

    let cartToastTimer = null;

    function showCartToast() {

        const toast =
            document.getElementById('cart-toast');

        if (!toast) {
            return;
        }

        toast.classList.add('show');

        clearTimeout(cartToastTimer);

        cartToastTimer =
            setTimeout(function () {

                toast.classList.remove('show');

            }, 4000);

    }


    /*
     * =========================================================
     * UPDATE CART BUTTON VISUALS
     * =========================================================
     */

    function updateCartVisuals() {

        document
            .querySelectorAll('[data-add-cart]')
            .forEach(function (button) {

                const id =
                    Number(button.dataset.resourceId);

                const type =
                    button.dataset.cartType || 'room';

                const active =
                    isInCart(id, type);


                button.dataset.inCart =
                    active
                        ? 'true'
                        : 'false';


                button.classList.toggle(
                    'in-cart',
                    active
                );


                const text =
                    button.querySelector(
                        'span:first-child'
                    );


                const arrow =
                    button.querySelector(
                        '.to-add-cart-arrow'
                    );


                if (text) {

                    text.textContent =
                        active
                            ? 'Remove from Booking List'
                            : 'Add to Booking List';

                }


                if (arrow) {

                    arrow.textContent =
                        active
                            ? '×'
                            : '→';

                }

            });


        document
            .querySelectorAll(
                '#reservation-cart-count, [data-reservation-cart-count]'
            )
            .forEach(function (element) {

                element.textContent =
                    getRoomCartCount();

            });

    }


    /*
     * =========================================================
     * SEND CART REQUEST
     * =========================================================
     */

    async function sendCartRequest(
        url,
        roomId,
        method = 'POST'
    ) {

        if (!csrfToken) {

            throw new Error(
                'CSRF token was not found.'
            );

        }


        const response =
            await fetch(
                url,
                {
                    method: method,

                    headers: {

                        'Content-Type':
                            'application/json',

                        'Accept':
                            'application/json',

                        'X-CSRF-TOKEN':
                            csrfToken,

                        'X-Requested-With':
                            'XMLHttpRequest'

                    },

                    body:
                        JSON.stringify({
                            id: Number(roomId),
                            type: 'room'
                        })
                }
            );


        if (!response.ok) {

            let message =
                'Unable to update the Booking List.';


            try {

                const contentType =
                    response.headers.get(
                        'content-type'
                    ) || '';


                if (
                    contentType.includes(
                        'application/json'
                    )
                ) {

                    const data =
                        await response.json();


                    if (data?.message) {

                        message =
                            data.message;

                    }

                }

            } catch (error) {

                /*
                 * Keep default error message.
                 */

            }


            throw new Error(message);

        }


        const data =
            await response.json();


        if (
            typeof window.updateClassroomReservationCount === 'function' &&
            Number.isFinite(Number(data?.cart_count))
        ) {

            window.updateClassroomReservationCount(
                Number(data.cart_count)
            );

        }


        return data;

    }


    /*
     * =========================================================
     * ADD / REMOVE ROOM
     * =========================================================
     */

    document
        .querySelectorAll('[data-add-cart]')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                async function () {

                    const id =
                        Number(
                            button.dataset.resourceId
                        );


                    const type =
                        'room';


                    if (
                        !Number.isFinite(id) ||
                        id <= 0
                    ) {

                        alert(
                            'Invalid room selected.'
                        );

                        return;

                    }


                    const currentlyInCart =
                        isInCart(
                            id,
                            type
                        );


                    button.disabled = true;


                    try {

                        /*
                         * =================================================
                         * REMOVE
                         * =================================================
                         */

                        if (currentlyInCart) {

                            await sendCartRequest(
                                cartRemoveUrl,
                                id,
                                'DELETE'
                            );


                            cartItems =
                                cartItems.filter(
                                    function (item) {

                                        return !(
                                            Number(item.id) === id &&
                                            item.type === 'room'
                                        );

                                    }
                                );

                        }


                        /*
                         * =================================================
                         * ADD
                         * =================================================
                         */

                        else {

                            await sendCartRequest(
                                cartAddUrl,
                                id,
                                'POST'
                            );


                            if (
                                !isInCart(
                                    id,
                                    'room'
                                )
                            ) {

                                cartItems.push({

                                    id: id,

                                    type: 'room'

                                });

                            }


                            showCartToast();

                        }


                        updateCartVisuals();

                        updateFilterResults();


                    } catch (error) {

                        console.error(
                            'Booking List error:',
                            error
                        );


                        alert(
                            error.message ||
                            'Unable to update the Booking List.'
                        );


                    } finally {

                        button.disabled = false;

                    }

                }
            );

        });


    /*
     * =========================================================
     * BUILDING TOGGLE
     * =========================================================
     */

    document
        .querySelectorAll('[data-building-toggle]')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    const targetId =
                        button.getAttribute(
                            'aria-controls'
                        );


                    const target =
                        document.getElementById(
                            targetId
                        );


                    if (!target) {

                        return;

                    }


                    const isOpen =
                        !target.hidden;


                    target.hidden =
                        isOpen;


                    button.setAttribute(
                        'aria-expanded',
                        isOpen
                            ? 'false'
                            : 'true'
                    );


                    button.classList.toggle(
                        'is-open',
                        !isOpen
                    );


                    const label =
                        button.querySelector(
                            'span:first-child'
                        );


                    if (label) {

                        label.textContent =
                            isOpen
                                ? 'Show Rooms'
                                : 'Collapse Rooms';

                    }

                }
            );

        });


    /*
     * =========================================================
     * FILTERING
     * =========================================================
     */

    function updateFilterResults() {

        const query =
            (
                searchInput?.value ||
                ''
            )
                .trim()
                .toLowerCase();


        const selectedType =
            typeFilter?.value ||
            'all';


        const selectedBuilding =
            buildingFilter?.value ||
            'all';


        const cards =
            Array.from(
                document.querySelectorAll(
                    '[data-resource-card]'
                )
            );


        let visibleCount =
            0;


        cards.forEach(function (card) {

            const name =
                (
                    card.dataset.resourceName ||
                    ''
                ).toLowerCase();


            const type =
                card.dataset.filterType ||
                '';


            const building =
                card.dataset.buildingFilter ||
                '';


            const matchesSearch =
                !query ||
                name.includes(query);


            const matchesType =
                selectedType === 'all' ||
                type === selectedType;


            const matchesBuilding =
                selectedBuilding === 'all' ||
                building === selectedBuilding;


            const visible =
                matchesSearch &&
                matchesType &&
                matchesBuilding;


            card.classList.toggle(
                'to-resource-hidden',
                !visible
            );


            if (visible) {

                visibleCount++;

            }

        });


        /*
         * =========================================================
         * BUILDING VISIBILITY
         * =========================================================
         */

        document
            .querySelectorAll(
                '[data-building-section]'
            )
            .forEach(function (section) {

                const cardsInside =
                    Array.from(
                        section.querySelectorAll(
                            '[data-resource-card]'
                        )
                    );


                if (!cardsInside.length) {

                    return;

                }


                const hasVisible =
                    cardsInside.some(
                        function (card) {

                            return !card.classList.contains(
                                'to-resource-hidden'
                            );

                        }
                    );


                section.style.display =
                    hasVisible
                        ? ''
                        : 'none';

            });


        /*
         * =========================================================
         * RESULT COUNT
         * =========================================================
         */

        if (resultCount) {

            resultCount.textContent =
                visibleCount;

        }


        /*
         * =========================================================
         * RESULT MESSAGE
         * =========================================================
         */

        if (resultInfo) {

            if (
                !query &&
                selectedType === 'all' &&
                selectedBuilding === 'all'
            ) {

                resultInfo.textContent =
                    '';

            } else {

                resultInfo.textContent =
                    'Showing ' +
                    visibleCount +
                    ' matching ' +
                    (
                        visibleCount === 1
                            ? 'resource.'
                            : 'resources.'
                    );

            }

        }


        /*
         * =========================================================
         * EMPTY STATE
         * =========================================================
         */

        if (emptyState) {

            emptyState.style.display =
                visibleCount === 0
                    ? ''
                    : 'none';

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
            updateFilterResults
        );

    }


    if (typeFilter) {

        typeFilter.addEventListener(
            'change',
            updateFilterResults
        );

    }


    if (buildingFilter) {

        buildingFilter.addEventListener(
            'change',
            updateFilterResults
        );

    }


    /*
     * =========================================================
     * CLEAR FILTERS
     * =========================================================
     */

    function clearFilters() {

        if (searchInput) {

            searchInput.value =
                '';

        }


        if (typeFilter) {

            typeFilter.value =
                'all';

        }


        if (buildingFilter) {

            buildingFilter.value =
                'all';

        }


        document
            .querySelectorAll(
                '[data-building-section]'
            )
            .forEach(function (section) {

                section.style.display =
                    '';

            });


        updateFilterResults();

    }


    if (clearFiltersButton) {

        clearFiltersButton.addEventListener(
            'click',
            clearFilters
        );

    }


    if (emptyClearButton) {

        emptyClearButton.addEventListener(
            'click',
            clearFilters
        );

    }


    /*
     * =========================================================
     * HERO BACKGROUND SLIDESHOW
     * =========================================================
     */

    const heroSlides =
        document.querySelectorAll(
            '.to-hero-background-slide'
        );

    let heroSlideIndex = 0;


    if (heroSlides.length > 1) {

        setInterval(function () {

            heroSlides[heroSlideIndex]
                .classList.remove('active');


            heroSlideIndex =
                (heroSlideIndex + 1) %
                heroSlides.length;


            heroSlides[heroSlideIndex]
                .classList.add('active');

        }, 6000);

    }


    /*
     * =========================================================
     * OPEN BOOKING LIST
     * =========================================================
     */

    function openBookingList() {

        if (
            getRoomCartCount() === 0
        ) {

            alert(
                'Your Booking List is empty. Please select at least one room first.'
            );

            return;

        }


        window.location.href =
            cartIndexUrl;

    }


    const heroCartButton =
        document.getElementById(
            'hero-cart-button'
        );


    const teaserCartButton =
        document.getElementById(
            'teaser-cart-button'
        );


    if (heroCartButton) {

        heroCartButton.addEventListener(
            'click',
            openBookingList
        );

    }


    if (teaserCartButton) {

        teaserCartButton.addEventListener(
            'click',
            openBookingList
        );

    }


    /*
     * =========================================================
     * INITIALIZE
     * =========================================================
     */

    updateCartVisuals();

    updateFilterResults();

});

</script>

@endsection