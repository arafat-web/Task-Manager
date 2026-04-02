@extends('layouts.app')

@section('title', 'Edit Reminder')

@push('styles')
<style>
.main-content { padding: 14px 16px; background: #f7f8fa; min-height: 100vh; }

/* Top header bar */
.cu-header {
    background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
    border-radius: 8px; padding: 12px 16px; margin-bottom: 14px;
    position: relative; overflow: hidden;
}
.cu-header::before {
    content: ''; position: absolute; top: -20px; right: -20px;
    width: 80px; height: 80px; background: rgba(255,255,255,.08); border-radius: 50%;
}
.cu-header-title { font-weight: 700; font-size: 17px; margin: 0; color: #fff; position: relative; z-index: 1; }
.cu-header-sub   { font-size: 12px; opacity: .8; margin: 2px 0 0; color: #fff; position: relative; z-index: 1; }

/* Two-column layout */
.cu-layout { display: grid; grid-template-columns: 220px 1fr; gap: 14px; align-items: start; }
@media(max-width:768px) { .cu-layout { grid-template-columns: 1fr; } }

/* Left info panel */
.cu-info-panel {
    background: white; border: 1px solid #e3e4e8; border-radius: 8px;
    overflow: hidden; position: sticky; top: 1rem;
}
.cu-info-panel-header { background: #f7f8fa; border-bottom: 1px solid #e3e4e8; padding: 10px 14px; }
.cu-info-panel-header span { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: #8a8f98; }
.cu-info-body { padding: 16px 14px; }
.cu-avatar {
    width: 48px; height: 48px; border-radius: 10px; background: #f59e0b;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; color: #fff; margin: 0 auto 10px;
}
.cu-panel-name { text-align: center; font-size: 13px; font-weight: 700; color: #1a1d23; margin-bottom: 4px; line-height: 1.35; }
.cu-panel-sub  { text-align: center; font-size: 11px; color: #adb0b8; margin-bottom: 12px; }
.cu-meta-row {
    display: flex; align-items: flex-start; gap: 8px;
    font-size: 12px; color: #6b7280; padding: 5px 0;
    border-top: 1px solid #f3f4f6;
}
.cu-meta-row i { font-size: 13px; color: #adb0b8; flex-shrink: 0; margin-top: 1px; }
.cu-meta-row strong { color: #1a1d23; font-weight: 600; }

/* Priority chip in panel */
.cu-pri-row { display: flex; justify-content: center; margin-bottom: 10px; }
.cu-pri-badge {
    font-size: 11px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .05em; padding: 3px 10px; border-radius: 20px;
}
.cu-pri-low    { background: #d1fae5; color: #065f46; }
.cu-pri-medium { background: #fef3c7; color: #92400e; }
.cu-pri-high   { background: #fee2e2; color: #991b1b; }
.cu-pri-urgent { background: #dc2626; color: #fff; }

/* Sections */
.cu-sections { display: flex; flex-direction: column; gap: 14px; }
.cu-section { background: white; border: 1px solid #e3e4e8; border-radius: 8px; overflow: hidden; }
.cu-section-header {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 16px; background: #fafbfc; border-bottom: 1px solid #e3e4e8;
}
.cu-section-icon {
    width: 24px; height: 24px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0;
}
.cu-section-icon.amber  { background: #fef3c7; color: #d97706; }
.cu-section-icon.blue   { background: #dbeafe; color: #2563eb; }
.cu-section-icon.red    { background: #fee2e2; color: #dc2626; }
.cu-section-icon.green  { background: #dcfce7; color: #16a34a; }
.cu-section-icon.violet { background: #ede9fe; color: #7c3aed; }
.cu-section-title { font-size: 13px; font-weight: 700; color: #1a1d23; margin: 0; }
.cu-section-sub   { font-size: 11px; color: #8a8f98; margin: 0 0 0 auto; }
.cu-section-body  { padding: 16px; }

/* Fields */
.cu-field { margin-bottom: 14px; }
.cu-field:last-child { margin-bottom: 0; }
.cu-field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
@media(max-width:500px) { .cu-field-row { grid-template-columns: 1fr; } }
.cu-label { display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 4px; }
.cu-input, .cu-select, .cu-textarea {
    width: 100%; border: 1px solid #d3d5db; border-radius: 6px;
    padding: 7px 10px; font-size: 13px; color: #111827; background: white;
    transition: border-color .15s, box-shadow .15s;
}
.cu-input:focus, .cu-select:focus, .cu-textarea:focus {
    outline: none; border-color: #f59e0b;
    box-shadow: 0 0 0 2px rgba(245,158,11,.18);
}
.cu-input.is-invalid, .cu-select.is-invalid, .cu-textarea.is-invalid { border-color: #dc2626; }
.cu-textarea { resize: vertical; min-height: 80px; }
.cu-err  { font-size: 11px; color: #dc2626; margin-top: 3px; }
.cu-hint { font-size: 11px; color: #9ca3af; margin-top: 3px; }

/* Priority chips */
.cu-pri-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
@media(max-width:500px) { .cu-pri-grid { grid-template-columns: repeat(2, 1fr); } }
.cu-pri-chip input[type="radio"] { display: none; }
.cu-pri-chip label {
    display: block; text-align: center; padding: 7px 4px;
    border: 2px solid #e5e7eb; border-radius: 7px;
    font-size: 12px; font-weight: 700; cursor: pointer; transition: all .15s;
}
.cu-pri-chip input:checked + label { color: #fff !important; }
.cu-pri-chip.low    label { border-color: #10b981; color: #065f46; }
.cu-pri-chip.low    input:checked + label { background: #10b981; border-color: #10b981; }
.cu-pri-chip.medium label { border-color: #f59e0b; color: #92400e; }
.cu-pri-chip.medium input:checked + label { background: #f59e0b; border-color: #f59e0b; }
.cu-pri-chip.high   label { border-color: #ef4444; color: #991b1b; }
.cu-pri-chip.high   input:checked + label { background: #ef4444; border-color: #ef4444; }
.cu-pri-chip.urgent label { border-color: #dc2626; color: #7f1d1d; background: #fee2e2; }
.cu-pri-chip.urgent input:checked + label { background: #dc2626; border-color: #dc2626; }

/* Recurring toggle */
.cu-recur-toggle {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 10px; background: #f9fafb; border-radius: 7px;
    border: 1px solid #e5e7eb; cursor: pointer;
}
.cu-recur-toggle input { display: none; }
.cu-toggle-track {
    width: 34px; height: 18px; border-radius: 9px;
    background: #d1d5db; position: relative; transition: background .2s; flex-shrink: 0;
}
.cu-toggle-thumb {
    position: absolute; top: 2px; left: 2px;
    width: 14px; height: 14px; border-radius: 50%;
    background: #fff; transition: left .2s;
    box-shadow: 0 1px 3px rgba(0,0,0,.2);
}
.cu-recur-toggle input:checked ~ .cu-toggle-track { background: #f59e0b; }
.cu-recur-toggle input:checked ~ .cu-toggle-track .cu-toggle-thumb { left: 18px; }
.cu-toggle-lbl { font-size: 13px; font-weight: 600; color: #374151; }
.cu-recur-opts { display: none; margin-top: 10px; }
.cu-recur-opts.open { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

/* Tag input */
.cu-tags-box {
    display: flex; flex-wrap: wrap; align-items: center; gap: 5px;
    border: 1px solid #d3d5db; border-radius: 6px;
    padding: 5px 8px; background: white; cursor: text;
    transition: border-color .15s, box-shadow .15s; min-height: 36px;
}
.cu-tags-box:focus-within {
    border-color: #f59e0b; box-shadow: 0 0 0 2px rgba(245,158,11,.18);
}
.cu-tag-pill {
    display: inline-flex; align-items: center; gap: 4px;
    background: #fef3c7; color: #92400e;
    font-size: 11px; font-weight: 600;
    padding: 2px 8px; border-radius: 20px;
}
.cu-tag-pill button {
    border: none; background: none; padding: 0; cursor: pointer;
    font-size: 11px; color: #b45309; line-height: 1;
}
.cu-tag-input {
    border: none; outline: none; font-size: 12px;
    min-width: 120px; flex: 1; padding: 1px 2px; background: transparent;
}

/* Action bar */
.cu-action-bar {
    background: white; border: 1px solid #e3e4e8; border-radius: 8px;
    padding: 12px 16px; display: flex; justify-content: flex-end; gap: 8px;
}
.cu-btn-cancel {
    padding: 6px 14px; border: 1.5px solid #d3d5db; border-radius: 6px;
    background: white; font-size: 13px; font-weight: 600; color: #6b7280;
    text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
    transition: border-color .15s, color .15s;
}
.cu-btn-cancel:hover { border-color: #adb0b8; color: #1a1d23; }
.cu-btn-save {
    padding: 6px 18px; background: #f59e0b; border: 1px solid #f59e0b;
    color: white; border-radius: 6px; font-size: 13px; font-weight: 600;
    cursor: pointer; transition: all .15s; display: inline-flex; align-items: center; gap: 5px;
}
.cu-btn-save:hover { background: #d97706; border-color: #d97706; box-shadow: 0 2px 6px rgba(245,158,11,.4); }

/* Danger zone */
.cu-danger-zone {
    background: white; border: 1px solid #fca5a5; border-radius: 8px; overflow: hidden;
}
.cu-danger-zone .cu-section-header { background: #fff5f5; border-bottom: 1px solid #fca5a5; }
.cu-danger-zone .cu-section-body   { padding: 14px 16px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.cu-danger-desc { font-size: 12px; color: #6b7280; }
.cu-btn-delete {
    padding: 6px 14px; background: #fee2e2; border: 1px solid #fca5a5;
    color: #dc2626; border-radius: 6px; font-size: 13px; font-weight: 600;
    cursor: pointer; display: inline-flex; align-items: center; gap: 5px;
    transition: background .15s, border-color .15s;
}
.cu-btn-delete:hover { background: #dc2626; border-color: #dc2626; color: #fff; }
</style>
@endpush

@section('content')
<div class="main-content">

    {{-- Top header bar --}}
    <div class="cu-header">
        <div class="d-flex align-items-center" style="position:relative;z-index:1;">
            <a href="{{ route('reminders.show', $reminder) }}" class="me-3 text-decoration-none">
                <i class="bi bi-arrow-left fs-5" style="color:rgba(255,255,255,.8);"></i>
            </a>
            <div>
                <h1 class="cu-header-title">Edit Reminder</h1>
                <p class="cu-header-sub">{{ Str::limit($reminder->title, 60) }}</p>
            </div>
        </div>
    </div>

    <div class="cu-layout">

        {{-- Left info panel --}}
        <div class="cu-info-panel">
            <div class="cu-info-panel-header"><span>Reminder Info</span></div>
            <div class="cu-info-body">
                <div class="cu-avatar"><i class="bi bi-bell-fill"></i></div>
                <div class="cu-panel-name">{{ Str::limit($reminder->title, 36) }}</div>
                <div class="cu-panel-sub">Last edited {{ $reminder->updated_at->diffForHumans() }}</div>

                <div class="cu-pri-row">
                    <span class="cu-pri-badge cu-pri-{{ $reminder->priority }}">
                        {{ ucfirst($reminder->priority) }} Priority
                    </span>
                </div>

                <div class="cu-meta-row">
                    <i class="bi bi-person"></i>
                    <span>{{ auth()->user()->name }}</span>
                </div>
                <div class="cu-meta-row">
                    <i class="bi bi-calendar3"></i>
                    <span>Created <strong>{{ $reminder->created_at->format('M d, Y') }}</strong></span>
                </div>
                @if($reminder->formatted_date_time)
                <div class="cu-meta-row">
                    <i class="bi bi-bell"></i>
                    <span>Due <strong>{{ $reminder->formatted_date_time->format('M j, Y') }}</strong></span>
                </div>
                @endif
                @if($reminder->category)
                <div class="cu-meta-row">
                    <i class="bi bi-folder2"></i>
                    <span>{{ $reminder->category }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('reminders.update', $reminder) }}" method="POST" id="rem-edit-form">
            @csrf
            @method('PUT')
            <div class="cu-sections">

                {{-- Basic Info --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon amber"><i class="bi bi-card-text"></i></span>
                        <span class="cu-section-title">Basic Info</span>
                    </div>
                    <div class="cu-section-body">
                        <div class="cu-field">
                            <label for="title" class="cu-label">Title <span style="color:#dc2626;">*</span></label>
                            <input type="text" id="title" name="title"
                                   class="cu-input @error('title') is-invalid @enderror"
                                   value="{{ old('title', $reminder->title) }}"
                                   required autofocus>
                            @error('title')<p class="cu-err">{{ $message }}</p>@enderror
                        </div>
                        <div class="cu-field">
                            <label for="description" class="cu-label">Description</label>
                            <textarea id="description" name="description" rows="3"
                                      class="cu-textarea @error('description') is-invalid @enderror">{{ old('description', $reminder->description) }}</textarea>
                            @error('description')<p class="cu-err">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Date & Time --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon blue"><i class="bi bi-calendar-event"></i></span>
                        <span class="cu-section-title">Date &amp; Time</span>
                        <span class="cu-section-sub">Optional</span>
                    </div>
                    <div class="cu-section-body">
                        <div class="cu-field-row">
                            <div class="cu-field" style="margin-bottom:0;">
                                <label for="date" class="cu-label">Date</label>
                                <input type="date" id="date" name="date"
                                       class="cu-input @error('date') is-invalid @enderror"
                                       value="{{ old('date', $reminder->date?->format('Y-m-d')) }}">
                                @error('date')<p class="cu-err">{{ $message }}</p>@enderror
                            </div>
                            <div class="cu-field" style="margin-bottom:0;">
                                <label for="time" class="cu-label">Time</label>
                                <input type="time" id="time" name="time"
                                       class="cu-input @error('time') is-invalid @enderror"
                                       value="{{ old('time', $reminder->time) }}">
                                @error('time')<p class="cu-err">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Priority --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon red"><i class="bi bi-flag-fill"></i></span>
                        <span class="cu-section-title">Priority</span>
                        <span class="cu-section-sub">Required</span>
                    </div>
                    <div class="cu-section-body">
                        <div class="cu-pri-grid">
                            @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent'] as $key => $label)
                            <div class="cu-pri-chip {{ $key }}">
                                <input type="radio" id="pri_{{ $key }}" name="priority"
                                       value="{{ $key }}"
                                       {{ old('priority', $reminder->priority) === $key ? 'checked' : '' }}
                                       required>
                                <label for="pri_{{ $key }}">{{ $label }}</label>
                            </div>
                            @endforeach
                        </div>
                        @error('priority')<p class="cu-err">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Details --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon green"><i class="bi bi-info-circle"></i></span>
                        <span class="cu-section-title">Details</span>
                        <span class="cu-section-sub">Category, location &amp; tags</span>
                    </div>
                    <div class="cu-section-body">
                        <div class="cu-field-row">
                            <div class="cu-field" style="margin-bottom:12px;">
                                <label for="category" class="cu-label">Category</label>
                                <input type="text" id="category" name="category" list="cat-list"
                                       class="cu-input @error('category') is-invalid @enderror"
                                       value="{{ old('category', $reminder->category) }}"
                                       placeholder="Work, Personal&hellip;">
                                <datalist id="cat-list">
                                    @foreach(auth()->user()->reminders()->whereNotNull('category')->where('category','!=','')->distinct()->pluck('category') as $cat)
                                        <option value="{{ $cat }}">
                                    @endforeach
                                </datalist>
                                @error('category')<p class="cu-err">{{ $message }}</p>@enderror
                            </div>
                            <div class="cu-field" style="margin-bottom:12px;">
                                <label for="location" class="cu-label">Location</label>
                                <input type="text" id="location" name="location"
                                       class="cu-input @error('location') is-invalid @enderror"
                                       value="{{ old('location', $reminder->location) }}"
                                       placeholder="Meeting room, address&hellip;">
                                @error('location')<p class="cu-err">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="cu-field">
                            <label class="cu-label">Tags</label>
                            <div class="cu-tags-box" id="tags-box">
                                <input type="text" class="cu-tag-input" id="tag-input"
                                       placeholder="Type and press Enter to add tag">
                            </div>
                            <input type="hidden" name="tags" id="tags-hidden"
                                   value="{{ old('tags', is_array($reminder->tags) ? implode(',', $reminder->tags) : $reminder->tags) }}">
                            <p class="cu-hint">Press Enter or , to add a tag</p>
                            @error('tags')<p class="cu-err">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Recurring --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon violet"><i class="bi bi-arrow-repeat"></i></span>
                        <span class="cu-section-title">Recurring</span>
                        <span class="cu-section-sub">Optional</span>
                    </div>
                    <div class="cu-section-body">
                        <label class="cu-recur-toggle" for="is_recurring">
                            <input type="checkbox" id="is_recurring" name="is_recurring" value="1"
                                   {{ old('is_recurring', $reminder->is_recurring) ? 'checked' : '' }}>
                            <div class="cu-toggle-track">
                                <div class="cu-toggle-thumb"></div>
                            </div>
                            <span class="cu-toggle-lbl">Make this reminder recurring</span>
                        </label>

                        <div class="cu-recur-opts {{ old('is_recurring', $reminder->is_recurring) ? 'open' : '' }}" id="recur-opts">
                            <div class="cu-field" style="margin-bottom:0;">
                                <label for="recurrence_type" class="cu-label">Repeat Every</label>
                                <select id="recurrence_type" name="recurrence_type"
                                        class="cu-select @error('recurrence_type') is-invalid @enderror">
                                    <option value="daily"   {{ old('recurrence_type', $reminder->recurrence_type) === 'daily'   ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly"  {{ old('recurrence_type', $reminder->recurrence_type) === 'weekly'  ? 'selected' : '' }}>Weekly</option>
                                    <option value="monthly" {{ old('recurrence_type', $reminder->recurrence_type) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="yearly"  {{ old('recurrence_type', $reminder->recurrence_type) === 'yearly'  ? 'selected' : '' }}>Yearly</option>
                                </select>
                                @error('recurrence_type')<p class="cu-err">{{ $message }}</p>@enderror
                            </div>
                            <div class="cu-field" style="margin-bottom:0;">
                                <label for="recurrence_interval" class="cu-label">Interval</label>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <input type="number" id="recurrence_interval" name="recurrence_interval"
                                           class="cu-input @error('recurrence_interval') is-invalid @enderror"
                                           value="{{ old('recurrence_interval', $reminder->recurrence_interval ?: 1) }}"
                                           min="1" max="365" style="max-width:80px;">
                                    <span id="interval-unit" style="font-size:12px;color:#6b7280;">day(s)</span>
                                </div>
                                @error('recurrence_interval')<p class="cu-err">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action bar --}}
                <div class="cu-action-bar">
                    <a href="{{ route('reminders.show', $reminder) }}" class="cu-btn-cancel">
                        <i class="bi bi-x-lg"></i> Cancel
                    </a>
                    <button type="submit" class="cu-btn-save">
                        <i class="bi bi-check-lg"></i> Update Reminder
                    </button>
                </div>

                {{-- Danger zone --}}
                <div class="cu-danger-zone cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon red"><i class="bi bi-exclamation-triangle"></i></span>
                        <span class="cu-section-title" style="color:#dc2626;">Danger Zone</span>
                    </div>
                    <div class="cu-section-body">
                        <p class="cu-danger-desc">Permanently delete this reminder. This action cannot be undone.</p>
                        <form action="{{ route('reminders.destroy', $reminder) }}" method="POST"
                              onsubmit="return confirm('Delete this reminder permanently?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cu-btn-delete">
                                <i class="bi bi-trash"></i> Delete Reminder
                            </button>
                        </form>
                    </div>
                </div>

            </div>{{-- /cu-sections --}}
        </form>

    </div>{{-- /cu-layout --}}
</div>{{-- /main-content --}}
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* Recurring toggle */
    var recurCheck = document.getElementById('is_recurring');
    var recurOpts  = document.getElementById('recur-opts');
    var recurType  = document.getElementById('recurrence_type');
    var intUnit    = document.getElementById('interval-unit');

    function updateUnit() {
        var map = { daily: 'day(s)', weekly: 'week(s)', monthly: 'month(s)', yearly: 'year(s)' };
        intUnit.textContent = map[recurType.value] || 'day(s)';
    }

    recurCheck.addEventListener('change', function () {
        recurOpts.classList.toggle('open', this.checked);
    });
    recurType.addEventListener('change', updateUnit);
    updateUnit();

    /* Tag pills — pre-populate from hidden input */
    var tagsBox    = document.getElementById('tags-box');
    var tagInput   = document.getElementById('tag-input');
    var tagsHidden = document.getElementById('tags-hidden');
    var tags = [];

    var existing = tagsHidden.value.trim();
    if (existing) existing.split(',').map(function(t){ return t.trim(); }).filter(Boolean).forEach(addTag);

    function render() {
        tagsBox.querySelectorAll('.cu-tag-pill').forEach(function(p){ p.remove(); });
        tags.forEach(function(t, i) {
            var pill = document.createElement('span');
            pill.className = 'cu-tag-pill';
            pill.innerHTML = t + '<button type="button" onclick="removeTag(' + i + ')" title="Remove">&times;</button>';
            tagsBox.insertBefore(pill, tagInput);
        });
        tagsHidden.value = tags.join(',');
    }

    window.removeTag = function(i) { tags.splice(i, 1); render(); };

    function addTag(val) {
        val = val.trim().replace(/,+$/, '');
        if (val && !tags.includes(val)) { tags.push(val); render(); }
    }

    tagInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            addTag(this.value);
            this.value = '';
        }
    });
    tagInput.addEventListener('blur', function() {
        if (this.value.trim()) { addTag(this.value); this.value = ''; }
    });
    tagsBox.addEventListener('click', function() { tagInput.focus(); });
});
</script>
@endpush
