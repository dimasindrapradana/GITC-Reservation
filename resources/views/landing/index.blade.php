<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="icon" type="image/svg+xml" href="{{ asset('assets/icons/logo/Garuda.svg') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GITC Info | Reservation Portal</title>

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
            --navy: #102b46;
            --navy-dark: #0a1d31;
            --navy-light: #1c4263;
            --teal: #168c8c;
            --teal-light: #e7f5f4;
            --gold: #d8b46a;
            --gold-light: #f7efd9;
            --text: #243447;
            --muted: #788695;
            --border: #e3e9ef;
            --surface: #ffffff;
            --background: #f4f7fa;
            --success: #16805d;
            --success-bg: #e7f6ee;
            --danger: #b54747;
            --danger-bg: #fff0f0;
            --shadow: 0 12px 35px rgba(16, 43, 70, .07);
            --radius: 18px;
        }

        * { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            margin: 0;
            color: var(--text);
            background: var(--background);
            font-family: "Garuda Sans", "Segoe UI", Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }

        button, input { font: inherit; }
        button { cursor: pointer; }
        a { color: inherit; text-decoration: none; }

        .navbar {
            position: sticky;
            z-index: 100;
            top: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            min-height: 76px;
            padding: 12px 6%;
            color: white;
            background: var(--navy-dark);
            border-bottom: 3px solid var(--gold);
            box-shadow: 0 4px 18px rgba(0, 0, 0, .12);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 13px;
            min-width: 0;
        }

        .brand img {
            display: block;
            width: 180px;
            height: 47px;
            object-fit: contain;
            margin-left: 50px;
        }

        .brand-copy { line-height: 1.25; }

        .brand-title {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: .08em;
        }

        .brand-subtitle {
            margin-top: 4px;
            color: #c7d5e2;
            font-size: 10px;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .nav-link {
            color: #e0e8f0;
            font-size: 13px;
            transition: color .2s ease;
        }

        .nav-link:hover { color: var(--gold); }

        .login-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 0 20px;
            color: var(--navy-dark);
            background: var(--gold);
            border: 1px solid var(--gold);
            border-radius: 9px;
            font-weight: 800;
            transition: .2s ease;
        }

        .login-btn:hover {
            background: #edcf8a;
            transform: translateY(-1px);
        }

        .hero {
            position: relative;
            overflow: hidden;
            padding: 58px 6% 62px;
            color: white;
            background:
                radial-gradient(circle at 85% 10%, rgba(22, 140, 140, .25), transparent 28%),
                linear-gradient(120deg, var(--navy-dark), var(--navy) 66%, #174660);
        }

        .hero::after {
            position: absolute;
            right: -95px;
            bottom: -210px;
            width: 430px;
            height: 430px;
            border: 1px solid rgba(216, 180, 106, .25);
            border-radius: 50%;
            content: "";
            pointer-events: none;
        }

        .hero-inner {
            position: relative;
            z-index: 1;
            max-width: 1250px;
            margin: 0 auto;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 18px;
            color: var(--gold);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .19em;
            text-transform: uppercase;
        }

        .eyebrow::before {
            width: 28px;
            height: 2px;
            background: var(--gold);
            content: "";
        }

        .hero h1 {
            max-width: 760px;
            margin: 0;
            font-size: clamp(32px, 5vw, 52px);
            font-weight: 800;
            line-height: 1.12;
            letter-spacing: -.025em;
        }

        .hero h1 span { color: var(--gold); }

        .hero-description {
            max-width: 650px;
            margin: 19px 0 26px;
            color: #d0dce6;
            font-size: 15px;
            line-height: 1.8;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .hero-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 43px;
            padding: 0 19px;
            border: 1px solid rgba(255, 255, 255, .3);
            border-radius: 9px;
            font-size: 12px;
            font-weight: 700;
            transition: .2s ease;
        }

        .hero-action.primary {
            color: var(--navy-dark);
            background: var(--gold);
            border-color: var(--gold);
        }

        .hero-action:hover { transform: translateY(-2px); }

        .page-content {
            width: min(1250px, 88%);
            margin: 0 auto;
            padding: 40px 0 64px;
        }

        .intro-row {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
        }

        .section-kicker {
            margin-bottom: 5px;
            color: var(--teal);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .intro-row h2 {
            margin: 0;
            color: var(--navy);
            font-size: clamp(24px, 3vw, 31px);
            font-weight: 800;
            line-height: 1.25;
        }

        .intro-row p {
            max-width: 560px;
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 13px;
        }

        .timezone-note {
            flex-shrink: 0;
            padding: 8px 12px;
            color: var(--navy);
            background: #e9eef4;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
        }

        .schedule-section {
            margin-top: 36px;
            scroll-margin-top: 100px;
        }

        .schedule-heading {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 17px;
            padding: 20px 22px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-left: 5px solid var(--teal);
            border-radius: 13px;
            box-shadow: 0 5px 18px rgba(16, 43, 70, .035);
        }

        .schedule-section.media-section .schedule-heading {
            border-left-color: var(--gold);
        }

        .section-icon {
            display: flex;
            flex: 0 0 47px;
            align-items: center;
            justify-content: center;
            width: 47px;
            height: 47px;
            color: var(--teal);
            background: var(--teal);
            border-radius: 12px;
            font-size: 22px;
        }

        .media-section .section-icon {
            color: #8a6829;
            background: var(--gold);
        }

        .schedule-heading-copy { flex: 1; min-width: 0; }

        .schedule-heading h3 {
            margin: 0;
            color: var(--navy);
            font-size: 19px;
            font-weight: 800;
        }

        .schedule-heading p {
            margin: 3px 0 0;
            color: var(--muted);
            font-size: 12px;
        }

        .schedule-label {
            flex-shrink: 0;
            padding: 6px 10px;
            color: var(--teal);
            background: var(--teal-light);
            border-radius: 7px;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .media-section .schedule-label {
            color: #806020;
            background: var(--gold-light);
        }

        .schedule-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) minmax(300px, .75fr);
            align-items: start;
            gap: 18px;
        }

        .panel {
            min-width: 0;
            overflow: hidden;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 19px 21px;
            border-bottom: 1px solid var(--border);
        }

        .panel-header h4 {
            margin: 0;
            color: var(--navy);
            font-size: 14px;
            font-weight: 800;
        }

        .panel-header p {
            margin: 3px 0 0;
            color: var(--muted);
            font-size: 11px;
        }

        .month-controls {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .month-title {
            min-width: 126px;
            color: var(--navy);
            font-size: 12px;
            font-weight: 800;
            text-align: center;
        }

        .icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            color: var(--navy);
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 17px;
            transition: .2s ease;
        }

        .icon-btn:hover {
            color: white;
            background: var(--navy);
            border-color: var(--navy);
        }

        .calendar-wrap { padding: 16px 19px 20px; }

        .calendar-weekdays,
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 5px;
        }

        .calendar-weekdays { margin-bottom: 8px; }

        .weekday {
            padding: 7px 0;
            color: var(--muted);
            font-size: 10px;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
        }

        .weekday:first-child { color: #b45a5a; }

        .day {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            min-width: 0;
            min-height: 48px;
            padding: 5px 2px;
            color: var(--text);
            background: white;
            border: 1px solid transparent;
            border-radius: 9px;
            transition: .16s ease;
        }

        .day:not(.empty):hover {
            background: #f1f6fa;
            border-color: #cbd8e4;
        }

        .day.empty { pointer-events: none; }
        .day-number { font-size: 12px; font-weight: 700; }

        .day.today {
            background: #f1f6fb;
            border-color: #9bb4ca;
        }

        .day.today .day-number {
            color: var(--navy);
            font-weight: 900;
        }

        .day.selected {
            color: white;
            background: var(--navy);
            border-color: var(--navy);
            box-shadow: 0 4px 10px rgba(16, 43, 70, .18);
        }

        .day.selected .day-number { color: white; }
        .day.has-events:not(.selected) { background: var(--teal-light); }

        .media-section .day.has-events:not(.selected) {
            background: var(--gold-light);
        }

        .day-dots {
            display: flex;
            justify-content: center;
            gap: 3px;
            min-height: 5px;
        }

        .day-dot {
            width: 5px;
            height: 5px;
            background: var(--teal);
            border-radius: 50%;
        }

        .media-section .day-dot { background: #b18a3e; }
        .day.selected .day-dot { background: var(--gold); }

        .today-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid var(--border);
        }

        .calendar-legend {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            color: var(--muted);
            font-size: 10px;
        }

        .legend-item { display: inline-flex; align-items: center; gap: 5px; }

        .legend-dot {
            width: 7px;
            height: 7px;
            background: var(--teal);
            border-radius: 50%;
        }

        .media-section .legend-dot { background: #b18a3e; }

        .today-btn {
            padding: 6px 11px;
            color: var(--navy);
            background: white;
            border: 1px solid var(--border);
            border-radius: 7px;
            font-size: 10px;
            font-weight: 800;
        }

        .today-btn:hover { background: #f1f6fa; }

        /* Keep the schedule list compact and scroll it independently. */
        .detail-panel {
            position: sticky;
            top: 96px;
            display: flex;
            flex-direction: column;
            max-height: 410px;
            min-height: 0;
        }

        .detail-top {
            flex: 0 0 auto;
            padding: 16px 18px;
            color: white;
            background: linear-gradient(120deg, var(--navy-dark), var(--navy-light));
        }

        .media-section .detail-top {
            background: linear-gradient(120deg, #3f3829, #7c6335);
        }

        .detail-top .small-label {
            color: #c9d7e3;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .detail-date {
            margin-top: 5px;
            font-size: 19px;
            font-weight: 800;
            line-height: 1.25;
        }

        .detail-count {
            display: inline-flex;
            margin-top: 8px;
            padding: 5px 9px;
            color: var(--navy-dark);
            background: var(--gold);
            border-radius: 6px;
            font-size: 10px;
            font-weight: 800;
        }

        .detail-body {
            flex: 1 1 auto;
            min-height: 0;
            padding: 10px;
            overflow-y: auto;
            overscroll-behavior: contain;
            scrollbar-width: thin;
            scrollbar-color: #c7d2dd transparent;
        }

        .detail-list {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .reservation-card {
            display: block;
            width: 100%;
            padding: 11px 12px;
            color: inherit;
            text-align: left;
            background: #fbfcfd;
            border: 1px solid var(--border);
            border-radius: 10px;
            cursor: pointer;
            transition: border-color .18s ease, background .18s ease, transform .18s ease;
        }

        .reservation-card:hover,
        .reservation-card:focus-visible {
            background: #fff;
            border-color: #9fb5c8;
            outline: none;
            transform: translateY(-1px);
        }

        .media-section .reservation-card:hover,
        .media-section .reservation-card:focus-visible {
            border-color: #c9ad72;
        }

        .reservation-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .reservation-title {
            margin: 0;
            color: var(--navy);
            font-size: 12px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .reservation-time {
            flex-shrink: 0;
            padding: 4px 6px;
            color: var(--teal);
            background: var(--teal-light);
            border-radius: 6px;
            font-size: 9px;
            font-weight: 800;
            white-space: nowrap;
        }

        .media-section .reservation-time {
            color: #806020;
            background: var(--gold-light);
        }

        .reservation-meta {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-top: 10px;
            color: #637284;
            font-size: 11px;
        }

        .meta-line {
            display: flex;
            align-items: flex-start;
            gap: 7px;
            overflow-wrap: anywhere;
        }

        .meta-icon { flex: 0 0 15px; color: var(--teal); text-align: center; }
        .media-section .meta-icon { color: #96732e; }

        .status-pill {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            padding: 4px 8px;
            color: var(--success);
            background: var(--success-bg);
            border-radius: 6px;
            font-size: 9px;
            font-weight: 900;
            letter-spacing: .04em;
        }

        .view-details-btn {
            width: 100%;
            margin-top: 12px;
            padding: 8px 10px;
            color: var(--navy);
            background: white;
            border: 1px solid #ccd7e1;
            border-radius: 7px;
            font-size: 10px;
            font-weight: 800;
            transition: .2s ease;
        }

        .view-details-btn:hover {
            color: white;
            background: var(--navy);
            border-color: var(--navy);
        }

        .empty-state { padding: 28px 15px; color: var(--muted); text-align: center; }
        .empty-state-icon { margin-bottom: 8px; font-size: 26px; opacity: .65; }

        .empty-state h5 {
            margin: 0 0 5px;
            color: var(--navy);
            font-size: 13px;
        }

        .empty-state p { margin: 0; font-size: 11px; }
        .loading-state { padding: 24px 12px; color: var(--muted); font-size: 12px; text-align: center; }

        .error-state {
            padding: 15px;
            color: var(--danger);
            background: var(--danger-bg);
            border-radius: 9px;
            font-size: 11px;
            text-align: center;
        }

        .separator {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 42px 0 12px;
            color: #9aa6b2;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .15em;
            text-transform: uppercase;
        }

        .separator::before,
        .separator::after {
            flex: 1;
            height: 1px;
            background: #dce4eb;
            content: "";
        }

        .footer { padding: 25px 6%; color: #c9d6e2; background: var(--navy-dark); }

        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            max-width: 1250px;
            margin: 0 auto;
        }

        .footer strong { color: white; letter-spacing: .06em; }
        .footer p { margin: 3px 0 0; font-size: 10px; }
        .footer-copy { color: #9fb0c0; font-size: 10px; text-align: right; }

        .modal-overlay {
            position: fixed;
            z-index: 1000;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(5, 20, 35, .72);
            backdrop-filter: blur(4px);
        }

        .modal-overlay.open { display: flex; }

        .modal {
            width: min(540px, 100%);
            max-height: 88vh;
            overflow-y: auto;
            background: white;
            border-radius: 17px;
            box-shadow: 0 24px 80px rgba(0, 0, 0, .25);
            animation: modalIn .18s ease-out;
        }

        

        @keyframes modalIn {
            from { opacity: 0; transform: translateY(8px) scale(.99); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
            padding: 21px 23px;
            color: white;
            background: var(--navy);
            border-bottom: 3px solid var(--gold);
        }

        .modal-header h3 { margin: 0; font-size: 18px; line-height: 1.35; }
        .modal-header p { margin: 5px 0 0; color: #c9d6e2; font-size: 11px; }

        .modal-close {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 31px;
            width: 31px;
            height: 31px;
            color: white;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 8px;
            font-size: 19px;
        }

        .modal-content { padding: 22px 23px; }

        .modal-info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .modal-info {
            min-width: 0;
            padding: 12px;
            background: #f6f8fa;
            border: 1px solid #e8edf2;
            border-radius: 9px;
        }

        .modal-info.full { grid-column: 1 / -1; }

        .modal-info-label {
            margin-bottom: 4px;
            color: var(--muted);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .modal-info-value {
            color: var(--navy);
            font-size: 12px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .modal-footer { display: flex; justify-content: flex-end; padding: 0 23px 22px; }

        .modal-done {
            min-height: 38px;
            padding: 0 17px;
            color: white;
            background: var(--navy);
            border: 1px solid var(--navy);
            border-radius: 8px;
            font-size: 11px;
            font-weight: 800;
        }

        @media (max-width: 900px) {
            .page-content { width: 92%; }
            .schedule-layout { grid-template-columns: minmax(0, 1fr); }
            .detail-panel { position: static; max-height: 390px; }
            .detail-list { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .intro-row { align-items: flex-start; flex-direction: column; }
            .timezone-note { align-self: flex-start; }
        }

        @media (max-width: 600px) {
            .navbar { min-height: 66px; padding: 10px 4%; }
            .brand img { width: 120px; height: 39px; margin-left: 0px }
            .brand-title { font-size: 16px; }
            .brand-subtitle { font-size: 8px; }
            .nav-right { gap: 10px; }
            .nav-link { display: none; }
            .login-btn { min-height: 36px; padding: 0 13px; font-size: 11px; }
            .hero { padding: 43px 5% 48px; }
            .hero-description { font-size: 13px; }
            .page-content { width: 92%; padding-top: 28px; }
            .schedule-section { margin-top: 27px; }
            .schedule-heading { align-items: flex-start; gap: 11px; padding: 15px; }
            .section-icon { flex-basis: 35px; width: 35%; height: 35px; font-size: 18px; }
            .schedule-heading h3 { font-size: 16px; }
            .schedule-heading p { font-size: 11px; }
            .schedule-label { display: none; }
            .panel-header { align-items: flex-start; flex-direction: column; padding: 15px; }
            .month-controls { width: 100%; justify-content: space-between; }
            .month-title { flex: 1; }
            .calendar-wrap { padding: 12px 10px 15px; }
            .calendar-weekdays, .calendar-grid { gap: 3px; }
            .day { min-height: 42px; border-radius: 7px; }
            .day-number { font-size: 11px; }
            .detail-panel { max-height: 360px; }
            .detail-list { grid-template-columns: minmax(0, 1fr); }
            .modal-info-grid { grid-template-columns: minmax(0, 1fr); }
            .modal-info.full { grid-column: auto; }
            .footer-inner { align-items: flex-start; flex-direction: column; }
            .footer-copy { text-align: left; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                scroll-behavior: auto !important;
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
        }
        .navbar {
            min-height: 76px;
            padding: 10px 6%;
        }

        .brand {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            flex: 0 0 auto;
            min-width: 0;
        }

        .brand-logo {
            display: block;
            width: 180px;
            height: 48px;
            object-fit: contain;
            object-position: left center;
        }

        /* ===== HIDE SCROLLBAR, KEEP SCROLLING ===== */
        html {
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* Internet Explorer lama */
        }

        html::-webkit-scrollbar,
        body::-webkit-scrollbar {
            display: none;
            width: 0;
            height: 0;
        }

        /* ===== RESPONSIVE LOGO ===== */
        @media (max-width: 600px) {
            .navbar {
                min-height: 66px;
                padding: 8px 4%;
            }

            .brand-logo {
                width: 115px;
                height: 42px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="{{ url('/') }}" class="brand" aria-label="GITC Info home">
            <img
                src="{{ asset('assets/icons/logo/logo.png') }}"
                alt="GITC Logo"
                onerror="this.style.display='none'"
            >
            {{-- <div class="brand-copy">
                <div class="brand-title">GITC INFO</div>
                <div class="brand-subtitle">Reservation Portal</div>
            </div> --}}
        </a>

        <div class="nav-right">
            <a href="#room-reservations" class="nav-link">Room Reservations</a>
            <a href="#training-media" class="nav-link">Training Media</a>
            <a href="{{ route('login') }}" class="login-btn">Login to Portal ↗</a>
        </div>
    </nav>

    <header class="hero">
        <div class="hero-inner">
            <div class="eyebrow">Reservation Portal</div>
            <h1>Welcome to <span>GITC INFO</span></h1>
            <p class="hero-description">
                Access reservation schedules in one place. Explore room
                availability and training media schedules using their
                separate calendars below.
            </p>
            <div class="hero-actions">
                <a href="#room-reservations" class="hero-action primary">View Room Reservations ↓</a>
                <a href="#training-media" class="hero-action">View Training Media Schedule ↓</a>
            </div>
        </div>
    </header>

    <main class="page-content">
        <div class="intro-row">
            <div>
                <div class="section-kicker">Schedule Overview</div>
                <h2>Reservation Schedules</h2>
                <p>Select a date on either calendar to view its scheduled activities. Each calendar is independent.</p>
            </div>
            {{-- <div class="timezone-note">All times follow the application's configured timezone.</div> --}}
        </div>

        <section class="schedule-section room-section" id="room-reservations" data-schedule="room">
            <div class="schedule-heading">
                <div class="section-icon" aria-hidden="true">
                     <img
                                src="{{ asset('assets/icons/navigation/reservation.svg') }}"
                                alt=""
                            >
                </div>
                <div class="schedule-heading-copy">
                    <h3>Room Reservations</h3>
                    <p>Approved bookings for meeting rooms and other rooms.</p>
                </div>
                <span class="schedule-label">Room Schedule</span>
            </div>

            <div class="schedule-layout">
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <h4>Room Reservation Calendar</h4>
                            <p>Select a date to view the room schedule.</p>
                        </div>
                        <div class="month-controls">
                            <button type="button" class="icon-btn" data-action="prev" aria-label="Previous month for room reservations">‹</button>
                            <div class="month-title" data-month-title>Loading...</div>
                            <button type="button" class="icon-btn" data-action="next" aria-label="Next month for room reservations">›</button>
                        </div>
                    </div>
                    <div class="calendar-wrap">
                        <div class="calendar-weekdays">
                            <div class="weekday">Sun</div><div class="weekday">Mon</div><div class="weekday">Tue</div>
                            <div class="weekday">Wed</div><div class="weekday">Thu</div><div class="weekday">Fri</div><div class="weekday">Sat</div>
                        </div>
                        <div class="calendar-grid" data-calendar-grid><div class="loading-state">Loading calendar...</div></div>
                        <div class="today-row">
                            <div class="calendar-legend">
                                <span class="legend-item"><span class="legend-dot"></span>Has reservations</span>
                                <span class="legend-item">Select a date for details</span>
                            </div>
                            <button type="button" class="today-btn" data-action="today">Today</button>
                        </div>
                    </div>
                </div>

                <aside class="panel detail-panel">
                    <div class="detail-top">
                        <div class="small-label">Room Schedule Details</div>
                        <div class="detail-date" data-detail-date>Loading date...</div>
                        <div class="detail-count" data-detail-count>Loading...</div>
                    </div>
                    <div class="detail-body">
                        <div class="detail-list" data-detail-list><div class="loading-state">Loading reservations...</div></div>
                    </div>
                </aside>
            </div>
        </section>

        <div class="separator">Separate schedule categories</div>

        <section class="schedule-section media-section" id="training-media" data-schedule="media">
            <div class="schedule-heading">
                <div class="section-icon" aria-hidden="true">
                    <img src="{{ asset('assets/icons/navigation/training-media.svg') }}" alt="">
                </div>
                <div class="schedule-heading-copy">
                    <h3>Training Media Schedule</h3>
                    <p>Scheduled sessions for training media resources.</p>
                </div>
                <span class="schedule-label">Training Media</span>
            </div>

            <div class="schedule-layout">
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <h4>Training Media Calendar</h4>
                            <p>This calendar displays scheduled Training Media sessions.</p>
                        </div>
                        <div class="month-controls">
                            <button type="button" class="icon-btn" data-action="prev" aria-label="Previous month for training media">‹</button>
                            <div class="month-title" data-month-title>Loading...</div>
                            <button type="button" class="icon-btn" data-action="next" aria-label="Next month for training media">›</button>
                        </div>
                    </div>
                    <div class="calendar-wrap">
                        <div class="calendar-weekdays">
                            <div class="weekday">Sun</div><div class="weekday">Mon</div><div class="weekday">Tue</div>
                            <div class="weekday">Wed</div><div class="weekday">Thu</div><div class="weekday">Fri</div><div class="weekday">Sat</div>
                        </div>
                        <div class="calendar-grid" data-calendar-grid><div class="loading-state">Loading calendar...</div></div>
                        <div class="today-row">
                            <div class="calendar-legend">
                                <span class="legend-item"><span class="legend-dot"></span>Has sessions</span>
                                <span class="legend-item">Select a date for details</span>
                            </div>
                            <button type="button" class="today-btn" data-action="today">Today</button>
                        </div>
                    </div>
                </div>

                <aside class="panel detail-panel">
                    <div class="detail-top">
                        <div class="small-label">Training Media Details</div>
                        <div class="detail-date" data-detail-date>Loading date...</div>
                        <div class="detail-count" data-detail-count>Loading...</div>
                    </div>
                    <div class="detail-body">
                        <div class="detail-list" data-detail-list><div class="loading-state">Loading sessions...</div></div>
                    </div>
                </aside>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-inner">
            <div><strong>GITC INFO</strong><p>Reservation Portal</p></div>
            <div class="footer-copy">
                Internal reservation schedule<br>
                &copy; {{ date('Y') }} GITC Info
            </div>
        </div>
    </footer>

    <div class="modal-overlay" id="reservation-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="modal">
            <div class="modal-header">
                <div>
                    <h3 id="modal-title">Schedule Details</h3>
                    <p id="modal-subtitle">Schedule information</p>
                </div>
                <button type="button" class="modal-close" id="modal-close" aria-label="Close details">&times;</button>
            </div>
            <div class="modal-content"><div class="modal-info-grid" id="modal-info-grid"></div></div>
            <div class="modal-footer"><button type="button" class="modal-done" id="modal-done">Close Details</button></div>
        </div>
    </div>

    <script>
        (() => {
            'use strict';

            const endpoint = @json(route('landing.reservations'));

            const monthFormatter = new Intl.DateTimeFormat('en-US', { month: 'long', year: 'numeric' });
            const dateFormatter = new Intl.DateTimeFormat('en-US', {
                weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
            });
            const shortDateFormatter = new Intl.DateTimeFormat('en-US', {
                day: 'numeric', month: 'short', year: 'numeric'
            });

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const sections = Array.from(document.querySelectorAll('[data-schedule]'));
            const states = {};

            sections.forEach(section => {
                const key = section.dataset.schedule;
                states[key] = {
                    section,
                    year: today.getFullYear(),
                    month: today.getMonth() + 1,
                    selectedDate: formatDate(today),
                    reservations: [],
                    loading: false,
                    error: null,
                    requestId: 0
                };

                section.querySelectorAll('[data-action]').forEach(button => {
                    button.addEventListener('click', () => handleCalendarAction(key, button.dataset.action));
                });
            });

            function formatDate(date) {
                return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
            }

            function parseDate(value) {
                if (!value) return null;
                const match = String(value).match(/^(\d{4})-(\d{2})-(\d{2})/);
                if (!match) return null;
                const date = new Date(Number(match[1]), Number(match[2]) - 1, Number(match[3]));
                date.setHours(0, 0, 0, 0);
                return date;
            }

            function escapeHtml(value) {
                return String(value ?? '').replace(/[&<>"']/g, character => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
                })[character]);
            }

            function getResourceType(key) {
                return key === 'media' ? 'Training Media' : 'Room';
            }

            function getStatusLabel(key) {
                return key === 'media' ? 'scheduled sessions' : 'approved reservations';
            }

            function getReservationDateRange(reservation) {
                const start = reservation.start_date || (reservation.starts_at || '').slice(0, 10);
                const end = reservation.end_date || (reservation.ends_at || '').slice(0, 10) || start;
                return { start, end };
            }

            function getReservationsForDate(state, dateString) {
                return state.reservations.filter(reservation => {
                    const range = getReservationDateRange(reservation);
                    return range.start && range.end && range.start <= dateString && range.end >= dateString;
                }).sort((a, b) => (a.start_time || '').localeCompare(b.start_time || ''));
            }

            function handleCalendarAction(key, action) {
                const state = states[key];

                if (action === 'prev') {
                    const date = new Date(state.year, state.month - 2, 1);
                    state.year = date.getFullYear();
                    state.month = date.getMonth() + 1;
                } else if (action === 'next') {
                    const date = new Date(state.year, state.month, 1);
                    state.year = date.getFullYear();
                    state.month = date.getMonth() + 1;
                } else if (action === 'today') {
                    state.year = today.getFullYear();
                    state.month = today.getMonth() + 1;
                    state.selectedDate = formatDate(today);
                }

                loadSchedule(key);
            }

            async function loadSchedule(key) {
                const state = states[key];
                const requestId = ++state.requestId;
                state.loading = true;
                state.error = null;

                renderCalendar(key);
                renderDetails(key);

                const params = new URLSearchParams({
                    year: String(state.year),
                    month: String(state.month),
                    resource_type: getResourceType(key)
                });

                try {
                    const response = await fetch(`${endpoint}?${params.toString()}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) throw new Error(`Request failed (${response.status})`);

                    const data = await response.json();
                    if (requestId !== state.requestId) return;
                    if (!Array.isArray(data.reservations)) throw new Error('Unexpected schedule response.');

                    state.reservations = data.reservations.filter(reservation => {
                        const status = String(reservation.status || '').toUpperCase();

                        if (key === 'media') {
                            return status === 'SCHEDULED';
                        }

                        return status === 'APPROVED';
                    });

                    state.loading = false;
                    state.error = null;
                    renderCalendar(key);
                    renderDetails(key);
                } catch (error) {
                    if (requestId !== state.requestId) return;
                    state.loading = false;
                    state.error = error.message || 'Unable to load schedule.';
                    state.reservations = [];
                    renderCalendar(key);
                    renderDetails(key);
                    console.error(`Unable to load ${getResourceType(key)} schedule:`, error);
                }
            }

            function renderCalendar(key) {
                const state = states[key];
                const section = state.section;
                const title = section.querySelector('[data-month-title]');
                const grid = section.querySelector('[data-calendar-grid]');

                title.textContent = monthFormatter.format(new Date(state.year, state.month - 1, 1));

                if (state.loading && state.reservations.length === 0) {
                    grid.innerHTML = '<div class="loading-state">Loading calendar...</div>';
                    return;
                }

                if (state.error) {
                    grid.innerHTML = '<div class="error-state" style="grid-column:1/-1">Unable to load this calendar. Please try again.</div>';
                    return;
                }

                const firstDay = new Date(state.year, state.month - 1, 1).getDay();
                const daysInMonth = new Date(state.year, state.month, 0).getDate();
                let html = '';

                for (let i = 0; i < firstDay; i++) {
                    html += '<div class="day empty" aria-hidden="true"></div>';
                }

                for (let dayNumber = 1; dayNumber <= daysInMonth; dayNumber++) {
                    const date = new Date(state.year, state.month - 1, dayNumber);
                    const dateString = formatDate(date);
                    const isToday = dateString === formatDate(today);
                    const isSelected = dateString === state.selectedDate;
                    const reservations = getReservationsForDate(state, dateString);
                    const hasEvents = reservations.length > 0;
                    const classes = ['day'];

                    if (isToday) classes.push('today');
                    if (isSelected) classes.push('selected');
                    if (hasEvents) classes.push('has-events');

                    html += `
                        <button type="button" class="${classes.join(' ')}"
                            data-select-date="${dateString}"
                            aria-label="${escapeHtml(dateFormatter.format(date))}${hasEvents ? ', ' + reservations.length + ' events' : ''}"
                            aria-pressed="${isSelected ? 'true' : 'false'}">
                            <span class="day-number">${dayNumber}</span>
                            <span class="day-dots">${hasEvents ? '<span class="day-dot"></span>' : ''}</span>
                        </button>
                    `;
                }

                grid.innerHTML = html;

                grid.querySelectorAll('[data-select-date]').forEach(button => {
                    button.addEventListener('click', () => {
                        state.selectedDate = button.dataset.selectDate;
                        renderCalendar(key);
                        renderDetails(key);
                    });
                });
            }

            function renderDetails(key) {
                const state = states[key];
                const section = state.section;
                const dateElement = section.querySelector('[data-detail-date]');
                const countElement = section.querySelector('[data-detail-count]');
                const listElement = section.querySelector('[data-detail-list]');
                const selectedDate = parseDate(state.selectedDate);
                const statusLabel = getStatusLabel(key);

                dateElement.textContent = selectedDate ? dateFormatter.format(selectedDate) : state.selectedDate;

                if (state.loading) {
                    countElement.textContent = 'Loading schedule';
                    listElement.innerHTML = '<div class="loading-state">Loading schedule...</div>';
                    return;
                }

                if (state.error) {
                    countElement.textContent = 'Schedule unavailable';
                    listElement.innerHTML = `
                        <div class="error-state">
                            Could not load schedule. Please try again.
                            <button type="button" class="view-details-btn" data-retry="${key}">Try Again</button>
                        </div>`;
                    listElement.querySelector('[data-retry]')?.addEventListener('click', () => loadSchedule(key));
                    return;
                }

                const reservations = getReservationsForDate(state, state.selectedDate);
                countElement.textContent = `${reservations.length} ${statusLabel}`;

                if (reservations.length === 0) {
                    listElement.innerHTML = `
                        <div class="empty-state">
                            <div class="empty-state-icon"></div>
                            <h5>No Schedule Found</h5>
                            <p>There are no ${key === 'media' ? 'scheduled sessions' : 'approved reservations'} for this date.</p>
                        </div>`;
                    return;
                }

                listElement.innerHTML = reservations.map((reservation, index) => {
                    const startTime = reservation.start_time || '—';
                    const endTime = reservation.end_time || '—';
                    const title = reservation.event_name || 'Untitled Schedule';
                    const resource = reservation.resource_name || title;
                    const displayName = key === 'room' ? resource : (reservation.resource_name || title);

                    return `
                        <button type="button" class="reservation-card" data-open-reservation="${key}-${index}"
                            aria-label="View details for ${escapeHtml(displayName)} at ${escapeHtml(startTime)}">
                            <div class="reservation-card-top">
                                <h5 class="reservation-title">${escapeHtml(displayName)}</h5>
                                <span class="reservation-time">${escapeHtml(startTime)}–${escapeHtml(endTime)}</span>
                            </div>
                        </button>`;
                }).join('');

                listElement.querySelectorAll('[data-open-reservation]').forEach((button, index) => {
                    button.addEventListener('click', () => openReservationModal(key, reservations[index]));
                });
            }

            function openReservationModal(key, reservation) {
                const modal = document.getElementById('reservation-modal');
                const titleElement = document.getElementById('modal-title');
                const subtitleElement = document.getElementById('modal-subtitle');
                const grid = document.getElementById('modal-info-grid');

                const resourceType = reservation.resource_type || getResourceType(key);
                const isMedia = key === 'media';
                const title = reservation.event_name || 'Untitled Schedule';
                const status = reservation.status || (isMedia ? 'SCHEDULED' : 'APPROVED');

                titleElement.textContent = title;
                subtitleElement.textContent = `${resourceType} · Schedule details`;

                const startDateFormatted = parseDate(reservation.start_date);
                const endDateFormatted = parseDate(reservation.end_date || reservation.start_date);
                const dateText = startDateFormatted ? shortDateFormatter.format(startDateFormatted) : 'Not specified';
                const endDateText = endDateFormatted ? shortDateFormatter.format(endDateFormatted) : dateText;

                const fields = [
                    { label: 'Resource Type', value: resourceType },
                    { label: 'Resource', value: reservation.resource_name || 'Not specified' },
                    { label: 'Building', value: reservation.building_name || 'Not specified', full: true },
                    { label: 'Schedule Status', value: status },
                    { label: 'Start Date', value: dateText },
                    { label: 'End Date', value: endDateText },
                    { label: 'Start Time', value: reservation.start_time || '—' },
                    { label: 'End Time', value: reservation.end_time || '—' },
                    { label: 'Scheduled By', value: reservation.username || 'Not specified' }
                ];

                if (!isMedia) {
                    fields.unshift({ label: 'Reservation Number', value: reservation.reservation_number || '—', full: true });
                } else {
                    fields.push(
                        { label: 'Group', value: reservation.class_name || 'Not specified' },
                        { label: 'Instructor', value: reservation.instructor_name || 'Not specified' },
                        { label: 'Participants', value: String(reservation.participant_count ?? 0) },
                        { label: 'Notes', value: reservation.notes || 'Not specified', full: true }
                    );
                }

                grid.innerHTML = fields.map(field => `
                    <div class="modal-info ${field.full ? 'full' : ''}">
                        <div class="modal-info-label">${escapeHtml(field.label)}</div>
                        <div class="modal-info-value">${escapeHtml(field.value)}</div>
                    </div>
                `).join('');

                modal.classList.add('open');
                document.body.style.overflow = 'hidden';
                document.getElementById('modal-close').focus();
            }

            function closeModal() {
                document.getElementById('reservation-modal').classList.remove('open');
                document.body.style.overflow = '';
            }

            document.getElementById('modal-close').addEventListener('click', closeModal);
            document.getElementById('modal-done').addEventListener('click', closeModal);

            document.getElementById('reservation-modal').addEventListener('click', event => {
                if (event.target.id === 'reservation-modal') closeModal();
            });

            document.addEventListener('keydown', event => {
                if (event.key === 'Escape') closeModal();
            });

            Object.keys(states).forEach(loadSchedule);
        })();
    </script>
</body>
</html>

