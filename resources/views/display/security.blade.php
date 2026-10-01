<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GITC Info - Security Monitoring</title>

    <style>
        :root {
            --navy-dark: #00294f;
            --navy: #003b6f;
            --blue: #006fae;
            --cyan: #00a8c8;
            --cyan-light: #dff7fb;

            --white: #ffffff;

            --text: #12304a;
            --muted: #668096;

            --background: #f3f7fa;
            --border: #d9e5ed;

            --success: #62e8c8;
            --success-bg: #ecfffa;
            --success-border: #b6efdf;
            --success-text: #178d71;

            --warning: #ffd36a;
            --warning-bg: #fff8df;
            --warning-border: #f2d27a;
            --warning-text: #9b6f00;

            --danger: #ff4d4d;
            --danger-bg: #fff0f0;
            --danger-border: #ffd0d0;
            --danger-text: #d33d3d;

            --completed: #7b8c97;
            --completed-bg: #f3f6f8;
            --completed-border: #dce4e9;

            --radius-large: 18px;
            --radius-medium: 13px;
            --radius-small: 10px;
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
            font-family: "Segoe UI", Arial, sans-serif;
            background: var(--background);
            color: var(--text);
        }

        button,
        input {
            font: inherit;
        }

        button {
            -webkit-tap-highlight-color: transparent;
        }

        .security-app {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* =========================================================
           HEADER — KEEP SAME VISUAL LANGUAGE AS BUILDING DISPLAY
        ========================================================= */

        .display-header {
            flex: 0 0 13vh;
            min-height: 94px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2vw;
            padding: 0 3vw;
            color: var(--white);
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

        .display-header::after {
            content: "";
            position: absolute;
            width: 42vw;
            height: 23vh;
            right: -10vw;
            top: -13vh;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.06);
            background: rgba(0, 168, 200, 0.06);
            transform: rotate(-15deg);
            pointer-events: none;
        }

        .header-left {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 1.1vw;
            position: relative;
            z-index: 2;
        }

        .header-brand,
        .header-building {
            min-width: 0;
        }

        .header-title {
            color: var(--white);
            font-size: clamp(20px, 1.65vw, 32px);
            font-weight: 800;
            /* line-height: 1; */
        }

        .header-subtitle {
            margin-top: 6px;
            color: rgba(255, 255, 255, 0.70);
            font-size: clamp(10px, 0.82vw, 16px);
        }

        .header-divider {
            flex: 0 0 1px;
            width: 1px;
            height: 52px;
            margin: 0 0.6vw;
            background: rgba(255, 255, 255, 0.25);
        }

        .header-building-name {
            max-width: 31vw;
            color: var(--white);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: clamp(19px, 1.55vw, 30px);
            font-weight: 800;
            /* line-height: 1.05; */
        }

        .header-building-code {
            margin-top: 4px;
            color: rgba(255, 255, 255, 0.67);
            font-size: clamp(9px, 0.62vw, 13px);
        }

        .header-right {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 1.45vw;
            position: relative;
            z-index: 2;
        }

        .datetime {
            text-align: right;
        }

        .header-date {
            color: rgba(255, 255, 255, 0.78);
            font-size: clamp(10px, 0.75vw, 15px);
            white-space: nowrap;
        }

        .header-clock {
            margin-top: 4px;
            color: #69e5f5;
            font-size: clamp(22px, 2.15vw, 42px);
            font-weight: 800;
            white-space: nowrap;
            /* line-height: 1; */
        }

        .logo-area {
            width: clamp(150px, 14vw, 280px);
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px 15px;
            border-left: 1px solid rgba(255, 255, 255, 0.18);
        }

        .logo-area img {
            display: block;
            max-width: 100%;
            max-height: 54px;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        /* =========================================================
           MAIN LAYOUT
        ========================================================= */

        .security-main {
            flex: 1;
            min-height: 0;
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(470px, 1fr);
            gap: 1vw;
            padding: 1vw 2.1vw 0.8vw;
            overflow: hidden;
        }

        .monitor-area,
        .security-side {
            min-width: 0;
            min-height: 0;
        }

        .monitor-area {
            display: flex;
            flex-direction: column;
            gap: 0.75vw;
        }

        /* =========================================================
           TOP TOOLBAR
        ========================================================= */

        .security-toolbar {
            flex: 0 0 auto;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-large);
            padding: 0.72vw 0.95vw 0.8vw;
            box-shadow: 0 8px 24px rgba(0, 59, 111, 0.05);
        }

        .toolbar-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1vw;
        }

        .toolbar-head-main {
            min-width: 0;
        }

        .section-kicker {
            color: var(--blue);
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: clamp(9px, 0.62vw, 13px);
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .section-title {
            margin: 5px 0 0;
            color: var(--navy-dark);
            font-size: clamp(23px, 1.55vw, 32px);
            /* line-height: 1.03; */
            font-weight: 850;
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
            flex-wrap: wrap;
            flex: 0 0 auto;
        }

        .visitor-lookup-button {
            min-height: 44px;
            padding: 0 18px;
            border: 1px solid var(--blue);
            border-radius: 12px;
            background: var(--blue);
            color: var(--white);
            cursor: pointer;
            font-size: clamp(10px, 0.68vw, 14px);
            font-weight: 850;
            letter-spacing: 0.02em;
            white-space: nowrap;
            box-shadow: 0 7px 18px rgba(0, 111, 174, 0.17);
            transition:
                background 0.18s ease,
                border-color 0.18s ease,
                transform 0.18s ease,
                box-shadow 0.18s ease;
        }

        .visitor-lookup-button:hover,
        .visitor-lookup-button:focus-visible {
            border-color: #005b8f;
            background: #005b8f;
            box-shadow: 0 9px 22px rgba(0, 91, 143, 0.21);
            transform: translateY(-1px);
            outline: none;
        }

        .visitor-lookup-button:active {
            transform: translateY(0);
        }

        .toolbar-summary {
            display: flex;
            align-items: stretch;
            gap: 6px;
        }

        .summary-chip {
            min-width: 78px;
            min-height: 44px;
            padding: 5px 9px;
            border: 1px solid var(--border);
            border-radius: 11px;
            background: #fbfdfe;
            text-align: center;
        }

        .summary-chip-value {
            color: var(--navy-dark);
            font-size: clamp(15px, 0.92vw, 20px);
            /* line-height: 1; */
            font-weight: 850;
        }

        .summary-chip-label {
            margin-top: 4px;
            color: var(--muted);
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .toolbar-controls {
            margin-top: 0.58vw;
            display: flex;
            align-items: center;
            gap: 0.55vw;
            flex-wrap: wrap;
        }

        .control-group {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 0;
        }

        .toolbar-label {
            flex: 0 0 auto;
            color: var(--muted);
            font-size: 10px;
            font-weight: 850;
            letter-spacing: 0.09em;
            text-transform: uppercase;
        }

        .control-divider {
            width: 1px;
            height: 28px;
            margin: 0 3px;
            background: var(--border);
        }

        .day-switch,
        .location-switch {
            display: flex;
            align-items: center;
            gap: 7px;
            flex-wrap: wrap;
        }

        .day-button,
        .location-button,
        .clear-scope-button {
            min-height: 34px;
            border: 1px solid var(--border);
            border-radius: 999px;
            background: #ffffff;
            color: var(--navy-dark);
            cursor: pointer;
            font-size: clamp(9px, 0.60vw, 13px);
            font-weight: 850;
            white-space: nowrap;
            transition:
                background 0.18s ease,
                border-color 0.18s ease,
                color 0.18s ease,
                transform 0.18s ease;
        }

        .day-button {
            min-width: 108px;
            padding: 0 15px;
        }

        .location-button {
            min-width: 72px;
            padding: 0 12px;
        }

        .clear-scope-button {
            min-width: 68px;
            padding: 0 11px;
            color: var(--muted);
        }

        .day-button:hover,
        .location-button:hover,
        .clear-scope-button:hover {
            border-color: #a9bfd0;
            transform: translateY(-1px);
        }

        .day-button.active,
        .location-button.active {
            border-color: var(--blue);
            background: var(--blue);
            color: var(--white);
            box-shadow: 0 5px 14px rgba(0, 111, 174, 0.14);
        }

        /* =========================================================
           SCHEDULE PANEL — PRIMARY CONTENT
        ========================================================= */

        .schedule-panel {
            flex: 1;
            min-height: 0;
            display: flex;
            flex-direction: column;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-large);
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 59, 111, 0.05);
        }

        .panel-header {
            flex: 0 0 auto;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
            padding: 0.66vw 0.9vw;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(90deg, #ffffff, #f9fcfe);
        }

        .panel-title-wrap {
            min-width: 0;
        }

        .panel-kicker {
            color: var(--blue);
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .panel-title {
            margin-top: 4px;
            color: var(--navy-dark);
            font-size: clamp(17px, 1.02vw, 23px);
            font-weight: 850;
            /* line-height: 1.1; */
        }

        .panel-count {
            color: var(--muted);
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }

        .schedule-grid-header,
        .schedule-row {
            display: grid;
            grid-template-columns:
                70px
                minmax(145px, 0.98fr)
                minmax(125px, 0.92fr)
                minmax(220px, 1.85fr)
                132px
                96px;
            gap: 10px;
            align-items: center;
        }

        .schedule-grid-header {
            flex: 0 0 auto;
            padding: 8px 11px;
            border-bottom: 1px solid var(--border);
            background: #f8fbfd;
            color: var(--muted);
            font-size: 9px;
            font-weight: 850;
            letter-spacing: 0.07em;
            text-transform: uppercase;
        }

        .schedule-list {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 9px;
            scroll-behavior: smooth;
        }

        .schedule-row {
            min-width: 0;
            min-height: 66px;
            margin-bottom: 8px;
            padding: 9px 11px;
            border: 1px solid var(--border);
            border-radius: 13px;
            background: #ffffff;
            cursor: pointer;
            transition:
                border-color 0.18s ease,
                transform 0.18s ease,
                box-shadow 0.18s ease;
        }

        .schedule-row:hover,
        .schedule-row:focus-visible {
            border-color: #b5cbd9;
            box-shadow: 0 7px 16px rgba(0, 59, 111, 0.07);
            transform: translateY(-1px);
            outline: none;
        }

        .schedule-row.current {
            border-color: var(--success-border);
            background: #fcfffe;
        }

        .schedule-row.pending {
            border-color: var(--warning-border);
            background: #fffdfa;
        }

        .schedule-cell {
            min-width: 0;
        }

        .schedule-building {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            background: var(--cyan-light);
            color: var(--navy-dark);
            font-size: 15px;
            font-weight: 900;
            letter-spacing: 0.02em;
        }

        .schedule-building.field {
            width: auto;
            min-width: 74px;
            padding: 0 10px;
            font-size: 10px;
            letter-spacing: 0.05em;
        }

        .schedule-type {
            color: var(--blue);
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 0.07em;
            text-transform: uppercase;
        }

        .schedule-resource {
            margin-top: 3px;
            color: var(--navy-dark);
            font-size: clamp(12px, 0.75vw, 16px);
            font-weight: 850;
            /* line-height: 1.2; */
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .schedule-location {
            color: var(--text);
            font-size: clamp(11px, 0.7vw, 15px);
            font-weight: 700;
            /* line-height: 1.25; */
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .schedule-event {
            color: var(--navy-dark);
            font-size: clamp(13px, 0.8vw, 17px);
            font-weight: 800;
            /* line-height: 1.25; */
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .schedule-time {
            color: var(--navy-dark);
            font-size: clamp(11px, 0.73vw, 16px);
            font-weight: 850;
            white-space: nowrap;
        }

        .schedule-time-date {
            margin-top: 3px;
            color: var(--muted);
            font-size: 9px;
            font-weight: 700;
        }

        .schedule-status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: fit-content;
            min-width: 84px;
            padding: 7px 11px;
            border: 1px solid transparent;
            border-radius: 999px;
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .schedule-status.live {
            border-color: #ffb3b3;
            background: #fff0f0;
            color: #d92d2d;
        }

        .schedule-status.upcoming,
        .schedule-status.pending {
            border-color: var(--warning-border);
            background: var(--warning-bg);
            color: var(--warning-text);
        }

        .schedule-status.completed {
            border-color: var(--completed-border);
            background: var(--completed-bg);
            color: var(--completed);
        }

        .schedule-empty {
            min-height: 220px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px dashed var(--border);
            border-radius: 14px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 750;
            text-align: center;
        }

        .schedule-footer {
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 8px 12px;
            border-top: 1px solid var(--border);
            background: #fbfdfe;
            color: var(--muted);
            font-size: 9px;
            font-weight: 750;
        }

        /* =========================================================
           RIGHT SIDE — NEWS FEED STYLE
        ========================================================= */

        .security-side {
            display: flex;
            min-height: 0;
        }

        .news-panel {
            flex: 1;
            min-height: 0;
            display: flex;
            flex-direction: column;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-large);
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 59, 111, 0.05);
        }

        .news-header {
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 0.62vw 0.9vw;
            border-bottom: 1px solid var(--border);
            background: #ffffff;
        }

        .news-header-main {
            min-width: 0;
        }

        .news-title {
            margin-top: 4px;
            color: var(--navy-dark);
            font-size: clamp(18px, 1.12vw, 25px);
            font-weight: 850;
            /* line-height: 1.08; */
        }

        .news-counter {
            flex: 0 0 auto;
            min-width: 56px;
            padding: 7px 9px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fbfdfe;
            color: var(--muted);
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 0.06em;
            text-align: center;
        }

        .news-body {
            flex: 1;
            min-height: 0;
            position: relative;
            overflow: hidden;
            padding: 10px 12px 12px;
        }

        .news-post {
            width: 100%;
            height: 100%;
            min-height: 0;
            display: flex;
            flex-direction: column;
            gap: 11px;
            align-items: stretch;
        }

        .news-slide-in-right {
            animation: newsSlideInFromRight 0.72s cubic-bezier(0.18, 0.78, 0.22, 1);
        }

        @keyframes newsSlideInFromRight {
            from {
                opacity: 0;
                transform: translateX(90px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .news-image-wrap {
            flex: 0 0 auto;
            height: clamp(300px, 58%, 440px);
            width: auto;
            aspect-ratio: 4 / 5;
            min-height: 0;
            max-width: 100%;
            align-self: center;
            border-radius: 15px;
            overflow: hidden;
            background: #edf4f8;
            border: 1px solid var(--border);
        }

        .news-image-wrap img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .news-image-empty {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            font-size: 11px;
            font-weight: 850;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .news-content-wrap {
            flex: 1 1 auto;
            min-height: 0;
            display: flex;
            flex-direction: column;
            padding: 0 3px 2px;
        }

        .news-content-title {
            margin-top: 0;
            color: var(--navy-dark);
            font-size: clamp(19px, 1.12vw, 25px);
            font-weight: 900;
            /* line-height: 1.18; */
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .news-content-date {
            margin-top: 5px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 750;
        }

        .news-scroll-box {
            flex: 1 1 auto;
            min-height: 56px;
            margin-top: 9px;
            overflow: hidden;
            position: relative;
            padding-right: 3px;
        }

        .news-scroll-content {
            color: var(--text);
            font-size: clamp(12px, 0.78vw, 16px);
            /* line-height: 1.58; */
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .news-scroll-content p {
            margin: 0 0 9px;
        }

        .news-scroll-content ul,
        .news-scroll-content ol {
            margin-top: 0;
            margin-bottom: 10px;
            padding-left: 1.25em;
        }

        .news-scroll-content a {
            color: var(--blue);
        }

        .news-empty {
            width: 100%;
            height: 100%;
            min-height: 260px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px dashed var(--border);
            border-radius: 14px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 750;
            text-align: center;
        }

        /* =========================================================
           VISITOR LOOKUP MODAL
        ========================================================= */

        .visitor-lookup-modal,
        .reservation-modal {
            position: fixed;
            inset: 0;
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: rgba(0, 20, 42, 0.66);
            backdrop-filter: blur(4px);
        }

        .visitor-lookup-modal.open,
        .reservation-modal.open {
            display: flex;
        }

        .visitor-lookup-modal-card {
            width: min(1080px, 94vw);
            height: min(760px, 88vh);
            max-height: 88vh;
            display: flex;
            flex-direction: column;
            background: var(--white);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 21px;
            box-shadow: 0 30px 80px rgba(0, 30, 60, 0.30);
            overflow: hidden;
        }

        .visitor-lookup-header,
        .reservation-modal-header {
            flex: 0 0 auto;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 22px;
            background: linear-gradient(115deg, var(--navy-dark), var(--navy));
            color: var(--white);
        }

        .visitor-lookup-kicker,
        .reservation-modal-kicker {
            color: rgba(255, 255, 255, 0.62);
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.13em;
            text-transform: uppercase;
        }

        .visitor-lookup-title,
        .reservation-modal-title {
            margin: 5px 0 0;
            color: var(--white);
            font-size: clamp(22px, 1.7vw, 31px);
            /* line-height: 1.15; */
            font-weight: 900;
        }

        .visitor-lookup-subtitle {
            margin-top: 5px;
            color: rgba(255, 255, 255, 0.70);
            font-size: 10px;
            /* line-height: 1.4; */
        }

        .visitor-lookup-close,
        .reservation-modal-close {
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.20);
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            color: var(--white);
            cursor: pointer;
            font-size: 26px;
            /* line-height: 1; */
        }

        .visitor-lookup-close:hover,
        .reservation-modal-close:hover {
            background: rgba(255, 255, 255, 0.16);
        }

        .visitor-lookup-body {
            min-height: 0;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 13px;
            padding: 17px 22px 20px;
            overflow: hidden;
        }

        .visitor-lookup-controls {
            flex: 0 0 auto;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 12px;
            align-items: center;
        }

        .visitor-search-box {
            position: relative;
        }

        .visitor-search-icon {
            position: absolute;
            left: 17px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--blue);
            font-size: 21px;
            pointer-events: none;
        }

        #lookupSearch {
            width: 100%;
            height: 54px;
            padding: 0 17px 0 48px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: #ffffff;
            color: var(--navy-dark);
            font-size: 14px;
            font-weight: 700;
            outline: none;
        }

        #lookupSearch:focus {
            border-color: #8bb9d1;
            box-shadow: 0 0 0 4px rgba(0, 111, 174, 0.08);
        }

        .lookup-day-switch {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .lookup-day-button,
        .lookup-location-button {
            min-height: 42px;
            border: 1px solid var(--border);
            border-radius: 999px;
            background: #ffffff;
            color: var(--navy-dark);
            cursor: pointer;
            font-size: 10px;
            font-weight: 850;
            white-space: nowrap;
        }

        .lookup-day-button {
            min-width: 112px;
            padding: 0 17px;
        }

        .lookup-day-button.active,
        .lookup-location-button.active {
            border-color: var(--blue);
            background: var(--blue);
            color: var(--white);
        }

        .lookup-location-wrap {
            flex: 0 0 auto;
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .lookup-location-label {
            color: var(--muted);
            font-size: 10px;
            font-weight: 850;
            letter-spacing: 0.09em;
            text-transform: uppercase;
        }

        .lookup-location-switch {
            display: flex;
            align-items: center;
            gap: 7px;
            flex-wrap: wrap;
        }

        .lookup-location-button {
            min-width: 84px;
            padding: 0 15px;
        }

        .lookup-results-meta {
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 800;
        }

        .lookup-results {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            padding-right: 4px;
        }

        .lookup-result {
            width: 100%;
            margin: 0 0 10px;
            padding: 15px 16px;
            display: block;
            border: 1px solid var(--border);
            border-radius: 15px;
            background: #ffffff;
            color: inherit;
            text-align: left;
            cursor: pointer;
            transition:
                border-color 0.18s ease,
                box-shadow 0.18s ease,
                transform 0.18s ease;
        }

        .lookup-result:hover,
        .lookup-result:focus-visible {
            border-color: #adc6d5;
            box-shadow: 0 9px 22px rgba(0, 59, 111, 0.08);
            transform: translateY(-1px);
            outline: none;
        }

        .lookup-result-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .lookup-result-location {
            color: var(--blue);
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .lookup-result-time {
            color: var(--navy-dark);
            font-size: 11px;
            font-weight: 850;
            white-space: nowrap;
        }

        .lookup-result-event {
            margin-top: 7px;
            color: var(--navy-dark);
            font-size: 15px;
            font-weight: 850;
            /* line-height: 1.28; */
            overflow-wrap: anywhere;
        }

        .lookup-result-resource {
            margin-top: 5px;
            color: var(--text);
            font-size: 11px;
            font-weight: 700;
            /* line-height: 1.3; */
            overflow-wrap: anywhere;
        }

        .lookup-result-booker {
            margin-top: 6px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 750;
        }

        .lookup-result-footer {
            margin-top: 11px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .lookup-view-label {
            color: var(--blue);
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .lookup-empty {
            min-height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px dashed var(--border);
            border-radius: 14px;
            color: var(--muted);
            padding: 24px;
            text-align: center;
            font-size: 12px;
            font-weight: 750;
        }

        /* =========================================================
           RESERVATION DETAIL MODAL
        ========================================================= */

        .reservation-modal-card {
            width: min(760px, 92vw);
            max-height: 86vh;
            display: flex;
            flex-direction: column;
            background: var(--white);
            border: 1px solid rgba(255, 255, 255, 0.40);
            border-radius: 21px;
            box-shadow: 0 30px 80px rgba(0, 30, 60, 0.32);
            overflow: hidden;
        }

        .reservation-modal-body {
            min-height: 0;
            overflow-y: auto;
            padding: 18px 20px 22px;
        }

        .reservation-modal-topline {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .modal-context {
            color: var(--muted);
            font-size: 11px;
            font-weight: 850;
        }

        .modal-status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 88px;
            padding: 7px 11px;
            border-radius: 999px;
            border: 1px solid transparent;
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .modal-status.live {
            border-color: #ffb3b3;
            background: #fff0f0;
            color: #d92d2d;
        }

        .modal-status.upcoming,
        .modal-status.pending {
            border-color: var(--warning-border);
            background: var(--warning-bg);
            color: var(--warning-text);
        }

        .modal-status.completed {
            border-color: var(--completed-border);
            background: var(--completed-bg);
            color: var(--completed);
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .detail-item {
            min-width: 0;
            padding: 12px 13px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fbfdfe;
        }

        .detail-item.full {
            grid-column: 1 / -1;
        }

        .detail-label {
            color: var(--muted);
            font-size: 8px;
            font-weight: 900;
            letter-spacing: 0.09em;
            text-transform: uppercase;
        }

        .detail-value {
            margin-top: 5px;
            color: var(--navy-dark);
            font-size: 14px;
            font-weight: 850;
            /* line-height: 1.35; */
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 1380px) {
            .security-main {
                grid-template-columns: minmax(0, 2fr) minmax(430px, 1fr);
            }

            .schedule-grid-header,
            .schedule-row {
                grid-template-columns:
                    70px
                    minmax(135px, 0.9fr)
                    minmax(120px, 0.82fr)
                    minmax(195px, 1.55fr)
                    132px
                    100px;
            }
        }

        @media (max-width: 1120px) {
            .security-main {
                grid-template-columns: 1fr;
                overflow-y: auto;
            }

            .monitor-area {
                min-height: 720px;
            }

            .security-side {
                min-height: 700px;
            }
        }

        @media (max-width: 760px) {
            .display-header {
                padding: 0 18px;
            }

            .header-divider,
            .logo-area {
                display: none;
            }

            .header-building-name {
                max-width: 40vw;
            }

            .security-main {
                padding: 10px 10px 0;
                gap: 10px;
            }

            .toolbar-head {
                flex-direction: column;
                align-items: stretch;
            }

            .toolbar-actions {
                justify-content: flex-start;
            }

            .toolbar-summary {
                width: 100%;
            }

            .summary-chip {
                flex: 1;
            }

            .toolbar-controls {
                align-items: flex-start;
                flex-direction: column;
            }

            .control-divider {
                display: none;
            }

            .schedule-grid-header {
                display: none;
            }

            .schedule-row {
                grid-template-columns: 50px 1fr;
                align-items: start;
            }

            .schedule-cell:nth-child(2),
            .schedule-cell:nth-child(3),
            .schedule-cell:nth-child(4),
            .schedule-cell:nth-child(5),
            .schedule-cell:nth-child(6) {
                grid-column: 2;
            }

            .schedule-status {
                margin-top: 4px;
            }

            .schedule-footer {
                flex-direction: column;
                align-items: flex-start;
            }

            .visitor-lookup-modal,
            .reservation-modal {
                padding: 12px;
            }

            .visitor-lookup-modal-card {
                width: 100%;
                max-height: 94vh;
                height: 94vh;
                border-radius: 16px;
            }

            .visitor-lookup-controls {
                grid-template-columns: 1fr;
            }

            .lookup-day-switch {
                justify-content: flex-start;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .detail-item.full {
                grid-column: auto;
            }
        }
    </style>
</head>

<body>
<div class="security-app" id="securityApp">

    {{-- =========================================================
         HEADER
    ========================================================= --}}

    <header class="display-header">
        <div class="header-left">
            <div class="header-brand">
                <div class="header-title">GITC Info</div>
                <div class="header-subtitle">
                    Garuda Training &amp; Classroom System
                </div>
            </div>

            <div class="header-divider"></div>

            <div class="header-building">
                <div class="header-building-name">
                    Security Monitoring
                </div>
                <div class="header-building-code">
                    SECURITY POST
                </div>
            </div>
        </div>

        <div class="header-right">
            <div class="datetime">
                <div class="header-date" id="headerDate">---</div>
                <div class="header-clock" id="headerClock">--:--:-- WIB</div>
            </div>

            <div class="logo-area">
                <img
                
                src="{{ asset('images/logo/gitc-logo.png') }}"
                alt="GITC Logo"
    
                    alt="Garuda Indonesia Training Center"
                >
            </div>
        </div>
    </header>

    {{-- =========================================================
         MAIN
    ========================================================= --}}

    <main class="security-main">

        {{-- =====================================================
             LEFT — MONITORING
        ====================================================== --}}

        <section class="monitor-area">

            <section class="security-toolbar">
                <div class="toolbar-head">
                    <div class="toolbar-head-main">
                                                <h1 class="section-title" id="mainScheduleTitle">
                            Today's Reservations
                        </h1>
                    </div>

                    <div class="toolbar-actions">
                        <button
                            type="button"
                            class="visitor-lookup-button"
                            id="openVisitorLookup"
                        >
                            VISITOR LOOKUP
                        </button>

                        <div class="toolbar-summary">
                            <div class="summary-chip">
                                <div class="summary-chip-value" id="totalCount">0</div>
                                <div class="summary-chip-label">Reservations</div>
                            </div>

                            <div class="summary-chip">
                                <div class="summary-chip-value" id="liveCount">0</div>
                                <div class="summary-chip-label">Live</div>
                            </div>

                            <div class="summary-chip">
                                <div class="summary-chip-value" id="upcomingCount">0</div>
                                <div class="summary-chip-label">Upcoming</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="toolbar-controls">
                    <div class="control-group">
                        <span class="toolbar-label">Schedule</span>

                        <div class="day-switch">
                            <button
                                type="button"
                                class="day-button active"
                                data-day="today"
                            >
                                TODAY
                            </button>

                            <button
                                type="button"
                                class="day-button"
                                data-day="tomorrow"
                            >
                                TOMORROW
                            </button>
                        </div>
                    </div>

                    <div class="control-divider"></div>

                    <div class="control-group">
                        <span class="toolbar-label">Location</span>

                        <div
                            class="location-switch"
                            id="mainLocationSwitch"
                        >
                            <button
                                type="button"
                                class="location-button active"
                                data-location="ALL"
                            >
                                ALL
                            </button>

                            @foreach ($buildings as $building)
                                <button
                                    type="button"
                                    class="location-button"
                                    data-location="building-{{ $building->id }}"
                                >
                                    {{ $building->name }}
                                </button>
                            @endforeach

                            <button
                                type="button"
                                class="location-button"
                                data-location="FIELD"
                            >
                                FIELD
                            </button>

                        </div>
                    </div>
                </div>
            </section>

            {{-- =================================================
                 TODAY / TOMORROW RESERVATIONS
            ================================================== --}}

            <section class="schedule-panel">
                <div class="panel-header">
                    <div class="panel-title-wrap">
                                                <div class="panel-title" id="schedulePanelTitle">
                            Today's Reservations
                        </div>
                    </div>

                    <div class="panel-count" id="schedulePanelCount">
                        0 reservations
                    </div>
                </div>

                <div class="schedule-grid-header">
                    <div>Building</div>
                    <div>Resource</div>
                    <div>Location</div>
                    <div>Event</div>
                    <div>Time</div>
                    <div>Status</div>
                </div>

                <div
                    class="schedule-list"
                    id="scheduleList"
                >
                    <div class="schedule-empty">
                        Loading reservation data...
                    </div>
                </div>

                <div class="schedule-footer">
                    <span id="scheduleFooterScope">
                        All locations
                    </span>

                    <span>
                        Click any reservation for details
                    </span>

                    <span id="lastUpdated">
                        Updated --:--:--
                    </span>
                </div>
            </section>

        </section>

        {{-- =====================================================
             RIGHT — NEWS
        ====================================================== --}}

        <aside class="security-side">
            <section class="news-panel">

                <div class="news-header">
                    <div class="news-header-main">
                        <div class="news-title">Information</div>
                    </div>

                    <div class="news-counter" id="newsCounter">
                        0 / 0
                    </div>
                </div>

                <div class="news-body" id="newsBody">
                    <div class="news-empty">
                        Loading info...
                    </div>
                </div>

            </section>
        </aside>

    </main>

    {{-- =========================================================
         FOOTER
    ========================================================= --}}

    <footer class="display-footer" style="
        flex: 0 0 4vh;
        min-height: 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 0 2.1vw;
        background: #eef3f6;
        border-top: 1px solid var(--border);
        color: var(--muted);
    ">
        <div style="
            color: var(--navy);
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 0.08em;
        ">
            GITC INFO
        </div>

        <div style="
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 8px;
            font-weight: 800;
        ">
            <span>Safety</span>
            <span>•</span>
            <span>Service</span>
            <span>•</span>
            <span>Excellence</span>
        </div>
    </footer>

    {{-- =========================================================
         VISITOR LOOKUP MODAL
    ========================================================= --}}

    <div
        class="visitor-lookup-modal"
        id="visitorLookupModal"
        aria-hidden="true"
    >
        <div
            class="visitor-lookup-modal-card"
            role="dialog"
            aria-modal="true"
            aria-labelledby="visitorLookupTitle"
        >
            <div class="visitor-lookup-header">
                <div>
                    <div class="visitor-lookup-kicker">
                        Visitor Assistance
                    </div>

                    <h2
                        class="visitor-lookup-title"
                        id="visitorLookupTitle"
                    >
                        Reservation Lookup
                    </h2>

                    <div class="visitor-lookup-subtitle">
                        Search for an event, location, booking, or booker.
                    </div>
                </div>

                <button
                    type="button"
                    class="visitor-lookup-close"
                    id="visitorLookupClose"
                    aria-label="Close visitor lookup"
                >
                    ×
                </button>
            </div>

            <div class="visitor-lookup-body">

                <div class="visitor-lookup-controls">
                    <div class="visitor-search-box">
                        <span class="visitor-search-icon">⌕</span>
                        <input
                            type="search"
                            id="lookupSearch"
                            autocomplete="off"
                            placeholder="Search event, room, field, booking, or booker..."
                        >
                    </div>

                    <div class="lookup-day-switch">
                        <button
                            type="button"
                            class="lookup-day-button active"
                            data-lookup-day="today"
                        >
                            TODAY
                        </button>

                        <button
                            type="button"
                            class="lookup-day-button"
                            data-lookup-day="tomorrow"
                        >
                            TOMORROW
                        </button>
                    </div>
                </div>

                <div class="lookup-location-wrap">
                    <div class="lookup-location-label">
                        Location
                    </div>

                    <div
                        class="lookup-location-switch"
                        id="lookupLocationSwitch"
                    >
                        <button
                            type="button"
                            class="lookup-location-button active"
                            data-lookup-location="ALL"
                        >
                            ALL
                        </button>

                        @foreach ($buildings as $building)
                            <button
                                type="button"
                                class="lookup-location-button"
                                data-lookup-location="building-{{ $building->id }}"
                            >
                                {{ $building->name }}
                            </button>
                        @endforeach

                        <button
                            type="button"
                            class="lookup-location-button"
                            data-lookup-location="FIELD"
                        >
                            FIELD
                        </button>
                    </div>
                </div>

                <div class="lookup-results-meta">
                    <span id="lookupScopeText">All locations</span>
                    <span id="lookupResultsCount">0 results</span>
                </div>

                <div
                    class="lookup-results"
                    id="lookupResults"
                >
                    <div class="lookup-empty">
                        Loading reservation data...
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- =========================================================
         RESERVATION DETAIL MODAL
    ========================================================= --}}

    <div
        class="reservation-modal"
        id="reservationModal"
        aria-hidden="true"
    >
        <div
            class="reservation-modal-card"
            role="dialog"
            aria-modal="true"
            aria-labelledby="reservationModalTitle"
        >
            <div class="reservation-modal-header">
                <div>
                    <div class="reservation-modal-kicker">
                        Reservation Detail
                    </div>

                    <h2
                        class="reservation-modal-title"
                        id="reservationModalTitle"
                    >
                        —
                    </h2>
                </div>

                <button
                    type="button"
                    class="reservation-modal-close"
                    id="reservationModalClose"
                    aria-label="Close reservation detail"
                >
                    ×
                </button>
            </div>

            <div class="reservation-modal-body">
                <div class="reservation-modal-topline">
                    <div
                        class="modal-context"
                        id="reservationModalContext"
                    >
                        —
                    </div>

                    <div
                        class="modal-status upcoming"
                        id="reservationModalStatus"
                    >
                        UPCOMING
                    </div>
                </div>

                <div
                    class="detail-grid"
                    id="reservationModalDetails"
                >
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    /* =========================================================
       CONFIGURATION
    ========================================================= */

    const SECURITY_DATA_URL = @json(route('security.display.data'));

    const REFRESH_INTERVAL = 180000;
    const NEWS_ROTATION_INTERVAL = 30000;
    const RESET_TO_MONITORING_AFTER = 60000;

    /* =========================================================
       STATE
    ========================================================= */

    let securityData = {
        current_time: null,
        today: {
            date: null,
            reservations: [],
        },
        tomorrow: {
            date: null,
            reservations: [],
        },
        buildings: [],
        news: [],
        counts: {},
    };

    let selectedDay = 'today';
    let selectedLocation = 'ALL';

    let lookupDay = 'today';
    let lookupLocation = 'ALL';
    let searchKeyword = '';

    let currentNews = null;
    let currentNewsIndex = 0;
    let newsScrollTimer = null;
    let newsScrollPauseUntil = 0;

    let scheduleScrollTimer = null;
    let scheduleScrollPauseUntil = 0;

    let resetTimer = null;
    let interactionMode = false;

    /* =========================================================
       DOM
    ========================================================= */

    const headerDate = document.getElementById('headerDate');
    const headerClock = document.getElementById('headerClock');

    const mainScheduleTitle = document.getElementById('mainScheduleTitle');
    const totalCount = document.getElementById('totalCount');
    const liveCount = document.getElementById('liveCount');
    const upcomingCount = document.getElementById('upcomingCount');

    const schedulePanelTitle = document.getElementById('schedulePanelTitle');
    const schedulePanelCount = document.getElementById('schedulePanelCount');
    const scheduleList = document.getElementById('scheduleList');
    const scheduleFooterScope = document.getElementById('scheduleFooterScope');
    const lastUpdated = document.getElementById('lastUpdated');

    const openVisitorLookupButton = document.getElementById('openVisitorLookup');
    const visitorLookupModal = document.getElementById('visitorLookupModal');
    const visitorLookupClose = document.getElementById('visitorLookupClose');

    const lookupSearch = document.getElementById('lookupSearch');
    const lookupScopeText = document.getElementById('lookupScopeText');
    const lookupResultsCount = document.getElementById('lookupResultsCount');
    const lookupResults = document.getElementById('lookupResults');

    const newsBody = document.getElementById('newsBody');
    const newsCounter = document.getElementById('newsCounter');

    const reservationModal = document.getElementById('reservationModal');
    const reservationModalClose = document.getElementById('reservationModalClose');
    const reservationModalTitle = document.getElementById('reservationModalTitle');
    const reservationModalContext = document.getElementById('reservationModalContext');
    const reservationModalStatus = document.getElementById('reservationModalStatus');
    const reservationModalDetails = document.getElementById('reservationModalDetails');

    /* =========================================================
       CLOCK
    ========================================================= */

    function updateClock() {
        const now = new Date();

        headerDate.textContent = now.toLocaleDateString('en-GB', {
            weekday: 'long',
            day: '2-digit',
            month: 'long',
            year: 'numeric',
        });

        headerClock.textContent = now.toLocaleTimeString('en-GB', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
        }) + ' WIB';
    }

    updateClock();
    setInterval(updateClock, 1000);

    /* =========================================================
       HELPERS
    ========================================================= */

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function formatTime(value) {
        if (!value) return '—';

        return new Date(value).toLocaleTimeString('en-GB', {
            hour: '2-digit',
            minute: '2-digit',
        });
    }

    function formatDate(value) {
        if (!value) return '—';

        return new Date(value).toLocaleDateString('en-GB', {
            weekday: 'long',
            day: '2-digit',
            month: 'long',
            year: 'numeric',
        });
    }

    function formatShortDate(value) {
        if (!value) return '—';

        return new Date(value).toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        });
    }

    function formatTimeRange(reservation) {
        return `${formatTime(reservation.starts_at)} – ${formatTime(reservation.ends_at)}`;
    }

    function getBuildingLabel(name) {
        if (!name) return 'FIELD';

        return String(name)
            .replace(/^Building\s+/i, '')
            .trim()
            .toUpperCase();
    }

    function getLocationLabel(location = selectedLocation) {
        if (location === 'ALL') {
            return 'All locations';
        }

        if (location === 'FIELD') {
            return 'Field';
        }

        const match = String(location).match(/^building-(\d+)$/);

        if (!match) {
            return 'Selected location';
        }

        const building = securityData.buildings.find(item => {
            return String(item.id) === String(match[1]);
        });

        return building
            ? building.name
            : 'Selected building';
    }

        function getReservationStatus(reservation) {
        const now = new Date();
        const start = new Date(reservation.starts_at);
        const end = new Date(reservation.ends_at);

        if (start <= now && end >= now) {
            return 'LIVE';
        }

        if (start > now) {
            return 'UPCOMING';
        }

        return 'COMPLETED';
    }

    function getStatusClass(status) {
        return String(status || '')
            .toLowerCase()
            .replace(/[^a-z0-9_-]/g, '');
    }

    function getCurrentReservations() {
        return selectedDay === 'today'
            ? (securityData.today.reservations || [])
            : (securityData.tomorrow.reservations || []);
    }

    function filterReservationsByLocation(reservations, location = selectedLocation) {
        if (location === 'ALL') {
            return reservations;
        }

        if (location === 'FIELD') {
            return reservations.filter(reservation => {
                return reservation.resource?.type === 'FIELD';
            });
        }

        const match = String(location).match(/^building-(\d+)$/);

        if (!match) {
            return reservations;
        }

        const buildingId = String(match[1]);

        return reservations.filter(reservation => {
            return String(reservation.building?.id ?? '') === buildingId;
        });
    }

    function getLookupReservations() {
        return lookupDay === 'today'
            ? (securityData.today.reservations || [])
            : (securityData.tomorrow.reservations || []);
    }

    function matchesSearch(reservation) {
        const keyword = searchKeyword.trim().toLowerCase();

        if (!keyword) {
            return true;
        }

        const haystack = [
            reservation.event_name,
            reservation.reservation_number,
            reservation.booker_name,
            reservation.user_name,
            reservation.resource?.type,
            reservation.resource?.name,
            reservation.building?.name,
            reservation.location_name,
        ]
            .filter(Boolean)
            .join(' ')
            .toLowerCase();

        return haystack.includes(keyword);
    }

    /* =========================================================
       INTERACTION MODE / AUTO RESET
    ========================================================= */

    function enterInteractionMode() {
        interactionMode = true;

        clearTimeout(resetTimer);

        resetTimer = setTimeout(() => {
            resetToMonitoringMode();
        }, RESET_TO_MONITORING_AFTER);

        scheduleScrollPauseUntil = Date.now() + 1800;
        newsScrollPauseUntil = Date.now() + 1800;
    }

    function resetToMonitoringMode() {
        interactionMode = false;
        clearTimeout(resetTimer);

        selectedDay = 'today';
        selectedLocation = 'ALL';
        lookupDay = 'today';
        lookupLocation = 'ALL';
        searchKeyword = '';

        lookupSearch.value = '';

        visitorLookupModal.classList.remove('open');
        visitorLookupModal.setAttribute('aria-hidden', 'true');

        reservationModal.classList.remove('open');
        reservationModal.setAttribute('aria-hidden', 'true');

        document.querySelectorAll('.day-button').forEach(button => {
            button.classList.toggle(
                'active',
                button.dataset.day === 'today'
            );
        });

        syncLocationButtons();
        syncLookupControls();
        renderCurrentViews(true);
    }

    /* =========================================================
       MAIN CONTROL SYNC
    ========================================================= */

    function syncLocationButtons() {
        document.querySelectorAll('.location-button').forEach(button => {
            button.classList.toggle(
                'active',
                button.dataset.location === selectedLocation
            );
        });

        scheduleFooterScope.textContent = getLocationLabel(selectedLocation);
    }

    function syncLookupControls() {
        document.querySelectorAll('.lookup-day-button').forEach(button => {
            button.classList.toggle(
                'active',
                button.dataset.lookupDay === lookupDay
            );
        });

        document.querySelectorAll('.lookup-location-button').forEach(button => {
            button.classList.toggle(
                'active',
                button.dataset.lookupLocation === lookupLocation
            );
        });

        const scope = lookupLocation === 'ALL'
            ? 'All locations'
            : getLocationLabel(lookupLocation);

        lookupScopeText.textContent = `${scope} • ${lookupDay === 'today' ? 'Today' : 'Tomorrow'}`;
    }

    /* =========================================================
       RENDER MAIN
    ========================================================= */

    function renderCurrentViews(restartScroll = false) {
        const previousScrollTop = restartScroll
            ? 0
            : scheduleList.scrollTop;

        renderMainSummary();
        renderSchedule();
        renderLookupResults();
        syncLocationButtons();
        syncLookupControls();

        if (restartScroll) {
            restartScheduleAutoScroll();
            return;
        }

        requestAnimationFrame(() => {
            scheduleList.scrollTop = previousScrollTop;
        });
    }

    function renderMainSummary() {
        const reservations = filterReservationsByLocation(
            getCurrentReservations(),
            selectedLocation
        );

        const live = reservations.filter(reservation => {
            return getReservationStatus(reservation) === 'LIVE';
        });

        const upcoming = reservations.filter(reservation => {
            const status = getReservationStatus(reservation);
            return status === 'UPCOMING' || status === 'PENDING';
        });

        const dayLabel = selectedDay === 'today'
            ? "Today's Reservations"
            : "Tomorrow's Reservations";

        const countLabel = `${reservations.length} reservation${reservations.length === 1 ? '' : 's'}`;

        mainScheduleTitle.textContent = dayLabel;
        schedulePanelTitle.textContent = dayLabel;
        schedulePanelCount.textContent = countLabel;

        totalCount.textContent = reservations.length;
        liveCount.textContent = live.length;
        upcomingCount.textContent = upcoming.length;

        scheduleFooterScope.textContent = getLocationLabel(selectedLocation);
    }

    /* =========================================================
       SCHEDULE
    ========================================================= */

    function renderSchedule() {
        const reservations = filterReservationsByLocation(
            getCurrentReservations(),
            selectedLocation
        )
            .slice()
            .sort((a, b) => {
                return new Date(a.starts_at) - new Date(b.starts_at);
            });

        if (!reservations.length) {
            scheduleList.innerHTML = `
                <div class="schedule-empty">
                    No reservations found for this selection.
                </div>
            `;
            return;
        }

        scheduleList.innerHTML = reservations
            .map(renderScheduleRow)
            .join('');
    }

    function renderScheduleRow(reservation) {
        const status = getReservationStatus(reservation);
        const statusClass = getStatusClass(status);

        const rowClass = [
            'schedule-row',
            status === 'LIVE' ? 'current' : '',
            status === 'PENDING' ? 'pending' : '',
        ]
            .filter(Boolean)
            .join(' ');

        const isField = reservation.resource?.type === 'FIELD';
        const buildingLabel = reservation.building?.name
            ? getBuildingLabel(reservation.building.name)
            : 'FIELD';

        const resourceType = reservation.resource?.type === 'TRAINING MEDIA'
            ? 'TRAINING MEDIA'
            : (reservation.resource?.type || 'RESOURCE');

        const resourceName = reservation.resource?.name || '—';
        const locationName = reservation.location_name || resourceName;

        return `
            <div
                class="${rowClass}"
                data-reservation-id="${escapeHtml(reservation.id)}"
                tabindex="0"
                role="button"
                aria-label="View reservation details for ${escapeHtml(reservation.event_name || resourceName)}"
            >
                <div class="schedule-cell">
                    <div class="schedule-building ${isField ? 'field' : ''}">
                        ${escapeHtml(buildingLabel)}
                    </div>
                </div>

                <div class="schedule-cell">
                    <div class="schedule-type">
                        ${escapeHtml(resourceType)}
                    </div>
                    <div class="schedule-resource">
                        ${escapeHtml(resourceName)}
                    </div>
                </div>

                <div class="schedule-cell">
                    <div class="schedule-location">
                        ${escapeHtml(locationName)}
                    </div>
                </div>

                <div class="schedule-cell">
                    <div class="schedule-event">
                        ${escapeHtml(reservation.event_name || 'Untitled event')}
                    </div>
                </div>

                <div class="schedule-cell">
                    <div class="schedule-time">
                        ${escapeHtml(formatTimeRange(reservation))}
                    </div>
                    <div class="schedule-time-date">
                        ${escapeHtml(formatShortDate(reservation.starts_at))}
                    </div>
                </div>

                <div class="schedule-cell">
                    <span class="schedule-status ${statusClass}">
                        ${escapeHtml(status)}
                    </span>
                </div>
            </div>
        `;
    }

    /* =========================================================
       VISITOR LOOKUP
    ========================================================= */

    function renderLookupResults() {
        const reservations = filterReservationsByLocation(
            getLookupReservations(),
            lookupLocation
        )
            .filter(matchesSearch)
            .slice()
            .sort((a, b) => {
                return new Date(a.starts_at) - new Date(b.starts_at);
            });

        lookupResultsCount.textContent = `${reservations.length} result${reservations.length === 1 ? '' : 's'}`;

        if (!reservations.length) {
            lookupResults.innerHTML = `
                <div class="lookup-empty">
                    No matching reservation was found for ${escapeHtml(getLocationLabel(lookupLocation))} on ${lookupDay === 'today' ? 'today' : 'tomorrow'}.
                </div>
            `;
            return;
        }

        lookupResults.innerHTML = reservations
            .slice(0, 50)
            .map(renderLookupResult)
            .join('');
    }

    function renderLookupResult(reservation) {
        const status = getReservationStatus(reservation);
        const statusClass = getStatusClass(status);

        const locationLabel = reservation.building?.name
            ? reservation.building.name
            : 'Field';

        const resourceLabel = [
            reservation.resource?.type,
            reservation.resource?.name,
        ]
            .filter(Boolean)
            .join(' • ');

        const bookedBy = reservation.booker_name
            || reservation.user_name
            || '';

        return `
            <button
                type="button"
                class="lookup-result"
                data-reservation-id="${escapeHtml(reservation.id)}"
            >
                <div class="lookup-result-top">
                    <div class="lookup-result-location">
                        ${escapeHtml(locationLabel)}
                    </div>

                    <div class="lookup-result-time">
                        ${escapeHtml(formatTimeRange(reservation))}
                    </div>
                </div>

                <div class="lookup-result-event">
                    ${escapeHtml(reservation.event_name || 'Untitled event')}
                </div>

                <div class="lookup-result-resource">
                    ${escapeHtml(resourceLabel || 'Resource information unavailable')}
                </div>

                ${bookedBy ? `
                    <div class="lookup-result-booker">
                        Booked by: ${escapeHtml(bookedBy)}
                    </div>
                ` : ''}

                <div class="lookup-result-footer">
                    <span class="lookup-view-label">
                        View Details
                    </span>

                    <span class="schedule-status ${statusClass}">
                        ${escapeHtml(status)}
                    </span>
                </div>
            </button>
        `;
    }

    /* =========================================================
       RESERVATION DETAIL
    ========================================================= */

    function findReservationById(id) {
        const reservations = [
            ...(securityData.today.reservations || []),
            ...(securityData.tomorrow.reservations || []),
        ];

        return reservations.find(item => {
            return String(item.id) === String(id);
        });
    }

    function openReservationModal(reservation) {
        if (!reservation) return;

        enterInteractionMode();

        const status = getReservationStatus(reservation);
        const resourceType = reservation.resource?.type || 'RESOURCE';
        const resourceName = reservation.resource?.name || '—';
        const buildingName = reservation.building?.name || 'Field';
        const bookedBy = reservation.booker_name
            || reservation.user_name
            || 'Not specified';

        reservationModalTitle.textContent = reservation.event_name || 'Reservation Detail';
        reservationModalContext.textContent = `${buildingName} • ${resourceType}`;

        reservationModalStatus.textContent = status;
        reservationModalStatus.className = `modal-status ${getStatusClass(status)}`;

        reservationModalDetails.innerHTML = `
            ${createDetailItem('Date', formatDate(reservation.starts_at))}
            ${createDetailItem('Time', `${formatTime(reservation.starts_at)} – ${formatTime(reservation.ends_at)} WIB`)}
            ${createDetailItem('Building', buildingName)}
            ${createDetailItem('Resource', resourceType)}
            ${createDetailItem('Location', resourceName)}
            ${createDetailItem('Booked By', bookedBy)}
            ${reservation.total_person !== null && reservation.total_person !== undefined
                ? createDetailItem('Participants', reservation.total_person)
                : ''}
        `;

        reservationModal.classList.add('open');
        reservationModal.setAttribute('aria-hidden', 'false');
    }

    function createDetailItem(label, value, full = false) {
        return `
            <div class="detail-item ${full ? 'full' : ''}">
                <div class="detail-label">
                    ${escapeHtml(label)}
                </div>
                <div class="detail-value">
                    ${escapeHtml(value)}
                </div>
            </div>
        `;
    }

    function closeReservationModal() {
        reservationModal.classList.remove('open');
        reservationModal.setAttribute('aria-hidden', 'true');
        enterInteractionMode();
    }

    reservationModalClose.addEventListener('click', closeReservationModal);

    reservationModal.addEventListener('click', event => {
        if (event.target === reservationModal) {
            closeReservationModal();
        }
    });

    /* =========================================================
       EVENT DELEGATION — MAIN SCHEDULE
    ========================================================= */

    scheduleList.addEventListener('click', event => {
        const row = event.target.closest('[data-reservation-id]');

        if (!row) return;

        const reservation = findReservationById(
            row.dataset.reservationId
        );

        openReservationModal(reservation);
    });

    scheduleList.addEventListener('keydown', event => {
        if (event.key !== 'Enter' && event.key !== ' ') {
            return;
        }

        const row = event.target.closest('[data-reservation-id]');

        if (!row) return;

        event.preventDefault();

        const reservation = findReservationById(
            row.dataset.reservationId
        );

        openReservationModal(reservation);
    });

    /* =========================================================
       EVENT DELEGATION — LOOKUP RESULTS
    ========================================================= */

    lookupResults.addEventListener('click', event => {
        const button = event.target.closest('[data-reservation-id]');

        if (!button) return;

        const reservation = findReservationById(
            button.dataset.reservationId
        );

        openReservationModal(reservation);
    });

    /* =========================================================
       DAY BUTTONS
    ========================================================= */

    document.querySelectorAll('.day-button').forEach(button => {
        button.addEventListener('click', () => {
            enterInteractionMode();

            selectedDay = button.dataset.day;

            document.querySelectorAll('.day-button').forEach(item => {
                item.classList.toggle(
                    'active',
                    item.dataset.day === selectedDay
                );
            });

            renderCurrentViews(true);
        });
    });

    /* =========================================================
       LOCATION BUTTONS
    ========================================================= */

    document.querySelectorAll('.location-button').forEach(button => {
        button.addEventListener('click', () => {
            enterInteractionMode();

            selectedLocation = button.dataset.location;

            renderCurrentViews(true);
        });
    });

    const clearScopeButton = document.getElementById('clearScopeButton');

    if (clearScopeButton) {
        clearScopeButton.addEventListener('click', () => {
            enterInteractionMode();

            selectedLocation = 'ALL';

            renderCurrentViews(true);
        });
    }

    /* =========================================================
       VISITOR LOOKUP MODAL
    ========================================================= */

    function openVisitorLookup() {
        enterInteractionMode();

        lookupDay = selectedDay;
        lookupLocation = 'ALL';
        searchKeyword = '';
        lookupSearch.value = '';

        syncLookupControls();
        renderLookupResults();

        visitorLookupModal.classList.add('open');
        visitorLookupModal.setAttribute('aria-hidden', 'false');

        setTimeout(() => {
            lookupSearch.focus();
        }, 90);
    }

    function closeVisitorLookup() {
        visitorLookupModal.classList.remove('open');
        visitorLookupModal.setAttribute('aria-hidden', 'true');
        enterInteractionMode();
    }

    if (openVisitorLookupButton) {
        openVisitorLookupButton.addEventListener('click', openVisitorLookup);
    }

    visitorLookupClose.addEventListener('click', closeVisitorLookup);

    visitorLookupModal.addEventListener('click', event => {
        if (event.target === visitorLookupModal) {
            closeVisitorLookup();
        }
    });

    document.querySelectorAll('.lookup-day-button').forEach(button => {
        button.addEventListener('click', () => {
            enterInteractionMode();

            lookupDay = button.dataset.lookupDay;

            syncLookupControls();
            renderLookupResults();
        });
    });

    document.querySelectorAll('.lookup-location-button').forEach(button => {
        button.addEventListener('click', () => {
            enterInteractionMode();

            lookupLocation = button.dataset.lookupLocation;

            syncLookupControls();
            renderLookupResults();
        });
    });

    lookupSearch.addEventListener('input', event => {
        enterInteractionMode();

        searchKeyword = event.target.value || '';

        renderLookupResults();
    });

    lookupSearch.addEventListener('focus', () => {
        enterInteractionMode();
    });

    /* =========================================================
       ESC KEY
    ========================================================= */

    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape') {
            return;
        }

        if (reservationModal.classList.contains('open')) {
            closeReservationModal();
            return;
        }

        if (visitorLookupModal.classList.contains('open')) {
            closeVisitorLookup();
            return;
        }

        resetToMonitoringMode();
    });

    /* =========================================================
       SECURITY DATA LOAD
    ========================================================= */

    async function loadSecurityData() {
        try {
            const response = await fetch(
                SECURITY_DATA_URL,
                {
                    cache: 'no-store',
                    headers: {
                        'Accept': 'application/json',
                    },
                }
            );

            if (!response.ok) {
                throw new Error(`Security API error: ${response.status}`);
            }

            const data = await response.json();

            securityData = {
                current_time: data.current_time || null,
                today: data.today || {
                    date: null,
                    reservations: [],
                },
                tomorrow: data.tomorrow || {
                    date: null,
                    reservations: [],
                },
                buildings: data.buildings || [],
                news: data.news || [],
                counts: data.counts || {},
            };

            renderCurrentViews(false);
            updateLastUpdated();
            updateNewsFromServer();

            console.log(
                '[SECURITY] Data refreshed:',
                new Date().toLocaleTimeString('en-GB')
            );
        } catch (error) {
            console.error('[SECURITY] Failed to load data:', error);

            if (!securityData.today.reservations.length && !securityData.tomorrow.reservations.length) {
                scheduleList.innerHTML = `
                    <div class="schedule-empty">
                        Reservation data is temporarily unavailable.
                    </div>
                `;

                lookupResults.innerHTML = `
                    <div class="lookup-empty">
                        Reservation lookup is temporarily unavailable.
                    </div>
                `;
            }
        }
    }

    function updateLastUpdated() {
        lastUpdated.textContent = 'Updated ' + new Date().toLocaleTimeString('en-GB', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
        });
    }

    /* =========================================================
       NEWS
    ========================================================= */

    function updateNewsFromServer() {
        const nextNews = Array.isArray(securityData.news)
            ? securityData.news
            : [];

        if (!nextNews.length) {
            currentNews = null;
            currentNewsIndex = 0;
            renderNews();
            return;
        }

        if (currentNews) {
            const matchingIndex = nextNews.findIndex(item => {
                return String(item.id) === String(currentNews.id);
            });

            if (matchingIndex !== -1) {
                const nextCurrentNews = nextNews[matchingIndex];
                const changed = JSON.stringify(nextCurrentNews) !== JSON.stringify(currentNews);

                currentNews = nextCurrentNews;
                currentNewsIndex = matchingIndex;

                if (changed) {
                    renderNews();
                } else {
                    updateNewsCounter(nextNews.length);
                }

                return;
            }

            updateNewsCounter(nextNews.length);
            return;
        }

        currentNews = nextNews[0];
        currentNewsIndex = 0;
        renderNews();
    }

    function updateNewsCounter(total) {
        if (!newsCounter) return;

        if (!total || !currentNews) {
            newsCounter.textContent = '0 / 0';
            return;
        }

        newsCounter.textContent = `${currentNewsIndex + 1} / ${total}`;
    }

    function rotateNews() {
        const items = Array.isArray(securityData.news)
            ? securityData.news
            : [];

        if (!items.length) {
            currentNews = null;
            currentNewsIndex = 0;
            renderNews();
            return;
        }

        if (items.length === 1) {
            currentNews = items[0];
            currentNewsIndex = 0;
            updateNewsCounter(1);
            return;
        }

        const currentIndex = currentNews
            ? items.findIndex(item => String(item.id) === String(currentNews.id))
            : -1;

        currentNewsIndex = currentIndex >= 0
            ? (currentIndex + 1) % items.length
            : 0;

        currentNews = items[currentNewsIndex];

        renderNews();
    }

    function renderNews() {
        clearInterval(newsScrollTimer);

        const items = Array.isArray(securityData.news)
            ? securityData.news
            : [];

        updateNewsCounter(items.length);

        if (!currentNews) {
            newsBody.innerHTML = `
                <div class="news-empty">
                    No active published news.
                </div>
            `;
            return;
        }

        const image = currentNews.image_url
            ? `
                <div class="news-image-wrap">
                    <img
                        src="${escapeHtml(currentNews.image_url)}"
                        alt="${escapeHtml(currentNews.title || 'News image')}"
                    >
                </div>
            `
            : `
                <div class="news-image-wrap">
                    <div class="news-image-empty">
                        No Image
                    </div>
                </div>
            `;

        const content = currentNews.content || 'No additional content.';

        newsBody.innerHTML = `
            <article class="news-post news-slide-in-right">
                ${image}

                <div class="news-content-wrap">
                    <div class="news-content-title">
                        ${escapeHtml(currentNews.title || 'Untitled News')}
                    </div>

                    <div class="news-scroll-box" id="newsScrollBox">
                        <div class="news-scroll-content" id="newsScrollContent">
                            ${content}
                        </div>
                    </div>
                </div>
            </article>
        `;

        startNewsAutoScroll();
    }

    function startNewsAutoScroll() {
        clearInterval(newsScrollTimer);

        const box = document.getElementById('newsScrollBox');

        if (!box) return;

        box.scrollTop = 0;

        let direction = 1;

        newsScrollTimer = setInterval(() => {
            if (Date.now() < newsScrollPauseUntil) {
                return;
            }

            const maxScroll = box.scrollHeight - box.clientHeight;

            if (maxScroll <= 5) {
                return;
            }

            if (direction > 0) {
                box.scrollTop += 0.7;

                if (box.scrollTop >= maxScroll - 2) {
                    newsScrollPauseUntil = Date.now() + 2200;
                    direction = -1;
                }
            } else {
                box.scrollTop -= 0.7;

                if (box.scrollTop <= 2) {
                    newsScrollPauseUntil = Date.now() + 1200;
                    direction = 1;
                }
            }
        }, 40);
    }

    setInterval(rotateNews, NEWS_ROTATION_INTERVAL);

    /* =========================================================
       SCHEDULE AUTO SCROLL
    ========================================================= */

    function restartScheduleAutoScroll() {
        clearInterval(scheduleScrollTimer);

        scheduleList.scrollTop = 0;
        scheduleScrollPauseUntil = Date.now() + 1600;

        scheduleScrollTimer = setInterval(() => {
            if (interactionMode) {
                return;
            }

            if (Date.now() < scheduleScrollPauseUntil) {
                return;
            }

            const maxScroll = scheduleList.scrollHeight - scheduleList.clientHeight;

            if (maxScroll <= 5) {
                return;
            }

            scheduleList.scrollTop += 0.55;

            if (scheduleList.scrollTop >= maxScroll - 2) {
                scheduleScrollPauseUntil = Date.now() + 2400;

                setTimeout(() => {
                    if (!interactionMode) {
                        scheduleList.scrollTop = 0;
                    }
                }, 2400);
            }
        }, 40);
    }

    /* =========================================================
       INITIALIZE
    ========================================================= */

    syncLocationButtons();
    syncLookupControls();
    renderCurrentViews(true);
    loadSecurityData();

    setInterval(loadSecurityData, REFRESH_INTERVAL);
</script>
</body>
</html>
