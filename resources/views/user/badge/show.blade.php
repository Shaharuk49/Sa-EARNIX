@extends('layouts.user')

@section('title', '{{ $leaderBadge->name }} - SA EarniX')

@section('content')
<div class="container py-4">

    {{-- Top Nav --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('user.badge.index') }}" class="btn btn-sm btn-warning text-white">← ব্যাক</a>
        <h5 class="mb-0 fw-bold">{{ $leaderBadge->name }}</h5>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-pills mb-4 gap-2" id="badgeTabs">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="pill" href="#info">র‍্যাংক তথ্য</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="pill" href="#mybadge">আমার র‍্যাংক</a>
        </li>
    </ul>

    <div class="tab-content">
        {{-- Rank Info Tab --}}
        <div class="tab-pane fade show active" id="info">

            {{-- Progress Circle --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold">Verified Members</span>
                        <a href="#" class="text-primary small">লিস্ট দেখুন</a>
                    </div>
                    <div class="text-center">
                        <div class="position-relative d-inline-block">
                            <svg width="120" height="120" viewBox="0 0 120 120">
                                <circle cx="60" cy="60" r="50" fill="none" stroke="#e2e8f0" stroke-width="8"/>
                                @php
                                    $pct = $required > 0 ? min(1, $current / $required) : 0;
                                    $circ = 2 * 3.14159 * 50;
                                    $dash = $pct * $circ;
                                @endphp
                                <circle cx="60" cy="60" r="50" fill="none" stroke="#22c55e" stroke-width="8"
                                    stroke-dasharray="{{ round($dash, 1) }} {{ round($circ, 1) }}"
                                    stroke-linecap="round"
                                    transform="rotate(-90 60 60)"/>
                            </svg>
                            <div class="position-absolute top-50 start-50 translate-middle">
                                <div class="fw-bold text-success fs-5">{{ $current }}/{{ $required }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Conditions --}}
            @if($leaderBadge->condition_text)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">শর্তাবলী</h6>
                    <p class="text-muted small">{!! nl2br(e($leaderBadge->condition_text)) !!}</p>
                </div>
            </div>
            @endif

            {{-- Prize --}}
            @if($leaderBadge->prize_text)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">🎁 প্রাইজ</h6>
                    <p class="text-muted small">{!! nl2br(e($leaderBadge->prize_text)) !!}</p>
                </div>
            </div>
            @endif

            {{-- Status --}}
            @if($earned)
                <div class="alert alert-success">✅ আপনি এই ব্যাজ অর্জন করেছেন!</div>
            @elseif($qualified)
                <div class="alert alert-info">🎉 আপনি যোগ্যতা অর্জন করেছেন! Admin approval পেন্ডিং।</div>
            @else
                <div class="alert alert-warning">আরও <strong>{{ $required - $current }}</strong> টি member প্রয়োজন।</div>
            @endif
        </div>

        {{-- My Badge Tab --}}
        <div class="tab-pane fade" id="mybadge">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">যোগ্য Members</h6>
                    @forelse($qualifiedDirects as $member)
                        <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                            @if($member->userProfile && $member->userProfile->profile_photo)
                                <img src="{{ asset('storage/' . $member->userProfile->profile_photo) }}"
                                     class="rounded-circle" width="40" height="40" style="object-fit:cover;">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white"
                                     style="width:40px;height:40px;font-size:1rem;">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold small">{{ $member->name }}</div>
                                <div class="text-muted" style="font-size:0.75rem;">ID: {{ $member->affiliate_id }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small">কোনো যোগ্য member নেই।</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
