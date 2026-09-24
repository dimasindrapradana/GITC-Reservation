@extends('layouts.admin')

@section('title', 'Edit Training Room')
@section('page_title', 'Edit Training Room')

@section('content')

<div class="page-header">
    <div>
        <a href="{{ route('training-rooms.index') }}" class="back-link">
            ← Back to Media Training
        </a>

        <h2>Edit Media Training</h2>

        <p class="page-description">
            Update Media Training information and manage room images.
        </p>
    </div>

    <div class="header-actions">
        <a
            href="{{ route('training-rooms.show', $trainingRoom) }}"
            class="btn btn-secondary"
        >
            View Details
        </a>

        <a
            href="{{ route('training-rooms.index') }}"
            class="btn btn-secondary"
        >
            Back
        </a>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-error">
        <div class="alert-title">
            Please check the following errors:
        </div>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="content-card">

    <div class="card-header">
        <div>
            <h3>Media Training Information</h3>
            <p>
                Update the basic information for this Media Training.
            </p>
        </div>
    </div>

    {{-- MAIN EDIT FORM --}}
    <form
        action="{{ route('training-rooms.update', $trainingRoom) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        <div class="form-body">

            <div class="form-row">

                <div class="form-group">
                    <label for="building_id">
                        Building
                    </label>

                    <select
                        name="building_id"
                        id="building_id"
                        class="form-control"
                        required
                    >
                        @foreach ($buildings as $building)
                            <option
                                value="{{ $building->id }}"
                                @selected(
                                    old(
                                        'building_id',
                                        $trainingRoom->building_id
                                    ) == $building->id
                                )
                            >
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="name">
                        Media Training Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control"
                        value="{{ old('name', $trainingRoom->name) }}"
                        maxlength="100"
                        required
                    >
                </div>

            </div>

            <div class="form-row">

                <div class="form-group">
                    <label for="capacity">
                        Capacity
                    </label>

                    <input
                        type="number"
                        name="capacity"
                        id="capacity"
                        class="form-control"
                        value="{{ old('capacity', $trainingRoom->capacity) }}"
                        min="0"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="simulation_type">
                        Simulation Type
                    </label>

                    <input
                        type="text"
                        name="simulation_type"
                        id="simulation_type"
                        class="form-control"
                        value="{{ old('simulation_type', $trainingRoom->simulation_type) }}"
                        maxlength="150"
                        required
                    >
                </div>

            </div>

            <div class="form-group">
                <label for="simulation_facilities">
                    Simulation Facilities
                </label>

                <textarea
                    name="simulation_facilities"
                    id="simulation_facilities"
                    class="form-control textarea-control"
                    rows="5"
                    required
                >{{ old('simulation_facilities', $trainingRoom->simulation_facilities) }}</textarea>
            </div>

            <div class="form-group">
                <label for="status">
                    Status
                </label>

                <select
                    name="status"
                    id="status"
                    class="form-control"
                    required
                >
                    <option
                        value="AVAILABLE"
                        @selected(
                            old(
                                'status',
                                $trainingRoom->status
                            ) === 'AVAILABLE'
                        )
                    >
                        AVAILABLE
                    </option>

                    <option
                        value="MAINTENANCE"
                        @selected(
                            old(
                                'status',
                                $trainingRoom->status
                            ) === 'MAINTENANCE'
                        )
                    >
                        MAINTENANCE
                    </option>
                </select>
            </div>

            <div class="section-divider"></div>

            <div class="form-section-header">
                <div>
                    <h4>Add New Images</h4>
                    <p>
                        Upload additional images for this Media Training.
                    </p>
                </div>
            </div>

            <div class="upload-area">

                <label
                    for="images"
                    class="upload-label"
                >
                    <span class="upload-icon">
                        +
                    </span>

                    <span class="upload-title">
                        Choose Images
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

            </div>

            <div
                id="image-upload-error"
                class="upload-error"
                role="alert"
            ></div>

            <div
                id="image-preview"
                class="image-preview"
            ></div>

        </div>

        <div class="form-footer">

            <a
                href="{{ route('training-rooms.index') }}"
                class="btn btn-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Changes
            </button>

        </div>

    </form>

    {{-- EXISTING IMAGES IS OUTSIDE MAIN FORM --}}
    <div class="existing-images-section">

        <div class="section-divider"></div>

        <div class="form-section-header">

            <div>
                <h4>Existing Images</h4>
                <p>
                    Manage images currently assigned to this Media Training.
                </p>
            </div>

            <span class="image-count">
                {{ $trainingRoom->images->count() }} image(s)
            </span>

        </div>

        @if ($trainingRoom->images->count())

            <div class="existing-images">

                @foreach ($trainingRoom->images as $image)

                    <div class="existing-image-card">

                        <div class="existing-image-wrapper">

                            <img
                                src="{{ asset('storage/' . $image->file) }}"
                                alt="{{ $trainingRoom->name }} image {{ $loop->iteration }}"
                            >

                            @if ($loop->first)
                                <span class="primary-badge">
                                    Primary Image
                                </span>
                            @endif

                        </div>

                        <div class="existing-image-footer">

                            <span class="image-name">
                                Image {{ $loop->iteration }}
                            </span>

                            <form
                                action="{{ route('training-rooms.images.destroy', [$trainingRoom, $image->id]) }}"
                                method="POST"
                                class="delete-image-form"
                                onsubmit="return confirm('Are you sure you want to delete this image?');"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn-delete-image"
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
                <div class="empty-images-icon">
                    ▧
                </div>

                <h4>No Images Available</h4>

                <p>
                    No images have been uploaded for this Media Training yet.
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

    .form-body {
        padding: 24px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        color: #12304a;
        font-size: 12px;
        font-weight: 700;
    }

    .form-control {
        width: 100%;
        min-height: 42px;
        padding: 0 12px;
        border: 1px solid #ccd9e3;
        border-radius: 8px;
        color: #12304a;
        font-size: 13px;
        box-sizing: border-box;
    }

    .textarea-control {
        padding-top: 10px;
        padding-bottom: 10px;
        resize: vertical;
    }

    .form-control:focus {
        border-color: #006fae;
        box-shadow: 0 0 0 3px rgba(0, 111, 174, 0.08);
        outline: none;
    }

    .section-divider {
        height: 1px;
        margin: 28px 0;
        background: #edf2f5;
    }

    .form-section-header {
        display: flex;
        justify-content: space-between;
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

    .existing-images-section {
        padding: 0 24px 24px;
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

    .existing-images {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .existing-image-card {
        overflow: hidden;
        border: 1px solid #d9e5ed;
        border-radius: 10px;
    }

    .existing-image-wrapper {
        position: relative;
        aspect-ratio: 16 / 10;
        overflow: hidden;
        background: #f3f7fa;
    }

    .existing-image-wrapper img {
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

    .existing-image-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        min-height: 48px;
        padding: 8px 10px;
        border-top: 1px solid #edf2f5;
    }

    .image-name {
        color: #668096;
        font-size: 11px;
        font-weight: 600;
    }

    .delete-image-form {
        margin: 0;
    }

    .btn-delete-image {
        min-height: 30px;
        padding: 0 10px;
        border: 1px solid #e2b8b8;
        border-radius: 6px;
        background: #fff7f7;
        color: #b33a3a;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
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

    .upload-area {
        border: 1.5px dashed #b8cbd8;
        border-radius: 10px;
        background: #f9fbfc;
    }

    .upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 150px;
        padding: 24px;
        text-align: center;
        cursor: pointer;
    }

    .upload-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        margin-bottom: 10px;
        border-radius: 50%;
        background: #dff7fb;
        color: #006fae;
        font-size: 24px;
    }

    .upload-title {
        margin-bottom: 5px;
        color: #12304a;
        font-size: 13px;
        font-weight: 700;
    }

    .upload-description {
        color: #668096;
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

    .image-preview {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-top: 16px;
    }

    .preview-card {
        overflow: hidden;
        border: 1px solid #d9e5ed;
        border-radius: 8px;
    }

    .preview-image-wrapper {
        aspect-ratio: 16 / 10;
        overflow: hidden;
        background: #f3f7fa;
    }

    .preview-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .preview-footer {
        padding: 7px 8px;
        border-top: 1px solid #edf2f5;
    }

    .preview-name {
        display: block;
        overflow: hidden;
        color: #668096;
        font-size: 10px;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .form-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 18px 24px;
        border-top: 1px solid #edf2f5;
        background: #fafcfd;
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

    .alert-success {
        border: 1px solid #b9dfc8;
        background: #f3fbf6;
        color: #267344;
    }

    .alert-title {
        margin-bottom: 6px;
        font-weight: 700;
    }

    .alert ul {
        margin: 0;
        padding-left: 18px;
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
        .existing-images {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .image-preview {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .page-header {
            flex-direction: column;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .existing-images {
            grid-template-columns: 1fr;
        }

        .image-preview {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const imageInput = document.getElementById('images');
        const imagePreview = document.getElementById('image-preview');
        const uploadError = document.getElementById('image-upload-error');

        const maximumFileSize = 5 * 1024 * 1024;

        if (!imageInput || !imagePreview) {
            return;
        }

        imageInput.addEventListener('change', function () {

            imagePreview.innerHTML = '';

            if (uploadError) {
                uploadError.textContent = '';
                uploadError.style.display = 'none';
            }

            const validFiles = [];

            Array.from(this.files).forEach((file) => {

                if (file.size > maximumFileSize) {

                    if (uploadError) {
                        uploadError.textContent =
                            file.name + ' exceeds the maximum size of 5 MB.';

                        uploadError.style.display = 'block';
                    }

                    return;
                }

                if (!file.type.startsWith('image/')) {

                    if (uploadError) {
                        uploadError.textContent =
                            file.name + ' is not a supported image file.';

                        uploadError.style.display = 'block';
                    }

                    return;
                }

                validFiles.push(file);
            });

            const dataTransfer = new DataTransfer();

            validFiles.forEach(function (file) {
                dataTransfer.items.add(file);
            });

            imageInput.files = dataTransfer.files;

            validFiles.forEach((file) => {

                const reader = new FileReader();

                reader.onload = function (event) {

                    const card = document.createElement('div');
                    card.className = 'preview-card';

                    const wrapper = document.createElement('div');
                    wrapper.className = 'preview-image-wrapper';

                    const image = document.createElement('img');
                    image.src = event.target.result;
                    image.alt = file.name;

                    wrapper.appendChild(image);

                    const footer = document.createElement('div');
                    footer.className = 'preview-footer';

                    const name = document.createElement('span');
                    name.className = 'preview-name';
                    name.textContent = file.name;

                    footer.appendChild(name);

                    card.appendChild(wrapper);
                    card.appendChild(footer);

                    imagePreview.appendChild(card);
                };

                reader.readAsDataURL(file);
            });
        });
    });
</script>
@endpush