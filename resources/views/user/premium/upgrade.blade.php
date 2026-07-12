@extends('layouts.user')

@section('title', 'Premium Upgrade - SA EarniX')

@section('content')
<style>
    .premium-page {
        background: #0a0a14;
        min-height: 100vh;
        padding: 1.5rem 0.75rem 3rem;
        color: #e8e6f0;
    }
    .premium-wrap {
        max-width: 480px;
        margin: 0 auto;
    }
    .premium-back {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(240,180,41,0.08);
        border: 1px solid rgba(240,180,41,0.25);
        color: #f0b429;
        font-size: 0.85rem;
        padding: 0.4rem 1rem;
        border-radius: 999px;
        text-decoration: none;
        margin-bottom: 1.5rem;
    }
    .premium-back:hover { color: #ffce54; background: rgba(240,180,41,0.14); }

    .premium-icon-badge {
        width: 56px;
        height: 56px;
        margin: 0 auto 1rem;
        border-radius: 16px;
        background: linear-gradient(135deg, #f0b429, #c9861a);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: #1a1206;
        box-shadow: 0 8px 24px rgba(240,180,41,0.25);
    }
    .premium-title {
        text-align: center;
        color: #f5c451;
        font-weight: 700;
        font-size: 1.4rem;
        margin-bottom: 0.15rem;
    }
    .premium-subtitle {
        text-align: center;
        color: #9a97ad;
        font-size: 0.9rem;
        margin-bottom: 1.75rem;
    }

    .premium-info {
        display: flex;
        gap: 0.6rem;
        background: rgba(240,180,41,0.06);
        border: 1px solid rgba(240,180,41,0.18);
        border-radius: 14px;
        padding: 0.9rem 1rem;
        font-size: 0.85rem;
        color: #cfcbe0;
        margin-bottom: 1.5rem;
    }
    .premium-info i { color: #f0b429; margin-top: 0.15rem; }

    .premium-section-label {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #f0b429;
        font-weight: 700;
        margin: 0 0.1rem 0.6rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .premium-card {
        background: #14141f;
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 16px;
        padding: 1.1rem 1.1rem 1.3rem;
        margin-bottom: 1.5rem;
    }

    .payment-number-row {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        background: #191926;
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 14px;
        padding: 0.85rem 1rem;
    }
    .payment-number-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, #f0b429, #c9861a);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1a1206;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .payment-number-body { flex: 1; min-width: 0; }
    .payment-number-label { font-size: 0.78rem; color: #9a97ad; }
    .payment-number-value { font-size: 1.05rem; font-weight: 700; color: #f5c451; letter-spacing: 0.02em; }
    .payment-copy-btn {
        background: rgba(240,180,41,0.12);
        border: 1px solid rgba(240,180,41,0.3);
        color: #f0b429;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.4rem 0.85rem;
        border-radius: 999px;
        white-space: nowrap;
    }
    .payment-copy-btn:hover { background: rgba(240,180,41,0.22); color: #ffce54; }
    .payment-copy-btn.copied { background: rgba(74,222,128,0.15); border-color: rgba(74,222,128,0.4); color: #4ade80; }

    .premium-hint {
        font-size: 0.78rem;
        color: #726f85;
        margin-top: 0.75rem;
    }

    .premium-form-label {
        font-size: 0.8rem;
        color: #b5b2c8;
        font-weight: 600;
        margin-bottom: 0.4rem;
    }
    .premium-input, .premium-select {
        width: 100%;
        background: #191926 !important;
        border: 1px solid rgba(255,255,255,0.08) !important;
        color: #e8e6f0 !important;
        border-radius: 12px;
        padding: 0.7rem 0.9rem;
        font-size: 0.95rem;
    }
    .premium-input::placeholder { color: #5f5c72; }
    .premium-input:focus, .premium-select:focus {
        border-color: rgba(240,180,41,0.5) !important;
        box-shadow: 0 0 0 3px rgba(240,180,41,0.12) !important;
        background: #1d1d2c !important;
    }
    .premium-small { font-size: 0.75rem; color: #726f85; margin-top: 0.3rem; display: block; }

    .premium-pay-btn {
        width: 100%;
        background: linear-gradient(135deg, #f6c453, #e0a520);
        border: none;
        color: #1a1206;
        font-weight: 700;
        font-size: 1.02rem;
        padding: 0.9rem;
        border-radius: 14px;
        box-shadow: 0 10px 24px rgba(240,180,41,0.22);
    }
    .premium-pay-btn:hover { filter: brightness(1.05); color: #1a1206; }
    .premium-cancel-link {
        display: block;
        text-align: center;
        color: #9a97ad;
        font-size: 0.85rem;
        margin-top: 0.85rem;
        text-decoration: none;
    }
    .premium-cancel-link:hover { color: #cfcbe0; }

    .premium-secure-note {
        display: flex;
        gap: 0.6rem;
        align-items: flex-start;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 14px;
        padding: 0.9rem 1rem;
        font-size: 0.8rem;
        color: #9a97ad;
        margin-top: 1.25rem;
    }
    .premium-secure-note i { color: #f0b429; margin-top: 0.15rem; }

    .premium-errors {
        background: rgba(239,68,68,0.1);
        border: 1px solid rgba(239,68,68,0.3);
        color: #fca5a5;
        border-radius: 12px;
        padding: 0.8rem 1rem;
        font-size: 0.85rem;
        margin-bottom: 1.2rem;
    }
</style>

<div class="premium-page">
    <div class="premium-wrap">

        <a href="{{ route('user.home') }}" class="premium-back">
            <i class="fas fa-arrow-left"></i> Back
        </a>

        <div class="premium-icon-badge">
            <i class="fas fa-credit-card"></i>
        </div>
        <div class="premium-title">Complete Payment</div>
        <div class="premium-subtitle">Fee: ৳{{ number_format($amount, 0) }}</div>

        @if($errors->any())
            <div class="premium-errors">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="premium-info">
            <i class="fas fa-circle-info"></i>
            <div>Send the premium upgrade fee to the number below via <strong>Send Money</strong>, then enter the Transaction ID in the form.</div>
        </div>

        <div class="premium-section-label">
            <i class="fas fa-mobile-screen-button"></i> Payment Number
        </div>
        <div class="payment-number-row" style="margin-bottom:1.5rem;">
            <div class="payment-number-icon"><i class="fas fa-wallet"></i></div>
            <div class="payment-number-body">
                <div class="payment-number-label">Send Money to</div>
                <div class="payment-number-value" id="paymentNumberValue">{{ $paymentPhone ?: 'Contact admin for payment number' }}</div>
            </div>
            @if($paymentPhone)
                <button type="button" class="payment-copy-btn" id="copyNumberBtn" onclick="copyPaymentNumber()">Copy</button>
            @endif
        </div>
        <div class="premium-hint" style="margin-top:-1rem;margin-bottom:1.5rem;">Admin can update this number and the fee amount from settings.</div>

        <div class="premium-card">
            <div class="premium-section-label">
                <i class="fas fa-circle-check"></i> Confirm Payment
            </div>

            <form method="POST" action="{{ route('premium.upgrade.process') }}">
                @csrf

                <div class="mb-3">
                    <div class="premium-form-label">Payment Method</div>
                    <select name="payment_method" class="form-select premium-select" required>
                        <option value="">Select a method</option>
                        <option value="bkash">bKash</option>
                        <option value="nagad">Nagad</option>
                        <option value="rocket">Rocket</option>
                        <option value="card">Card</option>
                    </select>
                </div>

                <div class="mb-4">
                    <div class="premium-form-label">Transaction ID</div>
                    <input type="text" name="transaction_ref" class="form-control premium-input"
                           placeholder="Example: TXN123456" required>
                    <small class="premium-small">Enter the transaction/reference number from your payment.</small>
                </div>

                <button type="submit" class="btn premium-pay-btn">
                    <i class="fas fa-check me-1"></i> Pay ৳{{ number_format($amount, 0) }} & Complete Upgrade
                </button>
                <a href="{{ route('user.home') }}" class="premium-cancel-link">Cancel</a>
            </form>
        </div>

        <div class="premium-secure-note">
            <i class="fas fa-shield-halved"></i>
            <div><strong style="color:#cfcbe0;">Safe Payment:</strong> Your payment information is secured. We use trusted payment gateways for all transactions.</div>
        </div>

    </div>
</div>

<script>
function copyPaymentNumber() {
    const text = document.getElementById('paymentNumberValue').innerText.trim();
    const btn = document.getElementById('copyNumberBtn');
    navigator.clipboard.writeText(text).then(function () {
        const original = btn.innerText;
        btn.innerText = 'Copied';
        btn.classList.add('copied');
        setTimeout(function () {
            btn.innerText = original;
            btn.classList.remove('copied');
        }, 1500);
    });
}
</script>
@endsection