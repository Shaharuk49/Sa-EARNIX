@extends('layouts.user')

@section('title', $section->title)

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('user.bonus') }}" class="btn btn-sm btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Back to Bonus
            </a>
            <h2>{{ $section->title }}</h2>
            <p class="text-muted">{{ $section->description }}</p>
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
                                <h5 class="card-title mb-1">{{ $video->video_title }}</h5>
                                <small class="text-muted">{{ $video->duration_minutes }} minutes</small>
                            </div>
                            <span class="badge {{ in_array($video->id, $watchedVideoIds) ? 'bg-success' : 'bg-secondary' }}">
                                <i class="fas {{ in_array($video->id, $watchedVideoIds) ? 'fa-check' : 'fa-eye-slash' }}"></i>
                                {{ in_array($video->id, $watchedVideoIds) ? 'Watched' : 'Not watched' }}
                            </span>
                        </div>
                        <p class="card-text text-muted small">{{ $video->description }}</p>
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
                                <i class="fas fa-check-circle"></i> Video watched on {{ \Carbon\Carbon::parse($watchedVideoIds[$video->id] ?? now())->format('M d, Y') }}
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
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Section Progress</h6>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Videos Watched</span>
                        <strong>{{ count(array_intersect($watchedVideoIds, array_column($videos->toArray(), 'id'))) }}/{{ $videos->count() }}</strong>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $videos->count() > 0 ? (count(array_intersect($watchedVideoIds, array_column($videos->toArray(), 'id'))) / $videos->count() * 100) : 0 }}%">
                        </div>
                    </div>
                    @if (count(array_intersect($watchedVideoIds, array_column($videos->toArray(), 'id'))) == $videos->count())
                        <div class="alert alert-success mt-3 mb-0">
                            <i class="fas fa-check-circle"></i> Great! You've completed all videos in this section.
                            <a href="{{ route('user.bonus') }}" class="alert-link">Go back to check overall progress</a>
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

    .card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
    }
</style>
@endsection
@endsection
