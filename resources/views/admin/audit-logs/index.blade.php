@extends('layouts.admin')

@section('title', 'Audit Logs')
@section('page_title', 'Audit Logs')

@section('content')

    <div class="content-header">
        <h2>Audit Logs</h2>
        <p>Review system activities and recorded changes.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('audit-logs.index') }}"
                class="filter-form"
            >

                <div class="filter-grid">

                    <div class="field-group field-search">
                        <label for="search">
                            Search
                        </label>

                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search activity..."
                        >
                    </div>

                    <div class="field-group">
                        <label for="action">
                            Action
                        </label>

                        <select
                            id="action"
                            name="action"
                        >
                            <option value="">
                                All Actions
                            </option>

                            @foreach($actions as $item)
                                <option
                                    value="{{ $item }}"
                                    {{ $action === $item ? 'selected' : '' }}
                                >
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field-group">
                        <label for="user_id">
                            User
                        </label>

                        <select
                            id="user_id"
                            name="user_id"
                        >
                            <option value="">
                                All Users
                            </option>

                            @foreach($users as $user)
                                <option
                                    value="{{ $user->id }}"
                                    {{ $userId === (string) $user->id ? 'selected' : '' }}
                                >
                                    {{ $user->name }}
                                    @if($user->employee_number)
                                        — {{ $user->employee_number }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field-group">
                        <label for="date_from">
                            Date From
                        </label>

                        <input
                            type="date"
                            id="date_from"
                            name="date_from"
                            value="{{ $dateFrom }}"
                        >
                    </div>

                    <div class="field-group">
                        <label for="date_to">
                            Date To
                        </label>

                        <input
                            type="date"
                            id="date_to"
                            name="date_to"
                            value="{{ $dateTo }}"
                        >
                    </div>

                </div>

                <div class="filter-actions">

                    <button
                        type="submit"
                        class="button button-primary"
                    >
                        Apply Filter
                    </button>

                    <a
                        href="{{ route('audit-logs.index') }}"
                        class="button button-secondary"
                    >
                        Reset
                    </a>

                </div>

            </form>

        </div>

    </div>

    <div class="card table-card">

        <div class="table-wrapper">

            <table class="data-table">

                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th class="action-column">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($logs as $log)

                        <tr>

                            <td>
                                <div class="date-main">
                                    {{ $log->created_at?->format('d M Y') }}
                                </div>

                                <div class="date-sub">
                                    {{ $log->created_at?->format('H:i:s') }}
                                </div>
                            </td>

                            <td>

                                @if($log->user)

                                    <div class="user-main">
                                        {{ $log->user->name }}
                                    </div>

                                    @if($log->user->employee_number)
                                        <div class="user-sub">
                                            {{ $log->user->employee_number }}
                                        </div>
                                    @endif

                                @else

                                    <span class="muted-text">
                                        System
                                    </span>

                                @endif

                            </td>

                            <td>

                                @php
                                    $actionClass = match ($log->action) {
                                        'CREATE' => 'action-created',
                                        'UPDATE' => 'action-updated',
                                        'APPROVE' => 'action-approved',
                                        'REJECT' => 'action-rejected',
                                        'CANCEL' => 'action-cancelled',
                                        default => 'action-default',
                                    };
                                @endphp

                                <span class="action-badge {{ $actionClass }}">
                                    {{ $log->action }}
                                </span>

                            </td>

                            <td>

                                <span class="category-badge">
                                    {{ $log->module ?: '—' }}
                                </span>

                            </td>

                            <td>

                                <div class="description">
                                    {{ $log->description }}
                                </div>

                            </td>

                            <td class="action-column">

                                <a
                                    href="{{ route('audit-logs.show', $log) }}"
                                    class="button button-detail"
                                >
                                    Detail
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="empty-state"
                            >
                                No audit logs found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="pagination-wrapper">

            <div class="pagination-info">
                Showing
                {{ $logs->firstItem() ?? 0 }}
                to
                {{ $logs->lastItem() ?? 0 }}
                of
                {{ $logs->total() }}
                results
            </div>

            @if($logs->hasPages())

                <div class="pagination">

                    @foreach($logs->getUrlRange(
                        max(1, $logs->currentPage() - 2),
                        min($logs->lastPage(), $logs->currentPage() + 2)
                    ) as $page => $url)

                        <a
                            href="{{ $url }}"
                            class="page-button {{ $page == $logs->currentPage() ? 'active' : '' }}"
                        >
                            {{ $page }}
                        </a>

                    @endforeach

                </div>

            @endif

        </div>

    </div>

@endsection

@push('styles')
<style>

    .alert {
        margin-bottom: 20px;
        padding: 12px 15px;
        border-radius: 8px;
        font-size: 13px;
        border: 1px solid;
    }

    .alert-success {
        background: #edf8f2;
        border-color: #b8e1ca;
        color: #217346;
    }

    .alert-error {
        background: #fff2f2;
        border-color: #f0c4c4;
        color: #b42318;
    }

    .filter-form {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns:
            minmax(220px, 1.5fr)
            minmax(150px, 1fr)
            minmax(180px, 1fr)
            minmax(150px, 1fr)
            minmax(150px, 1fr);
        gap: 14px;
    }

    .field-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
        min-width: 0;
    }

    .field-group label {
        color: var(--text);
        font-size: 12px;
        font-weight: 600;
    }

    .field-group input,
    .field-group select {
        width: 100%;
        height: 40px;
        padding: 0 11px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: var(--white);
        color: var(--text);
        font-size: 13px;
        outline: none;
    }

    .field-group input:focus,
    .field-group select:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(0, 111, 174, 0.08);
    }

    .filter-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 0 14px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        border: 1px solid transparent;
        transition:
            background 0.15s ease,
            border-color 0.15s ease,
            color 0.15s ease;
    }

    .button-primary {
        background: #007fae;
        border-color: #007fae;
        color: #ffffff;
    }

    .button-primary:hover {
        background: #006f99;
        border-color: #006f99;
    }

    .button-secondary {
        background: #ffffff;
        border-color: var(--border);
        color: var(--text);
    }

    .button-secondary:hover {
        background: #f3f7fa;
    }

    .table-card {
        margin-top: 20px;
        overflow: hidden;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        min-width: 980px;
        border-collapse: collapse;
    }

    .data-table th {
        padding: 13px 16px;
        background: #f7fafc;
        border-bottom: 1px solid var(--border);
        color: var(--muted);
        font-size: 11px;
        font-weight: 700;
        text-align: left;
        white-space: nowrap;
    }

    .data-table td {
        padding: 15px 16px;
        border-bottom: 1px solid #edf2f6;
        color: var(--text);
        font-size: 12px;
        vertical-align: middle;
    }

    .data-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .data-table tbody tr:hover {
        background: #fbfdfe;
    }

    .date-main {
        font-weight: 600;
        color: var(--text);
        white-space: nowrap;
    }

    .date-sub {
        margin-top: 3px;
        color: var(--muted);
        font-size: 11px;
    }

    .user-main {
        font-weight: 600;
        white-space: nowrap;
    }

    .user-sub {
        margin-top: 3px;
        color: var(--muted);
        font-size: 11px;
    }

    .muted-text {
        color: var(--muted);
    }

    .action-badge,
    .category-badge {
        display: inline-flex;
        align-items: center;
        min-height: 26px;
        padding: 0 9px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .action-created {
        background: #edf7ff;
        color: #0369a1;
    }

    .action-updated {
        background: #fff7e6;
        color: #a16207;
    }

    .action-approved {
        background: #eaf7ef;
        color: #15803d;
    }

    .action-rejected {
        background: #fff1f1;
        color: #b42318;
    }

    .action-cancelled {
        background: #f0f2f4;
        color: #596773;
    }

    .action-default {
        background: #eef4f8;
        color: #36566d;
    }

    .category-badge {
        background: #eef6fa;
        color: #36566d;
    }

    .description {
        max-width: 360px;
        line-height: 1.5;
    }

    .action-column {
        width: 90px;
        text-align: right !important;
    }

    .button-detail {
        min-height: 34px;
        padding: 0 12px;
        background: #ffffff;
        border-color: var(--border);
        color: var(--navy);
    }

    .button-detail:hover {
        background: #f3f7fa;
        border-color: #c6d7e2;
    }

    .empty-state {
        padding: 40px 20px !important;
        color: var(--muted) !important;
        text-align: center !important;
    }

    .pagination-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 16px 18px;
        border-top: 1px solid var(--border);
    }

    .pagination-info {
        color: var(--muted);
        font-size: 12px;
    }

    .pagination {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .page-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 8px;
        border: 1px solid var(--border);
        border-radius: 6px;
        background: #ffffff;
        color: var(--text);
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
    }

    .page-button:hover {
        background: #f3f7fa;
    }

    .page-button.active {
        background: var(--blue);
        border-color: var(--blue);
        color: #ffffff;
    }

    @media (max-width: 1100px) {

        .filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .field-search {
            grid-column: 1 / -1;
        }

    }

    @media (max-width: 700px) {

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .field-search {
            grid-column: auto;
        }

        .pagination-wrapper {
            align-items: flex-start;
            flex-direction: column;
        }

        .pagination {
            width: 100%;
            justify-content: flex-end;
        }

    }

</style>
@endpush