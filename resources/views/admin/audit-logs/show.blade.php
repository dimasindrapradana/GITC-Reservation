@extends('layouts.admin')

@section('title', 'Audit Log Detail')
@section('page_title', 'Audit Log Detail')

@section('content')

<div class="detail-actions">
    <a
        href="{{ route('audit-logs.index') }}"
        class="button button-secondary"
    >
        Back to Audit Logs
    </a>
</div>

<div class="page-card">

    <div class="page-card-header">
        <div>
            <h2>Audit Log Information</h2>
            <p>Detailed information about this recorded activity.</p>
        </div>
    </div>

    <div class="detail-grid">

        <div class="detail-item">
            <span class="detail-label">Date & Time</span>
            <div class="detail-value">
                {{ $auditLog->created_at?->format('d M Y H:i:s') }}
            </div>
        </div>

        <div class="detail-item">
            <span class="detail-label">User</span>
            <div class="detail-value">
                {{ $auditLog->user?->name ?? 'System' }}
            </div>

            @if ($auditLog->user?->employee_number)
                <div class="detail-secondary">
                    {{ $auditLog->user->employee_number }}
                </div>
            @endif
        </div>

        <div class="detail-item">
            <span class="detail-label">Action</span>
            <div class="detail-value">
                {{ $auditLog->action }}
            </div>
        </div>

        <div class="detail-item">
            <span class="detail-label">Module</span>
            <div class="detail-value">
                {{ $auditLog->module }}
            </div>
        </div>

        <div class="detail-item">
            <span class="detail-label">Target Type</span>
            <div class="detail-value">
                {{ $auditLog->target_type ?? '-' }}
            </div>
        </div>

        <div class="detail-item">
            <span class="detail-label">Target ID</span>
            <div class="detail-value">
                {{ $auditLog->target_id ?? '-' }}
            </div>
        </div>

        <div class="detail-item detail-full">
            <span class="detail-label">Description</span>
            <div class="detail-description">
                {{ $auditLog->description }}
            </div>
        </div>

        <div class="detail-item">
            <span class="detail-label">Old Value</span>

            <pre class="json-box">{{ json_encode($auditLog->old_value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
        </div>

        <div class="detail-item">
            <span class="detail-label">New Value</span>

            <pre class="json-box">{{ json_encode($auditLog->new_value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
        </div>

    </div>

</div>

@endsection

@push('styles')
<style>
    .detail-actions {
        display: flex;
        justify-content: flex-start;
        margin-bottom: 20px;
    }

    .page-card {
        background: #ffffff;
        border: 1px solid #e7edf2;
        border-radius: 12px;
        overflow: hidden;
    }

    .page-card-header {
        padding: 22px 24px;
        border-bottom: 1px solid #e7edf2;
    }

    .page-card-header h2 {
        margin: 0;
        color: #102a43;
        font-size: 18px;
        font-weight: 700;
    }

    .page-card-header p {
        margin: 5px 0 0;
        color: #6b7c93;
        font-size: 13px;
    }

    .button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 8px 15px;
        border-radius: 7px;
        border: 1px solid transparent;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        box-sizing: border-box;
    }

    .button-secondary {
        background: #ffffff;
        color: #334e68;
        border-color: #cbd5df;
    }

    .button-secondary:hover {
        background: #f5f8fa;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0;
    }

    .detail-item {
        padding: 20px 24px;
        border-bottom: 1px solid #edf2f7;
    }

    .detail-item:nth-child(odd) {
        border-right: 1px solid #edf2f7;
    }

    .detail-full {
        grid-column: 1 / -1;
        border-right: none !important;
    }

    .detail-label {
        display: block;
        margin-bottom: 7px;
        color: #829ab1;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .detail-value {
        color: #243b53;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.5;
        word-break: break-word;
    }

    .detail-secondary {
        margin-top: 3px;
        color: #829ab1;
        font-size: 12px;
    }

    .detail-description {
        color: #243b53;
        font-size: 14px;
        line-height: 1.7;
        white-space: pre-wrap;
    }

    .json-box {
        margin: 0;
        padding: 14px;
        border: 1px solid #e7edf2;
        border-radius: 8px;
        background: #f7fafc;
        color: #334e68;
        font-family: Consolas, Monaco, monospace;
        font-size: 12px;
        line-height: 1.6;
        overflow-x: auto;
        white-space: pre-wrap;
        word-break: break-word;
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .detail-item:nth-child(odd) {
            border-right: none;
        }

        .page-card-header,
        .detail-item {
            padding: 18px;
        }
    }
</style>
@endpush