@extends('layouts.admin')

@section('title', 'Add News')

@section('page_title', 'Add News')

@section('content')

<div class="page-header">

    <div>
        <h2>Add News</h2>

        <p class="page-description">
            Add a new news announcement.
        </p>
    </div>

    <a
        href="{{ route('news.index') }}"
        class="btn btn-secondary"
    >
        Back to News
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
            <h3>News Information</h3>

            <p>
                Enter the information for the new news announcement.
            </p>
        </div>

    </div>

    <form
        action="{{ route('news.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="form"
        id="news-form"
    >

        @csrf

        <div class="form-grid">

            {{-- =====================================================
                 TITLE
            ====================================================== --}}

            <div class="form-group">

                <label for="title">
                    Title
                    <span class="required">*</span>
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    class="form-control"
                    value="{{ old('title') }}"
                    maxlength="255"
                    required
                >

            </div>


            {{-- =====================================================
                 STATUS
            ====================================================== --}}

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

                    <option value="">
                        Select status
                    </option>

                    <option
                        value="PENDING"
                        @selected(old('status') === 'PENDING')
                    >
                        PENDING
                    </option>

                    <option
                        value="SCHEDULED"
                        @selected(old('status') === 'SCHEDULED')
                    >
                        SCHEDULED
                    </option>

                    <option
                        value="PUBLISHED"
                        @selected(old('status') === 'PUBLISHED')
                    >
                        PUBLISHED
                    </option>

                    <option
                        value="EXPIRED"
                        @selected(old('status') === 'EXPIRED')
                    >
                        EXPIRED
                    </option>

                    <option
                        value="CANCELLED"
                        @selected(old('status') === 'CANCELLED')
                    >
                        CANCELLED
                    </option>

                    <option
                        value="REJECTED"
                        @selected(old('status') === 'REJECTED')
                    >
                        REJECTED
                    </option>

                </select>

            </div>


            {{-- =====================================================
                 START DATE
            ====================================================== --}}

            <div class="form-group">

                <label for="start_date">
                    Start Date
                    <span class="required">*</span>
                </label>

                <input
                    type="date"
                    id="start_date"
                    name="start_date"
                    class="form-control"
                    value="{{ old('start_date', old('starts_at') ? \Carbon\Carbon::parse(old('starts_at'))->format('Y-m-d') : '') }}"
                    required
                >

                <span class="field-hint">
                    Select the date when the news starts.
                </span>

            </div>


            {{-- =====================================================
                 START TIME
            ====================================================== --}}

            <div class="form-group">

                <label for="start_time">
                    Start Time
                    <span class="required">*</span>
                </label>

                <input
                    type="time"
                    id="start_time"
                    name="start_time"
                    class="form-control"
                    value="{{ old('start_time', old('starts_at') ? \Carbon\Carbon::parse(old('starts_at'))->format('H:i') : '') }}"
                    required
                >

                <span class="field-hint">
                    Select the time when the news starts.
                </span>

            </div>


            {{-- =====================================================
                 END DATE
            ====================================================== --}}

            <div class="form-group">

                <label for="end_date">
                    End Date
                    <span class="required">*</span>
                </label>

                <input
                    type="date"
                    id="end_date"
                    name="end_date"
                    class="form-control"
                    value="{{ old('end_date', old('ends_at') ? \Carbon\Carbon::parse(old('ends_at'))->format('Y-m-d') : '') }}"
                    required
                >

                <span class="field-hint">
                    Select the date when the news ends.
                </span>

            </div>


            {{-- =====================================================
                 END TIME
            ====================================================== --}}

            <div class="form-group">

                <label for="end_time">
                    End Time
                    <span class="required">*</span>
                </label>

                <input
                    type="time"
                    id="end_time"
                    name="end_time"
                    class="form-control"
                    value="{{ old('end_time', old('ends_at') ? \Carbon\Carbon::parse(old('ends_at'))->format('H:i') : '') }}"
                    required
                >

                <span class="field-hint">
                    Select the time when the news ends.
                </span>

            </div>


            {{-- =====================================================
                 HIDDEN DATETIME VALUES
                 
                 These preserve the existing backend fields:
                 starts_at / ends_at
            ====================================================== --}}

            <input
                type="hidden"
                id="starts_at"
                name="starts_at"
                value="{{ old('starts_at') }}"
            >

            <input
                type="hidden"
                id="ends_at"
                name="ends_at"
                value="{{ old('ends_at') }}"
            >


            {{-- =====================================================
                 CONTENT
            ====================================================== --}}

            <div class="form-group form-group-full">

                <label for="content">
                    Content
                    <span class="required">*</span>
                </label>

                <textarea
                    id="content"
                    name="content"
                    class="form-control textarea-control"
                    rows="8"
                    required
                >{{ old('content') }}</textarea>

            </div>

        </div>


        {{-- =========================================================
             NEWS IMAGES
        ========================================================== --}}

        <div class="image-section">

            <div class="section-title">

                <div>

                    <h3>News Images</h3>

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
                    Select News Images
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
                id="image-upload-error"
                class="upload-error"
                role="alert"
            ></div>

            <div
                id="image-preview"
                class="image-grid"
            ></div>

        </div>


        {{-- =========================================================
             FORM ACTIONS
        ========================================================== --}}

        <div class="form-actions">

            <a
                href="{{ route('news.index') }}"
                class="btn btn-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save News
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


    /* =========================================================
       CONTENT CARD
    ========================================================= */

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


    /* =========================================================
       FORM
    ========================================================= */

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

    .form-group-full {
        grid-column: 1 / -1;
    }

    .form-group label {
        color: #12304a;
        font-size: 13px;
        font-weight: 700;
    }

    .required {
        color: #b33a3a;
    }


    /* =========================================================
       INPUTS
    ========================================================= */

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

    .field-hint {
        color: #8294a2;
        font-size: 11px;
        line-height: 1.4;
    }

    .textarea-control {
        min-height: 180px;
        resize: vertical;
        line-height: 1.6;
    }


    /* =========================================================
       DATE / TIME VISUAL GROUPING
    ========================================================= */

    #start_date,
    #start_time,
    #end_date,
    #end_time {
        cursor: pointer;
    }

    #start_date::-webkit-calendar-picker-indicator,
    #end_date::-webkit-calendar-picker-indicator {
        cursor: pointer;
        opacity: .7;
    }

    #start_time::-webkit-calendar-picker-indicator,
    #end_time::-webkit-calendar-picker-indicator {
        cursor: pointer;
        opacity: .7;
    }


    /* =========================================================
       IMAGES
    ========================================================= */

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


    /* =========================================================
       ACTIONS
    ========================================================= */

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


    /* =========================================================
       ALERT
    ========================================================= */

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


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 800px) {

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group-full {
            grid-column: auto;
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

        const form = document.getElementById('news-form');

        const startDateInput =
            document.getElementById('start_date');

        const startTimeInput =
            document.getElementById('start_time');

        const endDateInput =
            document.getElementById('end_date');

        const endTimeInput =
            document.getElementById('end_time');

        const startsAtInput =
            document.getElementById('starts_at');

        const endsAtInput =
            document.getElementById('ends_at');


        if (
            form &&
            startDateInput &&
            startTimeInput &&
            endDateInput &&
            endTimeInput &&
            startsAtInput &&
            endsAtInput
        ) {

            const combineDateTime = (
                date,
                time
            ) => {

                if (!date || !time) {
                    return '';
                }

                return `${date}T${time}`;

            };


            const updateHiddenValues = () => {

                startsAtInput.value =
                    combineDateTime(
                        startDateInput.value,
                        startTimeInput.value
                    );

                endsAtInput.value =
                    combineDateTime(
                        endDateInput.value,
                        endTimeInput.value
                    );

            };


            const updateEndMinimum = () => {

                if (
                    startDateInput.value &&
                    startTimeInput.value
                ) {

                    endDateInput.min =
                        startDateInput.value;

                }

            };


            startDateInput.addEventListener(
                'change',
                () => {

                    updateHiddenValues();
                    updateEndMinimum();

                }
            );


            startTimeInput.addEventListener(
                'change',
                () => {

                    updateHiddenValues();

                }
            );


            endDateInput.addEventListener(
                'change',
                () => {

                    updateHiddenValues();

                }
            );


            endTimeInput.addEventListener(
                'change',
                () => {

                    updateHiddenValues();

                }
            );


            form.addEventListener(
                'submit',
                (event) => {

                    updateHiddenValues();


                    if (
                        !startsAtInput.value ||
                        !endsAtInput.value
                    ) {

                        event.preventDefault();

                        alert(
                            'Please complete the start and end date and time.'
                        );

                        return;

                    }


                    const start =
                        new Date(
                            startsAtInput.value
                        );

                    const end =
                        new Date(
                            endsAtInput.value
                        );


                    if (
                        Number.isNaN(start.getTime()) ||
                        Number.isNaN(end.getTime())
                    ) {

                        event.preventDefault();

                        alert(
                            'Please enter a valid date and time.'
                        );

                        return;

                    }


                    if (end <= start) {

                        event.preventDefault();

                        alert(
                            'End date and time must be later than the start date and time.'
                        );

                        return;

                    }

                }
            );


            updateHiddenValues();
            updateEndMinimum();

        }

    })();


    /* =========================================================
       IMAGE UPLOAD
    ========================================================= */

    (() => {

        const input =
            document.getElementById('images');

        const preview =
            document.getElementById('image-preview');

        const errorBox =
            document.getElementById(
                'image-upload-error'
            );


        if (!input || !preview || !errorBox) {
            return;
        }


        let selectedFiles = [];


        const maxFiles = 10;

        const maxFileSize =
            5 * 1024 * 1024;


        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp',
        ];


        const updateInput = () => {

            const dataTransfer =
                new DataTransfer();


            selectedFiles.forEach(
                file => {

                    dataTransfer.items.add(
                        file
                    );

                }
            );


            input.files =
                dataTransfer.files;

        };


        const showError = (
            message
        ) => {

            errorBox.textContent =
                message;

            errorBox.style.display =
                'block';

        };


        const clearError = () => {

            errorBox.textContent = '';

            errorBox.style.display =
                'none';

        };


        const renderPreview = () => {

            preview.innerHTML = '';


            selectedFiles.forEach(
                (file, index) => {

                    const wrapper =
                        document.createElement(
                            'div'
                        );

                    wrapper.className =
                        'image-preview';


                    const image =
                        document.createElement(
                            'img'
                        );

                    image.alt =
                        file.name;


                    const badge =
                        document.createElement(
                            'span'
                        );

                    badge.className =
                        'primary-badge';

                    badge.textContent =
                        index === 0
                            ? 'Primary'
                            : `Image ${index + 1}`;


                    const remove =
                        document.createElement(
                            'button'
                        );


                    remove.type =
                        'button';

                    remove.className =
                        'remove-preview';

                    remove.textContent =
                        '×';


                    remove.setAttribute(
                        'aria-label',
                        `Remove ${file.name}`
                    );


                    remove.addEventListener(
                        'click',
                        () => {

                            selectedFiles.splice(
                                index,
                                1
                            );

                            updateInput();
                            renderPreview();

                        }
                    );


                    const reader =
                        new FileReader();


                    reader.addEventListener(
                        'load',
                        () => {

                            image.src =
                                reader.result;

                        }
                    );


                    reader.readAsDataURL(
                        file
                    );


                    wrapper.appendChild(
                        image
                    );

                    wrapper.appendChild(
                        badge
                    );

                    wrapper.appendChild(
                        remove
                    );


                    preview.appendChild(
                        wrapper
                    );

                }
            );

        };


        input.addEventListener(
            'change',
            () => {

                clearError();


                const incomingFiles =
                    Array.from(
                        input.files
                    );


                for (
                    const file
                    of incomingFiles
                ) {

                    if (
                        !allowedTypes.includes(
                            file.type
                        )
                    ) {

                        showError(
                            'Only JPG, JPEG, PNG, or WEBP images are allowed.'
                        );

                        return;

                    }


                    if (
                        file.size >
                        maxFileSize
                    ) {

                        showError(
                            'Each image must not exceed 5 MB.'
                        );

                        return;

                    }

                }


                const combinedFiles = [
                    ...selectedFiles,
                    ...incomingFiles,
                ];


                const uniqueFiles = [];

                const signatures =
                    new Set();


                for (
                    const file
                    of combinedFiles
                ) {

                    const signature =
                        `${file.name}-${file.size}-${file.lastModified}`;


                    if (
                        !signatures.has(
                            signature
                        )
                    ) {

                        signatures.add(
                            signature
                        );

                        uniqueFiles.push(
                            file
                        );

                    }

                }


                if (
                    uniqueFiles.length >
                    maxFiles
                ) {

                    showError(
                        'You can upload a maximum of 10 images.'
                    );

                    return;

                }


                selectedFiles =
                    uniqueFiles;


                updateInput();
                renderPreview();

            }
        );

    })();

</script>

@endpush