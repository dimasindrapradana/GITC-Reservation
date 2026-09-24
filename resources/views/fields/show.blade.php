@extends('layouts.admin')

@section('title', 'Field Details')
@section('page_title', 'Field Details')

@section('content')

<div class="page-header">

    <div>
        <a
            href="{{ route('fields.index') }}"
            class="back-link"
        >
            ← Back to Fields
        </a>

        <h2>Field Details</h2>

        <p class="page-description">
            View field information and available images.
        </p>
    </div>

    <div class="header-actions">

        <a
            href="{{ route('fields.edit', $field) }}"
            class="btn btn-primary"
        >
            Edit Field
        </a>

        <a
            href="{{ route('fields.index') }}"
            class="btn btn-secondary"
        >
            Back
        </a>

    </div>

</div>

<div class="content-card">

    <div class="card-header">

        <div>
            <h3>Field Information</h3>

            <p>
                Basic information about this field.
            </p>
        </div>

    </div>

    <div class="details-body">

        <div class="details-grid">

            <div class="detail-item">
                <span class="detail-label">
                    Field Name
                </span>

                <span class="detail-value">
                    {{ $field->name }}
                </span>
            </div>

            <div class="detail-item">
                <span class="detail-label">
                    Capacity
                </span>

                <span class="detail-value">
                    {{ $field->capacity }}
                </span>
            </div>

            <div class="detail-item">

                <span class="detail-label">
                    Status
                </span>

                <span
                    class="status-badge {{ strtolower($field->status) }}"
                >
                    {{ $field->status }}
                </span>

            </div>

        </div>

        <div class="section-divider"></div>

        <div class="form-section-header">

            <div>
                <h4>Field Images</h4>

                <p>
                    Images currently assigned to this field.
                </p>
            </div>

            <span class="image-count">
                {{ $field->images->count() }} image(s)
            </span>

        </div>

        @if ($field->images->count())

            <div class="image-gallery">

                @foreach ($field->images as $image)

                    <div class="image-card">

                        <div class="image-wrapper">

                            <img
                                src="{{ asset('storage/' . $image->file) }}"
                                alt="{{ $field->name }} image {{ $loop->iteration }}"
                            >

                            @if ($loop->first)
                                <span class="primary-badge">
                                    Primary Image
                                </span>
                            @endif

                        </div>

                        <div class="image-footer">

                            <span>
                                Image {{ $loop->iteration }}
                            </span>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="empty-images">

                <div class="empty-images-icon">
                    ▧
                </div>

                <h4>No Images Available</h4>

                <p>
                    No images have been uploaded for this field yet.
                </p>

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

    .details-body {
        padding: 24px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .detail-item {
        min-height: 72px;
        padding: 14px;
        border: 1px solid #e1eaf0;
        border-radius: 9px;
        background: #fafcfd;
    }

    .detail-label {
        display: block;
        margin-bottom: 8px;
        color: #668096;
        font-size: 11px;
        font-weight: 700;
    }

    .detail-value {
        display: block;
        color: #12304a;
        font-size: 14px;
        font-weight: 600;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        min-height: 26px;
        padding: 0 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
    }

    .status-badge.available {
        background: #eaf5fb;
        color: #006fae;
    }

    .status-badge.maintenance {
        background: #fff4e5;
        color: #a45b00;
    }

    .section-divider {
        height: 1px;
        margin: 28px 0;
        background: #edf2f5;
    }

    .form-section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 16px;
    }

    .form-section-header h4 {
        margin: 0 0 5px;
        color: #12304a;
        font-size: 15px;
    }

    .form-section-header p {
        margin: 0;
        color: #668096;
        font-size: 12px;
    }

    .image-count {
        display: inline-flex;
        align-items: center;
        min-height: 28px;
        padding: 0 10px;
        border-radius: 20px;
        background: #eef7fb;
        color: #006fae;
        font-size: 11px;
        font-weight: 700;
    }

    .image-gallery {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .image-card {
        overflow: hidden;
        border: 1px solid #d9e5ed;
        border-radius: 10px;
        background: #ffffff;
    }

    .image-wrapper {
        position: relative;
        aspect-ratio: 16 / 10;
        overflow: hidden;
        background: #f3f7fa;
    }

    .image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .primary-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 5px 9px;
        border-radius: 20px;
        background: #dff7fb;
        color: #006fae;
        font-size: 10px;
        font-weight: 700;
    }

    .image-footer {
        display: flex;
        align-items: center;
        min-height: 44px;
        padding: 8px 10px;
        border-top: 1px solid #edf2f5;
        color: #668096;
        font-size: 11px;
        font-weight: 600;
    }

    .empty-images {
        padding: 32px 20px;
        border: 1px dashed #ccd9e3;
        border-radius: 10px;
        background: #f9fbfc;
        text-align: center;
    }

    .empty-images-icon {
        color: #006fae;
        font-size: 28px;
    }

    .empty-images h4 {
        margin: 8px 0 5px;
        color: #12304a;
    }

    .empty-images p {
        margin: 0;
        color: #668096;
        font-size: 12px;
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
        cursor: pointer;
        box-sizing: border-box;
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

        .details-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .image-gallery {
            grid-template-columns: repeat(2, minmax(0, 1fr));
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

        .details-grid {
            grid-template-columns: 1fr;
        }

        .image-gallery {
            grid-template-columns: 1fr;
        }

        .form-section-header {
            flex-direction: column;
        }

    }

</style>
@endpush