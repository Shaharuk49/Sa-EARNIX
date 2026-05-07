@extends('layouts.admin')
@section('title', 'All Users')
@section('page-title', 'All Users')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <span class="fw-semibold">Total: {{ $users->total() }} users</span>
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
                            @if($user->is_premium)
                                <span class="badge bg-warning text-dark">Premium</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $user->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">{{ $users->links() }}</div>
</div>
@endsection
