@extends('layouts.user')
@section('title', 'Freelancing - SA EarniX')

@section('content')
<div class="container py-4">

    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="rounded-circle d-flex align-items-center justify-content-center"
             style="width:52px;height:52px;background:var(--bs-primary);flex-shrink:0">
            <i class="fas fa-laptop-code text-white fs-5"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0">Freelancing Categories</h4>
            <small class="text-muted">Join a group and start earning through freelancing</small>
        </div>
    </div>

    @if($categories->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
            <p class="text-muted">No freelancing categories available yet. Check back soon!</p>
        </div>
    @else
        <div class="row g-3">
            @foreach($categories as $cat)
            <div class="col-md-6 col-lg-4" data-reveal>
                <div class="card h-100 shadow-sm border-0 rounded-4">
                    <div class="card-body d-flex flex-column p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center"
                                 style="width:44px;height:44px;background:var(--bs-primary-bg-subtle);flex-shrink:0">
                                <i class="fas fa-briefcase text-primary"></i>
                            </div>
                            <h5 class="fw-bold mb-0">{{ $cat->name }}</h5>
                        </div>
                        <p class="text-muted small flex-grow-1">Join the official group for this freelancing category and get access to job leads, training, and support.</p>
                        <a href="{{ $cat->group_link }}" target="_blank" rel="noopener noreferrer"
                           class="btn btn-primary rounded-pill mt-2">
                            <i class="fas fa-users me-2"></i>Join Group
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
