@extends('layouts.admin')

@section('title', 'Notifications')
@section('page_title', 'Notifications')

@section('content')

    <div class="page-header">
        <div>
            <h1>Notifications</h1>
            <p>
                View your latest system notifications and updates.
            </p>
        </div>

        @if ($notifications->whereNull('read_at')->count() > 0)
            <form
                method="POST"
                action="{{ route('notifications.read-all') }}"
            >
                @csrf

                <button
                    type="submit"
                    class="mark-all-button"
                >
                    Mark All as Read
                </button>
            </form>
        @endif
    </div>

    <div class="content-card">

        <div class="card-header">
            <div>
                <h2>Notification Center</h2>
                <p>
                    Review notifications related to your activities.
                </p>
            </div>
        </div>

        <div class="notification-list">

            @forelse ($notifications as $notification)

                <form
                    method="POST"
                    action="{{ route('notifications.read', $notification) }}"
                    class="notification-form"
                >
                    @csrf

                    <button
                        type="submit"
                        class="notification-item {{ $notification->read_at === null ? 'unread' : '' }}"
                    >

                        <div class="notification-indicator">
                            @if ($notification->read_at === null)
                                <span class="unread-dot"></span>
                            @else
                                <span class="read-dot"></span>
                            @endif
                        </div>

                        <div class="notification-content">

                            <div class="notification-top">
                                <span class="notification-title">
                                    {{ $notification->title }}
                                </span>

                                <span class="notification-date">
                                    {{ $notification->created_at->format('d M Y H:i') }}
                                </span>
                            </div>

                            <div class="notification-message">
                                {{ $notification->message }}
                            </div>

                            @if ($notification->read_at === null)
                                <span class="notification-status">
                                    Unread
                                </span>
                            @else
                                <span class="notification-status read">
                                    Read
                                </span>
                            @endif

                        </div>

                    </button>

                </form>

            @empty

                <div class="empty-state">
                    <div class="empty-icon">
                        ✓
                    </div>

                    <h3>No Notifications</h3>

                    <p>
                        You currently have no notifications to review.
                    </p>
                </div>

            @endforelse

        </div>

        @if ($notifications->hasPages())

            <div class="pagination-wrapper">

                <div class="pagination-info">
                    Showing
                    {{ $notifications->firstItem() }}
                    to
                    {{ $notifications->lastItem() }}
                    of
                    {{ $notifications->total() }}
                    results
                </div>

                <div class="pagination-pages">

                    @for (
                        $page = 1;
                        $page <= $notifications->lastPage();
                        $page++
                    )

                        @if ($page === $notifications->currentPage())

                            <span class="pagination-page active">
                                {{ $page }}
                            </span>

                        @else

                            <a
                                href="{{ $notifications->url($page) }}"
                                class="pagination-page"
                            >
                                {{ $page }}
                            </a>

                        @endif

                    @endfor

                </div>

            </div>

        @endif

    </div>

@endsection

@push('styles')
<style>

    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .page-header h1 {
        margin: 0 0 6px;
        color: #17324d;
        font-size: 24px;
        font-weight: 700;
    }

    .page-header p {
        margin: 0;
        color: #71869a;
        font-size: 13px;
    }

    .mark-all-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 36px;
        padding: 0 13px;
        border: 1px solid #d0dce5;
        border-radius: 7px;
        background: #ffffff;
        color: #4f6680;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
    }

    .mark-all-button:hover {
        background: #f5f8fa;
    }

    .content-card {
        overflow: hidden;
        border: 1px solid #e1e9ee;
        border-radius: 9px;
        background: #ffffff;
    }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 18px 20px;
        border-bottom: 1px solid #edf2f5;
    }

    .card-header h2 {
        margin: 0 0 4px;
        color: #29445d;
        font-size: 15px;
        font-weight: 700;
    }

    .card-header p {
        margin: 0;
        color: #7a8ea1;
        font-size: 11px;
    }

    .notification-list {
        width: 100%;
    }

    .notification-form {
        margin: 0;
        border-bottom: 1px solid #edf2f5;
    }

    .notification-form:last-child {
        border-bottom: none;
    }

    .notification-item {
        display: flex;
        align-items: flex-start;
        width: 100%;
        padding: 17px 20px;
        border: 0;
        background: #ffffff;
        color: inherit;
        font-family: inherit;
        text-align: left;
        cursor: pointer;
        box-sizing: border-box;
    }

    .notification-item:hover {
        background: #fbfdfe;
    }

    .notification-item.unread {
        background: #f8fcfe;
    }

    .notification-item.unread:hover {
        background: #f3f9fc;
    }

    .notification-indicator {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        min-width: 28px;
        padding-top: 4px;
    }

    .unread-dot,
    .read-dot {
        display: block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .unread-dot {
        background: #006fae;
    }

    .read-dot {
        background: #d5e0e7;
    }

    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .notification-title {
        color: #29445d;
        font-size: 13px;
        font-weight: 700;
    }

    .notification-date {
        color: #8a9aaa;
        font-size: 10px;
        white-space: nowrap;
    }

    .notification-message {
        margin-top: 5px;
        color: #61778b;
        font-size: 12px;
        line-height: 1.5;
    }

    .notification-status {
        display: inline-flex;
        margin-top: 8px;
        padding: 3px 7px;
        border-radius: 999px;
        background: #eaf5fb;
        color: #006fae;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
    }

    .notification-status.read {
        background: #edf1f4;
        color: #65798a;
    }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .empty-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        margin-bottom: 12px;
        border-radius: 50%;
        background: #edf7f1;
        color: #15803d;
        font-size: 18px;
        font-weight: 700;
    }

    .empty-state h3 {
        margin: 0 0 6px;
        color: #29445d;
        font-size: 14px;
        font-weight: 700;
    }

    .empty-state p {
        margin: 0;
        color: #8294a5;
        font-size: 11px;
    }

    .pagination-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 40px;
        padding: 16px 20px;
        border-top: 1px solid #edf2f5;
    }

    .pagination-info {
        color: #668096;
        font-size: 12px;
        white-space: nowrap;
    }

    .pagination-pages {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-left: auto;
    }

    .pagination-page {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border: 1px solid #d0dce5;
        border-radius: 6px;
        background: #ffffff;
        color: #4f6680;
        font-size: 12px;
        font-weight: 600;
        line-height: 1;
        text-decoration: none;
        box-sizing: border-box;
    }

    .pagination-page:hover {
        border-color: #c9dfe9;
        background: #f2f9fc;
        color: #006fae;
    }

    .pagination-page.active {
        border-color: #006fae;
        background: #006fae;
        color: #ffffff;
    }

    @media (max-width: 700px) {

        .page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .mark-all-button {
            width: 100%;
        }

        .notification-top {
            align-items: flex-start;
            flex-direction: column;
            gap: 4px;
        }

        .notification-date {
            white-space: normal;
        }

        .pagination-wrapper {
            align-items: flex-start;
            flex-direction: column;
            gap: 12px;
        }

        .pagination-pages {
            margin-left: 0;
        }

    }

</style>
@endpush