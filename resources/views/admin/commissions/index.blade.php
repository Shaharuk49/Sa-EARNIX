@extends('layouts.admin')
@section('title', 'Commission Settings')
@section('page-title', 'Referral Commission (24 Generations)')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Set Commission Per Generation</span>
        <span class="badge bg-{{ $total <= 220 ? 'success' : 'danger' }} fs-6">Total: ৳{{ number_format($total, 2) }} / 220 BDT</span>
    </div>
    <div class="card-body">
        <div class="alert alert-info small mb-4">
            <i class="fas fa-info-circle me-2"></i>
            Registration fee = 250 BDT. Total commission = 220 BDT. Company profit = 30 BDT.
            All 24 generation amounts must sum to ≤ 220 BDT.
        </div>
        <form method="POST" action="{{ route('admin.commissions.update') }}">
            @csrf
            <div class="row g-2">
                @foreach($commissions as $comm)
                <div class="col-6 col-md-3 col-lg-2">
                    <label class="form-label small fw-semibold mb-1">Gen {{ $comm->generation_number }}</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">৳</span>
                        <input type="number" name="amounts[{{ $comm->id }}]" value="{{ $comm->amount }}"
                               class="form-control" step="0.01" min="0" max="220">
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4 d-flex gap-2 align-items-center">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save Commission Rates
                </button>
                <span class="text-muted small">Changes take effect immediately for new registrations.</span>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Live total calculator
    document.querySelectorAll('input[type=number]').forEach(inp => {
        inp.addEventListener('input', () => {
            const total = [...document.querySelectorAll('input[type=number]')]
                .reduce((s, i) => s + parseFloat(i.value || 0), 0);
            const badge = document.querySelector('.badge');
            badge.textContent = 'Total: ৳' + total.toFixed(2) + ' / 220 BDT';
            badge.className = 'badge fs-6 ' + (total <= 220 ? 'bg-success' : 'bg-danger');
        });
    });
</script>
@endpush
