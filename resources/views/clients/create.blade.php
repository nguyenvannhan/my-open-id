@extends('clients.layout')

@section('client_content')
    <div class="container my-3">
        <h2>{{ __('Create Client') }}</h2>

        <form action="{{ route('clients.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="client-name-input" class="form-label">{{ __('Client Name') }}</label>
                <input type="text" class="form-control" id="client-name-input" name="name">
            </div>

            <div class="mb-3">
                <label for="client-name-input" class="form-label">{{ __('Redirect URI') }}</label>
                <input type="url" class="form-control" id="client-name-input" name="redirect">
            </div>

            <div class="d-flex align-items-center justify-content-center">
                <a class="btn btn-outline-secondary mx-2">{{ __('Back') }}</a>
                <button class="btn btn-primary mx-2">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
@endsection
