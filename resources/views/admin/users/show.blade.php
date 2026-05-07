@extends('layouts.admin')
@section('title', 'User Details')
@section('page-title', 'User Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold">{{ $user->name }}</h4>
        <div class="text-muted">Affiliate ID: <code>{{ $user->affiliate_id }}</code></div>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Back to Users
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Direct Referrals</div>
                <div class="h4 mb-0 fw-bold">{{ $stats['direct_referrals'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Earned Badges</div>
                <div class="h4 mb-0 fw-bold">{{ $stats['earned_badges'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Total Commission</div>
                <div class="h4 mb-0 fw-bold">৳{{ number_format($stats['total_commissions'], 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Withdraw Requested</div>
                <div class="h4 mb-0 fw-bold">৳{{ number_format($stats['total_withdraw_requested'], 2) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">Basic Information</div>
            <div class="card-body small">
                <div class="mb-2"><strong>Name:</strong> {{ $user->name }}</div>
                <div class="mb-2"><strong>Username:</strong> {{ $user->username }}</div>
                <div class="mb-2"><strong>Email:</strong> {{ $user->email ?? '—' }}</div>
                <div class="mb-2"><strong>Phone:</strong> {{ $user->phone ?? '—' }}</div>
                <div class="mb-2"><strong>Upline:</strong> {{ $user->upline?->name ?? '—' }}</div>
                <div class="mb-2"><strong>Status:</strong>
                    @if($user->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                    @if($user->is_premium)
                        <span class="badge bg-warning text-dark">Premium</span>
                    @endif
                </div>
                <div class="mb-2"><strong>Joined At:</strong> {{ optional($user->joined_at)->format('d M Y h:i A') ?? '—' }}</div>
                <div class="mb-0"><strong>Last Login:</strong> {{ optional($user->last_login_at)->format('d M Y h:i A') ?? '—' }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">Wallet & Registration Payment</div>
            <div class="card-body small">
                <div class="mb-2"><strong>Current Balance:</strong> ৳{{ number_format((float)($user->walletAccount->current_balance ?? 0), 2) }}</div>
                <div class="mb-2"><strong>Hold Balance:</strong> ৳{{ number_format((float)($user->walletAccount->hold_balance ?? 0), 2) }}</div>
                <div class="mb-2"><strong>Total Earned:</strong> ৳{{ number_format((float)($user->walletAccount->total_earned ?? 0), 2) }}</div>
                <div class="mb-3"><strong>Total Withdrawn:</strong> ৳{{ number_format((float)($user->walletAccount->total_withdrawn ?? 0), 2) }}</div>

                @if($registrationPayment)
                    <hr>
                    <div class="mb-2"><strong>Registration Amount:</strong> ৳{{ number_format($registrationPayment->amount, 2) }}</div>
                    <div class="mb-2"><strong>Method:</strong> {{ ucfirst($registrationPayment->payment_method ?? 'manual') }}</div>
                    <div class="mb-2"><strong>Transaction ID:</strong> {{ $registrationPayment->gateway_transaction_id ?? '—' }}</div>
                    <div class="mb-2"><strong>Status:</strong>
                        @if($registrationPayment->status === 'paid')
                            <span class="badge bg-success">Paid</span>
                        @elseif($registrationPayment->status === 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @else
                            <span class="badge bg-danger">Failed</span>
                        @endif
                    </div>
                @else
                    <div class="text-muted">No registration payment record.</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Recent Wallet Transactions</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Source</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $tx)
                            <tr>
                                <td>{{ $tx->created_at->format('d M Y h:i A') }}</td>
                                <td>{{ ucfirst($tx->type) }}</td>
                                <td>{{ $tx->source_type }}</td>
                                <td>৳{{ number_format($tx->amount, 2) }}</td>
                                <td><span class="badge bg-secondary">{{ ucfirst($tx->status) }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-3 text-muted">No transactions.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Recent Withdraw Requests</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($withdrawRequests as $wr)
                            <tr>
                                <td>{{ $wr->created_at->format('d M Y') }}</td>
                                <td>৳{{ number_format($wr->amount, 2) }}</td>
                                <td>
                                    @if($wr->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($wr->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-3 text-muted">No withdraw requests.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
