@extends('layouts.user')
@section('title', 'Dashboard')
@section('content')
<div class="container py-4">
    <h2>Welcome back, {{ Auth::user()->name }}!</h2>
    <p class="text-muted">Your account is active and ready to earn.</p>
    <a href="{{ route('user.home') }}" class="btn btn-primary">Go to Dashboard</a>
</div>
@endsection