@extends('layouts.user')

@section('title', 'Home - SA EarniX')

@section('content')
<style>
    .sax-page { background:#0a0a14; min-height:100vh; padding:1.75rem 0 3rem; color:#e8e6f0; }
    .sax-page .container { max-width:1140px; }

    /* ---- Hero ---- */
    .sax-hero {
        position:relative;
        background:linear-gradient(135deg, #14141f 0%, #1a1526 60%, #201a15 100%);
        border:1px solid rgba(240,180,41,0.15);
        border-radius:22px;
        padding:2.25rem;
        overflow:hidden;
        margin-bottom:1.5rem;
    }
    .sax-hero::before{
        content:"";
        position:absolute; inset:-40% -10% auto auto;
        width:340px; height:340px;
        background:radial-gradient(circle, rgba(240,180,41,0.18), transparent 70%);
        pointer-events:none;
    }
    .sax-badge {
        display:inline-flex; align-items:center; gap:0.4rem;
        background:rgba(240,180,41,0.1); border:1px solid rgba(240,180,41,0.3);
        color:#f0b429; font-size:0.75rem; font-weight:700; letter-spacing:0.04em;
        text-transform:uppercase; padding:0.35rem 0.85rem; border-radius:999px;
        margin-bottom:1rem;
    }
    .sax-hero h2 { color:#f4f2fa; font-weight:700; }
    .sax-hero p { color:#9a97ad; max-width:520px; }

    .sax-btn-gold {
        background:linear-gradient(135deg,#f6c453,#e0a520);
        border:none; color:#1a1206 !important; font-weight:700;
        border-radius:12px; padding:0.6rem 1.2rem;
        box-shadow:0 8px 20px rgba(240,180,41,0.2);
    }
    .sax-btn-gold:hover { filter:brightness(1.06); }
    .sax-btn-ghost {
        background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.12);
        color:#e8e6f0 !important; border-radius:12px; padding:0.6rem 1.2rem; font-weight:600;
    }
    .sax-btn-ghost:hover { background:rgba(255,255,255,0.07); border-color:rgba(255,255,255,0.2); }

    .sax-balance-card {
        position:relative; z-index:1;
        background:#14141f; border:1px solid rgba(240,180,41,0.2);
        border-radius:18px; padding:1.5rem;
    }
    .sax-eyebrow { font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:#9a97ad; font-weight:700; }
    .sax-balance-figure { font-size:1.9rem; font-weight:700; color:#f5c451; margin:0.15rem 0 0; }
    .sax-icon-badge {
        width:46px; height:46px; border-radius:13px;
        background:linear-gradient(135deg,#f0b429,#c9861a);
        display:flex; align-items:center; justify-content:center;
        color:#1a1206; font-size:1.15rem; flex-shrink:0;
    }
    .sax-balance-meta { border-top:1px solid rgba(255,255,255,0.08); margin-top:1.1rem; padding-top:1.1rem; }
    .sax-balance-meta .label { font-size:0.72rem; color:#726f85; text-transform:uppercase; letter-spacing:0.04em; }
    .sax-balance-meta .value { font-size:0.92rem; font-weight:600; color:#e8e6f0; }

    /* ---- Generic card ---- */
    .sax-card {
        background:#14141f; border:1px solid rgba(255,255,255,0.06);
        border-radius:18px; padding:1.5rem;
    }
    .sax-card-soft-label { font-size:0.72rem; text-transform:uppercase; letter-spacing:0.06em; color:#f0b429; font-weight:700; margin-bottom:0.3rem; }

    /* ---- Profile ---- */
    .sax-avatar-ring {
        width:64px; height:64px; border-radius:50%;
        padding:2px; background:linear-gradient(135deg,#f0b429,#c9861a);
        flex-shrink:0;
    }
    .sax-avatar-ring img, .sax-avatar-ring .fallback {
        width:100%; height:100%; border-radius:50%; object-fit:cover;
        display:flex; align-items:center; justify-content:center;
        background:#1a1a26; color:#f0b429; font-weight:700; font-size:1.2rem;
        border:2px solid #14141f;
    }

    /* ---- Balance highlight (secondary) ---- */
    .sax-balance-secondary {
        background:radial-gradient(circle at 30% 20%, rgba(240,180,41,0.16), transparent 55%), #14141f;
        border:1px solid rgba(240,180,41,0.18);
        border-radius:18px; height:100%;
        display:flex; flex-direction:column; align-items:center; justify-content:center;
        text-align:center; padding:1.75rem 1.25rem;
    }

    /* ---- Quick info grid ---- */
    .sax-mini-card {
        background:#14141f; border:1px solid rgba(255,255,255,0.06);
        border-radius:16px; padding:1.25rem 1rem; text-align:center; height:100%;
        transition:border-color .15s ease;
    }
    .sax-mini-card:hover { border-color:rgba(240,180,41,0.3); }
    .sax-mini-icon {
        width:42px; height:42px; margin:0 auto 0.75rem; border-radius:12px;
        background:rgba(240,180,41,0.1); border:1px solid rgba(240,180,41,0.25);
        display:flex; align-items:center; justify-content:center; color:#f0b429; font-size:1.05rem;
    }
    .sax-mini-label { font-size:0.72rem; text-transform:uppercase; letter-spacing:0.04em; color:#9a97ad; font-weight:700; }
    .sax-mini-value { font-size:0.92rem; font-weight:700; color:#e8e6f0; margin-top:0.2rem; }
    .sax-mini-link { color:#f0b429; font-size:0.82rem; font-weight:600; text-decoration:none; margin-top:0.4rem; display:inline-block; }
    .sax-mini-link:hover { color:#ffce54; }
    .sax-status-active { color:#4ade80 !important; }
    .sax-status-inactive { color:#f87171 !important; }

    /* ---- Referral ---- */
    .sax-input {
        background:#191926 !important; border:1px solid rgba(255,255,255,0.08) !important;
        color:#cfcbe0 !important; border-radius:12px 0 0 12px !important; font-size:0.88rem;
    }
    .sax-copy-btn {
        background:rgba(240,180,41,0.1); border:1px solid rgba(240,180,41,0.3);
        color:#f0b429; border-radius:12px; font-weight:600; padding:0.55rem 1.1rem;
    }
    .sax-copy-btn:hover { background:rgba(240,180,41,0.2); color:#ffce54; }
    .sax-whatsapp-btn {
        background:#1fae5c; border:none; color:#fff !important; font-weight:600;
        border-radius:0 12px 12px 0 !important;
    }
    .sax-whatsapp-btn:hover { background:#1a9650; }

    /* ---- Official links ---- */
    .sax-link-chip {
        display:flex; align-items:center; gap:0.6rem;
        background:#191926; border:1px solid rgba(255,255,255,0.07);
        border-radius:12px; padding:0.75rem 1rem; color:#e8e6f0;
        font-size:0.85rem; font-weight:600; text-decoration:none; height:100%;
    }
    .sax-link-chip i { color:#f0b429; }
    .sax-link-chip:hover { border-color:rgba(240,180,41,0.35); color:#f5c451; background:#1d1d2c; }

    /* ---- Premium banner ---- */
    .sax-upsell {
        position:relative; overflow:hidden;
        background:linear-gradient(135deg, rgba(240,180,41,0.14), rgba(201,134,26,0.08)), #14141f;
        border:1px solid rgba(240,180,41,0.35);
        border-radius:20px; padding:2rem;
    }
    .sax-upsell::before {
        content:"\f521"; font-family:"Font Awesome 6 Free"; font-weight:900;
        position:absolute; right:-10px; bottom:-25px; font-size:8rem;
        color:rgba(240,180,41,0.06);
    }
</style>

<div class="sax-page">
<div class="container">

    {{-- Hero --}}
    <div class="sax-hero" data-reveal>
        <div class="row g-4 align-items-center position-relative">
            <div class="col-lg-8">
                <span class="sax-badge"><i class="fas fa-signal"></i> Smart earning dashboard</span>
                <h2 class="mb-2">Welcome back, {{ $user->name }}</h2>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('user.income') }}" class="btn sax-btn-gold"><i class="fas fa-wallet me-2"></i>View Income</a>
                    <a href="{{ route('user.team') }}" class="btn sax-btn-ghost"><i class="fas fa-users me-2"></i>My Team</a>
                    <a href="{{ route('user.withdraw') }}" class="btn sax-btn-ghost"><i class="fas fa-arrow-up-from-bracket me-2"></i>Withdraw</a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="sax-balance-card">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div>
                            <div class="sax-eyebrow">Current Balance</div>
                            <div class="sax-balance-figure">৳ {{ number_format((float) $walletAccount->current_balance, 2) }}</div>
                        </div>
                        <div class="sax-icon-badge"><i class="fas fa-coins"></i></div>
                    </div>
                    <div class="sax-balance-meta d-flex justify-content-between">
                        <div>
                            <div class="label">Affiliate ID</div>
                            <div class="value">{{ $user->affiliate_id }}</div>
                        </div>
                        <div class="text-end">
                            <div class="label">Joined</div>
                            <div class="value">{{ $user->joined_at->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Profile Section --}}
    <div class="row mb-4 g-3">
        <div class="col-md-8" data-reveal>
            <div class="sax-card h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="sax-avatar-ring">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}">
                        @else
                            <div class="fallback">{{ substr($user->name, 0, 1) }}</div>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <div class="sax-card-soft-label mb-0">Member profile</div>
                        <h4 class="mb-1" style="color:#f4f2fa;">{{ $user->name }}</h4>
                        <small style="color:#726f85;">Joined: {{ $user->joined_at->format('d M Y') }}</small>
                    </div>
                    <a href="{{ route('user.personal-info') }}" class="btn sax-btn-ghost btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                </div>
            </div>
        </div>
        
    </div>

    {{-- Quick Info Cards --}}
    <div class="row mb-4 g-3">
        <div class="col-6 col-md-3" data-reveal>
            <div class="sax-mini-card">
                <div class="sax-mini-icon"><i class="fas fa-user-pen"></i></div>
                <div class="sax-mini-label">Personal Info</div>
                <a href="{{ route('user.personal-info') }}" class="sax-mini-link">Edit</a>
            </div>
        </div>
        <div class="col-6 col-md-3" data-reveal>
            <div class="sax-mini-card">
                <div class="sax-mini-icon"><i class="fas fa-share-nodes"></i></div>
                <div class="sax-mini-label">Upline Affiliate ID</div>
                <div class="sax-mini-value">{{ $user->upline?->affiliate_id ?? 'N/A' }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3" data-reveal>
            <div class="sax-mini-card">
                <div class="sax-mini-icon"><i class="fas fa-id-badge"></i></div>
                <div class="sax-mini-label">Your Affiliate ID</div>
                <div class="sax-mini-value">{{ $user->affiliate_id }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3" data-reveal>
            <div class="sax-mini-card">
                <div class="sax-mini-icon"><i class="fas fa-crown"></i></div>
                <div class="sax-mini-label">Premium Status</div>
                <div class="sax-mini-value {{ $user->is_premium ? 'sax-status-active' : 'sax-status-inactive' }}">
                    {{ $user->is_premium ? '✓ Active' : '✗ Inactive' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Referral Link --}}
    <div class="sax-card mb-4" data-reveal>
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <label class="sax-card-soft-label d-block mb-1">Your Referral Link</label>
                <h5 class="mb-0" style="color:#f4f2fa;">Invite faster with one tap</h5>
            </div>
            <button class="btn sax-copy-btn" type="button" data-copy-target="#referralLink" data-copy-message="Referral link copied">
                <i class="fas fa-copy me-2"></i>Copy Link
            </button>
        </div>
        <div class="input-group">
            <input type="text" class="form-control sax-input" value="{{ $referralLink }}" id="referralLink" readonly>
            <a class="btn sax-whatsapp-btn" href="https://wa.me/?text={{ urlencode($referralLink) }}" target="_blank">
                <i class="fab fa-whatsapp me-2"></i>Share
            </a>
        </div>
    </div>

    {{-- Official Links --}}
    @if($officialLinks->count())
        <div class="sax-card mb-4" data-reveal>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0" style="color:#f4f2fa;">Official Links</h6>
                <span class="small" style="color:#726f85;">Stay connected with every channel</span>
            </div>
            <div class="row g-3">
                @foreach($officialLinks as $link)
                    <div class="col-6 col-md-4">
                        <a href="{{ $link->url }}" target="_blank" class="sax-link-chip">
                            <i class="fas fa-arrow-up-right-from-square"></i>
                            {{ $link->title }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Premium Upgrade --}}
    @if(!$user->is_premium)
        <div class="sax-upsell text-center mb-4" data-reveal>
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