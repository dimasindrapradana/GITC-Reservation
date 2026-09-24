@extends('layouts.admin')

@section('title', 'Add Field')

@section('content')

<div class="page-header">
    <div>
        <h1>Add Field</h1>
        <p>Create a new field and upload field images.</p>
    </div>

    <a
        href="{{ route('fields.index') }}"
        class="btn btn-secondary"
    >
        ← Back to Fields
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-error">
        <strong>Please correct the following errors:</strong>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="content-card">

    <div class="card-header">
        <div>
            <h2>Field Information</h2>
            <p>Enter the field details below.</p>
        </div>
    </div>

    <form
        action="{{ route('fields.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        <div class="form-grid">

            <div class="form-group full-width">
                <label for="name">
                    Field Name
                    <span class="required">*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter field name"
                    required
                >

                @error('name')
                    <small class="field-error">
                        {{ $message }}
                    </small>
                @enderror
            </div>

            <div class="form-group">
                <label for="capacity">
                    Capacity
                    <span class="required">*</span>
                </label>

                <input
                    type="number"
                    id="capacity"
                    name="capacity"
                    value="{{ old('capacity', 0) }}"
                    min="0"
                    required
                >

                @error('capacity')
                    <small class="field-error">
                        {{ $message }}
                    </small>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">
                    Status
                    <span class="required">*</span>
                </label>

                <select
                    id="status"
                    name="status"
                    required
                >
                    <option value="AVAILABLE">
                        Available
                    </option>

                    <option value="MAINTENANCE">
                        Maintenance
                    </option>
                </select>

                @error('status')
                    <small class="field-error">
                        {{ $message }}
                    </small>
                @enderror
            </div>

        </div>

        <div class="section-divider"></div>

        <div class="image-section">

            <div class="section-title">
                <div>
                    <h3>Field Images</h3>
                    <p>
                        Upload up to 10 images. The first image will be
                        treated as the primary image.
                    </p>
                </div>
            </div>

            <div class="upload-box">

                <input
                    type="file"
                    id="images"
                    name="images[]"
                    accept=".jpg,.jpeg,.png,.webp"
                    multiple
                >

                <label
                    for="images"
                    class="upload-label"
                >
                    <span class="upload-icon">＋</span>

                    <span class="upload-title">
                        Choose Images
                    </span>

                    <span class="upload-description">
                        JPG, JPEG, PNG or WebP · Maximum 5 MB each
                    </span>
                </label>

            </div>

            <div
                id="image-count"
                class="image-count"
            ></div>

            <div
                id="image-preview"
                class="preview-grid"
            ></div>

        </div>

        <div class="form-actions">

            <a
                href="{{ route('fields.index') }}"
                class="btn btn-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Field
            </button>

        </div>

    </form>

</div>

<style>

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 22px;
}

.page-header h1 {
    margin: 0 0 6px;
    color: #17324d;
    font-size: 25px;
    font-weight: 700;
}

.page-header p {
    margin: 0;
    color: #668096;
    font-size: 13px;
}

.content-card {
    background: #ffffff;
    border: 1px solid #e2eaf0;
    border-radius: 10px;
    overflow: hidden;
}

.card-header {
    padding: 20px 22px;
    border-bottom: 1px solid #edf2f5;
}

.card-header h2 {
    margin: 0 0 5px;
    color: #17324d;
    font-size: 16px;
    font-weight: 700;
}

.card-header p {
    margin: 0;
    color: #7a8da0;
    font-size: 12px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
    padding: 22px;
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
    color: #344b60;
    font-size: 12px;
    font-weight: 600;
}

.required {
    color: #d9534f;
}

.form-group input,
.form-group select {
    width: 100%;
    height: 40px;
    padding: 0 12px;
    border: 1px solid #d0dce5;
    border-radius: 6px;
    background: #ffffff;
    color: #334b61;
    font-family: inherit;
    font-size: 13px;
    outline: none;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #006fae;
    box-shadow: 0 0 0 3px rgba(0, 111, 174, 0.08);
}

.field-error {
    display: block;
    margin-top: 6px;
    color: #c0392b;
    font-size: 11px;
}

.section-divider {
    height: 1px;
    background: #edf2f5;
}

.image-section {
    padding: 22px;
}

.section-title {
    margin-bottom: 16px;
}

.section-title h3 {
    margin: 0 0 5px;
    color: #17324d;
    font-size: 14px;
    font-weight: 700;
}

.section-title p {
    margin: 0;
    color: #7a8da0;
    font-size: 12px;
}

.upload-box {
    position: relative;
    border: 1px dashed #b8cbd8;
    border-radius: 8px;
    background: #f9fbfc;
}

.upload-box input[type="file"] {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
    pointer-events: none;
}

.upload-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 145px;
    padding: 20px;
    cursor: pointer;
    text-align: center;
}

.upload-label:hover {
    background: #f2f9fc;
}

.upload-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    margin-bottom: 10px;
    border-radius: 50%;
    background: #eaf5fb;
    color: #006fae;
    font-size: 22px;
}

.upload-title {
    color: #344b60;
    font-size: 13px;
    font-weight: 700;
}

.upload-description {
    margin-top: 5px;
    color: #8799a8;
    font-size: 11px;
}

.image-count {
    margin-top: 10px;
    color: #668096;
    font-size: 11px;
}

.preview-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-top: 12px;
}

.preview-item {
    position: relative;
    overflow: hidden;
    border: 1px solid #dfe8ee;
    border-radius: 8px;
    background: #ffffff;
}

.preview-item img {
    display: block;
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.preview-remove {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 28px;
    height: 28px;
    border: 0;
    border-radius: 50%;
    background: rgba(35, 48, 61, 0.82);
    color: #ffffff;
    font-size: 16px;
    line-height: 28px;
    cursor: pointer;
}

.preview-remove:hover {
    background: rgba(192, 57, 43, 0.95);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 18px 22px;
    border-top: 1px solid #edf2f5;
    background: #fbfcfd;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 38px;
    padding: 0 15px;
    border-radius: 6px;
    font-family: inherit;
    font-size: 12px;
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

.btn-primary:hover {
    background: #005d91;
}

.btn-secondary {
    border: 1px solid #d0dce5;
    background: #ffffff;
    color: #4f6680;
}

.btn-secondary:hover {
    background: #f5f8fa;
}

.alert {
    margin-bottom: 18px;
    padding: 12px 15px;
    border-radius: 7px;
    font-size: 12px;
}

.alert-error {
    border: 1px solid #efd0cd;
    background: #fdf5f4;
    color: #a93226;
}

.alert-error ul {
    margin: 8px 0 0;
    padding-left: 18px;
}

@media (max-width: 900px) {
    .preview-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

@media (max-width: 700px) {
    .page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full-width {
        grid-column: auto;
    }

    .preview-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 480px) {
    .preview-grid {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .form-actions .btn {
        width: 100%;
    }
}

</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('images');
    const preview = document.getElementById('image-preview');
    const imageCount = document.getElementById('image-count');

    let selectedFiles = [];

    input.addEventListener('change', function () {

        const incomingFiles = Array.from(input.files);

        for (const file of incomingFiles) {

            if (selectedFiles.length >= 10) {
                break;
            }

            const duplicate = selectedFiles.some(function (existingFile) {
                return (
                    existingFile.name === file.name &&
                    existingFile.size === file.size &&
                    existingFile.lastModified === file.lastModified
                );
            });

            if (!duplicate) {
                selectedFiles.push(file);
            }
        }

        syncInputFiles();
        renderPreviews();

    });

    function syncInputFiles() {

        const dataTransfer = new DataTransfer();

        selectedFiles.forEach(function (file) {
            dataTransfer.items.add(file);
        });

        input.files = dataTransfer.files;
    }

    function renderPreviews() {

        preview.innerHTML = '';

        imageCount.textContent =
            selectedFiles.length > 0
                ? selectedFiles.length + ' image(s) selected.'
                : '';

        selectedFiles.forEach(function (file, index) {

            const reader = new FileReader();

            reader.onload = function (event) {

                const item = document.createElement('div');

                item.className = 'preview-item';

                item.innerHTML = `
                    <img
                        src="${event.target.result}"
                        alt="Field image preview"
                    >

                    <button
                        type="button"
                        class="preview-remove"
                        aria-label="Remove image"
                    >
                        ×
                    </button>
                `;

                item
                    .querySelector('.preview-remove')
                    .addEventListener('click', function () {

                        selectedFiles.splice(index, 1);

                        syncInputFiles();
                        renderPreviews();

                    });

                preview.appendChild(item);
            };

            reader.readAsDataURL(file);
        });
    }

});
</script>

@endsection