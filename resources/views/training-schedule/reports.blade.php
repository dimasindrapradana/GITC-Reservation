
@extends('layouts.admin')

@section('title', 'Training Schedule Reports')
@section('page_title', 'Training Schedule Reports')

@push('styles')
<style>
    .report-page{max-width:1500px;margin:0 auto}
    .report-header{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;margin-bottom:24px}
    .report-title{margin:0;color:var(--navy);font-size:24px;font-weight:750;letter-spacing:-.035em}
    .report-subtitle{margin:7px 0 0;color:var(--muted);font-size:13px;line-height:1.6}
    .report-button{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:40px;padding:10px 15px;border:1px solid var(--border);border-radius:8px;background:white;color:var(--navy);font-size:12px;font-weight:650;text-decoration:none;cursor:pointer;transition:background .15s,border-color .15s}
    .report-button:hover{background:#f5f9fc;border-color:#b9d0df}
    .report-button-primary{background:var(--navy);color:white;border-color:var(--navy)}
    .report-button-primary:hover{background:var(--navy-dark);border-color:var(--navy-dark);color:white}
    .report-card{background:white;border:1px solid var(--border);border-radius:12px;margin-bottom:20px;overflow:hidden}
    .report-card-header{padding:18px 22px;border-bottom:1px solid var(--border)}
    .report-card-heading{margin:0;color:var(--navy);font-size:14px;font-weight:750}
    .report-card-description{margin:5px 0 0;color:var(--muted);font-size:11px;line-height:1.6}
    .report-card-body{padding:22px}
    .report-grid{display:grid;grid-template-columns:minmax(220px,1.2fr) minmax(150px,.8fr) minmax(130px,.7fr);gap:16px;align-items:end}
    .report-field label{display:block;margin-bottom:7px;color:var(--text);font-size:12px;font-weight:650}
    .report-input,.report-select{width:100%;min-height:40px;padding:9px 11px;border:1px solid #d3e0e9;border-radius:7px;outline:none;background:white;color:var(--text);font-size:12px}
    .report-input:focus,.report-select:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(0,111,174,.09)}
    .report-alert{padding:13px 16px;margin-bottom:18px;border:1px solid transparent;border-radius:9px;font-size:12px;line-height:1.6}
    .report-alert-success{background:#edf9f1;border-color:#c6ead2;color:#17653e}
    .report-alert-error{background:#fff0ef;border-color:#f3cbc7;color:#a52b24}
    .report-alert-info{background:#eff8fc;border-color:#d1e9f4;color:#205c7b}
    .report-options{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}
    .report-option{display:flex;flex-direction:column;padding:22px;border:1px solid var(--border);border-radius:10px;background:#fff}
    .report-option-label{color:var(--muted);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.04em}
    .report-option-title{margin:9px 0 7px;color:var(--navy);font-size:18px;font-weight:750}
    .report-option-description{flex:1;margin:0 0 18px;color:var(--muted);font-size:12px;line-height:1.7}
    .report-option-fields{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px}
    .report-option-actions{display:flex;flex-wrap:wrap;gap:9px}
    .report-option-actions .report-button{width:100%}
    .report-footnote{margin-top:15px;color:var(--muted);font-size:11px;line-height:1.7}
    @media(max-width:760px){
        .report-grid{grid-template-columns:1fr}
        .report-options{grid-template-columns:1fr}
        .report-card-body{padding:16px}
        .report-card-header{padding:16px}
    }
    @media(max-width:480px){
        .report-title{font-size:21px}
        .report-option{padding:17px}
        .report-option-fields{grid-template-columns:1fr}
    }
</style>
@endpush

@section('content')
@php
    $userRole = auth()->user()->role->name;

    if ($userRole === 'Admin') {
        $reportRoute = 'admin.training-schedule.reports';
        $dailyRoute = 'admin.training-schedule.daily';
        $monthlyExportRoute = 'admin.training-schedule.monthly.export';
        $yearlyExportRoute = 'admin.training-schedule.yearly.export';
    } elseif ($userRole === 'Building Coordinator') {
        $reportRoute = 'building-coordinator.training-schedule.reports';
        $dailyRoute = 'building-coordinator.training-schedule.daily';
        $monthlyExportRoute = 'building-coordinator.training-schedule.monthly.export';
        $yearlyExportRoute = 'building-coordinator.training-schedule.yearly.export';
    } else {
        $reportRoute = 'coordinator.training-schedule.reports';
        $dailyRoute = 'coordinator.training-schedule.daily';
        $monthlyExportRoute = 'coordinator.training-schedule.monthly.export';
        $yearlyExportRoute = 'coordinator.training-schedule.yearly.export';
    }

    $currentMonth = (int) ($month ?? now()->month);
    $currentYear = (int) ($year ?? now()->year);
    $currentBuildingId = (int) ($selectedBuilding?->id ?? 0);
@endphp

<div class="report-page">
    <div class="report-header">
        <div>
            <h2 class="report-title">Training Schedule Reports</h2>
            <p class="report-subtitle">
                Export monthly and yearly training schedules to Excel.
            </p>
        </div>

        <a href="{{ route($dailyRoute) }}" class="report-button">
            <span aria-hidden="true">←</span>
            Back to Daily Schedule
        </a>
    </div>

    @if (session('success'))
        <div class="report-alert report-alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="report-alert report-alert-error">
            <strong>Please review the following:</strong>
            <ul style="margin:7px 0 0;padding-left:20px">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (!$selectedBuilding)
        <div class="report-alert report-alert-info">
            No building is available for your account. Please contact an administrator.
        </div>
    @else
        <section class="report-card">
            <div class="report-card-header">
                <h3 class="report-card-heading">Report Selection</h3>
                <p class="report-card-description">
                    Select the building and reporting period before exporting.
                </p>
            </div>

            <div class="report-card-body">
                <form method="GET" action="{{ route($reportRoute) }}">
                    <div class="report-grid">
                        <div class="report-field">
                            <label for="building_id">Building</label>
                            <select name="building_id" id="building_id" class="report-select" required>
                                @foreach ($buildings as $building)
                                    <option value="{{ $building->id }}"
                                        @selected($currentBuildingId === (int) $building->id)>
                                        {{ $building->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="report-field">
                            <label for="month">Month</label>
                            <select name="month" id="month" class="report-select" required>
                                @foreach (range(1, 12) as $monthNumber)
                                    <option value="{{ $monthNumber }}"
                                        @selected($currentMonth === $monthNumber)>
                                        {{ \Carbon\Carbon::create()->month($monthNumber)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="report-field">
                            <label for="year">Year</label>
                            <select name="year" id="year" class="report-select" required>
                                @foreach (range(max(2000, now()->year - 5), min(2100, now()->year + 5)) as $yearNumber)
                                    <option value="{{ $yearNumber }}"
                                        @selected($currentYear === $yearNumber)>
                                        {{ $yearNumber }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- <div style="display:flex;justify-content:flex-end;margin-top:16px">
                        <button type="submit" class="report-button report-button-primary">
                            Apply Selection
                        </button>
                    </div> --}}
                </form>
            </div>
        </section>

        <div class="report-options">
            <section class="report-option">
                <div class="report-option-label">Monthly report</div>
                <h3 class="report-option-title">Monthly Training Schedule</h3>
                <p class="report-option-description">
                    Export all saved training sessions for the selected building,
                    month, and year into a separate Excel file.
                </p>

                <form method="GET" action="{{ route($monthlyExportRoute) }}">
                    <input type="hidden" name="building_id" value="{{ $currentBuildingId }}">
                    <input type="hidden" name="month" value="{{ $currentMonth }}">
                    <input type="hidden" name="year" value="{{ $currentYear }}">

                    <div class="report-option-actions">
                        <button type="submit" class="report-button report-button-primary">
                            Export Monthly Excel
                        </button>
                    </div>
                </form>
            </section>

            <section class="report-option">
                <div class="report-option-label">Yearly report</div>
                <h3 class="report-option-title">Yearly Training Schedule</h3>
                <p class="report-option-description">
                    Export all saved training sessions for the selected building
                    throughout the entire year into a separate Excel file.
                </p>

                <form method="GET" action="{{ route($yearlyExportRoute) }}">
                    <input type="hidden" name="building_id" value="{{ $currentBuildingId }}">
                    <input type="hidden" name="year" value="{{ $currentYear }}">

                    <div class="report-option-actions">
                        <button type="submit" class="report-button report-button-primary">
                            Export Yearly Excel
                        </button>
                    </div>
                </form>
            </section>
        </div>

        <p class="report-footnote">
            Reports contain saved training sessions only. The monthly and yearly
            exports are downloaded as separate Excel files.
        </p>
    @endif
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.report-card form');

    if (!form) return;

    const filters = form.querySelectorAll(
        'select[name="building_id"], select[name="month"], select[name="year"]'
    );

    filters.forEach(function (filter) {
        filter.addEventListener('change', function () {
            form.requestSubmit();
        });
    });
});
</script>
@endpush        
