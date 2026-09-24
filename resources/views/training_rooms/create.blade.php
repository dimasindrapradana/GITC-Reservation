@extends('layouts.admin')

@section('title', 'Add Training Room')
@section('page_title', 'Add Training Room')

@section('content')

<div class="page-header">
    <div>
        <a href="{{ route('training-rooms.index') }}" class="back-link">
            ← Back to Media Training
        </a>

        <h2>Add Media Training</h2>

        <p class="page-description">
            Create a new Media Training and upload its images.
        </p>
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

<div class="content-card">

    <div class="card-header">
        <h3>Media Training Information</h3>
        <p>
            Enter the information for the new Media Training.
        </p>
    </div>

    <form
        action="{{ route('training-rooms.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

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
                        <option value="">
                            Select building
                        </option>

                        @foreach ($buildings as $building)
                            <option
                                value="{{ $building->id }}"
                                @selected(
                                    old('building_id') == $building->id
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
                        value="{{ old('name') }}"
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
                        value="{{ old('capacity', 0) }}"
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
                        value="{{ old('simulation_type') }}"
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
                >{{ old('simulation_facilities') }}</textarea>
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

            <div class="section-divider"></div>

            <div class="form-section-header">
                <div>
                    <h4>Media Training Images</h4>
                    <p>
                        Upload up to 10 images for this Media Training.
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
                        JPG, JPEG, PNG or WEBP · Maximum 10 images
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
                Save Media Training
            </button>

        </div>

    </form>

</div>

@endsection

@push('styles')
<style>
    .page-header {
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
        background: #ffffff;
        color: #12304a;
        font-size: 13px;
        outline: none;
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
    }

    .section-divider {
        height: 1px;
        margin: 28px 0;
        background: #edf2f5;
    }

    .form-section-header {
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
        position: relative;
        aspect-ratio: 16 / 10;
        overflow: hidden;
        background: #f3f7fa;
    }

    .preview-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .preview-primary {
        position: absolute;
        top: 8px;
        left: 8px;
        padding: 4px 7px;
        border-radius: 20px;
        background: #dff7fb;
        color: #006fae;
        font-size: 9px;
        font-weight: 700;
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
        .image-preview {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .image-preview {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .form-footer {
            flex-direction: column-reverse;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    const imageInput = document.getElementById('images');
    const imagePreview = document.getElementById('image-preview');

    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function () {
            imagePreview.innerHTML = '';

            Array.from(this.files).forEach((file, index) => {
                if (!file.type.startsWith('image/')) {
                    return;
                }

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

                    if (index === 0) {
                        const primary = document.createElement('span');
                        primary.className = 'preview-primary';
                        primary.textContent = 'Primary Image';
                        wrapper.appendChild(primary);
                    }

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
    }
</script>
@endpush