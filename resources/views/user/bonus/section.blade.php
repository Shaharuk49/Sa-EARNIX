@extends('layouts.user')

@section('title', $section->title)

@section('content')
@php
    $watchedCount = count(array_intersect($watchedVideoIds, $videos->pluck('id')->toArray()));
    $completionPercent = $videos->count() > 0 ? round($watchedCount / $videos->count() * 100) : 0;
@endphp
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('user.bonus') }}" class="btn btn-sm btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Back to Bonus
            </a>
            <h2>{{ $section->title }}</h2>
            <p class="text-muted">{{ $section->description }}</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card bonus-section-hero border-0 shadow-sm rounded-4">
                <div class="card-body p-4 d-flex flex-column flex-md-row align-items-start justify-content-between gap-3">
                    <div>
                        <h3 class="fw-bold mb-2">Section Progress</h3>
                        <p class="text-muted mb-0">Track your video completion and finish this section to unlock the next one.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="hero-stat-box text-center">
                            <span class="d-block text-muted small">Videos Watched</span>
                            <strong class="fs-3">{{ $watchedCount }} / {{ $videos->count() }}</strong>
                        </div>
                        <div class="hero-stat-box text-center">
                            <span class="d-block text-muted small">Completion</span>
                            <strong class="fs-3">{{ $completionPercent }}%</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Videos -->
    <div class="row">
        @forelse ($videos as $video)
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <!-- Video Container -->
                    <div class="card-body p-0">
                        <div class="ratio ratio-16x9 bg-dark">
                            <iframe id="video-{{ $video->id }}" 
                                    class="video-frame"
                                    src="{{ $video->video_url }}" 
                                    title="{{ $video->video_title }}"
                                    allowfullscreen="" 
                                    loading="lazy"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share">
                            </iframe>
                        </div>
                    </div>

                    <!-- Video Info -->
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="card-title mb-1">{{ $video->title }}</h5>
                            </div>
                            <span class="badge {{ in_array($video->id, $watchedVideoIds) ? 'bg-success' : 'bg-secondary' }}">
                                <i class="fas {{ in_array($video->id, $watchedVideoIds) ? 'fa-check' : 'fa-eye-slash' }}"></i>
                                {{ in_array($video->id, $watchedVideoIds) ? 'Watched' : 'Not watched' }}
                            </span>
                        </div>
                        <p class="card-text text-muted small">Watch the video embedded above and mark it as watched when complete.</p>
                    </div>

                    <!-- Mark as Watched Button -->
                    @if (!in_array($video->id, $watchedVideoIds))
                        <div class="card-footer bg-light">
                            <button class="btn btn-sm btn-primary w-100 mark-watched-btn" 
                                    data-video-id="{{ $video->id }}"
                                    onclick="markVideoWatched({{ $video->id }})">
                                <i class="fas fa-check"></i> Mark as Watched
                            </button>
                        </div>
                    @else
                        <div class="card-footer bg-light">
                            <div class="text-success text-center py-2">
                                <i class="fas fa-check-circle"></i> Video watched on {{ isset($watchedVideoDates[$video->id]) ? \Carbon\Carbon::parse($watchedVideoDates[$video->id])->format('M d, Y') : now()->format('M d, Y') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No videos available in this section yet.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Progress Summary -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card rounded-4 shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                        <div>
                            <h6 class="card-title mb-1">Section Progress</h6>
                            <p class="text-muted mb-0">Keep going by marking the remaining videos as watched.</p>
                        </div>
                        <strong>{{ $watchedCount }}/{{ $videos->count() }} videos</strong>
                    </div>
                    <div class="progress" style="height: 12px; border-radius: 999px;">
                        <div class="progress-bar rounded-pill bg-success" role="progressbar" style="width: {{ $completionPercent }}%"></div>
                    </div>
                    @if ($watchedCount === $videos->count() && $videos->count() > 0)
                        <div class="alert alert-success mt-3 mb-0 d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            <span>Great! You've completed all videos in this section.</span>
                            <a href="{{ route('user.bonus') }}" class="alert-link ms-auto">Go back to check overall progress</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function markVideoWatched(videoId) {
    const btn = document.querySelector(`[data-video-id="${videoId}"]`);
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Marking...';
    
    fetch(`/bonus/video/${videoId}/watched`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Failed to mark video as watched');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> Mark as Watched';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while marking the video as watched');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> Mark as Watched';
    });
}
</script>
@endpush

@section('styles')
<style>
    .ratio {
        --bs-aspect-ratio: 56.25%;
    }

    .video-frame {
        border: none;
        border-radius: 0.5rem;
    }

    .card {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .bonus-section-hero {
        background: linear-gradient(135deg, rgba(236, 253, 245, 0.8), rgba(219, 234, 254, 0.85));
    }

    .hero-stat-box {
        min-width: 140px;
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 1rem;
        padding: 1rem 1.15rem;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    }

    .ratio {
        --bs-aspect-ratio: 56.25%;
    }

    .video-frame {
        border: none;
        border-radius: 0.5rem;
    }

    .card {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
    }
</style>
@endsection
@endsection
