@extends('layouts.auth')
@section('title', 'রেজিস্ট্রেশন')

@section('content')
<div class="auth-card mx-auto my-4" data-reveal>
    <div class="auth-header">
        <div class="auth-kicker mb-3">
            <i class="fas fa-sparkles"></i>
            New member onboarding
        </div>
        <h1>MLM Platform</h1>
        <p>নতুন Account তৈরি করুন</p>
    </div>

    {{-- Step Indicator --}}
    <div class="auth-stepper">
        <div class="auth-step">
            <span class="step-badge">1</span>
            <span class="fw-bold text-primary small">তথ্য পূরণ</span>
        </div>
        <div class="auth-step is-dim">
            <span class="step-badge bg-secondary shadow-none">2</span>
            <span class="small text-muted">Payment</span>
        </div>
        <div class="auth-step is-dim">
            <span class="step-badge bg-secondary shadow-none">3</span>
            <span class="small text-muted">সম্পন্ন</span>
        </div>
    </div>

    <div class="auth-body">
        @if(session('error'))
            <div class="alert alert-danger rounded-3">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger rounded-3">
                <i class="fas fa-exclamation-circle me-2"></i>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" class="auth-form">
            @csrf

            {{-- Referral notice --}}
            @if(isset($upline) && $upline)
                <div class="alert alert-info rounded-3 mb-3 py-2">
                    <i class="fas fa-user-check me-2"></i>
                    <strong>{{ $upline->name }}</strong> এর Referral দিয়ে join করছেন।
                </div>
            @endif

            <div class="auth-field">
                <label class="auth-label" for="reg-name">পুরো নাম</label>
                <div class="auth-input-wrap">
                    <i class="fas fa-user auth-input-icon"></i>
                    <input id="reg-name" type="text" name="name" class="form-control auth-control" placeholder="আপনার পুরো নাম লিখুন"
                       value="{{ old('name') }}" required>
                </div>
            </div>

            <div class="auth-field">
                <label class="auth-label" for="reg-username">Username</label>
                <div class="auth-input-wrap">
                    <i class="fas fa-at auth-input-icon"></i>
                    <input id="reg-username" type="text" name="username" class="form-control auth-control" placeholder="ইউনিক username বেছে নিন"
                       value="{{ old('username') }}" required>
                </div>
                <div class="form-text">শুধুমাত্র অক্ষর, সংখ্যা এবং underscore (_) ব্যবহার করুন।</div>
            </div>

            <div class="auth-field">
                <label class="auth-label" for="reg-password">Password</label>
                <div class="auth-input-wrap">
                    <i class="fas fa-lock auth-input-icon"></i>
                    <input id="reg-password" type="password" name="password" class="form-control auth-control with-toggle" placeholder="কমপক্ষে ৬ অক্ষর" required>
                    <button type="button" class="auth-toggle-pass" data-toggle-password="#reg-password" aria-label="Toggle password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="auth-field">
                <label class="auth-label" for="reg-password-confirm">Password নিশ্চিত করুন</label>
                <div class="auth-input-wrap">
                    <i class="fas fa-lock auth-input-icon"></i>
                    <input id="reg-password-confirm" type="password" name="password_confirmation" class="form-control auth-control with-toggle" placeholder="একই password আবার দিন" required>
                    <button type="button" class="auth-toggle-pass" data-toggle-password="#reg-password-confirm" aria-label="Toggle password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="auth-field">
                <label class="auth-label" for="reg-referral">Referral Code (ঐচ্ছিক)</label>
                <div class="auth-input-wrap">
                    <i class="fas fa-link auth-input-icon"></i>
                    <input id="reg-referral" type="text" name="referral_code" class="form-control auth-control" placeholder="Referral code থাকলে দিন"
                       value="{{ old('referral_code', $referralCode ?? '') }}">
                </div>
            </div>

            <div class="alert alert-warning rounded-3 mb-3 py-2 small">
                <i class="fas fa-info-circle me-2"></i>
                Registration fee: <strong>৳ ২৫০</strong> — Payment করার পর account active হবে।
            </div>

            <button type="submit" class="btn btn-auth py-3">
                <i class="fas fa-arrow-right me-2"></i>Register Now — Payment করুন
            </button>
        </form>

        <hr class="my-3">
        <div class="text-center">
            <p class="text-muted mb-0 small">
                ইতিমধ্যে account আছে?
                <a href="{{ route('login') }}" class="text-primary fw-bold">Login করুন</a>
            </p>
        </div>
    </div>
</div>
@endsection