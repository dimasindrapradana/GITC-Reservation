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
        @yield('title', 'GITC Classroom Reservation')
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

            --gitc-warning: #b45309;
            --gitc-warning-bg: #fef3c7;

            --gitc-danger: #b91c1c;
            --gitc-danger-bg: #fee2e2;
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

            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;

            -webkit-font-smoothing: antialiased;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }


        /* =========================================================
           TOP NAVIGATION
        ========================================================== */

        .toc-navbar {
            position: sticky;
            top: 0;
            z-index: 100;

            height: 72px;

            background: rgba(255, 255, 255, 0.97);

            border-bottom: 1px solid var(--gitc-border);

            backdrop-filter: blur(12px);
        }

        .toc-navbar-inner {
            width: min(1440px, calc(100% - 48px));

            height: 100%;

            margin: 0 auto;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 24px;
        }


        /* =========================================================
           BRAND
        ========================================================== */

        .toc-brand {
            display: flex;
            align-items: center;

            gap: 12px;

            min-width: 0;
        }

        .toc-brand-mark {
            width: 40px;
            height: 40px;

            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;

            border-radius: 10px;

            background: var(--gitc-navy);

            color: white;

            font-size: 15px;
            font-weight: 800;

            letter-spacing: -0.03em;

            box-shadow:
                0 5px 12px rgba(15, 39, 71, 0.16);
        }

        .toc-brand-text {
            display: flex;
            flex-direction: column;

            line-height: 1.15;
        }

        .toc-brand-title {
            color: var(--gitc-navy);

            font-size: 15px;
            font-weight: 800;

            letter-spacing: -0.01em;
        }

        .toc-brand-subtitle {
            margin-top: 3px;

            color: var(--gitc-muted);

            font-size: 11px;
            font-weight: 500;
        }


        /* =========================================================
           NAVIGATION ACTIONS
        ========================================================== */

        .toc-nav-actions {
            display: flex;
            align-items: center;

            gap: 10px;
        }


        /* =========================================================
           USER
        ========================================================== */

        .toc-user {
            display: flex;
            align-items: center;

            gap: 10px;

            padding-right: 4px;
        }

        .toc-user-avatar {
            width: 36px;
            height: 36px;

            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;

            border-radius: 50%;

            background: var(--gitc-blue-light);

            color: var(--gitc-blue);

            font-size: 13px;
            font-weight: 800;
        }

        .toc-user-info {
            display: flex;
            flex-direction: column;

            line-height: 1.2;
        }

        .toc-user-name {
            color: var(--gitc-text);

            font-size: 13px;
            font-weight: 700;
        }

        .toc-user-role {
            margin-top: 3px;

            color: var(--gitc-muted);

            font-size: 11px;
        }


        /* =========================================================
           MY RESERVATIONS
        ========================================================== */

        .toc-my-reservations {
            min-height: 42px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 0 14px;

            border: 1px solid #cbd5e1;
            border-radius: 9px;

            background: #ffffff;

            color: var(--gitc-navy);

            font-size: 13px;
            font-weight: 700;

            white-space: nowrap;

            transition:
                background 0.18s ease,
                border-color 0.18s ease,
                transform 0.18s ease,
                box-shadow 0.18s ease;
        }

        .toc-my-reservations:hover {
            background: #f8fafc;

            border-color: #94a3b8;

            transform: translateY(-1px);

            box-shadow:
                0 5px 14px rgba(15, 39, 71, 0.08);
        }


        /* =========================================================
           BOOKING LIST / CART
        ========================================================== */

        .toc-cart-button {
            min-height: 42px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            gap: 8px;

            padding: 0 14px;

            border: 1px solid #cbd5e1;
            border-radius: 9px;

            background: #ffffff;

            color: var(--gitc-navy);

            font-size: 13px;
            font-weight: 700;

            white-space: nowrap;

            transition:
                background 0.18s ease,
                border-color 0.18s ease,
                transform 0.18s ease,
                box-shadow 0.18s ease;
        }

        .toc-cart-button:hover {
            background: #f8fafc;

            border-color: #94a3b8;

            transform: translateY(-1px);

            box-shadow:
                0 5px 14px rgba(15, 39, 71, 0.08);
        }

        .toc-cart-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            font-size: 16px;
            line-height: 1;
        }

        .toc-cart-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            min-width: 20px;
            min-height: 20px;

            padding: 0 5px;

            border-radius: 999px;

            background: var(--gitc-blue-light);

            color: var(--gitc-blue);

            font-size: 11px;
            font-weight: 800;

            line-height: 1;
        }


        /* =========================================================
           LOGOUT
        ========================================================== */

        .toc-logout {
            min-height: 42px;

            padding: 0 13px;

            border: 0;
            border-radius: 9px;

            background: transparent;

            color: #64748b;

            font-size: 13px;
            font-weight: 600;

            cursor: pointer;

            transition:
                color 0.18s ease,
                background 0.18s ease;
        }

        .toc-logout:hover {
            background: #f1f5f9;

            color: #334155;
        }


        /* =========================================================
           MAIN
        ========================================================== */

        .toc-main {
            min-height: calc(100vh - 72px);
        }

        .toc-container {
            width: min(1440px, calc(100% - 48px));

            margin: 0 auto;

            padding: 34px 0 64px;
        }


        /* =========================================================
           PAGE HEADER
        ========================================================== */

        .toc-page-header {
            margin-bottom: 28px;
        }

        .toc-page-eyebrow {
            display: block;

            margin-bottom: 7px;

            color: var(--gitc-blue);

            font-size: 11px;
            font-weight: 800;

            letter-spacing: 0.08em;

            text-transform: uppercase;
        }

        .toc-page-title {
            margin: 0;

            color: var(--gitc-navy);

            font-size: 30px;
            font-weight: 800;

            line-height: 1.15;

            letter-spacing: -0.025em;
        }

        .toc-page-description {
            max-width: 760px;

            margin: 9px 0 0;

            color: var(--gitc-muted);

            font-size: 14px;
            line-height: 1.7;
        }


        /* =========================================================
           ALERT
        ========================================================== */

        .toc-alert {
            margin-bottom: 24px;

            padding: 14px 16px;

            border: 1px solid var(--gitc-border);

            border-radius: 10px;

            background: white;

            font-size: 13px;
            line-height: 1.6;
        }

        .toc-alert-success {
            border-color: #bbf7d0;

            background: var(--gitc-success-bg);

            color: var(--gitc-success);
        }

        .toc-alert-warning {
            border-color: #fde68a;

            background: var(--gitc-warning-bg);

            color: var(--gitc-warning);
        }

        .toc-alert-danger {
            border-color: #fecaca;

            background: var(--gitc-danger-bg);

            color: var(--gitc-danger);
        }


        /* =========================================================
           FOOTER
        ========================================================== */

        .toc-footer {
            border-top: 1px solid var(--gitc-border);

            background: white;
        }

        .toc-footer-inner {
            width: min(1440px, calc(100% - 48px));

            min-height: 72px;

            margin: 0 auto;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 20px;
        }

        .toc-footer-text {
            color: var(--gitc-muted);

            font-size: 12px;
        }

        .toc-footer-accent {
            display: inline-flex;
            align-items: center;

            gap: 6px;

            color: var(--gitc-navy);

            font-size: 12px;
            font-weight: 700;
        }

        .toc-footer-dot {
            width: 7px;
            height: 7px;

            border-radius: 50%;

            background: var(--gitc-teal);
        }


        /* =========================================================
           RESPONSIVE
        ========================================================== */

        @media (max-width: 900px) {

            .toc-user {
                display: none;
            }

        }


        @media (max-width: 768px) {

            .toc-navbar {
                height: 64px;
            }

            .toc-navbar-inner,
            .toc-container,
            .toc-footer-inner {
                width: min(100% - 28px, 1440px);
            }

            .toc-main {
                min-height: calc(100vh - 64px);
            }

            .toc-container {
                padding-top: 24px;
                padding-bottom: 48px;
            }

            .toc-brand-subtitle {
                display: none;
            }

            .toc-logout {
                display: none;
            }

            .toc-my-reservations,
            .toc-cart-button {
                min-height: 40px;

                padding: 0 11px;

                font-size: 12px;
            }

            .toc-page-title {
                font-size: 26px;
            }

            .toc-footer-inner {
                min-height: 64px;
            }

            .toc-footer-text {
                font-size: 11px;
            }

        }


        @media (max-width: 560px) {

            .toc-navbar-inner,
            .toc-container,
            .toc-footer-inner {
                width: calc(100% - 20px);
            }

            .toc-brand-mark {
                width: 36px;
                height: 36px;

                border-radius: 9px;
            }

            .toc-brand-title {
                font-size: 14px;
            }

            .toc-nav-actions {
                gap: 6px;
            }

            .toc-my-reservations,
            .toc-cart-button {
                padding: 0 9px;

                font-size: 11px;
            }

            .toc-cart-label {
                display: none;
            }

            .toc-page-title {
                font-size: 23px;
            }

        }
    </style>

    @yield('head')

    @stack('styles')

</head>


@php

    /*
     * =========================================================
     * CLASSROOM CART
     * =========================================================
     *
     * IMPORTANT:
     * Classroom has its own cart session.
     *
     * This is intentionally separated from:
     *
     * training_officer_cart
     *
     * used by the main Training Officer area.
     */

    $trainingOfficerClassroomCart = session(
        'training_officer_classroom_cart',
        [
            'rooms' => [],
        ]
    );

    $trainingOfficerClassroomCartCount =
        count(
            $trainingOfficerClassroomCart['rooms'] ?? []
        );

@endphp


<body>


    {{-- =========================================================
         TOP NAVIGATION
    ========================================================== --}}

    <header class="toc-navbar">

        <div class="toc-navbar-inner">


            {{-- =================================================
                 BRAND
            ================================================== --}}

            <a
                href="{{ route('training-officer.classroom.home') }}"
                class="toc-brand"
            >

                <div class="toc-brand-mark">
                    GITC
                </div>

                <div class="toc-brand-text">

                    <span class="toc-brand-title">
                        GITC Classroom
                    </span>

                    <span class="toc-brand-subtitle">
                        Training System, Media & Business
                    </span>

                </div>

            </a>


            {{-- =================================================
                 NAVIGATION ACTIONS
            ================================================== --}}

            <div class="toc-nav-actions">


                {{-- =================================================
                     USER
                ================================================== --}}

                <div class="toc-user">

                    <div class="toc-user-avatar">

                        {{ strtoupper(
                            substr(
                                auth()->user()->name,
                                0,
                                1
                            )
                        ) }}

                    </div>

                    <div class="toc-user-info">

                        <span class="toc-user-name">
                            {{ auth()->user()->name }}
                        </span>

                        <span class="toc-user-role">
                            Training Officer
                        </span>

                    </div>

                </div>


                {{-- =================================================
                     MY RESERVATIONS
                ================================================== --}}

                <a
                    href="{{ route('training-officer.classroom.my-reservations') }}"
                    class="toc-my-reservations"
                >
                    My Reservations
                </a>


                {{-- =================================================
                     CLASSROOM BOOKING LIST
                ================================================== --}}

                <a
                    href="{{ route('training-officer.classroom.cart') }}"
                    class="toc-cart-button"
                    aria-label="Open classroom booking list"
                    id="classroom-cart-button"
                >

                    <span class="toc-cart-icon">
                        🛒
                    </span>

                    <span class="toc-cart-label">
                        Booking List
                    </span>

                    <span
                        class="toc-cart-count"
                        id="classroom-reservation-count"
                    >
                        {{ $trainingOfficerClassroomCartCount }}
                    </span>

                </a>


                {{-- =================================================
                     LOGOUT
                ================================================== --}}

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="toc-logout"
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

    <main class="toc-main">

        <div class="toc-container">


            {{-- =================================================
                 FLASH MESSAGES
            ================================================== --}}

            @if (session('success'))

                <div class="toc-alert toc-alert-success">
                    {{ session('success') }}
                </div>

            @endif


            @if (session('warning'))

                <div class="toc-alert toc-alert-warning">
                    {{ session('warning') }}
                </div>

            @endif


            @if (session('error'))

                <div class="toc-alert toc-alert-danger">
                    {{ session('error') }}
                </div>

            @endif


            {{-- =================================================
                 PAGE CONTENT
            ================================================== --}}

            @yield('content')

        </div>

    </main>


    {{-- =========================================================
         FOOTER
    ========================================================== --}}

    <footer class="toc-footer">

        <div class="toc-footer-inner">

            <span class="toc-footer-text">
                © {{ date('Y') }} GITC Reservation System
            </span>

            <span class="toc-footer-accent">

                <span class="toc-footer-dot"></span>

                Classroom Facility Booking

            </span>

        </div>

    </footer>


    {{-- =========================================================
         GLOBAL CLASSROOM CART SCRIPT
    ========================================================== --}}

    <script>

        window.updateClassroomReservationCount = function (count) {

            const countElement =
                document.getElementById(
                    'classroom-reservation-count'
                );

            if (!countElement) {
                return;
            }

            const safeCount =
                Number.isFinite(Number(count))
                    ? Number(count)
                    : 0;

            countElement.textContent =
                safeCount;

        };

    </script>


    @yield('scripts')

    @stack('scripts')

</body>

</html>
