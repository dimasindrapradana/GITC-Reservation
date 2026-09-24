@extends('layouts.admin')

@section('title', 'Add Building')
@section('page_title', 'Add Building')

@section('content')

<div class="page-header">
    <div>
        <h2>Add Building</h2>
        <p class="page-description">
            Tambahkan building baru ke master data.
        </p>
    </div>

    <a href="{{ route('buildings.index') }}" class="back-link">
        <span class="back-icon">←</span>
        <span>Back to Buildings</span>
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-error">
        <div class="alert-title">Unable to save building</div>

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
            <h3>Building Information</h3>
            <p>
                Masukkan informasi building yang akan ditambahkan.
            </p>
        </div>
    </div>

    <form action="{{ route('buildings.store') }}" method="POST">
        @csrf

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
                placeholder="Contoh: Building A"
            >

            <small class="form-help">
                Gunakan nama building yang mudah dikenali. Maksimal 50 karakter.
            </small>
        </div>

        <div class="form-divider"></div>

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
        margin: 28px 0 20px;
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