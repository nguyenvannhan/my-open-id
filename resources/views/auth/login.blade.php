@extends('layout')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh">
        <div class="border px-5 py-5 rounded-5" style="max-width: 50%; min-width: 30rem;">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="loginName">Email</label>
                            <input type="email" id="loginName" class="form-control" name="email" />
                        </div>

                        <!-- Password input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="loginPassword">Password</label>
                            <input type="password" id="loginPassword" class="form-control" name="password" />
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-warning">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <div class="d-grid gap-1">
                            <button type="submit" class="btn btn-primary btn-block mb-4">Sign in</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
