@extends('layouts.admin')
@section('title', 'Site Settings')
@section('page-title', 'Site Settings & Withdraw Methods')

@section('content')
<div class="row g-4">

    {{-- Site Settings --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Site Settings (Links)</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Support Link (Telegram/WhatsApp)</label>
                        <input type="text" name="support_link" class="form-control"
                               value="{{ $settings['support_link'] ?? '' }}" placeholder="https://t.me/...">
                        <div class="form-text">Users click "Support" → goes here</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">PC/Laptop Apply Link</label>
                        <input type="text" name="laptop_apply_link" class="form-control"
                               value="{{ $settings['laptop_apply_link'] ?? '' }}" placeholder="https://...">
                        <div class="form-text">Users click "Apply for PC/Laptop" → goes here</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Dropshipping Group Link</label>
                        <input type="text" name="dropshipping_link" class="form-control"
                               value="{{ $settings['dropshipping_link'] ?? '' }}" placeholder="https://...">
                        <div class="form-text">Dropshipping & Print On Demand button redirect</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Monthly Salary Global Rules</label>
                        <textarea name="monthly_salary_rules" class="form-control" rows="5"
                            placeholder="Write general salary rules shown to all users...">{{ $settings['monthly_salary_rules'] ?? '' }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Settings
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Withdraw Methods --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Withdraw Methods</div>
            <div class="card-body">
                {{-- Add method --}}
                <form method="POST" action="{{ route('admin.withdraw-methods.store') }}" class="d-flex gap-2 mb-3">
                    @csrf
                    <input type="text" name="name" class="form-control form-control-sm" placeholder="Method name (e.g. bKash)" required>
                    <button class="btn btn-sm btn-primary text-nowrap">+ Add</button>
                </form>

                {{-- Methods list --}}
                @forelse($withdrawMethods as $method)
                <div class="d-flex align-items-center justify-content-between p-2 mb-2 border rounded">
                    <span class="fw-semibold small">{{ $method->name }}</span>
                    <div class="d-flex gap-1 align-items-center">
                        <span class="badge {{ $method->is_active ? 'bg-success' : 'bg-secondary' }} small">
                            {{ $method->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <form method="POST" action="{{ route('admin.withdraw-methods.toggle', $method) }}">
                            @csrf
                            <button class="btn btn-xs btn-outline-{{ $method->is_active ? 'warning' : 'success' }} btn-sm py-0 px-2">
                                {{ $method->is_active ? 'Disable' : 'Enable' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.withdraw-methods.destroy', $method) }}"
                              onsubmit="return confirm('Remove this method?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2">Del</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-muted small">No withdraw methods yet.</div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
