@extends('clients.layout')

@section('client_content')
    <style>
        @media(min-width: 768px) {
            #card-client-created {
                width: 30rem;
            }
        }
        @media(min-width: 1400px) {
            #card-client-created {
                width: 35rem;
            }
        }
    </style>

    <div class="container my-3">
        <div class="d-flex justify-content-center align-items-center">
            <div class="card" id="card-client-created">
                <div class="card-header">
                    {{ __('Created Client') }}
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <p><span class="fw-bold">Name: </span><span>{{ $client['name'] }}</span></p>
                        <p><span class="fw-bold">Redirect URI: </span><span>{{ $client['redirect'] }}</span></p>
                        <p><span class="fw-bold">Client ID: </span><span>{{ $client['id'] }}</span></p>
                        <p><span class="fw-bold">Client Secret: </span><span>{{ $secretHashed ? $client['plainSecret'] : $client['secret'] }}</span></p>
                    </div>

                    @if ($secretHashed)
                        <div class="alert alert-danger text-center">
                            {!! __(
                                'The secret key is hashed after creating. <br><strong>Please save the secret key in other place</strong>',
                            ) !!}
                        </div>
                    @endif
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-center align-items-center">
                        <a href="{{ route('clients.index') }}" class="btn btn-info mx-2">{{ __('Go to Client List') }}</a>
                        <a href="{{ route('clients.create') }}" class="btn btn-primary mx-2">{{ __('Go to Client Creation') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
