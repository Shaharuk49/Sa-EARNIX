@extends('layouts.user')

@section('title', 'Monthly Salary - SA EarniX')

@section('content')
<div class="container py-4">
    <h4 class="mb-4 fw-bold">Monthly Salary</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(!$canClaimToday)
        <div class="alert alert-warning d-flex align-items-center gap-2">
            <span>⏳</span>
            <span>Salary claim window: <strong>1st – 5th</strong> of each month. Come back on the 1st!</span>
        </div>
    @endif

    {{-- Salary Levels --}}
    @foreach($levelData as $item)
        @php $level = $item['level']; @endphp
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h6 class="fw-bold mb-1">Level {{ $level->level_number }}
                            @if($level->title) <span class="text-muted fw-normal">({{ $level->title }})</span> @endif
                            @if(!$level->is_active_by_admin) 🔒 @endif
                        </h6>
                        <div class="text-muted small">Monthly Salary: <strong>{{ number_format($level->salary_amount, 0) }} BDT</strong></div>
                    </div>
                    <div class="text-end">
                        @if($item['claimed'])
                            <span class="badge bg-success px-3 py-2">✓ Claimed</span>
                        @elseif(!$level->is_active_by_admin)
                            <form method="POST" action="{{ route('user.salary.claim') }}">
                                @csrf
                                <input type="hidden" name="level_id" value="{{ $level->id }}">
                                <button type="submit" class="btn btn-warning btn-sm">Active করুন</button>
                            </form>
                        @elseif($item['can_claim'])
                            <div class="d-flex gap-2">
                                <span class="badge bg-success align-self-center">Active</span>
                                <form method="POST" action="{{ route('user.salary.claim') }}">
                                    @csrf
                                    <input type="hidden" name="level_id" value="{{ $level->id }}">
                                    <button type="submit" class="btn btn-primary btn-sm">claim করুন</button>
                                </form>
                            </div>
                        @else
                            <span class="badge bg-warning text-dark px-3 py-2">Active করুন</span>
                        @endif
                    </div>
                </div>

                {{-- Rules --}}
                @if($level->rules->count() > 0)
                    <ul class="list-unstyled mb-0 mt-2">
                        @foreach($level->rules->sortBy('sort_order') as $rule)
                            <li class="small text-muted">• {{ $rule->rule_text }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @endforeach

    {{-- Global Rules --}}
    @if($globalRules)
        <div class="card border-0 bg-light mt-3 mb-3">
            <div class="card-body">
                <div class="fw-bold mb-2">✅ Monthly Salary Rules</div>
                <p class="text-muted small mb-0">{!! nl2br(e($globalRules)) !!}</p>
            </div>
        </div>
    @else
        <div class="card border-0 bg-light mt-3 mb-3">
            <div class="card-body">
                <div class="fw-bold mb-2">✅ Monthly Salary Rules</div>
                <p class="text-muted small mb-0">সকল সদস্য প্রতি মাসের ১ তারিখে Monthly salary পেতে পারবেন। ১-৫ তারিখের মধ্যে claim করতে হবে।</p>
            </div>
        </div>
    @endif

    {{-- Claim History --}}
    <h5 class="fw-bold mt-4 mb-3">Salary Claim History</h5>

    @forelse($claimHistory as $claim)
        <div class="card border-0 shadow-sm mb-3" style="border-left: 4px solid #1e3a8a !important;">
            <div class="card-body" style="border-left: 4px solid #1e3a8a; border-radius: 8px;">
                <div class="fw-bold text-primary mb-1">Level – {{ $claim->level->level_number }}</div>
                <div class="small text-muted">Date : {{ $claim->claimed_at ? $claim->claimed_at->format('j F Y') : $claim->claim_month }}</div>
                <div class="small text-muted">Amount : ৳{{ number_format($claim->amount, 0) }}</div>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-4">
            <p>No salary claims yet.</p>
        </div>
    @endforelse
</div>
@endsection
