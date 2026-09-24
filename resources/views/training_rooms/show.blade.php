@extends('layouts.admin')

@section('title', 'Training Room Details')
@section('page_title', 'Training Room Details')

@section('content')

<div class="page-header">

    <div>
        <a
            href="{{ route('training-rooms.index') }}"
            class="back-link"
        >
            ← Back to Media Training
        </a>

        <h2>{{ $trainingRoom->name }}</h2>

        <p class="page-description">
            View Media Training information and available images.
        </p>
    </div>

    <div class="header-actions">

        <a
            href="{{ route('training-rooms.edit', $trainingRoom) }}"
            class="btn btn-primary"
        >
            Edit Media Training
        </a>

        <a
            href="{{ route('training-rooms.index') }}"
            class="btn btn-secondary"
        >
            Back
        </a>

    </div>

</div>

<div class="detail-grid">

    <div class="content-card">

        <div class="card-header">

            <div>
                <h3>Media Training Information</h3>

                <p>
                    Basic information about this Media Training.
                </p>
            </div>

            <span
                class="status-badge status-{{ strtolower($trainingRoom->status) }}"
            >
                {{ $trainingRoom->status }}
            </span>

        </div>

        <div class="info-grid">

            <div class="info-item">
                <span class="info-label">
                    Media Training Name
                </span>

                <span class="info-value">
                    {{ $trainingRoom->name }}
                </span>
            </div>

            <div class="info-item">
                <span class="info-label">
                    Building
                </span>

                <span class="info-value">
                    {{ $trainingRoom->building->name }}
                </span>
            </div>

            <div class="info-item">
                <span class="info-label">
                    Capacity
                </span>

                <span class="info-value">
                    {{ $trainingRoom->capacity }}
                </span>
            </div>

            <div class="info-item">
                <span class="info-label">
                    Simulation Type
                </span>

                <span class="info-value">
                    {{ $trainingRoom->simulation_type }}
                </span>
            </div>

            <div class="info-item">
                <span class="info-label">
                    Images
                </span>

                <span class="info-value">
                    {{ $trainingRoom->images->count() }}
                </span>
            </div>

            <div class="info-item info-item-wide">
                <span class="info-label">
                    Simulation Facilities
                </span>

                <span class="info-value description-value">
                    {{ $trainingRoom->simulation_facilities }}
                </span>
            </div>

        </div>

    </div>

    <div class="content-card">

        <div class="card-header">

            <div>
                <h3>Media Training Images</h3>

                <p>
                    Images uploaded for this Media Training.
                </p>
            </div>

        </div>

        @if ($trainingRoom->images->count())

            <div class="image-gallery">

                @foreach ($trainingRoom->images as $image)

                    <div class="image-card">

                        <div class="image-wrapper">

                            <img
                                src="{{ asset('storage/' . $image->file) }}"
                                alt="{{ $trainingRoom->name }} image {{ $loop->iteration }}"
                            >

                        </div>

                        <div class="image-meta">

                            @if ($loop->first)

                                <span class="primary-badge">
                                    Primary Image
                                </span>

                            @else

                                <span class="image-number">
                                    Image {{ $loop->iteration }}
                                </span>

                            @endif

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="empty-state">

                <div class="empty-icon">
                    ▧
                </div>

                <h3>
                    No Images Available
                </h3>

                <p>
                    No images have been uploaded for this Media Training yet.
                </p>

                <a
                    href="{{ route('training-rooms.edit', $trainingRoom) }}"
                    class="btn btn-primary"
                >
                    Add Images
                </a>

            </div>

        @endif

    </div>

</div>

@endsection

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 10px;
        color: #006fae;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
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

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .detail-grid {
        display: grid;
        gap: 20px;
    }

    .content-card {
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #d9e5ed;
        border-radius: 12px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        gap: 20px;
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

    .status-badge {
        display: inline-flex;
        height: fit-content;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .status-available {
        background: #dff7fb;
        color: #006fae;
    }

    .status-maintenance {
        background: #fff3d6;
        color: #8a6200;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .info-item {
        padding: 18px 20px;
        border-bottom: 1px solid #edf2f5;
    }

    .info-item-wide {
        grid-column: span 2;
    }

    .info-label {
        display: block;
        margin-bottom: 6px;
        color: #668096;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .info-value {
        display: block;
        color: #12304a;
        font-size: 14px;
        font-weight: 600;
    }

    .description-value {
        white-space: pre-line;
    }

    .image-gallery {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
        padding: 20px;
    }

    .image-card {
        overflow: hidden;
        border: 1px solid #d9e5ed;
        border-radius: 10px;
    }

    .image-wrapper {
        aspect-ratio: 16 / 10;
        overflow: hidden;
        background: #f3f7fa;
    }

    .image-wrapper img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-meta {
        min-height: 46px;
        display: flex;
        align-items: center;
        padding: 10px 12px;
        border-top: 1px solid #edf2f5;
    }

    .primary-badge {
        padding: 5px 9px;
        border-radius: 20px;
        background: #dff7fb;
        color: #006fae;
        font-size: 10px;
        font-weight: 700;
    }

    .image-number {
        color: #668096;
        font-size: 11px;
        font-weight: 600;
    }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .empty-icon {
        color: #006fae;
        font-size: 34px;
    }

    .empty-state h3 {
        margin: 10px 0 6px;
        color: #12304a;
    }

    .empty-state p {
        margin: 0 0 20px;
        color: #668096;
        font-size: 13px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 17px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .btn-primary {
        border: 1px solid #006fae;
        background: #006fae;
        color: #ffffff;
    }

    .btn-secondary {
        border: 1px solid #d0dce5;
        background: #ffffff;
        color: #4f6680;
    }

    @media (max-width: 900px) {
        .info-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .image-gallery {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .info-item-wide {
            grid-column: span 2;
        }
    }

    @media (max-width: 700px) {
        .page-header {
            flex-direction: column;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            flex: 1;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .info-item-wide {
            grid-column: span 1;
        }

        .image-gallery {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush