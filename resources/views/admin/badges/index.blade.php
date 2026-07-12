@extends('layouts.admin')
@section('title', 'Leader Badges')
@section('page-title', 'Leader Badge Management')

@section('content')
<div class="row g-3">
    @forelse($badges as $badge)
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="fs-5">{{ $badge->icon ?? '🏅' }}</span>
                    <span class="fw-bold">{{ $badge->name }}</span>
                    <code class="small text-muted">{{ $badge->slug }}</code>
                </div>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBadge{{ $badge->id }}">
                    <i class="fas fa-edit"></i> Edit
                </button>
            </div>
            <div class="card-body small">
                <div class="mb-2">
                    <span class="fw-semibold">Condition:</span>
                    <p class="text-muted mb-0">{{ $badge->condition_text ?: '(not set)' }}</p>
                </div>
                <div>
                    <span class="fw-semibold">Prize / Reward:</span>
                    <p class="text-muted mb-0">{{ $badge->prize_text ?: '(not set)' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit badge modal --}}
    <div class="modal fade" id="editBadge{{ $badge->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg"><div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Edit Badge: {{ $badge->name }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.badges.update', $badge) }}">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Badge Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $badge->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Icon (emoji or FA class)</label>
                        <input type="text" name="icon" class="form-control" value="{{ $badge->icon }}" placeholder="e.g. 🥇 or fas fa-crown">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Condition Text (shown to users)</label>
                        <textarea name="condition_text" class="form-control" rows="4" placeholder="Describe qualification conditions...">{{ $badge->condition_text }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Prize / Reward Text</label>
                        <textarea name="prize_text" class="form-control" rows="3" placeholder="Describe the reward...">{{ $badge->prize_text }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div></div>
    </div>
    @empty
    <div class="col-12">
        <div class="card border-0 shadow-sm py-5 text-center">
            <div class="mb-3">
                <i class="fas fa-award fa-2x text-secondary"></i>
            </div>
            <h5 class="fw-bold">No badges available</h5>
            <p class="text-muted mb-0">Add leader badges to the database or run your seeders so this page can show the badge structure.</p>
        </div>
    </div>
    @endforelse
</div>
@endsection
