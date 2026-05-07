@extends('layouts.user')

@section('title', 'Leadership Ranks - SA EarniX')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-3" style="background:#f59e0b;">
            <span class="text-white fw-bold fs-5">← র‍্যাংক</span>
        </div>
    </div>

    {{-- Current Badge --}}
    <div class="text-center mb-4">
        <div class="mx-auto mb-3" style="width:140px;height:140px;">
            @if($currentBadge && $currentBadge->icon)
                <img src="{{ $currentBadge->icon }}" alt="{{ $currentBadge->name }}" class="img-fluid">
            @else
                {{-- Default USER badge SVG --}}
                <svg viewBox="0 0 140 140" xmlns="http://www.w3.org/2000/svg" width="140" height="140">
                    <circle cx="70" cy="70" r="65" fill="#e91e8c" opacity="0.15"/>
                    <circle cx="70" cy="70" r="50" fill="#9c27b0"/>
                    <circle cx="70" cy="55" r="18" fill="#ddd"/>
                    <ellipse cx="70" cy="90" rx="22" ry="14" fill="#ddd"/>
                    <polygon points="70,5 75,20 90,20 78,30 83,45 70,36 57,45 62,30 50,20 65,20" fill="#e91e8c"/>
                    <rect x="45" y="100" width="50" height="20" rx="4" fill="#e91e8c"/>
                    <text x="70" y="115" text-anchor="middle" fill="white" font-size="10" font-weight="bold">USER</text>
                </svg>
            @endif
        </div>
        <div class="fw-bold fs-5">বর্তমান র‍্যাংক</div>
        <div class="text-muted">{{ $currentBadge ? $currentBadge->name : 'User' }}</div>
    </div>

    {{-- Badge Grid --}}
    <div class="row g-3">
        @foreach($badgeData as $item)
            <div class="col-4">
                <a href="{{ route('user.badge.show', $item['badge']->slug) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm text-center py-3 {{ $item['earned'] ? 'border-warning border' : '' }}">
                        <div class="mx-auto mb-2" style="width:70px;height:70px;">
                            @if($item['badge']->icon)
                                <img src="{{ $item['badge']->icon }}" alt="{{ $item['badge']->name }}" class="img-fluid">
                            @else
                                @php
                                    $colors = [
                                        'leader'     => ['bg' => '#cd7f32', 'star' => '#f59e0b', 'label' => 'LEADER'],
                                        'silver'     => ['bg' => '#a0aec0', 'star' => '#e2e8f0', 'label' => 'SILVER'],
                                        'gold'       => ['bg' => '#ecc94b', 'star' => '#f59e0b', 'label' => 'GOLD'],
                                        'diamond'    => ['bg' => '#76e4f7', 'star' => '#0bc5ea', 'label' => 'DIAMOND'],
                                        'max-leader' => ['bg' => '#805ad5', 'star' => '#9f7aea', 'label' => 'MAX LEADER'],
                                        'umrah-haji' => ['bg' => '#2b6cb0', 'star' => '#63b3ed', 'label' => 'UMRAH HAJI'],
                                    ];
                                    $c = $colors[$item['badge']->slug] ?? ['bg' => '#718096', 'star' => '#a0aec0', 'label' => strtoupper($item['badge']->name)];
                                @endphp
                                <svg viewBox="0 0 70 70" xmlns="http://www.w3.org/2000/svg" width="70" height="70">
                                    <circle cx="35" cy="35" r="32" fill="{{ $c['bg'] }}" opacity="0.3"/>
                                    <circle cx="35" cy="35" r="25" fill="{{ $c['bg'] }}"/>
                                    <circle cx="35" cy="28" r="9" fill="#ddd"/>
                                    <ellipse cx="35" cy="46" rx="11" ry="7" fill="#ddd"/>
                                    <polygon points="35,3 37,11 45,11 39,16 41,24 35,19 29,24 31,16 25,11 33,11" fill="{{ $c['star'] }}"/>
                                    <rect x="18" y="50" width="24" height="12" rx="3" fill="{{ $c['bg'] }}" opacity="0.8"/>
                                    <text x="30" y="59" text-anchor="middle" fill="white" font-size="5" font-weight="bold">{{ $c['label'] }}</text>
                                </svg>
                            @endif
                        </div>
                        <div class="small fw-bold text-truncate px-1">{{ $item['badge']->name }}</div>
                        @if($item['earned'])
                            <span class="badge bg-success mt-1" style="font-size:0.65rem;">✓ Earned</span>
                        @else
                            <div class="small text-muted mt-1">{{ $item['current'] }}/{{ $item['required'] }}</div>
                        @endif
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
