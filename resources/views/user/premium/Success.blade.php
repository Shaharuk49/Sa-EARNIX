@extends('layouts.user')

@section('title', 'Upgrade Successful - SA EarniX')

@section('content')
<style>
    .ps-page {
        background: #0a0a14;
        min-height: 100vh;
        padding: 1.5rem 0.75rem 3rem;
        color: #e8e6f0;
        display: flex;
        align-items: center;
    }
    .ps-wrap {
        max-width: 460px;
        margin: 0 auto;
        width: 100%;
    }
    .ps-card {
        background: #14141f;
        border: 1px solid rgba(74,222,128,0.25);
        border-radius: 20px;
        padding: 2.25rem 1.75rem;
        text-align: center;
    }
    .ps-check-badge {
        width: 68px;
        height: 68px;
        margin: 0 auto 1.25rem;
        border-radius: 50%;
        background: rgba(74,222,128,0.12);
        border: 1px solid rgba(74,222,128,0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.9rem;
        color: #4ade80;
    }
    .ps-title {
        color: #f4f2fa;
        font-weight: 700;
        font-size: 1.35rem;
        margin-bottom: 0.5rem;
    }
    .ps-subtitle {
        color: #9a97ad;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }
    .ps-summary {
        background: #191926;
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 14px;
        padding: 1rem 1.1rem;
        text-align: left;
        margin-bottom: 1.75rem;
    }
    .ps-summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        padding: 0.35rem 0;
    }
    .ps-summary-row + .ps-summary-row {
        border-top: 1px solid rgba(255,255,255,0.05);
    }
    .ps-summary-label { color: #9a97ad; }
    .ps-summary-value { color: #e8e6f0; font-weight: 600; }
    .ps-summary-value.gold { color: #f5c451; }

    .ps-btn-gold {
        display: block;
        width: 100%;
        background: linear-gradient(135deg, #f6c453, #e0a520);
        border: none;
        color: #1a1206 !important;
        font-weight: 700;
        font-size: 1rem;
        padding: 0.85rem;
        border-radius: 14px;
        text-decoration: none;
        box-shadow: 0 10px 24px rgba(240,180,41,0.2);
        margin-bottom: 0.75rem;
    }
    .ps-btn-gold:hover { filter: brightness(1.05); color: #1a1206 !important; }

    .ps-btn-ghost {
        display: block;
        width: 100%;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.12);
        color: #e8e6f0 !important;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.8rem;
        border-radius: 14px;
        text-decoration: none;
    }
    .ps-btn-ghost:hover { background: rgba(255,255,255,0.07); }
</style>

<div class="ps-page">
    <div class="ps-wrap">
        <div class="ps-card">
            <div class="ps-check-badge"><i class="fas fa-check"></i></div>
            <div class="ps-title">Payment Successful!</div>
            <div class="ps-subtitle">Your account has been upgraded to Premium. Enjoy full access to every course and income opportunity.</div>

            <div class="ps-summary">
                <div class="ps-summary-row">
                    <span class="ps-summary-label">Amount Paid</span>
                    <span class="ps-summary-value gold">৳{{ number_format($premium->amount, 0) }}</span>
                </div>
                <div class="ps-summary-row">
                    <span class="ps-summary-label">Payment Method</span>
                    <span class="ps-summary-value">{{ ucfirst($premium->payment_method) }}</span>
                </div>
                <div class="ps-summary-row">
                    <span class="ps-summary-label">Transaction ID</span>
                    <span class="ps-summary-value">{{ $premium->gateway_transaction_id }}</span>
                </div>
                <div class="ps-summary-row">
                    <span class="ps-summary-label">Date</span>
                    <span class="ps-summary-value">{{ $premium->paid_at->format('d M Y, h:i A') }}</span>
                </div>
            </div>

            <a href="{{ route('user.home') }}" class="ps-btn-gold">
                <i class="fas fa-house me-1"></i> Go to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection