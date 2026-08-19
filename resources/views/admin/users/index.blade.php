@extends('layouts.admin')
@section('title', 'All Users')
@section('page-title', 'All Users')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
            <span class="fw-semibold">Total: {{ $users->total() }} users</span>
            <div class="btn-group btn-group-sm" role="group" aria-label="Filter users by ban status">
                <a href="{{ route('admin.users.index') }}" class="btn {{ $status === '' ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
                <a href="{{ route('admin.users.index', ['status' => 'unbanned']) }}" class="btn {{ $status === 'unbanned' ? 'btn-primary' : 'btn-outline-primary' }}">Unbanned</a>
                <a href="{{ route('admin.users.index', ['status' => 'banned']) }}" class="btn {{ $status === 'banned' ? 'btn-primary' : 'btn-outline-primary' }}">Banned</a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 small align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Affiliate ID</th>
                        <th>Phone</th>
                        <th>Referrals</th>
                        <th>Status</th>
                        <th>Ban Control</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $user)
                    <tr>
                        <td class="text-muted">{{ $users->firstItem() + $i }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $user) }}" class="fw-semibold text-decoration-none">
                                {{ $user->name }}
                            </a>
                            <div class="text-muted">{{ $user->email }}</div>
                            <div class="small text-primary">{{ '@' . $user->username }}</div>
                        </td>
                        <td><code>{{ $user->affiliate_id }}</code></td>
                        <td>{{ $user->phone ?? '—' }}</td>
                        <td>{{ $user->referral_count ?? 0 }}</td>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                            @if($user->banned_at)
                                <span class="badge bg-danger">Banned</span>
                            @else
                                <span class="badge bg-success">Unbanned</span>
                            @endif
                            @if($user->is_premium)
                                <span class="badge bg-warning text-dark">Premium</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.users.toggle-ban', $user) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $user->banned_at ? 'btn-outline-success' : 'btn-outline-danger' }}">
                                    {{ $user->banned_at ? 'Unban' : 'Ban' }}
                                </button>
                            </form>
                        </td>
                        <td class="text-muted">{{ $user->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">{{ $users->links() }}</div>
</div>
@endsection
