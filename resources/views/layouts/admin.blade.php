<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Dashboard') - GITC Info
    </title>

    <style>
        :root {
            --navy: #003b6f;
            --navy-dark: #00294f;
            --blue: #006fae;
            --cyan: #00a8c8;
            --cyan-light: #dff7fb;

            --white: #ffffff;
            --text: #12304a;
            --muted: #668096;
            --background: #f3f7fa;
            --border: #d9e5ed;

            --sidebar-width: 250px;
            --topbar-height: 68px;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
        }

        body {
            background: var(--background);
            color: var(--text);
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* =========================
           APP SHELL
        ========================= */

        .app {
            min-height: 100vh;
        }

        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--sidebar-width);
            background: var(--navy-dark);
            color: var(--white);
            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.10);
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--cyan);
            color: var(--white);
            font-size: 14px;
            font-weight: 800;
        }

        .brand-text {
            line-height: 1.2;
        }

        .brand-name {
            font-size: 16px;
            font-weight: 700;
        }

        .brand-subtitle {
            margin-top: 3px;
            color: rgba(255, 255, 255, 0.60);
            font-size: 10px;
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 18px 12px;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .sidebar-content::-webkit-scrollbar {
            display: none;
            width: 0;
            height: 0;
        }

        .menu-section {
            margin-bottom: 22px;
        }

        .menu-title {
            padding: 0 10px;
            margin-bottom: 7px;
            color: rgba(255, 255, 255, 0.42);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 11px;
            min-height: 42px;
            padding: 9px 11px;
            margin-bottom: 3px;
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.72);
            font-size: 13px;
            transition:
                background 0.15s ease,
                color 0.15s ease;
        }

        .menu-link:hover {
            background: rgba(255, 255, 255, 0.07);
            color: var(--white);
        }

        .menu-link.active {
            background: var(--blue);
            color: var(--white);
        }

        .menu-icon {
            width: 20px;
            text-align: center;
            font-size: 15px;
        }

        .sidebar-footer {
            padding: 14px 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.10);
        }

        /* =========================
           MAIN
        ========================= */

        .main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        /* =========================
           TOPBAR
        ========================= */

        .topbar {
            position: sticky;
            top: 0;
            height: var(--topbar-height);
            background: var(--white);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            z-index: 50;
        }

        .page-heading {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-heading h1 {
            margin: 0;
            color: var(--navy);
            font-size: 18px;
            font-weight: 700;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* =========================
           NOTIFICATION
        ========================= */

        .notification-wrapper {
            position: relative;
        }

        .notification-button {
            position: relative;
            width: 36px;
            height: 36px;
            border: 0;
            background: transparent;
            color: var(--muted);
            border-radius: 8px;
            cursor: pointer;
            font-size: 17px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notification-button:hover {
            background: var(--background);
            color: var(--navy);
        }

        .notification-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #ff4d4d;
        }

        .notification-count {
            position: absolute;
            top: 1px;
            right: 0;
            min-width: 16px;
            height: 16px;
            padding: 0 4px;
            border-radius: 999px;
            background: #ff4d4d;
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            font-weight: 700;
            line-height: 1;
        }

        .notification-count.hidden,
        .notification-dot.hidden {
            display: none;
        }

        .notification-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 360px;
            max-width: calc(100vw - 36px);
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(18, 48, 74, 0.12);
            overflow: hidden;
            display: none;
        }

        .notification-dropdown.open {
            display: block;
        }

        .notification-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 16px;
            border-bottom: 1px solid var(--border);
        }

        .notification-header-title {
            color: var(--navy);
            font-size: 14px;
            font-weight: 700;
        }

        .notification-header-link {
            color: var(--blue);
            font-size: 11px;
            font-weight: 600;
        }

        .notification-header-link:hover {
            text-decoration: underline;
        }

        .notification-list {
            max-height: 360px;
            overflow-y: auto;
        }

        .notification-item {
            display: block;
            width: 100%;
            padding: 13px 16px;
            border: 0;
            border-bottom: 1px solid #edf2f5;
            background: transparent;
            color: inherit;
            font: inherit;
            text-align: left;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .notification-item:hover {
            background: #f7fafc;
        }

        .notification-item.unread {
            background: #f2f9fc;
        }

        .notification-item-top {
            display: flex;
            align-items: flex-start;
            gap: 9px;
        }

        .notification-item-dot {
            width: 7px;
            height: 7px;
            margin-top: 5px;
            flex-shrink: 0;
            border-radius: 50%;
            background: var(--blue);
        }

        .notification-item-dot.read {
            background: transparent;
        }

        .notification-item-content {
            min-width: 0;
            flex: 1;
        }

        .notification-item-title {
            color: var(--text);
            font-size: 12px;
            font-weight: 700;
        }

        .notification-item-message {
            margin-top: 4px;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.5;
        }

        .notification-item-time {
            margin-top: 6px;
            color: #8aa0b1;
            font-size: 10px;
        }

        .notification-empty {
            padding: 28px 18px;
            text-align: center;
            color: var(--muted);
            font-size: 12px;
        }

        .notification-footer {
            padding: 11px 16px;
            border-top: 1px solid var(--border);
            text-align: center;
        }

        .notification-footer-link {
            color: var(--blue);
            font-size: 11px;
            font-weight: 600;
        }

        .notification-footer-link:hover {
            text-decoration: underline;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--cyan-light);
            color: var(--navy);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
        }

        .user-details {
            line-height: 1.2;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
        }

        .user-role {
            margin-top: 3px;
            color: var(--muted);
            font-size: 11px;
        }

        /* =========================
           CONTENT
        ========================= */

        .content {
            padding: 28px;
        }

        .content-header {
            margin-bottom: 24px;
        }

        .content-header h2 {
            margin: 0;
            color: var(--navy);
            font-size: 22px;
        }

        .content-header p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 13px;
        }

        .card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .card-body {
            padding: 24px;
        }

        /* =========================
           MOBILE
        ========================= */

        .mobile-menu-button {
            display: none;
            width: 36px;
            height: 36px;
            border: 0;
            background: transparent;
            color: var(--navy);
            cursor: pointer;
            font-size: 20px;
        }

        @media (max-width: 900px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.2s ease;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main {
                margin-left: 0;
            }

            .mobile-menu-button {
                display: block;
            }

            .topbar {
                padding: 0 18px;
            }

            .content {
                padding: 20px 18px;
            }

            .user-details {
                display: none;
            }

            .notification-dropdown {
                right: -8px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

<div class="app">

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">

        <div class="brand">
            <div class="brand-mark">
                GI
            </div>

            <div class="brand-text">
                <div class="brand-name">
                    GITC Info
                </div>

                <div class="brand-subtitle">
                    Management System
                </div>
            </div>
        </div>

        <div class="sidebar-content">

            {{-- Main --}}
            <div class="menu-section">

                <div class="menu-title">
                    Main
                </div>

                @if(auth()->user()->role->name === 'Admin')

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">⌂</span>
                        <span>Dashboard</span>
                    </a>

                @elseif(auth()->user()->role->name === 'Coordinator')

                    <a
                        href="{{ route('coordinator.dashboard') }}"
                        class="menu-link {{ request()->routeIs('coordinator.dashboard') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">⌂</span>
                        <span>Dashboard</span>
                    </a>

                @endif

            </div>

            {{-- News --}}
            @if(auth()->user()->role->name === 'Admin')

                <div class="menu-section">

                    <div class="menu-title">
                        News
                    </div>

                    <a
                        href="{{ route('news.index') }}"
                        class="menu-link {{ request()->routeIs('news.*') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">▤</span>
                        <span>News</span>
                    </a>

                </div>

            @endif

            {{-- Reservation --}}
            @if(auth()->user()->role->name === 'Admin')

                <div class="menu-section">

                    <div class="menu-title">
                        Reservation
                    </div>

                    <a
                        href="{{ route('reservations.index') }}"
                        class="menu-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">◫</span>
                        <span>Reservations</span>
                    </a>

                </div>

            @elseif(auth()->user()->role->name === 'Coordinator')

                <div class="menu-section">

                    <div class="menu-title">
                        Reservation
                    </div>

                    <a
                        href="{{ route('coordinator.reservations.index') }}"
                        class="menu-link {{ request()->routeIs('coordinator.reservations.*') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">◫</span>
                        <span>Reservations</span>
                    </a>

                </div>

            @endif

            {{-- Reports --}}
            @if(auth()->user()->role->name === 'Coordinator')

                <div class="menu-section">

                    <div class="menu-title">
                        Reports
                    </div>

                    <a
                        href="{{ route('coordinator.reports.reservations') }}"
                        class="menu-link {{ request()->routeIs('coordinator.reports.*') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">▥</span>
                        <span>Reservation Report</span>
                    </a>

                </div>

            @endif

            {{-- Master Data --}}
            @if(auth()->user()->role->name === 'Admin')

                <div class="menu-section">

                    <div class="menu-title">
                        Master Data
                    </div>

                    <a
                        href="{{ route('buildings.index') }}"
                        class="menu-link {{ request()->routeIs('buildings.*') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">▤</span>
                        <span>Buildings</span>
                    </a>

                    <a
                        href="{{ route('rooms.index') }}"
                        class="menu-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">▤</span>
                        <span>Rooms</span>
                    </a>

                    <a
                        href="{{ route('training-rooms.index') }}"
                        class="menu-link {{ request()->routeIs('training-rooms.*') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">▥</span>
                        <span>Media Training</span>
                    </a>

                    <a
                        href="{{ route('fields.index') }}"
                        class="menu-link {{ request()->routeIs('fields.*') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">◇</span>
                        <span>Fields</span>
                    </a>

                </div>

            @endif

            {{-- System --}}
            @if(auth()->user()->role->name === 'Admin')

                <div class="menu-section">

                    <div class="menu-title">
                        System
                    </div>

                    <a
                        href="#"
                        class="menu-link"
                    >
                        <span class="menu-icon">♙</span>
                        <span>Users</span>
                    </a>

                    <a
                        href="{{ route('audit-logs.index') }}"
                        class="menu-link {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">◷</span>
                        <span>Audit Logs</span>
                    </a>

                </div>

            @endif

        </div>

        <div class="sidebar-footer">

            <form
                method="POST"
                action="{{ route('logout') }}"
            >
                @csrf

                <button
                    type="submit"
                    class="menu-link"
                    style="
                        width: 100%;
                        border: 0;
                        background: transparent;
                        cursor: pointer;
                        text-align: left;
                    "
                >
                    <span class="menu-icon">↪</span>
                    <span>Logout</span>
                </button>

            </form>

        </div>

    </aside>

    {{-- MAIN --}}
    <div class="main">

        {{-- TOPBAR --}}
        <header class="topbar">

            <div class="page-heading">

                <button
                    type="button"
                    class="mobile-menu-button"
                    onclick="toggleSidebar()"
                >
                    ☰
                </button>

                <h1>
                    @yield('page_title', 'Dashboard')
                </h1>

            </div>

            <div class="topbar-right">

                {{-- NOTIFICATIONS --}}
                <div class="notification-wrapper">

                    @php
                        $topbarNotifications = \App\Models\Notification::query()
                            ->where('user_id', auth()->id())
                            ->orderByRaw('read_at IS NULL DESC')
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();

                        $unreadNotificationCount = \App\Models\Notification::query()
                            ->where('user_id', auth()->id())
                            ->whereNull('read_at')
                            ->count();
                    @endphp

                    <button
                        type="button"
                        class="notification-button"
                        id="notification-button"
                        title="Notifications"
                        aria-label="Notifications"
                        aria-expanded="false"
                    >
                        ♧

                        <span
                            id="notification-dot"
                            class="notification-dot {{ $unreadNotificationCount > 0 ? '' : 'hidden' }}"
                        ></span>

                        @if($unreadNotificationCount > 0)
                            <span
                                id="notification-count"
                                class="notification-count"
                            >
                                {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}
                            </span>
                        @endif
                    </button>

                    <div
                        class="notification-dropdown"
                        id="notification-dropdown"
                    >

                        <div class="notification-header">

                            <div class="notification-header-title">
                                Notifications
                            </div>

                            <a
                                href="{{ route('notifications.index') }}"
                                class="notification-header-link"
                            >
                                View all
                            </a>

                        </div>

                        <div class="notification-list">

                            @forelse($topbarNotifications as $notification)

                                <form
                                    method="POST"
                                    action="{{ route('notifications.read', $notification) }}"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="notification-item {{ $notification->read_at === null ? 'unread' : '' }}"
                                    >
                                        <div class="notification-item-top">

                                            <span
                                                class="notification-item-dot {{ $notification->read_at !== null ? 'read' : '' }}"
                                            ></span>

                                            <div class="notification-item-content">

                                                <div class="notification-item-title">
                                                    {{ $notification->title }}
                                                </div>

                                                <div class="notification-item-message">
                                                    {{ $notification->message }}
                                                </div>

                                                <div class="notification-item-time">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </div>

                                            </div>

                                        </div>
                                    </button>

                                </form>

                            @empty

                                <div class="notification-empty">
                                    No notifications yet.
                                </div>

                            @endforelse

                        </div>

                        @if($unreadNotificationCount > 0)

                            <div class="notification-footer">

                                <form
                                    method="POST"
                                    action="{{ route('notifications.read-all') }}"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        style="
                                            border: 0;
                                            background: transparent;
                                            color: var(--blue);
                                            font-size: 11px;
                                            font-weight: 600;
                                            cursor: pointer;
                                        "
                                    >
                                        Mark all as read
                                    </button>

                                </form>

                            </div>

                        @endif

                    </div>

                </div>

                {{-- USER --}}
                <div class="user-menu">

                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div class="user-details">

                        <div class="user-name">
                            {{ auth()->user()->name }}
                        </div>

                        <div class="user-role">
                            {{ auth()->user()->role->name }}
                        </div>

                    </div>

                </div>

            </div>

        </header>

        {{-- PAGE CONTENT --}}
        <main class="content">

            @yield('content')

        </main>

    </div>

</div>

<script>
    function toggleSidebar() {
        document
            .getElementById('sidebar')
            .classList
            .toggle('open');
    }

    const notificationButton =
        document.getElementById('notification-button');

    const notificationDropdown =
        document.getElementById('notification-dropdown');

    if (notificationButton && notificationDropdown) {

        notificationButton.addEventListener('click', function (event) {
            event.stopPropagation();

            const isOpen =
                notificationDropdown.classList.toggle('open');

            notificationButton.setAttribute(
                'aria-expanded',
                isOpen ? 'true' : 'false'
            );
        });

        document.addEventListener('click', function (event) {

            if (
                !notificationDropdown.contains(event.target) &&
                !notificationButton.contains(event.target)
            ) {
                notificationDropdown.classList.remove('open');

                notificationButton.setAttribute(
                    'aria-expanded',
                    'false'
                );
            }

        });

    }
</script>

@stack('scripts')

</body>
</html>