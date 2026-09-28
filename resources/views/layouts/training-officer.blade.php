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

        .to-navbar {
            position: sticky;
            top: 0;
            z-index: 100;

            height: 72px;

            background: rgba(255, 255, 255, 0.96);

            border-bottom: 1px solid var(--gitc-border);

            backdrop-filter: blur(12px);
        }

        .to-navbar-inner {
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

        .to-brand {
            display: flex;
            align-items: center;

            gap: 12px;

            min-width: 0;
        }

        .to-brand-mark {
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

        .to-brand-text {
            display: flex;
            flex-direction: column;

            line-height: 1.15;
        }

        .to-brand-title {
            color: var(--gitc-navy);

            font-size: 15px;
            font-weight: 800;

            letter-spacing: -0.01em;
        }

        .to-brand-subtitle {
            margin-top: 3px;

            color: var(--gitc-muted);

            font-size: 11px;
            font-weight: 500;
        }

        /* =========================================================
           NAV RIGHT
        ========================================================== */

        .to-nav-actions {
            display: flex;
            align-items: center;

            gap: 12px;
        }

        .to-user {
            display: flex;
            align-items: center;

            gap: 10px;

            padding-right: 4px;
        }

        .to-user-avatar {
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

        .to-user-info {
            display: flex;
            flex-direction: column;

            line-height: 1.2;
        }

        .to-user-name {
            color: var(--gitc-text);

            font-size: 13px;
            font-weight: 700;
        }

        .to-user-role {
            margin-top: 3px;

            color: var(--gitc-muted);

            font-size: 11px;
        }

        /* =========================================================
           CART BUTTON
        ========================================================== */

        .to-cart-button {
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

            cursor: pointer;

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

        .to-cart-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            font-size: 16px;
            line-height: 1;
        }

        .to-cart-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            color: var(--gitc-navy);

            font-size: 13px;
            font-weight: 800;

            line-height: 1;
        }

        /* =========================================================
           LOGOUT
        ========================================================== */

        .to-logout {
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

        .to-logout:hover {
            background: #f1f5f9;

            color: #334155;
        }

        /* =========================================================
           MAIN
        ========================================================== */

        .to-main {
            min-height: calc(100vh - 72px);
        }

        .to-container {
            width: min(1440px, calc(100% - 48px));

            margin: 0 auto;

            padding: 34px 0 64px;
        }

        /* =========================================================
           FOOTER
        ========================================================== */

        .to-footer {
            border-top: 1px solid var(--gitc-border);

            background: white;
        }

        .to-footer-inner {
            width: min(1440px, calc(100% - 48px));

            min-height: 72px;

            margin: 0 auto;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 20px;
        }

        .to-footer-text {
            color: var(--gitc-muted);

            font-size: 12px;
        }

        .to-footer-accent {
            display: inline-flex;
            align-items: center;

            gap: 6px;

            color: var(--gitc-navy);

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
        ========================================================== */

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

            .to-user {
                display: none;
            }

            .to-brand-subtitle {
                display: none;
            }

            .to-logout {
                display: none;
            }

            .to-footer-inner {
                min-height: 64px;
            }

            .to-footer-text {
                font-size: 11px;
            }
        }

        @media (max-width: 480px) {

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

            .to-cart-button {
                padding: 0 11px;
            }

            .to-cart-label {
                display: none;
            }
        }
    </style>

    @yield('head')
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
                    GITC
                </div>

                <div class="to-brand-text">

                    <span class="to-brand-title">
                        GITC Reservation
                    </span>

                    <span class="to-brand-subtitle">
                        Training & Facility Booking
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


                {{-- Reservation Cart --}}
                <a
                    href="{{ route('training-officer.cart') }}"
                    class="to-cart-button"
                    aria-label="Open reservation cart"
                    id="reservation-cart-button"
                >

                    <span class="to-cart-icon">
                        🛒
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