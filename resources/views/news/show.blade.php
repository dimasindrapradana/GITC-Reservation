@extends('layouts.admin')

@section('title', 'News Details')
@section('page_title', 'News Details')

@section('content')

<div class="page-header">
    <div>
        <h1>News Details</h1>
        <p>View detailed information about this news.</p>
    </div>

    <div class="page-header-actions">
        <a
            href="{{ route('news.edit', $news) }}"
            class="btn-primary"
        >
            Edit News
        </a>

        <a
            href="{{ route('news.index') }}"
            class="btn-secondary"
        >
            Back to News
        </a>
    </div>
</div>

<div class="detail-grid">

    <div class="content-card">

        <div class="card-header">
            <div>
                <h2>News Information</h2>
                <p>Basic information and publication schedule.</p>
            </div>
        </div>

        <div class="detail-content">

            <div class="detail-row">
                <div class="detail-label">
                    Title
                </div>

                <div class="detail-value">
                    {{ $news->title }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">
                    Status
                </div>

                <div class="detail-value">
                    @php
                        $statusClass = match ($news->status) {
                            'PENDING' => 'pending',
                            'SCHEDULED' => 'scheduled',
                            'PUBLISHED' => 'published',
                            'EXPIRED' => 'expired',
                            'CANCELLED' => 'cancelled',
                            'REJECTED' => 'rejected',
                            default => 'cancelled',
                        };
                    @endphp

                    <span class="status-badge {{ $statusClass }}">
                        {{ $news->status }}
                    </span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">
                    Start Date & Time
                </div>

                <div class="detail-value">
                    {{ $news->starts_at?->format('d M Y, H:i') ?? '—' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">
                    End Date & Time
                </div>

                <div class="detail-value">
                    {{ $news->ends_at?->format('d M Y, H:i') ?? '—' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">
                    Created By
                </div>

                <div class="detail-value">
                    {{ $news->creator?->name ?? '—' }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">
                    Created At
                </div>

                <div class="detail-value">
                    {{ $news->created_at?->format('d M Y, H:i') ?? '—' }}
                </div>
            </div>

            <div class="detail-row description-row">
                <div class="detail-label">
                    Content
                </div>

                <div class="detail-value detail-description">
                    {!! nl2br(e($news->content)) !!}
                </div>
            </div>

        </div>

    </div>

    <div class="content-card">

        <div class="card-header">
            <div>
                <h2>News Images</h2>
                <p>Images attached to this news.</p>
            </div>
        </div>

        <div class="image-content">

            @if ($news->images->isNotEmpty())

                <div class="image-gallery">

                    @foreach ($news->images as $image)

                        <div class="gallery-item">

                            <img
                                src="{{ asset('storage/' . $image->file) }}"
                                alt="{{ $news->title }}"
                            >

                            @if ($loop->first)
                                <span class="primary-badge">
                                    Primary
                                </span>
                            @endif

                        </div>

                    @endforeach

                </div>

            @else

                <div class="empty-images">
                    <div class="empty-icon">▧</div>
                    <h3>No Images</h3>
                    <p>No images have been uploaded for this news.</p>
                </div>

            @endif

        </div>

    </div>

</div>


<style>
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 22px;
    }

    .page-header h1 {
        margin: 0 0 6px;
        color: #183b56;
        font-size: 22px;
        font-weight: 700;
    }

    .page-header p {
        margin: 0;
        color: #668096;
        font-size: 13px;
    }

    .page-header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .content-card {
        background: #ffffff;
        border: 1px solid #e5edf2;
        border-radius: 9px;
        overflow: hidden;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(0, 0.85fr);
        gap: 18px;
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
        color: #23445d;
        font-size: 15px;
        font-weight: 700;
    }

    .card-header p {
        margin: 0;
        color: #7a8fa1;
        font-size: 12px;
    }

    .detail-content {
        padding: 4px 20px 10px;
    }

    .detail-row {
        display: grid;
        grid-template-columns: 170px minmax(0, 1fr);
        gap: 20px;
        padding: 15px 0;
        border-bottom: 1px solid #edf2f5;
    }

    .detail-row:last-child {
        border-bottom: 0;
    }

    .detail-label {
        color: #668096;
        font-size: 12px;
        font-weight: 600;
    }

    .detail-value {
        min-width: 0;
        color: #29465d;
        font-size: 13px;
        font-weight: 500;
        line-height: 1.6;
    }

    .detail-description {
        white-space: normal;
        overflow-wrap: anywhere;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        min-height: 24px;
        padding: 0 9px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.2px;
    }

    .status-badge.pending {
        background: #fff3d6;
        color: #8a6200;
    }

    .status-badge.scheduled {
        background: #f0edfb;
        color: #6650a3;
    }

    .status-badge.published {
        background: #eaf5fb;
        color: #006fae;
    }

    .status-badge.expired {
        background: #edf1f4;
        color: #65798a;
    }

    .status-badge.cancelled {
        background: #edf1f4;
        color: #65798a;
    }

    .status-badge.rejected {
        background: #fff0f0;
        color: #a33b3b;
    }

    .image-content {
        padding: 20px;
    }

    .image-gallery {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .gallery-item {
        position: relative;
        overflow: hidden;
        aspect-ratio: 4 / 3;
        border: 1px solid #e1e9ee;
        border-radius: 8px;
        background: #f5f8fa;
    }

    .gallery-item img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .primary-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        display: inline-flex;
        align-items: center;
        min-height: 23px;
        padding: 0 8px;
        border-radius: 5px;
        background: #006fae;
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
    }

    .empty-images {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 220px;
        padding: 30px 20px;
        text-align: center;
    }

    .empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        margin-bottom: 12px;
        border-radius: 8px;
        background: #f2f6f8;
        color: #8aa0b1;
        font-size: 20px;
    }

    .empty-images h3 {
        margin: 0 0 5px;
        color: #456176;
        font-size: 14px;
        font-weight: 700;
    }

    .empty-images p {
        margin: 0;
        color: #8295a5;
        font-size: 12px;
    }

    .btn-primary,
    .btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 34px;
        padding: 0 13px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        box-sizing: border-box;
        cursor: pointer;
    }

    .btn-primary {
        border: 1px solid #006fae;
        background: #006fae;
        color: #ffffff;
    }

    .btn-primary:hover {
        background: #005f96;
        border-color: #005f96;
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

    .page-footer-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 18px;
    }

    @media (max-width: 900px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 650px) {
        .page-header {
            flex-direction: column;
        }

        .page-header-actions {
            width: 100%;
        }

        .page-header-actions .btn-primary,
        .page-header-actions .btn-secondary {
            flex: 1;
        }

        .detail-row {
            grid-template-columns: 1fr;
            gap: 6px;
        }

        .image-gallery {
            grid-template-columns: 1fr;
        }

        .page-footer-actions {
            flex-direction: column-reverse;
        }

        .page-footer-actions .btn-primary,
        .page-footer-actions .btn-secondary {
            width: 100%;
        }
    }
</style>

@endsection