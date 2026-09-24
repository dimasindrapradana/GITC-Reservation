@extends('layouts.admin')

@section('title', 'Add Room')

@section('page_title', 'Add Room')

@section('content')

<div class="page-header">

    <div>
        <h2>Add Room</h2>

        <p class="page-description">
            Add a new room to the master data.
        </p>
    </div>

    <a
        href="{{ route('rooms.index') }}"
        class="btn btn-secondary"
    >
        Back to Rooms
    </a>

</div>

@if ($errors->any())

    <div class="alert alert-error">

        <div>

            <strong>Please check the following errors:</strong>

            <ul class="error-list">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    </div>

@endif

<div class="content-card">

    <div class="card-header">

        <div>
            <h3>Room Information</h3>

            <p>
                Enter the information for the new room.
            </p>
        </div>

    </div>

    <form
        action="{{ route('rooms.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="form"
    >

        @csrf

        <div class="form-grid">

            <div class="form-group">

                <label for="building_id">
                    Building
                    <span class="required">*</span>
                </label>

                <select
                    id="building_id"
                    name="building_id"
                    class="form-control"
                    required
                >

                    <option value="">
                        Select building
                    </option>

                    @foreach ($buildings as $building)

                        <option
                            value="{{ $building->id }}"
                            @selected(old('building_id') == $building->id)
                        >
                            {{ $building->name }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="form-group">

                <label for="name">
                    Room Name
                    <span class="required">*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                    maxlength="100"
                    required
                >

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
                    class="form-control"
                    value="{{ old('capacity', 0) }}"
                    min="0"
                    required
                >

                <small class="form-help">
                    Enter 0 if no capacity value is assigned.
                </small>

            </div>

            <div class="form-group">

                <label for="lcd_count">
                    LCD Count
                    <span class="required">*</span>
                </label>

                <input
                    type="number"
                    id="lcd_count"
                    name="lcd_count"
                    class="form-control"
                    value="{{ old('lcd_count', 0) }}"
                    min="0"
                    required
                >

            </div>

            <div class="form-group">

                <label for="whiteboard_count">
                    Whiteboard Count
                    <span class="required">*</span>
                </label>

                <input
                    type="number"
                    id="whiteboard_count"
                    name="whiteboard_count"
                    class="form-control"
                    value="{{ old('whiteboard_count', 0) }}"
                    min="0"
                    required
                >

            </div>

            <div class="form-group">

                <label for="status">
                    Status
                    <span class="required">*</span>
                </label>

                <select
                    id="status"
                    name="status"
                    class="form-control"
                    required
                >

                    <option
                        value="AVAILABLE"
                        @selected(old('status', 'AVAILABLE') === 'AVAILABLE')
                    >
                        AVAILABLE
                    </option>

                    <option
                        value="MAINTENANCE"
                        @selected(old('status') === 'MAINTENANCE')
                    >
                        MAINTENANCE
                    </option>

                </select>

            </div>

        </div>

        <div class="image-section">

            <div class="section-title">

                <div>

                    <h3>Room Images</h3>

                    <p>
                        Upload up to 10 images. The first image will be used as the primary image.
                    </p>

                </div>

            </div>

            <label
                for="images"
                class="upload-box"
            >

                <span class="upload-icon">
                    +
                </span>

                <strong>
                    Select Room Images
                </strong>

                <span>
                    JPG, JPEG, PNG, or WEBP • Maximum 5 MB each
                </span>

            </label>

            <input
                type="file"
                id="images"
                name="images[]"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                multiple
                hidden
            >

            <div
                id="image-preview"
                class="image-grid"
            ></div>

        </div>

        <div class="form-actions">

            <a
                href="{{ route('rooms.index') }}"
                class="btn btn-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Room
            </button>

        </div>

    </form>

</div>

@endsection

@push('styles')

<style>

    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-header h2 {
        margin: 0 0 6px;
        color: #12304a;
        font-size: 24px;
        font-weight: 700;
    }

    .page-description {
        margin: 0;
        color: #668096;
        font-size: 14px;
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
        font-weight: 700;
    }

    .card-header p {
        margin: 0;
        color: #668096;
        font-size: 13px;
    }

    .form {
        padding: 24px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        color: #12304a;
        font-size: 13px;
        font-weight: 700;
    }

    .required {
        color: #b33a3a;
    }

    .form-control {
        width: 100%;
        min-height: 42px;
        padding: 9px 12px;
        border: 1px solid #d0dce5;
        border-radius: 7px;
        outline: none;
        background: #ffffff;
        color: #4f6680;
        font-family: inherit;
        font-size: 13px;
        box-sizing: border-box;
        transition:
            border-color .15s ease,
            box-shadow .15s ease;
    }

    .form-control:focus {
        border-color: #006fae;
        box-shadow: 0 0 0 3px rgba(0, 111, 174, .10);
    }

    .form-help {
        color: #668096;
        font-size: 11px;
        line-height: 1.5;
    }

    .image-section {
        margin-top: 28px;
        padding-top: 24px;
        border-top: 1px solid #edf2f5;
    }

    .section-title {
        margin-bottom: 16px;
    }

    .section-title h3 {
        margin: 0 0 5px;
        color: #12304a;
        font-size: 15px;
        font-weight: 700;
    }

    .section-title p {
        margin: 0;
        color: #668096;
        font-size: 12px;
        line-height: 1.5;
    }

    .upload-box {
        min-height: 150px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 20px;
        border: 1.5px dashed #c9d9e3;
        border-radius: 10px;
        background: #f8fafb;
        color: #668096;
        text-align: center;
        cursor: pointer;
        transition:
            border-color .15s ease,
            background .15s ease;
        box-sizing: border-box;
    }

    .upload-box:hover {
        border-color: #006fae;
        background: #f2f9fc;
    }

    .upload-box strong {
        color: #12304a;
        font-size: 13px;
        font-weight: 700;
    }

    .upload-box span:last-child {
        color: #668096;
        font-size: 11px;
    }

    .upload-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border: 1px solid #c9dfe9;
        border-radius: 50%;
        background: #ffffff;
        color: #006fae;
        font-size: 22px;
        font-weight: 400;
        line-height: 1;
    }

    .image-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-top: 16px;
    }

    .image-preview {
        position: relative;
        overflow: hidden;
        aspect-ratio: 4 / 3;
        border: 1px solid #d9e5ed;
        border-radius: 8px;
        background: #f3f6f8;
    }

    .image-preview img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .primary-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        padding: 4px 7px;
        border-radius: 5px;
        background: rgba(18, 48, 74, .90);
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
    }

    .remove-preview {
        position: absolute;
        top: 8px;
        right: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        padding: 0;
        border: 1px solid rgba(255, 255, 255, .35);
        border-radius: 6px;
        background: rgba(18, 48, 74, .90);
        color: #ffffff;
        font-size: 17px;
        line-height: 1;
        cursor: pointer;
    }

    .remove-preview:hover {
        background: #b33a3a;
        border-color: #b33a3a;
    }

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid #edf2f5;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 16px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        box-sizing: border-box;
        transition:
            background .15s ease,
            border-color .15s ease,
            color .15s ease;
    }

    .btn-primary {
        border: 1px solid #006fae;
        background: #006fae;
        color: #ffffff;
    }

    .btn-primary:hover {
        border-color: #003b6f;
        background: #003b6f;
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

    .alert {
        margin-bottom: 20px;
        padding: 14px 16px;
        border-radius: 8px;
        font-size: 13px;
    }

    .alert-error {
        border: 1px solid #edc4c4;
        background: #fff7f7;
        color: #9b2c2c;
    }

    .error-list {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    .error-list li {
        margin-bottom: 3px;
    }

    @media (max-width: 800px) {

        .form-grid {
            grid-template-columns: 1fr;
        }

        .image-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

    }

    @media (max-width: 600px) {

        .page-header {
            flex-direction: column;
        }

        .page-header .btn {
            width: 100%;
        }

        .form {
            padding: 18px;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .form-actions .btn {
            width: 100%;
        }

    }

    @media (max-width: 480px) {

        .image-grid {
            grid-template-columns: 1fr;
        }

    }

</style>

@endpush

@push('scripts')

<script>
    (() => {

        const input = document.getElementById('images');
        const preview = document.getElementById('image-preview');

        if (!input || !preview) {
            return;
        }

        let selectedFiles = [];

        const updateInput = () => {

            const dataTransfer = new DataTransfer();

            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });

            input.files = dataTransfer.files;

        };

        const renderPreview = () => {

            preview.innerHTML = '';

            selectedFiles.forEach((file, index) => {

                const wrapper = document.createElement('div');
                wrapper.className = 'image-preview';

                const image = document.createElement('img');
                image.alt = file.name;

                const badge = document.createElement('span');
                badge.className = 'primary-badge';
                badge.textContent =
                    index === 0
                        ? 'Primary'
                        : `Image ${index + 1}`;

                const remove = document.createElement('button');

                remove.type = 'button';
                remove.className = 'remove-preview';
                remove.textContent = '×';
                remove.setAttribute(
                    'aria-label',
                    `Remove ${file.name}`
                );

                remove.addEventListener('click', () => {

                    selectedFiles.splice(index, 1);

                    updateInput();
                    renderPreview();

                });

                const reader = new FileReader();

                reader.addEventListener('load', () => {
                    image.src = reader.result;
                });

                reader.readAsDataURL(file);

                wrapper.appendChild(image);
                wrapper.appendChild(badge);
                wrapper.appendChild(remove);

                preview.appendChild(wrapper);

            });

        };

        input.addEventListener('change', () => {

            const incomingFiles = Array.from(input.files);

            selectedFiles = [
                ...selectedFiles,
                ...incomingFiles,
            ];

            const uniqueFiles = [];
            const signatures = new Set();

            for (const file of selectedFiles) {

                const signature =
                    `${file.name}-${file.size}-${file.lastModified}`;

                if (!signatures.has(signature)) {

                    signatures.add(signature);
                    uniqueFiles.push(file);

                }

            }

            selectedFiles = uniqueFiles.slice(0, 10);

            updateInput();
            renderPreview();

        });

    })();
</script>

@endpush