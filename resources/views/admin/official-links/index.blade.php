@extends('layouts.admin')
@section('title', 'Official Links')
@section('page-title', 'Official Links Management')

@section('content')
{{-- Add link --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-semibold">Add New Official Link</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.official-links.store') }}">
            @csrf
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="key_name" class="form-control" placeholder="Key (e.g. tg_group)" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="title" class="form-control" placeholder="Display Name (e.g. TG Group)" required>
                </div>
                <div class="col-md-4">
                    <input type="url" name="url" class="form-control" placeholder="https://..." required>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary w-100">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Links list --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 small align-middle">
            <thead class="table-light">
                <tr><th>Key</th><th>Title</th><th>URL</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($links as $link)
                <tr>
                    <td><code>{{ $link->key_name }}</code></td>
                    <td>{{ $link->title }}</td>
                    <td><a href="{{ $link->url }}" target="_blank" class="text-truncate d-block" style="max-width:200px;">{{ $link->url }}</a></td>
                    <td>
                        <span class="badge {{ $link->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $link->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="d-flex gap-1">
                        <button class="btn btn-sm btn-outline-secondary py-0" data-bs-toggle="modal" data-bs-target="#editLink{{ $link->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form method="POST" action="{{ route('admin.official-links.destroy', $link) }}" onsubmit="return confirm('Delete this link?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger py-0"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>

                {{-- Edit modal --}}
                <div class="modal fade" id="editLink{{ $link->id }}" tabindex="-1">
                    <div class="modal-dialog"><div class="modal-content">
                        <div class="modal-header"><h6 class="modal-title">Edit Link</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                        <form method="POST" action="{{ route('admin.official-links.update', $link) }}">
                            @csrf @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3"><label class="form-label fw-semibold">Title</label>
                                    <input type="text" name="title" class="form-control" value="{{ $link->title }}" required></div>
                                <div class="mb-3"><label class="form-label fw-semibold">URL</label>
                                    <input type="url" name="url" class="form-control" value="{{ $link->url }}" required></div>
                            </div>
                            <div class="modal-footer"><button class="btn btn-primary btn-sm">Update</button></div>
                        </form>
                    </div></div>
                </div>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">No links added yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
