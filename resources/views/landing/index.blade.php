<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        GITC Info — Reservation Calendar
    </title>

    <meta
        name="description"
        content="GITC Info reservation calendar"
    >

    <style>

        :root {
            --navy-950: #071d31;
            --navy-900: #0b2740;
            --navy-800: #123b5b;
            --navy-700: #15506f;

            --teal-600: #009eb4;
            --teal-500: #18afbd;
            --teal-100: #e6f8fa;

            --gold: #c7a85b;
            --gold-soft: #f7f0dd;

            --white: #ffffff;
            --surface: #f5f8fa;
            --surface-2: #f9fbfc;

            --text: #18364e;
            --muted: #6b8192;
            --border: #dce6ec;

            --shadow:
                0 18px 45px rgba(7, 29, 49, .10);

            --radius-lg: 18px;
            --radius-md: 12px;
        }


        * {
            box-sizing: border-box;
        }


        html {
            min-height: 100%;
            background: var(--surface);
        }


        body {
            margin: 0;
            min-height: 100vh;

            color: var(--text);

            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;

            background:
                linear-gradient(
                    135deg,
                    #f4f8fa 0%,
                    #eef5f7 55%,
                    #f8fafb 100%
                );
        }


        button {
            font: inherit;
        }


        /* =====================================================
           HEADER
        ===================================================== */

        .site-header {

            position: sticky;
            top: 0;
            z-index: 20;

            height: 76px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 42px;

            background:
                linear-gradient(
                    105deg,
                    var(--navy-950),
                    var(--navy-900) 65%,
                    #0d3d58
                );

            border-bottom:
                1px solid
                rgba(255,255,255,.08);

            box-shadow:
                0 8px 25px rgba(7,29,49,.10);
        }


        .brand {

            display: flex;
            align-items: center;
            gap: 13px;

            color: white;
            text-decoration: none;
        }


        .brand-mark {

            width: 38px;
            height: 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            border:
                1px solid
                rgba(199,168,91,.55);

            border-radius: 10px;

            color: var(--gold);

            font-size: 15px;
            font-weight: 800;

            background:
                rgba(255,255,255,.045);
        }


        .brand-text {

            display: flex;
            flex-direction: column;
            gap: 1px;
        }


        .brand-title {

            color: white;

            font-size: 16px;
            font-weight: 800;

            letter-spacing: .02em;
        }


        .brand-subtitle {

            color:
                rgba(255,255,255,.62);

            font-size: 10px;

            letter-spacing: .10em;
            text-transform: uppercase;
        }


        .login-button {

            display: inline-flex;
            align-items: center;
            justify-content: center;

            min-height: 40px;

            padding: 0 17px;

            border:
                1px solid
                rgba(199,168,91,.65);

            border-radius: 8px;

            color: white;

            background:
                rgba(255,255,255,.05);

            text-decoration: none;

            font-size: 12px;
            font-weight: 700;

            transition:
                background .2s ease,
                border-color .2s ease,
                transform .2s ease;
        }


        .login-button:hover {

            background:
                rgba(199,168,91,.14);

            border-color:
                var(--gold);

            transform:
                translateY(-1px);
        }


        /* =====================================================
           MAIN
        ===================================================== */

        .page {

            width:
                min(
                    calc(100% - 48px),
                    1500px
                );

            margin:
                0 auto;

            padding:
                38px 0 50px;
        }


        .hero {

            margin-bottom: 24px;
        }


        .eyebrow {

            display: inline-flex;
            align-items: center;
            gap: 8px;

            margin-bottom: 10px;

            color: var(--teal-600);

            font-size: 11px;
            font-weight: 800;

            letter-spacing: .12em;
            text-transform: uppercase;
        }


        .eyebrow::before {

            content: "";

            width: 24px;
            height: 2px;

            background: var(--gold);

            border-radius: 2px;
        }


        .hero h1 {

            margin: 0 0 7px;

            color: var(--navy-950);

            font-size:
                clamp(
                    28px,
                    4vw,
                    42px
                );

            line-height: 1.1;

            letter-spacing: -.035em;
        }


        .hero p {

            max-width: 700px;

            margin: 0;

            color: var(--muted);

            font-size: 14px;
            line-height: 1.6;
        }


        /* =====================================================
           CALENDAR SHELL
        ===================================================== */

        .calendar-shell {

            display: grid;

            grid-template-columns:
                minmax(0, 1fr)
                365px;

            height: 650px;

            overflow: hidden;

            background: white;

            border:
                1px solid
                var(--border);

            border-radius:
                var(--radius-lg);

            box-shadow:
                var(--shadow);
        }


        /* =====================================================
           CALENDAR
        ===================================================== */

        .calendar-area {

            min-width: 0;

            overflow: hidden;

            padding: 28px;
        }


        .calendar-toolbar {

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 16px;

            margin-bottom: 25px;
        }


        .month-title {

            margin: 0;

            color: var(--navy-950);

            font-size: 23px;
            font-weight: 800;

            letter-spacing: -.025em;
        }


        .month-subtitle {

            margin-top: 4px;

            color: var(--muted);

            font-size: 11px;
        }


        .month-navigation {

            display: flex;
            align-items: center;
            gap: 7px;
        }


        .month-button {

            width: 38px;
            height: 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            border:
                1px solid
                var(--border);

            border-radius: 9px;

            color: var(--navy-800);

            background: white;

            cursor: pointer;

            font-size: 18px;

            transition:
                background .2s ease,
                border-color .2s ease,
                color .2s ease;
        }


        .month-button:hover {

            border-color:
                var(--teal-500);

            color:
                var(--teal-600);

            background:
                var(--teal-100);
        }


        .today-button {

            min-height: 38px;

            padding: 0 12px;

            border:
                1px solid
                var(--border);

            border-radius: 9px;

            color: var(--navy-800);

            background: white;

            cursor: pointer;

            font-size: 11px;
            font-weight: 700;
        }


        .today-button:hover {

            border-color:
                var(--teal-500);

            color:
                var(--teal-600);
        }


        /* =====================================================
           WEEKDAYS
        ===================================================== */

        .calendar-weekdays {

            display: grid;

            grid-template-columns:
                repeat(7, minmax(0, 1fr));

            margin-bottom: 8px;
        }


        .weekday {

            padding:
                8px 5px;

            color: #8193a0;

            font-size: 10px;
            font-weight: 800;

            text-align: center;

            letter-spacing: .08em;
            text-transform: uppercase;
        }


        /* =====================================================
           DAYS
        ===================================================== */

        .calendar-grid {

            display: grid;

            grid-template-columns:
                repeat(7, minmax(0, 1fr));

            gap: 7px;
        }


        .calendar-day {

            position: relative;

            min-height: 78px;

            padding: 9px;

            border:
                1px solid
                transparent;

            border-radius: 11px;

            background:
                var(--surface-2);

            cursor: pointer;

            text-align: left;

            transition:
                border-color .18s ease,
                background .18s ease,
                transform .18s ease;
        }


        .calendar-day:hover {

            border-color:
                #b8dce3;

            background:
                #f2fbfc;

            transform:
                translateY(-1px);
        }


        .calendar-day.empty {

            cursor: default;

            background: transparent;

            border-color: transparent;
        }


        .calendar-day.empty:hover {

            transform: none;
        }


        .calendar-day-number {

            display: inline-flex;
            align-items: center;
            justify-content: center;

            width: 28px;
            height: 28px;

            color: var(--text);

            font-size: 12px;
            font-weight: 700;
        }


        .calendar-day.today
        .calendar-day-number {

            border-radius: 50%;

            color: white;

            background:
                var(--teal-600);

            box-shadow:
                0 5px 13px
                rgba(0,158,180,.22);
        }


        .calendar-day.selected {

            border-color:
                var(--navy-700);

            background:
                #edf5f8;
        }


        .calendar-day.selected
        .calendar-day-number {

            color: white;

            border-radius: 50%;

            background:
                var(--navy-800);
        }


        .calendar-day-indicators {

            position: absolute;

            left: 10px;
            right: 10px;
            bottom: 9px;

            display: flex;
            align-items: center;

            gap: 4px;

            min-height: 8px;
        }


        .reservation-dot {

            width: 6px;
            height: 6px;

            flex: 0 0 6px;

            border-radius: 50%;

            background:
                var(--teal-500);
        }


        .reservation-dot:nth-child(2) {
            background: var(--gold);
        }


        .reservation-dot:nth-child(3) {
            background: #53799a;
        }


        .more-indicator {

            color: var(--muted);

            font-size: 8px;
            font-weight: 700;
        }


        /* =====================================================
           CALENDAR FOOTER
        ===================================================== */

        .calendar-footer {

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;

            margin-top: 22px;
            padding-top: 16px;

            border-top:
                1px solid
                #edf2f5;
        }


        .legend {

            display: flex;
            align-items: center;
            gap: 15px;
        }


        .legend-item {

            display: inline-flex;
            align-items: center;
            gap: 7px;

            color: var(--muted);

            font-size: 10px;
            font-weight: 600;
        }


        .legend-dot {

            width: 7px;
            height: 7px;

            border-radius: 50%;

            background:
                var(--teal-500);
        }


        .reservation-summary {

            color: var(--muted);

            font-size: 10px;
        }


        .reservation-summary strong {

            color: var(--navy-800);
        }


        /* =====================================================
           DETAILS PANEL
        ===================================================== */

        .details-panel {

            display: flex;
            flex-direction: column;

            min-width: 0;
            min-height: 0;

            border-left:
                1px solid
                var(--border);

            background:
                linear-gradient(
                    180deg,
                    #f8fbfc,
                    #f2f7f9
                );
        }


        .details-header {

            flex: 0 0 auto;

            padding:
                26px 24px 20px;

            border-bottom:
                1px solid
                var(--border);

            background:
                rgba(248,251,252,.96);
        }


        .details-label {

            margin-bottom: 7px;

            color:
                var(--teal-600);

            font-size: 10px;
            font-weight: 800;

            letter-spacing: .12em;
            text-transform: uppercase;
        }


        .details-date {

            margin: 0;

            color:
                var(--navy-950);

            font-size: 21px;
            font-weight: 800;

            letter-spacing: -.025em;
        }


        .details-count {

            margin-top: 5px;

            color: var(--muted);

            font-size: 11px;
        }


        /*
         * IMPORTANT:
         * Only the reservation list scrolls.
         * Header stays visible.
         */

        .details-list {

            flex: 1 1 auto;

            min-height: 0;

            overflow-y: auto;

            padding: 18px;

            scrollbar-width: thin;

            scrollbar-color:
                #b9cdd6
                transparent;
        }


        .details-list::-webkit-scrollbar {

            width: 7px;
        }


        .details-list::-webkit-scrollbar-track {

            background: transparent;
        }


        .details-list::-webkit-scrollbar-thumb {

            border-radius: 10px;

            background:
                #b9cdd6;
        }


        .details-list::-webkit-scrollbar-thumb:hover {

            background:
                #91abb8;
        }


        .reservation-card {

            margin-bottom: 12px;
            padding: 15px;

            border:
                1px solid
                var(--border);

            border-radius: 12px;

            background: white;

            cursor: pointer;

            transition:
                border-color .18s ease,
                box-shadow .18s ease,
                transform .18s ease;
        }


        .reservation-card:last-child {

            margin-bottom: 0;
        }


        .reservation-card:hover {

            border-color:
                #b8dce3;

            box-shadow:
                0 8px 20px
                rgba(7,29,49,.07);

            transform:
                translateY(-1px);
        }


        .reservation-time {

            display: flex;
            align-items: center;
            gap: 7px;

            margin-bottom: 10px;

            color:
                var(--teal-600);

            font-size: 11px;
            font-weight: 800;
        }


        .time-line {

            width: 5px;
            height: 5px;

            border-radius: 50%;

            background:
                var(--gold);
        }


        .reservation-event {

            margin: 0 0 9px;

            color:
                var(--navy-950);

            font-size: 14px;
            font-weight: 800;

            line-height: 1.35;
        }


        .reservation-resource {

            display: flex;
            flex-direction: column;
            gap: 3px;

            margin-bottom: 12px;
        }


        .resource-name {

            color:
                var(--text);

            font-size: 11px;
            font-weight: 700;
        }


        .resource-building {

            color:
                var(--muted);

            font-size: 10px;
        }


        .resource-type {

            display: inline-flex;
            align-items: center;

            align-self: flex-start;

            margin-top: 3px;
            padding: 4px 7px;

            border-radius: 5px;

            color:
                var(--teal-600);

            background:
                var(--teal-100);

            font-size: 8px;
            font-weight: 800;

            letter-spacing: .05em;
            text-transform: uppercase;
        }


        .reservation-date-range {

            padding-top: 10px;

            border-top:
                1px solid
                #edf2f5;

            color:
                var(--muted);

            font-size: 9px;

            line-height: 1.5;
        }


        /* =====================================================
           EMPTY DETAILS
        ===================================================== */

        .details-empty {

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

            min-height: 260px;

            padding: 30px;

            text-align: center;
        }


        .empty-icon {

            width: 46px;
            height: 46px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin-bottom: 13px;

            border:
                1px solid
                #c8dce4;

            border-radius: 12px;

            color:
                var(--teal-600);

            background:
                white;

            font-size: 19px;
        }


        .details-empty h3 {

            margin: 0 0 5px;

            color:
                var(--navy-950);

            font-size: 14px;
        }


        .details-empty p {

            max-width: 220px;

            margin: 0;

            color:
                var(--muted);

            font-size: 10px;

            line-height: 1.6;
        }


        /* =====================================================
           LOADING
        ===================================================== */

        .loading-state {

            display: flex;
            align-items: center;
            justify-content: center;

            min-height: 180px;

            color:
                var(--muted);

            font-size: 11px;
        }


        .loading-dot {

            width: 6px;
            height: 6px;

            margin-right: 7px;

            border-radius: 50%;

            background:
                var(--teal-500);

            animation:
                pulse 1s infinite ease-in-out;
        }


        @keyframes pulse {

            0%,
            100% {
                opacity: .35;
            }

            50% {
                opacity: 1;
            }
        }


        /* =====================================================
           DETAIL MODAL
        ===================================================== */

        .reservation-modal {

            position: fixed;

            inset: 0;

            z-index: 100;

            display: none;
            align-items: center;
            justify-content: center;

            padding: 24px;

            background:
                rgba(7,29,49,.55);

            backdrop-filter:
                blur(5px);
        }


        .reservation-modal.show {

            display: flex;
        }


        .modal-card {

            width:
                min(
                    100%,
                    520px
                );

            max-height:
                calc(100vh - 48px);

            overflow-y: auto;

            border-radius:
                16px;

            background:
                white;

            box-shadow:
                0 25px 70px
                rgba(7,29,49,.25);
        }


        .modal-header {

            display: flex;
            align-items: flex-start;
            justify-content: space-between;

            gap: 15px;

            padding: 22px 23px;

            border-bottom:
                1px solid
                var(--border);
        }


        .modal-header h2 {

            margin: 0 0 5px;

            color:
                var(--navy-950);

            font-size: 18px;
        }


        .modal-header p {

            margin: 0;

            color:
                var(--muted);

            font-size: 10px;
        }


        .modal-close {

            width: 34px;
            height: 34px;

            display: flex;
            align-items: center;
            justify-content: center;

            flex: 0 0 34px;

            border:
                1px solid
                var(--border);

            border-radius: 8px;

            color:
                var(--muted);

            background:
                white;

            cursor: pointer;

            font-size: 17px;
        }


        .modal-close:hover {

            color:
                var(--navy-950);

            background:
                var(--surface);
        }


        .modal-body {

            padding: 23px;
        }


        .modal-event {

            margin-bottom: 20px;

            color:
                var(--navy-950);

            font-size: 21px;
            font-weight: 800;
        }


        .modal-grid {

            display: grid;

            grid-template-columns:
                repeat(2, minmax(0,1fr));

            gap: 10px;
        }


        .modal-field {

            padding: 12px;

            border:
                1px solid
                var(--border);

            border-radius: 9px;

            background:
                var(--surface-2);
        }


        .modal-field-label {

            margin-bottom: 5px;

            color:
                var(--muted);

            font-size: 9px;
            font-weight: 700;

            text-transform: uppercase;
            letter-spacing: .06em;
        }


        .modal-field-value {

            color:
                var(--navy-950);

            font-size: 12px;
            font-weight: 700;

            line-height: 1.4;

            word-break: break-word;
        }


        .modal-status {

            display: inline-flex;

            padding: 5px 8px;

            border-radius: 5px;

            color:
                #217346;

            background:
                #e9f7ef;

            font-size: 9px;
            font-weight: 800;

            letter-spacing: .04em;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 1050px) {

            .calendar-shell {

                grid-template-columns:
                    1fr;

                height: auto;
            }


            .details-panel {

                height: 450px;

                min-height: 0;

                border-top:
                    1px solid
                    var(--border);

                border-left: 0;
            }
        }


        @media (max-width: 700px) {

            .site-header {

                height: 68px;

                padding:
                    0 18px;
            }


            .brand-subtitle {

                display: none;
            }


            .page {

                width:
                    min(
                        calc(100% - 24px),
                        1500px
                    );

                padding-top: 25px;
            }


            .calendar-area {

                padding: 18px;
            }


            .calendar-toolbar {

                align-items: flex-start;
            }


            .month-title {

                font-size: 19px;
            }


            .calendar-grid {

                gap: 4px;
            }


            .calendar-day {

                min-height: 58px;

                padding: 5px;
            }


            .calendar-day-number {

                width: 24px;
                height: 24px;

                font-size: 10px;
            }


            .calendar-day-indicators {

                left: 7px;
                right: 7px;
                bottom: 6px;
            }


            .calendar-footer {

                flex-direction: column;
                align-items: flex-start;
            }


            .details-panel {

                height: 420px;
            }


            .modal-grid {

                grid-template-columns: 1fr;
            }
        }


        @media (max-width: 430px) {

            .today-button {

                display: none;
            }


            .weekday {

                font-size: 8px;
            }


            .calendar-day {

                min-height: 51px;
            }


            .calendar-day-indicators {

                display: none;
            }


            .month-button {

                width: 34px;
                height: 34px;
            }


            .details-panel {

                height: 400px;
            }
        }

    </style>

</head>


<body>

<header class="site-header">

    <a
        href="{{ url('/') }}"
        class="brand"
    >

        <div class="brand-mark">
            G
        </div>

        <div class="brand-text">

            <div class="brand-title">
                GITC INFO
            </div>

            <div class="brand-subtitle">
                Training System, Media & Business
            </div>

        </div>

    </a>


    <a
        href="{{ route('login') }}"
        class="login-button"
    >
        Login
    </a>

</header>


<main class="page">

    <section class="hero">

        <div class="eyebrow">
            Reservation Portal
        </div>

        <h1>
            GITC INFO
        </h1>

        <p>
            Explore approved reservations and facility schedules.
            Select a date to view the reservation details.
        </p>

    </section>


    <section class="calendar-shell">

        {{-- =================================================
             CALENDAR
        ================================================== --}}

        <div class="calendar-area">

            <div class="calendar-toolbar">

                <div>

                    <h2
                        class="month-title"
                        id="month-title"
                    >
                        Loading...
                    </h2>

                    <div
                        class="month-subtitle"
                        id="month-subtitle"
                    >
                        Reservation schedule
                    </div>

                </div>


                <div class="month-navigation">

                    <button
                        type="button"
                        class="today-button"
                        id="today-button"
                    >
                        Today
                    </button>

                    <button
                        type="button"
                        class="month-button"
                        id="previous-month"
                        aria-label="Previous month"
                    >
                        ‹
                    </button>

                    <button
                        type="button"
                        class="month-button"
                        id="next-month"
                        aria-label="Next month"
                    >
                        ›
                    </button>

                </div>

            </div>


            <div class="calendar-weekdays">

                <div class="weekday">Sun</div>
                <div class="weekday">Mon</div>
                <div class="weekday">Tue</div>
                <div class="weekday">Wed</div>
                <div class="weekday">Thu</div>
                <div class="weekday">Fri</div>
                <div class="weekday">Sat</div>

            </div>


            <div
                class="calendar-grid"
                id="calendar-grid"
            >
            </div>


            <div class="calendar-footer">


                <div
                    class="reservation-summary"
                    id="reservation-summary"
                >
                    <strong>0</strong>
                    reservations this month
                </div>

            </div>

        </div>


        {{-- =================================================
             DETAILS
        ================================================== --}}

        <aside class="details-panel">

            <div class="details-header">

                <div class="details-label">
                    Selected Date
                </div>

                <h2
                    class="details-date"
                    id="details-date"
                >
                    Select a date
                </h2>

                <div
                    class="details-count"
                    id="details-count"
                >
                    Click a calendar date to view reservations.
                </div>

            </div>


            <div
                class="details-list"
                id="details-list"
            >

                <div class="details-empty">

                    <div class="empty-icon">
                        ▦
                    </div>

                    <h3>
                        No Date Selected
                    </h3>

                    <p>
                        Select a date from the calendar to see its reservation schedule.
                    </p>

                </div>

            </div>

        </aside>

    </section>

</main>


{{-- =====================================================
     RESERVATION DETAIL MODAL
====================================================== --}}

<div
    class="reservation-modal"
    id="reservation-modal"
    aria-hidden="true"
>

    <div
        class="modal-card"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-event"
    >

        <div class="modal-header">

            <div>

                <h2>
                    Reservation Details
                </h2>

                <p>
                    Approved reservation information
                </p>

            </div>


            <button
                type="button"
                class="modal-close"
                id="modal-close"
                aria-label="Close"
            >
                ×
            </button>

        </div>


        <div class="modal-body">

            <div
                class="modal-event"
                id="modal-event"
            >
                Reservation
            </div>


            <div class="modal-grid">

                <div class="modal-field">

                    <div class="modal-field-label">
                        Booker Username
                    </div>

                    <div
                        class="modal-field-value"
                        id="modal-username"
                    >
                        —
                    </div>

                </div>


                <div class="modal-field">

                    <div class="modal-field-label">
                        Resource
                    </div>

                    <div
                        class="modal-field-value"
                        id="modal-resource"
                    >
                        —
                    </div>

                </div>


                <div class="modal-field">

                    <div class="modal-field-label">
                        Building
                    </div>

                    <div
                        class="modal-field-value"
                        id="modal-building"
                    >
                        —
                    </div>

                </div>

                  <div class="modal-field">

                    <div class="modal-field-label">
                        Reservation Number
                    </div>

                    <div
                        class="modal-field-value"
                        id="modal-number"
                    >
                        —
                    </div>

                </div>


                <div class="modal-field">

                    <div class="modal-field-label">
                        Start
                    </div>

                    <div
                        class="modal-field-value"
                        id="modal-start"
                    >
                        —
                    </div>

                </div>


                <div class="modal-field">

                    <div class="modal-field-label">
                        End
                    </div>

                    <div
                        class="modal-field-value"
                        id="modal-end"
                    >
                        —
                    </div>

                </div>


                <div class="modal-field">

                    <div class="modal-field-label">
                        Status
                    </div>

                    <div>

                        <span
                            class="modal-status"
                            id="modal-status"
                        >
                            APPROVED
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const calendarGrid =
            document.getElementById(
                'calendar-grid'
            );

        const monthTitle =
            document.getElementById(
                'month-title'
            );

        const monthSubtitle =
            document.getElementById(
                'month-subtitle'
            );

        const reservationSummary =
            document.getElementById(
                'reservation-summary'
            );

        const detailsDate =
            document.getElementById(
                'details-date'
            );

        const detailsCount =
            document.getElementById(
                'details-count'
            );

        const detailsList =
            document.getElementById(
                'details-list'
            );

        const previousButton =
            document.getElementById(
                'previous-month'
            );

        const nextButton =
            document.getElementById(
                'next-month'
            );

        const todayButton =
            document.getElementById(
                'today-button'
            );


        const modal =
            document.getElementById(
                'reservation-modal'
            );

        const modalClose =
            document.getElementById(
                'modal-close'
            );


        const modalEvent =
            document.getElementById(
                'modal-event'
            );

        const modalUsername =
            document.getElementById(
                'modal-username'
            );

        const modalResource =
            document.getElementById(
                'modal-resource'
            );

        const modalBuilding =
            document.getElementById(
                'modal-building'
            );

        const modalStart =
            document.getElementById(
                'modal-start'
            );

        const modalEnd =
            document.getElementById(
                'modal-end'
            );

        const modalNumber =
            document.getElementById(
                'modal-number'
            );

        const modalStatus =
            document.getElementById(
                'modal-status'
            );


        const monthNames = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];


        let currentDate =
            new Date();

        currentDate.setDate(1);


        let reservations = [];


        let selectedDateKey =
            formatDateKey(
                new Date()
            );


        /*
         * -------------------------------------------------
         * INITIAL LOAD
         * -------------------------------------------------
         */

        loadReservations();


        /*
         * -------------------------------------------------
         * NAVIGATION
         * -------------------------------------------------
         */

        previousButton.addEventListener(
            'click',
            function () {

                currentDate.setMonth(
                    currentDate.getMonth() - 1
                );

                selectedDateKey = null;

                loadReservations();

            }
        );


        nextButton.addEventListener(
            'click',
            function () {

                currentDate.setMonth(
                    currentDate.getMonth() + 1
                );

                selectedDateKey = null;

                loadReservations();

            }
        );


        todayButton.addEventListener(
            'click',
            function () {

                const today =
                    new Date();

                currentDate =
                    new Date(
                        today.getFullYear(),
                        today.getMonth(),
                        1
                    );

                selectedDateKey =
                    formatDateKey(today);

                loadReservations();

            }
        );


        /*
         * -------------------------------------------------
         * LOAD RESERVATIONS
         * -------------------------------------------------
         */

        async function loadReservations() {

            renderLoading();


            const year =
                currentDate.getFullYear();

            const month =
                currentDate.getMonth() + 1;


            try {

                const response =
                    await fetch(
                        '{{ route('landing.reservations') }}'
                        + '?year='
                        + encodeURIComponent(year)
                        + '&month='
                        + encodeURIComponent(month),
                        {
                            headers: {
                                'Accept':
                                    'application/json'
                            }
                        }
                    );


                if (!response.ok) {

                    throw new Error(
                        'Failed to load reservations.'
                    );

                }


                const data =
                    await response.json();


                reservations =
                    Array.isArray(
                        data.reservations
                    )
                        ? data.reservations
                        : [];


                renderCalendar();


                if (!selectedDateKey) {

                    const today =
                        new Date();


                    if (
                        today.getFullYear() === year
                        &&
                        today.getMonth() + 1 === month
                    ) {

                        selectedDateKey =
                            formatDateKey(today);

                    } else {

                        selectedDateKey =
                            formatDateKey(
                                new Date(
                                    year,
                                    month - 1,
                                    1
                                )
                            );

                    }

                }


                renderSelectedDate();


            } catch (error) {

                console.error(error);

                calendarGrid.innerHTML = '';

                const message =
                    document.createElement(
                        'div'
                    );

                message.style.gridColumn =
                    '1 / -1';

                message.style.padding =
                    '50px 20px';

                message.style.textAlign =
                    'center';

                message.style.color =
                    '#6b8192';

                message.textContent =
                    'Unable to load reservation data.';

                calendarGrid.appendChild(
                    message
                );

            }

        }


        /*
         * -------------------------------------------------
         * RENDER CALENDAR
         * -------------------------------------------------
         */

        function renderCalendar() {

            const year =
                currentDate.getFullYear();

            const month =
                currentDate.getMonth();


            monthTitle.textContent =
                monthNames[month]
                + ' '
                + year;


            monthSubtitle.textContent =
                'Approved reservation schedule';


            calendarGrid.innerHTML =
                '';


            const firstDay =
                new Date(
                    year,
                    month,
                    1
                ).getDay();


            const daysInMonth =
                new Date(
                    year,
                    month + 1,
                    0
                ).getDate();


            const today =
                new Date();


            /*
             * Empty cells before first day.
             */

            for (
                let i = 0;
                i < firstDay;
                i++
            ) {

                const empty =
                    document.createElement(
                        'div'
                    );

                empty.className =
                    'calendar-day empty';

                calendarGrid.appendChild(
                    empty
                );

            }


            /*
             * Calendar days.
             */

            for (
                let day = 1;
                day <= daysInMonth;
                day++
            ) {

                const date =
                    new Date(
                        year,
                        month,
                        day
                    );


                const dateKey =
                    formatDateKey(
                        date
                    );


                const dayReservations =
                    getReservationsForDate(
                        date
                    );


                const dayElement =
                    document.createElement(
                        'button'
                    );


                dayElement.type =
                    'button';


                dayElement.className =
                    'calendar-day';


                if (
                    today.getFullYear() === year
                    &&
                    today.getMonth() === month
                    &&
                    today.getDate() === day
                ) {

                    dayElement.classList.add(
                        'today'
                    );

                }


                if (
                    selectedDateKey === dateKey
                ) {

                    dayElement.classList.add(
                        'selected'
                    );

                }


                const number =
                    document.createElement(
                        'span'
                    );


                number.className =
                    'calendar-day-number';


                number.textContent =
                    day;


                dayElement.appendChild(
                    number
                );


                if (
                    dayReservations.length
                ) {

                    const indicators =
                        document.createElement(
                            'span'
                        );


                    indicators.className =
                        'calendar-day-indicators';


                    const visibleCount =
                        Math.min(
                            dayReservations.length,
                            3
                        );


                    for (
                        let i = 0;
                        i < visibleCount;
                        i++
                    ) {

                        const dot =
                            document.createElement(
                                'span'
                            );


                        dot.className =
                            'reservation-dot';


                        indicators.appendChild(
                            dot
                        );

                    }


                    if (
                        dayReservations.length > 3
                    ) {

                        const more =
                            document.createElement(
                                'span'
                            );


                        more.className =
                            'more-indicator';


                        more.textContent =
                            '+'
                            + (
                                dayReservations.length
                                - 3
                            );


                        indicators.appendChild(
                            more
                        );

                    }


                    dayElement.appendChild(
                        indicators
                    );

                }


                dayElement.addEventListener(
                    'click',
                    function () {

                        selectedDateKey =
                            dateKey;

                        renderCalendar();

                        renderSelectedDate();

                    }
                );


                calendarGrid.appendChild(
                    dayElement
                );

            }


            reservationSummary.innerHTML =
                '<strong>'
                + reservations.length
                + '</strong>'
                + (
                    reservations.length === 1
                        ? ' reservation this month'
                        : ' reservations this month'
                );

        }


        /*
         * -------------------------------------------------
         * GET RESERVATIONS FOR DATE
         * -------------------------------------------------
         */

        function getReservationsForDate(
            date
        ) {

            const dateKey =
                formatDateKey(date);


            return reservations.filter(
                function (reservation) {

                    return (
                        dateKey >=
                            reservation.start_date
                        &&
                        dateKey <=
                            reservation.end_date
                    );

                }
            );

        }


        /*
         * -------------------------------------------------
         * SELECTED DATE
         * -------------------------------------------------
         */

        function renderSelectedDate() {

            if (!selectedDateKey) {

                return;

            }


            const selectedDate =
                parseDateKey(
                    selectedDateKey
                );


            const dateReservations =
                getReservationsForDate(
                    selectedDate
                );


            detailsDate.textContent =
                selectedDate.toLocaleDateString(
                    'en-US',
                    {
                        weekday: 'long',
                        month: 'long',
                        day: 'numeric',
                        year: 'numeric'
                    }
                );


            detailsCount.textContent =
                dateReservations.length === 0
                    ? 'No approved reservations on this date.'
                    : (
                        dateReservations.length
                        + (
                            dateReservations.length === 1
                                ? ' approved reservation'
                                : ' approved reservations'
                        )
                    );


            if (
                dateReservations.length === 0
            ) {

                detailsList.innerHTML = `
                    <div class="details-empty">

                        <div class="empty-icon">
                            ✓
                        </div>

                        <h3>
                            No Reservations
                        </h3>

                        <p>
                            There are no approved reservations scheduled for this date.
                        </p>

                    </div>
                `;

                return;

            }


            detailsList.innerHTML =
                '';


            dateReservations.forEach(
                function (reservation) {

                    const card =
                        document.createElement(
                            'div'
                        );


                    card.className =
                        'reservation-card';


                    const time =
                        document.createElement(
                            'div'
                        );


                    time.className =
                        'reservation-time';


                    time.innerHTML =
                        '<span class="time-line"></span>'
                        + escapeHtml(
                            reservation.start_time
                        )
                        + ' — '
                        + escapeHtml(
                            reservation.end_time
                        );


                    const event =
                        document.createElement(
                            'h3'
                        );


                    event.className =
                        'reservation-event';


                    event.textContent =
                        reservation.event_name
                        || 'Reservation';


                    const resource =
                        document.createElement(
                            'div'
                        );


                    resource.className =
                        'reservation-resource';


                    resource.innerHTML = `
                        <span class="resource-name">
                            ${escapeHtml(reservation.resource_name)}
                        </span>

                        <span class="resource-building">
                            ${escapeHtml(reservation.building_name)}
                        </span>

                        <span class="resource-type">
                            ${escapeHtml(reservation.resource_type)}
                        </span>
                    `;


                    const range =
                        document.createElement(
                            'div'
                        );


                    range.className =
                        'reservation-date-range';


                    range.textContent =
                        formatDateRange(
                            reservation
                        );


                    card.appendChild(
                        time
                    );

                    card.appendChild(
                        event
                    );

                    card.appendChild(
                        resource
                    );

                    card.appendChild(
                        range
                    );


                    card.addEventListener(
                        'click',
                        function () {

                            openReservationModal(
                                reservation
                            );

                        }
                    );


                    detailsList.appendChild(
                        card
                    );

                }
            );

        }


        /*
         * -------------------------------------------------
         * MODAL
         * -------------------------------------------------
         */

        function openReservationModal(
            reservation
        ) {

            modalEvent.textContent =
                reservation.event_name
                || 'Reservation';


            modalUsername.textContent =
                reservation.username
                || '—';


            modalResource.textContent =
                reservation.resource_name
                + ' · '
                + reservation.resource_type;


            modalBuilding.textContent =
                reservation.building_name;


            modalStart.textContent =
                formatLongDate(
                    reservation.start_date
                )
                + ' · '
                + reservation.start_time;


            modalEnd.textContent =
                formatLongDate(
                    reservation.end_date
                )
                + ' · '
                + reservation.end_time;


            modalNumber.textContent =
                reservation.reservation_number;


            modalStatus.textContent =
                reservation.status;


            modal.classList.add(
                'show'
            );


            modal.setAttribute(
                'aria-hidden',
                'false'
            );

        }


        function closeReservationModal() {

            modal.classList.remove(
                'show'
            );


            modal.setAttribute(
                'aria-hidden',
                'true'
            );

        }


        modalClose.addEventListener(
            'click',
            closeReservationModal
        );


        modal.addEventListener(
            'click',
            function (event) {

                if (
                    event.target === modal
                ) {

                    closeReservationModal();

                }

            }
        );


        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Escape'
                ) {

                    closeReservationModal();

                }

            }
        );


        /*
         * -------------------------------------------------
         * HELPERS
         * -------------------------------------------------
         */

        function formatDateKey(
            date
        ) {

            const year =
                date.getFullYear();


            const month =
                String(
                    date.getMonth() + 1
                ).padStart(
                    2,
                    '0'
                );


            const day =
                String(
                    date.getDate()
                ).padStart(
                    2,
                    '0'
                );


            return (
                year
                + '-'
                + month
                + '-'
                + day
            );

        }


        function parseDateKey(
            key
        ) {

            const parts =
                key.split('-');


            return new Date(
                Number(parts[0]),
                Number(parts[1]) - 1,
                Number(parts[2])
            );

        }


        function formatLongDate(
            value
        ) {

            return parseDateKey(
                value
            ).toLocaleDateString(
                'en-US',
                {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                }
            );

        }


        function formatDateRange(
            reservation
        ) {

            if (
                reservation.start_date ===
                reservation.end_date
            ) {

                return (
                    formatLongDate(
                        reservation.start_date
                    )
                    + ' · '
                    + reservation.start_time
                    + ' — '
                    + reservation.end_time
                );

            }


            return (
                formatLongDate(
                    reservation.start_date
                )
                + ' '
                + reservation.start_time
                + ' — '
                + formatLongDate(
                    reservation.end_date
                )
                + ' '
                + reservation.end_time
            );

        }


        function escapeHtml(
            value
        ) {

            return String(
                value ?? ''
            )
                .replace(
                    /&/g,
                    '&amp;'
                )
                .replace(
                    /</g,
                    '&lt;'
                )
                .replace(
                    />/g,
                    '&gt;'
                )
                .replace(
                    /"/g,
                    '&quot;'
                )
                .replace(
                    /'/g,
                    '&#039;'
                );

        }


        function renderLoading() {

            calendarGrid.innerHTML = `
                <div
                    class="loading-state"
                    style="grid-column:1 / -1;"
                >
                    <span class="loading-dot"></span>
                    Loading reservations...
                </div>
            `;


            detailsList.innerHTML = `
                <div class="loading-state">
                    <span class="loading-dot"></span>
                    Loading...
                </div>
            `;

        }

    }

);

</script>

</body>
</html>