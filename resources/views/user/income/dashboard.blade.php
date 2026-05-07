@extends('layouts.user')

@section('title', 'Income Dashboard - SA EarniX')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Income Dashboard</h3>

    {{-- Current Balance --}}
    <div class="card border-0 shadow-sm mb-4 bg-light">
        <div class="card-body text-center">
            <small class="text-muted d-block mb-2">Available Balance</small>
            <h2 class="mb-1">৳ {{ number_format((float) $walletAccount->current_balance, 2) }}</h2>
            <small class="text-muted">Last updated: {{ now()->format('d M Y, H:i') }}</small>
        </div>
    </div>

    {{-- Income Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-2">Today Income</div>
                    <h4 class="mb-0 text-success">৳ {{ number_format($todayIncome, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-2">Yesterday Income</div>
                    <h4 class="mb-0 text-info">৳ {{ number_format($yesterdayIncome, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6 mt-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-2">7 Days Income</div>
                    <h4 class="mb-0 text-warning">৳ {{ number_format($sevenDayIncome, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6 mt-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-2">30 Days Income</div>
                    <h4 class="mb-0">৳ {{ number_format($thirtyDayIncome, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Income --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="text-muted small mb-2">Total Income (All Time)</div>
            <h3 class="mb-0 text-primary">৳ {{ number_format($totalIncome, 2) }}</h3>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Income History</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>
                                <small class="text-muted">
                                    {{ $transaction->created_at->format('d M Y, H:i') }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($transaction->source_type) }}</span>
                            </td>
                            <td>
                                <span class="text-success fw-bold">৳ {{ number_format($transaction->amount, 2) }}</span>
                            </td>
                            <td>
                                <small>৳ {{ number_format($transaction->balance_after, 2) }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No transactions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="card-footer border-top">
                {{ $transactions->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
