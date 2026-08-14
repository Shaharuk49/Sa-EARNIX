@extends('layouts.auth')
@section('title', 'Registration Payment')

@section('content')
<div class="pay-page">
    <div class="pay-topbar">
        <a href="{{ url()->previous() }}" class="pay-back" aria-label="Go back">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="pay-brand">
            <img class="site-logo pay-small" src="{{ asset('images/logo.png') }}" alt="SA EarniX">
        </div>
        <span class="pay-topbar-spacer"></span>
    </div>

    <div class="pay-card" data-reveal>

        <div class="pay-header">
            <div class="pay-kicker">
                <i class="fas fa-lock"></i>
                Secure payment submission
            </div>
            <h1>Registration Payment</h1>
            <p class="pay-subtitle">Step 2 of 3</p>
        </div>

        <div class="pay-stepper">
            <div class="pay-step is-done">
                <span class="pay-step-badge"><i class="fas fa-check"></i></span>
                <span class="pay-step-label">তথ্য পূরণ</span>
            </div>
            <div class="pay-step is-active">
                <span class="pay-step-badge">2</span>
                <span class="pay-step-label">Payment</span>
            </div>
            <div class="pay-step is-upcoming">
                <span class="pay-step-badge">3</span>
                <span class="pay-step-label">Complete</span>
            </div>
        </div>

        <div class="pay-body">

            @if(session('error'))
                <div class="pay-alert pay-alert-danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="pay-alert pay-alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="pay-fee-card">
                <div class="pay-fee-label">Registration fee</div>
                <div class="pay-fee-amount">৳ {{ number_format($amount, 0) }}</div>
                <div class="pay-fee-note">Payment সম্পন্ন হলে account active হবে</div>
            </div>

            <div class="pay-numbers-card">
                <div class="pay-numbers-head">
                    <i class="fas fa-building-columns"></i>
                    <div>
                        <div class="pay-numbers-title">Mobile Banking Numbers</div>
                        <div class="pay-numbers-sub">যেকোনো একটিতে Send Money করুন</div>
                    </div>
                </div>

                @php
                    $methods = [
                        ['key' => 'bkash', 'label' => 'bKash', 'icon' => 'fa-mobile-screen', 'class' => 'is-bkash', 'number' => $bkashNumber ?? $paymentPhone ?? null],
                        ['key' => 'nagad', 'label' => 'Nagad', 'icon' => 'fa-wallet', 'class' => 'is-nagad', 'number' => $nagadNumber ?? $paymentPhone ?? null],
                        ['key' => 'rocket', 'label' => 'Rocket', 'icon' => 'fa-rocket', 'class' => 'is-rocket', 'number' => $rocketNumber ?? $paymentPhone ?? null],
                    ];
                @endphp

                @foreach($methods as $method)
                    @if($method['number'])
                        <div class="pay-number-row" data-method="{{ $method['key'] }}">
                            <span class="pay-number-icon {{ $method['class'] }}">
                                <i class="fas {{ $method['icon'] }}"></i>
                            </span>
                            <span class="pay-number-info">
                                <span class="pay-number-name">{{ $method['label'] }}</span>
                                <span class="pay-number-value">{{ $method['number'] }}</span>
                            </span>
                            <button type="button" class="pay-copy-btn" data-copy="{{ $method['number'] }}" data-method-select="{{ $method['key'] }}">
                                Copy
                            </button>
                        </div>
                    @endif
                @endforeach

                @if(!collect($methods)->pluck('number')->filter()->count())
                    <div class="pay-number-row">
                        <span class="pay-number-value">Contact admin for payment number</span>
                    </div>
                @endif

                <div class="pay-numbers-note">Admin can update these numbers from settings.</div>
            </div>

            <form method="POST" action="{{ route('payment.process') }}" class="pay-form">
                @csrf

                <div class="pay-field">
                    <label class="pay-label" for="payment-method">Payment Method</label>
                    <div class="pay-input-wrap">
                        <i class="fas fa-building-columns pay-input-icon"></i>
                        <select id="payment-method" name="payment_method" class="pay-select" required>
                            <option value="">Select a method</option>
                            <option value="bkash">bKash</option>
                            <option value="nagad">Nagad</option>
                            <option value="rocket">Rocket</option>
                            <option value="card">Card</option>
                        </select>
                    </div>
                </div>

                <div class="pay-field">
                    <label class="pay-label" for="transaction-ref">Transaction Reference</label>
                    <div class="pay-input-wrap">
                        <i class="fas fa-receipt pay-input-icon"></i>
                        <input id="transaction-ref" type="text" name="transaction_ref" class="pay-control" placeholder="Example: TXN123456" required>
                    </div>
                </div>

                <div class="pay-field">
                    <label class="pay-label" for="mobile-number">Mobile Number</label>
                    <div class="pay-input-wrap">
                        <i class="fas fa-mobile-screen pay-input-icon"></i>
                        <input id="mobile-number" type="tel" name="mobile_number" class="pay-control" placeholder="Example: 01712345678" maxlength="11" required>
                    </div>
                    <small class="pay-hint">Bangladeshi number only (e.g. 01712345678)</small>
                </div>

                <button type="submit" class="pay-submit">
                    <i class="fas fa-shield-halved"></i>
                    Pay {{ number_format($amount, 0) }} Tk and Complete Registration
                </button>
            </form>

            <a href="{{ url()->previous() }}" class="pay-back-link">
                <i class="fas fa-arrow-left"></i> তথ্য পূরণে ফিরে যান
            </a>

        </div>
    </div>
</div>

<style>
    :root{
        --pay-bg-1: #0c0e16;
        --pay-bg-2: #141a2b;
        --pay-panel: #171d2c;
        --pay-panel-alt: #1b2233;
        --pay-border: rgba(255,255,255,.08);
        --pay-gold: #f0b429;
        --pay-gold-soft: #e0a51e;
        --pay-text: #f4f6fb;
        --pay-text-dim: #93a0b8;
        --pay-danger: #ef5461;
        --pay-radius-lg: 22px;
        --pay-radius-md: 14px;
    }

    .pay-page{
        min-height: 100vh;
        background:
            radial-gradient(1200px 600px at 15% -10%, rgba(240,180,41,.08), transparent 55%),
            radial-gradient(900px 500px at 110% 10%, rgba(64,90,180,.14), transparent 60%),
            linear-gradient(180deg, var(--pay-bg-1), var(--pay-bg-2));
        color: var(--pay-text);
        padding: 0 0 48px;
        font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    .pay-topbar{
        display:flex;
        align-items:center;
        gap: 12px;
        padding: 18px 20px;
        max-width: 480px;
        margin: 0 auto;
    }
    .pay-back{
        width: 38px; height: 38px;
        display:flex; align-items:center; justify-content:center;
        border-radius: 999px;
        background: rgba(255,255,255,.05);
        border: 1px solid var(--pay-border);
        color: var(--pay-text);
        text-decoration:none;
        flex-shrink:0;
        transition: background .15s ease;
    }
    .pay-back:hover{ background: rgba(255,255,255,.1); }
    .pay-brand{ display:flex; align-items:center; gap:8px; }
    .pay-brand-mark{
        font-weight: 800;
        color: var(--pay-bg-1);
        background: var(--pay-gold);
        border-radius: 8px;
        padding: 2px 7px;
        font-size: .8rem;
        letter-spacing: .04em;
    }
    .pay-brand-name{ font-weight: 700; letter-spacing: .01em; }
    .pay-topbar-spacer{ flex: 1; }

    .pay-card{
        max-width: 480px;
        margin: 0 auto;
        padding: 4px 20px 0;
    }

    .pay-header{ text-align:center; padding: 6px 6px 20px; }
    .pay-kicker{
        display:inline-flex; align-items:center; gap:8px;
        font-size: .72rem;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--pay-gold);
        background: rgba(240,180,41,.1);
        border: 1px solid rgba(240,180,41,.25);
        border-radius: 999px;
        padding: 6px 14px;
        margin-bottom: 14px;
    }
    .pay-header h1{
        font-size: clamp(1.5rem, 5vw, 1.9rem);
        font-weight: 800;
        margin: 0 0 4px;
        line-height: 1.2;
    }
    .pay-subtitle{ color: var(--pay-text-dim); margin: 0; font-size: .95rem; }

    .pay-stepper{
        display:flex;
        align-items:flex-start;
        justify-content: space-between;
        gap: 6px;
        padding: 4px 4px 26px;
    }
    .pay-step{
        flex:1;
        display:flex;
        flex-direction:column;
        align-items:center;
        gap:8px;
        text-align:center;
    }
    .pay-step-badge{
        width: 34px; height:34px;
        border-radius: 999px;
        display:flex; align-items:center; justify-content:center;
        font-weight:700;
        font-size:.9rem;
        background: rgba(255,255,255,.06);
        color: var(--pay-text-dim);
        border: 1px solid var(--pay-border);
    }
    .pay-step.is-done .pay-step-badge{
        background: rgba(60,190,120,.15);
        border-color: rgba(60,190,120,.4);
        color: #4ade80;
    }
    .pay-step.is-active .pay-step-badge{
        background: var(--pay-gold);
        border-color: var(--pay-gold);
        color: var(--pay-bg-1);
        box-shadow: 0 0 0 5px rgba(240,180,41,.15);
    }
    .pay-step-label{ font-size: .75rem; color: var(--pay-text-dim); }
    .pay-step.is-done .pay-step-label{ color: #4ade80; }
    .pay-step.is-active .pay-step-label{ color: var(--pay-gold); font-weight:700; }

    .pay-body{ display:flex; flex-direction:column; gap: 18px; }

    .pay-alert{ border-radius: var(--pay-radius-md); padding: 12px 16px; font-size:.9rem; }
    .pay-alert-danger{
        background: rgba(239,84,97,.12);
        border: 1px solid rgba(239,84,97,.35);
        color: #fda4ac;
    }

    .pay-fee-card{
        background: linear-gradient(160deg, rgba(240,180,41,.14), rgba(240,180,41,.04));
        border: 1px solid rgba(240,180,41,.3);
        border-radius: var(--pay-radius-lg);
        padding: 18px 20px;
    }
    .pay-fee-label{
        text-transform: uppercase;
        font-size: .7rem;
        letter-spacing: .08em;
        color: var(--pay-gold-soft);
        font-weight: 700;
        margin-bottom: 6px;
    }
    .pay-fee-amount{
        font-size: clamp(1.8rem, 7vw, 2.2rem);
        font-weight: 800;
        color: var(--pay-gold);
        line-height:1;
    }
    .pay-fee-note{ margin-top: 8px; font-size: .8rem; color: var(--pay-text-dim); }

    .pay-numbers-card{
        background: var(--pay-panel);
        border: 1px solid var(--pay-border);
        border-radius: var(--pay-radius-lg);
        padding: 18px;
    }
    .pay-numbers-head{ display:flex; align-items:flex-start; gap: 10px; margin-bottom: 14px; }
    .pay-numbers-head i{ color: var(--pay-gold); margin-top: 3px; }
    .pay-numbers-title{ font-weight: 700; font-size: .95rem; }
    .pay-numbers-sub{ font-size: .78rem; color: var(--pay-text-dim); margin-top:2px; }

    .pay-number-row{
        display:flex;
        align-items:center;
        gap: 12px;
        background: var(--pay-panel-alt);
        border: 1px solid var(--pay-border);
        border-radius: var(--pay-radius-md);
        padding: 12px 14px;
        margin-bottom: 10px;
    }
    .pay-number-row:last-of-type{ margin-bottom: 0; }

    .pay-number-icon{
        width: 40px; height: 40px;
        border-radius: 10px;
        display:flex; align-items:center; justify-content:center;
        flex-shrink:0;
        color:#fff;
        font-size: 1rem;
    }
    .pay-number-icon.is-bkash{ background:#e2136e; }
    .pay-number-icon.is-nagad{ background:#f6921e; }
    .pay-number-icon.is-rocket{ background:#8c3494; }

    .pay-number-info{ display:flex; flex-direction:column; gap:2px; min-width:0; flex:1; }
    .pay-number-name{ font-weight:700; font-size:.85rem; }
    .pay-number-value{
        font-weight:700;
        font-size: 1rem;
        color: var(--pay-text);
        letter-spacing:.02em;
        overflow-wrap: anywhere;
    }

    .pay-copy-btn{
        flex-shrink:0;
        background: var(--pay-gold);
        color: var(--pay-bg-1);
        border:none;
        font-weight:700;
        font-size:.8rem;
        padding: 8px 14px;
        border-radius: 10px;
        cursor:pointer;
        transition: transform .1s ease, background .15s ease;
    }
    .pay-copy-btn:hover{ background: var(--pay-gold-soft); }
    .pay-copy-btn:active{ transform: scale(.96); }
    .pay-copy-btn.is-copied{ background:#4ade80; }

    .pay-numbers-note{
        margin-top: 12px;
        font-size: .75rem;
        color: var(--pay-text-dim);
    }

    .pay-form{ display:flex; flex-direction:column; gap: 16px; }
    .pay-field{ display:flex; flex-direction:column; gap:6px; }
    .pay-label{
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing:.06em;
        color: var(--pay-text-dim);
        font-weight:700;
    }
    .pay-input-wrap{ position: relative; display:flex; align-items:center; }
    .pay-input-icon{
        position:absolute; left:16px;
        color: var(--pay-text-dim);
        font-size:.9rem;
        pointer-events:none;
    }
    .pay-select, .pay-control{
        width:100%;
        background: var(--pay-panel);
        border: 1px solid var(--pay-border);
        color: var(--pay-text);
        border-radius: var(--pay-radius-md);
        padding: 14px 16px 14px 44px;
        font-size: .95rem;
        appearance: none;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .pay-select:focus, .pay-control:focus{
        outline: none;
        border-color: var(--pay-gold);
        box-shadow: 0 0 0 3px rgba(240,180,41,.18);
    }
    .pay-control::placeholder{ color: rgba(147,160,184,.6); }
    .pay-hint{ font-size: .72rem; color: var(--pay-text-dim); }

    .pay-submit{
        display:flex; align-items:center; justify-content:center; gap:10px;
        width:100%;
        background: linear-gradient(135deg, var(--pay-gold), var(--pay-gold-soft));
        color: var(--pay-bg-1);
        border:none;
        border-radius: var(--pay-radius-md);
        padding: 16px 18px;
        font-weight: 800;
        font-size: .98rem;
        cursor:pointer;
        margin-top: 4px;
        transition: transform .1s ease, box-shadow .15s ease;
        box-shadow: 0 10px 24px -8px rgba(240,180,41,.5);
    }
    .pay-submit:hover{ transform: translateY(-1px); }
    .pay-submit:active{ transform: translateY(0); }

    .pay-back-link{
        display:flex; align-items:center; justify-content:center; gap:8px;
        color: var(--pay-text-dim);
        font-size: .85rem;
        text-decoration:none;
        margin: 6px 0 18px;
    }
    .pay-back-link:hover{ color: var(--pay-gold); }

    /* ---------- Tablet ---------- */
    @media (min-width: 640px){
        .pay-card{
            background: var(--pay-panel);
            border: 1px solid var(--pay-border);
            border-radius: 28px;
            padding: 8px 32px 8px;
            margin-top: 10px;
            box-shadow: 0 30px 60px -20px rgba(0,0,0,.5);
        }
        .pay-numbers-card, .pay-fee-card{ border-color: rgba(255,255,255,.06); }
    }

    /* ---------- Desktop ---------- */
    @media (min-width: 992px){
        .pay-page{ padding-top: 40px; }
        .pay-topbar{ max-width: 560px; }
        .pay-card{ max-width: 560px; padding: 24px 40px 8px; }
        .pay-header h1{ font-size: 2.1rem; }
        .pay-number-row{ padding: 14px 18px; }
        .pay-submit{ padding: 17px 18px; }
    }

    @media (prefers-reduced-motion: reduce){
        .pay-submit, .pay-copy-btn, .pay-back{ transition:none; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var methodSelect = document.getElementById('payment-method');

    document.querySelectorAll('.pay-copy-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var value = btn.getAttribute('data-copy');
            var restore = btn.textContent;

            var finish = function (ok) {
                btn.textContent = ok ? 'Copied' : 'Failed';
                btn.classList.toggle('is-copied', ok);
                setTimeout(function () {
                    btn.textContent = restore;
                    btn.classList.remove('is-copied');
                }, 1500);
            };

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(value).then(function () {
                    finish(true);
                }).catch(function () {
                    finish(false);
                });
            } else {
                var temp = document.createElement('textarea');
                temp.value = value;
                temp.style.position = 'fixed';
                temp.style.opacity = '0';
                document.body.appendChild(temp);
                temp.select();
                try {
                    document.execCommand('copy');
                    finish(true);
                } catch (e) {
                    finish(false);
                }
                document.body.removeChild(temp);
            }

            var methodKey = btn.getAttribute('data-method-select');
            if (methodKey && methodSelect) {
                methodSelect.value = methodKey;
            }
        });
    });
});
</script>
@endsection