@extends('layouts.user')

@section('title', 'Withdraw - SA EarniX')

@section('content')
<div class="container py-4">

    <h4 class="fw-bold mb-4">Withdraw</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Balance Card --}}
    <div class="card border-0 mb-4 text-white text-center py-4"
         style="background: linear-gradient(135deg, #0ea5e9, #38bdf8); border-radius:16px;">
        <div class="small mb-1" style="opacity:0.85;">Available Balance</div>
        <div class="display-6 fw-bold">৳ {{ number_format((float) $wallet->current_balance, 0) }}</div>
    </div>

    {{-- Withdraw Form --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('user.withdraw.process') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Withdraw Amount</label>
                    <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror"
                           placeholder="Enter amount" min="500" value="{{ old('amount') }}" required>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text text-muted">Minimum: ৳500</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Select Payment Method</label>
                    <select name="withdraw_method_id" class="form-select @error('withdraw_method_id') is-invalid @enderror" required>
                        <option value="">Choose Method</option>
                        @foreach($methods as $method)
                            <option value="{{ $method->id }}" {{ old('withdraw_method_id') == $method->id ? 'selected' : '' }}>
                                {{ $method->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('withdraw_method_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Account Number</label>
                    <input type="text" name="account_number"
                           class="form-control @error('account_number') is-invalid @enderror"
                           placeholder="Enter account number" value="{{ old('account_number') }}" required>
                    @error('account_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small">Transaction Password</label>
                    <input type="password" name="transaction_password"
                           class="form-control @error('transaction_password') is-invalid @enderror"
                           placeholder="Enter password" required>
                    @error('transaction_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn w-100 text-white fw-bold py-2"
                        style="background: linear-gradient(135deg, #ef4444, #f87171); border-radius:10px;">
                    Withdraw Now
                </button>
            </form>
        </div>
    </div>

    {{-- Withdraw History --}}
    <h5 class="fw-bold mb-3">Withdraw History</h5>

    @forelse($history as $req)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-bold small">৳ {{ number_format($req->amount, 0) }}</div>
                    <div class="text-muted" style="font-size:0.8rem;">{{ $req->method->name ?? '-' }} — {{ $req->account_number }}</div>
                    <div class="text-muted" style="font-size:0.75rem;">{{ $req->requested_at ? $req->requested_at->format('d M Y, H:i') : $req->created_at->format('d M Y, H:i') }}</div>
                </div>
                <span class="badge rounded-pill
                    {{ $req->status === 'approved' ? 'bg-success' : ($req->status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                    {{ ucfirst($req->status) }}
                </span>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-4">
            <p>No withdraw requests yet.</p>
        </div>
    @endforelse

    {{ $history->links() }}
</div>
@endsection
