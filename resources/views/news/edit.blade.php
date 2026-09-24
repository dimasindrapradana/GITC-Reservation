@extends('layouts.admin')

@section('title', 'Edit News')
@section('page_title', 'Edit News')

@section('content')

<div class="page-header">
    <div>
        <h1>Edit News</h1>
        <p>Update news information, schedule, and images.</p>
    </div>

    <div class="page-header-actions">
        <a
            href="{{ route('news.show', $news) }}"
            class="btn-secondary"
        >
            View Details
        </a>

        <a
            href="{{ route('news.index') }}"
            class="btn-secondary"
        >
            Back to News
        </a>
    </div>
</div>

@if ($errors->any())
    <div class="alert-error">
        <strong>Please correct the following errors:</strong>

        <ul class="error-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="content-card">

    <div class="card-header">
        <div>
            <h2>News Information</h2>
            <p>Update the information below and save your changes.</p>
        </div>
    </div>

    <form
        action="{{ route('news.update', $news) }}"
        method="POST"
        enctype="multipart/form-data"
        id="news-form"
    >
        @csrf
        @method('PUT')

        <div class="form-content">

            <div class="form-grid">

                <div class="form-group full-width">
                    <label for="title">
                        Title
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="title"
                        id="title"
                        value="{{ old('title', $news->title) }}"
                        required
                        maxlength="255"
                    >

                    @error('title')
                        <span class="field-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">
                        Status
                        <span class="required">*</span>
                    </label>

                    <select
                        name="status"
                        id="status"
                        required
                    >
                        <option
                            value="PENDING"
                            @selected(old('status', $news->status) === 'PENDING')
                        >
                            Pending
                        </option>

                        <option
                            value="SCHEDULED"
                            @selected(old('status', $news->status) === 'SCHEDULED')
                        >
                            Scheduled
                        </option>

                        <option
                            value="PUBLISHED"
                            @selected(old('status', $news->status) === 'PUBLISHED')
                        >
                            Published
                        </option>

                        <option
                            value="EXPIRED"
                            @selected(old('status', $news->status) === 'EXPIRED')
                        >
                            Expired
                        </option>

                        <option
                            value="CANCELLED"
                            @selected(old('status', $news->status) === 'CANCELLED')
                        >
                            Cancelled
                        </option>

                        <option
                            value="REJECTED"
                            @selected(old('status', $news->status) === 'REJECTED')
                        >
                            Rejected
                        </option>
                    </select>

                    @error('status')
                        <span class="field-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="starts_at">
                        Start Date & Time
                        <span class="required">*</span>
                    </label>

                    <input
                        type="datetime-local"
                        name="starts_at"
                        id="starts_at"
                        value="{{ old('starts_at', $news->starts_at?->format('Y-m-d\TH:i')) }}"
                        required
                    >

                    @error('starts_at')
                        <span class="field-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="ends_at">
                        End Date & Time
                        <span class="required">*</span>
                    </label>

                    <input
                        type="datetime-local"
                        name="ends_at"
                        id="ends_at"
                        value="{{ old('ends_at', $news->ends_at?->format('Y-m-d\TH:i')) }}"
                        required
                    >

                    @error('ends_at')
                        <span class="field-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <label for="content">
                        Content
                        <span class="required">*</span>
                    </label>

                    <textarea
                        name="content"
                        id="content"
                        rows="9"
                        required
                    >{{ old('content', $news->content) }}</textarea>

                    @error('content')
                        <span class="field-error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

            </div>

            <div class="image-section">

                <div class="section-title">
                    <div>
                        <h3>New News Images</h3>
                        <p>
                            Upload additional images for this news.
                        </p>
                    </div>
                </div>

                <label
                    for="images"
                    class="upload-box"
                    id="upload-box"
                >
                    <div class="upload-icon">+</div>

                    <span class="upload-title">
                        Select News Images
                    </span>

                    <span class="upload-description">
                        JPG, JPEG, PNG or WEBP · Maximum 5 MB per file · Maximum 10 images
                    </span>
                </label>

                <input
                    type="file"
                    name="images[]"
                    id="images"
                    accept=".jpg,.jpeg,.png,.webp"
                    multiple
                >

                <div
                    id="image-upload-error"
                    class="upload-error"
                    role="alert"
                ></div>

                <div
                    id="image-preview"
                    class="image-grid"
                ></div>

            </div>

        </div>

        <div class="form-actions">

            <a
                href="{{ route('news.index') }}"
                class="btn-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn-primary"
            >
                Update News
            </button>

        </div>

    </form>

</div>

<div class="content-card existing-images-card">

    <div class="card-header">
        <div>
            <h2>Existing Images</h2>
            <p>Images currently attached to this news.</p>
        </div>
    </div>

    <div class="existing-images-content">

        @if ($news->images->isNotEmpty())

            <div class="existing-image-grid">

                @foreach ($news->images as $image)

                    <div class="existing-image-item">

                        <div class="existing-image-preview">
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

                        <div class="existing-image-footer">

                            <span class="image-number">
                                Image {{ $loop->iteration }}
                            </span>

                            <form
                                action="{{ route('news.images.destroy', [$news, $image]) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this image?');"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="delete-image"
                                >
                                    Delete
                                </button>
                            </form>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="empty-images">
                <div class="empty-icon">▧</div>

                <h3>No Existing Images</h3>

                <p>
                    No images have been uploaded for this news.
                </p>
            </div>

        @endif

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
        margin-bottom: 18px;
        background: #ffffff;
        border: 1px solid #e5edf2;
        border-radius: 9px;
        overflow: hidden;
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

    .form-content {
        padding: 22px 20px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .form-group {
        min-width: 0;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        color: #456176;
        font-size: 12px;
        font-weight: 700;
    }

    .required {
        color: #c24a4a;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        border: 1px solid #d5e0e7;
        border-radius: 7px;
        background: #ffffff;
        color: #29465d;
        font-family: inherit;
        font-size: 13px;
        box-sizing: border-box;
        outline: none;
        transition:
            border-color .15s ease,
            box-shadow .15s ease;
    }

    .form-group input,
    .form-group select {
        height: 38px;
        padding: 0 11px;
    }

    .form-group textarea {
        min-height: 190px;
        padding: 11px;
        resize: vertical;
        line-height: 1.6;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #8bc6dd;
        box-shadow: 0 0 0 3px rgba(0, 111, 174, .08);
    }

    .field-error {
        display: block;
        margin-top: 6px;
        color: #a33b3b;
        font-size: 11px;
    }

    .alert-error {
        margin-bottom: 18px;
        padding: 13px 15px;
        border: 1px solid #edc4c4;
        border-radius: 8px;
        background: #fff7f7;
        color: #9b2c2c;
        font-size: 12px;
    }

    .alert-success {
        margin-bottom: 18px;
        padding: 13px 15px;
        border: 1px solid #c8e2cf;
        border-radius: 8px;
        background: #f4fbf6;
        color: #2e7040;
        font-size: 12px;
    }

    .error-list {
        margin: 7px 0 0;
        padding-left: 18px;
    }

    .error-list li {
        margin-bottom: 3px;
    }

    .image-section {
        margin-top: 26px;
        padding-top: 22px;
        border-top: 1px solid #edf2f5;
    }

    .section-title {
        margin-bottom: 12px;
    }

    .section-title h3 {
        margin: 0 0 4px;
        color: #34546b;
        font-size: 14px;
        font-weight: 700;
    }

    .section-title p {
        margin: 0;
        color: #7a8fa1;
        font-size: 12px;
    }

    .upload-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 150px;
        padding: 20px;
        border: 1px dashed #bfd2dd;
        border-radius: 8px;
        background: #fbfdfe;
        text-align: center;
        cursor: pointer;
        transition:
            border-color .15s ease,
            background .15s ease;
        box-sizing: border-box;
    }

    .upload-box:hover {
        border-color: #8bc6dd;
        background: #f7fbfd;
    }

    .upload-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        margin-bottom: 10px;
        border-radius: 7px;
        background: #eaf5fb;
        color: #006fae;
        font-size: 22px;
        font-weight: 400;
    }

    .upload-title {
        margin-bottom: 5px;
        color: #34546b;
        font-size: 12px;
        font-weight: 700;
    }

    .upload-description {
        color: #8195a5;
        font-size: 11px;
    }

    #images {
        display: none;
    }

    .upload-error {
        display: none;
        margin-top: 12px;
        padding: 10px 12px;
        border: 1px solid #edc4c4;
        border-radius: 7px;
        background: #fff7f7;
        color: #9b2c2c;
        font-size: 12px;
    }

    .image-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-top: 14px;
    }

    .image-preview {
        position: relative;
        overflow: hidden;
        aspect-ratio: 4 / 3;
        border: 1px solid #dbe5eb;
        border-radius: 7px;
        background: #f5f8fa;
    }

    .image-preview img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .primary-badge {
        position: absolute;
        top: 7px;
        left: 7px;
        display: inline-flex;
        align-items: center;
        min-height: 22px;
        padding: 0 7px;
        border-radius: 5px;
        background: #006fae;
        color: #ffffff;
        font-size: 9px;
        font-weight: 700;
    }

    .remove-preview {
        position: absolute;
        top: 7px;
        right: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 25px;
        height: 25px;
        border: 0;
        border-radius: 5px;
        background: rgba(255, 255, 255, .94);
        color: #a33b3b;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .08);
    }

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        padding: 15px 20px;
        border-top: 1px solid #edf2f5;
        background: #fbfcfd;
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

    .existing-images-card {
        margin-bottom: 0;
    }

    .existing-images-content {
        padding: 20px;
    }

    .existing-image-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .existing-image-item {
        overflow: hidden;
        border: 1px solid #dbe5eb;
        border-radius: 8px;
        background: #ffffff;
    }

    .existing-image-preview {
        position: relative;
        aspect-ratio: 4 / 3;
        background: #f5f8fa;
    }

    .existing-image-preview img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .existing-image-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding: 9px 10px;
        border-top: 1px solid #edf2f5;
    }

    .image-number {
        color: #668096;
        font-size: 10px;
        font-weight: 600;
    }

    .existing-image-footer form {
        margin: 0;
    }

    .delete-image {
        min-height: 27px;
        padding: 0 8px;
        border: 1px solid #e2b8b8;
        border-radius: 5px;
        background: #fff7f7;
        color: #b33a3a;
        font-size: 10px;
        font-weight: 700;
        cursor: pointer;
    }

    .delete-image:hover {
        background: #fff1f1;
    }

    .empty-images {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 180px;
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

    @media (max-width: 900px) {
        .image-grid,
        .existing-image-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .page-header {
            flex-direction: column;
        }

        .page-header-actions {
            width: 100%;
        }

        .page-header-actions .btn-secondary {
            flex: 1;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .image-grid,
        .existing-image-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 480px) {
        .image-grid,
        .existing-image-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .form-actions .btn-primary,
        .form-actions .btn-secondary {
            width: 100%;
        }
    }
</style>

<script>
    (() => {
        const input = document.getElementById('images');
        const preview = document.getElementById('image-preview');
        const errorBox = document.getElementById('image-upload-error');
        const form = document.getElementById('news-form');

        if (!input || !preview || !errorBox || !form) {
            return;
        }

        const maxFiles = 10;
        const maxSize = 5 * 1024 * 1024;

        let selectedFiles = [];

        function showError(message) {
            errorBox.textContent = message;
            errorBox.style.display = 'block';
        }

        function clearError() {
            errorBox.textContent = '';
            errorBox.style.display = 'none';
        }

        function fileSignature(file) {
            return [
                file.name,
                file.size,
                file.lastModified
            ].join('|');
        }

        function syncInputFiles() {
            const dataTransfer = new DataTransfer();

            selectedFiles.forEach((file) => {
                dataTransfer.items.add(file);
            });

            input.files = dataTransfer.files;
        }

        function renderPreview() {
            preview.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();

                reader.onload = (event) => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'image-preview';

                    const image = document.createElement('img');
                    image.src = event.target.result;
                    image.alt = file.name;

                    if (index === 0) {
                        const badge = document.createElement('span');
                        badge.className = 'primary-badge';
                        badge.textContent = 'Primary';
                        wrapper.appendChild(badge);
                    }

                    const removeButton = document.createElement('button');
                    removeButton.type = 'button';
                    removeButton.className = 'remove-preview';
                    removeButton.textContent = '×';
                    removeButton.setAttribute(
                        'aria-label',
                        'Remove ' + file.name
                    );

                    removeButton.addEventListener('click', () => {
                        selectedFiles.splice(index, 1);
                        syncInputFiles();
                        renderPreview();
                    });

                    wrapper.appendChild(image);
                    wrapper.appendChild(removeButton);
                    preview.appendChild(wrapper);
                };

                reader.readAsDataURL(file);
            });
        }

        input.addEventListener('change', () => {
            clearError();

            const incomingFiles = Array.from(input.files);

            if (incomingFiles.length === 0) {
                return;
            }

            const existingSignatures = new Set(
                selectedFiles.map(fileSignature)
            );

            for (const file of incomingFiles) {
                if (
                    ![
                        'image/jpeg',
                        'image/png',
                        'image/webp'
                    ].includes(file.type)
                ) {
                    showError(
                        'Only JPG, JPEG, PNG, or WEBP images are allowed.'
                    );

                    syncInputFiles();
                    return;
                }

                if (file.size > maxSize) {
                    showError(
                        'Each image must not exceed 5 MB.'
                    );

                    syncInputFiles();
                    return;
                }

                if (selectedFiles.length >= maxFiles) {
                    showError(
                        'You can upload a maximum of 10 images.'
                    );

                    syncInputFiles();
                    return;
                }

                const signature = fileSignature(file);

                if (!existingSignatures.has(signature)) {
                    selectedFiles.push(file);
                    existingSignatures.add(signature);
                }
            }

            syncInputFiles();
            renderPreview();
        });

        form.addEventListener('submit', (event) => {
            if (selectedFiles.length > maxFiles) {
                event.preventDefault();

                showError(
                    'You can upload a maximum of 10 images.'
                );
            }
        });
    })();
</script>

@endsection