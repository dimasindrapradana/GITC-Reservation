@extends('layouts.admin')

@section('title', 'Edit Building')
@section('page_title', 'Edit Building')

@section('content')

<div class="building-edit-wrapper">

    {{-- PAGE HEADER --}}
    <div class="page-header">

        <div class="page-header-content">

            <h2>
                Edit Building
            </h2>

            <p class="page-description">
                Update building information and manage building images.
            </p>

        </div>

        <div class="header-actions">

            <a
                href="{{ route('buildings.index') }}"
                class="btn btn-secondary"
            >
                Back
            </a>

        </div>

    </div>


    {{-- VALIDATION ERROR --}}
    @if ($errors->any())

        <div class="alert alert-error">

            <div class="alert-title">
                Please check the following errors:
            </div>

            <ul>
                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach
            </ul>

        </div>

    @endif


    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    <div class="content-card">

        {{-- CARD HEADER --}}
        <div class="card-header">

            <div class="form-card-icon">
                ▤
            </div>

            <div>

                <h3>
                    Building Information
                </h3>

                <p>
                    Update the basic information and images for this building.
                </p>

            </div>

        </div>


        {{-- MAIN EDIT FORM --}}
        <form
            action="{{ route('buildings.update', $building) }}"
            method="POST"
            enctype="multipart/form-data"
            id="building-edit-form"
        >

            @csrf
            @method('PUT')


            <div class="form-body">

                {{-- BUILDING NAME --}}
                <div class="form-group">

                    <label
                        for="name"
                        class="form-label"
                    >
                        Building Name
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control"
                        value="{{ old('name', $building->name) }}"
                        maxlength="50"
                        required
                        autofocus
                    >

                    <small class="form-help">
                        Use a clear and recognizable building name. Maximum 50 characters.
                    </small>

                    @error('name')

                        <div class="form-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- DIVIDER --}}
                <div class="section-divider"></div>


                {{-- ADD NEW IMAGES --}}
                <div class="form-section-header">

                    <div>

                        <h4>
                            Add New Images
                        </h4>

                        <p>
                            Upload additional images for this building.
                        </p>

                    </div>

                </div>


                <div class="upload-area">

                    <label
                        for="building-images"
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
                        id="building-images"
                        accept=".jpg,.jpeg,.png,.webp"
                        multiple
                    >

                </div>


                {{-- UPLOAD ERROR --}}
                <div
                    id="image-upload-error"
                    class="upload-error"
                    role="alert"
                ></div>


                {{-- NEW IMAGE PREVIEW --}}
                <div
                    id="image-preview"
                    class="image-preview"
                ></div>


                @error('images')

                    <div class="form-error">
                        {{ $message }}
                    </div>

                @enderror

                @error('images.*')

                    <div class="form-error">
                        {{ $message }}
                    </div>

                @enderror


                {{-- IMAGE COUNTER --}}
                <div class="image-counter">

                    <span>
                        Existing images:
                        <strong>
                            {{ $building->images->count() }}
                        </strong>
                        / 10
                    </span>

                    <span>
                        Maximum 10 images in total.
                    </span>

                </div>

            </div>


            {{-- FORM FOOTER --}}
            <div class="form-footer">

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
                    Save Changes
                </button>

            </div>

        </form>


        {{-- EXISTING IMAGES --}}
        <div class="existing-images-section">

            <div class="section-divider"></div>


            <div class="form-section-header">

                <div>

                    <h4>
                        Existing Images
                    </h4>

                    <p>
                        Manage images currently assigned to this building.
                    </p>

                </div>

                <span class="image-count">
                    {{ $building->images->count() }} image(s)
                </span>

            </div>


            @if ($building->images->count())

                <div class="existing-images">

                    @foreach (
                        $building->images->sortBy('sort_order')
                        as $image
                    )

                        <div class="existing-image-card">

                            {{-- IMAGE --}}
                            <div class="existing-image-wrapper">

                                <img
                                    src="{{ asset('storage/' . $image->file) }}"
                                    alt="{{ $building->name }} image {{ $loop->iteration }}"
                                >

                                @if ($loop->first)

                                    <span class="primary-badge">
                                        Primary Image
                                    </span>

                                @endif

                            </div>


                            {{-- IMAGE FOOTER --}}
                            <div class="existing-image-footer">

                                <span class="image-name">
                                    Image {{ $loop->iteration }}
                                </span>


                                {{-- DELETE IMAGE ONLY --}}
                                <form
                                    action="{{ route('buildings.images.destroy', [$building, $image->id]) }}"
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

                    <h4>
                        No Images Available
                    </h4>

                    <p>
                        No images have been uploaded for this building yet.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection


@push('styles')

<style>

    /* =====================================================
       PAGE
    ===================================================== */

    .building-edit-wrapper {
        max-width: 1100px;
    }


    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }


    .page-header-content {
        min-width: 0;
    }


    .back-link {
        display: inline-block;
        margin-bottom: 10px;
        color: #006fae;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }


    .back-link:hover {
        text-decoration: underline;
    }


    .page-header-content h2 {
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


    .header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }


    /* =====================================================
       CARD
    ===================================================== */

    .content-card {
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #d9e5ed;
        border-radius: 12px;
    }


    .card-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 20px 24px;
        border-bottom: 1px solid #edf2f5;
    }


    .form-card-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 42px;
        border-radius: 10px;
        background: #dff7fb;
        color: #006fae;
        font-size: 18px;
        font-weight: 800;
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


    /* =====================================================
       FORM
    ===================================================== */

    .form-body {
        padding: 24px;
    }


    .form-group {
        margin-bottom: 18px;
    }


    .form-label {
        display: block;
        margin-bottom: 7px;
        color: #12304a;
        font-size: 12px;
        font-weight: 700;
    }


    .required {
        color: #c62828;
    }


    .form-control {
        width: 100%;
        min-height: 42px;
        padding: 0 12px;
        box-sizing: border-box;
        border: 1px solid #ccd9e3;
        border-radius: 8px;
        background: #ffffff;
        color: #12304a;
        font-size: 13px;
        outline: none;
    }


    .form-control:focus {
        border-color: #006fae;
        box-shadow: 0 0 0 3px rgba(0, 111, 174, .08);
    }


    .form-help {
        display: block;
        margin-top: 7px;
        color: #668096;
        font-size: 11px;
        line-height: 1.5;
    }


    .form-error {
        margin-top: 7px;
        color: #b33a3a;
        font-size: 12px;
    }


    .section-divider {
        height: 1px;
        margin: 28px 0;
        background: #edf2f5;
    }


    /* =====================================================
       SECTION HEADER
    ===================================================== */

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


    /* =====================================================
       UPLOAD
    ===================================================== */

    .upload-area {
        border: 1.5px dashed #b8cbd8;
        border-radius: 10px;
        background: #f9fbfc;
        transition:
            border-color .2s ease,
            background .2s ease;
    }


    .upload-area:hover {
        border-color: #006fae;
        background: #f4fafc;
    }


    .upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 150px;
        padding: 24px;
        box-sizing: border-box;
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


    #building-images {
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


    /* =====================================================
       NEW IMAGE PREVIEW
    ===================================================== */

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
        background: #ffffff;
    }


    .preview-image-wrapper {
        aspect-ratio: 16 / 10;
        overflow: hidden;
        background: #f3f7fa;
    }


    .preview-image-wrapper img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }


    .preview-footer {
        min-height: 38px;
        padding: 6px 8px;
        box-sizing: border-box;
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


    /* =====================================================
       IMAGE COUNTER
    ===================================================== */

    .image-counter {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-top: 12px;
        color: #668096;
        font-size: 11px;
    }


    .image-counter strong {
        color: #12304a;
    }


    /* =====================================================
       EXISTING IMAGES
    ===================================================== */

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
        white-space: nowrap;
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
        background: #ffffff;
    }


    .existing-image-wrapper {
        position: relative;
        aspect-ratio: 16 / 10;
        overflow: hidden;
        background: #f3f7fa;
    }


    .existing-image-wrapper img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }


    .primary-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 20px;
        background: #dff7fb;
        color: #006fae;
        font-size: 10px;
        font-weight: 700;
    }


    /* =====================================================
       IMAGE FOOTER
       ONE DELETE BUTTON ONLY
    ===================================================== */

    .existing-image-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        min-height: 48px;
        padding: 8px 10px;
        box-sizing: border-box;
        border-top: 1px solid #edf2f5;
    }


    .image-name {
        color: #668096;
        font-size: 11px;
        font-weight: 600;
    }


    .delete-image-form {
        margin: 0;
        padding: 0;
    }


    .btn-delete-image {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 30px;
        padding: 0 12px;
        border: 1px solid #e2b8b8;
        border-radius: 7px;
        background: #fff7f7;
        color: #b33a3a;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: all .2s ease;
    }


    .btn-delete-image:hover {
        background: #ffeaea;
        border-color: #d99898;
    }


    /* =====================================================
       EMPTY IMAGES
    ===================================================== */

    .empty-images {
        padding: 32px 20px;
        border: 1px dashed #ccd9e3;
        border-radius: 10px;
        background: #f9fbfc;
        text-align: center;
    }


    .empty-images-icon {
        margin-bottom: 8px;
        color: #006fae;
        font-size: 28px;
    }


    .empty-images h4 {
        margin: 0 0 5px;
        color: #12304a;
        font-size: 14px;
    }


    .empty-images p {
        margin: 0;
        color: #668096;
        font-size: 12px;
    }


    /* =====================================================
       FORM FOOTER
    ===================================================== */

    .form-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding: 18px 24px;
        border-top: 1px solid #edf2f5;
        background: #fafcfd;
    }


    /* =====================================================
       BUTTONS
    ===================================================== */

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 17px;
        box-sizing: border-box;
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


    .btn-primary:hover {
        background: #003b6f;
        border-color: #003b6f;
    }


    .btn-secondary {
        border: 1px solid #d0dce5;
        background: #ffffff;
        color: #4f6680;
    }


    .btn-secondary:hover {
        background: #f3f7fa;
    }


    /* =====================================================
       ALERT
    ===================================================== */

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


    /* =====================================================
       RESPONSIVE
    ===================================================== */

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


        .header-actions {
            width: 100%;
        }


        .header-actions .btn {
            flex: 1;
        }


        .existing-images {
            grid-template-columns: 1fr;
        }


        .image-preview {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }


        .form-footer {
            flex-direction: column-reverse;
        }


        .form-footer .btn {
            width: 100%;
        }


        .image-counter {
            flex-direction: column;
            align-items: flex-start;
        }

    }


    @media (max-width: 420px) {

        .form-body {
            padding: 18px;
        }


        .existing-images-section {
            padding: 0 18px 18px;
        }


        .card-header {
            padding: 18px;
        }


        .form-footer {
            padding: 16px 18px;
        }


        .image-preview {
            grid-template-columns: 1fr;
        }

    }

</style>

@endpush


@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const imageInput =
        document.getElementById('building-images');

    const imagePreview =
        document.getElementById('image-preview');

    const uploadError =
        document.getElementById('image-upload-error');

    const maximumFileSize =
        5 * 1024 * 1024;

    const maximumImages =
        10;

    const existingImageCount =
        {{ $building->images->count() }};


    if (!imageInput || !imagePreview) {
        return;
    }


    imageInput.addEventListener(
        'change',
        function () {

            imagePreview.innerHTML = '';

            if (uploadError) {

                uploadError.textContent = '';
                uploadError.style.display = 'none';

            }


            const availableSlots =
                Math.max(
                    0,
                    maximumImages - existingImageCount
                );


            const selectedFiles =
                Array.from(this.files);

            const validFiles = [];


            selectedFiles.forEach(
                function (file) {

                    if (
                        file.size >
                        maximumFileSize
                    ) {

                        showUploadError(
                            file.name +
                            ' exceeds the maximum size of 5 MB.'
                        );

                        return;
                    }


                    if (
                        !file.type.startsWith('image/')
                    ) {

                        showUploadError(
                            file.name +
                            ' is not a supported image file.'
                        );

                        return;
                    }


                    validFiles.push(file);

                }
            );


            const limitedFiles =
                validFiles.slice(
                    0,
                    availableSlots
                );


            if (
                validFiles.length >
                availableSlots
            ) {

                showUploadError(
                    'You can only add ' +
                    availableSlots +
                    ' more image(s).'
                );

            }


            const dataTransfer =
                new DataTransfer();


            limitedFiles.forEach(
                function (file) {

                    dataTransfer.items.add(file);

                }
            );


            imageInput.files =
                dataTransfer.files;


            limitedFiles.forEach(
                function (file) {

                    renderPreview(file);

                }
            );

        }
    );


    function showUploadError(message) {

        if (!uploadError) {
            return;
        }

        uploadError.textContent =
            message;

        uploadError.style.display =
            'block';

    }


    function renderPreview(file) {

        const reader =
            new FileReader();


        reader.onload =
            function (event) {

                const card =
                    document.createElement('div');

                card.className =
                    'preview-card';


                const wrapper =
                    document.createElement('div');

                wrapper.className =
                    'preview-image-wrapper';


                const image =
                    document.createElement('img');

                image.src =
                    event.target.result;

                image.alt =
                    file.name;


                wrapper.appendChild(image);


                const footer =
                    document.createElement('div');

                footer.className =
                    'preview-footer';


                const name =
                    document.createElement('span');

                name.className =
                    'preview-name';

                name.textContent =
                    file.name;

                name.title =
                    file.name;


                footer.appendChild(name);


                card.appendChild(wrapper);
                card.appendChild(footer);


                imagePreview.appendChild(card);

            };


        reader.readAsDataURL(file);

    }

});
</script>

@endpush