@extends('layouts.auth')
@section('title', 'Pending Approval')

@section('content')
<div class="auth-card mx-auto my-4" data-reveal>
    <div class="auth-header text-center">
        <div class="auth-kicker mb-3">
            <i class="fas fa-hourglass-half"></i>
            Approval in progress
        </div>
        <h1>Payment Submitted</h1>
        <p>আপনার registration এখন review queue-তে আছে</p>
    </div>

    <div class="auth-body text-center">
        <div class="metric-icon mx-auto mb-3" style="width:72px;height:72px;">
            <i class="fas fa-clock fa-xl"></i>
        </div>

        <h4 class="fw-bold mb-2">Admin approval pending</h4>
        <p class="text-muted mb-4">আপনার payment verify হওয়ার পর account active হবে, তারপর login করতে পারবেন।</p>

        <div class="alert alert-warning text-start py-3 px-4 mb-4">
            <div class="fw-bold mb-2"><i class="fas fa-circle-info me-2"></i>পরবর্তী ধাপ</div>
            <ol class="mb-0 ps-3">
                <li>Admin আপনার payment verify করবে</li>
                <li>Approval এর পর account activate হবে</li>
                <li>তারপর username এবং password দিয়ে login করতে পারবেন</li>
            </ol>
        </div>

        <p class="text-muted small mb-4">সাধারণত <strong>1-24 ঘন্টার</strong> মধ্যে approval দেওয়া হয়।</p>

        <div class="d-grid gap-2">
            <a href="{{ route('login') }}" class="btn btn-auth py-3">
                <i class="fas fa-arrow-right-to-bracket me-2"></i>Login পেজে যান
            </a>
            <a href="{{ route('register') }}" class="btn btn-outline-primary">নতুন account register করুন</a>
        </div>
    </div>
</div>
@endsection
