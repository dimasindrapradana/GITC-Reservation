<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - GITC Info</title>

    <style>
        :root {
            --navy: #003b6f;
            --navy-dark: #00294f;
            --blue: #006fae;
            --cyan: #00a8c8;

            --background: #eef4f8;
            --white: #ffffff;

            --text: #12304a;
            --muted: #6d8292;
            --border: #d7e2e9;

            --danger: #d93025;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            width: 100%;
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;

            padding: 32px;

            background:
                radial-gradient(
                    circle at top left,
                    rgba(0, 111, 174, 0.08),
                    transparent 34%
                ),
                var(--background);

            color: var(--text);

            font-family:
                'Garuda Sans',
                Arial,
                Helvetica,
                sans-serif;
        }

        /* =========================================================
           LOGIN SHELL
        ========================================================= */

        .login-shell {
            width: min(1180px, 100%);
            min-height: 680px;

            display: grid;
            grid-template-columns: 55% 45%;

            overflow: hidden;

            background: var(--white);

            border: 1px solid rgba(0, 59, 111, 0.08);
            border-radius: 24px;

            box-shadow:
                0 25px 70px rgba(0, 41, 79, 0.12),
                0 8px 24px rgba(0, 41, 79, 0.06);
        }

        /* =========================================================
           LEFT / HERO
        ========================================================= */

        .login-hero {
            position: relative;
            min-height: 680px;

            overflow: hidden;

            background: var(--navy);
        }

        .login-hero-image {
            position: absolute;
            inset: 0;

            width: 100%;
            height: 100%;

            object-fit: cover;
            object-position: center center;

            display: block;
        }

        /*
         * Soft overlay.
         * Tidak dibuat terlalu gelap supaya foto pesawat tetap terlihat.
         */
        .login-hero-overlay {
            position: absolute;
            inset: 0;

            background:
                linear-gradient(
                    180deg,
                    rgba(0, 41, 79, 0.18) 0%,
                    rgba(0, 59, 111, 0.02) 38%,
                    rgba(0, 41, 79, 0.48) 100%
                );
        }

        /*
         * Sedikit gradient dari kiri agar logo putih tetap terbaca.
         */
        .login-hero-overlay::after {
            content: "";
            position: absolute;
            inset: 0;

            background:
                linear-gradient(
                    90deg,
                    rgba(0, 41, 79, 0.34) 0%,
                    rgba(0, 41, 79, 0.04) 48%,
                    transparent 100%
                );
        }

        .hero-content {
            position: relative;
            z-index: 2;

            height: 100%;

            display: flex;
            flex-direction: column;
            justify-content: space-between;

            padding: 38px 42px;
        }

        /* =========================================================
           HERO LOGO
        ========================================================= */

        .hero-brand {
            display: flex;
            align-items: center;

            width: fit-content;
        }

        .hero-brand-logo {
            width: 145px;
            height: 58px;

            display: flex;
            align-items: center;
            justify-content: flex-start;

            overflow: hidden;
        }

        .hero-brand-logo img {
            display: block;

            width: 100%;
            height: 100%;

            object-fit: contain;
            object-position: left center;

            /*
             * Membuat logo Garuda menjadi putih
             * untuk background foto.
             */
            filter: brightness(0) invert(1);
        }

        /* =========================================================
           HERO TEXT
        ========================================================= */

        .hero-copy {
            max-width: 470px;
        }

        .hero-eyebrow {
            margin-bottom: 10px;

            color: rgba(255, 255, 255, 0.84);

            font-family:
                'Garuda Sans',
                Arial,
                Helvetica,
                sans-serif;

            font-size: 13px;
            font-weight: 600;

            letter-spacing: 0.02em;
        }

        .hero-title {
            margin: 0;

            color: var(--white);

            font-family:
                'Garuda Serif',
                Georgia,
                serif;

            font-size: clamp(32px, 3.2vw, 46px);
            line-height: 1.04;

            font-weight: 700;

            letter-spacing: -0.025em;
        }

        .hero-description {
            margin: 16px 0 0;

            max-width: 400px;

            color: rgba(255, 255, 255, 0.84);

            font-family:
                'Garuda Sans',
                Arial,
                Helvetica,
                sans-serif;

            font-size: 14px;
            line-height: 1.65;
        }

        .hero-footer {
            display: flex;
            align-items: center;

            color: rgba(255, 255, 255, 0.68);

            font-size: 11px;
        }

        /* =========================================================
           RIGHT / LOGIN
        ========================================================= */

        .login-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;

            padding: 56px 62px;

            background: var(--white);
        }

        .login-panel-inner {
            width: 100%;
            max-width: 380px;

            margin: 0 auto;
        }

        /* =========================================================
           RIGHT BRAND
        ========================================================= */

        .login-brand {
            margin-bottom: 38px;
        }

        .login-brand-logo {
            width: 150px;
            height: 58px;

            margin-bottom: 28px;

            display: flex;
            align-items: center;
            justify-content: flex-start;

            overflow: hidden;
        }

        .login-brand-logo img {
            display: block;

            width: 100%;
            height: 100%;

            object-fit: contain;
            object-position: left center;
        }

        .login-eyebrow {
            margin: 0 0 7px;

            color: var(--blue);

            font-family:
                'Garuda Sans',
                Arial,
                Helvetica,
                sans-serif;

            font-size: 13px;
            font-weight: 600;
        }

        .login-title {
            margin: 0;

            color: var(--navy);

            font-family:
                'Garuda Serif',
                Georgia,
                serif;

            font-size: 34px;
            line-height: 1.08;

            font-weight: 700;

            letter-spacing: -0.025em;
        }

        .login-subtitle {
            margin: 11px 0 0;

            color: var(--muted);

            font-family:
                'Garuda Sans',
                Arial,
                Helvetica,
                sans-serif;

            font-size: 13px;
            line-height: 1.55;
        }

        /* =========================================================
           FORM
        ========================================================= */

        .form-group {
            margin-bottom: 19px;
        }

        label {
            display: block;

            margin-bottom: 8px;

            color: var(--text);

            font-family:
                'Garuda Sans',
                Arial,
                Helvetica,
                sans-serif;

            font-size: 13px;
            font-weight: 600;
        }

        .input-wrapper {
            position: relative;
        }

        input {
            width: 100%;

            min-height: 48px;

            padding: 12px 14px;

            border: 1px solid var(--border);
            border-radius: 9px;

            outline: none;

            background: var(--white);

            color: var(--text);

            font-family:
                'Garuda Sans',
                Arial,
                Helvetica,
                sans-serif;

            font-size: 14px;

            transition:
                border-color 0.18s ease,
                box-shadow 0.18s ease;
        }

        input::placeholder {
            color: #9aabb7;
        }

        input:hover {
            border-color: #b9ccd8;
        }

        input:focus {
            border-color: var(--blue);

            box-shadow:
                0 0 0 3px rgba(0, 111, 174, 0.10);
        }

        .error {
            margin-top: 7px;

            color: var(--danger);

            font-family:
                'Garuda Sans',
                Arial,
                Helvetica,
                sans-serif;

            font-size: 12px;
            line-height: 1.4;
        }

        /* =========================================================
           LOGIN BUTTON
        ========================================================= */

        .login-button {
            width: 100%;

            min-height: 48px;

            margin-top: 7px;

            border: 0;
            border-radius: 9px;

            background: var(--navy);

            color: var(--white);

            font-family:
                'Garuda Sans',
                Arial,
                Helvetica,
                sans-serif;

            font-size: 14px;
            font-weight: 600;

            cursor: pointer;

            box-shadow:
                0 7px 18px rgba(0, 59, 111, 0.16);

            transition:
                background 0.18s ease,
                transform 0.18s ease,
                box-shadow 0.18s ease;
        }

        .login-button:hover {
            background: var(--navy-dark);

            transform: translateY(-1px);

            box-shadow:
                0 10px 22px rgba(0, 59, 111, 0.20);
        }

        .login-button:active {
            transform: translateY(0);
        }

        /* =========================================================
           FOOTER
        ========================================================= */

        .login-footer {
            margin-top: 28px;

            padding-top: 22px;

            border-top: 1px solid #edf2f5;

            text-align: center;

            color: #8a9aa6;

            font-family:
                'Garuda Sans',
                Arial,
                Helvetica,
                sans-serif;

            font-size: 11px;
            line-height: 1.5;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 900px) {
            body {
                padding: 20px;
            }

            .login-shell {
                grid-template-columns: 1fr;

                min-height: auto;

                max-width: 560px;
            }

            .login-hero {
                min-height: 360px;
            }

            .hero-content {
                min-height: 360px;

                padding: 28px 30px;
            }

            .hero-title {
                font-size: 34px;
            }

            .login-panel {
                padding: 42px 34px;
            }
        }

        @media (max-width: 560px) {
            body {
                padding: 0;

                align-items: stretch;
            }

            .login-shell {
                width: 100%;
                min-height: 100vh;

                border: 0;
                border-radius: 0;

                box-shadow: none;
            }

            .login-hero {
                min-height: 300px;
            }

            .hero-content {
                min-height: 300px;

                padding: 24px;
            }

            .hero-brand-logo {
                width: 125px;
                height: 50px;
            }

            .hero-title {
                font-size: 29px;
            }

            .hero-description {
                font-size: 13px;
            }

            .login-panel {
                padding: 38px 24px;
            }

            .login-brand {
                margin-bottom: 30px;
            }

            .login-brand-logo {
                margin-bottom: 22px;
            }

            .login-title {
                font-size: 30px;
            }
        }

        @media (max-height: 760px) and (min-width: 901px) {
            .login-shell {
                min-height: 620px;
            }

            .login-hero {
                min-height: 620px;
            }

            .hero-content {
                padding-top: 28px;
                padding-bottom: 28px;
            }

            .login-panel {
                padding-top: 40px;
                padding-bottom: 40px;
            }
        }
    </style>
</head>

<body>

    <main class="login-shell">

        <!-- =====================================================
             LEFT : HERO IMAGE
        ====================================================== -->

        <section class="login-hero">

            <img
                class="login-hero-image"
                src="{{ asset('assets/images/Login.jpg') }}"
                alt="Garuda Indonesia Training Center"
            >

            <div class="login-hero-overlay"></div>

            <div class="hero-content">

                <div class="hero-brand">
                    <div class="hero-brand-logo">
                        <img
                            src="{{ asset('assets/icons/logo/Garuda.svg') }}"
                            alt="Garuda Indonesia"
                        >
                    </div>
                </div>

                <div class="hero-copy">

                    <div class="hero-eyebrow">
                        Garuda Indonesia Training Center
                    </div>

                    <h2 class="hero-title">
                        GITC Info
                    </h2>

                    <p class="hero-description">
                        Training, reservation, and information management
                        in one integrated system.
                    </p>

                </div>

                <div class="hero-footer">
                    GITC Info Management System
                </div>

            </div>

        </section>


        <!-- =====================================================
             RIGHT : LOGIN FORM
        ====================================================== -->

        <section class="login-panel">

            <div class="login-panel-inner">

                <div class="login-brand">

                    <div class="login-brand-logo">
                        <img
                            src="{{ asset('assets/icons/logo/Garuda.svg') }}"
                            alt="Garuda Indonesia"
                        >
                    </div>

                    <p class="login-eyebrow">
                        Welcome back
                    </p>

                    <h1 class="login-title">
                        Sign in
                    </h1>

                    <p class="login-subtitle">
                        Access your GITC Info account to continue.
                    </p>

                </div>


                <form
                    method="POST"
                    action="{{ route('login.store') }}"
                >
                    @csrf

                    <!-- Username -->

                    <div class="form-group">

                        <label for="username">
                            Username
                        </label>

                        <div class="input-wrapper">

                            <input
                                id="username"
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                autocomplete="username"
                                placeholder="Enter your username"
                                required
                                autofocus
                            >

                        </div>

                        @error('username')
                            <div class="error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <!-- Password -->

                    <div class="form-group">

                        <label for="password">
                            Password
                        </label>

                        <div class="input-wrapper">

                            <input
                                id="password"
                                type="password"
                                name="password"
                                autocomplete="current-password"
                                placeholder="Enter your password"
                                required
                            >

                        </div>

                        @error('password')
                            <div class="error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <!-- Login -->

                    <button
                        type="submit"
                        class="login-button"
                    >
                        Login
                    </button>

                </form>


                <div class="login-footer">
                    © {{ date('Y') }} GITC Info Management System
                </div>

            </div>

        </section>

    </main>

</body>
</html>