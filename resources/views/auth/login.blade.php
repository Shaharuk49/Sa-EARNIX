@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="auth-card mx-auto my-4" data-reveal>
    <div class="auth-header">
        <div class="auth-kicker mb-3">
            <i class="fas fa-shield-halved"></i>
            Secure member access
        </div>
        <h1>SA EarniX</h1>
        <p>Login to your account dashboard</p>
    </div>

    <div class="auth-body">
        @if($errors->any())
            <div class="alert alert-danger rounded-3">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" class="auth-form">
            @csrf

            <div class="auth-field">
                <label class="auth-label" for="login-username">Username</label>
                <div class="auth-input-wrap">
                    <i class="fas fa-user auth-input-icon"></i>
                    <input id="login-username" type="text" class="form-control auth-control" name="username" value="{{ old('username') }}" placeholder="Enter your username" required>
                </div>
            </div>

            <div class="auth-field">
                <label class="auth-label" for="login-password">Password</label>
                <div class="auth-input-wrap">
                    <i class="fas fa-lock auth-input-icon"></i>
                    <input id="login-password" type="password" class="form-control auth-control with-toggle" name="password" placeholder="Enter your password" required>
                    <button type="button" class="auth-toggle-pass" data-toggle-password="#login-password" aria-label="Toggle password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-auth py-3">
                <i class="fas fa-arrow-right-to-bracket me-2"></i>Login
            </button>
        </form>

        <hr class="my-4">
        <p class="text-muted mb-0 small text-center">
            No account yet?
            <a href="{{ route('register') }}" class="fw-bold text-primary">Register now</a>
        </p>
    </div>
</div>
@endsection