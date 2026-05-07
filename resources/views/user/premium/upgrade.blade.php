@extends('layouts.user')

@section('title', 'Premium Upgrade - SA EarniX')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-crown me-2"></i>Premium Upgrade
                    </h5>
                </div>

                <div class="card-body">
                    {{-- Benefits --}}
                    <div class="mb-4">
                        <h6>Premium Member Benefits:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i> Unlimited referral earning</li>
                            <li><i class="fas fa-check text-success me-2"></i> Higher commission rates</li>
                            <li><i class="fas fa-check text-success me-2"></i> Exclusive content access</li>
                            <li><i class="fas fa-check text-success me-2"></i> Priority support</li>
                            <li><i class="fas fa-check text-success me-2"></i> Special badges & rewards</li>
                        </ul>
                    </div>

                    {{-- Price Box --}}
                    <div class="alert alert-warning mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Upgrade Fee</span>
                            <span style="font-size: 1.5rem; font-weight: bold;">৳ 250</span>
                        </div>
                        <small class="text-muted d-block mt-2">One-time payment for lifetime premium access</small>
                    </div>

                    {{-- Payment Form --}}
                    <form method="POST" action="{{ route('premium.upgrade.process') }}">
                        @csrf

                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">-- Select Payment Method --</option>
                                <option value="bkash">bKash</option>
                                <option value="nagad">Nagad</option>
                                <option value="rocket">Rocket</option>
                                <option value="card">Card</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Transaction Reference</label>
                            <input type="text" name="transaction_ref" class="form-control" 
                                   placeholder="Enter transaction/reference number" required>
                            <small class="text-muted">Example: TXN123456 or your payment gateway reference</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="fas fa-lock me-2"></i>Pay ৳250 & Become Premium
                            </button>
                            <a href="{{ route('user.home') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Info Box --}}
            <div class="alert alert-info mt-4">
                <strong>Safe Payment:</strong> Your payment information is secured. We use trusted payment gateways for all transactions.
            </div>
        </div>
    </div>
</div>
@endsection
