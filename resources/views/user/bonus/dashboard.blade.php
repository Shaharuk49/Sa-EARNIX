@extends('layouts.user')

@section('title', 'Welcome Bonus')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="welcome-header d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-start">
                <div>
                    <h2 class="mb-2 display-6 fw-bold">Welcome Bonus</h2>
                    <p class="text-secondary mb-0">Complete each section in order to unlock your reward. Watch the required videos and meet the unlock criteria to progress.</p>
                </div>
                <div class="text-lg-end">
                    @if ($all_completed && !$bonus_claimed)
                        <button class="btn btn-warning btn-lg fw-semibold px-4" data-bs-toggle="modal" data-bs-target="#claimBonusModal">
                            <i class="fas fa-gift me-2"></i> Claim Bonus
                        </button>
                    @elseif ($bonus_claimed)
                        <span class="badge bg-success bg-opacity-15 text-success py-3 px-4 fs-6 rounded-pill">
                            <i class="fas fa-check-circle me-2"></i> Bonus Claimed
                        </span>
                    @else
                        <span class="badge bg-secondary bg-opacity-15 text-secondary py-3 px-4 fs-6 rounded-pill">
                            <i class="fas fa-hourglass-half me-2"></i> In Progress
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card bonus-hero-card border-0 shadow-sm overflow-hidden">
                <div class="row g-0 align-items-center">
                    <div class="col-lg-8 p-4 p-lg-5">
                        <h3 class="fw-bold mb-3">Earn your reward by completing all bonus sections</h3>
                        <p class="text-muted mb-4">Unlock one section at a time. Each section requires referral targets and video completion before the next section becomes available.</p>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="hero-stat-box">
                                <span class="d-block text-muted small">Bonus Amount</span>
                                <strong class="fs-3">{{ number_format($bonus_amount ?? 1000) }} BDT</strong>
                            </div>
                            <div class="hero-stat-box">
                                <span class="d-block text-muted small">Sections</span>
                                <strong class="fs-3">{{ $sections->count() }}</strong>
                            </div>
                            <div class="hero-stat-box">
                                <span class="d-block text-muted small">Completed</span>
                                <strong class="fs-3">{{ $sections->where('is_completed')->count() }} / {{ $sections->count() }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 bonus-hero-card-side d-flex align-items-center justify-content-center p-4">
                        <div class="text-center">
                            <div class="hero-icon mb-3">
                                <i class="fas fa-gift"></i>
                            </div>
                            <p class="text-muted mb-2">Ready to earn?</p>
                            <div class="text-muted small">Complete each required section and watch the videos to clear all steps.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse ($sections as $index => $item)
            <div class="col-12 col-xl-6">
                <div class="card bonus-section-card shadow-sm {{ !$item['is_unlocked'] ? 'locked' : '' }}">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3 gap-3">
                            <div class="section-pill">
                                <span class="section-pill-number">{{ $index + 1 }}</span>
                                <span class="section-pill-label">Section</span>
                            </div>
                            <span class="badge {{ $item['is_unlocked'] ? 'bg-success bg-opacity-15 text-success' : 'bg-secondary bg-opacity-15 text-secondary' }} py-2 px-3 rounded-pill">
                                {{ $item['is_unlocked'] ? 'Unlocked' : 'Locked' }}
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-2 text-truncate">{{ $item['section']->title }}</h5>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="info-chip"><i class="fas fa-list me-1"></i> {{ $item['section']->rules->count() }} {{ Str::plural('Rule', $item['section']->rules->count()) }}</span>
                            <span class="info-chip"><i class="fas fa-video me-1"></i> {{ $item['total_videos'] }} {{ Str::plural('Video', $item['total_videos']) }}</span>
                        </div>
                        @if (!$item['is_unlocked'])
                            <div class="mb-3">
                                @if ($item['is_blocked_by_previous'])
                                    <div class="alert alert-warning py-2 mb-3 d-flex align-items-center gap-2">
                                        <i class="fas fa-arrow-right"></i>
                                        Complete previous section(s) first.
                                    </div>
                                @endif
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($item['section']->rules as $rule)
                                        <span class="requirement-pill">
                                            @if ($rule->rule_type === 'direct_referrals')
                                                <i class="fas fa-users"></i> {{ $rule->rule_value }}+ direct referrals
                                            @elseif ($rule->rule_type === 'total_referrals')
                                                <i class="fas fa-network-wired"></i> {{ $rule->rule_value }}+ team members
                                            @elseif ($rule->rule_type === 'premium_required')
                                                <i class="fas fa-star"></i> Premium account required
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    @foreach ($item['section']->rules as $rule)
                                        <span class="requirement-pill bg-white text-secondary border-muted">
                                            @if ($rule->rule_type === 'direct_referrals')
                                                <i class="fas fa-users"></i> {{ $rule->rule_value }}+ direct referrals
                                            @elseif ($rule->rule_type === 'total_referrals')
                                                <i class="fas fa-network-wired"></i> {{ $rule->rule_value }}+ team members
                                            @elseif ($rule->rule_type === 'premium_required')
                                                <i class="fas fa-star"></i> Premium account required
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                                <div class="progress progress-sm mb-3">
                                    <div class="progress-bar rounded-pill bg-success" role="progressbar" style="width: {{ $item['total_videos'] > 0 ? ($item['watched_videos'] / $item['total_videos'] * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                        <div class="small text-secondary">
                            @if ($item['is_unlocked'])
                                {{ $item['watched_videos'] }}/{{ $item['total_videos'] }} watched
                            @else
                                Unlock when rules are met
                            @endif
                        </div>
                        <a href="{{ $item['is_unlocked'] ? route('user.bonus.section', $item['section']) : '#' }}" class="stretched-link"></a>
                        <i class="fas fa-chevron-right text-secondary"></i>
                    </div>
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
</div>

<!-- Claim Bonus Modal -->
<div class="modal fade" id="claimBonusModal" tabindex="-1" aria-labelledby="claimBonusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="claimBonusModalLabel">
                    <i class="fas fa-gift text-warning"></i> Claim Your Welcome Bonus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="modal-bonus-icon mb-3">
                        <i class="fas fa-gift"></i>
                    </div>
                    <h4 class="fw-semibold">{{ number_format($bonus_amount ?? 1000) }} BDT</h4>
                    <p class="text-muted mb-0">You have completed all sections and are ready to receive your welcome bonus.</p>
                </div>
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i> The amount will be added directly to your wallet once claimed.
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('user.bonus.claim') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning fw-semibold">
                        <i class="fas fa-check me-2"></i> Claim Bonus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .welcome-header h2 {
        letter-spacing: -0.03em;
    }

    .bonus-hero-card {
        border-radius: 1.5rem;
        background: linear-gradient(135deg, rgba(10, 14, 25, 0.95), rgba(15, 23, 42, 0.96));
        border: 1px solid rgba(255, 255, 255, 0.08);
        overflow: hidden;
    }

    .bonus-hero-card-side {
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.88), rgba(10, 14, 25, 0.98));
        min-height: 220px;
    }

    .bonus-hero-card h3,
    .bonus-hero-card .fw-bold,
    .bonus-hero-card .hero-stat-box strong,
    .bonus-hero-card .card-title,
    .bonus-hero-card .text-white {
        color: rgba(248, 250, 252, 0.98) !important;
    }

    .bonus-hero-card p,
    .bonus-hero-card .text-muted,
    .bonus-hero-card .hero-stat-box span,
    .bonus-hero-card .card-body p {
        color: rgba(248, 250, 252, 0.9) !important;
    }

    .bonus-hero-card .badge {
        color: #60a5fa;
    }

    .hero-icon {
        width: 78px;
        height: 78px;
        border-radius: 1.25rem;
        border: 1px solid rgba(59, 130, 246, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #60a5fa;
        background: rgba(59, 130, 246, 0.12);
        margin: 0 auto;
        font-size: 1.75rem;
    }

    .bonus-hero-card .text-muted {
        color: rgba(226, 232, 240, 0.78) !important;
    }

    .hero-stat-box {
        background: rgba(255, 255, 255, 0.04);
        border-radius: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 1rem 1.25rem;
        min-width: 160px;
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.2);
        color: #e8e6f0;
    }

    .hero-stat-box span,
    .hero-stat-box strong {
        color: #e8e6f0;
    }

    .bonus-section-card {
        border-radius: 1.4rem;
        border: 1px solid rgba(255, 255, 255, 0.08);
        background: rgba(10, 14, 25, 0.95);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        overflow: hidden;
        position: relative;
    }

    .bonus-section-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 60px rgba(0, 0, 0, 0.25);
    }

    .bonus-section-card.locked {
        opacity: 0.9;
    }

    .bonus-section-card.locked::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(10, 14, 25, 0.65), rgba(10, 14, 25, 0.85));
        pointer-events: none;
    }

    .section-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        background: rgba(59, 130, 246, 0.12);
        color: #60a5fa;
        padding: 0.75rem 1rem;
        border-radius: 999px;
        font-size: 0.9rem;
    }

    .section-pill-number {
        width: 32px;
        height: 32px;
        display: grid;
        place-items: center;
        background: #60a5fa;
        color: #111;
        border-radius: 50%;
        font-weight: 700;
    }

    .info-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: rgba(255,255,255,0.06);
        color: #c8d0e0;
        border-radius: 999px;
        padding: 0.5rem 0.85rem;
        font-size: 0.85rem;
    }

    .requirement-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(255,255,255,0.05);
        color: #d1d5db;
        border-radius: 999px;
        padding: 0.55rem 0.9rem;
        font-size: 0.81rem;
        border: 1px solid rgba(255,255,255,0.08);
    }

    .modal-bonus-icon {
        width: 84px;
        height: 84px;
        border-radius: 1.5rem;
        background: rgba(59, 130, 246, 0.18);
        color: #60a5fa;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 2.2rem;
    }

    .progress-sm {
        height: 10px;
    }

    .bonus-section-card .card-footer {
        position: relative;
        z-index: 1;
    }

    .stretched-link {
        position: absolute;
        inset: 0;
        z-index: 1;
    }

    .card-footer .fas {
        position: relative;
        z-index: 2;
    }

    @media (max-width: 992px) {
        .bonus-hero-card {
            border-radius: 1.25rem;
        }
    }

    @media (max-width: 767.98px) {
        .welcome-header {
            gap: 1rem;
        }
        .hero-stat-box {
            min-width: 100%;
        }
        .bonus-hero-card-side {
            min-height: 180px;
        }
    }

    @media (max-width: 575.98px) {
        .bonus-section-card {
            border-radius: 1.2rem;
        }
        .section-pill {
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .info-chip,
        .requirement-pill {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush