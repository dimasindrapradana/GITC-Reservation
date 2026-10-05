<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Detail Schedule - {{ $buildingName }}
    </title>


    <style>

        /* =========================================================
           ROOT
        ========================================================= */

        :root {

            --navy-dark: #00294f;
            --navy: #003b6f;
            --blue: #006fae;
            --cyan: #00a8c8;
            --cyan-light: #dff7fb;

            --background: #f3f7fa;
            --white: #ffffff;

            --text: #12304a;
            --muted: #668096;

            --border: #d9e5ed;

            --red: #df4545;
            --red-bg: #fff0f0;
            --red-border: #ffd0d0;

            --green: #148a72;
            --green-bg: #eefaf7;
            --green-border: #bfe8dd;

            --blue-status: #007bb5;
            --blue-bg: #eefaff;
            --blue-border: #aee6fb;

            --gray: #7b8c97;
            --gray-bg: #f3f6f8;
            --gray-border: #dce4e9;

            --radius-large: 18px;
            --radius-medium: 12px;

        }


        /* =========================================================
           RESET
        ========================================================= */

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
                "Segoe UI",
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

        .detail-app {

            width: 100vw;
            height: 100vh;

            display: flex;

            flex-direction: column;

            overflow: hidden;

        }


        /* =========================================================
           HEADER
        ========================================================= */

        .detail-header {

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


        .detail-header::after {

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


        /* =========================================================
           HEADER LEFT
        ========================================================= */

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

            flex:
                0 0
                1px;

        }


        .building-block {

            min-width:
                0;

        }


        .building-label {

            color:
                rgba(
                    255,
                    255,
                    255,
                    0.55
                );

            font-size:
                10px;

            font-weight:
                700;

            letter-spacing:
                0.10em;

            text-transform:
                uppercase;

        }


        .building-name {

            margin-top:
                5px;

            max-width:
                34vw;

            overflow:
                hidden;

            text-overflow:
                clip;

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


        /* =========================================================
           HEADER RIGHT
        ========================================================= */

        .header-right {

            position: relative;

            z-index: 2;

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

            font-weight:
                500;

        }


        .clock {

            margin-top:
                4px;

            color:
                #69e5f5;

            font-family:
                "Segoe UI",
                Arial,
                sans-serif;

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

        .detail-main {

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
                0 0
                auto;

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


        .page-heading {

            min-width:
                0;

        }


        .page-eyebrow {

            color:
                var(--cyan);

            font-family:
                "Segoe UI",
                Arial,
                sans-serif;

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
                    2.0vw,
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

            font-weight:
                500;

        }


        /* =========================================================
           PAGE ACTIONS
        ========================================================= */

        .page-actions {

            display:
                flex;

            align-items:
                center;

            gap:
                10px;

            flex-shrink:
                0;

        }


        .schedule-search,
        .back-button {

            height:
                44px;

            min-height:
                44px;

            border:
                1px solid
                #cbdce6;

            border-radius:
                999px;

            font-family:
                "Segoe UI",
                Arial,
                sans-serif;

            font-size:
                12px;

            font-weight:
                700;

        }


        .schedule-search {

            width:
                clamp(
                    180px,
                    14vw,
                    240px
                );

            padding:
                0
                16px;

            outline:
                none;

            background:
                #ffffff;

            color:
                var(--text);

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

            background:
                #ffffff;

            color:
                #06466d;

            text-decoration:
                none;

            letter-spacing:
                0.04em;

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
           SCHEDULE CONTAINER
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


        .schedule-grid {

            width:
                100%;

            height:
                100%;

            display:
                grid;

            grid-template-columns:
                repeat(
                    2,
                    minmax(
                        0,
                        1fr
                    )
                );

            grid-auto-rows:
                minmax(
                    98px,
                    1fr
                );

            gap:
                12px
                14px;

            align-content:
                start;

            overflow-y:
                auto;

            overflow-x:
                hidden;

            padding-right:
                4px;

            scrollbar-width:
                thin;

        }


        /* =========================================================
           SCHEDULE CARD
        ========================================================= */

        .schedule-card {

            min-width:
                0;

            min-height:
                98px;

            display:
                grid;

            grid-template-columns:
                42px
                122px
                minmax(
                    0,
                    1fr
                );

            gap:
                12px;

            align-items:
                center;

            padding:
                12px
                15px;

            background:
                #ffffff;

            border:
                1px solid
                var(--border);

            border-radius:
                var(--radius-medium);

            box-shadow:
                0
                4px
                12px
                rgba(
                    0,
                    41,
                    79,
                    0.06
                );

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;

        }


        .schedule-card:hover {

            transform:
                translateY(
                    -1px
                );

            box-shadow:
                0
                7px
                16px
                rgba(
                    0,
                    41,
                    79,
                    0.08
                );

        }


        .schedule-card.current {

            border:
                2px solid
                rgba(
                    0,
                    168,
                    200,
                    0.50
                );

            background:
                #fbfeff;

        }


        /* =========================================================
           NUMBER
        ========================================================= */

        .schedule-number {

            color:
                rgba(
                    0,
                    59,
                    111,
                    0.35
                );

            font-family:
                "Segoe UI",
                Arial,
                sans-serif;

            font-size:
                15px;

            font-weight:
                800;

        }


        /* =========================================================
           TIME
        ========================================================= */

        .schedule-time {

            min-width:
                0;

        }


        .time-main {

            color:
                var(--navy);

            font-family:
                "Segoe UI",
                Arial,
                sans-serif;

            font-size:
                clamp(
                    14px,
                    0.95vw,
                    19px
                );

            font-weight:
                800;

            white-space:
                nowrap;

        }


        .time-label {

            margin-top:
                4px;

            color:
                var(--muted);

            font-size:
                9px;

            font-weight:
                700;

            letter-spacing:
                0.08em;

            text-transform:
                uppercase;

        }


        /* =========================================================
           INFO
        ========================================================= */

        .schedule-info {

            min-width:
                0;

        }


        .schedule-resource-line {

            display:
                flex;

            align-items:
                center;

            gap:
                8px;

            min-width:
                0;

        }


        .resource-type {

            flex:
                0 0 auto;

            color:
                var(--muted);

            font-size:
                8px;

            font-weight:
                800;

            letter-spacing:
                0.10em;

            text-transform:
                uppercase;

        }


        .resource-name {

            min-width:
                0;

            overflow:
                hidden;

            text-overflow:
                ellipsis;

            white-space:
                nowrap;

            color:
                var(--navy);

            font-size:
                clamp(
                    15px,
                    1.0vw,
                    20px
                );

            font-weight:
                800;

        }


        .event-name {

            margin-top:
                6px;

            color:
                var(--text);

            font-size:
                clamp(
                    12px,
                    0.76vw,
                    16px
                );

            font-weight:
                650;

            line-height:
                1.25;

            display:
                -webkit-box;

            -webkit-line-clamp:
                2;

            -webkit-box-orient:
                vertical;

            overflow:
                hidden;

        }


        /* =========================================================
           STATUS
        ========================================================= */

        .status-badge {

            width:
                fit-content;

            margin-top:
                6px;

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


        .status-badge.current {

            background:
                var(--red-bg);

            border:
                1px solid
                var(--red-border);

            color:
                var(--red);

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


        .status-badge.completed {

            background:
                var(--gray-bg);

            border:
                1px solid
                var(--gray-border);

            color:
                var(--gray);

        }


        /* =========================================================
           EMPTY
        ========================================================= */

        .empty-state {

            width:
                100%;

            min-height:
                260px;

            height:
                100%;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            text-align:
                center;

            padding:
                25px;

            color:
                var(--muted);

            font-size:
                15px;

            font-weight:
                500;

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

            align-items:
                center;

            gap:
                8px;

        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 1100px) {

            .schedule-card {

                grid-template-columns:
                    36px
                    110px
                    minmax(
                        0,
                        1fr
                    );

            }

            .back-button {

                min-width:
                    145px;

            }

        }


        @media (max-width: 850px) {

            .page-header {

                align-items:
                    flex-start;

            }


            .page-actions {

                flex-direction:
                    column;

                align-items:
                    flex-end;

            }


            .schedule-search {

                width:
                    200px;

            }


            .schedule-grid {

                grid-template-columns:
                    1fr;

            }

        }


        @media (max-width: 650px) {

            .detail-header {

                flex-basis:
                    10vh;

                min-height:
                    78px;

                padding:
                    0
                    3vw;

            }


            .header-divider,
            .building-block,
            .logo-area {

                display:
                    none;

            }


            .detail-main {

                padding:
                    2vw
                    2vw
                    0;

            }


            .page-header {

                flex-direction:
                    column;

                gap:
                    12px;

            }


            .page-actions {

                width:
                    100%;

                flex-direction:
                    row;

                align-items:
                    center;

            }


            .schedule-search {

                flex:
                    1;

                width:
                    auto;

            }


            .back-button {

                min-width:
                    145px;

            }


            .schedule-card {

                grid-template-columns:
                    34px
                    105px
                    minmax(
                        0,
                        1fr
                    );

            }


            .footer-right {

                display:
                    none;

            }

        }


        @media (max-height: 700px) {

            .detail-header {

                flex-basis:
                    11vh;

            }


            .page-title {

                font-size:
                    clamp(
                        24px,
                        1.75vw,
                        35px
                    );

            }


            .page-subtitle {

                margin-top:
                    4px;

            }


            .schedule-grid {

                grid-auto-rows:
                    minmax(
                        88px,
                        1fr
                    );

                gap:
                    9px
                    11px;

            }


            .schedule-card {

                min-height:
                    88px;

                padding:
                    10px
                    12px;

            }


            .detail-footer {

                flex-basis:
                    3.8vh;

            }

        }

    </style>

</head>


<body>


<div class="detail-app">


    {{-- =========================================================
         HEADER
    ========================================================= --}}

    <header class="detail-header">


        <div class="header-left">


            <div class="brand-block">

                <div class="brand-title">
                    GITC Info
                </div>

                <div class="brand-subtitle">
                    Garuda Training & Classroom System
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

    <main class="detail-main">


        <div class="page-header">


            <div class="page-heading">

                <div class="page-eyebrow">
                    Classroom
                </div>

                <h1 class="page-title">
                    Today's Full Schedule
                </h1>

                <div
                    class="page-subtitle"
                    id="pageSubtitle"
                >
                    Loading schedule...
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
                class="schedule-grid"
                id="scheduleGrid"
            >

                <div class="empty-state">
                    Loading schedule...
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
            'display.data',
            [
                'buildingName' => $buildingName
            ]
        )
    );


    /* =========================================================
       STATE
    ========================================================= */

    let reservations = [];

    let searchKeyword = '';


    /* =========================================================
       CLOCK
    ========================================================= */

    function updateClock() {

        const now = new Date();


        const time =
            now.toLocaleTimeString(
                'en-GB',
                {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                }
            );


        const date =
            now.toLocaleDateString(
                'en-GB',
                {
                    weekday: 'long',
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
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

    function escapeHtml(value) {

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


    function getResourceName(
        reservation
    ) {

        return (
            reservation?.resource?.name
            ??
            '—'
        );

    }


    function getResourceType(
        reservation
    ) {

        return (
            reservation?.resource?.type
            ??
            'ROOM'
        );

    }


    function getEventName(
        reservation
    ) {

        return (
            reservation?.event_name
            ??
            'Untitled Event'
        );

    }


    function getStatus(
        reservation
    ) {

        const now =
            new Date();


        const start =
            new Date(
                reservation.starts_at
            );


        const end =
            new Date(
                reservation.ends_at
            );


        if (
            start <= now &&
            end >= now
        ) {

            return 'IN PROGRESS';

        }


        if (
            start > now
        ) {

            return 'UPCOMING';

        }


        return 'COMPLETED';

    }


    function getStatusClass(
        status
    ) {

        if (
            status === 'IN PROGRESS'
        ) {

            return 'current';

        }


        if (
            status === 'UPCOMING'
        ) {

            return 'upcoming';

        }


        return 'completed';

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
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            }
        );

    }


    /* =========================================================
       RENDER
    ========================================================= */

    function renderSchedule() {

        const grid =
            document.getElementById(
                'scheduleGrid'
            );


        const subtitle =
            document.getElementById(
                'pageSubtitle'
            );


        if (!grid) {

            return;

        }


        const keyword =
            searchKeyword
                .trim()
                .toLowerCase();


        const filtered =
            reservations.filter(
                reservation => {

                    if (!keyword) {

                        return true;

                    }


                    const room =
                        getResourceName(
                            reservation
                        )
                        .toLowerCase();


                    const event =
                        getEventName(
                            reservation
                        )
                        .toLowerCase();


                    const type =
                        getResourceType(
                            reservation
                        )
                        .toLowerCase();


                    const booker =
                        String(
                            reservation.booker_name
                            ?? ''
                        )
                        .toLowerCase();


                    return (
                        room.includes(
                            keyword
                        )
                        ||
                        event.includes(
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


        if (subtitle) {

            const total =
                reservations.length;


            subtitle.textContent =
                keyword
                    ? `${filtered.length} matching reservations · ${total} total today`
                    : `${total} ${
                        total === 1
                            ? 'reservation'
                            : 'reservations'
                    } scheduled today`;

        }


        if (
            filtered.length === 0
        ) {

            grid.innerHTML = `

                <div class="empty-state">

                    ${
                        keyword
                            ? 'No matching reservations found.'
                            : 'There are no events scheduled for today.'
                    }

                </div>

            `;

            return;

        }


        grid.innerHTML =
            filtered
                .map(
                    (
                        reservation,
                        index
                    ) => {

                        const status =
                            getStatus(
                                reservation
                            );


                        const statusClass =
                            getStatusClass(
                                status
                            );


                        return `

                            <article
                                class="
                                    schedule-card
                                    ${
                                        status ===
                                        'IN PROGRESS'
                                            ? 'current'
                                            : ''
                                    }
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
                                        schedule-time
                                    "
                                >

                                    <div
                                        class="
                                            time-main
                                        "
                                    >

                                        ${formatTime(
                                            reservation.starts_at
                                        )}

                                        -

                                        ${formatTime(
                                            reservation.ends_at
                                        )}

                                    </div>


                                    <div
                                        class="
                                            time-label
                                        "
                                    >

                                        WIB

                                    </div>

                                </div>


                                <div
                                    class="
                                        schedule-info
                                    "
                                >


                                    <div
                                        class="
                                            schedule-resource-line
                                        "
                                    >

                                        <span
                                            class="
                                                resource-type
                                            "
                                        >

                                            ${escapeHtml(
                                                getResourceType(
                                                    reservation
                                                )
                                            )}

                                        </span>


                                        <span
                                            class="
                                                resource-name
                                            "
                                        >

                                            ${escapeHtml(
                                                getResourceName(
                                                    reservation
                                                )
                                            )}

                                        </span>

                                    </div>


                                    <div
                                        class="
                                            event-name
                                        "
                                    >

                                        ${escapeHtml(
                                            getEventName(
                                                reservation
                                            )
                                        )}

                                    </div>


                                    <div
                                        class="
                                            status-badge
                                            ${statusClass}
                                        "
                                    >

                                        ${status}

                                    </div>


                                </div>


                            </article>

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


            reservations =
                data.today_schedule
                ??
                data.reservations
                ??
                [];


            renderSchedule();


        } catch (error) {

            console.error(
                'Failed to load schedule:',
                error
            );


            const grid =
                document.getElementById(
                    'scheduleGrid'
                );


            if (grid) {

                grid.innerHTML = `

                    <div class="empty-state">

                        Unable to load today's schedule.

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