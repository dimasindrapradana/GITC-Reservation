@extends('layouts.admin')

@section('title', 'Audit Log Detail')
@section('page_title', 'Audit Log Detail')

@section('content')

@php
    $actionClass = match ($auditLog->action) {
        'CREATE' => 'action-created',
        'UPDATE' => 'action-updated',
        'APPROVE' => 'action-approved',
        'REJECT' => 'action-rejected',
        'CANCEL' => 'action-cancelled',
        default => 'action-default',
    };

    $targetType = $auditLog->target_type
        ? class_basename($auditLog->target_type)
        : '-';

    $oldValue = $auditLog->old_value;
    $newValue = $auditLog->new_value;
@endphp

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

            <p>
                Detailed information about this recorded activity.
            </p>
        </div>

        <span class="action-badge {{ $actionClass }}">
            {{ $auditLog->action }}
        </span>

    </div>

    <div class="detail-grid">

        <div class="detail-item">

            <span class="detail-label">
                Date & Time
            </span>

            <div class="detail-value">
                {{ $auditLog->created_at?->format('d M Y H:i:s') }}
            </div>

        </div>

        <div class="detail-item">

            <span class="detail-label">
                User
            </span>

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

            <span class="detail-label">
                Action
            </span>

            <div>
                <span class="action-badge {{ $actionClass }}">
                    {{ $auditLog->action }}
                </span>
            </div>

        </div>

        <div class="detail-item">

            <span class="detail-label">
                Category
            </span>

            <div class="detail-value">
                {{ $auditLog->module ?: '-' }}
            </div>

        </div>

        <div class="detail-item">

            <span class="detail-label">
                Target Type
            </span>

            <div class="detail-value">
                {{ $targetType }}
            </div>

        </div>

        <div class="detail-item">

            <span class="detail-label">
                Target ID
            </span>

            <div class="detail-value">
                {{ $auditLog->target_id ?? '-' }}
            </div>

        </div>

        <div class="detail-item detail-full">

            <span class="detail-label">
                Description
            </span>

            <div class="detail-description">
                {{ $auditLog->description ?: '-' }}
            </div>

        </div>

        <div class="detail-item value-item">

            <div class="value-header">

                <span class="detail-label">
                    Old Value
                </span>

                @if(!is_null($oldValue))
                    <span class="value-status value-status-old">
                        Before Change
                    </span>
                @endif

            </div>

            @if(is_null($oldValue))

                <div class="value-empty">
                    No previous value.
                </div>

            @else

                <pre class="json-box">{{ json_encode($oldValue, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>

            @endif

        </div>

        <div class="detail-item value-item">

            <div class="value-header">

                <span class="detail-label">
                    New Value
                </span>

                @if(!is_null($newValue))
                    <span class="value-status value-status-new">
                        After Change
                    </span>
                @endif

            </div>

            @if(is_null($newValue))

                <div class="value-empty">
                    No new value.
                </div>

            @else

                <pre class="json-box">{{ json_encode($newValue, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>

            @endif

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
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
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

    .action-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 26px;
        padding: 0 10px;
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

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0;
    }

    .detail-item {
        padding: 20px 24px;
        border-bottom: 1px solid #edf2f7;
        min-width: 0;
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
        word-break: break-word;
    }

    .value-item {
        min-width: 0;
    }

    .value-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 7px;
    }

    .value-header .detail-label {
        margin-bottom: 0;
    }

    .value-status {
        display: inline-flex;
        align-items: center;
        min-height: 22px;
        padding: 0 8px;
        border-radius: 999px;
        font-size: 9px;
        font-weight: 700;
        white-space: nowrap;
    }

    .value-status-old {
        background: #f0f2f4;
        color: #596773;
    }

    .value-status-new {
        background: #eaf7ef;
        color: #15803d;
    }

    .json-box {
        margin: 0;
        padding: 14px;
        min-height: 90px;
        max-height: 500px;
        overflow: auto;
        border: 1px solid #e7edf2;
        border-radius: 8px;
        background: #f7fafc;
        color: #334e68;
        font-family: Consolas, Monaco, monospace;
        font-size: 12px;
        line-height: 1.6;
        white-space: pre-wrap;
        word-break: break-word;
        box-sizing: border-box;
    }

    .value-empty {
        display: flex;
        align-items: center;
        min-height: 90px;
        padding: 14px;
        border: 1px dashed #d7e0e7;
        border-radius: 8px;
        background: #fafcfd;
        color: #829ab1;
        font-size: 12px;
        box-sizing: border-box;
    }

    @media (max-width: 768px) {

        .page-card-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 18px;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .detail-item:nth-child(odd) {
            border-right: none;
        }

        .detail-item {
            padding: 18px;
        }

        .value-header {
            align-items: flex-start;
            flex-direction: column;
            gap: 5px;
        }

    }

</style>
@endpush