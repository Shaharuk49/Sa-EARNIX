@extends('layouts.user')

@section('title', 'Home - SA EarniX')

@section('content')
<div class="container py-4">
    <div class="glass-panel home-hero mb-4" data-reveal>
        <div class="row g-4 align-items-center position-relative">
            <div class="col-lg-8">
                <span class="hero-badge mb-3"><i class="fas fa-signal"></i> Smart earning dashboard</span>
                <h2 class="mb-2">Welcome back, {{ $user->name }}</h2>
                <p class="text-muted mb-4">Track your balance, share your referral link, follow official channels, and move faster across every earning module.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('user.income') }}" class="btn btn-primary"><i class="fas fa-wallet me-2"></i>View Income</a>
                    <a href="{{ route('user.team') }}" class="btn btn-outline-primary"><i class="fas fa-users me-2"></i>My Team</a>
                    <a href="{{ route('user.withdraw') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-up-from-bracket me-2"></i>Withdraw</a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="metric-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">Current Balance</div>
                            <h3 class="mb-0">৳ {{ number_format((float) $walletAccount->current_balance, 2) }}</h3>
                        </div>
                        <div class="metric-icon"><i class="fas fa-coins"></i></div>
                    </div>
                    <div class="small text-muted">Affiliate ID</div>
                    <div class="fw-bold">{{ $user->affiliate_id }}</div>
                    <div class="small text-muted mt-3">Joined</div>
                    <div class="fw-semibold">{{ $user->joined_at->format('d M Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Profile Section --}}
    <div class="row mb-4 g-3">
        <div class="col-md-8" data-reveal>
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" 
                                     class="rounded-circle" style="width:60px;height:60px;object-fit:cover;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                     style="width:60px;height:60px;">
                                    <span class="fw-bold">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted small text-uppercase fw-bold">Member profile</div>
                            <h4 class="mb-1">{{ $user->name }}</h4>
                            <small class="text-muted">Joined: {{ $user->joined_at->format('d M Y') }}</small>
                        </div>
                        <a href="{{ route('user.personal-info') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4" data-reveal>
            <div class="card border-0 shadow-sm h-100" style="background:linear-gradient(135deg, #0f2e57, #1b5ea9); color:#fff;">
                <div class="card-body text-center d-flex flex-column justify-content-center p-4">
                    <small class="opacity-75 d-block text-uppercase fw-bold">Current Balance</small>
                    <h3 class="mb-2">৳ {{ number_format((float) $walletAccount->current_balance, 2) }}</h3>
                    <div class="small opacity-75">Use your wallet to track income and withdraw requests.</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Info Cards --}}
    <div class="row mb-4 g-3">
        <div class="col-6 col-md-3" data-reveal>
            <div class="metric-card text-center">
                <div class="metric-icon mx-auto mb-3"><i class="fas fa-user-pen"></i></div>
                <div class="text-primary small fw-bold">Personal Info</div>
                <a href="{{ route('user.personal-info') }}" class="btn btn-sm btn-link mt-2">Edit</a>
            </div>
        </div>
        <div class="col-6 col-md-3" data-reveal>
            <div class="metric-card text-center">
                <div class="metric-icon mx-auto mb-3"><i class="fas fa-share-nodes"></i></div>
                <div class="text-muted small">Upline Affiliate ID</div>
                <div class="fw-bold small">{{ $user->upline?->affiliate_id ?? 'N/A' }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3" data-reveal>
            <div class="metric-card text-center">
                <div class="metric-icon mx-auto mb-3"><i class="fas fa-id-badge"></i></div>
                <div class="text-muted small">Your Affiliate ID</div>
                <div class="fw-bold small">{{ $user->affiliate_id }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3" data-reveal>
            <div class="metric-card text-center">
                <div class="metric-icon mx-auto mb-3"><i class="fas fa-crown"></i></div>
                <div class="text-muted small">Premium Status</div>
                <div class="fw-bold small">{{ $user->is_premium ? '✓ Active' : '✗ Inactive' }}</div>
            </div>
        </div>
    </div>

    {{-- Referral Link --}}
    <div class="card border-0 shadow-sm mb-4" data-reveal>
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <div>
                    <label class="small text-muted d-block mb-1 text-uppercase fw-bold">Your Referral Link</label>
                    <h5 class="mb-0">Invite faster with one tap</h5>
                </div>
                <button class="btn btn-outline-primary" type="button" data-copy-target="#referralLink" data-copy-message="Referral link copied">
                    <i class="fas fa-copy me-2"></i>Copy Link
                </button>
            </div>
            <div class="input-group">
                <input type="text" class="form-control" value="{{ $referralLink }}" id="referralLink" readonly>
                <a class="btn btn-primary" href="https://wa.me/?text={{ urlencode($referralLink) }}" target="_blank">
                    <i class="fab fa-whatsapp me-2"></i>Share
                </a>
            </div>
        </div>
    </div>

    {{-- Official Links --}}
    @if($officialLinks->count())
        <div class="card border-0 shadow-sm mb-4" data-reveal>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title mb-0">Official Links</h6>
                    <span class="text-muted small">Stay connected with every channel</span>
                </div>
                <div class="row g-3">
                    @foreach($officialLinks as $link)
                        <div class="col-6 col-md-4">
                            <a href="{{ $link->url }}" target="_blank" class="link-chip">
                                <i class="fas fa-arrow-up-right-from-square"></i>
                                {{ $link->title }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Premium Upgrade --}}
    @if(!$user->is_premium)
        <div class="card border-0 shadow-sm mb-4" data-reveal style="background:linear-gradient(135deg, rgba(216,138,45,0.12), rgba(240,161,63,0.22));">
            <div class="card-body text-center p-4">
                <h5 class="mb-2">Premium Upgrade Now</h5>
                <p class="text-muted small mb-3">Unlock all features and earn more!</p>
                <a href="{{ route('premium.upgrade.show') }}" class="btn btn-warning">
                    Upgrade for ৳250
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
