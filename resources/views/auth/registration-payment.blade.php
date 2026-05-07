@extends('layouts.auth')
@section('title', 'Registration Payment')

@section('content')
<div class="auth-card mx-auto my-4" data-reveal>
    <div class="auth-header">
        <div class="auth-kicker mb-3">
            <i class="fas fa-credit-card"></i>
            Secure payment submission
        </div>
        <h1>Registration Payment</h1>
        <p>Step 2 of 3</p>
    </div>

    <div class="auth-stepper">
        <div class="auth-step is-dim" style="opacity:.65">
            <span class="step-badge bg-secondary shadow-none">1</span>
            <span class="small">তথ্য পূরণ</span>
        </div>
        <div class="auth-step">
            <span class="step-badge">2</span>
            <span class="fw-bold text-primary small">Payment</span>
        </div>
        <div class="auth-step is-dim">
            <span class="step-badge bg-secondary shadow-none">3</span>
            <span class="small text-muted">Complete</span>
        </div>
    </div>

    <div class="auth-body">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="alert alert-warning rounded-4 mb-4">
            <div class="fw-bold mb-1">Registration fee</div>
            <div class="fs-4">{{ number_format($amount, 0) }} Tk</div>
        </div>

        <form method="POST" action="{{ route('payment.process') }}" class="auth-form">
            @csrf

            <div class="auth-field">
                <label class="auth-label" for="payment-method">Payment Method</label>
                <div class="auth-input-wrap">
                    <i class="fas fa-building-columns auth-input-icon"></i>
                    <select id="payment-method" name="payment_method" class="form-select auth-select" required>
                    <option value="">Select a method</option>
                    <option value="bkash">bKash</option>
                    <option value="nagad">Nagad</option>
                    <option value="rocket">Rocket</option>
                    <option value="card">Card</option>
                    </select>
                </div>
            </div>

            <div class="auth-field">
                <label class="auth-label" for="transaction-ref">Transaction Reference</label>
                <div class="auth-input-wrap">
                    <i class="fas fa-receipt auth-input-icon"></i>
                    <input id="transaction-ref" type="text" name="transaction_ref" class="form-control auth-control" placeholder="Example: TXN123456" required>
                </div>
            </div>

            <button type="submit" class="btn btn-auth py-3">Pay {{ number_format($amount, 0) }} Tk and Complete Registration</button>
        </form>
    </div>
</div>
@endsection
