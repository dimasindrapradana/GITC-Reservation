
@extends('layouts.admin')

@push('styles')
<style>
    /* ========================================
       TRAINING SESSION SETTINGS
    ======================================== */

    .session-settings-page {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        color: #12304a;
    }

    .session-settings-page .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 28px;
    }

    .session-settings-page .page-title {
        margin: 0 0 7px;
        color: #003b6f;
        font-size: 1.65rem;
        font-weight: 700;
        line-height: 1.3;
    }

    .session-settings-page .page-description {
        margin: 0;
        color: #668096;
        font-size: 0.94rem;
        line-height: 1.6;
    }

    /* Panels */

    .session-settings-page .settings-panel {
        margin-bottom: 24px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #d9e5ed;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(0, 41, 79, 0.035);
    }

    .session-settings-page .settings-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        padding: 20px 24px;
        background: #fff;
        border-bottom: 1px solid #e5edf3;
    }

    .session-settings-page .settings-panel-body {
        padding: 24px;
    }

    .session-settings-page .panel-title {
        margin: 0 0 5px;
        color: #003b6f;
        font-size: 1.08rem;
        font-weight: 700;
    }

    .session-settings-page .panel-description {
        margin: 0;
        color: #668096;
        font-size: 0.88rem;
        line-height: 1.5;
    }

    /* Building selector */

    .session-settings-page .field-label {
        display: block;
        margin-bottom: 9px;
        color: #12304a;
        font-size: 0.9rem;
        font-weight: 700;
    }

    .session-settings-page .building-select {
        width: 100%;
        max-width: 440px;
    }

    .session-settings-page .form-control,
    .session-settings-page .form-select {
        min-height: 42px;
        border: 1px solid #cfdee8;
        border-radius: 8px;
        color: #12304a;
        font-size: 0.92rem;
    }

    .session-settings-page .form-control:focus,
    .session-settings-page .form-select:focus {
        border-color: #00a8c8;
        box-shadow: 0 0 0 3px rgba(0, 168, 200, 0.12);
    }

   
    /* Table */

    .session-settings-page .table-responsive {
        width: 100%;
    }

    .session-settings-page .session-table {
        width: 100%;
        margin: 0;
        table-layout: fixed;
        border-collapse: separate;
        border-spacing: 0;
    }

    .session-settings-page .session-table th,
    .session-settings-page .session-table td {
        padding: 14px 20px;
        vertical-align: middle;
    }

    .session-settings-page .session-table thead th {
        background: #f3f7fa;
        color: #49657b;
        border-top: 1px solid #e5edf3;
        border-bottom: 1px solid #d9e5ed;
        font-size: 0.77rem;
        font-weight: 700;
        letter-spacing: 0.045em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .session-settings-page .session-table tbody td {
        background: #fff;
        border-bottom: 1px solid #e8eff4;
    }

    .session-settings-page .session-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .session-settings-page .session-table tbody tr:hover td {
        background: #fbfdff;
    }

    /* Column widths */
    .session-settings-page .session-table th:nth-child(1),
    .session-settings-page .session-table td:nth-child(1) {
        width: 20%;
    }

    .session-settings-page .session-table th:nth-child(2),
    .session-settings-page .session-table td:nth-child(2) {
        width: 30%;
    }

    .session-settings-page .session-table th:nth-child(3),
    .session-settings-page .session-table td:nth-child(3) {
        width: 30%;
    }

    .session-settings-page .session-table th:nth-child(4),
    .session-settings-page .session-table td:nth-child(4) {
        width: 20%;
    }

    /* Input sizes */
    .session-settings-page .session-number {
        width: 100%;
        max-width: 120px;
    }

    .session-settings-page .session-time {
        width: 100%;
        max-width: 180px;
    }

    /* Action column */
    .session-settings-page .session-table .action-cell {
        text-align: center;
    }



    /* Buttons */

    .session-settings-page .btn {
        min-height: 38px;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 0.86rem;
        font-weight: 600;
        transition:
            background-color 0.15s ease,
            border-color 0.15s ease,
            color 0.15s ease;
    }

    .session-settings-page .btn-add-session {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        color: #006fae;
        background: #fff;
        border: 1px solid #b9d6e8;
        white-space: nowrap;
    }

    .session-settings-page .btn-add-session:hover {
        color: #003b6f;
        background: #eef8fc;
        border-color: #006fae;
    }

    .session-settings-page .btn-remove-session {
        min-width: 76px;
        min-height: 34px;
        padding: 6px 10px;
        color: #b42332;
        background: #fff;
        border: 1px solid #e4bfc4;
        font-size: 0.82rem;
    }

    .session-settings-page .btn-remove-session:hover {
        color: #fff;
        background: #b42332;
        border-color: #b42332;
    }

    .session-settings-page .btn-save-settings {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 10px 20px;
        color: #fff;
        background: #003b6f;
        border: 1px solid #003b6f;
    }

    .session-settings-page .btn-save-settings:hover {
        color: #fff;
        background: #00294f;
        border-color: #00294f;
    }

    /* Form footer */

    .session-settings-page .settings-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        padding-top: 22px;
        margin-top: 20px;
        border-top: 1px solid #e5edf3;
    }

    /* Empty selection message */

    .session-settings-page .empty-state {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 18px 20px;
        margin: 0;
        color: #49657b;
        background: #f3f8fc;
        border: 1px solid #d9e5ed;
        border-radius: 10px;
        font-size: 0.92rem;
        line-height: 1.6;
    }

    .session-settings-page .empty-state strong {
        display: block;
        margin-bottom: 3px;
        color: #003b6f;
    }

    /* Feedback */

    .session-settings-page .alert {
        margin-bottom: 20px;
        border-radius: 9px;
        font-size: 0.92rem;
    }

    .session-settings-page .alert ul {
        padding-left: 20px;
    }

    /* Responsive */

    @media (max-width: 768px) {
        .session-settings-page {
            max-width: 100%;
        }

        .session-settings-page .page-header {
            margin-bottom: 20px;
        }

        .session-settings-page .page-title {
            font-size: 1.4rem;
        }

        .session-settings-page .settings-panel-header,
        .session-settings-page .settings-panel-body {
            padding: 17px;
        }

        .session-settings-page .session-table {
            min-width: 650px;
        }

        .session-settings-page .settings-actions {
            justify-content: stretch;
        }

        .session-settings-page .btn-save-settings {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-4 session-settings-page">

    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Training Session Settings</h1>
            <p class="page-description">
                Configure session numbers and time ranges for each building.
            </p>
        </div>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Please check the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Building Selector --}}
    <section class="settings-panel">
        <div class="settings-panel-header">
            <div>
                <h2 class="panel-title">Building Configuration</h2>
                <p class="panel-description">
                    Select a building to manage its training sessions.
                </p>
            </div>
        </div>

        <div class="settings-panel-body">
            <form
                method="GET"
                action="{{ route('training-session-settings.index') }}"
                id="building-selector-form"
            >
                <label for="building_id" class="field-label">
                    Select Building
                </label>

                <select
                    name="building_id"
                    id="building_id"
                    class="form-select building-select"
                    required
                >
                    <option value="">Choose a building</option>

                    @foreach ($buildings as $building)
                        <option
                            value="{{ $building->id }}"
                            @selected((string) $selectedBuildingId === (string) $building->id)
                        >
                            {{ $building->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </section>

    {{-- Selected Building Sessions --}}
    @if ($selectedBuilding)
        <section class="settings-panel">

            <div class="settings-panel-header">
                <div>
                    <h2 class="panel-title">
                        {{ $selectedBuilding->name }}
                    </h2>
                    <p class="panel-description">
                        Configure the session number, start time, and end time.
                    </p>
                </div>

                <button
                    type="button"
                    class="btn btn-add-session"
                    id="add-session"
                >
                    <span aria-hidden="true">+</span>
                    Add Session
                </button>
            </div>

            <div class="settings-panel-body">
                <form
                    method="POST"
                    action="{{ route('training-session-settings.store') }}"
                    id="session-settings-form"
                >
                    @csrf

                    <input
                        type="hidden"
                        name="building_id"
                        value="{{ $selectedBuilding->id }}"
                    >

                   
            <div class="table-responsive">
                <table class="table session-table">
                    <thead>
                        <tr>
                            <th style="width: 20%">Session No.</th>
                            <th style="width: 30%">Start Time</th>
                            <th style="width: 30%">End Time</th>
                            <th class="action-cell" style="width: 20%">Action</th>
                        </tr>
                    </thead>

                    <tbody id="session-rows">
                        @forelse ($templates as $index => $template)
                            <tr class="session-row">
                                <td>
                                    <input
                                        type="number"
                                        name="sessions[{{ $index }}][session_number]"
                                        class="form-control session-number"
                                        min="1"
                                        value="{{ $template->session_number }}"
                                        required
                                    >
                                </td>

                                <td>
                                    <input
                                        type="time"
                                        name="sessions[{{ $index }}][start_time]"
                                        class="form-control session-time"
                                        value="{{ substr($template->start_time, 0, 5) }}"
                                        required
                                    >
                                </td>

                                <td>
                                    <input
                                        type="time"
                                        name="sessions[{{ $index }}][end_time]"
                                        class="form-control session-time"
                                        value="{{ substr($template->end_time, 0, 5) }}"
                                        required
                                    >
                                </td>

                                <td class="action-cell">
                                    <button
                                        type="button"
                                        class="btn btn-remove-session remove-session"
                                    >
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr class="session-row">
                                <td>
                                    <input
                                        type="number"
                                        name="sessions[0][session_number]"
                                        class="form-control session-number"
                                        min="1"
                                        value="1"
                                        required
                                    >
                                </td>

                                <td>
                                    <input
                                        type="time"
                                        name="sessions[0][start_time]"
                                        class="form-control session-time"
                                        required
                                    >
                                </td>

                                <td>
                                    <input
                                        type="time"
                                        name="sessions[0][end_time]"
                                        class="form-control session-time"
                                        required
                                    >
                                </td>

                                <td class="action-cell">
                                    <button
                                        type="button"
                                        class="btn btn-remove-session remove-session"
                                    >
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


                    <div class="settings-actions">
                        <button type="submit" class="btn btn-save-settings">
                            Save Session Settings
                        </button>
                    </div>
                </form>
            </div>
        </section>
    @else
        <div class="empty-state">
            <div>
                <strong>Select a building to get started</strong>
                Choose a building above to view or configure its training session times.
            </div>
        </div>
    @endif

</div>

@if ($selectedBuilding)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rows = document.getElementById('session-rows');
    const addButton = document.getElementById('add-session');

    if (!rows || !addButton) {
        return;
    }

    let nextIndex = rows.querySelectorAll('.session-row').length;

    function renumberInputs() {
        rows.querySelectorAll('.session-row').forEach(function (row, index) {
            row.querySelectorAll('input').forEach(function (input) {
                input.name = input.name.replace(
                    /sessions\[\d+\]/,
                    'sessions[' + index + ']'
                );
            });
        });

        nextIndex = rows.querySelectorAll('.session-row').length;
    }

    addButton.addEventListener('click', function () {
        const row = document.createElement('tr');
        row.className = 'session-row';

        row.innerHTML = `
            <td>
                <input type="number"
                       name="sessions[${nextIndex}][session_number]"
                       class="form-control session-number"
                       min="1"
                       value="${nextIndex + 1}"
                       required>
            </td>
            <td>
                <input type="time"
                       name="sessions[${nextIndex}][start_time]"
                       class="form-control session-time"
                       required>
            </td>
            <td>
                <input type="time"
                       name="sessions[${nextIndex}][end_time]"
                       class="form-control session-time"
                       required>
            </td>
            <td class="action-cell">
                <button type="button"
                        class="btn btn-remove-session remove-session">
                    Remove
                </button>
            </td>
        `;

        rows.appendChild(row);
        renumberInputs();
    });

    rows.addEventListener('click', function (event) {
        const button = event.target.closest('.remove-session');

        if (!button) {
            return;
        }

        const currentRows = rows.querySelectorAll('.session-row');

        if (currentRows.length <= 1) {
            alert('At least one session is required.');
            return;
        }

        button.closest('.session-row').remove();
        renumberInputs();
    });
});
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const buildingSelect = document.getElementById('building_id');
    const buildingForm = document.getElementById('building-selector-form');

    if (buildingSelect && buildingForm) {
        buildingSelect.addEventListener('change', function () {
            if (this.value !== '') {
                buildingForm.submit();
            }
        });
    }
});
</script>
@endsection
