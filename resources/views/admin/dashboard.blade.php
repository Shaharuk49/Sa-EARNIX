@extends('layouts.admin')
@section('title', 'Dashboard – SA EarniX Admin')
@section('page-title', 'Dashboard')

@section('content')
<style>
    .dashboard-metric-card {
        min-height: 130px;
    }

    .admin-action-btn {
        min-width: 120px;
        font-weight: 700;
        border-radius: 12px;
        letter-spacing: .01em;
    }

    .admin-action-btn.btn-sm {
        padding: .45rem .85rem;
    }
</style>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h4 class="mb-1 fw-bold">Admin Dashboard</h4>
        <p class="text-muted mb-0">Live summary of users, payments, and withdrawal operations.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.registrations.index') }}" class="btn btn-outline-primary btn-sm admin-action-btn">
            <i class="fas fa-money-check-dollar me-1"></i> Registration Queue
        </a>
        <a href="{{ route('admin.withdraw.index') }}" class="btn btn-primary btn-sm admin-action-btn">
            <i class="fas fa-wallet me-1"></i> Withdraw Requests
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
        <div class="card border-0 shadow-sm h-100 dashboard-metric-card">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase">Total Users</div>
                    <div class="h3 mb-0 fw-bold js-count" data-value="{{ (int) $stats['total_users'] }}">0</div>
                </div>
                <span class="badge text-bg-primary-subtle text-primary rounded-pill p-2"><i class="fas fa-users"></i></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
        <div class="card border-0 shadow-sm h-100 dashboard-metric-card">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase">Active Users</div>
                    <div class="h3 mb-0 fw-bold js-count" data-value="{{ (int) $stats['active_users'] }}">0</div>
                </div>
                <span class="badge text-bg-success-subtle text-success rounded-pill p-2"><i class="fas fa-user-check"></i></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
        <div class="card border-0 shadow-sm h-100 dashboard-metric-card">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase">Premium Users</div>
                    <div class="h3 mb-0 fw-bold js-count" data-value="{{ (int) $stats['premium_users'] }}">0</div>
                </div>
                <span class="badge text-bg-warning-subtle text-warning rounded-pill p-2"><i class="fas fa-crown"></i></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
        <div class="card border-0 shadow-sm h-100 dashboard-metric-card">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase">Today Joins</div>
                    <div class="h3 mb-0 fw-bold js-count" data-value="{{ (int) $stats['today_registrations'] }}">0</div>
                </div>
                <span class="badge text-bg-info-subtle text-info rounded-pill p-2"><i class="fas fa-calendar-day"></i></span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
        <a href="{{ route('admin.registrations.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 dashboard-metric-card">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Pending Registrations</div>
                        <div class="h3 mb-0 fw-bold js-count" data-value="{{ (int) $stats['pending_registrations'] }}">0</div>
                        <small class="text-muted">Needs admin approval</small>
                    </div>
                    <span class="badge text-bg-danger-subtle text-danger rounded-pill p-2"><i class="fas fa-clock"></i></span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
        <a href="{{ route('admin.withdraw.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 dashboard-metric-card">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Pending Withdraws</div>
                        <div class="h3 mb-0 fw-bold js-count" data-value="{{ (int) $stats['pending_withdraws'] }}">0</div>
                        <small class="text-muted">Needs payout action</small>
                    </div>
                    <span class="badge text-bg-danger-subtle text-danger rounded-pill p-2"><i class="fas fa-wallet"></i></span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
        <div class="card border-0 shadow-sm h-100 dashboard-metric-card">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small fw-semibold text-uppercase">Total Paid Out</div>
                    <div class="h3 mb-0 fw-bold">৳<span class="js-count" data-value="{{ (int) $stats['total_paid_out'] }}">0</span></div>
                    <small class="text-muted">Completed credit transactions</small>
                </div>
                <span class="badge text-bg-primary-subtle text-primary rounded-pill p-2"><i class="fas fa-hand-holding-dollar"></i></span>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <div>
                    <div class="fw-semibold">Recent Registrations</div>
                    <small class="text-muted">Latest joined accounts</small>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary admin-action-btn">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Name</th>
                                <th>Affiliate ID</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUsers as $user)
                                <tr>
                                    <td class="ps-3 fw-semibold">{{ $user->name }}</td>
                                    <td><code>{{ $user->affiliate_id }}</code></td>
                                    <td>
                                        @if($user->is_active)
                                            <span class="badge text-bg-success">Active</span>
                                        @else
                                            <span class="badge text-bg-secondary">Inactive</span>
                                        @endif
                                        @if($user->is_premium)
                                            <span class="badge text-bg-warning">Premium</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3 text-muted">{{ $user->created_at->format('d M, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">No users yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <div>
                    <div class="fw-semibold">Pending Withdraws</div>
                    <small class="text-muted">Requests waiting for decision</small>
                </div>
                <a href="{{ route('admin.withdraw.index') }}" class="btn btn-sm btn-outline-primary admin-action-btn">Manage</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">User</th>
                                <th>Amount</th>
                                <th class="pe-3">Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingWithdraws as $w)
                                <tr>
                                    <td class="ps-3 fw-semibold">{{ $w->user->name ?? '—' }}</td>
                                    <td><span class="badge text-bg-danger">৳{{ number_format($w->amount, 0) }}</span></td>
                                    <td class="pe-3">{{ $w->method->name ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-4">No pending requests.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const counters = document.querySelectorAll('.js-count');
    counters.forEach((counter) => {
        const target = parseInt(counter.dataset.value || '0', 10);
        const duration = 900;
        const startTime = performance.now();

        const step = (now) => {
            const progress = Math.min((now - startTime) / duration, 1);
            const value = Math.floor(progress * target);
            counter.textContent = value.toLocaleString();
            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                counter.textContent = target.toLocaleString();
            }
        };

        requestAnimationFrame(step);
    });
});
</script>
@endpush
