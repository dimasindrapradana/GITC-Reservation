@extends('layouts.admin')

@section('title', 'Edit Building')

@section('page_title', 'Edit Building')

@push('styles')
<style>
    .form-card {
        max-width: 650px;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
    }

    .form-body {
        padding: 24px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 7px;
        color: var(--text);
        font-size: 13px;
        font-weight: 600;
    }

    input {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid var(--border);
        border-radius: 8px;
        outline: none;
        color: var(--text);
        background: var(--white);
    }

    input:focus {
        border-color: var(--cyan);
        box-shadow: 0 0 0 3px rgba(0, 168, 200, .10);
    }

    .form-error {
        margin-top: 6px;
        color: #c62828;
        font-size: 12px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        padding-top: 5px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-primary {
        border: 0;
        background: var(--navy);
        color: var(--white);
    }

    .btn-secondary {
        border: 1px solid var(--border);
        background: var(--white);
        color: var(--text);
    }
</style>
@endpush

@section('content')

    <div class="content-header">
        <h2>Edit Building</h2>

        <p>
            Perbarui informasi building.
        </p>
    </div>


    <div class="form-card">

        <div class="form-body">

            <form
                method="POST"
                action="{{ route('buildings.update', $building) }}"
            >

                @csrf
                @method('PUT')

                <div class="form-group">

                    <label for="name">
                        Building Name
                    </label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name', $building->name) }}"
                        maxlength="50"
                        required
                        autofocus
                    >

                    @error('name')
                        <div class="form-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


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
                        Update Building
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection