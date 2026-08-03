@extends('layouts.admin')
@section('title', 'Bonus Sections')
@section('page-title', 'Welcome Bonus – Sections & Videos')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>There was a problem saving the form.</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
{{-- Bonus Amount --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-semibold">Welcome Bonus Amount</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.bonus.amount.update') }}" class="row g-2 align-items-end">
            @csrf
            <div class="col-auto flex-grow-1">
                <label class="form-label mb-1">Amount (BDT)</label>
                <input type="number" name="welcome_bonus_amount" class="form-control @error('welcome_bonus_amount') is-invalid @enderror" value="{{ old('welcome_bonus_amount', $bonusAmount) }}" min="0" required>
                @error('welcome_bonus_amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-auto">
                <button class="btn btn-success">Save Amount</button>
            </div>
        </form>
    </div>
</div>

{{-- Add New Section --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-semibold">Add New Section</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.bonus.section.store') }}" class="row g-2 align-items-end">
            @csrf
            <div class="col">
                <label class="form-label mb-1">Section title</label>
                <input type="text" name="title" id="newSectionTitle" class="form-control" placeholder="Section title..." required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary text-nowrap">Add Section</button>
            </div>
        </form>
        <small class="text-muted d-block mt-3">Enter a new section title and click Add Section to create it immediately.</small>
    </div>
</div>

{{-- Sections --}}
@forelse($sections as $section)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary">Section {{ $section->sort_order }}</span>
            <span class="fw-semibold">{{ $section->title }}</span>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editSection{{ $section->id }}">
                <i class="fas fa-edit"></i>
            </button>
            <form method="POST" action="{{ route('admin.bonus.section.destroy', $section) }}" onsubmit="return confirm('Delete this section and all its content?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
            </form>
        </div>
    </div>

    <div class="card-body">
        <div class="row g-3">
            {{-- Videos --}}
            <div class="col-md-7">
                <div class="fw-semibold small mb-2"><i class="fas fa-play-circle me-1 text-danger"></i>Videos ({{ $section->videos->count() }})</div>
                @foreach($section->videos as $video)
                <div class="d-flex align-items-center gap-2 mb-2 p-2 bg-light rounded">
                    <div class="flex-grow-1">
                        <div class="small fw-semibold">{{ $video->title }}</div>
                        <div class="text-muted" style="font-size:.7rem; word-break:break-all;">{{ $video->video_url }}</div>
                    </div>
                    <form method="POST" action="{{ route('admin.bonus.video.destroy', $video) }}" onsubmit="return confirm('Remove video?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2">Del</button>
                    </form>
                </div>
                @endforeach
                {{-- Add video --}}
                <div class="mt-2" data-section-id="{{ $section->id }}">
                    <div class="mb-2">
                        <label class="form-label small mb-1">Video title</label>
                        <input type="text" class="form-control form-control-sm video-title-input" placeholder="Video title...">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small mb-1">Video URL</label>
                        <input type="url" class="form-control form-control-sm video-url-input" placeholder="https://youtube.com/...">
                    </div>
                    <button type="button" class="btn btn-sm btn-primary save-video-button">Add video to save list</button>
                </div>
            </div>

            {{-- Rules --}}
            <div class="col-md-5">
                <div class="fw-semibold small mb-2"><i class="fas fa-list me-1 text-primary"></i>Unlock Rules ({{ $section->rules->count() }})</div>
                @foreach($section->rules as $rule)
                <div class="d-flex align-items-start gap-2 mb-2">
                    <span class="small flex-grow-1 text-muted">
                        <strong>
                            @if($rule->rule_type === 'direct_referrals')
                                Direct referrals
                            @elseif($rule->rule_type === 'total_referrals')
                                Total team referrals
                            @else
                                Premium account required
                            @endif
                        </strong>
                        @if($rule->rule_type === 'premium_required')
                            
                        @else
                            : {{ $rule->rule_value }}
                        @endif
                    </span>
                    <form method="POST" action="{{ route('admin.bonus.rule.destroy', $rule) }}" onsubmit="return confirm('Remove rule?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2">Del</button>
                    </form>
                </div>
                @endforeach
                <div class="mt-2" data-section-id="{{ $section->id }}">
                    <div class="mb-2">
                        <label class="form-label small mb-1">Rule type</label>
                        <select class="form-control form-control-sm rule-type-input">
                            <option value="">Select rule...</option>
                            <option value="direct_referrals">Direct referrals</option>
                            <option value="total_referrals">Total team referrals</option>
                            <option value="premium_required">Premium account required</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small mb-1">Rule value</label>
                        <input type="text" class="form-control form-control-sm rule-value-input" placeholder="Value (e.g. 2)">
                    </div>
                    <button type="button" class="btn btn-sm btn-primary save-rule-button">Add rule to save list</button>
                    <div class="form-text small">Use a value only for referral rules. Leave value empty for premium requirement.</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit section modal --}}
<div class="modal fade" id="editSection{{ $section->id }}" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h6 class="modal-title">Edit Section</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form method="POST" action="{{ route('admin.bonus.section.update', $section) }}">
            @csrf @method('PUT')
            <div class="modal-body">
                <input type="text" name="title" class="form-control" value="{{ $section->title }}" required>
            </div>
            <div class="modal-footer"><button class="btn btn-primary btn-sm">Update</button></div>
        </form>
    </div></div>
</div>
@empty
<div class="alert alert-info">No bonus sections yet. Add one above.</div>
@endforelse
<div class="d-flex justify-content-end mt-4 mb-5">
    <button id="saveAllBonusData" class="btn btn-success">Save All Bonus Data</button>
</div>
<form method="POST" action="{{ route('admin.bonus.save_all') }}" id="adminBonusSaveAllForm" class="d-none">
    @csrf
    <input type="hidden" name="welcome_bonus_amount" id="hiddenBonusAmount">
    <div id="hiddenSectionData"></div>
</form>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const saveAllButton = document.getElementById('saveAllBonusData');
        const bonusAmountInput = document.querySelector('input[name="welcome_bonus_amount"]');
        const sectionTitleInput = document.getElementById('newSectionTitle');
        const hiddenForm = document.getElementById('adminBonusSaveAllForm');
        const hiddenSectionData = document.getElementById('hiddenSectionData');

        saveAllButton.addEventListener('click', function () {
            document.getElementById('hiddenBonusAmount').value = bonusAmountInput ? bonusAmountInput.value : '';
            const title = sectionTitleInput.value.trim();
            if (title) {
                hiddenSectionData.insertAdjacentHTML('beforeend', `
                    <input type="hidden" name="new_section_title" value="${title}">
                `);
                sectionTitleInput.value = '';
            }

            document.querySelectorAll('[data-section-id]').forEach(sectionEl => {
                const sectionId = sectionEl.dataset.sectionId;
                const videoTitle = sectionEl.querySelector('.video-title-input')?.value || '';
                const videoUrl = sectionEl.querySelector('.video-url-input')?.value || '';
                const ruleType = sectionEl.querySelector('.rule-type-input')?.value || '';
                const ruleValue = sectionEl.querySelector('.rule-value-input')?.value || '';

                if (videoTitle || videoUrl) {
                    hiddenSectionData.insertAdjacentHTML('beforeend', `
                        <input type="hidden" name="video_titles[${sectionId}]" value="${encodeURIComponent(videoTitle)}">
                        <input type="hidden" name="video_urls[${sectionId}]" value="${encodeURIComponent(videoUrl)}">
                    `);
                }

                if (ruleType) {
                    hiddenSectionData.insertAdjacentHTML('beforeend', `
                        <input type="hidden" name="rule_types[${sectionId}]" value="${ruleType}">
                        <input type="hidden" name="rule_values[${sectionId}]" value="${encodeURIComponent(ruleValue)}">
                    `);
                }
            });

            hiddenForm.submit();
        });
    });
</script>
@endpush
