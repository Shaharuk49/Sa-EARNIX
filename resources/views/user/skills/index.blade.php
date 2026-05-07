@extends('layouts.user')
@section('title', 'Skills Learning - SA EarniX')

@section('content')
<div class="container py-4">

    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="rounded-circle d-flex align-items-center justify-content-center"
             style="width:52px;height:52px;background:var(--bs-success);flex-shrink:0">
            <i class="fas fa-graduation-cap text-white fs-5"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0">Skills Learning</h4>
            <small class="text-muted">Upgrade your skills with our free video courses</small>
        </div>
    </div>

    @if($courses->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
            <p class="text-muted">No courses available yet. Check back soon!</p>
        </div>
    @else
        <div class="row g-3">
            @foreach($courses as $course)
            <div class="col-md-6 col-lg-4" data-reveal>
                <a href="{{ route('user.skills.show', $course) }}" class="text-decoration-none">
                    <div class="card h-100 shadow-sm border-0 rounded-4 hover-card">
                        @if($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}"
                             class="card-img-top rounded-top-4" style="height:160px;object-fit:cover" alt="{{ $course->title }}">
                        @else
                        <div class="rounded-top-4 d-flex align-items-center justify-content-center"
                             style="height:160px;background:linear-gradient(135deg,var(--bs-success),#198754)">
                            <i class="fas fa-play-circle fa-3x text-white opacity-75"></i>
                        </div>
                        @endif
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-dark mb-1">{{ $course->title }}</h6>
                            @if($course->description)
                            <p class="text-muted small mb-2 line-clamp-2">{{ $course->description }}</p>
                            @endif
                            <span class="badge bg-success-subtle text-success rounded-pill">
                                <i class="fas fa-video me-1"></i>{{ $course->videos_count }} Video{{ $course->videos_count != 1 ? 's' : '' }}
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
