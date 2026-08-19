@extends('layouts.auth')
@section('title', 'রেজিস্ট্রেশন')

@section('content')
<div class="pay-page">
    <div class="pay-topbar">
        <a href="{{ url()->previous() }}" class="pay-back" aria-label="Go back">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="pay-brand">
            <img class="site-logo pay-small" src="{{ asset('images/logo.png') }}" alt="SA EarniX">
        </div>
        <span class="pay-topbar-spacer"></span>
    </div>

    <div class="pay-card" data-reveal>

        <div class="pay-header">
            <div class="pay-kicker">
                <i class="fas fa-sparkles"></i>
                New member onboarding
            </div>
            <h1>রেজিস্ট্রেশন</h1>
            <p class="pay-subtitle">নতুন Account তৈরি করুন</p>
        </div>

        {{-- Step Indicator --}}
        <div class="pay-stepper">
            <div class="pay-step is-active">
                <span class="pay-step-badge">1</span>
                <span class="pay-step-label">তথ্য পূরণ</span>
            </div>
            <div class="pay-step is-upcoming">
                <span class="pay-step-badge">2</span>
                <span class="pay-step-label">Payment</span>
            </div>
            <div class="pay-step is-upcoming">
                <span class="pay-step-badge">3</span>
                <span class="pay-step-label">সম্পন্ন</span>
            </div>
        </div>

        <div class="pay-body">

            @if(session('error'))
                <div class="pay-alert pay-alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="pay-alert pay-alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" class="pay-form">
                @csrf

                {{-- Referral notice --}}
                @if(isset($upline) && $upline)
                    <div class="pay-alert pay-alert-info">
                        <i class="fas fa-user-check me-2"></i>
                        <strong>{{ $upline->name }}</strong> এর Referral দিয়ে join করছেন।
                    </div>
                @endif

                <div class="pay-field">
                    <label class="pay-label" for="reg-name">পুরো নাম</label>
                    <div class="pay-input-wrap">
                        <i class="fas fa-user pay-input-icon"></i>
                        <input id="reg-name" type="text" name="name" class="pay-control" placeholder="আপনার পুরো নাম লিখুন"
                           value="{{ old('name') }}" required>
                    </div>
                </div>

                <div class="pay-field">
                    <label class="pay-label" for="reg-username">Username</label>
                    <div class="pay-input-wrap">
                        <i class="fas fa-at pay-input-icon"></i>
                        <input id="reg-username" type="text" name="username" class="pay-control" placeholder="ইউনিক username বেছে নিন"
                           value="{{ old('username') }}" required>
                    </div>
                    <small class="pay-hint">শুধুমাত্র অক্ষর, সংখ্যা এবং underscore (_) ব্যবহার করুন।</small>
                </div>

                <div class="pay-field">
                    <label class="pay-label" for="reg-password">Password</label>
                    <div class="pay-input-wrap">
                        <i class="fas fa-lock pay-input-icon"></i>
                        <input id="reg-password" type="password" name="password" class="pay-control pay-control-with-toggle" placeholder="কমপক্ষে ৬ অক্ষর" required>
                        <button type="button" class="pay-toggle-pass" data-toggle-password="#reg-password" aria-label="Toggle password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="pay-field">
                    <label class="pay-label" for="reg-password-confirm">Password নিশ্চিত করুন</label>
                    <div class="pay-input-wrap">
                        <i class="fas fa-lock pay-input-icon"></i>
                        <input id="reg-password-confirm" type="password" name="password_confirmation" class="pay-control pay-control-with-toggle" placeholder="একই password আবার দিন" required>
                        <button type="button" class="pay-toggle-pass" data-toggle-password="#reg-password-confirm" aria-label="Toggle password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="pay-field">
                    <label class="pay-label" for="reg-referral">Referral Code (ঐচ্ছিক)</label>
                    <div class="pay-input-wrap">
                        <i class="fas fa-link pay-input-icon"></i>
                        <input id="reg-referral" type="text" name="referral_code" class="pay-control" placeholder="Referral code থাকলে দিন"
                           value="{{ old('referral_code', $referralCode ?? '') }}">
                    </div>
                </div>

                <div class="pay-alert pay-alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    Registration fee: <strong>৳ ২৫০</strong> — Payment করার পর account active হবে।
                </div>

                <button type="submit" class="pay-submit">
                    <i class="fas fa-arrow-right"></i>
                    Register Now — Payment করুন
                </button>
            </form>

            <div class="pay-divider"></div>

            <div class="pay-login-note">
                ইতিমধ্যে account আছে?
                <a href="{{ route('login') }}" class="pay-login-link">Login করুন</a>
            </div>

        </div>
    </div>
</div>

<style>
    :root{
        --pay-bg-1: #0c0e16;
        --pay-bg-2: #141a2b;
        --pay-panel: #171d2c;
        --pay-panel-alt: #1b2233;
        --pay-border: rgba(255,255,255,.08);
        --pay-gold: var(--theme-accent-light);
        --pay-gold-soft: #e0a51e;
        --pay-text: #f4f6fb;
        --pay-text-dim: #93a0b8;
        --pay-danger: #ef5461;
        --pay-info: #5aa9e6;
        --pay-radius-lg: 22px;
        --pay-radius-md: 14px;
    }

    .pay-page{
        min-height: 100vh;
        background:
            radial-gradient(1200px 600px at 15% -10%, rgba(240,180,41,.08), transparent 55%),
            radial-gradient(900px 500px at 110% 10%, rgba(64,90,180,.14), transparent 60%),
            linear-gradient(180deg, var(--pay-bg-1), var(--pay-bg-2));
        color: var(--pay-text);
        padding: 0 0 48px;
        font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    .pay-topbar{
        display:flex;
        align-items:center;
        gap: 12px;
        padding: 18px 20px;
        max-width: 480px;
        margin: 0 auto;
    }
    .pay-back{
        width: 38px; height: 38px;
        display:flex; align-items:center; justify-content:center;
        border-radius: 999px;
        background: rgba(255,255,255,.05);
        border: 1px solid var(--pay-border);
        color: var(--pay-text);
        text-decoration:none;
        flex-shrink:0;
        transition: background .15s ease;
    }
    .pay-back:hover{ background: rgba(255,255,255,.1); }
    .pay-brand{ display:flex; align-items:center; gap:8px; }
    .pay-brand-mark{
        font-weight: 800;
        color: var(--pay-bg-1);
        background: var(--pay-gold);
        border-radius: 8px;
        padding: 2px 7px;
        font-size: .8rem;
        letter-spacing: .04em;
    }
    .pay-brand-name{ font-weight: 700; letter-spacing: .01em; }
    .pay-topbar-spacer{ flex: 1; }

    .pay-card{
        max-width: 480px;
        margin: 0 auto;
        padding: 4px 20px 0;
    }

    .pay-header{ text-align:center; padding: 6px 6px 20px; }
    .pay-kicker{
        display:inline-flex; align-items:center; gap:8px;
        font-size: .72rem;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--pay-gold);
        background: rgba(240,180,41,.1);
        border: 1px solid rgba(240,180,41,.25);
        border-radius: 999px;
        padding: 6px 14px;
        margin-bottom: 14px;
    }
    .pay-header h1{
        font-size: clamp(1.5rem, 5vw, 1.9rem);
        font-weight: 800;
        margin: 0 0 4px;
        line-height: 1.2;
    }
    .pay-subtitle{ color: var(--pay-text-dim); margin: 0; font-size: .95rem; }

    .pay-stepper{
        display:flex;
        align-items:flex-start;
        justify-content: space-between;
        gap: 6px;
        padding: 4px 4px 26px;
    }
    .pay-step{
        flex:1;
        display:flex;
        flex-direction:column;
        align-items:center;
        gap:8px;
        text-align:center;
    }
    .pay-step-badge{
        width: 34px; height:34px;
        border-radius: 999px;
        display:flex; align-items:center; justify-content:center;
        font-weight:700;
        font-size:.9rem;
        background: rgba(255,255,255,.06);
        color: var(--pay-text-dim);
        border: 1px solid var(--pay-border);
    }
    .pay-step.is-done .pay-step-badge{
        background: rgba(60,190,120,.15);
        border-color: rgba(60,190,120,.4);
        color: #4ade80;
    }
    .pay-step.is-active .pay-step-badge{
        background: var(--pay-gold);
        border-color: var(--pay-gold);
        color: var(--pay-bg-1);
        box-shadow: 0 0 0 5px rgba(240,180,41,.15);
    }
    .pay-step-label{ font-size: .75rem; color: var(--pay-text-dim); }
    .pay-step.is-done .pay-step-label{ color: #4ade80; }
    .pay-step.is-active .pay-step-label{ color: var(--pay-gold); font-weight:700; }

    .pay-body{ display:flex; flex-direction:column; gap: 18px; }

    .pay-alert{ border-radius: var(--pay-radius-md); padding: 12px 16px; font-size:.9rem; }
    .pay-alert-danger{
        background: rgba(239,84,97,.12);
        border: 1px solid rgba(239,84,97,.35);
        color: #fda4ac;
    }
    .pay-alert-info{
        background: rgba(90,169,230,.12);
        border: 1px solid rgba(90,169,230,.35);
        color: #a9d2f2;
    }
    .pay-alert-warning{
        background: rgba(240,180,41,.12);
        border: 1px solid rgba(240,180,41,.35);
        color: #f4d791;
    }

    .pay-form{ display:flex; flex-direction:column; gap: 16px; }
    .pay-field{ display:flex; flex-direction:column; gap:6px; }
    .pay-label{
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing:.06em;
        color: var(--pay-text-dim);
        font-weight:700;
    }
    .pay-input-wrap{ position: relative; display:flex; align-items:center; }
    .pay-input-icon{
        position:absolute; left:16px;
        color: var(--pay-text-dim);
        font-size:.9rem;
        pointer-events:none;
    }
    .pay-select, .pay-control{
        width:100%;
        background: var(--pay-panel);
        border: 1px solid var(--pay-border);
        color: var(--pay-text);
        border-radius: var(--pay-radius-md);
        padding: 14px 16px 14px 44px;
        font-size: .95rem;
        appearance: none;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .pay-control-with-toggle{ padding-right: 46px; }
    .pay-select:focus, .pay-control:focus{
        outline: none;
        border-color: var(--pay-gold);
        box-shadow: 0 0 0 3px rgba(240,180,41,.18);
    }
    .pay-control::placeholder{ color: rgba(147,160,184,.6); }
    .pay-hint{ font-size: .72rem; color: var(--pay-text-dim); }

    .pay-toggle-pass{
        position:absolute; right:14px;
        background:none; border:none;
        color: var(--pay-text-dim);
        cursor:pointer;
        padding: 4px;
        font-size: .95rem;
        display:flex; align-items:center;
    }
    .pay-toggle-pass:hover{ color: var(--pay-gold); }

    .pay-submit{
        display:flex; align-items:center; justify-content:center; gap:10px;
        width:100%;
        background: linear-gradient(135deg, var(--pay-gold), var(--pay-gold-soft));
        color: var(--pay-bg-1);
        border:none;
        border-radius: var(--pay-radius-md);
        padding: 16px 18px;
        font-weight: 800;
        font-size: .98rem;
        cursor:pointer;
        margin-top: 4px;
        transition: transform .1s ease, box-shadow .15s ease;
        box-shadow: 0 10px 24px -8px rgba(240,180,41,.5);
    }
    .pay-submit:hover{ transform: translateY(-1px); }
    .pay-submit:active{ transform: translateY(0); }

    .pay-divider{
        height:1px;
        background: var(--pay-border);
        margin: 4px 0;
    }

    .pay-login-note{
        text-align:center;
        color: var(--pay-text-dim);
        font-size: .85rem;
        padding-bottom: 8px;
    }
    .pay-login-link{
        color: var(--pay-gold);
        font-weight:700;
        text-decoration:none;
        margin-left: 4px;
    }
    .pay-login-link:hover{ text-decoration:underline; }

    /* ---------- Tablet ---------- */
    @media (min-width: 640px){
        .pay-card{
            background: var(--pay-panel);
            border: 1px solid var(--pay-border);
            border-radius: 28px;
            padding: 8px 32px 8px;
            margin-top: 10px;
            box-shadow: 0 30px 60px -20px rgba(0,0,0,.5);
        }
    }

    /* ---------- Desktop ---------- */
    @media (min-width: 992px){
        .pay-page{ padding-top: 40px; }
        .pay-topbar{ max-width: 560px; }
        .pay-card{ max-width: 560px; padding: 24px 40px 8px; }
        .pay-header h1{ font-size: 2.1rem; }
        .pay-submit{ padding: 17px 18px; }
    }

    @media (prefers-reduced-motion: reduce){
        .pay-submit, .pay-back, .pay-toggle-pass{ transition:none; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.querySelector(btn.getAttribute('data-toggle-password'));
            if (!input) return;
            var icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
});
</script>
@endsection