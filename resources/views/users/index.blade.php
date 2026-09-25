@extends('layouts.admin')

@section('title', 'Users')
@section('page_title', 'Users')

@section('content')

<div class="page-header">

    <div>
        <h2>Users</h2>

        <p class="page-description">
            Manage user accounts and access roles.
        </p>
    </div>

    <a
        href="{{ route('users.create') }}"
        class="btn btn-primary"
    >
        Add User
    </a>

</div>

@if (session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif

@if (session('error'))

    <div class="alert alert-error">
        {{ session('error') }}
    </div>

@endif

<div class="content-card">

    <div class="card-header">

        <div>
            <h3>User List</h3>

            <p>
                All registered users.
            </p>
        </div>

    </div>

    <form
        action="{{ route('users.index') }}"
        method="GET"
        class="filter-form"
    >

        <div class="search-group">

            <label for="search">
                Search
            </label>

            <input
                type="text"
                id="search"
                name="search"
                value="{{ $search }}"
                class="filter-control search-control"
                placeholder="Search name, username, email..."
            >

        </div>

        <div class="filter-group">

            <label for="role">
                Role
            </label>

            <select
                id="role"
                name="role"
                class="filter-control"
            >

                <option
                    value=""
                    @selected($role === '')
                >
                    All Roles
                </option>

                @foreach ($roles as $item)

                    <option
                        value="{{ $item->name }}"
                        @selected($role === $item->name)
                    >
                        {{ $item->name }}
                    </option>

                @endforeach

            </select>

        </div>

        <div class="filter-actions">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Search
            </button>

            <a
                href="{{ route('users.index') }}"
                class="btn btn-secondary"
            >
                Reset
            </a>

        </div>

    </form>

    <div class="table-wrapper">

        <table class="data-table">

            <thead>

                <tr>

                    <th>No.</th>
                    <th>Employee Number</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Building</th>
                    <th>Actions</th>

                </tr>

            </thead>

            <tbody>

                @forelse ($users as $user)

                    <tr>

                        <td>
                            {{ $users->firstItem() + $loop->index }}
                        </td>

                        <td>
                            {{ $user->employee_number }}
                        </td>

                        <td>

                            <strong>
                                {{ $user->name }}
                            </strong>

                        </td>

                        <td>
                            {{ $user->username }}
                        </td>

                        <td>
                            {{ $user->email }}
                        </td>

                        <td>

                            <span class="role-badge">
                                {{ $user->role?->name ?? '-' }}
                            </span>

                        </td>

                        <td>

                            @if ($user->role?->name === 'Building Coordinator')

                                @if ($user->buildings->isNotEmpty())

                                    {{ $user->buildings->pluck('name')->join(', ') }}

                                @else

                                    <span class="muted">
                                        Not assigned
                                    </span>

                                @endif

                            @else

                                <span class="muted">
                                    —
                                </span>

                            @endif

                        </td>

                        <td>

                            <div class="action-group">

                                <a
                                    href="{{ route('users.edit', $user) }}"
                                    class="action-link edit"
                                >
                                    Edit
                                </a>

                                @if ($user->id !== auth()->id())

                                    <form
                                        action="{{ route('users.destroy', $user) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this user?');"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="action-link delete"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="empty-table"
                        >
                            No users available.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if ($users->hasPages())

        <div class="pagination-wrapper">

            <div class="pagination-info">

                Showing
                {{ $users->firstItem() }}
                to
                {{ $users->lastItem() }}
                of
                {{ $users->total() }}
                results

            </div>

            <div class="pagination-pages">

                @for (
                    $page = 1;
                    $page <= $users->lastPage();
                    $page++
                )

                    @if ($page === $users->currentPage())

                        <span class="pagination-page active">
                            {{ $page }}
                        </span>

                    @else

                        <a
                            href="{{ $users->url($page) }}"
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
        margin-bottom: 24px;
    }

    .page-header h2 {
        margin: 0 0 6px;
        color: #12304a;
        font-size: 24px;
    }

    .page-description {
        margin: 0;
        color: #668096;
        font-size: 14px;
    }

    .content-card {
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #d9e5ed;
        border-radius: 12px;
    }

    .card-header {
        padding: 20px;
        border-bottom: 1px solid #edf2f5;
    }

    .card-header h3 {
        margin: 0 0 5px;
        color: #12304a;
        font-size: 16px;
    }

    .card-header p {
        margin: 0;
        color: #668096;
        font-size: 13px;
    }

    .filter-form {
        display: flex;
        align-items: flex-end;
        gap: 14px;
        padding: 18px 20px;
        border-bottom: 1px solid #edf2f5;
        background: #fbfcfd;
    }

    .search-group,
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .search-group {
        flex: 1;
        min-width: 220px;
    }

    .filter-group {
        width: 200px;
    }

    .search-group label,
    .filter-group label {
        color: #12304a;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
    }

    .filter-control {
        width: 100%;
        min-height: 38px;
        padding: 8px 11px;
        border: 1px solid #d0dce5;
        border-radius: 7px;
        outline: none;
        background: #ffffff;
        color: #4f6680;
        font-family: inherit;
        font-size: 12px;
        box-sizing: border-box;
    }

    .filter-control:focus {
        border-color: #006fae;
        box-shadow: 0 0 0 3px rgba(0, 111, 174, .08);
    }

    .filter-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-actions .btn {
        min-height: 38px;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        padding: 13px 16px;
        background: #f8fafb;
        border-bottom: 1px solid #d9e5ed;
        color: #668096;
        font-size: 11px;
        font-weight: 700;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .03em;
        white-space: nowrap;
    }

    .data-table td {
        padding: 15px 16px;
        border-bottom: 1px solid #edf2f5;
        color: #4f6680;
        font-size: 13px;
        white-space: nowrap;
    }

    .data-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .data-table strong {
        color: #12304a;
        font-weight: 700;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 20px;
        background: #eaf5fb;
        color: #006fae;
        font-size: 10px;
        font-weight: 700;
    }

    .muted {
        color: #9aabb9;
    }

    .action-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .action-group form {
        margin: 0;
    }

    .action-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 30px;
        padding: 0 10px;
        border-radius: 6px;
        border: 1px solid transparent;
        background: transparent;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }

    .action-link.edit {
        border-color: #d0dce5;
        background: #ffffff;
        color: #4f6680;
    }

    .action-link.delete {
        border-color: #e2b8b8;
        background: #fff7f7;
        color: #b33a3a;
    }

    .action-link:hover {
        filter: brightness(.97);
    }

    .empty-table {
        padding: 50px 20px !important;
        color: #668096 !important;
        text-align: center;
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

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 17px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        box-sizing: border-box;
    }

    .btn-primary {
        border: 1px solid #006fae;
        background: #006fae;
        color: #ffffff;
    }

    .btn-primary:hover {
        border-color: #003b6f;
        background: #003b6f;
    }

    .btn-secondary {
        border: 1px solid #d0dce5;
        background: #ffffff;
        color: #4f6680;
    }

    .btn-secondary:hover {
        border-color: #c9dfe9;
        background: #f2f9fc;
        color: #006fae;
    }

    .alert {
        margin-bottom: 20px;
        padding: 14px 16px;
        border-radius: 8px;
        font-size: 13px;
    }

    .alert-success {
        border: 1px solid #b9dfc8;
        background: #f3fbf6;
        color: #267344;
    }

    .alert-error {
        border: 1px solid #edc4c4;
        background: #fff7f7;
        color: #9b2c2c;
    }

    @media (max-width: 1100px) {

        .filter-form {
            flex-wrap: wrap;
        }

        .search-group {
            flex: 1 1 100%;
        }

        .filter-group {
            flex: 1;
            width: auto;
        }

    }

    @media (max-width: 700px) {

        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }

        .search-group,
        .filter-group {
            width: 100%;
        }

        .filter-actions {
            width: 100%;
        }

        .filter-actions .btn {
            flex: 1;
        }

    }

    @media (max-width: 600px) {

        .page-header {
            flex-direction: column;
        }

        .page-header .btn {
            width: 100%;
        }

        .pagination-wrapper {
            flex-direction: column;
            justify-content: center;
            gap: 12px;
        }

        .pagination-pages {
            margin-left: 0;
        }

    }

</style>

@endpush