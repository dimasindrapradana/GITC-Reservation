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
            --background: #f3f7fa;
            --border: #d9e5ed;
            --text: #12304a;
            --muted: #668096;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--background);
            color: var(--text);
            font-family: Arial, Helvetica, sans-serif;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 24px;
        }

        .login-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 36px;
            box-shadow: 0 12px 35px rgba(0, 41, 79, 0.08);
        }

        .brand {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-mark {
            width: 58px;
            height: 58px;
            margin: 0 auto 16px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--navy);
            color: var(--white);
            font-size: 22px;
            font-weight: 700;
        }

        .brand h1 {
            margin: 0;
            color: var(--navy);
            font-size: 24px;
        }

        .brand p {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-size: 14px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 9px;
            outline: none;
            font-size: 15px;
            color: var(--text);
        }

        input:focus {
            border-color: var(--cyan);
            box-shadow: 0 0 0 3px rgba(0, 168, 200, 0.12);
        }

        .error {
            margin-top: 6px;
            color: #d93025;
            font-size: 13px;
        }

        button {
            width: 100%;
            border: 0;
            border-radius: 9px;
            padding: 13px;
            background: var(--navy);
            color: var(--white);
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }

        button:hover {
            background: var(--navy-dark);
        }

        .footer {
            margin-top: 24px;
            text-align: center;
            color: var(--muted);
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="login-card">

            <div class="brand">
                <div class="brand-mark">
                    GI
                </div>

                <h1>GITC Info</h1>

                <p>News & Reservation Management System</p>
            </div>

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="form-group">
                    <label for="username">Username</label>

                    <input
                        id="username"
                        type="text"
                        name="username"
                        value="{{ old('username') }}"
                        autocomplete="username"
                        required
                        autofocus
                    >

                    @error('username')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        autocomplete="current-password"
                        required
                    >

                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit">
                    Login
                </button>
            </form>

            <div class="footer">
                GITC Info Management System
            </div>

        </div>
    </div>
</body>
</html>