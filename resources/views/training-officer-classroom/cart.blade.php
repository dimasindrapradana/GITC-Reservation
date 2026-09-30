@extends('layouts.training-officer-classroom')

@section('title', 'Classroom Booking List')

@section('head')
<style>
    /* =========================================================
       CLASSROOM BOOKING LIST
    ========================================================= */

    .cart-page {
        width: 100%;
    }

    .cart-header {
        margin-bottom: 28px;
    }

    .cart-eyebrow {
        margin: 0 0 7px;

        color: var(--gitc-teal);

        font-size: 11px;
        font-weight: 800;

        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .cart-header-row {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;

        gap: 24px;
    }

    .cart-title {
        margin: 0;

        color: var(--gitc-navy);

        font-size: 30px;
        font-weight: 800;

        letter-spacing: -0.025em;
    }

    .cart-description {
        margin: 8px 0 0;

        color: var(--gitc-muted);

        font-size: 14px;
        line-height: 1.6;
    }

    .cart-browse-button {
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

    .cart-browse-button:hover {
        border-color: #b8cbe0;
        background: #f8fbff;
        color: var(--gitc-blue);

        transform: translateY(-1px);
    }


    /* =========================================================
       FLASH / AJAX MESSAGE
    ========================================================= */

    .cart-alert {
        margin-bottom: 22px;

        padding: 13px 16px;

        border: 1px solid #b7dfc5;
        border-radius: 10px;

        background: #f0faf3;

        color: var(--gitc-success);

        font-size: 13px;
        font-weight: 600;
    }

    .cart-error {
        margin-bottom: 22px;

        padding: 13px 16px;

        border: 1px solid #f0caca;
        border-radius: 10px;

        background: #fff5f5;

        color: #b42323;

        font-size: 13px;
        font-weight: 600;
    }


    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .cart-empty {
        padding: 64px 24px;

        border: 1px solid var(--gitc-border);
        border-radius: 16px;

        background: var(--gitc-card);

        text-align: center;

        box-shadow:
            0 8px 24px rgba(15, 39, 71, 0.04);
    }

    .cart-empty-icon {
        width: 64px;
        height: 64px;

        margin: 0 auto;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 16px;

        background: var(--gitc-blue-light);

        color: var(--gitc-blue);
    }

    .cart-empty-icon svg {
        width: 30px;
        height: 30px;
    }

    .cart-empty-title {
        margin: 20px 0 0;

        color: var(--gitc-navy);

        font-size: 20px;
        font-weight: 800;
    }

    .cart-empty-text {
        max-width: 480px;

        margin: 8px auto 0;

        color: var(--gitc-muted);

        font-size: 13px;
        line-height: 1.7;
    }

    .cart-primary-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-height: 44px;

        margin-top: 22px;

        padding: 0 18px;

        border: 0;
        border-radius: 9px;

        background: var(--gitc-navy);

        color: white;

        font-size: 13px;
        font-weight: 700;

        text-decoration: none;

        transition:
            background 0.18s ease,
            transform 0.18s ease,
            box-shadow 0.18s ease;
    }

    .cart-primary-button:hover {
        background: var(--gitc-navy-dark);

        transform: translateY(-1px);

        box-shadow:
            0 7px 16px rgba(15, 39, 71, 0.14);
    }


    /* =========================================================
       SUMMARY
    ========================================================= */

    .cart-summary {
        display: grid;

        grid-template-columns: repeat(2, minmax(0, 1fr));

        gap: 14px;

        margin-bottom: 22px;
    }

    .cart-summary-card {
        position: relative;

        overflow: hidden;

        padding: 18px 20px;

        border: 1px solid var(--gitc-border);
        border-radius: 13px;

        background: white;

        box-shadow:
            0 5px 16px rgba(15, 39, 71, 0.035);
    }

    .cart-summary-card::before {
        content: "";

        position: absolute;

        top: 0;
        left: 0;

        width: 100%;
        height: 3px;

        background: var(--gitc-teal);
    }

    .cart-summary-label {
        margin: 0;

        color: var(--gitc-muted);

        font-size: 12px;
        font-weight: 600;
    }

    .cart-summary-value {
        margin: 7px 0 0;

        color: var(--gitc-navy);

        font-size: 25px;
        font-weight: 800;

        line-height: 1;
    }


    /* =========================================================
       RESOURCE SECTION
    ========================================================= */

    .cart-sections {
        display: flex;
        flex-direction: column;

        gap: 18px;
    }

    .cart-section {
        overflow: hidden;

        border: 1px solid var(--gitc-border);
        border-radius: 14px;

        background: white;

        box-shadow:
            0 6px 18px rgba(15, 39, 71, 0.035);
    }

    .cart-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 16px;

        padding: 18px 20px;

        border-bottom: 1px solid var(--gitc-border);

        background:
            linear-gradient(
                90deg,
                #f8fbff 0%,
                #ffffff 70%
            );
    }

    .cart-section-heading {
        display: flex;
        align-items: center;

        gap: 10px;
    }

    .cart-section-marker {
        width: 8px;
        height: 28px;

        border-radius: 4px;

        background: var(--gitc-teal);
    }

    .cart-section-title {
        margin: 0;

        color: var(--gitc-navy);

        font-size: 16px;
        font-weight: 800;
    }

    .cart-section-count {
        margin: 3px 0 0;

        color: var(--gitc-muted);

        font-size: 12px;
    }


    /* =========================================================
       RESOURCE ITEM
    ========================================================= */

    .cart-resource {
        display: flex;
        align-items: center;

        gap: 18px;

        padding: 17px 20px;

        transition:
            background 0.18s ease,
            opacity 0.18s ease;
    }

    .cart-resource:hover {
        background: #fbfdff;
    }

    .cart-resource.is-removing {
        opacity: 0.45;

        pointer-events: none;
    }

    .cart-resource-image {
        width: 112px;
        height: 78px;

        flex-shrink: 0;

        overflow: hidden;

        border-radius: 10px;

        background: #eef3f7;

        border: 1px solid #e4eaf0;
    }

    .cart-resource-image img {
        width: 100%;
        height: 100%;

        display: block;

        object-fit: cover;
    }

    .cart-no-image {
        width: 100%;
        height: 100%;

        display: flex;
        align-items: center;
        justify-content: center;

        color: #94a3b8;

        font-size: 11px;
        font-weight: 600;
    }

    .cart-resource-info {
        min-width: 0;

        flex: 1;
    }

    .cart-resource-title-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;

        gap: 8px;
    }

    .cart-resource-name {
        margin: 0;

        color: var(--gitc-navy);

        font-size: 14px;
        font-weight: 800;
    }

    .cart-resource-type {
        display: inline-flex;
        align-items: center;

        min-height: 23px;

        padding: 0 8px;

        border-radius: 999px;

        background: var(--gitc-blue-light);

        color: var(--gitc-blue);

        font-size: 10px;
        font-weight: 800;

        letter-spacing: 0.02em;
    }

    .cart-resource-location {
        margin: 5px 0 0;

        color: var(--gitc-muted);

        font-size: 12px;
    }

    .cart-resource-capacity {
        margin: 7px 0 0;

        color: #64748b;

        font-size: 12px;
    }

    .cart-resource-capacity strong {
        color: var(--gitc-text);

        font-weight: 700;
    }


    /* =========================================================
       REMOVE BUTTON
    ========================================================= */

    .cart-remove-form {
        flex-shrink: 0;
    }

    .cart-remove-button {
        min-height: 38px;

        padding: 0 13px;

        border: 1px solid #f0caca;
        border-radius: 8px;

        background: white;

        color: #b42323;

        font-size: 12px;
        font-weight: 700;

        cursor: pointer;

        transition:
            background 0.18s ease,
            border-color 0.18s ease,
            color 0.18s ease,
            opacity 0.18s ease;
    }

    .cart-remove-button:hover:not(:disabled) {
        border-color: #e8aaaa;

        background: #fff5f5;

        color: #991b1b;
    }

    .cart-remove-button:disabled {
        cursor: wait;

        opacity: 0.55;
    }


    /* =========================================================
       CONTINUE PANEL
    ========================================================= */

    .cart-continue {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 24px;

        margin-top: 22px;

        padding: 18px 20px;

        border: 1px solid #c9dceb;
        border-radius: 14px;

        background:
            linear-gradient(
                100deg,
                #eef6fc 0%,
                #f8fbfd 100%
            );
    }

    .cart-continue-title {
        margin: 0;

        color: var(--gitc-navy);

        font-size: 14px;
        font-weight: 800;
    }

    .cart-continue-text {
        margin: 4px 0 0;

        color: var(--gitc-muted);

        font-size: 12px;
        line-height: 1.5;
    }

    .cart-continue-button {
        flex-shrink: 0;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-height: 42px;

        padding: 0 17px;

        border-radius: 9px;

        background: var(--gitc-navy);

        color: white;

        font-size: 12px;
        font-weight: 700;

        text-decoration: none;

        transition:
            background 0.18s ease,
            transform 0.18s ease,
            box-shadow 0.18s ease;
    }

    .cart-continue-button:hover {
        background: var(--gitc-navy-dark);

        transform: translateY(-1px);

        box-shadow:
            0 7px 16px rgba(15, 39, 71, 0.13);
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 820px) {

        .cart-header-row {
            align-items: flex-start;

            flex-direction: column;
        }

        .cart-summary {
            grid-template-columns: 1fr;
        }

        .cart-resource {
            align-items: flex-start;
        }

        .cart-resource-image {
            width: 100px;
            height: 72px;
        }

        .cart-continue {
            align-items: flex-start;

            flex-direction: column;
        }

        .cart-continue-button {
            width: 100%;
        }
    }

    @media (max-width: 560px) {

        .cart-title {
            font-size: 25px;
        }

        .cart-description {
            font-size: 13px;
        }

        .cart-browse-button {
            width: 100%;
        }

        .cart-resource {
            align-items: stretch;

            flex-direction: column;
        }

        .cart-resource-image {
            width: 100%;
            height: 150px;
        }

        .cart-remove-form {
            width: 100%;
        }

        .cart-remove-button {
            width: 100%;
        }

        .cart-section-header {
            padding: 16px;
        }

        .cart-resource {
            padding: 16px;
        }
    }
</style>
@endsection


@section('content')

<div
    class="cart-page"
    id="classroom-cart-page"
    data-remove-url="{{ route('training-officer.classroom.cart.remove') }}"
>

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="cart-header">

        <p class="cart-eyebrow">
            GITC Classroom Reservation
        </p>

        <div class="cart-header-row">

            <div>

                <h1 class="cart-title">
                    Classroom Booking List
                </h1>

                <p class="cart-description">
                    Review the classrooms you want to reserve before continuing.
                </p>

            </div>

            <a
                href="{{ route('training-officer.classroom.home') }}"
                class="cart-browse-button"
            >
                Browse Classrooms
            </a>

        </div>

    </div>


    {{-- =====================================================
         AJAX MESSAGE
    ====================================================== --}}

    <div
        id="cart-message"
        class="cart-alert"
        style="display: none;"
        role="alert"
    ></div>


    @php
        $totalItems = $rooms->count();
    @endphp


    {{-- =====================================================
         EMPTY CART
    ====================================================== --}}

    @if ($totalItems === 0)

        <div class="cart-empty">

            <div class="cart-empty-icon">

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
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1 5h12M9 21h.01M18 21h.01"
                    />
                </svg>

            </div>

            <h2 class="cart-empty-title">
                Your classroom booking list is empty
            </h2>

            <p class="cart-empty-text">
                Browse the available classrooms and add the rooms
                you need for your reservation.
            </p>

            <a
                href="{{ route('training-officer.classroom.home') }}"
                class="cart-primary-button"
            >
                Browse Available Classrooms
            </a>

        </div>


    {{-- =====================================================
         CART WITH ITEMS
    ====================================================== --}}

    @else

        {{-- =================================================
             SUMMARY
        ================================================== --}}

        <div class="cart-summary">

            <div class="cart-summary-card">

                <p class="cart-summary-label">
                    Total Classrooms
                </p>

                <p
                    class="cart-summary-value"
                    id="cart-total-count"
                >
                    {{ $totalItems }}
                </p>

            </div>


            <div class="cart-summary-card">

                <p class="cart-summary-label">
                    Selected Rooms
                </p>

                <p
                    class="cart-summary-value"
                    id="cart-room-count"
                >
                    {{ $rooms->count() }}
                </p>

            </div>

        </div>


        {{-- =================================================
             ROOMS
        ================================================== --}}

        <div class="cart-sections">

            <section class="cart-section">

                <div class="cart-section-header">

                    <div class="cart-section-heading">

                        <span class="cart-section-marker"></span>

                        <div>

                            <h2 class="cart-section-title">
                                Classrooms
                            </h2>

                            <p
                                class="cart-section-count"
                                id="cart-section-count"
                            >
                                {{ $rooms->count() }}
                                classroom(s) selected
                            </p>

                        </div>

                    </div>

                </div>


                <div id="cart-room-list">

                    @foreach ($rooms as $room)

                        <div
                            class="cart-resource"
                            data-cart-item
                            data-id="{{ $room->id }}"
                            data-type="room"
                        >

                            <div class="cart-resource-image">

                                @if ($room->images->first())

                                    <img
                                        src="{{ asset('storage/' . $room->images->first()->file) }}"
                                        alt="{{ $room->name }}"
                                    >

                                @else

                                    <div class="cart-no-image">
                                        No image
                                    </div>

                                @endif

                            </div>


                            <div class="cart-resource-info">

                                <div class="cart-resource-title-row">

                                    <h3 class="cart-resource-name">
                                        {{ $room->name }}
                                    </h3>

                                    <span class="cart-resource-type">
                                        Classroom
                                    </span>

                                </div>


                                <p class="cart-resource-location">
                                    {{ $room->building?->name ?? 'Building' }}
                                </p>


                                <p class="cart-resource-capacity">

                                    Capacity:

                                    <strong>
                                        {{ $room->capacity }}
                                    </strong>

                                </p>

                            </div>


                            {{-- =================================
                                 REMOVE
                            ================================== --}}

                            <div class="cart-remove-form">

                                <button
                                    type="button"
                                    class="cart-remove-button"
                                    data-remove-room
                                    data-id="{{ $room->id }}"
                                    data-type="room"
                                >
                                    Remove
                                </button>

                            </div>

                        </div>

                    @endforeach

                </div>

            </section>

        </div>


        {{-- =================================================
             CONTINUE
        ================================================== --}}

        <div
            class="cart-continue"
            id="cart-continue-panel"
        >

            <div>

                <p class="cart-continue-title">
                    Ready to make your classroom reservation?
                </p>

                <p class="cart-continue-text">
                    Continue to provide your event and reservation details.
                </p>

            </div>

            <a
                href="{{ route('training-officer.classroom.reservation.create') }}"
                class="cart-continue-button"
            >
                Continue to Reservation
            </a>

        </div>

    @endif

</div>


{{-- =========================================================
     REMOVE SCRIPT
========================================================= --}}

<script>
(function () {

    'use strict';


    /*
     * Prevent the script from being initialized twice.
     */
    if (window.__trainingOfficerClassroomCartInitialized) {
        return;
    }

    window.__trainingOfficerClassroomCartInitialized = true;


    /*
     * Main cart element.
     */
    const cartPage = document.getElementById(
        'classroom-cart-page'
    );

    if (!cartPage) {
        return;
    }


    /*
     * Remove endpoint.
     */
    const removeUrl = cartPage.dataset.removeUrl;


    /*
     * CSRF token.
     */
    const csrfTokenElement = document.querySelector(
        'meta[name="csrf-token"]'
    );

    const csrfToken = csrfTokenElement
        ? csrfTokenElement.getAttribute('content')
        : null;


    /*
     * Message element.
     */
    const messageElement = document.getElementById(
        'cart-message'
    );


    /*
     * Show message.
     */
    function showMessage(
        message,
        type = 'success'
    ) {

        if (!messageElement) {
            return;
        }


        messageElement.textContent = message;

        messageElement.style.display = 'block';


        /*
         * Success styling.
         */
        if (type === 'success') {

            messageElement.className = 'cart-alert';

        }


        /*
         * Error styling.
         */
        if (type === 'error') {

            messageElement.className = 'cart-error';

        }


        /*
         * Scroll to message.
         */
        messageElement.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest'
        });

    }


    /*
     * Update counters after successful removal.
     */
    function updateCounters() {

        const items = document.querySelectorAll(
            '[data-cart-item]'
        );

        const total = items.length;


        const totalElement = document.getElementById(
            'cart-total-count'
        );

        const roomCountElement = document.getElementById(
            'cart-room-count'
        );

        const sectionCountElement = document.getElementById(
            'cart-section-count'
        );


        if (totalElement) {

            totalElement.textContent = total;

        }


        if (roomCountElement) {

            roomCountElement.textContent = total;

        }


        if (sectionCountElement) {

            sectionCountElement.textContent =
                total + ' classroom(s) selected';

        }

    }


    /*
     * Show empty state when the last room has been removed.
     */
    function showEmptyState() {

        const roomList = document.getElementById(
            'cart-room-list'
        );

        const cartSections = document.querySelector(
            '.cart-sections'
        );

        const summary = document.querySelector(
            '.cart-summary'
        );

        const continuePanel = document.getElementById(
            'cart-continue-panel'
        );


        if (roomList) {

            roomList.innerHTML = '';

        }


        if (cartSections) {

            cartSections.style.display = 'none';

        }


        if (summary) {

            summary.style.display = 'none';

        }


        if (continuePanel) {

            continuePanel.style.display = 'none';

        }


        /*
         * Avoid creating the empty state more than once.
         */
        if (document.getElementById('cart-empty-state-js')) {
            return;
        }


        const emptyState = document.createElement('div');

        emptyState.id = 'cart-empty-state-js';

        emptyState.className = 'cart-empty';


        emptyState.innerHTML = `
            <div class="cart-empty-icon">

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
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1 5h12M9 21h.01M18 21h.01"
                    />
                </svg>

            </div>

            <h2 class="cart-empty-title">
                Your classroom booking list is empty
            </h2>

            <p class="cart-empty-text">
                Browse the available classrooms and add the rooms
                you need for your reservation.
            </p>

            <a
                href="{{ route('training-officer.classroom.home') }}"
                class="cart-primary-button"
            >
                Browse Available Classrooms
            </a>
        `;


        cartPage.appendChild(emptyState);

    }


    /*
     * Remove room from cart.
     */
    async function removeRoom(button) {

        if (!button) {
            return;
        }


        const roomId = Number(
            button.dataset.id
        );

        const type = button.dataset.type;


        /*
         * Validate ID.
         */
        if (!Number.isInteger(roomId) || roomId <= 0) {

            showMessage(
                'Unable to remove this room because the room ID is invalid.',
                'error'
            );

            return;

        }


        /*
         * Validate type.
         */
        if (type !== 'room') {

            showMessage(
                'Unable to remove this resource because the resource type is invalid.',
                'error'
            );

            return;

        }


        /*
         * Check CSRF.
         */
        if (!csrfToken) {

            showMessage(
                'Unable to remove the room because the CSRF token is missing.',
                'error'
            );

            return;

        }


        /*
         * Find parent cart item.
         */
        const cartItem = button.closest(
            '[data-cart-item]'
        );


        /*
         * Disable button while processing.
         */
        button.disabled = true;

        button.textContent = 'Removing...';


        if (cartItem) {

            cartItem.classList.add(
                'is-removing'
            );

        }


        try {

            const response = await fetch(
                removeUrl,
                {
                    method: 'DELETE',

                    headers: {
                        'Accept': 'application/json',

                        'Content-Type':
                            'application/json',

                        'X-CSRF-TOKEN':
                            csrfToken,

                        'X-Requested-With':
                            'XMLHttpRequest'
                    },

                    body: JSON.stringify({
                        id: roomId,
                        type: type
                    })
                }
            );


            /*
             * Try to decode JSON response.
             */
            let data = null;

            try {

                data = await response.json();

            } catch (jsonError) {

                data = null;

            }


            /*
             * HTTP error.
             */
            if (!response.ok) {

                let errorMessage =
                    'Unable to remove the room.';

                if (
                    data &&
                    data.message
                ) {

                    errorMessage = data.message;

                }


                throw new Error(
                    errorMessage
                );

            }


            /*
             * Backend returned unsuccessful response.
             */
            if (
                data &&
                data.success === false
            ) {

                throw new Error(
                    data.message ||
                    'Unable to remove the room.'
                );

            }


            /*
             * Remove item from DOM.
             */
            if (cartItem) {

                cartItem.remove();

            }


            /*
             * Update counters.
             */
            updateCounters();


            /*
             * Check if cart is now empty.
             */
            const remainingItems =
                document.querySelectorAll(
                    '[data-cart-item]'
                );


            if (remainingItems.length === 0) {

                showEmptyState();

            }


            /*
             * Success message.
             */
            showMessage(
                data && data.message
                    ? data.message
                    : 'Room removed from your booking list.',
                'success'
            );

        } catch (error) {

            /*
             * Restore UI if request failed.
             */
            if (cartItem) {

                cartItem.classList.remove(
                    'is-removing'
                );

            }


            button.disabled = false;

            button.textContent = 'Remove';


            showMessage(
                error.message ||
                'Unable to remove the room.',
                'error'
            );

        }

    }


    /*
     * Event delegation.
     *
     * This is intentionally attached to the cart page
     * instead of individual buttons.
     */
    cartPage.addEventListener(
        'click',
        function (event) {

            const button =
                event.target.closest(
                    '[data-remove-room]'
                );


            if (!button) {
                return;
            }


            event.preventDefault();


            removeRoom(button);

        }
    );

})();
</script>

@endsection