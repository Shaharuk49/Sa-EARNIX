@extends('layouts.user')

@section('title', 'Home - SA EarniX')

@section('content')
<style>
    .sax-page { background:var(--theme-dark-page); min-height:100vh; padding:1.25rem 0 3rem; color:var(--theme-dark-text); }
    .sax-page .container { max-width:520px; padding-left:1rem; padding-right:1rem; }
    @media (min-width:576px)  { .sax-page .container { max-width:540px; } }
    @media (min-width:768px)  { .sax-page .container { max-width:700px; } }
    @media (min-width:992px)  { .sax-page .container { max-width:900px; } }
    @media (min-width:1200px) { .sax-page .container { max-width:1040px; } }
    @media (min-width:768px)  { .sax-page { padding:2rem 0 4rem; } }

    /* ---- Profile ---- */
    .sax-profile-card {
        position:relative;
        background:var(--theme-dark-card); border:1px solid rgba(255,255,255,0.06);
        border-radius:20px; padding:1.25rem;
        margin-bottom:1rem;
    }
    .sax-avatar-ring {
        position:relative;
        width:58px; height:58px; border-radius:50%; flex-shrink:0;
        background:linear-gradient(135deg,#5b4bb0,#3c2f80);
        display:flex; align-items:center; justify-content:center;
        color:#fff; font-weight:700; font-size:1.3rem;
    }
    @media (min-width:768px) {
        .sax-avatar-ring { width:68px; height:68px; font-size:1.5rem; }
        .sax-profile-card { padding:1.5rem; }
        .sax-profile-name { font-size:1.3rem; }
    }
    .sax-avatar-ring img { width:100%; height:100%; border-radius:50%; object-fit:cover; }
    .sax-avatar-dot {
        position:absolute; right:-1px; bottom:-1px;
        width:16px; height:16px; border-radius:50%;
        background:#22c55e; border:2px solid #14141f;
    }
    .sax-card-soft-label {
        font-size:0.68rem; text-transform:uppercase; letter-spacing:0.07em;
        color:var(--theme-accent-light); font-weight:700; margin-bottom:0.15rem;
    }
    .sax-profile-name { color:#f4f2fa; font-weight:700; font-size:1.15rem; margin:0; }
    .sax-profile-meta { font-size:0.82rem; color:#8b889c; display:flex; align-items:center; gap:0.4rem; margin-top:0.2rem; flex-wrap:wrap; }
    .sax-premium-pill {
        display:inline-flex; align-items:center; gap:0.3rem;
        background:linear-gradient(135deg, var(--theme-accent), var(--theme-accent-mid));
        color:#1a1206; font-size:0.68rem; font-weight:800;
        padding:0.15rem 0.55rem; border-radius:999px;
    }
    .sax-edit-btn {
        width:38px; height:38px; border-radius:11px;
        background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.1);
        color:#c9c6d8; display:flex; align-items:center; justify-content:center;
        flex-shrink:0;
    }
    .sax-edit-btn:hover { background:rgba(255,255,255,0.08); color:var(--theme-accent-light); }

    /* ---- Balance ---- */
    .sax-balance-card {
        position:relative; overflow:hidden;
        background:linear-gradient(135deg,#2a2110 0%, #1c160c 55%, var(--theme-dark-card) 100%);
        border:1px solid rgba(var(--theme-accent-light-rgb),0.25);
        border-radius:20px; padding:1.5rem;
        margin-bottom:1rem;
    }
    .sax-balance-card::before {
        content:""; position:absolute; inset:-40% -20% auto auto;
        width:260px; height:260px;
        background:radial-gradient(circle, rgba(var(--theme-accent-light-rgb),0.16), transparent 70%);
        pointer-events:none;
    }
    .sax-eyebrow { font-size:0.7rem; text-transform:uppercase; letter-spacing:0.07em; color:#d8b566; font-weight:700; }
    .sax-balance-figure { font-size:clamp(2.1rem, 4vw, 2.6rem); font-weight:800; color:#fff; margin:0.25rem 0 0.35rem; }
    .sax-balance-figure .cur { color:var(--theme-accent-light); font-size:0.6em; font-weight:700; margin-right:0.15rem; }
    .sax-balance-sub { font-size:0.82rem; color:#9a97ad; margin:0; position:relative; z-index:1; }
    .sax-icon-badge {
        width:48px; height:48px; border-radius:14px;
        background:linear-gradient(135deg, var(--theme-accent), var(--theme-accent-mid));
        display:flex; align-items:center; justify-content:center;
        color:#1a1206; font-size:1.2rem; flex-shrink:0;
        box-shadow:0 8px 18px rgba(var(--theme-accent-light-rgb),0.25);
    }
    @media (min-width:768px) { .sax-balance-card { padding:2rem; } .sax-icon-badge { width:56px; height:56px; font-size:1.4rem; } }

    /* ---- Mini info grid ---- */
    .sax-mini-card {
        background:var(--theme-dark-card); border:1px solid rgba(255,255,255,0.06);
        border-radius:18px; padding:1.1rem; height:100%;
        transition:border-color .15s ease;
    }
    .sax-mini-card:hover { border-color:rgba(var(--theme-accent-light-rgb),0.3); }
    .sax-mini-icon {
        width:38px; height:38px; border-radius:11px;
        display:flex; align-items:center; justify-content:center;
        font-size:1rem; margin-bottom:0.7rem;
    }
    .sax-mini-icon.violet { background:rgba(139,92,246,0.14); color:#a78bfa; }
    .sax-mini-icon.amber  { background:rgba(var(--theme-accent-light-rgb),0.14); color:var(--theme-accent-light); }
    .sax-mini-icon.green  { background:rgba(74,222,128,0.14); color:#4ade80; }
    .sax-mini-label { font-size:0.72rem; color:#8b889c; font-weight:600; }
    .sax-mini-value { font-size:0.92rem; font-weight:700; color:#e8e6f0; margin-top:0.2rem; }
    .sax-mini-link { color:var(--theme-accent-light); font-size:0.85rem; font-weight:700; text-decoration:none; margin-top:0.2rem; display:inline-block; }
    .sax-mini-link:hover { color:#ffce54; }
    .sax-status-pill {
        display:inline-flex; align-items:center; gap:0.3rem;
        font-size:0.78rem; font-weight:700; margin-top:0.25rem;
        padding:0.15rem 0.6rem; border-radius:999px;
    }
    .sax-status-pill.active { background:rgba(74,222,128,0.14); color:#4ade80; }
    .sax-status-pill.inactive { background:rgba(248,113,113,0.14); color:#f87171; }

    /* ---- Generic card ---- */
    .sax-card {
        background:var(--theme-dark-card); border:1px solid rgba(255,255,255,0.06);
        border-radius:20px; padding:1.35rem;
        margin-bottom:1rem;
    }
    .sax-card h5 { color:#f4f2fa; font-weight:700; }

    /* ---- Referral ---- */
    .sax-input {
        background:var(--theme-dark-input) !important; border:1px solid rgba(255,255,255,0.08) !important;
        color:var(--theme-dark-text-soft) !important; border-radius:12px !important; font-size:0.85rem;
        padding:0.65rem 0.9rem;
    }
    .sax-copy-btn {
        background:linear-gradient(135deg, var(--theme-accent), var(--theme-accent-mid));
        border:none; color:#1a1206; border-radius:12px; font-weight:700;
        padding:0.55rem 1.1rem; white-space:nowrap; flex-shrink:0;
    }
    .sax-copy-btn:hover { filter:brightness(1.05); color:#1a1206; }
    @media (max-width:359px) {
        .sax-referral-row { flex-direction:column; }
        .sax-copy-btn { width:100%; justify-content:center; display:flex; }
    }
    @media (min-width:768px) {
        .sax-mini-card { padding:1.35rem; }
        .sax-mini-icon { width:44px; height:44px; font-size:1.1rem; }
        .sax-card { padding:1.75rem; }
    }

    /* ---- Official links ---- */
    .sax-official-link { text-decoration:none; text-align:center; width:76px; }
    .sax-official-icon {
        width:56px; height:56px; border-radius:50%;
        display:flex; align-items:center; justify-content:center;
        color:#fff; font-size:1.35rem; margin:0 auto 0.5rem;
    }
    .sax-official-link .sax-official-label { display:block; font-size:0.72rem; color:#a9a6ba; font-weight:600; line-height:1.2; }
    .sax-official-link:hover .sax-official-label { color:var(--theme-accent-light); }
    @media (min-width:768px) {
        .sax-official-icon { width:64px; height:64px; font-size:1.5rem; }
        .sax-official-link { width:88px; }
    }

    /* ---- Premium banner ---- */
    .sax-upsell {
        position:relative; overflow:hidden;
        background:linear-gradient(135deg, rgba(var(--theme-accent-light-rgb),0.14), rgba(201,134,26,0.08)), var(--theme-dark-card);
        border:1px solid rgba(var(--theme-accent-light-rgb),0.35);
        border-radius:20px; padding:2rem;
    }
    .sax-upsell::before {
        content:"\f521"; font-family:"Font Awesome 6 Free"; font-weight:900;
        position:absolute; right:-10px; bottom:-25px; font-size:8rem;
        color:rgba(var(--theme-accent-light-rgb),0.06);
    }
    .sax-btn-gold {
        background:linear-gradient(135deg, var(--theme-accent), var(--theme-accent-mid));
        border:none; color:#1a1206 !important; font-weight:700;
        border-radius:12px; padding:0.6rem 1.2rem;
        box-shadow:0 8px 20px rgba(var(--theme-accent-light-rgb),0.2);
    }
    .sax-btn-gold:hover { filter:brightness(1.06); }
</style>

<div class="sax-page">
<div class="container">

    {{-- Profile + Balance --}}
    <div class="row g-3 mb-1 align-items-stretch">
        <div class="col-12 col-lg-7" data-reveal>
            <div class="sax-profile-card d-flex align-items-center gap-3 h-100">
                <div class="sax-avatar-ring">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}">
                    @else
                        {{ substr($user->name, 0, 1) }}
                    @endif
                    <span class="sax-avatar-dot"></span>
                </div>
                <div class="flex-grow-1">
                    <div class="sax-card-soft-label">Member profile</div>
                    <h4 class="sax-profile-name">{{ $user->name }}</h4>
                    <div class="sax-profile-meta">
                        <span><i class="fas fa-circle-check" style="color:#22c55e;"></i> Joined {{ $user->joined_at->format('d M Y') }}</span>
                        @if($user->is_premium)
                            <span class="sax-premium-pill"><i class="fas fa-star"></i> Premium</span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('user.personal-info') }}" class="sax-edit-btn">
                    <i class="fas fa-pen"></i>
                </a>
            </div>
        </div>

        <div class="col-12 col-lg-5" data-reveal>
            <div class="sax-balance-card h-100">
                <div class="d-flex justify-content-between align-items-start position-relative" style="z-index:1;">
                    <div>
                        <div class="sax-eyebrow">Current Balance</div>
                        <div class="sax-balance-figure"><span class="cur">৳</span>{{ number_format((float) $walletAccount->current_balance, 2) }}</div>
                        <p class="sax-balance-sub">Track income &amp; manage withdrawals</p>
                    </div>
                    <div class="sax-icon-badge"><i class="fas fa-gift"></i></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Info Cards --}}
    <div class="row g-3 mb-1">
        <div class="col-6 col-md-3" data-reveal>
            <div class="sax-mini-card">
                <div class="sax-mini-icon violet"><i class="fas fa-user-pen"></i></div>
                <div class="sax-mini-label">Personal Info</div>
                <a href="{{ route('user.personal-info') }}" class="sax-mini-link">Edit Profile &rarr;</a>
            </div>
        </div>
        <div class="col-6 col-md-3" data-reveal>
            <div class="sax-mini-card">
                <div class="sax-mini-icon violet"><i class="fas fa-share-nodes"></i></div>
                <div class="sax-mini-label">Upline Affiliate ID</div>
                <div class="sax-mini-value">{{ $user->upline?->affiliate_id ?? 'N/A' }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3" data-reveal>
            <div class="sax-mini-card">
                <div class="sax-mini-icon amber"><i class="fas fa-id-badge"></i></div>
                <div class="sax-mini-label">Your Affiliate ID</div>
                <div class="sax-mini-value">{{ $user->affiliate_id }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3" data-reveal>
            <div class="sax-mini-card">
                <div class="sax-mini-icon green"><i class="fas fa-crown"></i></div>
                <div class="sax-mini-label">Premium Status</div>
                <div>
                    <span class="sax-status-pill {{ $user->is_premium ? 'active' : 'inactive' }}">
                        <i class="fas fa-circle" style="font-size:0.5rem;"></i>
                        {{ $user->is_premium ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Referral Link --}}
    <div class="sax-card" data-reveal>
        <label class="sax-card-soft-label d-block mb-1">Your Referral Link</label>
        <h5 class="mb-3">Invite faster with one tap</h5>
        <div class="d-flex gap-2 sax-referral-row">
            <input type="text" class="form-control sax-input" value="{{ $referralLink }}" id="referralLink" readonly>
            <button class="btn sax-copy-btn" type="button" data-copy-target="#referralLink" data-copy-message="Referral link copied">
                <i class="fas fa-copy me-1"></i>Copy
            </button>
        </div>
    </div>

    {{-- Official Links --}}
    @if($officialLinks->count())
        <div class="sax-card" data-reveal>
            <label class="sax-card-soft-label d-block mb-1">Official Links</label>
            <h5 class="mb-1">Stay connected</h5>
            <p class="small mb-3" style="color:#726f85;">Follow every official channel for updates</p>
            <div class="d-flex flex-wrap gap-3">
                @foreach($officialLinks as $link)
                    @php
                        $titleLc = strtolower($link->title);
                        if (str_contains($titleLc, 'youtube')) {
                            $icon = 'fab fa-youtube';
                            $bg = 'linear-gradient(135deg,#ff4d4d,#c0272d)';
                        } elseif (str_contains($titleLc, 'telegram')) {
                            $icon = 'fab fa-telegram';
                            $bg = 'linear-gradient(135deg,#3fb6f0,#1e88c7)';
                        } elseif (str_contains($titleLc, 'facebook')) {
                            $icon = 'fab fa-facebook-f';
                            $bg = 'linear-gradient(135deg,#4267ff,#2952cc)';
                        } elseif (str_contains($titleLc, 'whatsapp')) {
                            $icon = 'fab fa-whatsapp';
                            $bg = 'linear-gradient(135deg,#2fd06a,#1fae5c)';
                        } elseif (str_contains($titleLc, 'twitter') || str_contains($titleLc, ' x ')) {
                            $icon = 'fab fa-x-twitter';
                            $bg = 'linear-gradient(135deg,#3a3a44,#1a1a1f)';
                        } elseif (str_contains($titleLc, 'instagram')) {
                            $icon = 'fab fa-instagram';
                            $bg = 'linear-gradient(135deg, var(--theme-accent-light), #c4267c 55%, #5b4bb0)';
                        } else {
                            $icon = 'fas fa-arrow-up-right-from-square';
                            $bg = 'linear-gradient(135deg, var(--theme-accent-light), var(--theme-accent-deep))';
                        }
                    @endphp
                    <a href="{{ $link->url }}" target="_blank" class="sax-official-link">
                        <span class="sax-official-icon" style="background:{{ $bg }};"><i class="{{ $icon }}"></i></span>
                        <span class="sax-official-label">{{ $link->title }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Premium Upgrade --}}
    @if(!$user->is_premium)
        <div class="sax-upsell text-center" data-reveal>
            <h5 class="mb-2" style="color:#f5c451; position:relative; z-index:1;">Premium Benefits</h5>
            <p class="small mb-3" style="color:#9a97ad; position:relative; z-index:1;">Explore exclusive features and earning opportunities</p>
            <a href="{{ route('premium.benefits') }}" class="btn sax-btn-gold position-relative" style="z-index:1;">
                View Premium Benefits
            </a>
        </div>
    @endif

</div>
</div>
@endsection