@extends('layouts.user')

@section('title', 'Top Referrals - SA EarniX')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="text-center mb-4">
        <h4 class="fw-bold">SA Earnix - Top Referrals</h4>
        <small class="text-muted">Updated: {{ now()->format('d M Y, H:i') }}</small>
    </div>

    @if($topUsers->count() >= 3)
    {{-- Top 3 Podium --}}
    <div class="d-flex justify-content-center align-items-end gap-3 mb-4">

        {{-- Top 2 --}}
        @php $second = $topUsers[1]; @endphp
        <div class="text-center" style="flex:1; max-width:120px;">
            <div class="small text-muted mb-1">Top-2</div>
            <div class="rounded-3 border border-2 border-secondary p-2">
                @if($second->profile_photo)
                    <img src="{{ asset('storage/' . $second->profile_photo) }}" class="rounded-circle mb-2" width="55" height="55" style="object-fit:cover;">
                @else
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white mx-auto mb-2"
                         style="width:55px;height:55px;font-size:1.2rem;">
                        {{ strtoupper(substr($second->name, 0, 1)) }}
                    </div>
                @endif
                <div class="small fw-bold text-truncate">{{ $second->name }}</div>
                <div class="small text-muted">{{ number_format($second->referral_count) }} Referrals</div>
            </div>
        </div>

        {{-- Top 1 --}}
        @php $first = $topUsers[0]; @endphp
        <div class="text-center" style="flex:1; max-width:140px;">
            <div class="small text-warning fw-bold mb-1">Top-1</div>
            <div class="rounded-3 border border-2 p-2" style="border-color:#f59e0b !important;">
                @if($first->profile_photo)
                    <img src="{{ asset('storage/' . $first->profile_photo) }}" class="rounded-circle mb-2" width="65" height="65" style="object-fit:cover;">
                @else
                    <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center text-white mx-auto mb-2"
                         style="width:65px;height:65px;font-size:1.4rem;">
                        {{ strtoupper(substr($first->name, 0, 1)) }}
                    </div>
                @endif
                <div class="small fw-bold text-truncate">{{ $first->name }}</div>
                <div class="small text-muted">{{ number_format($first->referral_count) }} Referrals</div>
            </div>
        </div>

        {{-- Top 3 --}}
        @php $third = $topUsers[2]; @endphp
        <div class="text-center" style="flex:1; max-width:120px;">
            <div class="small text-muted mb-1">Top-3</div>
            <div class="rounded-3 border border-2 p-2" style="border-color:#cd7f32 !important;">
                @if($third->profile_photo)
                    <img src="{{ asset('storage/' . $third->profile_photo) }}" class="rounded-circle mb-2" width="55" height="55" style="object-fit:cover;">
                @else
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white mx-auto mb-2"
                         style="width:55px;height:55px;font-size:1.2rem;">
                        {{ strtoupper(substr($third->name, 0, 1)) }}
                    </div>
                @endif
                <div class="small fw-bold text-truncate">{{ $third->name }}</div>
                <div class="small text-muted">{{ number_format($third->referral_count) }} Referrals</div>
            </div>
        </div>
    </div>
    @endif

    {{-- My Rank --}}
    @if($myRank)
        <div class="alert alert-info small text-center mb-4">
            🏅 আপনার র‍্যাংক: <strong>#{{ $myRank }}</strong> — {{ number_format($myReferralCount) }} Referrals
        </div>
    @endif

    {{-- List #4 onwards --}}
    <div class="list-group">
        @foreach($topUsers->skip(3) as $i => $user)
            <div class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
                <span class="fw-bold text-muted" style="width:30px;">#{{ $i + 4 }}</span>
                @if($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}" class="rounded-circle"
                         width="45" height="45" style="object-fit:cover;">
                @else
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white flex-shrink-0"
                         style="width:45px;height:45px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div class="flex-grow-1">
                    <div class="fw-bold small">{{ $user->name }}</div>
                    <div class="text-muted" style="font-size:0.8rem;">{{ number_format($user->referral_count) }} Referrals</div>
                </div>
            </div>
        @endforeach
    </div>

    @if($topUsers->isEmpty())
        <div class="text-center py-5 text-muted">
            <p>No data yet.</p>
        </div>
    @endif
</div>
@endsection
