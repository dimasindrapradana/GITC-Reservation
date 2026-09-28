@extends('layouts.admin')

@section('title', 'Add Building')
@section('page_title', 'Add Building')

@section('content')

<div class="page-header">

    <div>
        <h2>Add Building</h2>

        <p class="page-description">
            Add a new building to the master data.
        </p>
    </div>

    <a
        href="{{ route('buildings.index') }}"
        class="back-link"
    >
        <span class="back-icon">←</span>
        <span>Back to Buildings</span>
    </a>

</div>


@if ($errors->any())

    <div class="alert alert-error">

        <div class="alert-title">
            Unable to save building
        </div>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

    </div>

@endif


<div class="content-card form-card">

    <div class="form-card-header">

        <div class="form-card-icon">
            ▤
        </div>

        <div>

            <h3>
                Building Information
            </h3>

            <p>
                Enter the information for the new building.
            </p>

        </div>

    </div>


    <form
        action="{{ route('buildings.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf


        {{-- =====================================================
             BUILDING NAME
        ====================================================== --}}

        <div class="form-group">

            <label for="name">

                Building Name

                <span class="required">*</span>

            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                maxlength="50"
                required
                autofocus
                class="form-control"
                placeholder="Example: Building A"
            >

            <small class="form-help">
                Use a clear and recognizable building name. Maximum 50 characters.
            </small>

        </div>


        <div class="form-divider"></div>


        {{-- =====================================================
             BUILDING IMAGES
        ====================================================== --}}

        <div class="image-section">

            <div class="image-section-header">

                <div>

                    <h3>
                        Building Images
                    </h3>

                    <p>
                        Upload up to 10 images. The first image will be used as the primary image.
                    </p>

                </div>

            </div>


            <label
                for="building-images"
                class="image-upload-box"
                id="image-upload-box"
            >

                <div class="image-upload-icon">
                    +
                </div>

                <strong>
                    Select Building Images
                </strong>

                <span>
                    JPG, JPEG, PNG, or WEBP • Maximum 5 MB each
                </span>

            </label>


            <input
                type="file"
                id="building-images"
                name="images[]"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                multiple
                hidden
            >


            <div
                class="image-preview-grid"
                id="image-preview-grid"
            ></div>


            <div
                class="image-upload-error"
                id="image-upload-error"
            ></div>

        </div>


        <div class="form-divider"></div>


        {{-- =====================================================
             ACTIONS
        ====================================================== --}}

        <div class="form-actions">

            <a
                href="{{ route('buildings.index') }}"
                class="btn btn-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Building
            </button>

        </div>

    </form>

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

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 40px;
        padding: 0 14px;
        border: 1px solid #d9e5ed;
        border-radius: 8px;
        background: #ffffff;
        color: #12304a;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .back-link:hover {
        background: #f3f7fa;
        border-color: #b9cfdd;
        color: #006fae;
    }

    .back-icon {
        font-size: 17px;
        line-height: 1;
    }


    .form-card {
        max-width: 850px;
    }


    .form-card-header {
        display: flex;
        align-items: center;
        gap: 16px;
        padding-bottom: 24px;
        margin-bottom: 24px;
        border-bottom: 1px solid #d9e5ed;
    }

    .form-card-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #dff7fb;
        color: #006fae;
        font-size: 21px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .form-card-header h3 {
        margin: 0 0 4px;
        color: #12304a;
        font-size: 18px;
        font-weight: 700;
    }

    .form-card-header p {
        margin: 0;
        color: #668096;
        font-size: 13px;
    }


    .form-group {
        margin-bottom: 8px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #12304a;
        font-size: 14px;
        font-weight: 600;
    }

    .required {
        color: #ff4d4d;
    }

    .form-control {
        width: 100%;
        box-sizing: border-box;
        padding: 12px 14px;
        border: 1px solid #d9e5ed;
        border-radius: 9px;
        background: #ffffff;
        color: #12304a;
        font-size: 14px;
        outline: none;
        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease;
    }

    .form-control::placeholder {
        color: #9aabba;
    }

    .form-control:focus {
        border-color: #006fae;
        box-shadow: 0 0 0 3px rgba(0, 111, 174, 0.10);
    }

    .form-help {
        display: block;
        margin-top: 8px;
        color: #668096;
        font-size: 12px;
    }


    .form-divider {
        height: 1px;
        background: #d9e5ed;
        margin: 28px 0 24px;
    }


    /* =========================================================
       IMAGE UPLOAD
    ========================================================= */

    .image-section {
        width: 100%;
    }

    .image-section-header {
        margin-bottom: 18px;
    }

    .image-section-header h3 {
        margin: 0 0 5px;
        color: #12304a;
        font-size: 16px;
        font-weight: 700;
    }

    .image-section-header p {
        margin: 0;
        color: #668096;
        font-size: 13px;
        line-height: 1.5;
    }


    .image-upload-box {
        min-height: 185px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 30px;
        border: 1px dashed #c7d9e6;
        border-radius: 12px;
        background: #f8fafc;
        color: #12304a;
        cursor: pointer;
        text-align: center;
        transition:
            background 0.2s ease,
            border-color 0.2s ease,
            transform 0.2s ease;
    }

    .image-upload-box:hover {
        background: #f2f8fb;
        border-color: #79bdd6;
    }

    .image-upload-box.dragover {
        background: #eef9fc;
        border-color: #006fae;
        transform: scale(1.005);
    }


    .image-upload-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2px;
        border: 1px solid #c6ddea;
        border-radius: 50%;
        background: #ffffff;
        color: #006fae;
        font-size: 27px;
        font-weight: 400;
        line-height: 1;
    }

    .image-upload-box strong {
        color: #12304a;
        font-size: 14px;
        font-weight: 700;
    }

    .image-upload-box span {
        color: #668096;
        font-size: 11px;
    }


    /* =========================================================
       PREVIEW GRID
    ========================================================= */

    .image-preview-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-top: 16px;
    }

    .image-preview-item {
        position: relative;
        overflow: hidden;
        aspect-ratio: 4 / 3;
        border: 1px solid #d9e5ed;
        border-radius: 9px;
        background: #f3f7fa;
    }

    .image-preview-item img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
    }

    .image-preview-primary {
        position: absolute;
        left: 8px;
        bottom: 8px;
        padding: 5px 7px;
        border-radius: 5px;
        background: rgba(18, 48, 74, 0.90);
        color: #ffffff;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .image-preview-remove {
        position: absolute;
        top: 7px;
        right: 7px;
        width: 27px;
        height: 27px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border: 0;
        border-radius: 50%;
        background: rgba(18, 48, 74, 0.88);
        color: #ffffff;
        font-size: 17px;
        line-height: 1;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .image-preview-remove:hover {
        background: #d93636;
    }


    .image-upload-error {
        display: none;
        margin-top: 10px;
        padding: 10px 12px;
        border: 1px solid #ffd1d1;
        border-radius: 8px;
        background: #fff4f4;
        color: #a52b2b;
        font-size: 12px;
        line-height: 1.5;
    }

    .image-upload-error.show {
        display: block;
    }


    .form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-primary {
        border: 1px solid #006fae;
        background: #006fae;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(0, 111, 174, 0.15);
    }

    .btn-primary:hover {
        background: #003b6f;
        border-color: #003b6f;
    }

    .btn-secondary {
        border: 1px solid #d9e5ed;
        background: #ffffff;
        color: #12304a;
    }

    .btn-secondary:hover {
        background: #f3f7fa;
        border-color: #b9cfdd;
    }


    .alert {
        max-width: 850px;
        margin-bottom: 18px;
        padding: 14px 16px;
        border-radius: 9px;
        font-size: 13px;
    }

    .alert-error {
        background: #fff2f2;
        border: 1px solid #ffd1d1;
        color: #9f2424;
    }

    .alert-title {
        margin-bottom: 6px;
        font-weight: 700;
    }

    .alert ul {
        margin: 5px 0 0 18px;
        padding: 0;
    }


    @media (max-width: 700px) {

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .back-link {
            width: 100%;
            justify-content: center;
            box-sizing: border-box;
        }

        .image-preview-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .form-actions .btn {
            width: 100%;
        }

    }

</style>

@endpush


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const input =
        document.getElementById('building-images');

    const uploadBox =
        document.getElementById('image-upload-box');

    const previewGrid =
        document.getElementById('image-preview-grid');

    const errorBox =
        document.getElementById('image-upload-error');


    if (
        !input ||
        !uploadBox ||
        !previewGrid ||
        !errorBox
    ) {
        return;
    }


    let selectedFiles = [];


    function showError(message) {

        errorBox.textContent = message;

        errorBox.classList.add('show');

    }


    function clearError() {

        errorBox.textContent = '';

        errorBox.classList.remove('show');

    }


    function renderPreviews() {

        previewGrid.innerHTML = '';


        selectedFiles.forEach(function (file, index) {

            const wrapper =
                document.createElement('div');

            wrapper.className =
                'image-preview-item';


            const image =
                document.createElement('img');

            image.alt =
                'Building image ' + (index + 1);


            const removeButton =
                document.createElement('button');

            removeButton.type =
                'button';

            removeButton.className =
                'image-preview-remove';

            removeButton.innerHTML =
                '&times;';

            removeButton.setAttribute(
                'aria-label',
                'Remove image ' + (index + 1)
            );


            removeButton.addEventListener(
                'click',
                function (event) {

                    event.preventDefault();
                    event.stopPropagation();

                    selectedFiles.splice(index, 1);

                    syncInputFiles();

                    renderPreviews();

                }
            );


            const reader =
                new FileReader();


            reader.onload =
                function (event) {

                    image.src =
                        event.target.result;

                };


            reader.readAsDataURL(file);


            wrapper.appendChild(image);


            if (index === 0) {

                const primary =
                    document.createElement('span');

                primary.className =
                    'image-preview-primary';

                primary.textContent =
                    'Primary Image';

                wrapper.appendChild(primary);

            }


            wrapper.appendChild(
                removeButton
            );


            previewGrid.appendChild(
                wrapper
            );

        });

    }


    function syncInputFiles() {

        const dataTransfer =
            new DataTransfer();


        selectedFiles.forEach(function (file) {

            dataTransfer.items.add(file);

        });


        input.files =
            dataTransfer.files;

    }


    function addFiles(files) {

        clearError();


        const incomingFiles =
            Array.from(files);


        if (
            selectedFiles.length +
            incomingFiles.length >
            10
        ) {

            showError(
                'You can upload a maximum of 10 images.'
            );

            return;

        }


        for (const file of incomingFiles) {

            if (
                ![
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ].includes(file.type)
            ) {

                showError(
                    'Only JPG, JPEG, PNG, and WEBP images are allowed.'
                );

                return;

            }


            if (
                file.size >
                5 * 1024 * 1024
            ) {

                showError(
                    'Each image must be 5 MB or smaller.'
                );

                return;

            }

        }


        selectedFiles =
            selectedFiles.concat(
                incomingFiles
            );


        syncInputFiles();

        renderPreviews();

    }


    input.addEventListener(
        'change',
        function () {

            addFiles(
                input.files
            );

        }
    );


    uploadBox.addEventListener(
        'dragover',
        function (event) {

            event.preventDefault();

            uploadBox.classList.add(
                'dragover'
            );

        }
    );


    uploadBox.addEventListener(
        'dragleave',
        function () {

            uploadBox.classList.remove(
                'dragover'
            );

        }
    );


    uploadBox.addEventListener(
        'drop',
        function (event) {

            event.preventDefault();

            uploadBox.classList.remove(
                'dragover'
            );

            addFiles(
                event.dataTransfer.files
            );

        }
    );

});

</script>

@endpush