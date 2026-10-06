<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'GITC Reservation')
    </title>

  <style>
    :root {
        --gitc-navy: #0f2747;
        --gitc-navy-dark: #091b33;
        --gitc-blue: #1e5aa8;
        --gitc-blue-light: #eaf2fb;
        --gitc-teal: #0ea5a4;

        --gitc-bg: #f4f7fa;
        --gitc-card: #ffffff;
        --gitc-border: #e2e8f0;

        --gitc-text: #172033;
        --gitc-muted: #64748b;

        --gitc-success: #15803d;
        --gitc-success-bg: #dcfce7;
    }

    * {
        box-sizing: border-box;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        margin: 0;
        min-height: 100vh;

        background: var(--gitc-bg);
        color: var(--gitc-text);

        font-family: 'Garuda Sans', sans-serif;

        -webkit-font-smoothing: antialiased;
        text-rendering: optimizeLegibility;
    }

    a {
        color: inherit;
        text-decoration: none;
    }

    button,
    input,
    select,
    textarea {
        font-family: 'Garuda Sans', sans-serif;
    }


    /* =========================================================
       TOP NAVIGATION
    ========================================================== */

    .to-navbar {
        position: sticky;
        top: 0;
        z-index: 100;

        height: 82px;

        background: rgba(255, 255, 255, 0.97);

        border-bottom: 1px solid var(--gitc-border);

        backdrop-filter: blur(12px);
    }

    .to-navbar-inner {
        width: min(1440px, calc(100% - 56px));

        height: 100%;

        margin: 0 auto;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 22px;
    }


    /* =========================================================
       BRAND / LOGO
    ========================================================== */

    .to-brand {
        display: flex;
        align-items: center;

        gap: 0;

        min-width: 0;

        flex-shrink: 0;
    }

    .to-brand-mark {
        width: 280px;
        height: 62px;

        display: flex;
        align-items: center;
        justify-content: flex-start;

        flex-shrink: 0;

        overflow: hidden;

        margin: 0;
        padding: 0;
    }

    .to-brand-mark img {
        display: block;

        width: 100%;
        height: 100%;

        object-fit: contain;
        object-position: left center;

        margin: 0;
        padding: 0;
    }

    .to-brand-text {
        display: flex;
        flex-direction: column;

        line-height: 1.15;

        margin: 0 0 0 -145px;
        padding: 0;
    }

    .to-brand-title {
        color: var(--gitc-navy);

        font-family: 'Garuda Serif', serif;

        font-size: 21px;
        font-weight: 700;

        letter-spacing: -0.02em;

        white-space: nowrap;
    }

    .to-brand-subtitle {
        margin-top: 3px;

        color: var(--gitc-muted);

        font-family: 'Garuda Sans', sans-serif;

        font-size: 11px;
        font-weight: 600;

        letter-spacing: -0.005em;

        white-space: nowrap;
    }


    /* =========================================================
       NAV RIGHT
    ========================================================== */

    .to-nav-actions {
        display: flex;
        align-items: center;

        gap: 9px;

        flex-shrink: 0;
    }


    /* =========================================================
       USER
    ========================================================== */

    .to-user {
        display: flex;
        align-items: center;

        gap: 9px;

        padding-right: 3px;
    }

    .to-user-avatar {
        width: 48px;
        height: 48px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 50%;

        background: var(--gitc-blue-light);

        color: var(--gitc-blue);

        font-family: 'Garuda Sans', sans-serif;

        font-size: 18px;
        font-weight: 800;

        line-height: 1;
    }

    .to-user-info {
        display: flex;
        flex-direction: column;

        line-height: 1.2;
    }

    .to-user-name {
        color: var(--gitc-text);

        font-family: 'Garuda Sans', sans-serif;

        font-size: 14px;
        font-weight: 700;

        white-space: nowrap;
    }

    .to-user-role {
        margin-top: 3px;

        color: var(--gitc-muted);

        font-family: 'Garuda Sans', sans-serif;

        font-size: 12px;
        font-weight: 600;

        white-space: nowrap;
    }


    /* =========================================================
       MY RESERVATIONS
    ========================================================== */

    .to-my-reservations {
        min-height: 50px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 6px;

        padding: 0 16px;

        border: 1px solid #cbd5e1;
        border-radius: 9px;

        background: #ffffff;

        color: var(--gitc-navy);

        font-family: 'Garuda Sans', sans-serif;

        font-size: 14px;
        font-weight: 700;

        white-space: nowrap;

        transition:
            background 0.18s ease,
            border-color 0.18s ease,
            transform 0.18s ease,
            box-shadow 0.18s ease;
    }

    .to-my-reservations:hover {
        background: #f8fafc;

        border-color: #94a3b8;

        transform: translateY(-1px);

        box-shadow:
            0 5px 14px rgba(15, 39, 71, 0.08);
    }

    .to-my-reservations-dot {
        width: 7px;
        height: 7px;

        flex: 0 0 7px;

        border-radius: 50%;

        background: #ef4444;
    }


    /* =========================================================
       CART / BOOKING LIST
    ========================================================== */

    .to-cart-button {
        min-height: 50px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 8px;

        padding: 0 16px;

        border: 1px solid #cbd5e1;
        border-radius: 9px;

        background: #ffffff;

        color: var(--gitc-navy);

        font-family: 'Garuda Sans', sans-serif;

        font-size: 14px;
        font-weight: 700;

        white-space: nowrap;

        transition:
            background 0.18s ease,
            border-color 0.18s ease,
            transform 0.18s ease,
            box-shadow 0.18s ease;
    }

    .to-cart-button:hover {
        background: #f8fafc;

        border-color: #94a3b8;

        transform: translateY(-1px);

        box-shadow:
            0 5px 14px rgba(15, 39, 71, 0.08);
    }


    /* =========================================================
       BOOKING LIST ICON
    ========================================================== */

    .to-cart-icon {
        width: 18px;
        height: 18px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;
    }

    .to-cart-icon img {
        display: block;

        width: 100%;
        height: 100%;

        object-fit: contain;
    }


    /* =========================================================
       BOOKING LIST COUNT
    ========================================================== */

    .to-cart-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-width: auto;

        padding: 0;

        color: var(--gitc-navy);

        font-family: 'Garuda Sans', sans-serif;

        font-size: 14px;
        font-weight: 800;

        line-height: 1;
    }


    /* =========================================================
       LOGOUT
    ========================================================== */

    .to-logout {
        min-height: 50px;

        padding: 0 12px;

        border: 0;
        border-radius: 9px;

        background: transparent;

        color: #111111;

        font-family: 'Garuda Sans', sans-serif;

        font-size: 14px;
        font-weight: 900;

        cursor: pointer;

        white-space: nowrap;

        transition:
            color 0.18s ease,
            background 0.18s ease;
    }

    .to-logout:hover {
        background: #f1f5f9;

        color: #000000;
    }


    /* =========================================================
       MAIN
    ========================================================== */

    .to-main {
        min-height: calc(100vh - 82px);
    }

    .to-container {
        width: min(1440px, calc(100% - 56px));

        margin: 0 auto;

        padding: 34px 0 64px;
    }


    /* =========================================================
       FOOTER
    ========================================================== */

    .to-footer {
        border-top: 1px solid var(--gitc-border);

        background: white;

        font-family: 'Garuda Sans', sans-serif;
    }

    .to-footer-inner {
        width: min(1440px, calc(100% - 56px));

        min-height: 72px;

        margin: 0 auto;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;
    }

    .to-footer-text {
        color: var(--gitc-muted);

        font-family: 'Garuda Sans', sans-serif;

        font-size: 12px;
        font-weight: 500;
    }

    .to-footer-accent {
        display: inline-flex;
        align-items: center;

        gap: 6px;

        color: var(--gitc-navy);

        font-family: 'Garuda Sans', sans-serif;

        font-size: 12px;
        font-weight: 700;
    }

    .to-footer-dot {
        width: 7px;
        height: 7px;

        border-radius: 50%;

        background: var(--gitc-teal);
    }


    /* =========================================================
       RESPONSIVE — TABLET
    ========================================================== */

    @media (max-width: 1100px) {

        .to-navbar {
            height: 82px;
        }

        .to-navbar-inner,
        .to-container,
        .to-footer-inner {
            width: min(100% - 40px, 1440px);
        }

        .to-brand-mark {
            width: 240px;
            height: 56px;
        }

        .to-brand-text {
            margin-left: -125px;
        }

        .to-brand-title {
            font-size: 19px;
        }

        .to-brand-subtitle {
            font-size: 10px;
        }

        .to-user-info {
            display: none;
        }

        .to-user-avatar {
            width: 44px;
            height: 44px;

            font-size: 17px;
        }

        .to-my-reservations,
        .to-cart-button,
        .to-logout {
            font-size: 13px;
        }

        .to-main {
            min-height: calc(100vh - 82px);
        }
    }


    /* =========================================================
       RESPONSIVE — MOBILE
    ========================================================== */

    @media (max-width: 768px) {

        .to-navbar {
            height: 72px;
        }

        .to-navbar-inner,
        .to-container,
        .to-footer-inner {
            width: min(100% - 28px, 1440px);
        }

        .to-main {
            min-height: calc(100vh - 72px);
        }

        .to-container {
            padding-top: 24px;
            padding-bottom: 48px;
        }

        .to-brand {
            gap: 0;
        }

        .to-brand-mark {
            width: 190px;
            height: 48px;
        }

        .to-brand-text {
            margin-left: -95px;
        }

        .to-brand-title {
            font-size: 16px;
        }

        .to-brand-subtitle {
            display: none;
        }

        .to-user {
            display: none;
        }

        .to-logout {
            display: none;
        }

        .to-my-reservations {
            min-height: 42px;

            padding: 0 12px;

            font-size: 12px;
        }

        .to-cart-button {
            min-height: 42px;

            padding: 0 12px;

            font-size: 12px;
        }

        .to-cart-icon {
            width: 20px;
            height: 20px;
        }

        .to-footer-inner {
            min-height: 64px;
        }

        .to-footer-text {
            font-size: 11px;
        }
    }


    /* =========================================================
       RESPONSIVE — SMALL MOBILE
    ========================================================== */

    @media (max-width: 480px) {

        .to-navbar-inner,
        .to-container,
        .to-footer-inner {
            width: calc(100% - 20px);
        }

        .to-brand-mark {
            width: 150px;
            height: 42px;
        }

        .to-brand-text {
            margin-left: -75px;
        }

        .to-brand-title {
            font-size: 15px;
        }

        .to-my-reservations {
            padding: 0 10px;

            font-size: 11px;
        }

        .to-cart-button {
            padding: 0 10px;
        }

        .to-cart-label {
            display: none;
        }

        .to-cart-icon {
            width: 20px;
            height: 20px;
        }
    }
</style>

    @yield('head')

    @stack('styles')
</head>

@php
    $trainingOfficerCart = session('training_officer_cart', [
        'rooms' => [],
        'training_rooms' => [],
        'fields' => [],
    ]);

    $trainingOfficerCartCount =
        count($trainingOfficerCart['rooms'] ?? []) +
        count($trainingOfficerCart['training_rooms'] ?? []) +
        count($trainingOfficerCart['fields'] ?? []);

    $hasUnreadReservationStatus = auth()->check()
        && \App\Models\Notification::query()
            ->where('user_id', auth()->id())
            ->where('type', 'RESERVATION_STATUS_CHANGED')
            ->where('target_type', 'reservation')
            ->whereNull('read_at')
            ->exists();
@endphp

<body>

    {{-- =========================================================
        TOP NAVIGATION
    ========================================================== --}}

    <header class="to-navbar">

        <div class="to-navbar-inner">

            {{-- Brand --}}
            <a
                href="{{ route('training-officer.home') }}"
                class="to-brand"
            >

                <div class="to-brand-mark">
                <img
                    src="{{ asset('assets/icons/logo/Garuda.svg') }}"
                    alt="GITC"
                >
            </div>

                <div class="to-brand-text">

                    <span class="to-brand-title">
                        GITC Reservation
                    </span>

                    <span class="to-brand-subtitle">
                        Training System, Media & Business
                    </span>

                </div>

            </a>


            {{-- Actions --}}
            <div class="to-nav-actions">

                {{-- User --}}
                <div class="to-user">

                    <div class="to-user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div class="to-user-info">

                        <span class="to-user-name">
                            {{ auth()->user()->name }}
                        </span>

                        <span class="to-user-role">
                            Training Officer
                        </span>

                    </div>

                </div>


                {{-- My Reservations --}}
                <a
                    href="{{ route('training-officer.my-reservations.index') }}"
                    class="to-my-reservations"
                >
                    My Reservations

                    @if ($hasUnreadReservationStatus)
                        <span
                            class="to-my-reservations-dot"
                            aria-hidden="true"
                        ></span>
                    @endif
                </a>


                {{-- Reservation Cart --}}
                <a
                    href="{{ route('training-officer.cart') }}"
                    class="to-cart-button"
                    aria-label="Open reservation cart"
                    id="reservation-cart-button"
                >

                    <span class="to-cart-icon">
                    <img
                        src="{{ asset('assets/icons/navigation/Booking_list.svg') }}"
                        alt=""
                        aria-hidden="true"
                    >
                </span>

                    <span class="to-cart-label">
                        Booking List
                    </span>

                    <span
                        class="to-cart-count"
                        id="reservation-cart-count"
                    >
                        {{ $trainingOfficerCartCount }}
                    </span>

                </a>


                {{-- Logout --}}
                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="to-logout"
                    >
                        Logout
                    </button>

                </form>

            </div>

        </div>

    </header>


    {{-- =========================================================
        MAIN CONTENT
    ========================================================== --}}

    <main class="to-main">

        <div class="to-container">

            @yield('content')

        </div>

    </main>


    {{-- =========================================================
        FOOTER
    ========================================================== --}}

    <footer class="to-footer">

        <div class="to-footer-inner">

            <span class="to-footer-text">
                © {{ date('Y') }} GITC Reservation System
            </span>

            <span class="to-footer-accent">
                <span class="to-footer-dot"></span>
                Training Facility Booking
            </span>

        </div>

    </footer>


    {{-- =========================================================
        GLOBAL CART SCRIPT
    ========================================================== --}}

    <script>
        window.updateReservationCartCount = function (count) {

            const cartCount =
                document.getElementById('reservation-cart-count');

            if (!cartCount) {
                return;
            }

            cartCount.textContent = count;
        };
    </script>

    @yield('scripts')

</body>
</html>