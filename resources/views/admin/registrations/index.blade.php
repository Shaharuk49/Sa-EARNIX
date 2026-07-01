@extends('layouts.admin')

@section('title', 'Registration Payments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Registration Payments</h4>
    @if($pendingCount > 0)
        <span class="badge bg-danger fs-6">{{ $pendingCount }} Pending</span>
    @endif
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

{{-- Status Tabs --}}
<ul class="nav nav-tabs mb-4">
    @foreach(['pending' => 'Pending', 'paid' => 'Approved', 'failed' => 'Rejected', '' => 'All'] as $val => $label)
        <li class="nav-item">
            <a class="nav-link {{ $status === $val ? 'active' : '' }}"
               href="{{ route('admin.registrations.index', ['status' => $val]) }}">
                {{ $label }}
                @if($val === 'pending' && $pendingCount > 0)
                    <span class="badge bg-danger ms-1">{{ $pendingCount }}</span>
                @endif
            </a>
        </li>
    @endforeach
</ul>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Referrer</th>
                        <th>Method</th>
                        <th>Trx ID</th>
                        <th>Mobile</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $p)
                        <tr id="payment-row-{{ $p->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-semibold">{{ $p->user->name ?? '—' }}</div>
                                <small class="text-muted">{{ $p->user->username ?? '' }}</small>
                            </td>
                            <td>
                                @if($p->user && $p->user->upline)
                                    <span class="text-info">{{ $p->user->upline->name }}</span><br>
                                    <small class="text-muted">{{ $p->user->upline->username }}</small>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td><span class="badge bg-secondary text-uppercase">{{ $p->payment_method }}</span></td>
                            <td><code>{{ $p->gateway_transaction_id }}</code></td>
                            <td><code>{{ $p->mobile_number ?? '—' }}</code></td>
                            <td class="fw-bold">৳{{ number_format($p->amount, 0) }}</td>
                            <td>
                                @if($p->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($p->status === 'paid')
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td><small>{{ $p->created_at->format('d M Y H:i') }}</small></td>
                            <td>
                                @if($p->status === 'pending')
                                    {{-- Approve --}}
                                    <form action="{{ route('admin.registrations.approve', $p) }}" method="POST" class="d-inline js-ajax-form"
                                        data-confirm
                                        data-confirm-title="Approve registration payment?"
                                        data-confirm-text="This will activate the user and distribute generation commissions."
                                        data-confirm-button="Approve now">
                                        @csrf
                                        <button class="btn btn-success btn-sm">✓ Approve</button>
                                    </form>
                                    {{-- Reject --}}
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#rejectModal{{ $p->id }}">✗ Reject</button>

                                    {{-- Reject Modal --}}
                                    <div class="modal fade" id="rejectModal{{ $p->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <form action="{{ route('admin.registrations.reject', $p) }}" method="POST" class="js-ajax-form">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title">Reject Payment</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>User:</strong> {{ $p->user->name }} ({{ $p->user->username }})</p>
                                                        <p><strong>Trx ID:</strong> {{ $p->gateway_transaction_id }}</p>
                                                        <div class="mb-3">
                                                            <label class="form-label">Reason (optional)</label>
                                                            <textarea name="reason" class="form-control" rows="2" placeholder="Invalid transaction ID, etc."></textarea>
                                                        </div>
                                                        <div class="alert alert-warning py-2">⚠ User will remain inactive after rejection.</div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Confirm Reject</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">No payments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $payments->links() }}
</div>
@endsection
