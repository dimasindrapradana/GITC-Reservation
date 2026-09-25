@extends('layouts.admin')

@section('title', 'Edit User')
@section('page_title', 'Edit User')

@section('content')

<div class="page-header">

    <div>
        <h2>Edit User</h2>

        <p class="page-description">
            Update the user's account and access information.
        </p>
    </div>

    <a
        href="{{ route('users.index') }}"
        class="btn btn-secondary"
    >
        Back to Users
    </a>

</div>

@if ($errors->any())

    <div class="alert alert-error">

        <strong>
            Please correct the following errors:
        </strong>

        <ul>
            @foreach ($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach
        </ul>

    </div>

@endif

<div class="content-card">

    <div class="card-header">

        <div>
            <h3>User Information</h3>

            <p>
                Update the user's account and access information.
            </p>
        </div>

    </div>

    <form
        action="{{ route('users.update', $user) }}"
        method="POST"
        class="form-content"
    >

        @csrf
        @method('PUT')

        <div class="form-grid">

            <div class="form-group">

                <label for="employee_number">
                    Employee Number
                    <span class="required">*</span>
                </label>

                <input
                    type="text"
                    id="employee_number"
                    name="employee_number"
                    value="{{ old('employee_number', $user->employee_number) }}"
                    class="form-control"
                    maxlength="50"
                    required
                >

                @error('employee_number')
                    <span class="field-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <div class="form-group">

                <label for="name">
                    Name
                    <span class="required">*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    class="form-control"
                    maxlength="150"
                    required
                >

                @error('name')
                    <span class="field-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <div class="form-group">

                <label for="username">
                    Username
                    <span class="required">*</span>
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    value="{{ old('username', $user->username) }}"
                    class="form-control"
                    maxlength="100"
                    required
                >

                @error('username')
                    <span class="field-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <div class="form-group">

                <label for="email">
                    Email
                    <span class="required">*</span>
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    class="form-control"
                    maxlength="150"
                    required
                >

                @error('email')
                    <span class="field-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <div class="form-group">

                <label for="role_id">
                    Role
                    <span class="required">*</span>
                </label>

                <select
                    id="role_id"
                    name="role_id"
                    class="form-control"
                    required
                >

                    <option value="">
                        Select Role
                    </option>

                    @foreach ($roles as $role)

                        <option
                            value="{{ $role->id }}"
                            data-role-name="{{ $role->name }}"
                            @selected(
                                (string) old(
                                    'role_id',
                                    $user->role_id
                                ) === (string) $role->id
                            )
                        >
                            {{ $role->name }}
                        </option>

                    @endforeach

                </select>

                @error('role_id')
                    <span class="field-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <div class="form-group">

                <label for="password">
                    New Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    minlength="8"
                >

                <span class="field-help">
                    Leave blank to keep the current password.
                </span>

                @error('password')
                    <span class="field-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <div class="form-group">

                <label for="password_confirmation">
                    Confirm New Password
                </label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control"
                    minlength="8"
                >

            </div>

        </div>

        <div
            id="building-section"
            class="building-section"
            style="display: none;"
        >

            <div class="section-heading">

                <h3>
                    Building Assignment
                </h3>

                <p>
                    Select the buildings assigned to this Building Coordinator.
                </p>

            </div>

            <div class="building-list">

                @foreach ($buildings as $building)

                    <label class="building-option">

                        <input
                            type="checkbox"
                            name="building_ids[]"
                            value="{{ $building->id }}"
                            @checked(
                                in_array(
                                    $building->id,
                                    old(
                                        'building_ids',
                                        $user->buildings->pluck('id')->all()
                                    )
                                )
                            )
                        >

                        <span>

                            <strong>
                                {{ $building->name }}
                            </strong>

                            @if ($building->code)

                                <small>
                                    {{ $building->code }}
                                </small>

                            @endif

                        </span>

                    </label>

                @endforeach

            </div>

            @error('building_ids')
                <span class="field-error">
                    {{ $message }}
                </span>
            @enderror

        </div>

        <div class="form-actions">

            <a
                href="{{ route('users.index') }}"
                class="btn btn-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Changes
            </button>

        </div>

    </form>

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

    .form-content {
        padding: 24px 20px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .form-group label {
        color: #12304a;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
    }

    .required {
        color: #b33a3a;
    }

    .form-control {
        width: 100%;
        min-height: 40px;
        padding: 9px 11px;
        border: 1px solid #d0dce5;
        border-radius: 7px;
        outline: none;
        background: #ffffff;
        color: #4f6680;
        font-family: inherit;
        font-size: 13px;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #006fae;
        box-shadow: 0 0 0 3px rgba(0, 111, 174, .08);
    }

    .field-help {
        color: #668096;
        font-size: 11px;
    }

    .field-error {
        color: #b33a3a;
        font-size: 11px;
        line-height: 1.4;
    }

    .building-section {
        margin-top: 26px;
        padding: 20px;
        border: 1px solid #d9e5ed;
        border-radius: 10px;
        background: #fbfcfd;
    }

    .section-heading {
        margin-bottom: 16px;
    }

    .section-heading h3 {
        margin: 0 0 5px;
        color: #12304a;
        font-size: 15px;
    }

    .section-heading p {
        margin: 0;
        color: #668096;
        font-size: 12px;
    }

    .building-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .building-option {
        display: flex;
        align-items: center;
        gap: 11px;
        min-height: 52px;
        padding: 10px 12px;
        border: 1px solid #d9e5ed;
        border-radius: 8px;
        background: #ffffff;
        cursor: pointer;
        box-sizing: border-box;
    }

    .building-option:hover {
        border-color: #c9dfe9;
        background: #f8fbfd;
    }

    .building-option input {
        width: 16px;
        height: 16px;
        margin: 0;
        accent-color: #006fae;
    }

    .building-option span {
        display: flex;
        flex-direction: column;
        gap: 3px;
        min-width: 0;
    }

    .building-option strong {
        color: #12304a;
        font-size: 12px;
    }

    .building-option small {
        color: #668096;
        font-size: 10px;
    }

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid #edf2f5;
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

    .alert-error {
        border: 1px solid #edc4c4;
        background: #fff7f7;
        color: #9b2c2c;
    }

    .alert ul {
        margin: 8px 0 0;
        padding-left: 18px;
    }

    .alert li {
        margin-bottom: 4px;
    }

    @media (max-width: 700px) {

        .form-grid {
            grid-template-columns: 1fr;
        }

        .building-list {
            grid-template-columns: 1fr;
        }

    }

    @media (max-width: 600px) {

        .page-header {
            flex-direction: column;
        }

        .page-header .btn {
            width: 100%;
        }

        .form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .form-actions .btn {
            width: 100%;
        }

    }

</style>

@endpush

@push('scripts')

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const roleSelect =
            document.getElementById('role_id');

        const buildingSection =
            document.getElementById('building-section');

        if (!roleSelect || !buildingSection) {
            return;
        }

        function toggleBuildingSection() {

            const selectedOption =
                roleSelect.options[
                    roleSelect.selectedIndex
                ];

            const roleName =
                selectedOption
                    ? selectedOption.dataset.roleName
                    : '';

            if (roleName === 'Building Coordinator') {
                buildingSection.style.display = 'block';
            } else {
                buildingSection.style.display = 'none';

                buildingSection
                    .querySelectorAll(
                        'input[name="building_ids[]"]'
                    )
                    .forEach(function (checkbox) {
                        checkbox.checked = false;
                    });
            }
        }

        roleSelect.addEventListener(
            'change',
            toggleBuildingSection
        );

        toggleBuildingSection();

    });

</script>

@endpush