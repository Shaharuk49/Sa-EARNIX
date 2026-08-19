@extends('layouts.auth')
@section('title', 'Pending Approval')

@section('content')
<div class="pay-page">
    <div class="pay-topbar">
        <a href="{{ route('login') }}" class="pay-back" aria-label="Go back">
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
                <i class="fas fa-hourglass-half"></i>
                Approval in progress
            </div>
            <h1>Payment Submitted</h1>
            <p class="pay-subtitle">আপনার registration এখন review queue-তে আছে</p>
        </div>

        <div class="pay-body pay-body-center">

            <div class="pay-metric-icon">
                <i class="fas fa-clock fa-xl"></i>
            </div>

            <h4 class="pay-pending-title">Admin approval pending</h4>
            <p class="pay-pending-text">আপনার payment verify হওয়ার পর account active হবে, তারপর login করতে পারবেন।</p>

            <div class="pay-alert pay-alert-warning pay-alert-left">
                <div class="pay-alert-title"><i class="fas fa-circle-info me-2"></i>পরবর্তী ধাপ</div>
                <ol class="pay-alert-list">
                    <li>Admin আপনার payment verify করবে</li>
                    <li>Approval এর পর account activate হবে</li>
                    <li>তারপর username এবং password দিয়ে login করতে পারবেন</li>
                </ol>
            </div>

            <p class="pay-eta-note">সাধারণত <strong>1-24 ঘন্টার</strong> মধ্যে approval দেওয়া হয়।</p>

            <div class="pay-actions">
                <a href="{{ route('login') }}" class="pay-submit">
                    <i class="fas fa-arrow-right-to-bracket"></i>
                    Login পেজে যান
                </a>
                <a href="{{ route('register') }}" class="pay-secondary-btn">
                    নতুন account register করুন
                </a>
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

    .pay-body{ display:flex; flex-direction:column; gap: 18px; }
    .pay-body-center{ align-items:center; text-align:center; }

    .pay-metric-icon{
        width: 72px; height: 72px;
        border-radius: 999px;
        display:flex; align-items:center; justify-content:center;
        background: rgba(240,180,41,.12);
        border: 1px solid rgba(240,180,41,.3);
        color: var(--pay-gold);
        margin-bottom: 4px;
    }

    .pay-pending-title{
        font-weight: 800;
        font-size: 1.1rem;
        margin: 0;
    }
    .pay-pending-text{
        color: var(--pay-text-dim);
        font-size: .9rem;
        margin: 0;
        max-width: 340px;
    }

    .pay-alert{ border-radius: var(--pay-radius-md); padding: 14px 18px; font-size:.9rem; width:100%; }
    .pay-alert-left{ text-align:left; }
    .pay-alert-warning{
        background: rgba(240,180,41,.12);
        border: 1px solid rgba(240,180,41,.35);
        color: #f4d791;
    }
    .pay-alert-title{ font-weight:700; margin-bottom: 8px; color: var(--pay-gold); }
    .pay-alert-list{ margin: 0; padding-left: 18px; color: var(--pay-text); }
    .pay-alert-list li{ margin-bottom: 4px; }
    .pay-alert-list li:last-child{ margin-bottom: 0; }

    .pay-eta-note{
        color: var(--pay-text-dim);
        font-size: .82rem;
        margin: 0;
    }
    .pay-eta-note strong{ color: var(--pay-gold-soft); }

    .pay-actions{
        display:flex;
        flex-direction:column;
        gap: 10px;
        width: 100%;
        margin-top: 6px;
    }

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
        text-decoration:none;
        transition: transform .1s ease, box-shadow .15s ease;
        box-shadow: 0 10px 24px -8px rgba(240,180,41,.5);
    }
    .pay-submit:hover{ transform: translateY(-1px); color: var(--pay-bg-1); }
    .pay-submit:active{ transform: translateY(0); }

    .pay-secondary-btn{
        display:flex; align-items:center; justify-content:center;
        width:100%;
        background: transparent;
        color: var(--pay-text);
        border: 1px solid var(--pay-border);
        border-radius: var(--pay-radius-md);
        padding: 14px 18px;
        font-weight: 700;
        font-size: .92rem;
        text-decoration:none;
        transition: border-color .15s ease, background .15s ease;
    }
    .pay-secondary-btn:hover{
        border-color: var(--pay-gold);
        background: rgba(240,180,41,.06);
        color: var(--pay-text);
    }

    /* ---------- Tablet ---------- */
    @media (min-width: 640px){
        .pay-card{
            background: var(--pay-panel);
            border: 1px solid var(--pay-border);
            border-radius: 28px;
            padding: 28px 32px 32px;
            margin-top: 10px;
            box-shadow: 0 30px 60px -20px rgba(0,0,0,.5);
        }
    }

    /* ---------- Desktop ---------- */
    @media (min-width: 992px){
        .pay-page{ padding-top: 40px; }
        .pay-topbar{ max-width: 560px; }
        .pay-card{ max-width: 560px; padding: 32px 40px 36px; }
        .pay-header h1{ font-size: 2.1rem; }
        .pay-submit, .pay-secondary-btn{ padding: 17px 18px; }
    }

    @media (prefers-reduced-motion: reduce){
        .pay-submit, .pay-back, .pay-secondary-btn{ transition:none; }
    }
</style>
@endsection