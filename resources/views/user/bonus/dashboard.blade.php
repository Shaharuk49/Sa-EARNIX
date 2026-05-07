@extends('layouts.user')

@section('title', 'Welcome Bonus')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Welcome Bonus System</h2>
                @if ($all_completed && !$bonus_claimed)
                    <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#claimBonusModal">
                        <i class="fas fa-gift"></i> Claim Bonus
                    </button>
                @elseif ($bonus_claimed)
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle"></i> Bonus Claimed!
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Welcome to the Bonus System!</strong>
                <br>
                Complete all videos in all sections to unlock a <strong>1000 BDT</strong> bonus that will be credited to your wallet.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Bonus Sections -->
    <div class="row">
        @forelse ($sections as $item)
            <div class="col-lg-6 col-xxl-4 mb-4">
                <div class="card h-100 {{ !$item['is_unlocked'] ? 'opacity-50' : '' }}">
                    <!-- Section Header -->
                    <div class="card-header bg-gradient text-white position-relative overflow-hidden">
                        <div class="position-absolute end-0 top-0 opacity-10" style="font-size: 4rem;">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <h5 class="card-title mb-1">
                            <i class="fas {{ $item['is_unlocked'] ? 'fa-unlock text-success' : 'fa-lock text-warning' }}"></i>
                            {{ $item['section']->title }}
                        </h5>
                        <p class="card-text text-sm mb-0">{{ $item['section']->description }}</p>
                    </div>

                    <!-- Section Body -->
                    <div class="card-body">
                        <!-- Progress Bar -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-sm font-weight-bold">Progress</span>
                                <span class="badge bg-primary">{{ $item['watched_videos'] }}/{{ $item['total_videos'] }} Videos</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {!! $item['total_videos'] > 0 ? ($item['watched_videos'] / $item['total_videos'] * 100) : 0 !!}%">
                                </div>
                            </div>
                        </div>

                        <!-- Unlock Requirements -->
                        @if (!$item['is_unlocked'])
                            <div class="alert alert-warning alert-sm mb-3">
                                <strong>Requirements to unlock:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($item['section']->rules as $rule)
                                        <li class="text-sm">
                                            @if ($rule->rule_type === 'direct_referrals')
                                                <i class="fas fa-users"></i> At least {{ $rule->rule_value }} direct referrals
                                            @elseif ($rule->rule_type === 'total_referrals')
                                                <i class="fas fa-network-wired"></i> At least {{ $rule->rule_value }} total team members
                                            @elseif ($rule->rule_type === 'is_premium')
                                                <i class="fas fa-star"></i> Premium membership required
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Videos List -->
                        <div class="list-group list-group-sm">
                            @forelse ($item['section']->videos()->orderBy('video_order')->get() as $video)
                                <a href="{{ $item['is_unlocked'] ? route('user.bonus.section', $item['section']) : '#' }}" 
                                   class="list-group-item list-group-item-action {{ !$item['is_unlocked'] ? 'disabled' : '' }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="d-block">
                                                <i class="fas {{ in_array($video->id, $item['watched_videos']) ? 'fa-check-circle text-success' : 'fa-circle text-muted' }}"></i>
                                                {{ $video->video_title }}
                                            </small>
                                            <small class="text-muted">{{ $video->duration_minutes }} min</small>
                                        </div>
                                        <i class="fas fa-chevron-right text-muted"></i>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-3 text-muted">
                                    <small>No videos yet</small>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Section Footer -->
                    @if ($item['is_unlocked'])
                        <div class="card-footer bg-light">
                            <a href="{{ route('user.bonus.section', $item['section']) }}" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-play"></i> Watch Videos
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No bonus sections available yet.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Summary Card -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card bg-gradient text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="icon icon-lg text-white">
                                <i class="fas fa-gift"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h6 class="mb-2">Welcome Bonus</h6>
                            <p class="text-sm mb-0">
                                @if ($all_completed && !$bonus_claimed)
                                    <strong>You're ready to claim your 1000 BDT bonus!</strong> All videos watched successfully.
                                @elseif ($bonus_claimed)
                                    <strong>Bonus Claimed:</strong> 1000 BDT has been added to your wallet.
                                @else
                                    Complete all videos in all sections to unlock a <strong>1000 BDT</strong> bonus.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Claim Bonus Modal -->
<div class="modal fade" id="claimBonusModal" tabindex="-1" aria-labelledby="claimBonusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient text-white">
                <h5 class="modal-title" id="claimBonusModalLabel">
                    <i class="fas fa-gift"></i> Claim Your Welcome Bonus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="icon icon-lg text-success mb-3">
                        <i class="fas fa-gift"></i>
                    </div>
                    <h4>1000 BDT</h4>
                    <p class="text-muted">You have successfully completed all sections and videos.</p>
                </div>
                <div class="alert alert-info alert-sm">
                    <i class="fas fa-info-circle"></i> This bonus will be added directly to your wallet and can be used for any transactions or withdrawals.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('user.bonus.claim') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Claim Bonus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .icon-lg i {
        font-size: 3rem;
    }

    .card-header.bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .card-header.bg-gradient.text-white {
        color: white !important;
    }

    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .text-sm {
        font-size: 0.875rem;
    }

    .alert-sm {
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
    }

    .list-group-item.disabled {
        pointer-events: none;
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
    }

    .opacity-50 {
        opacity: 0.5;
    }
</style>
@endsection
