
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/icons/logo/Garuda.svg') }}">
    <title>Training Media Schedule - {{ $buildingName }}</title>

    <style>
        @font-face {
            font-family: 'Garuda Sans';
            src: url('{{ asset('fonts/Garuda_Font/Sans/GarudaSans-Regular.ttf') }}') format('truetype');
            font-weight: 400;
            font-display: swap;
        }

        @font-face {
            font-family: 'Garuda Sans';
            src: url('{{ asset('fonts/Garuda_Font/Sans/GarudaSans-SemiBold.ttf') }}') format('truetype');
            font-weight: 600;
            font-display: swap;
        }

        @font-face {
            font-family: 'Garuda Sans';
            src: url('{{ asset('fonts/Garuda_Font/Sans/GarudaSans-Bold.ttf') }}') format('truetype');
            font-weight: 700;
            font-display: swap;
        }

        @font-face {
            font-family: 'Garuda Sans';
            src: url('{{ asset('fonts/Garuda_Font/Sans/GarudaSans-ExtraBold.ttf') }}') format('truetype');
            font-weight: 800;
            font-display: swap;
        }

        @font-face {
            font-family: 'Garuda Serif';
            src: url('{{ asset('fonts/Garuda_Font/Serif/GarudaSerif-Regular.otf') }}') format('opentype');
            font-weight: 400;
            font-display: swap;
        }

        @font-face {
            font-family: 'Garuda Serif';
            src: url('{{ asset('fonts/Garuda_Font/Serif/GarudaSerif-Bold.otf') }}') format('opentype');
            font-weight: 700;
            font-display: swap;
        }

        @font-face {
            font-family: 'Garuda Serif';
            src: url('{{ asset('fonts/Garuda_Font/Serif/GarudaSerif-ExtraBold.otf') }}') format('opentype');
            font-weight: 800;
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
            --radius-large: 18px;
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
            font-family: "Garuda Sans", Arial, sans-serif;
            background: var(--background);
            color: var(--text);
        }

        body {
            min-height: 100vh;
        }

        .training-app {
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* HEADER */

        .upcoming-header {
            flex: 0 0 13vh;
            min-height: 95px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            padding: 0 3vw;
            color: #ffffff;
            background:
                radial-gradient(
                    circle at 78% 15%,
                    rgba(0, 168, 200, 0.20),
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
            border: 1px solid rgba(255, 255, 255, 0.06);
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

        .brand-block,
        .building-block {
            min-width: 0;
        }

        .brand-title {
            font-size: clamp(22px, 1.65vw, 34px);
            font-weight: 800;
            line-height: 1;
        }

        .brand-subtitle {
            margin-top: 6px;
            color: rgba(255, 255, 255, 0.70);
            font-size: clamp(11px, 0.82vw, 16px);
        }

        .header-divider {
            width: 1px;
            height: 54px;
            flex: 0 0 1px;
            margin: 0 0.7vw;
            background: rgba(255, 255, 255, 0.24);
        }

        .building-name {
            max-width: 34vw;
            
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: clamp(21px, 1.5vw, 31px);
            font-weight: 800;
        }

        .header-right {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 1.5vw;
        }

        .datetime {
            text-align: right;
        }

        .date {
            color: rgba(255, 255, 255, 0.80);
            font-size: clamp(11px, 0.76vw, 16px);
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
            width: clamp(155px, 14vw, 270px);
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px 15px;
            border-left: 1px solid rgba(255, 255, 255, 0.18);
        }

        .logo-area img {
            display: block;
            max-width: 100%;
            max-height: 54px;
            object-fit: contain;
        }

        /* MAIN */

        .upcoming-main {
            flex: 1;
            min-height: 0;
            display: flex;
            flex-direction: column;
            padding: 1.3vw 2.2vw 0;
            overflow: hidden;
        }

        .page-header {
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 1vw;
        }

        .page-eyebrow {
            color: var(--cyan);
            font-size: clamp(10px, 0.66vw, 14px);
            font-weight: 800;
            letter-spacing: 0.10em;
            text-transform: uppercase;
        }

        .page-title {
            margin: 5px 0 0;
            color: var(--navy-dark);
            font-size: clamp(28px, 2vw, 40px);
            font-weight: 800;
            line-height: 1.08;
        }

        .page-subtitle {
            margin-top: 5px;
            color: var(--muted);
            font-size: clamp(11px, 0.76vw, 15px);
        }

        .page-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .back-button {
            min-width: 160px;
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 18px;
            border: 1px solid #cbdce6;
            border-radius: 999px;
            background: #ffffff;
            color: #06466d;
            font-family: "Garuda Sans", Arial, sans-serif;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-decoration: none;
            text-transform: uppercase;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .back-button:hover {
            background: #f1f8fb;
            transform: translateY(-1px);
        }

        /* SCHEDULE CARD */

        .schedule-container {
            flex: 1;
            min-height: 0;
            padding-bottom: 0.5vw;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .schedule-card {
            width: 100%;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: var(--radius-large);
            background: var(--white);
            box-shadow: 0 4px 12px rgba(0, 41, 79, 0.05);
        }

        .schedule-card-header {
            min-height: 58px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 10px 18px;
            background: #f9fbfc;
            border-bottom: 1px solid #e5edf2;
        }

        .schedule-heading {
            min-width: 0;
        }

        .schedule-heading h2 {
            margin: 0;
            color: var(--navy-dark);
            font-size: clamp(16px, 1.1vw, 23px);
            font-weight: 800;
        }

        .schedule-heading p {
            margin: 3px 0 0;
            color: var(--muted);
            font-size: clamp(10px, 0.68vw, 14px);
        }

        .schedule-date {
            flex: 0 0 auto;
            padding: 8px 13px;
            border: 1px solid var(--border);
            border-radius: 999px;
            background: #ffffff;
            color: var(--navy);
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        /* TABLE */

        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        thead {
            background: #f5f9fc;
        }

        th {
            padding: 10px 18px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        td {
            padding: 11px 18px;
            border-top: 1px solid #edf1f4;
            color: var(--text);
            font-size: 14px;
            vertical-align: middle;
        }

        tbody tr {
            transition: background 0.15s ease;
        }

        tbody tr:hover {
            background: #f7fcfe;
        }

        .time-range {
            min-width: 145px;
            color: var(--navy);
            font-size: clamp(17px, 1.2vw, 24px);
            font-weight: 800;
            white-space: nowrap;
        }

        .time-label {
            display: block;
            margin-top: 2px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 400;
        }

        .event-name {
            color: var(--navy-dark);
            font-size: clamp(15px, 1vw, 20px);
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .group-name {
            color: var(--navy);
            font-size: clamp(14px, 0.95vw, 18px);
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .program-name {
            display: block;
            margin-top: 3px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 400;
        }

        .media-badge {
            display: inline-flex;
            align-items: center;
            padding: 0;
            border: none;
            border-radius: 0;
            background: transparent;
            color: var(--navy-dark);
            font-size: clamp(15px, 1vw, 20px);
            font-weight: 800;
            white-space: normal;
            overflow-wrap: anywhere;
        }

        .media-dot {
            display: none;
        }

        /* EMPTY STATE */

        .empty-state {
            width: 100%;
            min-height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 30px;
            text-align: center;
            background: #ffffff;
        }

        .empty-icon {
            width: 58px;
            height: 58px;
            display: grid;
            place-items: center;
            margin-bottom: 14px;
            border: 1px solid #c9edf4;
            border-radius: 16px;
            background: #dff7fb;
            color: var(--navy);
            font-size: 25px;
        }

        .empty-state h3 {
            margin: 0;
            color: var(--navy);
            font-size: 18px;
            font-weight: 800;
        }

        .empty-state p {
            max-width: 420px;
            margin: 9px 0 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        /* FOOTER */

        .detail-footer {
            flex: 0 0 4.5vh;
            min-height: 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 0 2.5vw;
            color: var(--muted);
            font-size: clamp(9px, 0.60vw, 13px);
        }

        .footer-brand {
            font-weight: 700;
            letter-spacing: 0.12em;
        }

        .footer-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* TABLET / SMALLER DESKTOP */

        @media (max-width: 1000px) {
            .schedule-card-header {
                padding: 12px 16px;
            }

            th,
            td {
                padding: 12px 14px;
            }

            .media-badge {
                font-size: 14px;
            }
        }

        /* MOBILE */

        @media (max-width: 800px) {
            html,
            body {
                height: auto;
                min-height: 100%;
            }

            .training-app {
                height: auto;
                min-height: 100vh;
                overflow: visible;
            }

            .upcoming-header {
                flex-basis: auto;
                min-height: 82px;
                padding: 16px 20px;
            }

            .header-divider,
            .building-block,
            .logo-area {
                display: none;
            }

            .header-right {
                gap: 0;
            }

            .clock {
                font-size: 23px;
            }

            .upcoming-main {
                overflow: visible;
                padding: 16px 14px 0;
            }

            .page-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 12px;
                margin-bottom: 14px;
            }

            .page-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .back-button {
                min-width: 0;
                padding: 0 15px;
            }

            .schedule-container {
                overflow: visible;
                padding-bottom: 14px;
            }

            .schedule-card-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 10px;
                padding: 14px;
            }

            .table-wrap {
                overflow-x: auto;
            }

            table {
                min-width: 720px;
            }

            th,
            td {
                padding: 12px;
            }

            .time-range {
                min-width: 125px;
                font-size: 16px;
            }

            .event-name {
                font-size: 15px;
            }

            .group-name {
                font-size: 14px;
            }

            .media-badge {
                font-size: 14px;
            }

            .detail-footer {
                min-height: 36px;
                padding: 10px 16px;
            }

            .footer-right {
                display: none;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                transition-duration: 0.01ms !important;
                animation-duration: 0.01ms !important;
            }
        }

        .schedule-container {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .schedule-container::-webkit-scrollbar {
            display: none;
        }

        .table-wrap {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .table-wrap::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body>
    <div class="training-app">

        {{-- HEADER --}}
        <header class="upcoming-header">
            <div class="header-left">
                <div class="brand-block">
                    <div class="brand-title">GITC Info</div>
                    <div class="brand-subtitle">
                        Garuda Training System, Media &amp; Business
                    </div>
                </div>

                <div class="header-divider"></div>

                <div class="building-block">
                    <div class="building-name">{{ $building->name }}</div>
                </div>
            </div>

            <div class="header-right">
                <div class="datetime">
                    <div class="date" id="date">---</div>
                    <div class="clock" id="clock">--:--:--</div>
                </div>

                <div class="logo-area">
                    <img
                        src="{{ asset('assets/icons/logo/logo.png') }}"
                        alt="GITC Logo"
                    >
                </div>
            </div>
        </header>

        {{-- MAIN --}}
        <main class="upcoming-main">
            <div class="page-header">
                <div>
                    <div class="page-eyebrow">
                        Training &amp; Development
                    </div>

                    <h1 class="page-title">
                        Training Media Schedule
                    </h1>

                    <div class="page-subtitle">
                        View today's events and their assigned training media.
                    </div>
                </div>

                <div class="page-actions">
                    <a
                        href="{{ route('display', ['buildingName' => $buildingName]) }}"
                        class="back-button"
                    >
                        &larr; Back to Display
                    </a>
                </div>
            </div>

            <div class="schedule-container">
                <section class="schedule-card">
                    <div class="schedule-card-header">
                        <div class="schedule-heading">
                            <h2>Today's Schedule</h2>
                            <p>Daily event overview</p>
                        </div>

                        <div class="schedule-date">
                            {{ $scheduleDate->format('l, d F Y') }}
                        </div>
                    </div>

                    @if ($sessions->isNotEmpty())
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Event</th>
                                        <th>Group</th>
                                        <th>Training Media</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($sessions as $session)
                                        <tr>
                                            {{-- TIME --}}
                                            <td>
                                                <div class="time-range">
                                                    {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}
                                                    &ndash;
                                                    {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}

                                                    <span class="time-label">WIB</span>
                                                </div>
                                            </td>

                                            {{-- EVENT --}}
                                            <td>
                                                <div class="event-name">
                                                    {{ $session->subject ?: ($session->program->program_name ?? 'Training Event') }}
                                                </div>

                                                @if ($session->program)
                                                    <span class="program-name">
                                                        {{ $session->program->program_name }}
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- GROUP --}}
                                            <td>
                                                <div class="group-name">
                                                    {{ $session->class_name ?: '—' }}
                                                </div>
                                            </td>

                                            {{-- TRAINING MEDIA --}}
                                            <td>
                                                <span class="media-badge">
                                                    {{ $session->trainingRoom->name ?? '—' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon" aria-hidden="true">
                                &#128197;
                            </div>

                            <h3>No Events scheduled for this day.</h3>

                            <p>
                                There are currently no events scheduled
                                for {{ $building->name }} today.
                            </p>
                        </div>
                    @endif
                </section>
            </div>
        </main>

        {{-- FOOTER --}}
        <footer class="detail-footer">
            <div class="footer-brand">
                Garuda Indonesia Training Center
            </div>

            <div class="footer-right">
                <span>Safety</span>
                <span>&bull;</span>
                <span>Service</span>
                <span>&bull;</span>
                <span>Excellence</span>
            </div>
        </footer>
    </div>

    <script>
        /*
         * Clock uses Jakarta time.
         */
        function updateClock() {
            const now = new Date();

            const time = now.toLocaleTimeString('en-GB', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
                timeZone: 'Asia/Jakarta'
            });

            const date = now.toLocaleDateString('en-GB', {
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                timeZone: 'Asia/Jakarta'
            });

            const clockElement = document.getElementById('clock');
            const dateElement = document.getElementById('date');

            if (clockElement) {
                clockElement.textContent = `${time} WIB`;
            }

            if (dateElement) {
                dateElement.textContent = date;
            }
        }

        updateClock();
        setInterval(updateClock, 1000);

        /*
         * Automatically refresh the page every 60 seconds
         * so the schedule can reflect updated server data.
         */
        const AUTO_REFRESH_INTERVAL = 60 * 1000;

        setTimeout(() => {
            window.location.reload();
        }, AUTO_REFRESH_INTERVAL);
    </script>
</body>
</html>
