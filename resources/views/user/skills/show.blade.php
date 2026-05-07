@extends('layouts.user')
@section('title', $course->title . ' - SA EarniX')

@section('content')
<div class="container py-4">

    {{-- Back + Title --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('user.skills.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
        <div>
            <h4 class="fw-bold mb-0">{{ $course->title }}</h4>
            @if($course->description)
            <small class="text-muted">{{ $course->description }}</small>
            @endif
        </div>
    </div>

    @if($videos->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-video-slash fa-3x text-muted mb-3"></i>
            <p class="text-muted">No videos in this course yet.</p>
        </div>
    @else
        {{-- Progress bar --}}
        @php $total = $videos->count(); $watched = count($watchedIds); $pct = $total ? round($watched/$total*100) : 0; @endphp
        <div class="card border-0 rounded-4 shadow-sm mb-4 p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="small fw-semibold text-muted">Your Progress</span>
                <span class="small fw-bold text-success">{{ $watched }}/{{ $total }} watched ({{ $pct }}%)</span>
            </div>
            <div class="progress" style="height:8px;border-radius:8px">
                <div class="progress-bar bg-success" style="width:{{ $pct }}%"></div>
            </div>
        </div>

        <div class="d-flex flex-column gap-3">
            @foreach($videos as $index => $video)
            @php $isWatched = in_array($video->id, $watchedIds); @endphp
            <div class="card border-0 rounded-4 shadow-sm skill-video-card" data-video-id="{{ $video->id }}">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:36px;height:36px;background:{{ $isWatched ? 'var(--bs-success)' : 'var(--bs-secondary-bg)' }}">
                            <i class="fas fa-{{ $isWatched ? 'check' : 'play' }} {{ $isWatched ? 'text-white' : 'text-muted' }} small"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-semibold mb-0">{{ $index + 1 }}. {{ $video->title }}</h6>
                        </div>
                        @if($isWatched)
                        <span class="badge bg-success rounded-pill small">Watched</span>
                        @else
                        <button class="btn btn-sm btn-outline-success rounded-pill mark-watched-btn"
                                data-video-id="{{ $video->id }}"
                                data-url="{{ route('user.skills.video.watched', $video) }}">
                            <i class="fas fa-check me-1"></i>Mark Watched
                        </button>
                        @endif
                    </div>

                    {{-- Embed YouTube or direct video --}}
                    @php
                        $url   = $video->video_url;
                        $ytId  = null;
                        if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([A-Za-z0-9_\-]{11})/', $url, $m)) {
                            $ytId = $m[1];
                        }
                    @endphp
                    @if($ytId)
                    <div class="ratio ratio-16x9 rounded-3 overflow-hidden mt-2">
                        <iframe src="https://www.youtube.com/embed/{{ $ytId }}"
                                title="{{ $video->title }}"
                                allowfullscreen
                                loading="lazy"></iframe>
                    </div>
                    @else
                    <div class="mt-2">
                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                           class="btn btn-sm btn-primary rounded-pill">
                            <i class="fas fa-external-link-alt me-1"></i>Watch Video
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>

<script>
document.querySelectorAll('.mark-watched-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const url     = this.dataset.url;
        const videoId = this.dataset.videoId;
        const card    = this.closest('.skill-video-card');

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                this.outerHTML = '<span class="badge bg-success rounded-pill small">Watched</span>';
                const icon = card.querySelector('.rounded-circle i');
                const circle = card.querySelector('.rounded-circle');
                circle.style.background = 'var(--bs-success)';
                icon.className = 'fas fa-check text-white small';

                // Update progress bar
                const badges = document.querySelectorAll('.badge.bg-success.rounded-pill.small');
                const total  = {{ $total }};
                const watched = badges.length;
                const pct    = Math.round(watched / total * 100);
                document.querySelector('.progress-bar').style.width = pct + '%';
                document.querySelector('.fw-bold.text-success').textContent = watched + '/' + total + ' watched (' + pct + '%)';
            }
        });
    });
});
</script>
@endsection
