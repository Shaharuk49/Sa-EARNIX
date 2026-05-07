@extends('layouts.admin')
@section('title', 'Withdraw Requests')
@section('page-title', 'Withdraw Management')

@section('content')
{{-- Filter tabs --}}
<div class="mb-3 d-flex gap-2">
    @foreach(['pending','approved','rejected','all'] as $s)
    <a href="{{ route('admin.withdraw.index', ['status' => $s]) }}"
       class="btn btn-sm {{ $status === $s ? 'btn-primary' : 'btn-outline-secondary' }}">
        {{ ucfirst($s) }}
    </a>
    @endforeach
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 small align-middle">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Account No.</th>
                        <th>Requested</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdraws as $w)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $w->user->name ?? '—' }}</div>
                            <div class="text-muted">{{ $w->user->affiliate_id ?? '' }}</div>
                        </td>
                        <td class="fw-bold text-{{ $w->status === 'approved' ? 'success' : ($w->status === 'rejected' ? 'danger' : 'warning') }}">
                            ৳{{ number_format($w->amount, 0) }}
                        </td>
                        <td>{{ $w->method->name ?? '—' }}</td>
                        <td><code>{{ $w->account_number }}</code></td>
                        <td class="text-muted">{{ $w->requested_at ? \Carbon\Carbon::parse($w->requested_at)->format('d M Y') : '—' }}</td>
                        <td>
                            <span class="badge {{ $w->status === 'approved' ? 'bg-success' : ($w->status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                {{ ucfirst($w->status) }}
                            </span>
                        </td>
                        <td>
                            @if($w->status === 'pending')
                            <div class="d-flex gap-1">
                                <form method="POST" action="{{ route('admin.withdraw.approve', $w) }}">
                                    @csrf
                                    <button class="btn btn-xs btn-success btn-sm py-0 px-2">Approve</button>
                                </form>
                                <button class="btn btn-xs btn-danger btn-sm py-0 px-2"
                                    data-bs-toggle="modal" data-bs-target="#rejectModal{{ $w->id }}">Reject</button>
                            </div>
                            @else
                                <span class="text-muted">—</span>
                                @if($w->remarks)
                                    <div class="text-muted" style="font-size:.7rem;">{{ $w->remarks }}</div>
                                @endif
                            @endif
                        </td>
                    </tr>

                    {{-- Reject modal --}}
                    @if($w->status === 'pending')
                    <div class="modal fade" id="rejectModal{{ $w->id }}" tabindex="-1">
                        <div class="modal-dialog"><div class="modal-content">
                            <div class="modal-header"><h6 class="modal-title">Reject Withdraw</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                            <form method="POST" action="{{ route('admin.withdraw.reject', $w) }}">
                                @csrf
                                <div class="modal-body">
                                    <p class="small">Rejecting ৳{{ number_format($w->amount, 0) }} for <strong>{{ $w->user->name ?? '—' }}</strong>. Amount will be refunded.</p>
                                    <textarea name="remarks" class="form-control" rows="3" placeholder="Reason for rejection (optional)..."></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger btn-sm">Confirm Reject</button>
                                </div>
                            </form>
                        </div></div>
                    </div>
                    @endif
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No {{ $status }} withdraw requests.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">{{ $withdraws->links() }}</div>
</div>
@endsection
