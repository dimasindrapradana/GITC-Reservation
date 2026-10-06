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

        /* =====================================================
           FONT
        ===================================================== */

        @font-face {
            font-family: 'Garuda Serif';
            src: url('{{ asset('assets/fonts/GarudaSerif-Regular.woff2') }}') format('woff2');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Garuda Serif';
            src: url('{{ asset('assets/fonts/GarudaSerif-Bold.woff2') }}') format('woff2');
            font-weight: 700 900;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Garuda Sans';
            src: url('{{ asset('assets/fonts/GarudaSans-Regular.woff2') }}') format('woff2');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Garuda Sans';
            src: url('{{ asset('assets/fonts/GarudaSans-Bold.woff2') }}') format('woff2');
            font-weight: 700 900;
            font-style: normal;
            font-display: swap;
        }


        /* =====================================================
           ROOT
        ===================================================== */

        :root {

            --navy-950: #071d31;
            --navy-900: #0b2740;
            --navy-800: #123b5b;
            --navy-700: #15506f;

            --teal-700: #087f91;
            --teal-600: #009eb4;
            --teal-500: #18afbd;
            --teal-100: #e6f8fa;

            --gold: #c7a85b;
            --gold-soft: #f7f0dd;

            --white: #ffffff;

            --surface: #f4f7f9;
            --surface-2: #f9fbfc;

            --text: #18364e;
            --muted: #6b8192;

            --border: #dce6ec;

            --green: #237a4b;
            --green-soft: #eaf7ef;

            --shadow:
                0 18px 45px rgba(7, 29, 49, .09);

            --radius-lg: 18px;
            --radius-md: 12px;
        }


        /* =====================================================
           RESET
        ===================================================== */

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
                'Garuda Sans',
                Arial,
                Helvetica,
                sans-serif;

            background:
                linear-gradient(
                    135deg,
                    #f4f8fa 0%,
                    #eef5f7 55%,
                    #f8fafb 100%
                );
        }


        button,
        input,
        select,
        textarea {
            font: inherit;
        }


        button {
            cursor: pointer;
        }


        /* =====================================================
           TOP NAVBAR
        ===================================================== */

        .site-header {
            position: sticky;
            top: 0;
            z-index: 50;
            height: 78px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            background: linear-gradient(
                105deg,
                var(--navy-950),
                var(--navy-900) 65%,
                #0d3d58
            );
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 0;
            min-width: 0;

            text-decoration: none !important;
            color: inherit;
             filter: brightness(0) invert(1);
        }

        .brand-mark {
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

        .brand-mark img {
            display: block;

            width: 100%;
            height: 100%;

            object-fit: contain;
            object-position: left center;

            margin: 0;
            padding: 0;
        }

        .brand-text {
            display: flex;
            flex-direction: column;

            line-height: 1.15;

            margin: 0 0 0 -145px;
            padding: 0;
        }

        .brand-title {
            color: var(--gitc-navy);

            font-family:
                'Garuda Serif',
                Georgia,
                serif;

            font-size: 21px;
            font-weight: 700;

            letter-spacing: -0.02em;
        }

        .brand-subtitle {
            margin-top: 3px;

            color: var(--gitc-muted);

            font-family:
                'Garuda Sans',
                Inter,
                sans-serif;

            font-size: 11px;
            font-weight: 600;
        }

        .brand-title {
            color: #fff;
            font-family: 'Garuda Serif', Georgia, serif;
            font-size: 21px;
            font-weight: 700;
            line-height: 1.05;
            white-space: nowrap;
        }

        .brand-subtitle {
            margin-top: 5px;
            color: rgba(255, 255, 255, 0.62);
            font-family: 'Garuda Sans', sans-serif;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.06em;
            line-height: 1;
            white-space: normal;
        }


        .login-button {

            min-height: 40px;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            padding:
                0
                17px;

            border:
                1px solid
                rgba(199,168,91,.65);

            border-radius: 8px;

            color: white;

            background:
                rgba(255,255,255,.05);

            text-decoration: none;

            font-family:
                'Garuda Sans',
                sans-serif;

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
           PAGE
        ===================================================== */

        .page {

            width:
                min(
                    calc(100% - 48px),
                    1600px
                );

            margin:
                0 auto;

            padding:
                32px 0 42px;
        }


        /* =====================================================
           HERO
        ===================================================== */

        .hero {

            display: flex;

            align-items: flex-end;

            justify-content: space-between;

            gap: 30px;

            margin-bottom: 22px;
        }


        .hero-copy {
            min-width: 0;
        }


        .eyebrow {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            margin-bottom: 8px;

            color:
                var(--teal-600);

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 10px;

            font-weight: 800;

            letter-spacing: .13em;

            text-transform: uppercase;
        }


        .eyebrow::before {

            content: "";

            width: 25px;

            height: 2px;

            background:
                var(--gold);

            border-radius: 2px;
        }


        .hero h1 {

            margin: 0 0 6px;

            color:
                var(--navy-950);

            font-family:
                'Garuda Serif',
                Georgia,
                serif;

            font-size:
                clamp(
                    30px,
                    3.2vw,
                    43px
                );

            line-height: 1.05;

            font-weight: 700;

            letter-spacing: -.035em;
        }


        .hero p {

            max-width: 720px;

            margin: 0;

            color:
                var(--muted);

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 13px;

            line-height: 1.6;
        }


        .hero-note {

            flex: 0 0 auto;

            padding:
                10px 13px;

            border:
                1px solid
                var(--border);

            border-radius: 9px;

            color:
                var(--muted);

            background:
                rgba(255,255,255,.70);

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 10px;

            font-weight: 700;

            white-space: nowrap;
        }


        /* =====================================================
           CALENDAR SHELL
        ===================================================== */

        .calendar-shell {

            display: grid;

            grid-template-columns:
                minmax(0, 1fr)
                390px;

            /*
             * Desktop:
             * fill almost the entire visible browser height.
             */
            min-height:
                calc(100vh - 190px);

            height:
                calc(100vh - 190px);

            min-width: 0;

            overflow: hidden;

            background:
                white;

            border:
                1px solid
                var(--border);

            border-radius:
                var(--radius-lg);

            box-shadow:
                var(--shadow);
        }


        /* =====================================================
           CALENDAR AREA
        ===================================================== */

        .calendar-area {

            min-width: 0;

            min-height: 0;

            display: flex;

            flex-direction: column;

            padding:
                26px 28px 20px;
        }


        .calendar-toolbar {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            margin-bottom: 20px;
        }


        .month-title {

            margin: 0;

            color:
                var(--navy-950);

            font-family:
                'Garuda Serif',
                Georgia,
                serif;

            font-size: 25px;

            line-height: 1.1;

            font-weight: 700;

            letter-spacing: -.025em;
        }


        .month-subtitle {

            margin-top: 5px;

            color:
                var(--muted);

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 10px;

            font-weight: 600;
        }


        .month-navigation {

            display: flex;

            align-items: center;

            gap: 6px;
        }


        .month-button,
        .today-button {

            min-height: 37px;

            border:
                1px solid
                var(--border);

            border-radius: 8px;

            color:
                var(--navy-800);

            background:
                white;

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 11px;

            font-weight: 700;

            transition:
                background .18s ease,
                border-color .18s ease,
                color .18s ease;
        }


        .month-button {

            width: 37px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 18px;
        }


        .today-button {

            padding:
                0
                12px;
        }


        .month-button:hover,
        .today-button:hover {

            border-color:
                var(--teal-500);

            color:
                var(--teal-600);

            background:
                var(--teal-100);
        }


        /* =====================================================
           WEEKDAYS
        ===================================================== */

        .calendar-weekdays {

            display: grid;

            grid-template-columns:
                repeat(7, minmax(0, 1fr));

            margin-bottom: 7px;
        }


        .weekday {

            padding:
                6px 4px;

            color:
                #8295a2;

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 9px;

            font-weight: 800;

            text-align: center;

            letter-spacing: .08em;

            text-transform: uppercase;
        }


        /* =====================================================
           CALENDAR GRID
        ===================================================== */

        .calendar-grid {

            flex: 1 1 auto;

            min-height: 0;

            display: grid;

            grid-template-columns:
                repeat(7, minmax(0, 1fr));

            grid-auto-rows:
                minmax(70px, 1fr);

            gap: 6px;
        }


        .calendar-day {

            position: relative;

            min-width: 0;

            min-height: 0;

            padding:
                8px;

            border:
                1px solid
                transparent;

            border-radius: 10px;

            background:
                var(--surface-2);

            cursor: pointer;

            text-align: left;

            transition:
                border-color .16s ease,
                background .16s ease,
                transform .16s ease;
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

            background:
                transparent;

            border-color:
                transparent;
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

            color:
                var(--text);

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 12px;

            font-weight: 800;
        }


        .calendar-day.today .calendar-day-number {

            border-radius: 50%;

            color:
                white;

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


        .calendar-day.selected .calendar-day-number {

            color:
                white;

            border-radius: 50%;

            background:
                var(--navy-800);
        }


        .calendar-day-indicators {

            position: absolute;

            left: 9px;

            right: 9px;

            bottom: 8px;

            display: flex;

            align-items: center;

            gap: 4px;

            min-height: 7px;
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
            background:
                var(--gold);
        }


        .reservation-dot:nth-child(3) {
            background:
                #53799a;
        }


        .more-indicator {

            color:
                var(--muted);

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 8px;

            font-weight: 800;
        }


        /* =====================================================
           CALENDAR FOOTER
        ===================================================== */

        .calendar-footer {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            padding-top: 14px;

            margin-top: 14px;

            border-top:
                1px solid
                #edf2f5;
        }


        .reservation-summary {

            color:
                var(--muted);

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 10px;

            font-weight: 600;
        }


        .reservation-summary strong {

            color:
                var(--navy-800);

            font-size: 12px;
        }


        /* =====================================================
           DETAILS PANEL
        ===================================================== */

        .details-panel {

            display: flex;

            flex-direction: column;

            min-width: 0;

            min-height: 0;

            overflow: hidden;

            border-left:
                1px solid
                var(--border);

            background:
                linear-gradient(
                    180deg,
                    #f8fbfc,
                    #f1f6f8
                );
        }


        .details-header {

            flex: 0 0 auto;

            padding:
                25px 24px 20px;

            border-bottom:
                1px solid
                var(--border);

            background:
                rgba(248,251,252,.97);
        }


        .details-label {

            margin-bottom: 6px;

            color:
                var(--teal-600);

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 9px;

            font-weight: 800;

            letter-spacing: .12em;

            text-transform: uppercase;
        }


        .details-date {

            margin: 0;

            color:
                var(--navy-950);

            font-family:
                'Garuda Serif',
                Georgia,
                serif;

            font-size: 21px;

            line-height: 1.15;

            font-weight: 700;

            letter-spacing: -.02em;
        }


        .details-count {

            margin-top: 5px;

            color:
                var(--muted);

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 10px;

            font-weight: 600;
        }


        /* =====================================================
           IMPORTANT:
           ONLY THIS AREA SCROLLS
        ===================================================== */

        .details-list {

            flex: 1 1 auto;

            min-height: 0;

            overflow-y: auto;

            overflow-x: hidden;

            padding:
                17px;

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


        /* =====================================================
           RESERVATION CARD
        ===================================================== */

        .reservation-card {

            margin-bottom: 11px;

            padding:
                15px;

            border:
                1px solid
                var(--border);

            border-radius: 12px;

            background:
                white;

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


        /* =====================================================
           TIME
        ===================================================== */

        .reservation-time {

            display: flex;

            align-items: center;

            gap: 7px;

            margin-bottom: 10px;

            color:
                var(--teal-600);

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 10px;

            font-weight: 800;
        }


        .time-line {

            width: 5px;

            height: 5px;

            border-radius: 50%;

            background:
                var(--gold);
        }


        /* =====================================================
           EVENT
        ===================================================== */

        .reservation-event {

            margin:
                0 0 13px;

            color:
                var(--navy-950);

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 15px;

            line-height: 1.35;

            font-weight: 800;
        }


        /* =====================================================
           RESOURCE / ROOM
        ===================================================== */

        .reservation-resource {

            padding:
                12px;

            margin-bottom: 12px;

            border:
                1px solid
                #e1ebef;

            border-radius: 9px;

            background:
                #f8fbfc;
        }


        .resource-label {

            margin-bottom: 5px;

            color:
                var(--muted);

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 8px;

            font-weight: 800;

            letter-spacing: .09em;

            text-transform: uppercase;
        }


        .resource-name {

            display: block;

            color:
                var(--navy-950);

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 16px;

            line-height: 1.25;

            font-weight: 800;
        }


        .resource-building {

            display: block;

            margin-top: 4px;

            color:
                var(--muted);

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 10px;

            font-weight: 600;
        }


        .resource-type {

            display: inline-flex;

            align-items: center;

            margin-top: 8px;

            padding:
                4px 7px;

            border-radius: 5px;

            color:
                var(--teal-700);

            background:
                var(--teal-100);

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 8px;

            font-weight: 800;

            letter-spacing: .05em;

            text-transform: uppercase;
        }


        /* =====================================================
           DATE RANGE
        ===================================================== */

        .reservation-date-range {

            padding-top: 10px;

            border-top:
                1px solid
                #edf2f5;

            color:
                var(--muted);

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 9px;

            line-height: 1.5;

            font-weight: 600;
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

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 18px;

            font-weight: 800;
        }


        .details-empty h3 {

            margin:
                0 0 5px;

            color:
                var(--navy-950);

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 14px;

            font-weight: 800;
        }


        .details-empty p {

            max-width: 230px;

            margin: 0;

            color:
                var(--muted);

            font-family:
                'Garuda Sans',
                sans-serif;

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

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 10px;

            font-weight: 600;
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
           MODAL
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
                    540px
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

            padding:
                22px 23px;

            border-bottom:
                1px solid
                var(--border);
        }


        .modal-header h2 {

            margin:
                0 0 5px;

            color:
                var(--navy-950);

            font-family:
                'Garuda Serif',
                Georgia,
                serif;

            font-size: 19px;

            font-weight: 700;
        }


        .modal-header p {

            margin: 0;

            color:
                var(--muted);

            font-family:
                'Garuda Sans',
                sans-serif;

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

            font-family:
                'Garuda Sans',
                sans-serif;

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

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 20px;

            line-height: 1.3;

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

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 8px;

            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: .06em;
        }


        .modal-field-value {

            color:
                var(--navy-950);

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 12px;

            line-height: 1.4;

            font-weight: 700;

            word-break: break-word;
        }


        .modal-status {

            display: inline-flex;

            padding:
                5px 8px;

            border-radius: 5px;

            color:
                var(--green);

            background:
                var(--green-soft);

            font-family:
                'Garuda Sans',
                sans-serif;

            font-size: 8px;

            font-weight: 800;

            letter-spacing: .04em;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 1150px) {

            .calendar-shell {

                grid-template-columns:
                    minmax(0, 1fr)
                    350px;
            }

        }


        @media (max-width: 1000px) {

            .calendar-shell {

                grid-template-columns:
                    1fr;

                height: auto;

                min-height: 0;
            }


            .calendar-area {

                min-height: 650px;
            }


            .details-panel {

                height: 430px;

                min-height: 430px;

                border-top:
                    1px solid
                    var(--border);

                border-left: 0;
            }

        }


        @media (max-width: 700px) {

            .site-header {

                height: 70px;

                padding:
                    0 18px;
            }


            

            .brand-title {
                font-size: 16px;
            }


            .brand-subtitle {
                display: none;
            }


            .login-button {

                min-height: 36px;

                padding:
                    0 12px;

                font-size: 10px;
            }


            .page {

                width:
                    calc(100% - 24px);

                padding:
                    24px 0 30px;
            }


            .hero {

                display: block;

                margin-bottom: 18px;
            }


            .hero h1 {

                font-size: 30px;
            }


            .hero p {

                font-size: 12px;
            }


            .hero-note {

                display: inline-flex;

                margin-top: 14px;
            }


            .calendar-area {

                min-height: 590px;

                padding:
                    18px;
            }


            .calendar-toolbar {

                align-items: flex-start;
            }


            .month-title {
                font-size: 21px;
            }


            .calendar-grid {

                gap: 4px;

                grid-auto-rows:
                    minmax(57px, 1fr);
            }


            .calendar-day {

                padding: 5px;

                border-radius: 8px;
            }


            .calendar-day-number {

                width: 24px;

                height: 24px;

                font-size: 10px;
            }


            .calendar-day-indicators {

                left: 6px;

                right: 6px;

                bottom: 6px;
            }


            .weekday {

                font-size: 8px;
            }


            .calendar-footer {

                align-items: flex-start;

                flex-direction: column;
            }


            .details-panel {

                height: 430px;

                min-height: 430px;
            }


            .modal-grid {

                grid-template-columns:
                    1fr;
            }

        }


        @media (max-width: 430px) {

            .today-button {
                display: none;
            }


            .month-navigation {
                gap: 4px;
            }


            .month-button {

                width: 34px;

                min-height: 34px;

                height: 34px;
            }


            .calendar-grid {

                gap: 3px;

                grid-auto-rows:
                    minmax(50px, 1fr);
            }


            .calendar-day {

                min-height: 50px;

                padding: 3px;
            }


            .calendar-day-indicators {
                display: none;
            }


            .weekday {
                font-size: 7px;
            }


            .details-panel {

                height: 400px;

                min-height: 400px;
            }

        }

        @media (max-width: 700px) {

    .site-header {
        min-height: 78px;
        padding: 0 18px;
        gap: 12px;
    }

    .brand-logo {
        width: 68px;
        height: 56px;
        flex-basis: 68px;
    }

    .brand-logo img {
        width: 116px;
        height: 68px;
    }

    

    .brand-title {
        font-size: 19px;
    }

    .brand-subtitle {
        font-size: 9px;
        letter-spacing: 0.035em;
        white-space: normal;
        max-width: 220px;
    }

    .login-button {
        padding: 9px 13px;
        font-size: 12px;
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

            <img
                src="{{ asset('assets/icons/logo/Garuda.svg') }}"
                alt="GITC"
            >

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

        <div class="hero-copy">

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

        </div>


        

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
             DETAILS SIDEBAR
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


            {{--

                IMPORTANT:

                This container is the ONLY
                scrolling area in the details sidebar.

            --}}

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
                        Select a date from the calendar
                        to see its reservation schedule.
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
                        Location
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


        /* =====================================================
           ELEMENTS
        ===================================================== */

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


        /* =====================================================
           MONTHS
        ===================================================== */

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


        /* =====================================================
           STATE
        ===================================================== */

        let currentDate =
            new Date();


        currentDate.setDate(1);


        let reservations = [];


        let selectedDateKey =
            formatDateKey(
                new Date()
            );


        /* =====================================================
           INITIAL LOAD
        ===================================================== */

        loadReservations();


        /* =====================================================
           MONTH NAVIGATION
        ===================================================== */

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
                    formatDateKey(
                        today
                    );


                loadReservations();

            }
        );


        /* =====================================================
           LOAD RESERVATIONS
        ===================================================== */

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
                            formatDateKey(
                                today
                            );

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


                message.style.display =
                    'flex';


                message.style.alignItems =
                    'center';


                message.style.justifyContent =
                    'center';


                message.style.padding =
                    '40px';


                message.style.textAlign =
                    'center';


                message.style.color =
                    '#6b8192';


                message.style.fontFamily =
                    "'Garuda Sans', sans-serif";


                message.style.fontSize =
                    '11px';


                message.textContent =
                    'Unable to load reservation data.';


                calendarGrid.appendChild(
                    message
                );

            }

        }


        /* =====================================================
           RENDER CALENDAR
        ===================================================== */

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


            /* Empty cells */

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


            /* Calendar days */

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
                                dayReservations.length - 3
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


        /* =====================================================
           RESERVATIONS FOR DATE
        ===================================================== */

        function getReservationsForDate(
            date
        ) {

            const dateKey =
                formatDateKey(
                    date
                );


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


        /* =====================================================
           SELECTED DATE
        ===================================================== */

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

                        

                        <h3>
                            No Reservations
                        </h3>

                        <p>
                            There are no approved reservations
                            scheduled for this date.
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


                    /* TIME */

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


                    /* EVENT */

                    const event =
                        document.createElement(
                            'h3'
                        );


                    event.className =
                        'reservation-event';


                    event.textContent =
                        reservation.event_name
                        || 'Reservation';


                    /* RESOURCE */

                    const resource =
                        document.createElement(
                            'div'
                        );


                    resource.className =
                        'reservation-resource';


                    resource.innerHTML = `

                        <div class="resource-label">
                            Room / Resource
                        </div>

                        <span class="resource-name">
                            ${escapeHtml(
                                reservation.resource_name
                            )}
                        </span>

                        <span class="resource-building">
                            ${escapeHtml(
                                reservation.building_name
                            )}
                        </span>

                        <span class="resource-type">
                            ${escapeHtml(
                                reservation.resource_type
                            )}
                        </span>

                    `;


                    /* DATE RANGE */

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


        /* =====================================================
           MODAL
        ===================================================== */

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


        /* =====================================================
           HELPERS
        ===================================================== */

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