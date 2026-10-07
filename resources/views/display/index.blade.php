<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/icons/logo/Garuda.svg') }}">

    <title>
        GITC Info - {{ $building->name }}
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
       
        

        /* =========================================================
           ROOT
        ========================================================= */

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

            --success: #43d8b3;
            --danger: #e53935;
            --warning: #f3c75d;

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

        .display-app {

            width: 100vw;
            height: 100vh;

            display: flex;

            flex-direction: column;

            overflow: hidden;

        }


        /* =========================================================
           HEADER
        ========================================================= */

        .display-header {

            flex:
                0 0
                13vh;

            min-height: 94px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding:
                0
                3vw;

            color:
                var(--white);

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


        .display-header::after {

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

            background:
                rgba(
                    0,
                    168,
                    200,
                    0.06
                );

            transform:
                rotate(-15deg);

            pointer-events: none;

        }


        /* =========================================================
           HEADER LEFT
        ========================================================= */

        .header-left {

            display: flex;

            align-items: center;

            gap: 1.1vw;

            min-width: 0;

            position: relative;

            z-index: 2;

        }


        .header-brand {

            min-width: 0;

        }


        .header-title {

            font-size:
                clamp(
                    20px,
                    1.65vw,
                    32px
                );

            font-weight: 800;

            

        }


        .header-subtitle {

            margin-top: 5px;

            color:
                rgba(
                    255,
                    255,
                    255,
                    0.70
                );

            font-size:
                clamp(
                    10px,
                    0.82vw,
                    16px
                );

        }


        .header-divider {

            width: 1px;

            height: 52px;

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.25
                );

            margin:
                0
                0.6vw;

            flex:
                0 0 1px;

        }


        .header-building {

            min-width: 0;

        }


        .header-building-label {

            margin-bottom: 4px;

            color:
                rgba(
                    255,
                    255,
                    255,
                    0.55
                );

            font-family:
                "Courier New",
                monospace;

            font-size:
                clamp(
                    9px,
                    0.62vw,
                    13px
                );

            letter-spacing:
                0.12em;

            text-transform:
                uppercase;

        }


        .header-building-name {

            max-width: 28vw;

            overflow: visible;

            text-overflow: ellipsis;

            white-space: nowrap;

            font-size:
                clamp(
                    19px,
                    1.55vw,
                    30px
                );

            font-weight: 800;

        }


        .header-building-code {

            margin-top: 3px;

            color:
                rgba(
                    255,
                    255,
                    255,
                    0.67
                );

            font-size:
                clamp(
                    9px,
                    0.62vw,
                    13px
                );

        }


        /* =========================================================
           HEADER RIGHT
        ========================================================= */

        .header-right {

            display: flex;

            align-items: center;

            gap: 1.5vw;

            position: relative;

            z-index: 2;

        }


        .datetime {

            text-align: right;

        }


        .header-date {

            color:
                rgba(
                    255,
                    255,
                    255,
                    0.78
                );

            font-size:
                clamp(
                    10px,
                    0.75vw,
                    15px
                );

        }


        .header-clock {

            margin-top: 4px;

            color:
                #69e5f5;

            font-size:
                clamp(
                    22px,
                    2.15vw,
                    42px
                );

            font-weight: 800;

            

            white-space: nowrap;

        }


        .logo-area {

            width:
                clamp(
                    150px,
                    14vw,
                    280px
                );

            height: 64px;

            display: flex;

            align-items: center;

            justify-content: center;

            padding:
                5px
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

            display: block;

            max-width: 100%;
            max-height: 54px;

            width: auto;
            height: auto;

            object-fit: contain;

        }


        /* =========================================================
           MAIN
        ========================================================= */

        .display-main {

            flex: 1;

            min-height: 0;

            display: grid;

            grid-template-columns:
                minmax(0, 2.15fr)
                minmax(330px, 0.95fr);

            grid-template-rows:
                minmax(0, 1fr);

            gap:
                1.1vw;

            padding:
                1.1vw
                2.2vw
                0;

            overflow: hidden;

        }


        /* =========================================================
           LEFT AREA
        ========================================================= */

        .left-area {

            min-width: 0;

            min-height: 0;

            display: grid;

            grid-template-rows:
                minmax(0, 1.35fr)
             minmax(210px, 1fr);

            gap:
                1.1vw;

            overflow: hidden;
        }


        /* =========================================================
           HERO
        ========================================================= */

        .hero {

            min-width: 0;

            min-height: 0;

            position: relative;

            overflow: hidden;

            border-radius:
                var(--radius-large);

            color:
                var(--white);

            background:

                radial-gradient(
                    ellipse at 85% 28%,
                    rgba(
                        0,
                        180,
                        220,
                        0.20
                    ),
                    transparent 30%
                ),

                linear-gradient(
                    100deg,
                    rgba(
                        0,
                        31,
                        60,
                        0.99
                    ) 0%,
                    rgba(
                        0,
                        55,
                        95,
                        0.92
                    ) 50%,
                    rgba(
                        0,
                        78,
                        105,
                        0.75
                    ) 100%
                );

            box-shadow:
                0 12px 35px
                rgba(
                    0,
                    40,
                    75,
                    0.18
                );

            border:
                1px solid
                rgba(
                    0,
                    160,
                    200,
                    0.35
                );

        }


        .hero::before {

            content: "";

            position: absolute;

            inset: 0;

            background:

                linear-gradient(
                    120deg,
                    rgba(
                        255,
                        255,
                        255,
                        0.04
                    ),
                    transparent 42%,
                    rgba(
                        0,
                        0,
                        0,
                        0.14
                    )
                );

            pointer-events: none;

        }


        .hero::after {

            content: "";

            position: absolute;

            width: 36vw;
            height: 36vw;

            right: -17vw;
            top: -15vw;

            border-radius: 50%;

            border:
                1px solid
                rgba(
                    255,
                    255,
                    255,
                    0.07
                );

            box-shadow:
                0 0 0 24px
                rgba(
                    255,
                    255,
                    255,
                    0.015
                ),
                0 0 0 48px
                rgba(
                    255,
                    255,
                    255,
                    0.01
                );

            pointer-events: none;

        }


       .hero-content {
            position: relative;
            z-index: 3;

            height: 100%;
            min-height: 0;

            padding:
                42px
                3vw
                42px;

            display: flex;
            flex-direction: column;
            justify-content: flex-start;

            visibility: hidden;
            overflow: hidden;
        }


        .hero-content.ready {

            visibility: visible;

        }


        /* =========================================================
           HERO STATUS
        ========================================================= */

        .hero-status {

            width: fit-content;

            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding:
                8px
                14px;

            border:
                1px solid
                rgba(
                    255,
                    90,
                    90,
                    0.75
                );

            border-radius: 999px;

            background:
                rgba(
                    220,
                    40,
                    40,
                    0.14
                );

            color:
                #ff9b9b;

            font-size:
                clamp(
                    10px,
                    0.72vw,
                    15px
                );

            font-weight: 800;

            letter-spacing:
                0.04em;

            text-transform:
                uppercase;

            white-space: nowrap;

        }


        .hero-status-dot {

            width: 8px;
            height: 8px;

            border-radius: 50%;

            background:
                #ff5151;

            box-shadow:
                0 0 12px
                rgba(
                    255,
                    81,
                    81,
                    0.9
                );

            flex:
                0 0 8px;

        }


        .hero-status.upcoming {

            border-color:
                rgba(
                    255,
                    210,
                    100,
                    0.72
                );

            background:
                rgba(
                    255,
                    190,
                    70,
                    0.11
                );

            color:
                #ffe4a5;

        }


        .hero-status.upcoming
        .hero-status-dot {

            background:
                #ffd46d;

            box-shadow:
                0 0 12px
                rgba(
                    255,
                    212,
                    109,
                    0.85
                );

        }


        .hero-status.empty {

            border-color:
                rgba(
                    255,
                    255,
                    255,
                    0.25
                );

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.05
                );

            color:
                rgba(
                    255,
                    255,
                    255,
                    0.70
                );

        }


        .hero-status.empty
        .hero-status-dot {

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.4
                );

            box-shadow:
                none;

        }


        /* =========================================================
           HERO CAROUSEL
        ========================================================= */

        .hero-carousel {

            margin-top:
                2.2vh;

            width: 100%;

            min-width: 0;

            min-height: 0;

            overflow: hidden;

        }


        .hero-carousel-track {

            display: flex;

            width: 100%;

            min-width: 100%;

            transition:
                transform
                0.8s
                cubic-bezier(
                    0.22,
                    0.61,
                    0.36,
                    1
                );

            will-change:
                transform;

        }


        .hero-slide {

            flex:
                0 0 100%;

            width: 100%;

            min-width: 100%;

            display: block;

            animation: none;

        }


        .hero-slide.active {

            display: block;

        }


        @keyframes heroFade {

            from {

                opacity: 0;

                transform:
                    translateY(8px);

            }

            to {

                opacity: 1;

                transform:
                    translateY(0);

            }

        }


        .hero-label {

            margin-bottom:
                10px;

            color:
                #71e5f5;

            font-size:
                clamp(
                    13px,
                    1.0vw,
                    21px
                );

            font-weight: 800;

            letter-spacing:
                0.12em;

            text-transform:
                uppercase;

        }


        .hero-title {

            margin: 0;

            max-width: 88%;

            color:
                #ffffff;

            font-size:
                clamp(
                    26px,
                    2.35vw,
                    50px
                );

            

            font-weight:
                700;

            letter-spacing:
                -0.025em;

            overflow-wrap:
                anywhere;

            word-break:
                normal;

            display:
                -webkit-box;

            -webkit-line-clamp:
                3;

            -webkit-box-orient:
                vertical;

            overflow:
                visible;

        }


        .hero-meta {

            display: grid;

            grid-template-columns:
                minmax(0, 1fr)
                minmax(0, 1fr);

            gap:
                3vw;

            margin-top:
                3.4vh;

            max-width:
                82%;

        }


        .hero-meta-item {

            min-width: 0;

            display: flex;

            flex-direction: column;

        }


        .hero-meta-label {

            margin-bottom:
                6px;

            color:
                rgba(
                    255,
                    255,
                    255,
                    0.50
                );

            font-size:
                clamp(
                    10px,
                    0.68vw,
                    14px
                );

            font-weight:
                700;

            letter-spacing:
                0.13em;

            text-transform:
                uppercase;

        }


        .hero-room {

            overflow: hidden;

            text-overflow: ellipsis;

            white-space: nowrap;

            color:
                #ffffff;

            font-size:
                clamp(
                    23px,
                    1.75vw,
                    38px
                );

            font-weight:
                800;

          

        }


        .hero-time {

            color:
                #71e5f5;

            font-size:
                clamp(
                    23px,
                    1.75vw,
                    38px
                );

            font-weight:
                800;

            line-height:
                1.05;

            white-space:
                nowrap;

        }


        /* =========================================================
           HERO EMPTY
        ========================================================= */

        .hero-empty {

            max-width:
                80%;

        }


        .hero-empty-title {

            margin-top:
                2vh;

            color:
                #ffffff;

            font-size:
                clamp(
                    30px,
                    2.55vw,
                    54px
                );

            font-weight:
                800;

            line-height:
                1.08;

        }


        .hero-empty-text {

            margin-top:
                12px;

            color:
                rgba(
                    255,
                    255,
                    255,
                    0.68
                );

            font-size:
                clamp(
                    12px,
                    0.82vw,
                    17px
                );

            line-height:
                1.5;

        }


        /* =========================================================
           HERO PAGINATION
        ========================================================= */

        .hero-bottom {

            position: absolute;

            left:
                3vw;

            right:
                3vw;

            bottom:
                1.65vw;

            z-index: 4;

            display: flex;

            align-items: center;

        }


        .pagination {

            display: flex;

            align-items: center;

            gap: 8px;

        }


        .pagination-dot {

            width: 8px;
            height: 8px;

            border-radius: 50%;

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.26
                );

            transition:
                0.3s ease;

        }


        .pagination-dot.active {

            width: 20px;

            border-radius:
                999px;

            background:
                #63dff0;

            box-shadow:
                0 0 10px
                rgba(
                    99,
                    223,
                    240,
                    0.65
                );

        }


        .pagination-number {

            margin-left:
                8px;

            color:
                rgba(
                    255,
                    255,
                    255,
                    0.65
                );

            font-family:
                "Garuda Sans",
                Arial,
                sans-serif;

            font-size:
                clamp(
                    10px,
                    0.68vw,
                    14px
                );

        }


        .hero-detail-link {

            position: absolute;

            right:
                2.2vw;

            bottom:
                1.25vw;

            z-index:
                10;

            display:
                inline-flex;

            align-items:
                center;

            justify-content:
                center;

            min-height:
                42px;

            padding:
                0
                18px;

            border:
                1px solid
                rgba(
                    255,
                    255,
                    255,
                    0.30
                );

            border-radius:
                999px;

            background:
                rgba(
                    0,
                    30,
                    55,
                    0.45
                );

            color:
                rgba(
                    255,
                    255,
                    255,
                    0.92
                );

            text-decoration:
                none;

            font-family:
                "Garuda Sans",
                Arial,
                sans-serif;

            font-size:
                10px;

            font-weight:
                800;

            letter-spacing:
                0.04em;

            text-transform:
                uppercase;

            backdrop-filter:
                blur(6px);

            transition:
                background 0.2s ease,
                transform 0.2s ease;

        }


        .hero-detail-link:hover {

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.12
                );

            transform:
                translateY(-1px);

        }


        /* =========================================================
           LOWER LEFT
        ========================================================= */

        .lower-left {

            min-width: 0;

            min-height: 0;

            display: grid;

            grid-template-columns:
                minmax(0, 1fr)
                minmax(0, 1fr);

            gap:
                1.1vw;

            overflow: hidden;

        }


        /* =========================================================
           GENERIC WHITE CARD
        ========================================================= */

        .info-card {

            min-width: 0;

            min-height: 0;

            display: flex;

            flex-direction: column;

            overflow: hidden;

            background:
                #ffffff;

            border:
                1px solid
                var(--border);

            border-radius:
                var(--radius-large);

            box-shadow:
                0 8px 18px
                rgba(
                    0,
                    41,
                    79,
                    0.08
                ),
                0 2px 5px
                rgba(
                    0,
                    41,
                    79,
                    0.05
                );

        }


        /* =========================================================
           CARD HEADER
        ========================================================= */

        .card-header {

            flex: 0 0 auto;

            display: flex;

            align-items: flex-start;

            justify-content: space-between;

            gap: 12px;

            padding:
                1vw
                1.3vw
                0.7vw;

        }


        .card-heading {

            min-width: 0;

        }


        .card-title {

            margin: 0;

            color:
                var(--navy-dark);

            font-size:
                clamp(
                    16px,
                    1.18vw,
                    24px
                );

            font-weight:
                800;


        }


        .card-subtitle {

            margin-top: 4px;

            color:
                var(--muted);

            font-size:
                clamp(
                    9px,
                    0.62vw,
                    13px
                );

        }


        /* =========================================================
           UPCOMING
        ========================================================= */

        .upcoming-list {

            flex:
                1;

            min-height:
                0;

            position:
                relative;

            overflow:
                hidden;

            margin:
                0
                0.9vw
                0.85vw;

        }


        .upcoming-card {

            position: absolute;

            top: 0;
            left: 0;
            right: 0;
            bottom: 4px;

            padding:
                10px 12px;

            display: grid;

            grid-template-columns:
                105px
                minmax(0, 1fr);

            grid-template-rows:
                minmax(0, 1fr)
                auto;

            grid-template-areas:
                "time event"
                "room status";

            column-gap:
                14px;

            row-gap:
                6px;

            border:
                1px solid
                #dfe8ee;

            border-radius:
                var(--radius-medium);

            background:
                #fbfdfe;

            box-shadow:
                0 3px 8px
                rgba(
                    0,
                    41,
                    79,
                    0.06
                ),
                0 1px 2px
                rgba(
                    0,
                    41,
                    79,
                    0.04
                );

            opacity:
                0;

            visibility:
                hidden;

            transform:
                translateY(5px);

            transition:
                opacity 0.55s ease,
                visibility 0.55s ease,
                transform 0.55s ease;

            overflow:
                hidden;

        }


        .upcoming-card.active {

            opacity: 1;

            visibility: visible;

            transform:
                translateY(0);

        }


        .upcoming-time {

            padding-top:
                2px;

        }


        .upcoming-time-start {

            color:
                var(--navy);

            font-family:
                "Garuda Sans",
                Arial,
                sans-serif;

            font-size:
                clamp(
                    17px,
                    1.15vw,
                    24px
                );

            font-weight:
                800;

        }


        .upcoming-time-end {

            margin-top:
                3px;

            color:
                var(--muted);

            font-size:
                clamp(
                    9px,
                    0.60vw,
                    13px
                );

        }


        .upcoming-content {

            min-width: 0;

        }


        .upcoming-event {

            overflow: hidden;

            display:
                -webkit-box;

            -webkit-line-clamp:
                2;

            -webkit-box-orient:
                vertical;

            color:
                var(--text);

            font-size:
                clamp(
                    15px,
                    0.92vw,
                    18px
                );

            font-weight:
                750;


        }


        .upcoming-resource {

            margin-top:
                6px;

            color:
                var(--muted);

            font-size:
                clamp(
                    14px,
                    0.63vw,
                    14px
                );

            font-weight:
                650;

            white-space:
                nowrap;

            overflow:
                hidden;

            text-overflow:
                ellipsis;

        }



        .upcoming-detail-button {

        flex:
            0 0 auto;

        display:
            inline-flex;

        align-items:
            center;

        justify-content:
            center;

        min-height:
            34px;

        padding:
            0
            16px;

        margin-top:
            1px;

        border:
            1px solid
            #006fae;

        border-radius:
            999px;

        background:
            #006fae;

        color:
            #ffffff;

        font-family:
            "Garuda Sans",
            Arial,
            sans-serif;

        font-size:
            8px;

        font-weight:
            800;

        letter-spacing:
            0.05em;

        text-decoration:
            none;

        text-transform:
            uppercase;

        white-space:
            nowrap;

        cursor:
            pointer;

        box-shadow:
            0
            3px
            8px
            rgba(
                0,
                111,
                174,
                0.18
            );

        transition:
            background 0.2s ease,
            border-color 0.2s ease,
            transform 0.2s ease,
            box-shadow 0.2s ease;

    }


    .upcoming-detail-button:hover,
    .upcoming-detail-button:focus,
    .upcoming-detail-button:active {

        background:
            #005b8f !important;

        border-color:
            #005b8f !important;

        color:
            #ffffff !important;

        transform:
            translateY(-1px);

        box-shadow:
            0
            5px
            12px
            rgba(
                0,
                111,
                174,
                0.25
            );

    }

        .upcoming-empty {

            height: 100%;

            display: flex;

            align-items: center;

            justify-content: center;

            padding:
                15px;

            text-align: center;

            color:
                var(--muted);

            font-size:
                clamp(
                    10px,
                    0.68vw,
                    14px
                );

        }


        /* =========================================================
           NEWS
        ========================================================= */

        .news-list {

            flex: 1;

            min-height: 0;

            position: relative;

            overflow: hidden;

            margin:
                0
                0.9vw
                0.85vw;

        }


        .news-card {

            position: absolute;

            inset: 0;

            overflow: hidden;

            border:
                1px solid
                #dfe8ee;

            border-radius:
                var(--radius-medium);

            background:
                #fbfdfe;

            opacity: 0;

            visibility: hidden;

            transform:
                translateY(5px);

            transition:
                opacity 0.55s ease,
                visibility 0.55s ease,
                transform 0.55s ease;

        }


        .news-card.active {

            opacity: 1;

            visibility: visible;

            transform:
                translateY(0);

        }


        .news-image {

            position: absolute;

            inset: 0;

            background:
                linear-gradient(
                    135deg,
                    #dff7fb,
                    #b9eaf3
                );

        }


        .news-image img {

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

            color:
                rgba(
                    0,
                    59,
                    111,
                    0.32
                );

            font-size:
                clamp(
                    24px,
                    2vw,
                    42px
                );

            font-weight:
                800;

            letter-spacing:
                0.10em;

            text-transform:
                uppercase;

        }


        .news-overlay {

            position: absolute;

            inset: 0;

            background:
                linear-gradient(
                    180deg,
                    rgba(
                        0,
                        20,
                        35,
                        0.05
                    ) 15%,
                    rgba(
                        0,
                        20,
                        35,
                        0.30
                    ) 45%,
                    rgba(
                        0,
                        22,
                        38,
                        0.92
                    ) 100%
                );

        }


        .news-content {

            position: absolute;

            left:
                1vw;

            right:
                1vw;

            bottom:
                0.9vw;

            z-index: 2;

        }


        /* .news-label {

            display:
                inline-flex;

            align-items:
                center;

            padding:
                5px
                9px;

            border-radius:
                999px;

            background:
                rgba(
                    0,
                    59,
                    111,
                    0.72
                );

            color:
                #ffffff;

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

        } */


        .news-title {

            margin-top:
                7px;

            color:
                #ffffff;

            font-size:
                clamp(
                    14px,
                    1.0vw,
                    20px
                );

            font-weight:
                800;

            line-height:
                1.16;

            display:
                -webkit-box;

            -webkit-line-clamp:
                2;

            -webkit-box-orient:
                vertical;

            overflow:
                hidden;

        }


        .news-excerpt {

            margin-top:
                5px;

            color:
                rgba(
                    255,
                    255,
                    255,
                    0.73
                );

            font-size:
                clamp(
                    8px,
                    0.58vw,
                    12px
                );

            line-height:
                1.35;

            display:
                -webkit-box;

            -webkit-line-clamp:
                2;

            -webkit-box-orient:
                vertical;

            overflow:
                hidden;

        }


        .news-pagination {

            position: absolute;

            right:
                0.9vw;

            top:
                0.9vw;

            z-index:
                3;

            display:
                flex;

            align-items:
                center;

            gap:
                5px;

            padding:
                5px
                8px;

            border-radius:
                999px;

            background:
                rgba(
                    0,
                    31,
                    60,
                    0.55
                );

            color:
                rgba(
                    255,
                    255,
                    255,
                    0.82
                );

            font-family:
                "Garuda Sans",
                Arial,
                sans-serif;

            font-size:
                9px;

        }


        .news-dot {

            width: 5px;
            height: 5px;

            border-radius: 50%;

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.38
                );

        }


        .news-dot.active {

            width: 15px;

            border-radius:
                999px;

            background:
                #71e5f5;

        }


        .news-empty {

            height: 100%;

            display: flex;

            align-items: center;

            justify-content: center;

            text-align: center;

            padding:
                20px;

            color:
                var(--muted);

            font-size:
                clamp(
                    10px,
                    0.68vw,
                    14px
                );

        }


        /* =========================================================
           RIGHT AREA / TODAY
        ========================================================= */

        .today-panel {

            min-width: 0;

            min-height: 0;

            display: flex;

            flex-direction: column;

            overflow: hidden;

            background:
                #ffffff;

            border:
                1px solid
                var(--border);

            border-radius:
                var(--radius-large);

            box-shadow:
                0 8px 18px
                rgba(
                    0,
                    41,
                    79,
                    0.08
                ),
                0 2px 5px
                rgba(
                    0,
                    41,
                    79,
                    0.05
                );

        }


        .today-header {

            flex:
                0 0 auto;

            padding:
                1.15vw
                1.3vw
                0.9vw;

            border-bottom:
                1px solid
                #e9eff3;

        }


        .today-header-top {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap:
                1vw;

        }


        .today-heading {

            min-width: 0;

        }


        .today-title {

            margin: 0;

            color:
                var(--navy-dark);

            font-size:
                clamp(
                    18px,
                    1.36vw,
                    28px
                );

            font-weight:
                800;

        }


        .today-subtitle {

            margin-top:
                4px;

            color:
                var(--muted);

            font-size:
                clamp(
                    9px,
                    0.62vw,
                    13px
                );

        }


        .today-search {

            position: relative;

            flex:
                0 0
                clamp(
                    125px,
                    9.5vw,
                    185px
                );

        }


        .today-search-icon {

            position: absolute;

            left:
                11px;

            top: 50%;

            transform:
                translateY(-50%);

            font-size:
                11px;

            opacity:
                0.52;

            pointer-events:
                none;

        }


        .today-search input {

            width: 100%;

            padding:
                7px
                10px
                7px
                30px;

            border:
                1px solid
                rgba(
                    0,
                    59,
                    111,
                    0.14
                );

            border-radius:
                999px;

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.76
                );

            color:
                var(--text);

            font-size:
                10px;

            outline:
                none;

            transition:
                0.2s ease;

        }


        .today-search input::placeholder {

            color:
                #9aa9b5;

        }


        .today-search input:focus {

            border-color:
                rgba(
                    0,
                    59,
                    111,
                    0.30
                );

            background:
                #ffffff;

            box-shadow:
                0 2px 8px
                rgba(
                    0,
                    41,
                    79,
                    0.06
                );

        }


        /* =========================================================
           TODAY LIST
        ========================================================= */

        .today-list {

            flex:
                1;

            min-height:
                0;

            overflow:
                hidden;

            padding:
                0.8vw;

            position:
                relative;

        }


        .today-track {

            display:
                flex;

            flex-direction:
                column;

            gap:
                8px;

            will-change:
                transform;

            transition:
                transform
                0.75s
                ease-in-out;

        }


        .today-row {
            width: 100%;

            height: 92px;
            min-height: 92px;

            display: grid;

            grid-template-columns:
                125px
                minmax(0, 1fr);

            grid-template-rows:
                minmax(0, 1fr)
                auto;

            grid-template-areas:
                "time event"
                "room status";

            column-gap: 14px;
            row-gap: 5px;

            padding:
                10px
                12px;

            border:
                1px solid
                #dfe8ee;

            border-radius:
                var(--radius-medium);

            background:
                #fbfdfe;

            box-shadow:
                0 3px 8px
                rgba(
                    0,
                    41,
                    79,
                    0.05
                );

            overflow: hidden;

        }


        .today-time {
            grid-area: time;

            align-self: start;

            margin: 0;

            color:
                var(--muted);

            font-family:
                "Garuda Sans",
                Arial,
                sans-serif;

            font-size:
                clamp(
                    10px,
                    0.68vw,
                    14px
                );

            font-weight: 700;

            white-space: nowrap;
        }


        .today-resource-label {
            margin: 0;

            color:
                var(--muted);

            font-size: 9px;

            font-weight: 800;

            letter-spacing: 0.10em;

            text-transform: uppercase;

        }

        .today-resource-block {
            grid-area: room;

            min-width: 0;

            align-self: end;

        }


        .today-resource {
            margin-top: 2px;

            color:
                var(--navy);

            font-size:
                clamp(
                    15px,
                    0.92vw,
                    19px
                );

            font-weight: 800;


            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;
        }


        .today-event {
            grid-area: event;

            align-self: start;

            margin: 0;

            min-width: 0;

            color:
                var(--text);

            font-size:
                clamp(
                    16px,
                    0.72vw,
                    15px
                );

            font-weight: 700;

           

            display:
                -webkit-box;

            -webkit-line-clamp: 2;

            -webkit-box-orient: vertical;

            overflow: hidden;

            text-overflow: ellipsis;
        }


       .today-status {

            grid-column: 2;

            grid-row: 2;

            justify-self: start;

            align-self: center;

            margin: 0;

            width: fit-content;

            padding:
                4px
                8px;

            border:
                1px solid
                #aee6fb;

            border-radius:
                999px;

            background:
                #eefaff;

            color:
                #007bb5;

            font-size:
                7px;

            font-weight:
                800;

            letter-spacing:
                0.06em;

            text-transform:
                uppercase;

        }

        .today-status.current {

            border-color:
                #ffd0d0;

            background:
                #fff0f0;

            color:
                #df4545;

        }


        .today-status.completed {

            border-color:
                #dce4e9;

            background:
                #f3f6f8;

            color:
                #7b8c97;

        }


        .today-empty {

            height:
                100%;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            padding:
                25px;

            text-align:
                center;

            color:
                var(--muted);

            font-size:
                clamp(
                    11px,
                    0.72vw,
                    14px
                );

        }


        .today-footer {

            flex:
                0 0 auto;

            min-height:
                48px;

            display:
                flex;

            align-items:
                center;

            justify-content:
                space-between;

            gap:
                10px;

            padding:
                0
                1vw;

            border-top:
                1px solid
                #e9eff3;

        }


        .today-count {

            color:
                var(--muted);

            font-family:
                "Garuda Sans",
                Arial,
                sans-serif;

            font-size:
                9px;

        }


        .today-detail-link {

        display:
            inline-flex;

        align-items:
            center;

        justify-content:
            center;

        min-height:
            34px;

        padding:
            0
            16px;

        border:
            1px solid
            #006fae;

        border-radius:
            999px;

        background:
            #006fae;

        color:
            #ffffff;

        text-decoration:
            none;

        font-family:
            "Garuda Sans",
            Arial,
            sans-serif;

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

        box-shadow:
            0
            3px
            8px
            rgba(
                0,
                111,
                174,
                0.18
            );

        transition:
            background 0.2s ease,
            border-color 0.2s ease,
            transform 0.2s ease,
            box-shadow 0.2s ease;

    }


    .today-detail-link:hover {

        background:
            #005b8f;

        border-color:
            #005b8f;

        transform:
            translateY(-1px);

        box-shadow:
            0
            5px
            12px
            rgba(
                0,
                111,
                174,
                0.25
            );

    }




        /* =========================================================
           FOOTER
        ========================================================= */

        .display-footer {

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
                    8px,
                    0.58vw,
                    12px
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

        @media (max-width: 1050px) {

            .display-main {

                grid-template-columns:
                    minmax(0, 1fr);

            }


            .today-panel {

                display:
                    none;

            }


            .left-area {

                grid-template-rows:
                    minmax(0, 2fr)
                    minmax(160px, 1fr);

            }

        }


        @media (max-width: 760px) {

            .display-header {

                flex-basis:
                    10vh;

                min-height:
                    76px;

                padding:
                    0
                    3vw;

            }

        .news-modal {
            padding:
                2vh
                2vw;
        }


        .news-modal-content {

            width:
                96vw;

            height:
                92vh;

            grid-template-columns:
                1fr;

            grid-template-rows:
                38vh
                minmax(
                    0,
                    1fr
                );
            touch-action: pan-y;

        }


        .news-modal-body {

            padding:
                24px;

        }


        .news-modal-title {

            font-size:
                26px;

        }


        .news-modal-content-text {

            font-size:
                14px;

        }


            .header-divider {

                display:
                    none;

            }


            .header-building {

                display:
                    none;

            }


            .logo-area {

                display:
                    none;

            }


            .display-main {

                padding:
                    1.5vw
                    2vw
                    0;

            }


            .left-area {

                grid-template-rows:
                    minmax(0, 1fr)
                    minmax(150px, 0.7fr);

            }


            .lower-left {

                grid-template-columns:
                    1fr;

            }


            .lower-left .news-card-wrapper {

                display:
                    none;

            }


            .hero-meta {

                max-width:
                    100%;

                gap:
                    5vw;

            }


            .hero-title {

                max-width:
                    100%;

            }




            .display-footer {

                display:
                    none;

            }

        }


        @media (max-height: 700px) {

            .display-header {

                flex-basis:
                    11vh;

            }


            .display-main {

                gap:
                    0.8vw;

                padding:
                    0.8vw
                    2vw
                    0;

            }


            .left-area,
            .lower-left {

                gap:
                    0.8vw;

            }




            .hero-carousel {

                margin-top:
                    1.5vh;

            }


            .hero-meta {

                margin-top:
                    2.3vh;

            }


            .card-header {

                padding:
                    0.75vw
                    1vw
                    0.5vw;

            }


            .today-list {

                padding:
                    0.6vw;

            }


            .today-row {

                padding:
                    7px
                    8px;

            }


            .today-footer {

                min-height:
                    42px;

            }

        }

        /* =========================================================
        NEWS MODAL
        ========================================================= */

        .news-card {
            cursor: pointer;
        }

        .news-modal {
            position: fixed;

            inset: 0;

            z-index: 9999;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 4vh 4vw;

            background:
                rgba(
                    0,
                    25,
                    45,
                    0.72
                );

            backdrop-filter:
                blur(6px);

            opacity: 0;

            visibility: hidden;

            transition:
                opacity 0.25s ease,
                visibility 0.25s ease;

        }

        .news-modal.open {

            opacity: 1;

            visibility: visible;

        }


        .news-modal-content {

            position: relative;

            width:
                min(
                    1100px,
                    88vw
                );

            height:
                min(
                    760px,
                    82vh
                );

            display: grid;

            grid-template-columns:
                minmax(0, 0.95fr)
                minmax(0, 1.05fr);

            overflow: hidden;

            background:
                #ffffff;

            border:
                1px solid
                #d9e5ed;

            border-radius:
                20px;

            box-shadow:
                0 25px 80px
                rgba(
                    0,
                    24,
                    45,
                    0.30
                );

            transform:
                translateY(12px)
                scale(0.98);

            transition:
                transform 0.25s ease;

        }


        .news-modal.open
        .news-modal-content {

            transform:
                translateY(0)
                scale(1);

        }


        .news-modal-image {

            min-width: 0;

            min-height: 0;

            background:
                #eaf5f8;

            overflow: hidden;

        }


        .news-modal-image img {

            width: 100%;

            height: 100%;

            display: block;

            object-fit: cover;

        }


        .news-modal-image-empty {

            width: 100%;

            height: 100%;

            display: flex;

            align-items: center;

            justify-content: center;

            color:
                rgba(
                    0,
                    59,
                    111,
                    0.28
                );

            font-size:
                clamp(
                    30px,
                    4vw,
                    60px
                );

            font-weight:
                800;

            letter-spacing:
                0.12em;

        }


        .news-modal-body {

            min-width: 0;

            min-height: 0;

            display: flex;

            flex-direction: column;

            padding:
                3vw;

            overflow-y: auto;

        }


        .news-modal-label {

            width: fit-content;

            padding:
                6px
                10px;

            border-radius:
                999px;

            background:
                #eefaff;

            border:
                1px solid
                #aee6fb;

            color:
                #007bb5;

            font-size:
                9px;

            font-weight:
                800;

            letter-spacing:
                0.08em;

            text-transform:
                uppercase;

        }


        .news-modal-title {

            margin:
                14px
                0
                0;

            color:
                var(--navy-dark);

            font-size:
                clamp(
                    24px,
                    2.1vw,
                    42px
                );

            line-height:
                1.12;

            font-weight:
                800;

        }


        .news-modal-date {

            margin-top:
                8px;

            color:
                var(--muted);

            font-size:
                11px;

            font-weight:
                500;

        }


        .news-modal-content-text {

            margin-top:
                22px;

            color:
                var(--text);

            font-size:
                clamp(
                    13px,
                    0.9vw,
                    18px
                );


            white-space:
                pre-wrap;

            overflow-wrap:
                anywhere;

        }


        .news-modal-close {

            position: absolute;

            top:
                14px;

            right:
                14px;

            z-index:
                5;

            width:
                40px;

            height:
                40px;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            border:
                1px solid
                rgba(
                    0,
                    59,
                    111,
                    0.14
                );

            border-radius:
                50%;

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.94
                );

            color:
                var(--navy);

            font-size:
                20px;

            font-weight:
                500;

            cursor:
                pointer;

            transition:
                background 0.2s ease,
                transform 0.2s ease;

        }


        .news-modal-close:hover {

            background:
                #f2f8fb;

            transform:
                scale(1.04);

        }


        body.news-modal-open {

            overflow: hidden;

        }

        .news-modal-nav {
            position: absolute;

            top: 50%;

            width: 52px;
            height: 52px;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 0;

            border: none;
            border-radius: 50%;

            background: rgba(0, 0, 0, 0.22);

            color: #ffffff;

            font-family: Arial, sans-serif;
            font-size: 34px;
            font-weight: 400;

            line-height: 52px;
            text-align: center;

            cursor: pointer;

            transform: translateY(-50%);

            z-index: 10001;

            transition:
                background 0.2s ease,
                transform 0.2s ease;
        }


        .news-modal-nav:hover {
            background: rgba(0, 0, 0, 0.42);

            transform:
                translateY(-50%)
                scale(1.08);
        }


        .news-modal-nav:active {
            transform:
                translateY(-50%)
                scale(0.96);
        }


        .news-modal-prev {
            left: calc(50% - min(44vw, 550px) - 58px);
        }


        .news-modal-next {
            right: calc(50% - min(44vw, 550px) - 58px);
        }


        .news-modal-nav[hidden] {
            display: none;
        }



        @media (max-width: 900px) {

        .news-modal-prev {
            left: 12px;
        }



       .news-modal-nav:hover {

            background:
                rgba(
                    0,
                    0,
                    0,
                    0.40
                );

        }


        .news-modal-prev:hover {

            transform:
                translateY(-50%)
                translateX(-1px);

        }


        .news-modal-next:hover {

            transform:
                translateY(-50%)
                translateX(1px);

        }


        .news-modal-nav:active {

            transform:
                translateY(-50%)
                scale(0.96);

        }


    /* =========================================================
    MOBILE DISPLAY OVERRIDE
    ========================================================= */

    @media (max-width: 760px) {

        html,
        body {
            width: 100%;
            min-width: 0;
            height: auto;
            min-height: 100%;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .display-app {
            width: 100%;
            min-width: 0;
            height: auto;
            min-height: 100vh;
            overflow: visible;
        }

        /* =========================
        HEADER
        ========================= */

        .display-header {
            flex: 0 0 auto;
            min-height: 0;
            height: auto;

            padding:
                16px
                18px;

            gap: 14px;

            flex-wrap: wrap;
            align-items: center;
        }

        .header-left {
            width: 100%;
            min-width: 0;
            gap: 10px;
        }

        .header-brand {
            min-width: 0;
        }

        .header-title {
            font-size: 21px;
            line-height: 1.15;
        }

        .header-subtitle {
            margin-top: 3px;
            font-size: 10px;
            line-height: 1.35;
        }

        .header-divider {
            height: 42px;
            margin: 0 2px;
        }

        .header-building {
            min-width: 0;
            flex: 1;
        }

        .header-building-label {
            font-size: 8px;
            margin-bottom: 2px;
        }

        .header-building-name {
            max-width: 100%;
            font-size: 18px;
            line-height: 1.15;
        }

        .header-building-code {
            font-size: 9px;
            margin-top: 2px;
        }

        .header-right {
            width: 100%;
            justify-content: space-between;
            gap: 10px;
        }

        .datetime {
            text-align: left;
            min-width: 0;
        }

        .header-date {
            font-size: 10px;
        }

        .header-clock {
            margin-top: 2px;
            font-size: 23px;
            line-height: 1;
        }

        .logo-area {
            width: auto;
            height: 42px;
            padding: 4px 8px;
            border-left: 0;
        }

        .logo-area img {
            max-height: 34px;
            max-width: 130px;
        }

        /* =========================
        MAIN
        ========================= */

        .display-main {
            display: flex;
            flex-direction: column;

            width: 100%;
            height: auto;
            min-height: 0;

            gap: 12px;

            padding:
                12px
                12px
                24px;

            overflow: visible;
        }

        /* =========================
        LEFT AREA
        ========================= */

        .left-area {
            width: 100%;
            min-width: 0;
            min-height: 0;

            display: flex;
            flex-direction: column;

            gap: 12px;

            overflow: visible;
        }

        /* =========================
        HERO
        ========================= */

        .hero {
            width: 100%;
            min-height: 430px;
            height: auto;

            border-radius: 16px;
        }

        .hero-content {
            height: auto;
            min-height: 430px;

            padding:
                24px
                20px
                28px;

            overflow: visible;
        }

        .hero-status {
            max-width: 100%;

            padding:
                7px
                11px;

            font-size: 9px;
            line-height: 1.2;

            white-space: normal;
        }

        .hero-carousel {
            margin-top: 18px;
        }

        .hero-slide {
            min-width: 100%;
        }

        .hero-title {
            font-size:
                clamp(
                    25px,
                    8vw,
                    36px
                );

            line-height: 1.08;

            overflow-wrap: anywhere;
        }

        .hero-subtitle {
            font-size: 12px;
            line-height: 1.45;
        }

        .hero-time {
            font-size:
                clamp(
                    28px,
                    9vw,
                    42px
                );

            line-height: 1;
        }

        .hero-meta {
            flex-wrap: wrap;
            gap: 7px;
        }

        .hero-meta-item {
            max-width: 100%;
        }

        .hero-actions {
            margin-top: 20px;

            display: flex;
            flex-wrap: wrap;

            gap: 8px;
        }

        .hero-actions a,
        .hero-actions button {
            max-width: 100%;
        }

        /* =========================
        LOWER LEFT
        ========================= */

        .lower-left {
            width: 100%;
            min-width: 0;

            display: flex;
            flex-direction: column;

            gap: 12px;

            min-height: 0;
            height: auto;
        }

        /* =========================
        UPCOMING
        ========================= */

        .upcoming-card,
        .upcoming-panel {
            width: 100%;
            min-width: 0;

            min-height: 240px;
            height: auto;
        }

        .upcoming-content {
            padding: 18px;
        }

        .upcoming-title {
            font-size: 16px;
        }

        .upcoming-time {
            font-size: 24px;
        }

        .upcoming-detail-button {
            min-height: 38px;
            padding:
                8px
                12px;

            font-size: 11px;
        }

        /* =========================
        NEWS
        ========================= */

        .lower-left .news-card-wrapper {
            display: block !important;

            width: 100%;
            min-width: 0;

            min-height: 300px;
            height: 300px;
        }

        .news-list {
            width: 100%;
            min-width: 0;

            min-height: 300px;

            margin:
                0;

            overflow: hidden;
        }

        .news-card {
            width: 100%;
            height: 100%;
        }

        .news-image {
            width: 100%;
            height: 100%;
        }

        .news-image img {
            width: 100%;
            height: 100%;

            object-fit: cover;
        }

        .news-overlay {
            padding:
                18px;
        }

        .news-title {
            font-size: 18px;
            line-height: 1.2;
        }

        .news-content {
            font-size: 11px;
            line-height: 1.45;
        }

        /* =========================
        TODAY PANEL
        ========================= */

        .today-panel {
            display: flex !important;

            width: 100%;
            min-width: 0;

            min-height: 420px;
            height: auto;

            overflow: hidden;
        }

        .today-header {
            padding:
                16px
                18px;
        }

        .today-title {
            font-size: 17px;
        }

        .today-subtitle {
            font-size: 10px;
        }

        .today-list {
            min-height: 0;
            max-height: 500px;

            overflow-y: auto;
            overflow-x: hidden;
        }

        .today-item {
            padding:
                14px
                16px;
        }

        .today-item-title {
            font-size: 13px;
            line-height: 1.35;
        }

        .today-item-time {
            font-size: 11px;
        }

        /* =========================
        NEWS MODAL
        ========================= */

        .news-modal {
            padding: 12px;
        }

        .news-modal-dialog {
            width: 100%;
            max-width: 100%;

            max-height: 92vh;
        }

        .news-modal-image {
            max-height: 55vh;
        }

        .news-modal-nav {
            width: 42px;
            height: 42px;

            font-size: 27px;
            line-height: 42px;
        }

        .news-modal-prev {
            left: 5px;
        }

        .news-modal-next {
            right: 5px;
        }
    }


    /* =========================================================
    VERY SMALL PHONES
    ========================================================= */

    @media (max-width: 420px) {

        .display-header {
            padding:
                13px
                14px;
        }

        .display-main {
            padding:
                10px
                10px
                20px;
        }

        .header-title {
            font-size: 19px;
        }

        .header-building-name {
            font-size: 16px;
        }

        .header-clock {
            font-size: 21px;
        }

        .hero {
            min-height: 400px;
        }

        .hero-content {
            min-height: 400px;

            padding:
                20px
                17px
                24px;
        }

        .hero-title {
            font-size: 25px;
        }

        .hero-time {
            font-size: 30px;
        }

        .news-list,
        .lower-left .news-card-wrapper {
            min-height: 270px;
            height: 270px;
        }

        .today-panel {
            min-height: 380px;
        }
    }


    /* =========================================================
    LANDSCAPE PHONE
    ========================================================= */

    @media (max-width: 900px) and (orientation: landscape) {

        html,
        body {
            overflow-y: auto;
        }

        .display-header {
            padding:
                10px
                18px;
        }

        .display-main {
            padding:
                10px
                14px
                20px;
        }

        .hero {
            min-height: 360px;
        }

        .hero-content {
            min-height: 360px;

            padding:
                20px
                24px;
                24px;
        }

        .news-list,
        .lower-left .news-card-wrapper {
            min-height: 250px;
            height: 250px;
        }

        .today-panel {
            min-height: 360px;
        }
    }

    /* =========================================================
   MOBILE — HERO INFO FIX
========================================================= */

@media (max-width: 768px) {

    .hero-content {
        padding-bottom: 28px;
    }

    .hero-meta,
    .hero-info,
    .hero-details {
        min-height: 0;
    }

    .hero-meta {
        margin-top: 18px;
    }

    .hero-start,
    .start-time {
        min-width: 0;
    }

    .hero-start .time,
    .start-time .time,
    .start-time-value {
        white-space: nowrap;
        line-height: 1.05;
    }

}

    </style>

</head>


<body>


<div
    class="display-app"
    id="displayApp"
>


    {{-- =========================================================
         HEADER
    ========================================================= --}}

    <header class="display-header">


        <div class="header-left">


            <div class="header-brand">

                <div class="header-title">
                    GITC Info
                </div>

                <div class="header-subtitle">
                    Garuda Training System, Media & Business
                </div>

            </div>


            <div class="header-divider"></div>


            <div class="header-building">


                <div class="header-building-name">
                    {{ $building->name }}
                </div>

            </div>


        </div>



        <div class="header-right">


            <div class="datetime">

                <div
                    class="header-date"
                    id="headerDate"
                >
                    ---
                </div>

                <div
                    class="header-clock"
                    id="headerClock"
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

    <main class="display-main">


        {{-- =====================================================
             LEFT AREA
        ====================================================== --}}

        <div class="left-area">


            {{-- =================================================
                 HERO
            ================================================== --}}

            <section class="hero">


                <div
                    class="hero-content"
                    id="heroContent"
                >

                    <div class="hero-status empty">

                        <span class="hero-status-dot"></span>

                        <span>
                            Loading
                        </span>

                    </div>


                </div>


                {{-- <a
                    href="{{ route('display.detail', [
                        'buildingName' => Str::slug(
                            $building->name,
                            '_'
                        )
                    ]) }}"
                    class="hero-detail-link"
                >
                    Detail Schedule
                </a> --}}


            </section>



            {{-- =================================================
                 LOWER LEFT
            ================================================== --}}

            <div class="lower-left">


                {{-- =================================================
                    UPCOMING SCHEDULE
                ================================================== --}}
                <section class="info-card">

                    <div class="card-header">

                        <div class="card-heading">

                            <h2 class="card-title">
                                Upcoming Schedule
                            </h2>

                            <div
                                class="card-subtitle"
                                id="upcomingSubtitle"
                            >
                                Loading...
                            </div>

                        </div>


                        <a
                            href="{{ route('display.upcoming', [
                                'buildingName' => Str::slug(
                                    $building->name,
                                    '_'
                                )
                            ]) }}"
                            class="upcoming-detail-button"
                        >
                            Detail Upcoming
                        </a>

                    </div>


                    <div
                        class="upcoming-list"
                        id="upcomingList"
                    >

                        <div class="upcoming-empty">
                            Loading schedule...
                        </div>

                    </div>

                </section>



                {{-- =============================================
                     NEWS
                ============================================== --}}

                <section
                    class="info-card news-card-wrapper"
                >


                    <div class="card-header">


                        <div class="card-heading">

                            <h2 class="card-title">
                                Information
                            </h2>

                            <div class="card-subtitle">
                                Information & Announcements
                            </div>

                        </div>


                    </div>


                    <div
                        class="news-list"
                        id="newsList"
                    >

                        <div class="news-empty">
                            Loading info...
                        </div>

                    </div>


                </section>


            </div>


        </div>



        {{-- =====================================================
             TODAY
        ====================================================== --}}

        <aside class="today-panel">


            <div class="today-header">


                <div class="today-header-top">


                    <div class="today-heading">

                        <h2 class="today-title">
                            Today's Schedule
                        </h2>

                        <div class="today-subtitle">
                            Today's Room Bookings
                        </div>

                    </div>


                    <div class="today-search">


                        <span class="today-search-icon">
                            🔍
                        </span>


                        <input
                            type="text"
                            id="todaySearch"
                            placeholder="Search..."
                            autocomplete="off"
                        >


                    </div>


                </div>


            </div>



            <div
                class="today-list"
                id="todayList"
            >

                <div class="today-empty">
                    Loading schedule...
                </div>

            </div>



            <div class="today-footer">


                <div
                    class="today-count"
                    id="todayCount"
                >
                    0 reservations
                </div>


                <a
                    href="{{ route('display.detail', [
                        'buildingName' => Str::slug(
                            $building->name,
                            '_'
                        )
                    ]) }}"
                    class="today-detail-link"
                >
                    View Full Schedule
                </a>


            </div>


        </aside>


    </main>



    {{-- =========================================================
         FOOTER
    ========================================================= --}}

    <footer class="display-footer">


        <div class="footer-brand">
            Garuda Indonesia Training Center 
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


    {{-- =========================================================
        NEWS DETAIL MODAL
    ========================================================= --}}

    <div
        class="news-modal"
        id="newsModal"
        aria-hidden="true"
    >

      <button
                type="button"
                class="news-modal-nav news-modal-prev"
                id="newsModalPrev"
                aria-label="Previous news"
            >
                ‹
            </button>

        <button
                type="button"
                class="news-modal-nav news-modal-next"
                id="newsModalNext"
                aria-label="Next news"
            >
                ›
         </button>

        <div
            class="news-modal-content"
            role="dialog"
            aria-modal="true"
            aria-labelledby="newsModalTitle"
        >

            <button
                type="button"
                class="news-modal-close"
                id="newsModalClose"
                aria-label="Close news"
            >
                ×
            </button>


            <div
                class="news-modal-image"
                id="newsModalImage"
            >
        </div>


            <div class="news-modal-body">

              


                <h2
                    class="news-modal-title"
                    id="newsModalTitle"
                >
                    —
                </h2>


                <div
                    class="news-modal-date"
                    id="newsModalDate"
                >
                    —
                </div>


                <div
                    class="news-modal-content-text"
                    id="newsModalContent"
                >
                    —
                </div>

            </div>

        </div>

    </div>



<script>


    /* =========================================================
       DATA URL
    ========================================================= */

    const dataUrl = @json(
        route('display.data', [
            'buildingName' => Str::slug(
                $building->name,
                '_'
            )
        ])
    );


    /* =========================================================
       STATE
    ========================================================= */

    let displayData = null;

    let reservations = [];

    let tomorrowReservations = [];

    let newsItems = [];

    let filteredReservations = [];

    let heroTimer = null;

    let upcomingTimer = null;

    let newsTimer = null;

    let todayTimer = null;

    let todayIndex = 0;

    let todayItemHeight = 0;

    let todayGap = 8;

    let todayVisibleItems = 5;

    let searchKeyword = '';

    let selectedNews = null;

    let selectedNewsIndex = 0;

    let newsTouchStartX = 0;

    let newsTouchEndX = 0;


    


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
                'headerClock'
            );


        const dateElement =
            document.getElementById(
                'headerDate'
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


    function stripHtml(value) {

        if (
            value === null ||
            value === undefined
        ) {

            return '';

        }


        const temporary =
            document.createElement(
                'div'
            );


        temporary.innerHTML =
            String(value);


        return temporary.textContent ||
            temporary.innerText ||
            '';

    }


    function formatTime(value) {

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


    function formatDate(value) {

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


        return date.toLocaleDateString(
            'en-GB',
            {
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            }
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


    function getReservationStatus(
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


    /* =========================================================
       HERO
    ========================================================= */

    function getHeroReservations() {

        const now =
            new Date();


        const current =
            reservations.filter(
                reservation => {

                    const start =
                        new Date(
                            reservation.starts_at
                        );

                    const end =
                        new Date(
                            reservation.ends_at
                        );


                    return (
                        start <= now &&
                        end >= now
                    );

                }
            );


        if (
            current.length > 0
        ) {

            return current;

        }


        return reservations
            .filter(
                reservation => {

                    return new Date(
                        reservation.starts_at
                    ) > now;

                }
            )
            .sort(
                (a, b) => {

                    return new Date(
                        a.starts_at
                    )
                    -
                    new Date(
                        b.starts_at
                    );

                }
            )
            .slice(
                0,
                1
            );

    }


    function stopHeroCarousel() {

        if (heroTimer) {

            clearInterval(
                heroTimer
            );

            heroTimer = null;

        }

    }


    function renderHero() {

        stopHeroCarousel();


        const heroContent =
            document.getElementById(
                'heroContent'
            );


        if (!heroContent) {

            return;

        }


        const now =
            new Date();


        const currentReservations =
            reservations.filter(
                reservation => {

                    const start =
                        new Date(
                            reservation.starts_at
                        );

                    const end =
                        new Date(
                            reservation.ends_at
                        );


                    return (
                        start <= now &&
                        end >= now
                    );

                }
            );


        const upcomingReservations =
            reservations
                .filter(
                    reservation =>
                        new Date(
                            reservation.starts_at
                        ) > now
                )
                .sort(
                    (a, b) => {

                        return new Date(
                            a.starts_at
                        )
                        -
                        new Date(
                            b.starts_at
                        );

                    }
                );


        /*
         * =========================
         * CURRENT
         * =========================
         */

        if (
            currentReservations.length > 0
        ) {

            heroContent.innerHTML = `

                <div class="hero-status">

                    <span
                        class="hero-status-dot"
                    ></span>

                    <span>
                        In Progress
                    </span>

                </div>


                <div
                    class="hero-carousel"
                    id="heroCarousel"
                >

                    <div
                        class="hero-carousel-track"
                        id="heroCarouselTrack"
                    >

                        ${currentReservations
                            .map(
                                (
                                    reservation,
                                    index
                                ) => `

                                    <div
                                        class="
                                            hero-slide
                                            ${
                                                index === 0
                                                    ? 'active'
                                                    : ''
                                            }
                                        "
                                        data-index="${index}"
                                    >

                                        <div class="hero-label">

                                            ${escapeHtml(
                                                getResourceType(
                                                    reservation
                                                )
                                            )}

                                            ${escapeHtml(
                                                getResourceName(
                                                    reservation
                                                )
                                            )}

                                        </div>


                                        <h1
                                            class="hero-title"
                                        >

                                            ${escapeHtml(
                                                getEventName(
                                                    reservation
                                                )
                                            )}

                                        </h1>


                                        <div class="hero-meta">


                                            <div
                                                class="
                                                    hero-meta-item
                                                "
                                            >

                                                <div
                                                    class="
                                                        hero-meta-label
                                                    "
                                                >
                                                    Room
                                                </div>

                                                <div
                                                    class="
                                                        hero-room
                                                    "
                                                >

                                                    ${escapeHtml(
                                                        getResourceName(
                                                            reservation
                                                        )
                                                    )}

                                                </div>

                                            </div>


                                            <div
                                                class="
                                                    hero-meta-item
                                                "
                                            >

                                                <div
                                                    class="
                                                        hero-meta-label
                                                    "
                                                >
                                                    Time
                                                </div>

                                                <div
                                                    class="
                                                        hero-time
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

                                            </div>


                                        </div>

                                    </div>

                                `
                            )
                            .join('')
                        }

                    </div>

                </div>


                ${
                    currentReservations.length > 1
                        ? `

                            <div
                                class="hero-bottom"
                            >

                                <div class="pagination">

                                    ${currentReservations
                                        .map(
                                            (
                                                _,
                                                index
                                            ) => `

                                                <span
                                                    class="
                                                        pagination-dot
                                                        ${
                                                            index === 0
                                                                ? 'active'
                                                                : ''
                                                        }
                                                    "
                                                ></span>

                                            `
                                        )
                                        .join('')
                                    }


                                    <span
                                        class="pagination-number"
                                        id="heroPaginationNumber"
                                    >
                                        01 /
                                        ${String(
                                            currentReservations.length
                                        ).padStart(
                                            2,
                                            '0'
                                        )}
                                    </span>

                                </div>

                            </div>

                        `
                        : ''
                }

            `;


            initHeroCarousel();

        }


        /*
         * =========================
         * UPCOMING
         * =========================
         */

        else if (
            upcomingReservations.length > 0
        ) {

            const next =
                upcomingReservations[0];


            heroContent.innerHTML = `

                <div
                    class="
                        hero-status
                        upcoming
                    "
                >

                    <span
                        class="hero-status-dot"
                    ></span>

                    <span>
                        Upcoming
                    </span>

                </div>


                <div
                    class="hero-carousel"
                >

                    <div
                        class="hero-slide active"
                    >


                        <div class="hero-label">

                            ${escapeHtml(
                                getResourceType(
                                    next
                                )
                            )}

                            ${escapeHtml(
                                getResourceName(
                                    next
                                )
                            )}

                        </div>


                        <h1
                            class="hero-title"
                        >

                            ${escapeHtml(
                                getEventName(
                                    next
                                )
                            )}

                        </h1>


                        <div class="hero-meta">


                            <div
                                class="hero-meta-item"
                            >

                                <div
                                    class="hero-meta-label"
                                >
                                    Room
                                </div>

                                <div
                                    class="hero-room"
                                >

                                    ${escapeHtml(
                                        getResourceName(
                                            next
                                        )
                                    )}

                                </div>

                            </div>


                            <div
                                class="hero-meta-item"
                            >

                                <div
                                    class="hero-meta-label"
                                >
                                    Start
                                </div>

                                <div
                                    class="hero-time"
                                >

                                    ${formatTime(
                                        next.starts_at
                                    )}

                                    WIB

                                </div>

                            </div>


                        </div>

                    </div>

                </div>

            `;

        }


        /*
         * =========================
         * EMPTY
         * =========================
         */

        else {

            heroContent.innerHTML = `

                <div
                    class="
                        hero-status
                        empty
                    "
                >

                    <span
                        class="hero-status-dot"
                    ></span>

                    <span>
                        No Schedule
                    </span>

                </div>


                <div class="hero-empty">

                    <div
                        class="hero-empty-title"
                    >
                        No classes scheduled today
                    </div>


                    <div
                        class="hero-empty-text"
                    >
                        There are currently no reservations
                        scheduled for
                        ${escapeHtml(
                            displayData?.building?.name
                            ?? 'this building'
                        )}.
                    </div>

                </div>

            `;

        }

    }


    function initHeroCarousel() {

            stopHeroCarousel();


            const carousel =
                document.getElementById(
                    'heroCarousel'
                );


            const track =
                document.getElementById(
                    'heroCarouselTrack'
                );


            if (
                !carousel ||
                !track
            ) {
                return;
            }


            const originalSlides =
                Array.from(
                    track.querySelectorAll(
                        '.hero-slide'
                    )
                );


            if (
                originalSlides.length <= 1
            ) {
                return;
            }


            const total =
                originalSlides.length;


            /*
            * Clone slide pertama.
            *
            * Tujuannya agar setelah slide terakhir,
            * kita bisa bergerak ke clone pertama
            * lalu reset ke slide asli pertama
            * tanpa terlihat patah.
            */

            const firstClone =
                originalSlides[0]
                    .cloneNode(true);


            firstClone.classList.add(
                'hero-slide-clone'
            );


            track.appendChild(
                firstClone
            );


            const slides =
                Array.from(
                    track.querySelectorAll(
                        '.hero-slide'
                    )
                );


            const dots =
                Array.from(
                    document.querySelectorAll(
                        '.pagination-dot'
                    )
                );


            const paginationNumber =
                document.getElementById(
                    'heroPaginationNumber'
                );


            let currentIndex = 0;

            let isResetting = false;


            function updateIndicators(
                index
            ) {

                const realIndex =
                    index % total;


                dots.forEach(
                    (
                        dot,
                        dotIndex
                    ) => {

                        dot.classList.toggle(
                            'active',
                            dotIndex === realIndex
                        );

                    }
                );


                if (
                    paginationNumber
                ) {

                    paginationNumber.textContent =
                        `${String(
                            realIndex + 1
                        ).padStart(
                            2,
                            '0'
                        )} / ${String(
                            total
                        ).padStart(
                            2,
                            '0'
                        )}`;

                }

            }


            function showSlide(
                index,
                animate = true
            ) {

                if (
                    !animate
                ) {

                    track.style.transition =
                        'none';

                } else {

                    track.style.transition =
                        'transform 0.8s cubic-bezier(0.22, 0.61, 0.36, 1)';

                }


                track.style.transform =
                    `translateX(-${index * 100}%)`;


                updateIndicators(
                    index
                );

            }


            /*
            * Posisi awal.
            */

            showSlide(
                0,
                false
            );


            /*
            * Slide berikutnya
            * setiap 5 detik.
            */

            heroTimer =
                setInterval(
                    () => {

                        if (
                            isResetting
                        ) {

                            return;

                        }


                        currentIndex++;


                        showSlide(
                            currentIndex,
                            true
                        );


                        /*
                        * Kita sudah masuk
                        * ke clone pertama.
                        */

                        if (
                            currentIndex === total
                        ) {

                            isResetting = true;


                            setTimeout(
                                () => {

                                    currentIndex = 0;


                                    showSlide(
                                        0,
                                        false
                                    );


                                    /*
                                    * Force browser
                                    * menerapkan posisi baru.
                                    */

                                    void track.offsetWidth;


                                    isResetting = false;

                                },
                                850
                            );

                        }

                    },
                    5000
                );

        }

    /* =========================================================
       TODAY
    ========================================================= */

    function renderToday() {

        const list =
            document.getElementById(
                'todayList'
            );


        const count =
            document.getElementById(
                'todayCount'
            );


        if (!list) {

            return;

        }


        const keyword =
            searchKeyword
                .trim()
                .toLowerCase();


        filteredReservations =
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
                        booker.includes(
                            keyword
                        )
                    );

                }
            );


        if (count) {

            count.textContent =
                `${filteredReservations.length} ${
                    filteredReservations.length === 1
                        ? 'reservation'
                        : 'reservations'
                }`;

        }


        if (
            filteredReservations.length === 0
        ) {

            list.innerHTML = `

                <div class="today-empty">

                    ${
                        keyword
                            ? 'No matching reservations'
                            : 'There are no events scheduled for today'
                    }

                </div>

            `;

            stopTodayCarousel();

            return;

        }


        list.innerHTML = `

            <div
                class="today-track"
                id="todayTrack"
            >

                ${
                    filteredReservations
                        .map(
                            reservation => {

                                const status =
                                    getReservationStatus(
                                        reservation
                                    );


                                let statusClass =
                                    '';


                                if (
                                    status ===
                                    'IN PROGRESS'
                                ) {

                                    statusClass =
                                        'current';

                                }


                                if (
                                    status ===
                                    'COMPLETED'
                                ) {

                                    statusClass =
                                        'completed';

                                }


                                return `

                                    <div
                                        class="today-row"
                                    >

                                        <!-- TIME -->

                                        <div
                                            class="today-time"
                                        >

                                            ${formatTime(
                                                reservation.starts_at
                                            )}

                                            -

                                            ${formatTime(
                                                reservation.ends_at
                                            )}

                                        </div>


                                        <!-- EVENT -->

                                        <div
                                            class="today-event"
                                        >

                                            ${escapeHtml(
                                                getEventName(
                                                    reservation
                                                )
                                            )}

                                        </div>


                                        <!-- ROOM -->

                                        <div
                                            class="today-resource-block"
                                        >

                                            <div
                                                class="today-resource-label"
                                            >

                                                ${escapeHtml(
                                                    getResourceType(
                                                        reservation
                                                    )
                                                )}

                                            </div>


                                            <div
                                                class="today-resource"
                                            >

                                                ${escapeHtml(
                                                    getResourceName(
                                                        reservation
                                                    )
                                                )}

                                            </div>

                                        </div>


                                        <!-- STATUS -->

                                        <div
                                            class="
                                                today-status
                                                ${statusClass}
                                            "
                                        >

                                            ${status}

                                        </div>

                                    </div>

                                `;

                            }
                        )
                        .join('')
                }

            </div>

        `;


        todayIndex = 0;


        requestAnimationFrame(
            () => {

                calculateTodayCarousel();

                startTodayCarousel();

            }
        );

    }


    function stopTodayCarousel() {

        if (todayTimer) {

            clearInterval(
                todayTimer
            );

            todayTimer = null;

        }

    }


    function calculateTodayCarousel() {

        const list =
            document.getElementById(
                'todayList'
            );

        const track =
            document.getElementById(
                'todayTrack'
            );


        if (
            !list ||
            !track ||
            filteredReservations.length === 0
        ) {
            return;
        }


        /*
        * Ambil hanya card asli.
        * Clone tidak dihitung sebagai data asli.
        */

        const originalItems =
            Array.from(
                track.querySelectorAll(
                    '.today-row:not(.today-clone)'
                )
            );


        const originalCount =
            originalItems.length;


        /*
        * Hapus clone lama.
        *
        * Penting karena fungsi ini juga
        * dipanggil saat resize.
        */

        track
            .querySelectorAll(
                '.today-clone'
            )
            .forEach(
                clone => clone.remove()
            );


        todayIndex = 0;


        /*
        * =====================================
        * DATA 1 - 5
        * =====================================
        *
        * Tidak perlu carousel.
        */

        if (
            originalCount < todayVisibleItems
        ) {

            originalItems.forEach(
                item => {

                    item.style.height = '';

                    item.style.minHeight =
                        '76px';

                    item.style.flex =
                        '';

                }
            );


            track.style.transition =
                'none';

            track.style.transform =
                'translateY(0)';

            todayItemHeight = 76;


            return;

        }


        /*
        * =====================================
        * DATA 6+
        * =====================================
        *
        * Kita clone 6 card pertama.
        */

        const cloneCount =
            Math.min(
                todayVisibleItems,
                originalCount
            );


        for (
            let i = 0;
            i < cloneCount;
            i++
        ) {

            const clone =
                originalItems[i]
                    .cloneNode(true);


            clone.classList.add(
                'today-clone'
            );


            track.appendChild(
                clone
            );

        }


        /*
        * =====================================
        * HITUNG TINGGI CARD
        * =====================================
        */

        const styles =
            window.getComputedStyle(
                track
            );


        const gap =
            parseFloat(
                styles.rowGap
            ) || 8;


        todayGap = gap;


        const availableHeight =
            list.clientHeight;


        const visible =
            todayVisibleItems;


        todayItemHeight =
            Math.floor(
                (
                    availableHeight
                    -
                    (
                        gap *
                        (visible - 1)
                    )
                )
                /
                visible
            );


        todayItemHeight =
            Math.max(
                76,
                todayItemHeight
            );


        /*
        * Terapkan ukuran ke semua
        * card asli + clone.
        */

        const allItems =
            track.querySelectorAll(
                '.today-row'
            );


        allItems.forEach(
            item => {

                item.style.height =
                    `${todayItemHeight}px`;

                item.style.minHeight =
                    `${todayItemHeight}px`;

                item.style.flex =
                    `0 0 ${todayItemHeight}px`;

            }
        );


        track.style.gap =
            `${todayGap}px`;


        track.style.transition =
            'none';

        track.style.transform =
            'translateY(0)';

    }


    function startTodayCarousel() {

        stopTodayCarousel();


        /*
        * Kalau belum mencapai 6 item,
        * tidak perlu looping.
        */

        if (
            filteredReservations.length <
            todayVisibleItems
        ) {

            return;

        }


        const track =
            document.getElementById(
                'todayTrack'
            );


        if (!track) {

            return;

        }


        let currentIndex = 0;


        /*
        * Satu card bergeser setiap 5 detik.
        */

        todayTimer =
            setInterval(
                () => {

                    currentIndex++;


                    const translateY =
                        currentIndex *
                        (
                            todayItemHeight
                            +
                            todayGap
                        );


                    track.style.transition =
                        'transform 0.75s cubic-bezier(0.22, 0.61, 0.36, 1)';


                    track.style.transform =
                        `translateY(-${translateY}px)`;


                    /*
                    * Setelah melewati semua
                    * reservation asli, kita sudah
                    * berada di clone.
                    *
                    * Contoh:
                    *
                    * 1 2 3 4 5 6
                    * 2 3 4 5 6 1
                    * 3 4 5 6 1 2
                    * ...
                    * 6 1 2 3 4 5
                    * 1 2 3 4 5 6  <- clone
                    */

                    if (
                        currentIndex >=
                        filteredReservations.length
                    ) {

                        setTimeout(
                            () => {

                                currentIndex = 0;


                                track.style.transition =
                                    'none';


                                track.style.transform =
                                    'translateY(0)';


                                /*
                                * Paksa browser
                                * menerima posisi baru
                                * sebelum animasi berikutnya.
                                */

                                void track.offsetHeight;

                            },
                            800
                        );

                    }

                },
                7000
            );

    }


    /* =========================================================
       UPCOMING
    ========================================================= */

    function stopUpcomingCarousel() {

        if (
            upcomingTimer
        ) {

            clearInterval(
                upcomingTimer
            );

            upcomingTimer = null;

        }

    }


    function renderUpcoming() {

        stopUpcomingCarousel();


        const list =
            document.getElementById(
                'upcomingList'
            );


        const subtitle =
            document.getElementById(
                'upcomingSubtitle'
            );


        if (!list) {

            return;

        }


        if (
            tomorrowReservations.length ===
            0
        ) {

            list.innerHTML = `

                <div class="upcoming-empty">

                    No bookings scheduled for tomorrow

                </div>

            `;


            if (subtitle) {

                subtitle.textContent =
                    formatDate(
                        new Date(
                            Date.now()
                            +
                            86400000
                        )
                    );

            }


            return;

        }


        // if (subtitle) {

        //     subtitle.textContent =
        //         formatDate(
        //             tomorrowReservations[0]
        //                 ?.starts_at
        //         );

        // }

        if (subtitle) {
            subtitle.textContent =
                formatDate(
                    new Date(
                        Date.now() + 86400000
                    )
                );
        }


        list.innerHTML =
            tomorrowReservations
                .slice(
                    0,
                    5
                )
                .map(
                    (
                        reservation,
                        index
                    ) => `

                        <div
                            class="
                                upcoming-card
                                ${
                                    index === 0
                                        ? 'active'
                                        : ''
                                }
                            "
                        >

                            <div
                                class="
                                    upcoming-time
                                "
                            >

                                <div
                                    class="
                                        upcoming-time-start
                                    "
                                >

                                    ${formatTime(
                                        reservation.starts_at
                                    )}

                                </div>


                                <div
                                    class="
                                        upcoming-time-end
                                    "
                                >

                                    ${formatTime(
                                        reservation.ends_at
                                    )}

                                </div>

                            </div>


                            <div
                                class="
                                    upcoming-content
                                "
                            >

                                <div
                                    class="
                                        upcoming-event
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
                                        upcoming-resource
                                    "
                                >

                                    ${escapeHtml(
                                        getResourceType(
                                            reservation
                                        )
                                    )}

                                    ·

                                    ${escapeHtml(
                                        getResourceName(
                                            reservation
                                        )
                                    )}

                                </div>

                            </div>

                        </div>

                    `
                )
                .join('');


        const cards =
            Array.from(
                list.querySelectorAll(
                    '.upcoming-card'
                )
            );


        if (
            cards.length <= 1
        ) {

            return;

        }


        let index = 0;


        upcomingTimer =
            setInterval(
                () => {

                    cards[index]
                        .classList.remove(
                            'active'
                        );


                    index =
                        (
                            index + 1
                        )
                        %
                        cards.length;


                    cards[index]
                        .classList.add(
                            'active'
                        );

                },
                4000
            );

    }


    /* =========================================================
       NEWS
    ========================================================= */

    function stopNewsCarousel() {

        if (
            newsTimer
        ) {

            clearInterval(
                newsTimer
            );

            newsTimer = null;

        }

    }


    function renderNews() {

        stopNewsCarousel();


        const list =
            document.getElementById(
                'newsList'
            );


        if (!list) {

            return;

        }


        if (
            newsItems.length === 0
        ) {

            list.innerHTML = `

                <div class="news-empty">

                    No active news or announcements

                </div>

            `;

            return;

        }


        list.innerHTML =
            newsItems
                .map(
                    (
                        news,
                        index
                    ) => {

                        const excerpt =
                            stripHtml(
                                news.content
                            );


                        const imageUrl =
                            news.image_url
                            ??
                            null;


                        return `

                            <article
                                class="
                                    news-card
                                    ${
                                        index === 0
                                            ? 'active'
                                            : ''
                                    }
                                "
                            >


                                <div
                                    class="news-image"
                                >

                                    ${
                                        imageUrl
                                            ? `
                                                <img
                                                    src="${escapeHtml(
                                                        imageUrl
                                                    )}"
                                                    alt="${escapeHtml(
                                                        news.title
                                                    )}"
                                                    loading="lazy"
                                                >
                                            `
                                            : `
                                                <div
                                                    class="
                                                        news-image-empty
                                                    "
                                                >
                                                    NEWS
                                                </div>
                                            `
                                    }

                                </div>


                                <div
                                    class="news-overlay"
                                ></div>


                                <div
                                    class="
                                        news-pagination
                                    "
                                >

                                    ${newsItems
                                        .map(
                                            (
                                                _,
                                                dotIndex
                                            ) => `

                                                <span
                                                    class="
                                                        news-dot
                                                        ${
                                                            dotIndex === 0
                                                                ? 'active'
                                                                : ''
                                                        }
                                                    "
                                                ></span>

                                            `
                                        )
                                        .join('')
                                    }

                                    <span>
                                        ${
                                            String(
                                                index + 1
                                            ).padStart(
                                                2,
                                                '0'
                                            )
                                        } /
                                        ${
                                            String(
                                                newsItems.length
                                            ).padStart(
                                                2,
                                                '0'
                                            )
                                        }
                                    </span>

                                </div>


                                <div
                                    class="news-content"
                                >



                                    <div
                                        class="news-title"
                                    >

                                        ${escapeHtml(
                                            news.title
                                        )}

                                    </div>


                                    <div
                                        class="news-excerpt"
                                    >

                                        ${escapeHtml(
                                            excerpt
                                        )}

                                    </div>

                                </div>


                            </article>

                        `;

                    }
                )
                .join('');


        const cards =
            Array.from(
                list.querySelectorAll(
                    '.news-card'
                )
            );

                    cards.forEach(
            (
                card,
                index
            ) => {

                card.addEventListener(
                    'click',
                    () => {

                        const news =
                            newsItems[index];


                        openNewsModal(
                            news
                        );

                    }
                );

            }
        );

        if (newsModalClose) {

            newsModalClose.addEventListener(
                'click',
                closeNewsModal
            );

        }


        if (newsModal) {

            newsModal.addEventListener(
                'click',
                function (event) {

                    if (
                        event.target ===
                        newsModal
                    ) {

                        closeNewsModal();

                    }

                }
            );

        }


        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key ===
                    'Escape'
                ) {

                    closeNewsModal();

                }

            }
        );


        if (
            cards.length <= 1
        ) {

            return;

        }


        let index = 0;


        newsTimer =
            setInterval(
                () => {

                    cards[index]
                        .classList.remove(
                            'active'
                        );


                    index =
                        (
                            index + 1
                        )
                        %
                        cards.length;


                    cards[index]
                        .classList.add(
                            'active'
                        );


                    const dots =
                        cards[index]
                            .querySelectorAll(
                                '.news-dot'
                            );


                    dots.forEach(
                        (
                            dot,
                            dotIndex
                        ) => {

                            dot.classList.toggle(
                                'active',
                                dotIndex === index
                            );

                        }
                    );

                },
                5000
            );

    }

    document.addEventListener(
    'keydown',
    function (event) {

        if (
            !newsModal ||
            !newsModal.classList.contains(
                'open'
            )
        ) {

            return;

        }


        if (
            event.key === 'ArrowLeft'
        ) {

            event.preventDefault();

            showPreviousNews();

        }


        if (
            event.key === 'ArrowRight'
        ) {

            event.preventDefault();

            showNextNews();

        }

    }
);

    /* =========================================================
    NEWS MODAL
    ========================================================= */

    const newsModal =
        document.getElementById(
            'newsModal'
        );


    const newsModalClose =
        document.getElementById(
            'newsModalClose'
        );

    const newsModalPrev =
        document.getElementById(
            'newsModalPrev'
        );


    const newsModalNext =
        document.getElementById(
            'newsModalNext'
        );


    const newsModalImage =
        document.getElementById(
            'newsModalImage'
        );


    const newsModalTitle =
        document.getElementById(
            'newsModalTitle'
        );


    const newsModalDate =
        document.getElementById(
            'newsModalDate'
        );


    const newsModalContent =
        document.getElementById(
            'newsModalContent'
        );


    function renderNewsModal() {

            if (
                !newsModal ||
                !newsItems.length
            ) {

                return;

            }


            const news =
                newsItems[
                    selectedNewsIndex
                ];


            if (!news) {

                return;

            }


            selectedNews =
                news;


            const imageUrl =
                news.image_url
                ??
                null;


            if (
                imageUrl
            ) {

                newsModalImage.innerHTML = `

                    <img
                        src="${escapeHtml(
                            imageUrl
                        )}"
                        alt="${escapeHtml(
                            news.title
                        )}"
                    >

                `;

            } else {

                newsModalImage.innerHTML = `

                    <div
                        class="
                            news-modal-image-empty
                        "
                    >
                        NEWS
                    </div>

                `;

            }


            newsModalTitle.textContent =
                news.title
                ??
                'Untitled News';


            newsModalDate.textContent =
                news.starts_at
                    ? formatDate(
                        news.starts_at
                    )
                    : '';


            newsModalContent.textContent =
                stripHtml(
                    news.content
                );


            const hasMultipleNews =
                newsItems.length > 1;


            if (
                newsModalPrev
            ) {

                newsModalPrev.hidden =
                    !hasMultipleNews;

            }


            if (
                newsModalNext
            ) {

                newsModalNext.hidden =
                    !hasMultipleNews;

            }

        }


        function openNewsModal(
            news
        ) {

            if (
                !newsModal ||
                !news
            ) {

                return;

            }


            const index =
                newsItems.indexOf(
                    news
                );


            selectedNewsIndex =
                index >= 0
                    ? index
                    : 0;


            renderNewsModal();


            newsModal.classList.add(
                'open'
            );


            newsModal.setAttribute(
                'aria-hidden',
                'false'
            );


            document.body.classList.add(
                'news-modal-open'
            );

        }


    function closeNewsModal() {

        if (!newsModal) {

            return;

        }


        newsModal.classList.remove(
            'open'
        );


        newsModal.setAttribute(
            'aria-hidden',
            'true'
        );


        document.body.classList.remove(
            'news-modal-open'
        );


        selectedNews =
            null;

    }

    function showPreviousNews() {

            if (
                newsItems.length <= 1
            ) {

                return;

            }


            selectedNewsIndex--;

            if (
                selectedNewsIndex < 0
            ) {

                selectedNewsIndex =
                    newsItems.length - 1;

            }


            renderNewsModal();

        }


        function showNextNews() {

            if (
                newsItems.length <= 1
            ) {

                return;

            }


            selectedNewsIndex++;

            if (
                selectedNewsIndex >=
                newsItems.length
            ) {

                selectedNewsIndex = 0;

            }


            renderNewsModal();

        }


        if (newsModalPrev) {

            newsModalPrev.addEventListener(
                'click',
                function (event) {

                    event.stopPropagation();

                    showPreviousNews();

                }
            );

        }


        if (newsModalNext) {

            newsModalNext.addEventListener(
                'click',
                function (event) {

                    event.stopPropagation();

                    showNextNews();

                }
            );

        }

        /* =========================================================
        NEWS MODAL SWIPE
        ========================================================= */



        if (newsModal) {

            newsModal.addEventListener(
                'touchstart',
                function (event) {

                    if (
                        !newsModal.classList.contains('open')
                    ) {

                        return;

                    }

                    const touch =
                        event.changedTouches[0];

                    newsTouchStartX =
                        touch.clientX;

                    newsTouchStartY =
                        touch.clientY;

                },
                {
                    passive: true
                }
            );


            newsModal.addEventListener(
                'touchend',
                function (event) {

                    if (
                        !newsModal.classList.contains('open')
                    ) {

                        return;

                    }

                    if (
                        newsItems.length <= 1
                    ) {

                        return;

                    }

                    const touch =
                        event.changedTouches[0];

                    const deltaX =
                        touch.clientX -
                        newsTouchStartX;

                    const deltaY =
                        touch.clientY -
                        newsTouchStartY;


                    /*
                    * Abaikan jika gerakannya
                    * lebih dominan vertikal.
                    */
                    if (
                        Math.abs(deltaX) <=
                        Math.abs(deltaY)
                    ) {

                        return;

                    }


                    /*
                    * Minimal jarak swipe 60px.
                    */
                    if (
                        Math.abs(deltaX) < 60
                    ) {

                        return;

                    }


                    /*
                    * Swipe ke kiri
                    * = News berikutnya
                    */
                    if (
                        deltaX < 0
                    ) {

                        selectedNewsIndex++;

                        if (
                            selectedNewsIndex >=
                            newsItems.length
                        ) {

                            selectedNewsIndex = 0;

                        }

                    }


                    /*
                    * Swipe ke kanan
                    * = News sebelumnya
                    */
                    else {

                        selectedNewsIndex--;

                        if (
                            selectedNewsIndex < 0
                        ) {

                            selectedNewsIndex =
                                newsItems.length - 1;

                        }

                    }


                    selectedNews =
                        newsItems[
                            selectedNewsIndex
                        ];


                    renderNewsModal();

                },
                {
                    passive: true
                }
            );

        }


    /* =========================================================
       LOAD DISPLAY DATA
    ========================================================= */

    async function loadDisplayData() {

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


            displayData =
                await response.json();


            reservations =
                displayData
                    .today_schedule
                ?? 
                displayData
                    .reservations
                ??
                [];


            tomorrowReservations =
                displayData
                    .tomorrow_reservations
                ??
                displayData
                    .upcoming_class
                ??
                [];


            newsItems =
                displayData.news
                ??
                [];


            /*
             * Re-render all sections.
             */

            renderHero();

            renderToday();

            renderUpcoming();

            renderNews();


            const heroContent =
                document.getElementById(
                    'heroContent'
                );


            if (heroContent) {

                heroContent.classList.add(
                    'ready'
                );

            }


            console.log(
                'Display data updated:',
                new Date()
                    .toLocaleTimeString()
            );

        } catch (error) {

            console.error(
                'Failed to update display:',
                error
            );


            const heroContent =
                document.getElementById(
                    'heroContent'
                );


            if (heroContent) {

                heroContent.innerHTML = `

                    <div
                        class="
                            hero-status
                            empty
                        "
                    >

                        <span
                            class="
                                hero-status-dot
                            "
                        ></span>

                        <span>
                            System Error
                        </span>

                    </div>


                    <div
                        class="hero-empty"
                    >

                        <div
                            class="
                                hero-empty-title
                            "
                        >
                            Unable to load display data
                        </div>


                        <div
                            class="
                                hero-empty-text
                            "
                        >
                            Please check the
                            reservation display
                            connection.
                        </div>

                    </div>

                `;

                heroContent.classList.add(
                    'ready'
                );

            }


            const todayList =
                document.getElementById(
                    'todayList'
                );


            if (todayList) {

                todayList.innerHTML = `

                    <div class="today-empty">

                        Unable to load schedule.

                    </div>

                `;

            }


            const upcomingList =
                document.getElementById(
                    'upcomingList'
                );


            if (upcomingList) {

                upcomingList.innerHTML = `

                    <div class="upcoming-empty">

                        Unable to load schedule.

                    </div>

                `;

            }


            const newsList =
                document.getElementById(
                    'newsList'
                );


            if (newsList) {

                newsList.innerHTML = `

                    <div class="news-empty">

                        Unable to load news.

                    </div>

                `;

            }

        }

    }

    

    /* =========================================================
       SEARCH
    ========================================================= */

    const todaySearch =
        document.getElementById(
            'todaySearch'
        );


    if (todaySearch) {

        todaySearch.addEventListener(
            'input',
            function () {

                searchKeyword =
                    this.value
                        .trim()
                        .toLowerCase();


                renderToday();

            }
        );

    }


    /* =========================================================
       RESIZE
    ========================================================= */

    window.addEventListener(
        'resize',
        () => {

            calculateTodayCarousel();

        }
    );

    /* =========================================================
       INITIAL LOAD
    ========================================================= */

    loadDisplayData();


    /* =========================================================
       AUTO REFRESH
       90 seconds
    ========================================================= */

    setInterval(
        loadDisplayData,
        90000
    );


</script>


</body>

</html>