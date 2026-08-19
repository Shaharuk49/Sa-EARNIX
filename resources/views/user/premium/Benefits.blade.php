@extends('layouts.user')

@section('title', 'Premium Benefits - SA EarniX')

@section('content')
<style>
    .pb-page {
        background: #0a0a14;
        min-height: 100vh;
        padding: 1.5rem 0.75rem 3rem;
        color: #e8e6f0;
    }
    .pb-wrap {
        max-width: 480px;
        margin: 0 auto;
    }
    .pb-back {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(240,180,41,0.08);
        border: 1px solid rgba(240,180,41,0.25);
        color: var(--theme-accent-light);
        font-size: 0.85rem;
        padding: 0.4rem 1rem;
        border-radius: 999px;
        text-decoration: none;
        margin-bottom: 1.5rem;
    }
    .pb-back:hover { color: #ffce54; background: rgba(240,180,41,0.14); }

    /* ---- Hero card ---- */
    .pb-hero {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(240,180,41,0.16), rgba(201,134,26,0.06)), #14141f;
        border: 1px solid rgba(240,180,41,0.3);
        border-radius: 20px;
        padding: 2rem 1.5rem;
        text-align: center;
        margin-bottom: 1.75rem;
    }
    .pb-hero::before {
        content: "\f521";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        right: -15px;
        bottom: -30px;
        font-size: 9rem;
        color: rgba(240,180,41,0.05);
    }
    .pb-crown-badge {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        border-radius: 18px;
        background: linear-gradient(135deg, var(--theme-accent-light), var(--theme-accent-deep));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        color: #1a1206;
        box-shadow: 0 10px 26px rgba(240,180,41,0.3);
        position: relative;
        z-index: 1;
    }
    .pb-eyebrow {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--theme-accent-light);
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    .pb-title {
        position: relative;
        z-index: 1;
        color: #f5c451;
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 0.2rem;
    }
    .pb-price {
        position: relative;
        z-index: 1;
        color: #f8d78a;
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 0.15rem;
    }
    .pb-subtitle {
        position: relative;
        z-index: 1;
        color: #9a97ad;
        font-size: 0.85rem;
        margin-bottom: 1.4rem;
    }
    .pb-cta-btn {
        position: relative;
        z-index: 1;
        display: inline-block;
        width: 100%;
        background: linear-gradient(135deg, var(--theme-accent), var(--theme-accent-mid));
        border: none;
        color: #1a1206 !important;
        font-weight: 700;
        font-size: 1rem;
        padding: 0.85rem;
        border-radius: 14px;
        box-shadow: 0 10px 24px rgba(240,180,41,0.22);
        text-decoration: none;
    }
    .pb-cta-btn:hover { filter: brightness(1.05); color: #1a1206 !important; }

    /* ---- Section label ---- */
    .pb-section-label {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--theme-accent-light);
        font-weight: 700;
        margin: 0 0.1rem 0.85rem;
    }

    /* ---- Feature cards ---- */
    .pb-feature-card {
        background: #14141f;
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 16px;
        padding: 1.1rem 1rem;
        height: 100%;
        transition: border-color .15s ease;
    }
    .pb-feature-card:hover { border-color: rgba(240,180,41,0.3); }
    .pb-feature-icon {
        width: 42px;
        height: 42px;
        margin-bottom: 0.75rem;
        border-radius: 12px;
        background: rgba(240,180,41,0.1);
        border: 1px solid rgba(240,180,41,0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--theme-accent-light);
        font-size: 1.05rem;
    }
    .pb-feature-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #e8e6f0;
        margin-bottom: 0.3rem;
    }
    .pb-feature-tag {
        display: inline-block;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.15rem 0.55rem;
        border-radius: 999px;
    }
    .pb-tag-blue   { color: #60a5fa; background: rgba(96,165,250,0.12); }
    .pb-tag-green  { color: #4ade80; background: rgba(74,222,128,0.12); }
    .pb-tag-purple { color: #c084fc; background: rgba(192,132,252,0.12); }
    .pb-tag-red    { color: #f87171; background: rgba(248,113,113,0.12); }
    .pb-tag-orange { color: #fb923c; background: rgba(251,146,60,0.12); }

    /* ---- Bottom note ---- */
    .pb-note {
        display: flex;
        gap: 0.6rem;
        align-items: flex-start;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 14px;
        padding: 0.9rem 1rem;
        font-size: 0.8rem;
        color: #9a97ad;
        margin-top: 1.5rem;
    }
    .pb-note i { color: var(--theme-accent-light); margin-top: 0.15rem; }

    .pb-bottom-cta {
        margin-top: 1.5rem;
    }
</style>

<div class="pb-page">
    <div class="pb-wrap">

        <a href="{{ route('user.home') }}" class="pb-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>

        {{-- Hero --}}
        <div class="pb-hero">
            <div class="pb-crown-badge"><i class="fas fa-crown"></i></div>
            <div class="pb-eyebrow"><i class="fas fa-star"></i> SA EarniX Premium</div>
            <div class="pb-title">Become a Premium Member</div>
            <div class="pb-price">৳{{ number_format($amount, 0) }}</div>
            <div class="pb-subtitle">One-time payment &mdash; unlock every course and income opportunity</div>
            <a href="{{ route('premium.upgrade.show') }}" class="pb-cta-btn">
                <i class="fas fa-bolt me-1"></i> Upgrade Now
            </a>
        </div>

        {{-- Feature grid --}}
        <div class="pb-section-label">
            <i class="fas fa-layer-group"></i> Income Opportunities
        </div>

        <div class="row g-3 mb-2">
            <div class="col-6">
                <div class="pb-feature-card">
                    <div class="pb-feature-icon"><i class="fas fa-laptop-code"></i></div>
                    <div class="pb-feature-title">Freelancing</div>
                    <span class="pb-feature-tag pb-tag-blue">200+ Courses</span>
                </div>
            </div>
            <div class="col-6">
                <div class="pb-feature-card">
                    <div class="pb-feature-icon"><i class="fas fa-cart-shopping"></i></div>
                    <div class="pb-feature-title">E-commerce Business</div>
                    <span class="pb-feature-tag pb-tag-green">Full Course</span>
                </div>
            </div>
            <div class="col-6">
                <div class="pb-feature-card">
                    <div class="pb-feature-icon"><i class="fas fa-box-open"></i></div>
                    <div class="pb-feature-title">Dropshipping &amp; POD</div>
                    <span class="pb-feature-tag pb-tag-green">Full Course</span>
                </div>
            </div>
            <div class="col-6">
                <div class="pb-feature-card">
                    <div class="pb-feature-icon"><i class="fas fa-chart-line"></i></div>
                    <div class="pb-feature-title">Amazon FBA</div>
                    <span class="pb-feature-tag pb-tag-purple">Marketing</span>
                </div>
            </div>
            <div class="col-6">
                <div class="pb-feature-card">
                    <div class="pb-feature-icon"><i class="fas fa-palette"></i></div>
                    <div class="pb-feature-title">Digital Product</div>
                    <span class="pb-feature-tag pb-tag-red">Business</span>
                </div>
            </div>
            <div class="col-6">
                <div class="pb-feature-card">
                    <div class="pb-feature-icon"><i class="fas fa-mobile-screen"></i></div>
                    <div class="pb-feature-title">Micro Job</div>
                    <span class="pb-feature-tag pb-tag-orange">Upcoming</span>
                </div>
            </div>
            <div class="col-12">
                <div class="pb-feature-card">
                    <div class="pb-feature-icon"><i class="fas fa-satellite-dish"></i></div>
                    <div class="pb-feature-title">Telecom Business</div>
                    <span class="pb-feature-tag pb-tag-blue">Full Course</span>
                </div>
            </div>
        </div>

        <div class="pb-note">
            <i class="fas fa-circle-info"></i>
            <div>Premium unlocks all courses and income tracks above, plus priority support. Your upgrade is activated as soon as your payment is confirmed.</div>
        </div>

        <div class="pb-bottom-cta">
            <a href="{{ route('premium.upgrade.show') }}" class="pb-cta-btn">
                <i class="fas fa-bolt me-1"></i> Upgrade for ৳{{ number_format($amount, 0) }}
            </a>
        </div>

    </div>
</div>
@endsection