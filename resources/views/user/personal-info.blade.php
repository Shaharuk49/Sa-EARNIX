@extends('layouts.user')

@section('title', 'Personal Information - SA EarniX')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Personal Information</h5>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('user.personal-info.update') }}" enctype="multipart/form-data">
                @csrf

                {{-- Profile Photo --}}
                <div class="mb-3">
                    <label class="form-label">Profile Picture</label>
                    <div class="d-flex align-items-end gap-3">
                        <div>
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" 
                                     class="rounded" style="width:80px;height:80px;object-fit:cover;">
                            @else
                                <div class="rounded bg-light d-flex align-items-center justify-content-center" 
                                     style="width:80px;height:80px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <input type="file" name="profile_photo" class="form-control" accept="image/*">
                            <small class="text-muted d-block mt-1">Max 2MB. JPG, PNG, GIF</small>
                        </div>
                    </div>
                </div>

                {{-- Name --}}
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $user->email) }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Phone --}}
                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                           value="{{ old('phone', $user->phone) }}" placeholder="+880...">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Password Change Section --}}
                <hr>
                <h6 class="mb-3">Change Password (optional)</h6>

                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Leave blank to keep current password">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" 
                           placeholder="Confirm new password">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
