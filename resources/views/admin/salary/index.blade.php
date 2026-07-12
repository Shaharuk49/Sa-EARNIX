@extends('layouts.admin')
@section('title', 'Monthly Salary Levels')
@section('page-title', 'Monthly Salary Management')

@section('content')
@forelse($levels as $level)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div>
            <span class="fw-bold">Level {{ $level->level_number }}</span>
            <span class="text-muted ms-2">{{ $level->title }}</span>
            <span class="ms-2 badge {{ $level->is_active_by_admin ? 'bg-success' : 'bg-secondary' }}">
                {{ $level->is_active_by_admin ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="fw-semibold text-primary">৳{{ number_format($level->salary_amount, 0) }}/month</span>
            <form method="POST" action="{{ route('admin.salary.toggle', $level) }}">
                @csrf
                <button type="submit" class="btn btn-sm {{ $level->is_active_by_admin ? 'btn-warning' : 'btn-success' }}">
                    <i class="fas fa-{{ $level->is_active_by_admin ? 'toggle-off' : 'toggle-on' }} me-1"></i>
                    {{ $level->is_active_by_admin ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        {{-- Rules list --}}
        <div class="mb-3">
            <div class="fw-semibold small mb-2">Rules ({{ $level->rules->count() }}):</div>
            @forelse($level->rules->sortBy('sort_order') as $rule)
            <div class="d-flex align-items-start gap-2 mb-2 p-2 bg-light rounded">
                <span class="text-muted small flex-grow-1">• {{ $rule->rule_text }}</span>
                <div class="d-flex gap-1">
                    <button class="btn btn-xs btn-outline-secondary btn-sm py-0 px-2"
                        data-bs-toggle="modal" data-bs-target="#editRule{{ $rule->id }}">Edit</button>
                    <form method="POST" action="{{ route('admin.salary.rule.destroy', $rule) }}" onsubmit="return confirm('Delete this rule?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2">Del</button>
                    </form>
                </div>
            </div>

            {{-- Edit rule modal --}}
            <div class="modal fade" id="editRule{{ $rule->id }}" tabindex="-1">
                <div class="modal-dialog"><div class="modal-content">
                    <div class="modal-header"><h6 class="modal-title">Edit Rule</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <form method="POST" action="{{ route('admin.salary.rule.update', $rule) }}">
                        @csrf @method('PUT')
                        <div class="modal-body">
                            <textarea name="rule_text" class="form-control" rows="3">{{ $rule->rule_text }}</textarea>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary btn-sm">Update</button>
                        </div>
                    </form>
                </div></div>
            </div>
            @empty
            <div class="text-muted small">No rules added yet.</div>
            @endforelse
        </div>

        {{-- Add rule --}}
        <form method="POST" action="{{ route('admin.salary.rule.store', $level) }}" class="d-flex gap-2">
            @csrf
            <input type="text" name="rule_text" class="form-control form-control-sm" placeholder="Add new rule..." required>
            <button class="btn btn-sm btn-outline-primary text-nowrap">+ Add Rule</button>
        </form>
    </div>
</div>
@empty
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body text-center py-5">
        <div class="mb-3">
            <i class="fas fa-info-circle fa-2x text-secondary"></i>
        </div>
        <h5 class="fw-bold">No salary levels found</h5>
        <p class="text-muted mb-0">Please add monthly salary levels in your local or production database so this page can display structured data.</p>
    </div>
</div>
@endforelse
@endsection
