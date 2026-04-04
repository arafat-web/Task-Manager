@extends('layouts.app')

@section('title', $reminder->title)

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
.cu-layout { display: grid; grid-template-columns: 240px 1fr; gap: 14px; align-items: start; }
@media(max-width:768px) { .cu-layout { grid-template-columns: 1fr; } }

/* Left info panel */
.cu-info-panel {
    background: white; border: 1px solid #e3e4e8; border-radius: 8px;
    overflow: hidden; position: sticky; top: 1rem;
}
.cu-info-panel-header { background: #f7f8fa; border-bottom: 1px solid #e3e4e8; padding: 10px 14px; }
.cu-info-panel-header span { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: #8a8f98; }
.cu-info-body { padding: 14px; }
.cu-avatar {
    width: 48px; height: 48px; border-radius: 10px; background: #f59e0b;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; color: #fff; margin: 0 auto 10px;
}
.cu-panel-name { text-align: center; font-size: 13px; font-weight: 700; color: #1a1d23; margin-bottom: 4px; line-height: 1.35; }
.cu-panel-sub  { text-align: center; font-size: 11px; color: #adb0b8; margin-bottom: 12px; }

/* Priority badge in panel */
.cu-pri-inline {
    display: flex; justify-content: center; margin-bottom: 10px;
}
.cu-pri-badge {
    font-size: 11px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .05em; padding: 3px 10px; border-radius: 20px;
}
.cu-pri-low    { background: #d1fae5; color: #065f46; }
.cu-pri-medium { background: #fef3c7; color: #92400e; }
.cu-pri-high   { background: #fee2e2; color: #991b1b; }
.cu-pri-urgent { background: #dc2626; color: #fff; }

/* Status badge */
.cu-status-row { display: flex; justify-content: center; margin-bottom: 12px; }
.cu-status-badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px;
}
.cu-sb-success { background: #d1fae5; color: #065f46; }
.cu-sb-danger  { background: #fee2e2; color: #dc2626; }
.cu-sb-warn    { background: #fef3c7; color: #d97706; }
.cu-sb-info    { background: #dbeafe; color: #1d4ed8; }

/* Meta rows */
.cu-meta-row {
    display: flex; align-items: flex-start; gap: 8px;
    font-size: 12px; color: #6b7280; padding: 6px 0;
    border-top: 1px solid #f3f4f6;
}
.cu-meta-row i { font-size: 13px; color: #adb0b8; flex-shrink: 0; margin-top: 1px; }
.cu-meta-row strong { color: #1a1d23; font-weight: 600; }
.cu-meta-row .cu-meta-danger { color: #dc2626; font-weight: 600; }
.cu-meta-row .cu-meta-warn   { color: #d97706; font-weight: 600; }

/* Action buttons in panel */
.cu-panel-actions { padding: 12px 14px; border-top: 1px solid #f3f4f6; display: flex; flex-direction: column; gap: 6px; }
.cu-pact {
    display: flex; align-items: center; gap: 7px;
    padding: 7px 10px; border-radius: 7px; font-size: 12px; font-weight: 600;
    border: none; cursor: pointer; text-decoration: none; transition: opacity .15s, transform .1s;
    width: 100%;
}
.cu-pact:hover { opacity: .82; transform: translateY(-1px); }
.cu-pact-success { background: #d1fae5; color: #065f46; }
.cu-pact-muted   { background: #f3f4f6; color: #6b7280; }
.cu-pact-warn    { background: #fef3c7; color: #92400e; }
.cu-pact-primary { background: #fef3c7; color: #b45309; }
.cu-pact-danger  { background: #fee2e2; color: #dc2626; }
.cu-pact-info    { background: #dbeafe; color: #1d4ed8; }

/* Right sections */
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
.cu-section-icon.violet { background: #ede9fe; color: #7c3aed; }
.cu-section-icon.green  { background: #dcfce7; color: #16a34a; }
.cu-section-icon.blue   { background: #dbeafe; color: #2563eb; }
.cu-section-title { font-size: 13px; font-weight: 700; color: #1a1d23; margin: 0; }
.cu-section-sub   { font-size: 11px; color: #8a8f98; margin: 0 0 0 auto; }
.cu-section-body  { padding: 16px; }

/* Content text */
.cu-content-text {
    font-size: 14px; line-height: 1.8; color: #374151;
    white-space: pre-wrap; word-break: break-word;
}

/* Tag pills */
.cu-tags-row { display: flex; flex-wrap: wrap; gap: 6px; }
.cu-tag-pill {
    font-size: 11px; font-weight: 600; padding: 3px 10px;
    border-radius: 20px; background: #fef3c7; color: #92400e;
}

/* Recurrence box */
.cu-recur-box {
    background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 7px;
    padding: 12px 14px; font-size: 13px; color: #374151;
    display: flex; align-items: center; gap: 8px;
}
.cu-recur-box i { color: #7c3aed; font-size: 15px; }
</style>
@endpush

@section('content')
<div class="main-content">

    {{-- Top header bar --}}
    <div class="cu-header">
        <div class="d-flex align-items-center" style="position:relative;z-index:1;">
            <a href="{{ route('reminders.index') }}" class="me-3 text-decoration-none">
                <i class="bi bi-arrow-left fs-5" style="color:rgba(255,255,255,.8);"></i>
            </a>
            <div>
                <h1 class="cu-header-title">{{ Str::limit($reminder->title, 60) }}</h1>
                <p class="cu-header-sub">
                    @if($reminder->is_completed)
                        Completed reminder
                    @elseif($reminder->is_overdue)
                        Overdue &mdash; {{ $reminder->formatted_date_time?->diffForHumans() }}
                    @elseif($reminder->formatted_date_time)
                        Due {{ $reminder->formatted_date_time->diffForHumans() }}
                    @else
                        No due date set
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="cu-layout">

        {{-- ── Left panel ── --}}
        <div class="cu-info-panel">
            <div class="cu-info-panel-header"><span>Reminder Info</span></div>
            <div class="cu-info-body">
                <div class="cu-avatar"><i class="bi bi-bell-fill"></i></div>
                <div class="cu-panel-name">{{ Str::limit($reminder->title, 40) }}</div>
                <div class="cu-panel-sub">Created {{ $reminder->created_at->diffForHumans() }}</div>

                {{-- Priority --}}
                <div class="cu-pri-inline">
                    <span class="cu-pri-badge cu-pri-{{ $reminder->priority }}">
                        {{ ucfirst($reminder->priority) }} Priority
                    </span>
                </div>

                {{-- Status --}}
                <div class="cu-status-row">
                    @if($reminder->is_completed)
                        <span class="cu-status-badge cu-sb-success">
                            <i class="bi bi-check-circle-fill"></i> Completed
                        </span>
                    @elseif($reminder->is_overdue)
                        <span class="cu-status-badge cu-sb-danger">
                            <i class="bi bi-exclamation-circle-fill"></i> Overdue
                        </span>
                    @elseif($reminder->is_due_soon)
                        <span class="cu-status-badge cu-sb-warn">
                            <i class="bi bi-clock-fill"></i> Due Soon
                        </span>
                    @else
                        <span class="cu-status-badge cu-sb-info">
                            <i class="bi bi-bell"></i> Active
                        </span>
                    @endif
                </div>

                {{-- Meta --}}
                @if($reminder->formatted_date_time)
                    <div class="cu-meta-row">
                        <i class="bi bi-calendar-event"></i>
                        <div>
                            <strong>{{ $reminder->formatted_date_time->format('M j, Y') }}</strong>
                            @if($reminder->time)
                                <br><span style="font-size:11px;">at {{ $reminder->formatted_time }}</span>
                            @endif
                            <br>
                            <span class="{{ $reminder->is_overdue ? 'cu-meta-danger' : '' }}" style="font-size:11px;">
                                {{ $reminder->formatted_date_time->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                @endif

                @if($reminder->category)
                    <div class="cu-meta-row">
                        <i class="bi bi-folder2"></i>
                        <span>{{ $reminder->category }}</span>
                    </div>
                @endif

                @if($reminder->location)
                    <div class="cu-meta-row">
                        <i class="bi bi-geo-alt"></i>
                        <span>{{ $reminder->location }}</span>
                    </div>
                @endif

                @if($reminder->is_recurring)
                    <div class="cu-meta-row">
                        <i class="bi bi-arrow-repeat"></i>
                        <span>Every {{ $reminder->recurrence_interval }}
                              {{ $reminder->recurrence_type }}{{ $reminder->recurrence_interval > 1 ? 's' : '' }}</span>
                    </div>
                @endif

                @if($reminder->snooze_until && $reminder->snooze_until->isFuture())
                    <div class="cu-meta-row">
                        <i class="bi bi-clock-history"></i>
                        <span class="cu-meta-warn">
                            Snoozed until {{ $reminder->snooze_until->format('M j, g:i A') }}
                        </span>
                    </div>
                @endif

                @if($reminder->is_completed && $reminder->completed_at)
                    <div class="cu-meta-row">
                        <i class="bi bi-check2-circle"></i>
                        <span style="color:#065f46;">
                            Done {{ $reminder->completed_at->diffForHumans() }}
                        </span>
                    </div>
                @endif

                <div class="cu-meta-row">
                    <i class="bi bi-person"></i>
                    <span>{{ $reminder->user->name }}</span>
                </div>

                <div class="cu-meta-row">
                    <i class="bi bi-clock"></i>
                    <span>Updated {{ $reminder->updated_at->diffForHumans() }}</span>
                </div>
            </div>

            {{-- Action buttons --}}
            <div class="cu-panel-actions">
                @if(!$reminder->is_completed)
                    <button onclick="toggleComplete({{ $reminder->id }})" class="cu-pact cu-pact-success">
                        <i class="bi bi-check-lg"></i> Mark Complete
                    </button>
                @else
                    <button onclick="toggleComplete({{ $reminder->id }})" class="cu-pact cu-pact-muted">
                        <i class="bi bi-arrow-counterclockwise"></i> Reactivate
                    </button>
                @endif

                @if(!$reminder->is_completed && !($reminder->snooze_until && $reminder->snooze_until->isFuture()))
                    <button onclick="snoozePrompt({{ $reminder->id }})" class="cu-pact cu-pact-warn">
                        <i class="bi bi-clock"></i> Snooze
                    </button>
                @endif

                <a href="{{ route('reminders.edit', $reminder) }}" class="cu-pact cu-pact-primary">
                    <i class="bi bi-pencil"></i> Edit
                </a>

                <form action="{{ route('reminders.duplicate', $reminder) }}" method="POST"
                      onsubmit="return confirm('Duplicate this reminder?')">
                    @csrf
                    <button type="submit" class="cu-pact cu-pact-info">
                        <i class="bi bi-copy"></i> Duplicate
                    </button>
                </form>

                <form action="{{ route('reminders.destroy', $reminder) }}" method="POST"
                      onsubmit="return confirm('Delete this reminder? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="cu-pact cu-pact-danger">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        {{-- ── Right sections ── --}}
        <div class="cu-sections">

            {{-- Description --}}
            @if($reminder->description)
            <div class="cu-section">
                <div class="cu-section-header">
                    <span class="cu-section-icon amber"><i class="bi bi-card-text"></i></span>
                    <span class="cu-section-title">Description</span>
                </div>
                <div class="cu-section-body">
                    <p class="cu-content-text">{{ $reminder->description }}</p>
                </div>
            </div>
            @endif

            {{-- Tags --}}
            @if($reminder->tags && count($reminder->tags) > 0)
            <div class="cu-section">
                <div class="cu-section-header">
                    <span class="cu-section-icon violet"><i class="bi bi-tags"></i></span>
                    <span class="cu-section-title">Tags</span>
                    <span class="cu-section-sub">{{ count($reminder->tags) }} tag{{ count($reminder->tags) !== 1 ? 's' : '' }}</span>
                </div>
                <div class="cu-section-body">
                    <div class="cu-tags-row">
                        @foreach($reminder->tags as $tag)
                            <span class="cu-tag-pill">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Recurrence info --}}
            @if($reminder->is_recurring)
            <div class="cu-section">
                <div class="cu-section-header">
                    <span class="cu-section-icon green"><i class="bi bi-arrow-repeat"></i></span>
                    <span class="cu-section-title">Recurring Schedule</span>
                </div>
                <div class="cu-section-body">
                    <div class="cu-recur-box">
                        <i class="bi bi-arrow-repeat"></i>
                        <span>
                            Repeats every
                            <strong>{{ $reminder->recurrence_interval }}
                            {{ $reminder->recurrence_type }}{{ $reminder->recurrence_interval > 1 ? 's' : '' }}</strong>
                            @if($reminder->recurrence_interval > 1 || $reminder->recurrence_type !== 'daily')
                                — automatically creates the next occurrence when completed
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            @endif

            {{-- If nothing else to show, show a placeholder --}}
            @if(!$reminder->description && !($reminder->tags && count($reminder->tags) > 0) && !$reminder->is_recurring)
            <div class="cu-section">
                <div class="cu-section-header">
                    <span class="cu-section-icon blue"><i class="bi bi-bell"></i></span>
                    <span class="cu-section-title">Reminder</span>
                </div>
                <div class="cu-section-body" style="text-align:center;padding:2rem;color:#adb0b8;">
                    <i class="bi bi-bell" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i>
                    <p style="margin:0;font-size:13px;">No additional details added for this reminder.</p>
                </div>
            </div>
            @endif

        </div>{{-- /cu-sections --}}

    </div>{{-- /cu-layout --}}
</div>{{-- /main-content --}}
@endsection

@push('scripts')
<script>
function toggleComplete(reminderId) {
    fetch('/reminders/' + reminderId + '/toggle-complete', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        if (data.success) { location.reload(); }
    })
    .catch(function (err) { console.error('Toggle failed:', err); });
}

function snoozePrompt(reminderId) {
    var minutes = prompt('Snooze for how many minutes?', '15');
    if (!minutes || isNaN(minutes) || parseInt(minutes) <= 0) return;
    fetch('/reminders/' + reminderId + '/snooze', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ minutes: parseInt(minutes) })
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        if (data.success) { location.reload(); }
    })
    .catch(function (err) { console.error('Snooze failed:', err); });
}
</script>
@endpush
