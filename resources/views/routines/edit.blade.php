@extends('layouts.app')

@section('title', 'Edit Routine')

@push('styles')
<style>
    .main-content { padding: 14px 16px; background: #f7f8fa; min-height: 100vh; }

    .cu-header {
        background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        border-radius: 10px; padding: 12px 18px; color: white;
        margin-bottom: 14px; position: relative; overflow: hidden;
        border: 1px solid #6d28d9; box-shadow: 0 2px 8px rgba(124,58,237,.3);
    }
    .cu-header::before {
        content: ''; position: absolute; top: 0; right: 0;
        width: 80px; height: 80px; background: rgba(255,255,255,.08);
        border-radius: 50%; transform: translate(20px,-20px);
    }
    .cu-header-title { font-weight: 700; font-size: 17px; margin: 0; position: relative; z-index: 1; }
    .cu-header-sub   { font-size: 12px; opacity: .8; margin: 2px 0 0; position: relative; z-index: 1; }

    .cu-layout { display: grid; grid-template-columns: 220px 1fr; gap: 14px; align-items: start; }
    @media(max-width:768px) { .cu-layout { grid-template-columns: 1fr; } }

    .cu-info-panel {
        background: white; border: 1px solid #e3e4e8; border-radius: 8px;
        overflow: hidden; position: sticky; top: 14px;
    }
    .cu-info-panel-header {
        background: #f7f8fa; border-bottom: 1px solid #e3e4e8; padding: 10px 14px;
    }
    .cu-info-panel-header span { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: #8a8f98; }
    .cu-info-body { padding: 14px; }
    .cu-avatar {
        width: 48px; height: 48px; border-radius: 10px; background: #7c3aed;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; color: white; margin: 0 auto 10px;
    }
    .cu-avatar.weekly  { background: #2563eb; }
    .cu-avatar.monthly { background: #d97706; }
    .cu-panel-name { text-align: center; font-size: 13px; font-weight: 700; color: #1a1d23; margin-bottom: 6px; word-break: break-word; }
    .cu-freq-badge {
        display: flex; align-items: center; justify-content: center; gap: 5px;
        padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .6px; margin-bottom: 12px;
    }
    .cu-freq-badge.daily   { background: #ede9fe; color: #6d28d9; }
    .cu-freq-badge.weekly  { background: #dbeafe; color: #1d4ed8; }
    .cu-freq-badge.monthly { background: #fef3c7; color: #b45309; }
    .cu-meta-row {
        display: flex; align-items: flex-start; gap: 8px;
        padding: 7px 0; border-top: 1px solid #f0f1f3;
        font-size: 12px; color: #6b7385;
    }
    .cu-meta-row i { font-size: 13px; color: #adb0b8; flex-shrink: 0; margin-top: 1px; }
    .cu-meta-row strong { color: #1a1d23; font-weight: 600; }

    .cu-sections { display: flex; flex-direction: column; gap: 10px; }
    .cu-section { background: white; border: 1px solid #e3e4e8; border-radius: 8px; overflow: hidden; }
    .cu-section-header {
        display: flex; align-items: center; gap: 8px;
        padding: 10px 16px; background: #fafbfc; border-bottom: 1px solid #e3e4e8;
    }
    .cu-section-icon {
        width: 26px; height: 26px; border-radius: 6px;
        display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0;
    }
    .cu-section-icon.purple { background: #ede9fe; color: #7c3aed; }
    .cu-section-icon.blue   { background: #dbeafe; color: #2563eb; }
    .cu-section-icon.green  { background: #dcfce7; color: #16a34a; }
    .cu-section-title { font-size: 13px; font-weight: 700; color: #1a1d23; margin: 0; }
    .cu-section-sub   { font-size: 11px; color: #8a8f98; margin: 0 0 0 auto; }
    .cu-section-body  { padding: 16px; }

    .cu-field { margin-bottom: 14px; }
    .cu-field:last-child { margin-bottom: 0; }
    .cu-field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    @media(max-width:500px) { .cu-field-row { grid-template-columns: 1fr; } }
    .cu-label {
        display: block; font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .7px; color: #8a8f98; margin-bottom: 5px;
    }
    .cu-input {
        width: 100%; height: 34px; padding: 0 10px 0 34px;
        border: 1px solid #d3d5db; border-radius: 6px; background: white;
        font-size: 13px; color: #1a1d23; outline: none;
        transition: border-color .15s, box-shadow .15s; box-sizing: border-box;
    }
    .cu-input:focus { border-color: #7c3aed; box-shadow: 0 0 0 2px rgba(124,58,237,.15); }
    .cu-input.is-invalid { border-color: #dc2626; }
    .cu-textarea {
        width: 100%; padding: 8px 10px; min-height: 90px;
        border: 1px solid #d3d5db; border-radius: 6px; background: white;
        font-size: 13px; color: #1a1d23; outline: none; resize: vertical;
        transition: border-color .15s, box-shadow .15s; box-sizing: border-box;
    }
    .cu-textarea:focus { border-color: #7c3aed; box-shadow: 0 0 0 2px rgba(124,58,237,.15); }
    .cu-input-wrap { position: relative; }
    .cu-input-wrap > i {
        position: absolute; left: 10px; top: 50%;
        transform: translateY(-50%); font-size: 13px; color: #adb0b8; pointer-events: none;
    }
    .invalid-feedback { display: block; margin-top: 4px; font-size: 11px; color: #dc2626; font-weight: 500; }

    .cu-freq-chips { display: flex; gap: 8px; flex-wrap: wrap; }
    .cu-chip-opt   { position: relative; }
    .cu-chip-opt input { position: absolute; opacity: 0; width: 0; height: 0; }
    .cu-chip-label {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 14px; border: 1px solid #d3d5db; border-radius: 20px;
        cursor: pointer; font-size: 12px; font-weight: 600; color: #6b7385;
        background: white; transition: all .15s; user-select: none;
    }
    .cu-chip-label:hover    { border-color: #7c3aed; color: #7c3aed; background: #faf5ff; }
    .chip-daily   input:checked + .cu-chip-label { color: #5b21b6; border-color: #7c3aed; background: #ede9fe; }
    .chip-weekly  input:checked + .cu-chip-label { color: #1d4ed8; border-color: #2563eb; background: #dbeafe; }
    .chip-monthly input:checked + .cu-chip-label { color: #b45309; border-color: #d97706; background: #fef3c7; }

    .cu-picker { display: none; margin-top: 14px; padding-top: 14px; border-top: 1px solid #e3e4e8; }
    .cu-picker.visible { display: block; }
    .cu-picker-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .7px; color: #8a8f98; margin-bottom: 10px; }
    .cu-check-grid { display: flex; flex-wrap: wrap; gap: 6px; }
    .cu-check-item { position: relative; }
    .cu-check-item input { position: absolute; opacity: 0; width: 0; height: 0; }
    .cu-check-item label {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 4px 10px; min-width: 36px; border: 1px solid #d3d5db; border-radius: 6px;
        cursor: pointer; font-size: 12px; font-weight: 600; color: #6b7385;
        background: white; transition: all .15s; user-select: none; text-align: center;
    }
    .cu-check-item label:hover { border-color: #7c3aed; color: #7c3aed; background: #faf5ff; }
    .cu-check-item input:checked + label { background: #7c3aed; border-color: #7c3aed; color: white; }
    .cu-week-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(50px,1fr)); gap: 5px; max-height: 220px; overflow-y: auto; }
    .cu-week-grid .cu-check-item label { width: 100%; font-size: 11px; padding: 4px 6px; }

    .cu-action-bar {
        display: flex; align-items: center; justify-content: flex-end; gap: 8px;
        padding: 12px 16px; background: #fafbfc; border-top: 1px solid #e3e4e8;
    }
    .cu-btn-cancel {
        padding: 6px 16px; border: 1px solid #d3d5db; background: white; color: #6b7385;
        border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none;
        transition: all .15s; line-height: 1.4;
    }
    .cu-btn-cancel:hover { border-color: #adb0b8; color: #1a1d23; background: #f7f8fa; }
    .cu-btn-save {
        padding: 6px 18px; background: #7c3aed; border: 1px solid #7c3aed;
        color: white; border-radius: 6px; font-size: 13px; font-weight: 600;
        cursor: pointer; transition: all .15s; line-height: 1.4;
    }
    .cu-btn-save:hover { background: #6d28d9; border-color: #6d28d9; box-shadow: 0 2px 6px rgba(109,40,217,.35); }

    .cu-danger-section {
        background: white; border: 1px solid #fca5a5; border-radius: 8px;
        overflow: hidden; margin-top: 10px;
    }
    .cu-danger-header {
        display: flex; align-items: center; gap: 8px;
        padding: 10px 16px; background: #fff5f5; border-bottom: 1px solid #fca5a5;
    }
    .cu-danger-header span { font-size: 13px; font-weight: 700; color: #dc2626; }
    .cu-danger-body { padding: 16px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
    .cu-danger-desc { font-size: 13px; color: #6b7385; }
    .cu-btn-danger {
        padding: 6px 16px; background: #dc2626; border: 1px solid #dc2626;
        color: white; border-radius: 6px; font-size: 13px; font-weight: 600;
        cursor: pointer; transition: all .15s; line-height: 1.4;
    }
    .cu-btn-danger:hover { background: #b91c1c; border-color: #b91c1c; }
</style>
@endpush

@php
    $currentDays   = json_decode($routine->days,   true) ?? [];
    $currentWeeks  = json_decode($routine->weeks,  true) ?? [];
    $currentMonths = json_decode($routine->months, true) ?? [];
    $freq          = old('frequency', $routine->frequency);
@endphp

@section('content')
<div class="main-content">

    {{-- Gradient Header --}}
    <div class="cu-header">
        <div class="d-flex align-items-center" style="position:relative;z-index:1;">
            <a href="{{ route('routines.index') }}" class="me-3 text-decoration-none">
                <i class="bi bi-arrow-left fs-5" style="color:rgba(255,255,255,.8);"></i>
            </a>
            <div>
                <h1 class="cu-header-title">Edit Routine</h1>
                <p class="cu-header-sub">Update "{{ $routine->title }}"</p>
            </div>
        </div>
    </div>

    {{-- Two-Panel Layout --}}
    <div class="cu-layout">

        {{-- Left Panel --}}
        <div class="cu-info-panel">
            <div class="cu-info-panel-header"><span>Routine Info</span></div>
            <div class="cu-info-body">
                <div class="cu-avatar {{ $routine->frequency }}">
                    @if($routine->frequency === 'daily')
                        <i class="bi bi-sun"></i>
                    @elseif($routine->frequency === 'weekly')
                        <i class="bi bi-calendar-week"></i>
                    @else
                        <i class="bi bi-calendar-month"></i>
                    @endif
                </div>
                <div class="cu-panel-name">{{ $routine->title }}</div>
                <div class="cu-freq-badge {{ $routine->frequency }}">{{ ucfirst($routine->frequency) }}</div>

                @if($routine->start_time)
                <div class="cu-meta-row">
                    <i class="bi bi-clock"></i>
                    <span>
                        {{ \Carbon\Carbon::parse($routine->start_time)->format('g:i A') }}
                        @if($routine->end_time)
                            &ndash; {{ \Carbon\Carbon::parse($routine->end_time)->format('g:i A') }}
                        @endif
                    </span>
                </div>
                @endif

                @if($routine->frequency === 'daily' && count($currentDays))
                <div class="cu-meta-row">
                    <i class="bi bi-calendar3"></i>
                    <span>{{ implode(', ', array_map(fn($d) => ucfirst(substr($d,0,3)), $currentDays)) }}</span>
                </div>
                @endif

                @if($routine->frequency === 'weekly' && count($currentWeeks))
                <div class="cu-meta-row">
                    <i class="bi bi-calendar3"></i>
                    <span>{{ count($currentWeeks) }} week{{ count($currentWeeks) > 1 ? 's' : '' }} selected</span>
                </div>
                @endif

                @if($routine->frequency === 'monthly' && count($currentMonths))
                <div class="cu-meta-row">
                    <i class="bi bi-calendar3"></i>
                    <span>{{ implode(', ', array_map(fn($m) => \Carbon\Carbon::createFromDate(null,(int)$m,1)->format('M'), $currentMonths)) }}</span>
                </div>
                @endif

                <div class="cu-meta-row">
                    <i class="bi bi-pencil"></i>
                    <span>Updated <strong>{{ $routine->updated_at->format('M d, Y') }}</strong></span>
                </div>
            </div>
        </div>

        {{-- Right Form --}}
        <form action="{{ route('routines.update', $routine->id) }}" method="POST" id="routineForm">
            @csrf
            @method('PUT')
            <div class="cu-sections">

                {{-- Basic Info --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon purple"><i class="bi bi-card-text"></i></span>
                        <span class="cu-section-title">Basic Info</span>
                    </div>
                    <div class="cu-section-body">
                        <div class="cu-field">
                            <label for="title" class="cu-label">Title <span style="color:#dc2626;">*</span></label>
                            <div class="cu-input-wrap">
                                <i class="bi bi-card-text"></i>
                                <input type="text" name="title" id="title"
                                       class="cu-input {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                       value="{{ old('title', $routine->title) }}"
                                       placeholder="e.g. Morning workout" required>
                            </div>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="cu-field">
                            <label for="description" class="cu-label">Description</label>
                            <textarea name="description" id="description"
                                      class="cu-textarea {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                      placeholder="Add details about this routine...">{{ old('description', $routine->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Schedule --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon blue"><i class="bi bi-calendar3"></i></span>
                        <span class="cu-section-title">Schedule</span>
                        <span class="cu-section-sub">Frequency &amp; recurrence</span>
                    </div>
                    <div class="cu-section-body">
                        <div class="cu-field">
                            <label class="cu-label">Frequency <span style="color:#dc2626;">*</span></label>
                            <div class="cu-freq-chips">
                                <div class="cu-chip-opt chip-daily">
                                    <input type="radio" name="frequency" id="freq_daily" value="daily"
                                        {{ $freq === 'daily' ? 'checked' : '' }}>
                                    <label for="freq_daily" class="cu-chip-label">
                                        <i class="bi bi-sun"></i> Daily
                                    </label>
                                </div>
                                <div class="cu-chip-opt chip-weekly">
                                    <input type="radio" name="frequency" id="freq_weekly" value="weekly"
                                        {{ $freq === 'weekly' ? 'checked' : '' }}>
                                    <label for="freq_weekly" class="cu-chip-label">
                                        <i class="bi bi-calendar-week"></i> Weekly
                                    </label>
                                </div>
                                <div class="cu-chip-opt chip-monthly">
                                    <input type="radio" name="frequency" id="freq_monthly" value="monthly"
                                        {{ $freq === 'monthly' ? 'checked' : '' }}>
                                    <label for="freq_monthly" class="cu-chip-label">
                                        <i class="bi bi-calendar-month"></i> Monthly
                                    </label>
                                </div>
                            </div>
                            @error('frequency')<div class="invalid-feedback mt-1">{{ $message }}</div>@enderror
                        </div>

                        {{-- Day picker --}}
                        <div class="cu-picker {{ $freq === 'daily' ? 'visible' : '' }}" id="picker-daily">
                            <div class="cu-picker-title">Select days</div>
                            <div class="cu-check-grid">
                                @foreach(['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $day)
                                <div class="cu-check-item">
                                    <input type="checkbox" name="days[]" value="{{ $day }}" id="day_{{ $day }}"
                                        {{ in_array($day, old('days', $currentDays)) ? 'checked' : '' }}>
                                    <label for="day_{{ $day }}">{{ ucfirst(substr($day,0,3)) }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Week picker --}}
                        <div class="cu-picker {{ $freq === 'weekly' ? 'visible' : '' }}" id="picker-weekly">
                            <div class="cu-picker-title">Select weeks (1 - 52)</div>
                            <div class="cu-week-grid">
                                @for($w = 1; $w <= 52; $w++)
                                <div class="cu-check-item">
                                    <input type="checkbox" name="weeks[]" value="{{ $w }}" id="week_{{ $w }}"
                                        {{ in_array($w, old('weeks', $currentWeeks)) ? 'checked' : '' }}>
                                    <label for="week_{{ $w }}">W{{ $w }}</label>
                                </div>
                                @endfor
                            </div>
                        </div>

                        {{-- Month picker --}}
                        <div class="cu-picker {{ $freq === 'monthly' ? 'visible' : '' }}" id="picker-monthly">
                            <div class="cu-picker-title">Select months</div>
                            <div class="cu-check-grid">
                                @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $idx => $month)
                                <div class="cu-check-item">
                                    <input type="checkbox" name="months[]" value="{{ $idx + 1 }}" id="month_{{ $idx + 1 }}"
                                        {{ in_array($idx + 1, old('months', $currentMonths)) ? 'checked' : '' }}>
                                    <label for="month_{{ $idx + 1 }}">{{ substr($month,0,3) }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Time Window --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon green"><i class="bi bi-clock"></i></span>
                        <span class="cu-section-title">Time Window</span>
                        <span class="cu-section-sub">Start &amp; end time</span>
                    </div>
                    <div class="cu-section-body">
                        <div class="cu-field-row">
                            <div class="cu-field" style="margin-bottom:0;">
                                <label for="start_time" class="cu-label">Start Time <span style="color:#dc2626;">*</span></label>
                                <div class="cu-input-wrap">
                                    <i class="bi bi-clock"></i>
                                    <input type="time" name="start_time" id="start_time"
                                           class="cu-input {{ $errors->has('start_time') ? 'is-invalid' : '' }}"
                                           value="{{ old('start_time', $routine->start_time ? \Carbon\Carbon::parse($routine->start_time)->format('H:i') : '') }}"
                                           required>
                                </div>
                                @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="cu-field" style="margin-bottom:0;">
                                <label for="end_time" class="cu-label">End Time <span style="color:#dc2626;">*</span></label>
                                <div class="cu-input-wrap">
                                    <i class="bi bi-clock-fill"></i>
                                    <input type="time" name="end_time" id="end_time"
                                           class="cu-input {{ $errors->has('end_time') ? 'is-invalid' : '' }}"
                                           value="{{ old('end_time', $routine->end_time ? \Carbon\Carbon::parse($routine->end_time)->format('H:i') : '') }}"
                                           required>
                                </div>
                                @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="cu-action-bar">
                        <a href="{{ route('routines.index') }}" class="cu-btn-cancel">Cancel</a>
                        <button type="submit" class="cu-btn-save">
                            <i class="bi bi-check-lg me-1"></i>Save Changes
                        </button>
                    </div>
                </div>

            </div>
        </form>

    </div>{{-- /.cu-layout --}}

    {{-- Danger Zone --}}
    <div class="cu-danger-section">
        <div class="cu-danger-header">
            <i class="bi bi-exclamation-triangle-fill" style="color:#dc2626;font-size:14px;"></i>
            <span>Danger Zone</span>
        </div>
        <div class="cu-danger-body">
            <div class="cu-danger-desc">
                Permanently delete this routine. This action <strong>cannot be undone</strong>.
            </div>
            <form action="{{ route('routines.destroy', $routine->id) }}" method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <button type="button" class="cu-btn-danger" onclick="confirmDelete()">
                    <i class="bi bi-trash me-1"></i>Delete Routine
                </button>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios  = document.querySelectorAll('input[name="frequency"]');
    const pickers = {
        daily:   document.getElementById('picker-daily'),
        weekly:  document.getElementById('picker-weekly'),
        monthly: document.getElementById('picker-monthly'),
    };

    function updatePickers(val) {
        Object.entries(pickers).forEach(([key, el]) => {
            el.classList.toggle('visible', key === val);
        });
    }

    radios.forEach(r => r.addEventListener('change', () => updatePickers(r.value)));
});

function confirmDelete() {
    if (confirm('Delete this routine? This cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endpush
