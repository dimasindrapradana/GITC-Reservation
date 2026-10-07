<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Upcoming Schedule - {{ $buildingName }}
    </title>


    <style>

         /* =========================================================
        GARUDA FONT
        ========================================================= */

            
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

            --navy-dark: #00294f;
            --navy: #003b6f;
            --cyan: #00a8c8;

            --background: #f3f7fa;
            --white: #ffffff;

            --text: #12304a;
            --muted: #668096;

            --border: #d9e5ed;

            --red: #df4545;
            --red-bg: #fff0f0;
            --red-border: #ffd0d0;

            --blue-status: #007bb5;
            --blue-bg: #eefaff;
            --blue-border: #aee6fb;

            --gray: #7b8c97;
            --gray-bg: #f3f6f8;
            --gray-border: #dce4e9;

            --radius-large: 18px;
            --radius-medium: 12px;

        }


        * {
            box-sizing: border-box;
        }


        html,
        body {

            width: 100%;
            height: 100%;

            margin: 0;
            padding: 0;

            overflow: hidden;

            font-family:
                "Garuda Sans",
                Arial,
                sans-serif;

            background:
                var(--background);

            color:
                var(--text);

        }


        body {

            display: flex;

            flex-direction: column;

        }


        /* =========================================================
           APP
        ========================================================= */

        .upcoming-app {

            width: 100vw;
            height: 100vh;

            display: flex;

            flex-direction: column;

            overflow: hidden;

        }


        /* =========================================================
           HEADER
        ========================================================= */

        .upcoming-header {

            flex:
                0 0
                13vh;

            min-height:
                95px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding:
                0
                3vw;

            color:
                #ffffff;

            background:

                radial-gradient(
                    circle at 78% 15%,
                    rgba(
                        0,
                        168,
                        200,
                        0.20
                    ),
                    transparent 28%
                ),

                linear-gradient(
                    115deg,
                    var(--navy-dark),
                    var(--navy)
                );

            position: relative;

            overflow: hidden;

        }


        .upcoming-header::after {

            content: "";

            position: absolute;

            width: 42vw;
            height: 23vh;

            right: -10vw;
            top: -13vh;

            border-radius: 50%;

            border:
                1px solid
                rgba(
                    255,
                    255,
                    255,
                    0.06
                );

            pointer-events: none;

        }


        .header-left {

            position: relative;

            z-index: 2;

            display: flex;

            align-items: center;

            gap: 1.1vw;

            min-width: 0;

        }


        .brand-block {

            min-width: 0;

        }


        .brand-title {

            font-size:
                clamp(
                    22px,
                    1.65vw,
                    34px
                );

            font-weight:
                800;

            line-height:
                1;

        }


        .brand-subtitle {

            margin-top:
                6px;

            color:
                rgba(
                    255,
                    255,
                    255,
                    0.70
                );

            font-size:
                clamp(
                    11px,
                    0.82vw,
                    16px
                );

        }


        .header-divider {

            width:
                1px;

            height:
                54px;

            margin:
                0
                0.7vw;

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.24
                );

        }


        .building-block {

            min-width:
                0;

        }


        .building-name {

            max-width:
                34vw;

            overflow:
                visible;

            text-overflow:
                ellipsis;

            white-space:
                nowrap;

            font-size:
                clamp(
                    21px,
                    1.5vw,
                    31px
                );

            font-weight:
                800;

        }


        .header-right {

            position:
                relative;

            z-index:
                2;

            display:
                flex;

            align-items:
                center;

            gap:
                1.5vw;

        }


        .datetime {

            text-align:
                right;

        }


        .date {

            color:
                rgba(
                    255,
                    255,
                    255,
                    0.80
                );

            font-size:
                clamp(
                    11px,
                    0.76vw,
                    16px
                );

        }


        .clock {

            margin-top:
                4px;

            color:
                #69e5f5;

            font-size:
                clamp(
                    24px,
                    1.95vw,
                    40px
                );

            font-weight:
                800;

            line-height:
                1;

            white-space:
                nowrap;

        }


        .logo-area {

            width:
                clamp(
                    155px,
                    14vw,
                    270px
                );

            height:
                64px;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            padding:
                4px
                15px;

            border-left:
                1px solid
                rgba(
                    255,
                    255,
                    255,
                    0.18
                );

        }


        .logo-area img {

            max-width:
                100%;

            max-height:
                54px;

            display:
                block;

            object-fit:
                contain;

        }


        /* =========================================================
           MAIN
        ========================================================= */

        .upcoming-main {

            flex:
                1;

            min-height:
                0;

            display:
                flex;

            flex-direction:
                column;

            padding:
                1.3vw
                2.2vw
                0;

            overflow:
                hidden;

        }


        /* =========================================================
           PAGE HEADER
        ========================================================= */

        .page-header {

            flex:
                0 0 auto;

            display:
                flex;

            align-items:
                center;

            justify-content:
                space-between;

            gap:
                20px;

            margin-bottom:
                1vw;

        }


        .page-eyebrow {

            color:
                var(--cyan);

            font-size:
                clamp(
                    10px,
                    0.66vw,
                    14px
                );

            font-weight:
                800;

            letter-spacing:
                0.10em;

            text-transform:
                uppercase;

        }


        .page-title {

            margin:
                5px
                0
                0;

            color:
                var(--navy-dark);

            font-size:
                clamp(
                    28px,
                    2vw,
                    40px
                );

            font-weight:
                800;

            line-height:
                1.08;

        }


        .page-subtitle {

            margin-top:
                6px;

            color:
                var(--muted);

            font-size:
                clamp(
                    11px,
                    0.76vw,
                    15px
                );

        }


        .page-actions {

            display:
                flex;

            align-items:
                center;

            gap:
                10px;

        }


        .schedule-search {

            width:
                clamp(
                    180px,
                    14vw,
                    240px
                );

            height:
                44px;

            min-height:
                44px;

            padding:
                0
                16px;

            border:
                1px solid
                #cbdce6;

            border-radius:
                999px;

            outline:
                none;

            background:
                #ffffff;

            color:
                var(--text);

            font-family:
                "Garuda Sans",
                Arial,
                sans-serif;

            font-size:
                12px;

            font-weight:
                500;

            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease;

        }


        .schedule-search::placeholder {

            color:
                #93a4af;

        }


        .schedule-search:focus {

            border-color:
                rgba(
                    0,
                    59,
                    111,
                    0.35
                );

            box-shadow:
                0
                3px
                10px
                rgba(
                    0,
                    41,
                    79,
                    0.07
                );

        }


        .back-button {

            height:
                44px;

            min-height:
                44px;

            min-width:
                160px;

            display:
                inline-flex;

            align-items:
                center;

            justify-content:
                center;

            padding:
                0
                18px;

            border:
                1px solid
                #cbdce6;

            border-radius:
                999px;

            background:
                #ffffff;

            color:
                #06466d;

            font-family:
                "Garuda Sans",
                Arial,
                sans-serif;

            font-size:
                12px;

            font-weight:
                700;

            letter-spacing:
                0.04em;

            text-decoration:
                none;

            text-transform:
                uppercase;

            transition:
                background 0.2s ease,
                transform 0.2s ease;

        }


        .back-button:hover {

            background:
                #f1f8fb;

            transform:
                translateY(-1px);

        }


        /* =========================================================
           SCHEDULE AREA
        ========================================================= */

        .schedule-container {

            flex:
                1;

            min-height:
                0;

            overflow:
                hidden;

            padding-bottom:
                0.8vw;

        }


        .schedule-scroll {

            width:
                100%;

            height:
                100%;

            overflow-y:
                auto;

            overflow-x:
                hidden;

            padding-right:
                5px;

            scrollbar-width:
                thin;

        }


        /* =========================================================
           DAY SECTION
        ========================================================= */

        .day-section {

            margin-bottom:
                14px;

            background:
                #ffffff;

            border:
                1px solid
                var(--border);

            border-radius:
                var(--radius-large);

            box-shadow:
                0
                4px
                12px
                rgba(
                    0,
                    41,
                    79,
                    0.05
                );

            overflow:
                hidden;

        }


        .day-header {

            min-height:
                58px;

            display:
                flex;

            align-items:
                center;

            justify-content:
                space-between;

            gap:
                15px;

            padding:
                10px
                16px;

            background:
                #f9fbfc;

            border-bottom:
                1px solid
                #e5edf2;

        }


        .day-title {

            color:
                var(--navy-dark);

            font-size:
                clamp(
                    16px,
                    1.1vw,
                    23px
                );

            font-weight:
                800;

        }


        /* .day-count {

            color:
                var(--muted);

            font-size:
                10px;

            font-weight:
                700;

        } */


        /* =========================================================
           ROW
        ========================================================= */

        .day-list {

            display:
                flex;

            flex-direction:
                column;

        }


        .schedule-row {

            min-width:
                0;

            min-height:
                82px;

            height:
                auto;

            display:
                grid;

            grid-template-columns:
                42px
                minmax(
                    200px,
                    0.9fr
                )
                minmax(
                    0,
                    1.4fr
                );

            align-items:
                start;

            gap:
                18px;

            padding:
                15px
                18px;

            border-bottom:
                1px solid
                #edf1f4;

        }


        .schedule-row:last-child {

            border-bottom:
                none;

        }


        /* =========================================================
           NUMBER
        ========================================================= */

        .schedule-number {

            padding-top:
                3px;

            color:
                rgba(
                    0,
                    59,
                    111,
                    0.34
                );

            font-size:
                12px;

            font-weight:
                800;

        }


        /* =========================================================
           RESOURCE / ROOM
        ========================================================= */

        .schedule-resource {

            min-width:
                0;

            display:
                flex;

            flex-direction:
                column;

            justify-content:
                flex-start;

        }


        .resource-label {

            color:
                var(--muted);

            font-size:
                clamp(
                    8px,
                    0.52vw,
                    11px
                );

            font-weight:
                800;

            letter-spacing:
                0.08em;

            text-transform:
                uppercase;

        }


        .resource-name {

            margin-top:
                4px;

            color:
                var(--navy);

            font-size:
                clamp(
                    17px,
                    1.18vw,
                    24px
                );

            font-weight:
                800;

            line-height:
                1.2;

            white-space:
                normal;

            overflow-wrap:
                anywhere;

            word-break:
                break-word;

        }


        /* =========================================================
           EVENT + TIME
        ========================================================= */

        .schedule-event-block {

            min-width:
                0;

            display:
                flex;

            flex-direction:
                column;

            justify-content:
                flex-start;

        }


        .event-name {

            min-width:
                0;

            color:
                var(--navy-dark);

            font-size:
                clamp(
                    16px,
                    1.05vw,
                    22px
                );

            font-weight:
                750;

            line-height:
                1.3;

            white-space:
                normal;

            overflow:
                visible;

            overflow-wrap:
                anywhere;

            word-break:
                break-word;

        }


        .schedule-time {

            margin-top:
                7px;

            color:
                var(--muted);

            font-family:
                "Garuda Sans",
                Arial,
                sans-serif;

            font-size:
                clamp(
                    10px,
                    0.64vw,
                    14px
                );

            font-weight:
                650;

            line-height:
                1.2;

            white-space:
                nowrap;

        }


        /* =========================================================
           STATUS
        ========================================================= */

        .status-badge {

            width:
                fit-content;

            padding:
                5px
                10px;

            border-radius:
                999px;

            font-size:
                8px;

            font-weight:
                800;

            letter-spacing:
                0.06em;

            text-transform:
                uppercase;

            white-space:
                nowrap;

        }


        .status-badge.upcoming {

            background:
                var(--blue-bg);

            border:
                1px solid
                var(--blue-border);

            color:
                var(--blue-status);

        }


        .status-badge.current {

            background:
                var(--red-bg);

            border:
                1px solid
                var(--red-border);

            color:
                var(--red);

        }


        /* =========================================================
           EMPTY
        ========================================================= */

        .empty-state {

            width:
                100%;

            min-height:
                300px;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            text-align:
                center;

            padding:
                30px;

            color:
                var(--muted);

            font-size:
                15px;

            background:
                #ffffff;

            border:
                1px solid
                var(--border);

            border-radius:
                var(--radius-large);

        }


        /* =========================================================
           FOOTER
        ========================================================= */

        .detail-footer {

            flex:
                0 0
                4.5vh;

            min-height:
                32px;

            display:
                flex;

            align-items:
                center;

            justify-content:
                space-between;

            padding:
                0
                2.5vw;

            color:
                var(--muted);

            font-size:
                clamp(
                    9px,
                    0.60vw,
                    13px
                );

        }


        .footer-brand {

            font-weight:
                700;

            letter-spacing:
                0.12em;

        }


        .footer-right {

            display:
                flex;

            gap:
                8px;

        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 1000px) {

            .schedule-row {

                grid-template-columns:
                    34px
                    minmax(
                        150px,
                        0.85fr
                    )
                    minmax(
                        0,
                        1.15fr
                    );

                gap:
                    14px;

                padding:
                    14px
                16px;

            }


            .resource-name {

                font-size:
                    clamp(
                        15px,
                        1.8vw,
                        21px
                    );

            }


            .event-name {

                font-size:
                    clamp(
                        14px,
                        1.6vw,
                        20px
                    );

            }

        }


        @media (max-width: 800px) {

            .header-divider,
            .building-block,
            .logo-area {

                display:
                    none;

            }


            .page-header {

                align-items:
                    flex-start;

            }


            .schedule-row {

                grid-template-columns:
                    30px
                    minmax(
                        130px,
                        0.8fr
                    )
                    minmax(
                        0,
                        1.2fr
                    );

                gap:
                    12px;

            }


            .schedule-number {

                font-size:
                    11px;

            }


            .resource-name {

                font-size:
                    15px;

            }


            .event-name {

                font-size:
                    14px;

            }


            .schedule-time {

                font-size:
                    10px;

            }

        }


        @media (max-width: 600px) {

            .upcoming-header {

                flex-basis:
                    10vh;

                min-height:
                    78px;

            }


            .upcoming-main {

                padding:
                    2vw
                    2vw
                    0;

            }


            .page-header {

                flex-direction:
                    column;

            }


            .page-actions {

                width:
                    100%;

                justify-content:
                    flex-end;

            }


            .day-header {

                min-height:
                    50px;

            }


            .schedule-row {

                grid-template-columns:
                    24px
                    minmax(
                        95px,
                        0.75fr
                    )
                    minmax(
                        0,
                        1.25fr
                    );

                min-height:
                    78px;

                gap:
                    10px;

                padding:
                    11px
                    10px;

            }


            .schedule-number {

                font-size:
                    10px;

            }


            .resource-label {

                font-size:
                    7px;

            }


            .resource-name {

                font-size:
                    13px;

            }


            .event-name {

                font-size:
                    12px;

                line-height:
                    1.3;

            }


            .schedule-time {

                margin-top:
                    5px;

                font-size:
                    9px;

            }


            .footer-right {

                display:
                    none;

            }

        }


    </style>

</head>


<body>


<div class="upcoming-app">


    {{-- =========================================================
         HEADER
    ========================================================= --}}

    <header class="upcoming-header">


        <div class="header-left">


            <div class="brand-block">

                <div class="brand-title">
                    GITC Info
                </div>

                <div class="brand-subtitle">
                    Garuda Training System, Media & Business
                </div>

            </div>


            <div class="header-divider"></div>


            <div class="building-block">

                <div class="building-name">
                    {{ $building->name }}
                </div>

            </div>


        </div>


        <div class="header-right">


            <div class="datetime">

                <div
                    class="date"
                    id="date"
                >
                    ---
                </div>

                <div
                    class="clock"
                    id="clock"
                >
                    --:--:--
                </div>

            </div>


            <div class="logo-area">

                <img
                
                src="{{ asset('assets/icons/logo/logo.png') }}"
                alt="GITC Logo"
    
                    alt="Garuda Indonesia Training Center"
                >

            </div>


        </div>


    </header>



    {{-- =========================================================
         MAIN
    ========================================================= --}}

    <main class="upcoming-main">


        <div class="page-header">


            <div>

                <div class="page-eyebrow">
                    Classroom
                </div>


                <h1 class="page-title">
                    Upcoming Schedule
                </h1>


                <div
                    class="page-subtitle"
                    id="pageSubtitle"
                >
                    Loading upcoming schedule...
                </div>

            </div>


            <div class="page-actions">


                <input
                    type="text"
                    id="scheduleSearch"
                    class="schedule-search"
                    placeholder="Search room or event..."
                    autocomplete="off"
                >


                <a
                    href="{{ route('display', [
                        'buildingName' => $buildingName
                    ]) }}"
                    class="back-button"
                >
                    Back to Display
                </a>


            </div>


        </div>



        <div class="schedule-container">


            <div
                class="schedule-scroll"
                id="scheduleScroll"
            >

                <div class="empty-state">
                    Loading upcoming schedule...
                </div>

            </div>


        </div>


    </main>



    {{-- =========================================================
         FOOTER
    ========================================================= --}}

    <footer class="detail-footer">


        <div class="footer-brand">
            RESERVATION DISPLAY SYSTEM
        </div>


        <div class="footer-right">

            <span>Safety</span>

            <span>•</span>

            <span>Service</span>

            <span>•</span>

            <span>Excellence</span>

        </div>


    </footer>


</div>



<script>

    /* =========================================================
       DATA URL
    ========================================================= */

    const dataUrl = @json(
        route(
            'display.upcoming.data',
            [
                'buildingName' =>
                    $buildingName
            ]
        )
    );


    /* =========================================================
       STATE
    ========================================================= */

    let schedule = [];

    let searchKeyword = '';


    /* =========================================================
       CLOCK
    ========================================================= */

    function updateClock() {

        const now =
            new Date();


        const time =
            now.toLocaleTimeString(
                'en-GB',
                {
                    hour:
                        '2-digit',

                    minute:
                        '2-digit',

                    second:
                        '2-digit',

                    hour12:
                        false
                }
            );


        const date =
            now.toLocaleDateString(
                'en-GB',
                {
                    weekday:
                        'long',

                    day:
                        '2-digit',

                    month:
                        'long',

                    year:
                        'numeric'
                }
            );


        const clock =
            document.getElementById(
                'clock'
            );


        const dateElement =
            document.getElementById(
                'date'
            );


        if (clock) {

            clock.textContent =
                `${time} WIB`;

        }


        if (dateElement) {

            dateElement.textContent =
                date;

        }

    }


    updateClock();


    setInterval(
        updateClock,
        1000
    );


    /* =========================================================
       HELPERS
    ========================================================= */

    function escapeHtml(
        value
    ) {

        if (
            value === null ||
            value === undefined
        ) {

            return '';

        }


        return String(value)
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


    function formatTime(
        value
    ) {

        if (!value) {

            return '—';

        }


        const date =
            new Date(value);


        if (
            Number.isNaN(
                date.getTime()
            )
        ) {

            return '—';

        }


        return date.toLocaleTimeString(
            'en-GB',
            {
                hour:
                    '2-digit',

                minute:
                    '2-digit',

                hour12:
                    false
            }
        );

    }


    /* =========================================================
       RENDER
    ========================================================= */

    function renderSchedule() {

        const container =
            document.getElementById(
                'scheduleScroll'
            );


        const subtitle =
            document.getElementById(
                'pageSubtitle'
            );


        if (!container) {

            return;

        }


        const keyword =
            searchKeyword
                .trim()
                .toLowerCase();


        /*
         * Filter setiap hari secara terpisah.
         * Hari tanpa hasil otomatis disembunyikan.
         */

        const filteredSchedule =
            schedule
                .map(
                    day => {

                        const filteredReservations =
                            day.reservations.filter(
                                reservation => {

                                    if (!keyword) {

                                        return true;

                                    }


                                    const event =
                                        String(
                                            reservation
                                                ?.event_name
                                            ??
                                            ''
                                        )
                                        .toLowerCase();


                                    const resource =
                                        String(
                                            reservation
                                                ?.resource
                                                ?.name
                                            ??
                                            ''
                                        )
                                        .toLowerCase();


                                    const type =
                                        String(
                                            reservation
                                                ?.resource
                                                ?.type
                                            ??
                                            ''
                                        )
                                        .toLowerCase();


                                    const booker =
                                        String(
                                            reservation
                                                ?.booker_name
                                            ??
                                            ''
                                        )
                                        .toLowerCase();


                                    return (
                                        event.includes(
                                            keyword
                                        )
                                        ||
                                        resource.includes(
                                            keyword
                                        )
                                        ||
                                        type.includes(
                                            keyword
                                        )
                                        ||
                                        booker.includes(
                                            keyword
                                        )
                                    );

                                }
                            );


                        return {
                            ...day,
                            reservations:
                                filteredReservations
                        };

                    }
                )
                .filter(
                    day =>
                        day.reservations.length >
                        0
                );


        /*
         * =========================
         * SUMMARY
         * =========================
         */

        const totalReservations =
            filteredSchedule.reduce(
                (
                    total,
                    day
                ) => {

                    return total +
                        day.reservations.length;

                },
                0
            );


        if (subtitle) {

            if (!keyword) {

                const total =
                    schedule.reduce(
                        (
                            total,
                            day
                        ) => {

                            return total +
                                day.reservations.length;

                        },
                        0
                    );


                subtitle.textContent =
                    `${schedule.length} ${
                        schedule.length === 1
                            ? 'upcoming day'
                            : 'upcoming days'
                    } · ${total} ${
                        total === 1
                            ? 'reservation'
                            : 'reservations'
                    }`;

            } else {

                subtitle.textContent =
                    `${totalReservations} ${
                        totalReservations === 1
                            ? 'matching reservation'
                            : 'matching reservations'
                    }`;

            }

        }


        /*
         * =========================
         * NO RESULT
         * =========================
         */

        if (
            filteredSchedule.length ===
            0
        ) {

            container.innerHTML = `

                <div class="empty-state">

                    ${
                        keyword
                            ? 'No matching reservations found.'
                            : 'No upcoming reservations for the next 7 days.'
                    }

                </div>

            `;

            return;

        }


        /*
         * =========================
         * RENDER DAYS
         * =========================
         */

        container.innerHTML =
            filteredSchedule
                .map(
                    day => {

                        const reservations =
                            day.reservations;


                        return `

                            <section
                                class="day-section"
                            >

                                <div
                                    class="day-header"
                                >

                                    <div
                                        class="day-title"
                                    >

                                        ${escapeHtml(
                                            day.formatted_date
                                        )}

                                    </div>


                                   

                                </div>


                                <div
                                    class="day-list"
                                >

                                    ${
                                        reservations
                                            .map(
                                                (
                                                    reservation,
                                                    index
                                                ) => {

                                                    const resourceType =
                                                        reservation
                                                            ?.resource
                                                            ?.type
                                                        ??
                                                        'ROOM';


                                                    const resourceName =
                                                        reservation
                                                            ?.resource
                                                            ?.name
                                                        ??
                                                        '—';


                                                    const eventName =
                                                        reservation
                                                            ?.event_name
                                                        ??
                                                        'Untitled Event';


                                                    return `

                                                        <div
                                                            class="
                                                                schedule-row
                                                            "
                                                        >

                                                            <div
                                                                class="
                                                                    schedule-number
                                                                "
                                                            >

                                                                ${String(
                                                                    index + 1
                                                                ).padStart(
                                                                    2,
                                                                    '0'
                                                                )}

                                                            </div>


                                                            <div
                                                                class="
                                                                    schedule-resource
                                                                "
                                                            >

                                                                <div
                                                                    class="
                                                                        resource-label
                                                                    "
                                                                >

                                                                    ${escapeHtml(
                                                                        resourceType
                                                                    )}

                                                                </div>


                                                                <div
                                                                    class="
                                                                        resource-name
                                                                    "
                                                                >

                                                                    ${escapeHtml(
                                                                        resourceName
                                                                    )}

                                                                </div>

                                                            </div>


                                                            <div
                                                                class="
                                                                    schedule-event-block
                                                                "
                                                            >

                                                                <div
                                                                    class="
                                                                        event-name
                                                                    "
                                                                >

                                                                    ${escapeHtml(
                                                                        eventName
                                                                    )}

                                                                </div>


                                                                <div
                                                                    class="
                                                                        schedule-time
                                                                    "
                                                                >

                                                                    ${formatTime(
                                                                        reservation
                                                                            .starts_at
                                                                    )}

                                                                    -

                                                                    ${formatTime(
                                                                        reservation
                                                                            .ends_at
                                                                    )}

                                                                </div>

                                                            </div>

                                                        </div>

                                                    `;

                                                }
                                            )
                                            .join('')
                                    }

                                </div>

                            </section>

                        `;

                    }
                )
                .join('');

    }


    /* =========================================================
       LOAD DATA
    ========================================================= */

    async function loadSchedule() {

        try {

            const response =
                await fetch(
                    dataUrl,
                    {
                        cache:
                            'no-store',

                        headers: {
                            'Accept':
                                'application/json'
                        }
                    }
                );


            if (!response.ok) {

                throw new Error(
                    `API error: ${response.status}`
                );

            }


            const data =
                await response.json();


            schedule =
                data.schedule
                ??
                [];


            renderSchedule();


        } catch (error) {

            console.error(
                'Failed to load upcoming schedule:',
                error
            );


            const container =
                document.getElementById(
                    'scheduleScroll'
                );


            if (container) {

                container.innerHTML = `

                    <div class="empty-state">

                        Unable to load upcoming schedule.

                    </div>

                `;

            }

        }

    }


    /* =========================================================
       SEARCH
    ========================================================= */

    const searchInput =
        document.getElementById(
            'scheduleSearch'
        );


    if (searchInput) {

        searchInput.addEventListener(
            'input',
            function () {

                searchKeyword =
                    this.value
                        .trim()
                        .toLowerCase();


                renderSchedule();

            }
        );

    }


    /* =========================================================
       INITIAL LOAD
    ========================================================= */

    loadSchedule();


    /* =========================================================
       AUTO REFRESH
       60 SECONDS
    ========================================================= */

    setInterval(
        loadSchedule,
        60000
    );

</script>


</body>

</html>