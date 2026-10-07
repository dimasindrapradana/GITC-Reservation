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
        @yield('title', 'GITC Classrom')
    </title>

  <style>

        @font-face {
    font-family: 'Garuda Sans';
    src: url('{{ asset('fonts/Garuda_Font/Sans/GarudaSans-Regular.ttf') }}') format('truetype');
    font-weight: 400;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'Garuda Sans';
    src: url('{{ asset('fonts/Garuda_Font/Sans/GarudaSans-SemiBold.ttf') }}') format('truetype');
    font-weight: 600;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'Garuda Sans';
    src: url('{{ asset('fonts/Garuda_Font/Sans/GarudaSans-Bold.ttf') }}') format('truetype');
    font-weight: 700;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'Garuda Sans';
    src: url('{{ asset('fonts/Garuda_Font/Sans/GarudaSans-ExtraBold.ttf') }}') format('truetype');
    font-weight: 800;
    font-style: normal;
    font-display: swap;
}


@font-face {
    font-family: 'Garuda Serif';
    src: url('{{ asset('fonts/Garuda_Font/Serif/GarudaSerif-Regular.otf') }}') format('opentype');
    font-weight: 400;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'Garuda Serif';
    src: url('{{ asset('fonts/Garuda_Font/Serif/GarudaSerif-Bold.otf') }}') format('opentype');
    font-weight: 700;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'Garuda Serif';
    src: url('{{ asset('fonts/Garuda_Font/Serif/GarudaSerif-ExtraBold.otf') }}') format('opentype');
    font-weight: 800;
    font-style: normal;
    font-display: swap;
}
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
        width: 170px;
        height: 32px;


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
        margin-left: -90px;
        
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
   RESPONSIVE
========================================================= */

@media (max-width: 1000px) {

    .to-user {
        display: none;
    }

}


@media (max-width: 768px) {

    .to-navbar {
        height: 64px;
    }

    .to-navbar-inner,
    .to-container,
    .to-footer-inner {
        width: min(100% - 28px, 1440px);
    }

    .to-main {
        min-height: calc(100vh - 64px);
    }

    .to-container {
        padding-top: 24px;
        padding-bottom: 48px;
    }

    .to-brand-subtitle {
        display: none;
    }

    .to-logout {
        display: none;
    }

    .to-my-reservations,
    .to-cart-button {
        min-height: 40px;

        padding: 0 11px;

        font-size: 12px;
    }

    .to-footer-inner {
        min-height: 64px;
    }

    .to-footer-text {
        font-size: 11px;
    }

}


@media (max-width: 560px) {

    .to-navbar-inner,
    .to-container,
    .to-footer-inner {
        width: calc(100% - 20px);
    }

    .to-brand-mark {
        width: 36px;
        height: 36px;

        border-radius: 9px;
    }

    .to-brand-title {
        font-size: 14px;
    }

    .to-brand-text {
        margin-left: -20px;
    }

    .to-nav-actions {
        gap: 6px;
    }

    .to-my-reservations,
    .to-cart-button {
        padding: 0 9px;

        font-size: 11px;
    }

    .to-cart-label {
        display: none;
    }

}


/* =========================================================
   RESPONSIVE — SMALL TABLET
========================================================= */

@media (max-width: 850px) {

    .to-navbar {
        height: auto;
        min-height: 76px;
    }

    .to-navbar-inner {
        width: calc(100% - 32px);

        min-height: 76px;

        gap: 14px;

        padding: 10px 0;
    }

    .to-brand {
        min-width: 0;
        flex: 1 1 auto;
    }

    .to-brand-mark {
        width: 135px;
        height: 38px;
    }

    .to-brand-text {
        margin-left: -72px;
    }

    .to-brand-title {
        font-size: 17px;
    }

    .to-brand-subtitle {
        display: none;
    }

    .to-nav-actions {
        gap: 6px;
        flex-shrink: 0;
    }

    .to-user-avatar {
        width: 40px;
        height: 40px;
        font-size: 15px;
    }

    .to-my-reservations,
    .to-cart-button {
        min-height: 42px;
        padding: 0 11px;
        border-radius: 8px;
        font-size: 12px;
    }

    .to-cart-icon {
        width: 17px;
        height: 17px;
    }

    .to-cart-count {
        font-size: 12px;
    }

    .to-logout {
        min-height: 42px;
        padding: 0 9px;
        font-size: 12px;
    }

    .to-container,
    .to-footer-inner {
        width: calc(100% - 32px);
    }

    .to-container {
        padding: 28px 0 48px;
    }
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1000px) {

    .to-user {
        display: none;
    }

}


@media (max-width: 768px) {

    .to-navbar {
        height: 64px;
    }

    .to-navbar-inner,
    .to-container,
    .to-footer-inner {
        width: min(100% - 28px, 1440px);
    }

    .to-main {
        min-height: calc(100vh - 64px);
    }

    .to-container {
        padding-top: 24px;
        padding-bottom: 48px;
    }

    .to-brand-subtitle {
        display: none;
    }

    /*
     * Keep Logout visible on TRO
     */
    .to-logout {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-height: 40px;

        padding: 0 10px;

        font-size: 12px;
    }

    .to-my-reservations,
    .to-cart-button {
        min-height: 40px;

        padding: 0 11px;

        font-size: 12px;
    }

    .to-footer-inner {
        min-height: 64px;
    }

    .to-footer-text {
        font-size: 11px;
    }

}


@media (max-width: 560px) {

    .to-navbar-inner,
    .to-container,
    .to-footer-inner {
        width: calc(100% - 20px);
    }


    /* =========================
       BRAND
    ========================= */

    .to-brand {
        display: flex;

        align-items: center;

        flex: 1 1 auto;

        min-width: 0;

        overflow: hidden;
    }

    .to-brand-mark {
        width: 105px;
        height: 30px;

        flex: 0 0 105px;

        border-radius: 0;
    }

    .to-brand-text {
        margin-left: -26px;

        min-width: 0;
    }

    .to-brand-title {
        font-size: 15px;

        white-space: nowrap;
         margin-left: 20px;
    }

    .to-brand-subtitle {
        display: none;
    }


    /* =========================
       USER
    ========================= */

    .to-user {
        display: none !important;
    }


    /* =========================
       NAV ACTIONS
    ========================= */

    .to-navbar-inner {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 9px;
    }

    .to-nav-actions {
        display: flex;

        align-items: center;

        justify-content: flex-end;

        flex: 0 0 auto;

        width: auto;

        gap: 5px;

        min-width: 0;
    }


    /* =========================
       MY RESERVATIONS
    ========================= */

    .to-my-reservations {
        min-height: 40px;

        padding: 0 8px;

        border-radius: 8px;

        font-size: 10px;

        white-space: nowrap;
    }


    /* =========================
       BOOKING LIST
    ========================= */

    .to-cart-button {
        min-height: 40px;

        width: 40px;

        padding: 0;

        gap: 0;

        border-radius: 8px;

        flex: 0 0 40px;
    }

    .to-cart-icon {
        width: 15px;
        height: 15px;
    }

    .to-cart-label {
        display: none;
    }

    .to-cart-count {
        font-size: 10px;
    }


    /* =========================
       LOGOUT
    ========================= */

    .to-logout {
        min-height: 40px;

        padding: 0 7px;

        font-size: 10px;

        white-space: nowrap;
    }

}


@media (max-width: 430px) {

    .to-navbar-inner {
        width: calc(100% - 20px);

        gap: 7px;
    }

    .to-brand-mark {
        width: 90px;
        height: 28px;

        flex-basis: 90px;
    }

    .to-brand-text {
        margin-left: -48px;
    }

    .to-brand-title {
        font-size: 14px;
    }

    .to-nav-actions {
        gap: 4px;
    }

    .to-my-reservations {
        min-height: 38px;

        padding: 0 6px;

        font-size: 9px;
    }

    .to-cart-button {
        width: 38px;
        min-height: 38px;

        flex-basis: 38px;
    }

    .to-cart-icon {
        width: 14px;
        height: 14px;
    }

    .to-cart-count {
        font-size: 9px;
    }

    .to-logout {
        min-height: 38px;

        padding: 0 6px;

        font-size: 9px;
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
                        GITC Classroom
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
                © {{ date('Y') }} Garuda Indonesia Training Center
            </span>

            {{-- <span class="to-footer-accent">
                <span class="to-footer-dot"></span>
                Training Facility Booking
            </span> --}}

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