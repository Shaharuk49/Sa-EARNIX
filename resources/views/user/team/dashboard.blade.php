@extends('layouts.user')

@section('title', 'My Team - SA EarniX')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">My Team Dashboard</h3>

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-muted small mb-2">My Business Partner</div>
                    <h3 class="mb-0 text-primary">{{ $directCount }}</h3>
                    <small class="text-muted">Total direct referrals</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-muted small mb-2">My Total Team</div>
                    <h3 class="mb-0 text-success">{{ $totalTeam }}</h3>
                    <small class="text-muted">Total team members</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Direct Referrals List --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">My Business Partners</h5>
        </div>
        <div class="card-body">
            @forelse($directReferrals as $referral)
                <div class="d-flex align-items-center p-3 border-bottom gap-3">
                    <div>
                        @if($referral->profile_photo)
                            <img src="{{ asset('storage/' . $referral->profile_photo) }}" 
                                 alt="{{ $referral->name }}" class="rounded-circle" 
                                 style="width:50px;height:50px;object-fit:cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                 style="width:50px;height:50px;">
                                <strong>{{ substr($referral->name, 0, 1) }}</strong>
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $referral->name }}</h6>
                        <small class="text-muted d-block">ID: {{ $referral->affiliate_id }}</small>
                        <small class="text-muted d-block">Joined: {{ $referral->joined_at->format('d M Y') }}</small>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('register', ['ref' => $referral->affiliate_id]) }}" 
                           class="btn btn-sm btn-outline-primary">View</a>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-users fa-2x opacity-25 d-block mb-2"></i>
                    No referrals yet. Share your link to get started.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
