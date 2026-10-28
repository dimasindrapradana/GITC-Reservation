
@extends('layouts.admin')

@section('title', 'Daily Training Schedule')
@section('page_title', 'Daily Training Schedule')

@push('styles')
<style>
    .daily-page{max-width:1500px;margin:0 auto}
    .daily-header{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;margin-bottom:24px}
    .daily-title{margin:0;color:var(--navy);font-size:24px;font-weight:750;letter-spacing:-.035em}
    .daily-subtitle{margin:7px 0 0;color:var(--muted);font-size:13px;line-height:1.6}
    .daily-button{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:40px;padding:10px 15px;border:1px solid var(--border);border-radius:8px;background:white;color:var(--navy);font-size:12px;font-weight:650;cursor:pointer;transition:background .15s,border-color .15s}
    .daily-button:hover{background:#f5f9fc;border-color:#b9d0df}
    .daily-button-primary{background:var(--navy);color:white;border-color:var(--navy)}
    .daily-button-primary:hover{background:var(--navy-dark);border-color:var(--navy-dark);color:white}
    .daily-button:disabled{opacity:.5;cursor:not-allowed}
    .daily-card{background:white;border:1px solid var(--border);border-radius:12px;margin-bottom:20px;overflow:visible}
    .daily-card-header{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;padding:18px 22px;border-bottom:1px solid var(--border)}
    .daily-card-heading{margin:0;color:var(--navy);font-size:14px;font-weight:750}
    .daily-card-description{margin:5px 0 0;color:var(--muted);font-size:11px;line-height:1.5}
    .daily-card-body{padding:22px}
    .daily-filter-grid{display:grid;grid-template-columns:minmax(200px,1.2fr) minmax(180px,.8fr) auto;gap:16px;align-items:end}
    .daily-field label{display:block;margin-bottom:7px;color:var(--text);font-size:12px;font-weight:650}
    .daily-input,.daily-select,.daily-textarea{width:100%;min-height:40px;padding:9px 11px;border:1px solid #d3e0e9;border-radius:7px;outline:none;background:white;color:var(--text);font-size:12px;transition:border-color .15s,box-shadow .15s}
    .daily-input:focus,.daily-select:focus,.daily-textarea:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(0,111,174,.09)}
    .daily-textarea{min-height:65px;resize:vertical}
    .daily-status{display:inline-flex;align-items:center;gap:7px;padding:6px 10px;border-radius:999px;font-size:10px;font-weight:700;white-space:nowrap}
    .daily-status-saved{color:#16734b;background:#e9f8ef}
    .daily-status-empty{color:#6c7f8f;background:#eef3f6}
    .daily-status-dot{width:6px;height:6px;border-radius:50%;background:currentColor}
    .daily-alert{padding:13px 16px;margin-bottom:18px;border:1px solid transparent;border-radius:9px;font-size:12px;line-height:1.6}
    .daily-alert-success{background:#edf9f1;border-color:#c6ead2;color:#17653e}
    .daily-alert-error{background:#fff0ef;border-color:#f3cbc7;color:#a52b24}
    .daily-alert-warning{background:#fff8e8;border-color:#f0dfb3;color:#805d12}
    .daily-alert-info{background:#eff8fc;border-color:#d1e9f4;color:#205c7b}
    .daily-alert ul{margin:7px 0 0;padding-left:20px}
    .daily-table-wrap{width:100%;overflow-x:auto}
    .daily-table{width:100%;min-width:1100px;border-collapse:separate;border-spacing:0}
    .daily-table th{padding:13px 14px;border-bottom:1px solid var(--border);background:#f7fafc;color:#627c91;font-size:10px;font-weight:750;text-align:left;letter-spacing:.035em;text-transform:uppercase;white-space:nowrap}
    .daily-table td{padding:15px 14px;border-bottom:1px solid #edf2f5;vertical-align:top}
    .daily-table tbody tr:last-child td{border-bottom:0}
    .daily-table tbody tr:hover{background:#fbfdfe}
    .daily-session-number{color:var(--navy);font-size:12px;font-weight:750;white-space:nowrap}
    .daily-session-time{display:inline-block;padding:7px 9px;border:1px solid #dce9f1;border-radius:7px;background:#f4f9fc;color:var(--navy);font-size:11px;font-weight:700;white-space:nowrap}
    .daily-session-state{display:block;margin-top:7px;color:var(--muted);font-size:10px}
    .daily-empty{padding:34px 20px;text-align:center;color:var(--muted);font-size:12px;line-height:1.7}
    .daily-empty strong{display:block;margin-bottom:5px;color:var(--navy);font-size:14px}
    .daily-footer{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:14px;padding:17px 22px;border-top:1px solid var(--border);background:#fbfdfe}
    .daily-footer-note{max-width:580px;color:var(--muted);font-size:11px;line-height:1.6}
    .daily-footer-actions{display:flex;flex-wrap:wrap;gap:9px}
    .daily-summary{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-bottom:20px}
    .daily-summary-item{padding:17px 19px;background:white;border:1px solid var(--border);border-radius:10px}
    .daily-summary-label{color:var(--muted);font-size:11px}
    .daily-summary-value{margin-top:7px;color:var(--navy);font-size:22px;font-weight:750;letter-spacing:-.03em}
    .daily-summary-caption{margin-top:4px;color:#8297a7;font-size:10px}

    /* Optional session checkbox */
    .daily-session-toggle{display:flex;align-items:center;gap:7px;margin-top:11px;color:var(--navy);font-size:11px;font-weight:650;cursor:pointer;white-space:nowrap}
    .daily-session-toggle input{width:15px;height:15px;margin:0;accent-color:var(--blue);cursor:pointer}
    .daily-session-row.is-disabled{background:#f8fafb}
    .daily-session-row.is-disabled td{color:#9aabb7}
    .daily-session-row.is-disabled .daily-session-number,
    .daily-session-row.is-disabled .daily-session-time{opacity:.6}
    .daily-session-row.is-disabled .daily-session-state{color:#8799a7}
    .daily-session-row.is-disabled .daily-input,
    .daily-session-row.is-disabled .daily-textarea,
    .daily-session-row.is-disabled .daily-media-search{background:#f0f3f5;color:#8998a3;cursor:not-allowed}
    .daily-session-row.is-disabled .daily-media-picker{opacity:.7}
    .daily-session-row.is-disabled .daily-session-toggle{color:#738795}
    .daily-session-row.is-disabled .daily-media-results{display:none!important}

    /* Searchable Training Media */
    .daily-media-picker{position:relative;min-width:175px}
    .daily-media-search{min-height:40px;font-size:12px}
    .daily-media-results{position:absolute;top:calc(100% + 4px);left:0;right:0;z-index:1000;max-height:220px;overflow-y:auto;background:#fff;border:1px solid #d3e0e9;border-radius:8px;box-shadow:0 8px 20px rgba(20,45,65,.12)}
    .daily-media-results[hidden],.daily-media-option[hidden],.daily-media-search-message[hidden],.daily-media-selected[hidden]{display:none!important}
    .daily-media-option{display:flex;flex-direction:column;align-items:flex-start;width:100%;padding:10px 12px;border:0;border-bottom:1px solid #edf2f5;background:#fff;color:var(--text);text-align:left;font-size:12px;cursor:pointer}
    .daily-media-option:last-child{border-bottom:0}
    .daily-media-option:hover,.daily-media-option:focus{background:#eff8fc;outline:none}
    .daily-media-option small{margin-top:3px;color:var(--muted);font-size:10px}
    .daily-media-search-message,.daily-media-selected{display:block;margin-top:6px;font-size:10px;line-height:1.5}
    .daily-media-search-message{color:#a52b24}
    .daily-media-selected{color:#17653e;overflow-wrap:anywhere}

    @media(max-width:900px){
        .daily-filter-grid{grid-template-columns:1fr 1fr}
        .daily-filter-grid .daily-filter-action{grid-column:1/-1}
        .daily-filter-action .daily-button{width:100%}
        .daily-summary{grid-template-columns:1fr}
        .daily-card-body{padding:16px}
    }
    @media(max-width:540px){
        .daily-title{font-size:21px}
        .daily-filter-grid{grid-template-columns:1fr}
        .daily-filter-grid .daily-filter-action{grid-column:auto}
        .daily-card-header,.daily-footer{padding:15px}
        .daily-footer-actions{width:100%}
        .daily-footer-actions .daily-button{flex:1}
    }
</style>
@endpush

@section('content')

@php
    $userRole = auth()->user()->role->name;

    if ($userRole === 'Admin') {
        $dailyRoute = 'admin.training-schedule.daily';
        $dailyStoreRoute = 'admin.training-schedule.daily.store';
        $legacyRoute = 'admin.dashboard';
    } elseif ($userRole === 'Building Coordinator') {
        $dailyRoute = 'building-coordinator.training-schedule.daily';
        $dailyStoreRoute = 'building-coordinator.training-schedule.daily.store';
        $legacyRoute = 'building-coordinator.dashboard';
    } else {
        $dailyRoute = 'coordinator.training-schedule.daily';
        $dailyStoreRoute = 'coordinator.training-schedule.daily.store';
        $legacyRoute = 'coordinator.dashboard';
    }

    $savedSessionCount = $schedule ? $schedule->sessions->count() : 0;


    $previousSessions = $previousSchedule
        ? $previousSchedule->sessions
            ->filter(fn ($session) => trim((string) $session->subject) !== '')
            ->values()
            ->map(fn ($session) => [
                'start_time' => substr((string) $session->start_time, 0, 8),
                'end_time' => substr((string) $session->end_time, 0, 8),
                'subject' => $session->subject,
                'training_room_id' => $session->training_room_id,
                'class_name' => $session->class_name,
                'instructor_name' => $session->instructor_name,
                'participant_count' => $session->participant_count,
                'notes' => $session->notes,
            ])
            ->all()
        : [];

    $oldSessions = old('sessions', []);
@endphp

<div class="daily-page">
    <div class="daily-header">
        <div>
            <h2 class="daily-title">Daily Training Schedule</h2>
            <p class="daily-subtitle">Plan daily events and assign Training Media by building and session time.</p>
        </div>

        <a href="{{ route($legacyRoute) }}" class="daily-button">
            <span aria-hidden="true">←</span>
            Back to Schedule Management
        </a>
    </div>

    @if (session('success'))
        <div class="daily-alert daily-alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="daily-alert daily-alert-error">
            <strong>Please review the following:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="daily-card">
        <div class="daily-card-header">
            <div>
                <h3 class="daily-card-heading">Schedule Selection</h3>
                <p class="daily-card-description">Choose a building and date to load its session templates.</p>
            </div>
        </div>

        <div class="daily-card-body">
            <form method="GET" action="{{ route($dailyRoute) }}">
                <div class="daily-filter-grid">
                    <div class="daily-field">
                        <label for="building_id">Building</label>
                        <select name="building_id" id="building_id" class="daily-select" required>
                            @forelse ($buildings as $building)
                                <option value="{{ $building->id }}"
                                    @selected((int) ($selectedBuilding?->id ?? 0) === (int) $building->id)>
                                    {{ $building->name }}
                                </option>
                            @empty
                                <option value="">No buildings available</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="daily-field">
                        <label for="date">Schedule Date</label>
                        <input type="date" name="date" id="date" class="daily-input" value="{{ $date }}" required>
                    </div>

                    
                    <div class="daily-filter-action" style="display:flex;gap:9px;flex-wrap:wrap">
                        <button type="submit" class="daily-button daily-button-primary">
                            Load Schedule
                        </button>

                          @if ($selectedBuilding)
                            <a href="{{ route(
                                auth()->user()->role->name === 'Admin'
                                    ? 'admin.training-schedule.reports'
                                    : (auth()->user()->role->name === 'Building Coordinator'
                                        ? 'building-coordinator.training-schedule.reports'
                                        : 'coordinator.training-schedule.reports')
                            ) }}"
                            class="daily-button"
                            style="color:#16734b;border-color:#b8e5c9;background:#edf9f1">
                                Monthly &amp; Yearly Reports
                            </a>
                        @endif
                    </div>

                </div>
            </form>
        </div>
    </section>

    @if (!$selectedBuilding)
        <div class="daily-alert daily-alert-info">No building is available for your account.</div>
    @elseif ($templates->isEmpty())
        <div class="daily-alert daily-alert-warning">
            No session time templates have been configured for
            <strong>{{ $selectedBuilding->name }}</strong>.
            Ask an administrator to configure the session times first.
        </div>
    @else
        <div class="daily-summary">
            <div class="daily-summary-item">
                <div class="daily-summary-label">Building</div>
                <div class="daily-summary-value" style="font-size:17px">{{ $selectedBuilding->name }}</div>
                <div class="daily-summary-caption">Selected location</div>
            </div>

            <div class="daily-summary-item">
                <div class="daily-summary-label">Session Templates</div>
                <div class="daily-summary-value">{{ $templates->count() }}</div>
                <div class="daily-summary-caption">Configured time slots</div>
            </div>

            <div class="daily-summary-item">
                <div class="daily-summary-label">Saved Sessions</div>
                <div class="daily-summary-value">{{ $savedSessionCount }}</div>
                <div class="daily-summary-caption">Events currently saved for this date</div>
            </div>
        </div>

        <section class="daily-card">
            <div class="daily-card-header">
                <div>
                    <h3 class="daily-card-heading">{{ $selectedBuilding->name }}</h3>
                    <p class="daily-card-description">
                        Schedule for {{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}
                    </p>
                </div>

                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                    @if ($previousSchedule)
                        <button type="button" class="daily-button" id="copyPreviousDayButton">
                            Copy Previous Day
                        </button>
                    @else
                        <button type="button" class="daily-button" disabled
                                title="No schedule exists for the previous day.">
                            Copy Previous Day
                        </button>
                    @endif

                    @if ($schedule)
                        <span class="daily-status daily-status-saved">
                            <span class="daily-status-dot"></span>Schedule saved
                        </span>
                    @else
                        <span class="daily-status daily-status-empty">
                            <span class="daily-status-dot"></span>Not saved yet
                        </span>
                    @endif
                </div>
            </div>

            <form method="POST" action="{{ route($dailyStoreRoute) }}" id="dailyScheduleForm">
                @csrf
                <input type="hidden" name="building_id" value="{{ $selectedBuilding->id }}">
                <input type="hidden" name="schedule_date" value="{{ $date }}">

                <div class="daily-table-wrap">
                    <table class="daily-table">
                        <thead>
                            <tr>
                                <th>Session</th>
                                <th>Time</th>
                                <th>Event Name</th>
                                <th>Training Media</th>
                                <th>Group</th>
                                <th>Instructor</th>
                                <th>Participants</th>
                                <th>Notes</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($templates as $template)
                                @php
                                    $timeKey = substr($template->start_time, 0, 8)
                                        . '|'
                                        . substr($template->end_time, 0, 8);

                                    $session = $sessionsByTime->get($timeKey);
                                    $index = $loop->index;
                                    $oldPrefix = 'sessions.' . $index;

                                    $selectedRoomId = old(
                                        $oldPrefix . '.training_room_id',
                                        $session?->training_room_id
                                    );

                                    /*
                                     * Default:
                                     * - Existing saved session = checked.
                                     * - New schedule with no validation redirect = all checked.
                                     * - After validation failure, honor the submitted checkbox.
                                     */
                                    $isChecked = array_key_exists('sessions', session()->getOldInput())
                                        ? array_key_exists((string) $index, $oldSessions)
                                            || array_key_exists($index, $oldSessions)
                                        : (!$schedule || $session !== null);
                                @endphp

                                <tr class="daily-session-row {{ $isChecked ? '' : 'is-disabled' }}"
                                    data-session-row>
                                    <td>
                                        <div class="daily-session-number">
                                            Session {{ $template->session_number }}
                                        </div>

                                        <label class="daily-session-toggle">
                                            <input type="checkbox"
                                                   class="daily-session-checkbox"
                                                   value="1"
                                                   {{ $isChecked ? 'checked' : '' }}
                                                   aria-label="Use session {{ $template->session_number }}">
                                            <span>Use session</span>
                                        </label>

                                        <span class="daily-session-state">
                                            {{ $session ? 'Event saved' : ($isChecked ? 'Enabled slot' : 'Session disabled') }}
                                        </span>

                                        <input type="hidden"
                                               class="daily-session-number-input"
                                               name="sessions[{{ $index }}][session_number]"
                                               value="{{ $template->session_number }}">
                                    </td>

                                    <td>
                                        <span class="daily-session-time">
                                            {{ substr($template->start_time, 0, 5) }} – {{ substr($template->end_time, 0, 5) }}
                                        </span>
                                    </td>

                                    <td>
                                        <input type="text" class="daily-input"
                                               name="sessions[{{ $index }}][subject]"
                                               maxlength="200"
                                               value="{{ old($oldPrefix . '.subject', $session?->subject) }}"
                                               placeholder="Enter event name">
                                    </td>

                                    <td>
                                        <div class="daily-field daily-media-picker">
                                            <input type="search"
                                                   class="daily-input daily-media-search"
                                                   placeholder="Search Training Media..."
                                                   aria-label="Search Training Media for session {{ $template->session_number }}"
                                                   autocomplete="off">

                                            <input type="hidden"
                                                   class="daily-media-value"
                                                   name="sessions[{{ $index }}][training_room_id]"
                                                   value="{{ $selectedRoomId }}">

                                            <div class="daily-media-results" hidden>
                                                @foreach ($trainingRooms as $room)
                                                    <button type="button"
                                                            class="daily-media-option"
                                                            data-value="{{ $room->id }}"
                                                            data-search="{{ strtolower($room->name) }}"
                                                            @if ((string) $selectedRoomId === (string) $room->id) data-selected="true" @endif>
                                                        <span>{{ $room->name }}</span>
                                                        @if ($room->capacity !== null)
                                                            <small>Capacity {{ $room->capacity }}</small>
                                                        @endif
                                                    </button>
                                                @endforeach
                                            </div>

                                            <small class="daily-media-search-message" hidden>
                                                No matching Training Media found.
                                            </small>
                                            <small class="daily-media-selected" hidden></small>
                                        </div>
                                    </td>

                                    <td>
                                        <input type="text" class="daily-input"
                                                name="sessions[{{ $index }}][class_name]"
                                                maxlength="150"
                                                value="{{ old($oldPrefix . '.class_name', $session?->class_name) }}"
                                                placeholder="Enter group name"
                                                aria-label="Group name">
                                    </td>

                                    <td>
                                        <input type="text" class="daily-input"
                                               name="sessions[{{ $index }}][instructor_name]"
                                               maxlength="150"
                                               value="{{ old($oldPrefix . '.instructor_name', $session?->instructor_name) }}"
                                               placeholder="Optional">
                                    </td>

                                    <td>
                                        <input type="number" min="0" class="daily-input"
                                               name="sessions[{{ $index }}][participant_count]"
                                               value="{{ old($oldPrefix . '.participant_count', $session?->participant_count ?? 0) }}"
                                               aria-label="Participant count">
                                    </td>

                                    <td>
                                        <textarea class="daily-textarea"
                                                  name="sessions[{{ $index }}][notes]"
                                                  placeholder="Optional notes">{{ old($oldPrefix . '.notes', $session?->notes) }}</textarea>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="daily-footer">
                    <div class="daily-footer-note">
                        Check <strong>Use session</strong> for every time slot you want to activate.
                        Unchecked sessions will not be saved as active sessions for this date.
                        Session times are taken from the building's templates.
                    </div>
                    <div class="daily-footer-actions">
                        <button type="button" class="daily-button" id="resetDailyScheduleButton">
                            Reset Form
                        </button>
                        <button type="submit" class="daily-button daily-button-primary">
                            Save Daily Schedule
                        </button>
                    </div>
                </div>
            </form>
        </section>

        <div class="daily-alert daily-alert-info">
            <strong>Schedule validation:</strong>
            the server checks Training Media availability, participant capacity,
            approved reservations, and overlapping sessions when saving.
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('dailyScheduleForm');
    if (!form) return;

    const copyButton = document.getElementById('copyPreviousDayButton');
    const resetButton = document.getElementById('resetDailyScheduleButton');
    const previousSessions = @json($previousSessions);

    const rows = Array.from(form.querySelectorAll('[data-session-row]'));

    function updateRowState(row) {
        const checkbox = row.querySelector('.daily-session-checkbox');
        const state = row.querySelector('.daily-session-state');
        const fields = Array.from(row.querySelectorAll(
            'input:not(.daily-session-checkbox), textarea'
        ));

        const enabled = checkbox.checked;

        row.classList.toggle('is-disabled', !enabled);

        fields.forEach(function (field) {
            field.disabled = !enabled;
        });

        const picker = row.querySelector('.daily-media-picker');
        if (picker) {
            const results = picker.querySelector('.daily-media-results');
            const message = picker.querySelector('.daily-media-search-message');

            if (!enabled) {
                if (results) results.hidden = true;
                if (message) message.hidden = true;
            }
        }

        if (state) {
            if (!enabled) {
                state.textContent = 'Session disabled';
            } else if (row.dataset.copied === 'true') {
                state.textContent = 'Copied draft — not saved';
            } else if (row.dataset.saved === 'true') {
                state.textContent = 'Event saved';
            } else {
                state.textContent = 'Enabled slot';
            }
        }
    }

    rows.forEach(function (row) {
        const checkbox = row.querySelector('.daily-session-checkbox');
        const state = row.querySelector('.daily-session-state');

        row.dataset.saved = state && state.textContent.trim() === 'Event saved'
            ? 'true'
            : 'false';

        if (checkbox) {
            checkbox.addEventListener('change', function () {
                updateRowState(row);
            });
        }

        updateRowState(row);
    });

    function setTrainingMedia(row, value) {
        const picker = row.querySelector('.daily-media-picker');
        if (!picker) return;

        const search = picker.querySelector('.daily-media-search');
        const hiddenInput = picker.querySelector('.daily-media-value');
        const results = picker.querySelector('.daily-media-results');
        const message = picker.querySelector('.daily-media-search-message');
        const selectedLabel = picker.querySelector('.daily-media-selected');
        const options = Array.from(picker.querySelectorAll('.daily-media-option'));

        const match = options.find(option => option.dataset.value === String(value));

        if (hiddenInput) hiddenInput.value = match ? match.dataset.value : '';
        if (search) search.value = match ? match.querySelector('span').textContent.trim() : '';
        if (selectedLabel) {
            selectedLabel.textContent = match ? 'Selected: ' + search.value : '';
            selectedLabel.hidden = !match;
        }
        if (results) results.hidden = true;
        if (message) message.hidden = true;
    }

    if (copyButton) {
        copyButton.addEventListener('click', function () {
            if (!Array.isArray(previousSessions) || previousSessions.length === 0) {
                alert('The previous day has no events to copy.');
                return;
            }

            const normalizeTime = value => String(value || '').substring(0, 8);
            const getField = (row, field) => row.querySelector('[name$="[' + field + ']"]');
            const used = new Set();

            const slots = rows.map(row => {
                const badge = row.querySelector('.daily-session-time');
                const times = badge ? badge.textContent.trim().split(/\s*[–-]\s*/) : [];
                return { row, start: times[0] || '', end: times[1] || '' };
            });

            slots.forEach(function (slot) {
                const checkbox = slot.row.querySelector('.daily-session-checkbox');
                if (checkbox) checkbox.checked = true;

                let sourceIndex = previousSessions.findIndex((source, index) =>
                    !used.has(index) &&
                    normalizeTime(source.start_time).substring(0, 5) === slot.start &&
                    normalizeTime(source.end_time).substring(0, 5) === slot.end
                );


                ['subject', 'class_name', 'instructor_name', 'participant_count', 'notes']
                    .forEach(function (field) {
                        const input = getField(slot.row, field);
                        if (!input) return;

                        input.value = sourceIndex === -1
                            ? (field === 'participant_count' ? '0' : '')
                            : (previousSessions[sourceIndex][field] ??
                                (field === 'participant_count' ? '0' : ''));

                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    });

                setTrainingMedia(
                    slot.row,
                    sourceIndex === -1 ? '' : previousSessions[sourceIndex].training_room_id
                );

                slot.row.dataset.copied = sourceIndex === -1 ? 'false' : 'true';

                updateRowState(slot.row);

                if (sourceIndex !== -1) used.add(sourceIndex);
            });

            alert('Draft copied. Review the events and Training Media, then click Save Daily Schedule to save.');
        });
    }

    if (resetButton) {
        resetButton.addEventListener('click', function () {
            rows.forEach(function (row) {
                // Reset keeps every slot enabled, matching the default for a new schedule.
                const checkbox = row.querySelector('.daily-session-checkbox');
                if (checkbox) checkbox.checked = true;

                row.querySelectorAll('input:not([type="hidden"]):not(.daily-session-checkbox), textarea')
                    .forEach(function (field) {
                        field.value = '';
                    });

                const participantCount = row.querySelector('input[name$="[participant_count]"]');
                if (participantCount) participantCount.value = '0';

                setTrainingMedia(row, '');

                row.dataset.copied = 'false';
                row.dataset.saved = 'false';

                updateRowState(row);
            });
        });
    }
});
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.daily-media-picker').forEach(function (picker) {
        const search = picker.querySelector('.daily-media-search');
        const hiddenInput = picker.querySelector('.daily-media-value');
        const results = picker.querySelector('.daily-media-results');
        const message = picker.querySelector('.daily-media-search-message');
        const selectedLabel = picker.querySelector('.daily-media-selected');
        const options = Array.from(picker.querySelectorAll('.daily-media-option'));

        if (!search || !hiddenInput || !results || !message || !selectedLabel) return;

        function filterMedia() {
            if (search.disabled) return;

            const query = search.value.trim().toLocaleLowerCase();
            let matchCount = 0;

            options.forEach(function (option) {
                const name = (option.dataset.search || '').trim().toLocaleLowerCase();
                const matches = query.length > 0 && name.includes(query);
                option.hidden = !matches;
                if (matches) matchCount++;
            });

            results.hidden = true;
            message.hidden = true;

            if (query === '') return;

            if (matchCount > 0) {
                results.hidden = false;
            } else {
                message.hidden = false;
            }
        }

        function selectMedia(option) {
            if (search.disabled) return;

            hiddenInput.value = option.dataset.value;
            search.value = option.querySelector('span').textContent.trim();
            selectedLabel.textContent = 'Selected: ' + search.value;
            selectedLabel.hidden = false;
            results.hidden = true;
            message.hidden = true;
        }

        options.forEach(function (option) {
            option.hidden = true;
            option.addEventListener('click', function () {
                selectMedia(option);
            });
        });

        search.addEventListener('input', function () {
            if (search.disabled) return;

            hiddenInput.value = '';
            selectedLabel.hidden = true;
            filterMedia();
        });

        search.addEventListener('focus', filterMedia);

        search.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                results.hidden = true;
                message.hidden = true;
            }
        });

        document.addEventListener('click', function (event) {
            if (!picker.contains(event.target)) {
                results.hidden = true;
            }
        });

        const savedValue = hiddenInput.value;
        if (savedValue !== '') {
            const savedOption = options.find(option => option.dataset.value === savedValue);
            if (savedOption) {
                search.value = savedOption.querySelector('span').textContent.trim();
                selectedLabel.textContent = 'Selected: ' + search.value;
                selectedLabel.hidden = false;
            }
        }

        results.hidden = true;
        message.hidden = true;
    });
});
</script>
@endpush
