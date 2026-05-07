@extends('layouts.admin')
@section('title', 'Bonus Sections')
@section('page-title', 'Welcome Bonus – Sections & Videos')

@section('content')
{{-- Add New Section --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-semibold">Add New Section</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.bonus.section.store') }}" class="d-flex gap-2">
            @csrf
            <input type="text" name="title" class="form-control" placeholder="Section title..." required>
            <button class="btn btn-primary text-nowrap">+ Add Section</button>
        </form>
    </div>
</div>

{{-- Sections --}}
@forelse($sections as $section)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary">Section {{ $section->sort_order }}</span>
            <span class="fw-semibold">{{ $section->title }}</span>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editSection{{ $section->id }}">
                <i class="fas fa-edit"></i>
            </button>
            <form method="POST" action="{{ route('admin.bonus.section.destroy', $section) }}" onsubmit="return confirm('Delete this section and all its content?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
            </form>
        </div>
    </div>

    <div class="card-body">
        <div class="row g-3">
            {{-- Videos --}}
            <div class="col-md-7">
                <div class="fw-semibold small mb-2"><i class="fas fa-play-circle me-1 text-danger"></i>Videos ({{ $section->videos->count() }})</div>
                @foreach($section->videos as $video)
                <div class="d-flex align-items-center gap-2 mb-2 p-2 bg-light rounded">
                    <div class="flex-grow-1">
                        <div class="small fw-semibold">{{ $video->title }}</div>
                        <div class="text-muted" style="font-size:.7rem; word-break:break-all;">{{ $video->video_url }}</div>
                    </div>
                    <form method="POST" action="{{ route('admin.bonus.video.destroy', $video) }}" onsubmit="return confirm('Remove video?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2">Del</button>
                    </form>
                </div>
                @endforeach
                {{-- Add video --}}
                <form method="POST" action="{{ route('admin.bonus.video.store', $section) }}" class="mt-2">
                    @csrf
                    <div class="mb-1">
                        <input type="text" name="title" class="form-control form-control-sm" placeholder="Video title..." required>
                    </div>
                    <div class="d-flex gap-2">
                        <input type="url" name="video_url" class="form-control form-control-sm" placeholder="https://youtube.com/..." required>
                        <button class="btn btn-sm btn-outline-danger text-nowrap">+ Add Video</button>
                    </div>
                </form>
            </div>

            {{-- Rules --}}
            <div class="col-md-5">
                <div class="fw-semibold small mb-2"><i class="fas fa-list me-1 text-primary"></i>Unlock Rules ({{ $section->rules->count() }})</div>
                @foreach($section->rules as $rule)
                <div class="d-flex align-items-start gap-2 mb-2">
                    <span class="small flex-grow-1 text-muted">
                        <strong>{{ $rule->rule_type }}:</strong> {{ $rule->rule_value }}
                    </span>
                    <form method="POST" action="{{ route('admin.bonus.rule.destroy', $rule) }}" onsubmit="return confirm('Remove rule?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2">Del</button>
                    </form>
                </div>
                @endforeach
                <form method="POST" action="{{ route('admin.bonus.rule.store', $section) }}" class="mt-2">
                    @csrf
                    <div class="d-flex gap-1 mb-1">
                        <input type="text" name="rule_type" class="form-control form-control-sm" placeholder="Type (e.g. min_referrals)" required style="width:45%">
                        <input type="text" name="rule_value" class="form-control form-control-sm" placeholder="Value (e.g. 2)" required style="width:45%">
                        <button class="btn btn-sm btn-outline-primary text-nowrap">+</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Edit section modal --}}
<div class="modal fade" id="editSection{{ $section->id }}" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h6 class="modal-title">Edit Section</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form method="POST" action="{{ route('admin.bonus.section.update', $section) }}">
            @csrf @method('PUT')
            <div class="modal-body">
                <input type="text" name="title" class="form-control" value="{{ $section->title }}" required>
            </div>
            <div class="modal-footer"><button class="btn btn-primary btn-sm">Update</button></div>
        </form>
    </div></div>
</div>
@empty
<div class="alert alert-info">No bonus sections yet. Add one above.</div>
@endforelse
@endsection
