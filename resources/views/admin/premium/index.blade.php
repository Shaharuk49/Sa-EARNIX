@extends('layouts.admin')

@section('title', 'Premium Payments - Admin')

@section('content')
<style>
    .ap-page { padding: 1.5rem; color: #e8e6f0; }
    .ap-tabs { display: flex; gap: 0.5rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
    .ap-tab {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.5rem 1rem; border-radius: 999px; font-size: 0.85rem; font-weight: 600;
        text-decoration: none; border: 1px solid rgba(255,255,255,0.1); color: #cfcbe0;
    }
    .ap-tab.active { background: rgba(240,180,41,0.14); border-color: rgba(240,180,41,0.4); color: #f5c451; }
    .ap-tab .badge { background: rgba(255,255,255,0.1); color: #e8e6f0; }
    .ap-tab.active .badge { background: rgba(240,180,41,0.3); color: #1a1206; }

    .ap-card { background: #14141f; border: 1px solid rgba(255,255,255,0.06); border-radius: 16px; overflow: hidden; }
    .ap-table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
    .ap-table th {
        text-align: left; padding: 0.85rem 1rem; color: #9a97ad; font-size: 0.72rem;
        text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .ap-table td { padding: 0.9rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.05); vertical-align: middle; }
    .ap-table tr:last-child td { border-bottom: none; }

    .ap-status { font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.65rem; border-radius: 999px; }
    .ap-status-pending { color: #fbbf24; background: rgba(251,191,36,0.12); }
    .ap-status-paid { color: #4ade80; background: rgba(74,222,128,0.12); }
    .ap-status-rejected { color: #f87171; background: rgba(248,113,113,0.12); }

    .ap-btn-approve {
        background: rgba(74,222,128,0.15); border: 1px solid rgba(74,222,128,0.4);
        color: #4ade80; font-size: 0.8rem; font-weight: 600; padding: 0.35rem 0.85rem; border-radius: 8px;
    }
    .ap-btn-approve:hover { background: rgba(74,222,128,0.25); color: #4ade80; }
    .ap-btn-reject {
        background: rgba(248,113,113,0.12); border: 1px solid rgba(248,113,113,0.35);
        color: #f87171; font-size: 0.8rem; font-weight: 600; padding: 0.35rem 0.85rem; border-radius: 8px;
    }
    .ap-btn-reject:hover { background: rgba(248,113,113,0.22); color: #f87171; }
    .ap-empty { padding: 3rem 1rem; text-align: center; color: #726f85; }
</style>

<div class="ap-page">
    <h4 class="mb-3" style="color:#4ade80;">Premium Upgrade Payments</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="ap-tabs">
        <a href="{{ route('admin.premium.index', ['status' => 'pending']) }}" class="ap-tab {{ $status === 'pending' ? 'active' : '' }}">
            Pending <span class="badge">{{ $counts['pending'] }}</span>
        </a>
        <a href="{{ route('admin.premium.index', ['status' => 'paid']) }}" class="ap-tab {{ $status === 'paid' ? 'active' : '' }}">
            Approved <span class="badge">{{ $counts['paid'] }}</span>
        </a>
        <a href="{{ route('admin.premium.index', ['status' => 'rejected']) }}" class="ap-tab {{ $status === 'rejected' ? 'active' : '' }}">
            Rejected <span class="badge">{{ $counts['rejected'] }}</span>
        </a>
        <a href="{{ route('admin.premium.index', ['status' => 'all']) }}" class="ap-tab {{ $status === 'all' ? 'active' : '' }}">
            All
        </a>
    </div>

    <div class="ap-card">
        @if($upgrades->count())
            <table class="ap-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Transaction ID</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($upgrades as $upgrade)
                        <tr>
                            <td>
                                <div style="font-weight:600;color:#e8e6f0;">{{ $upgrade->user->name ?? 'N/A' }}</div>
                                <div style="font-size:0.75rem;color:#726f85;">{{ $upgrade->user->affiliate_id ?? '' }}</div>
                            </td>
                            <td>৳{{ number_format($upgrade->amount, 0) }}</td>
                            <td class="text-capitalize">{{ $upgrade->payment_method }}</td>
                            <td>{{ $upgrade->gateway_transaction_id }}</td>
                            <td>{{ $upgrade->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <span class="ap-status ap-status-{{ $upgrade->status }}">{{ ucfirst($upgrade->status) }}</span>
                                @if($upgrade->status === 'rejected' && $upgrade->rejection_reason)
                                    <div style="font-size:0.72rem;color:#726f85;margin-top:0.2rem;">{{ $upgrade->rejection_reason }}</div>
                                @endif
                            </td>
                            <td>
                                @if($upgrade->status === 'pending')
                                    <div class="d-flex gap-2">
                                        <form method="POST" action="{{ route('admin.premium.approve', $upgrade) }}">
                                            @csrf
                                            <button type="submit" class="ap-btn-approve" onclick="return confirm('Approve this payment and activate premium for this user?')">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <button type="button" class="ap-btn-reject" data-bs-toggle="collapse" data-bs-target="#reject-{{ $upgrade->id }}">
                                            <i class="fas fa-xmark"></i> Reject
                                        </button>
                                    </div>
                                    <div class="collapse mt-2" id="reject-{{ $upgrade->id }}">
                                        <form method="POST" action="{{ route('admin.premium.reject', $upgrade) }}" class="d-flex gap-2">
                                            @csrf
                                            <input type="text" name="rejection_reason" class="form-control form-control-sm" placeholder="Reason (optional)" style="background:#191926;border:1px solid rgba(255,255,255,0.08);color:#e8e6f0;">
                                            <button type="submit" class="ap-btn-reject" style="white-space:nowrap;">Confirm</button>
                                        </form>
                                    </div>
                                @else
                                    <span style="color:#726f85;font-size:0.8rem;">&mdash;</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-3">
                {{ $upgrades->links() }}
            </div>
        @else
            <div class="ap-empty">No {{ $status !== 'all' ? $status : '' }} premium payments found.</div>
        @endif
    </div>
</div>
@endsection