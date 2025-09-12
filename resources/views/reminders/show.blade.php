@extends('layouts.app')

@section('title', $reminder->title)

@section('content')
<style>
    :root {
        --reminder-primary: #667eea;
        --reminder-secondary: #764ba2;
        --reminder-success: #10b981;
        --reminder-warning: #f59e0b;
        --reminder-danger: #ef4444;
        --reminder-info: #3b82f6;
        --reminder-light: #f8fafc;
        --reminder-dark: #1e293b;
        --reminder-gray: #64748b;
        --reminder-border: #e2e8f0;
        --reminder-shadow: rgba(0, 0, 0, 0.1);
        --reminder-shadow-lg: rgba(0, 0, 0, 0.15);
    }

    .reminder-header {
        background: linear-gradient(135deg, var(--reminder-primary) 0%, var(--reminder-secondary) 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px var(--reminder-shadow-lg);
    }

    .reminder-content-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--reminder-border);
        box-shadow: 0 4px 6px -1px var(--reminder-shadow);
        overflow: hidden;
    }

    .reminder-content-body {
        padding: 2rem;
        line-height: 1.8;
        color: var(--reminder-dark);
    }

    .reminder-meta-sidebar {
        background: var(--reminder-light);
        padding: 1.5rem;
        border-left: 1px solid var(--reminder-border);
    }

    .breadcrumb-modern {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px var(--reminder-shadow);
        border: 1px solid var(--reminder-border);
    }

    .breadcrumb-modern .breadcrumb {
        margin: 0;
        background: none;
        padding: 0;
    }

    .breadcrumb-modern .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: var(--reminder-gray);
        font-weight: 600;
    }

    .meta-item {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--reminder-border);
    }

    .meta-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .meta-icon {
        width: 40px;
        height: 40px;
        background: var(--reminder-primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .meta-content h6 {
        margin: 0;
        font-weight: 600;
        color: var(--reminder-dark);
    }

    .meta-content p {
        margin: 0;
        color: var(--reminder-gray);
        font-size: 0.9rem;
    }

    .priority-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .priority-badge.urgent {
        background: #fef2f2;
        color: var(--reminder-danger);
        border: 1px solid var(--reminder-danger);
    }

    .priority-badge.high {
        background: #fff7ed;
        color: #ea580c;
        border: 1px solid #ea580c;
    }

    .priority-badge.medium {
        background: #fffbeb;
        color: var(--reminder-warning);
        border: 1px solid var(--reminder-warning);
    }

    .priority-badge.low {
        background: var(--reminder-light);
        color: var(--reminder-gray);
        border: 1px solid var(--reminder-gray);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .status-badge.completed {
        background: #f0fdf4;
        color: var(--reminder-success);
        border: 1px solid var(--reminder-success);
    }

    .status-badge.active {
        background: #eff6ff;
        color: var(--reminder-info);
        border: 1px solid var(--reminder-info);
    }

    .status-badge.overdue {
        background: #fef2f2;
        color: var(--reminder-danger);
        border: 1px solid var(--reminder-danger);
    }

    .tag {
        display: inline-block;
        background: var(--reminder-light);
        color: var(--reminder-gray);
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        margin: 0.125rem;
        border: 1px solid var(--reminder-border);
    }

    .action-buttons {
        background: var(--reminder-light);
        padding: 1.5rem;
        border-top: 1px solid var(--reminder-border);
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn-modern {
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .btn-modern.btn-primary {
        background: var(--reminder-primary);
        color: white;
    }

    .btn-modern.btn-primary:hover {
        background: var(--reminder-secondary);
        transform: translateY(-1px);
        color: white;
    }

    .btn-modern.btn-success {
        background: var(--reminder-success);
        color: white;
    }

    .btn-modern.btn-success:hover {
        background: #059669;
        transform: translateY(-1px);
        color: white;
    }

    .btn-modern.btn-warning {
        background: var(--reminder-warning);
        color: white;
    }

    .btn-modern.btn-warning:hover {
        background: #d97706;
        transform: translateY(-1px);
        color: white;
    }

    .btn-modern.btn-info {
        background: var(--reminder-info);
        color: white;
    }

    .btn-modern.btn-info:hover {
        background: #2563eb;
        transform: translateY(-1px);
        color: white;
    }

    .btn-modern.btn-danger {
        background: var(--reminder-danger);
        color: white;
    }

    .btn-modern.btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
        color: white;
    }

    .btn-modern.btn-secondary {
        background: var(--reminder-gray);
        color: white;
    }

    .btn-modern.btn-secondary:hover {
        background: var(--reminder-dark);
        transform: translateY(-1px);
        color: white;
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="reminder-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-2">
                    <i class="fas fa-bell me-3"></i>{{ $reminder->title }}
                </h1>
                <div class="d-flex gap-2 mb-2">
                    <span class="priority-badge {{ $reminder->priority }}">
                        <i class="fas fa-{{ $reminder->priority === 'urgent' ? 'exclamation-triangle' : ($reminder->priority === 'high' ? 'exclamation' : ($reminder->priority === 'medium' ? 'minus' : 'chevron-down')) }}"></i>
                        {{ ucfirst($reminder->priority) }} Priority
                    </span>
                    @if($reminder->is_completed)
                        <span class="status-badge completed">
                            <i class="fas fa-check"></i>Completed
                        </span>
                    @elseif($reminder->is_overdue)
                        <span class="status-badge overdue">
                            <i class="fas fa-exclamation-triangle"></i>Overdue
                        </span>
                    @else
                        <span class="status-badge active">
                            <i class="fas fa-clock"></i>Active
                        </span>
                    @endif
                </div>
                @if($reminder->description)
                    <p class="mb-0 opacity-75">{{ Str::limit($reminder->description, 100) }}</p>
                @endif
            </div>
            <a href="{{ route('reminders.index') }}" class="btn btn-light btn-lg">
                <i class="fas fa-arrow-left me-2"></i>Back to Reminders
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="reminder-content-card">
                <div class="reminder-content-body">
                    @if($reminder->description)
                        <div class="mb-4">
                            <h5 class="mb-3" style="color: var(--reminder-primary);">
                                <i class="fas fa-align-left me-2"></i>Description
                            </h5>
                            <div style="line-height: 1.8;">
                                {{ $reminder->description }}
                            </div>
                        </div>
                    @endif

                    @if($reminder->tags && count($reminder->tags) > 0)
                        <div class="mb-4">
                            <h6 class="mb-3" style="color: var(--reminder-primary);">
                                <i class="fas fa-tags me-2"></i>Tags
                            </h6>
                            <div>
                                @foreach($reminder->tags as $tag)
                                    <span class="tag">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($reminder->is_recurring)
                        <div class="mb-4">
                            <h6 class="mb-3" style="color: var(--reminder-primary);">
                                <i class="fas fa-repeat me-2"></i>Recurring Information
                            </h6>
                            <div class="p-3 rounded" style="background: var(--reminder-light); border: 1px solid var(--reminder-border);">
                                <p class="mb-1">
                                    <strong>Type:</strong> Every {{ $reminder->recurrence_interval }} {{ $reminder->recurrence_type }}{{ $reminder->recurrence_interval > 1 ? 's' : '' }}
                                </p>
                                @if($reminder->recurrence_end_date)
                                    <p class="mb-0">
                                        <strong>Ends:</strong> {{ \Carbon\Carbon::parse($reminder->recurrence_end_date)->format('F j, Y') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="reminder-content-card">
                <div class="reminder-meta-sidebar">
                    <h6 class="mb-3" style="color: var(--reminder-dark); font-weight: 600;">Reminder Details</h6>

                    @if($reminder->formatted_date_time)
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="meta-content">
                                <h6>Date & Time</h6>
                                <p>
                                    <strong>{{ $reminder->formatted_date_time->format('l, F j, Y') }}</strong>
                                    @if($reminder->time)
                                        <br>at {{ $reminder->formatted_date_time->format('g:i A') }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="meta-content">
                                <h6>Time Until</h6>
                                <p class="{{ $reminder->is_overdue ? 'text-danger' : 'text-success' }}">
                                    {{ $reminder->formatted_date_time->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @endif

                    @if($reminder->category)
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-folder"></i>
                            </div>
                            <div class="meta-content">
                                <h6>Category</h6>
                                <p>{{ $reminder->category }}</p>
                            </div>
                        </div>
                    @endif

                    @if($reminder->location)
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="meta-content">
                                <h6>Location</h6>
                                <p>{{ $reminder->location }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="meta-content">
                            <h6>Created By</h6>
                            <p>{{ $reminder->user->name }}</p>
                        </div>
                    </div>

                    <div class="meta-item">
                        <div class="meta-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="meta-content">
                            <h6>Created</h6>
                            <p>{{ $reminder->created_at->format('F j, Y') }}</p>
                        </div>
                    </div>

                    @if($reminder->updated_at != $reminder->created_at)
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="meta-content">
                                <h6>Last Updated</h6>
                                <p>{{ $reminder->updated_at->format('F j, Y') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($reminder->snooze_until && $reminder->snooze_until->isFuture())
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-sleep"></i>
                            </div>
                            <div class="meta-content">
                                <h6>Snoozed Until</h6>
                                <p class="text-warning">{{ $reminder->snooze_until->format('F j, Y g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    @if(!$reminder->is_completed)
                        <button onclick="toggleComplete({{ $reminder->id }})" class="btn-modern btn-success">
                            <i class="fas fa-check"></i>Mark Complete
                        </button>
                    @else
                        <button onclick="toggleComplete({{ $reminder->id }})" class="btn-modern btn-secondary">
                            <i class="fas fa-undo"></i>Mark Incomplete
                        </button>
                    @endif

                    @if(!$reminder->is_completed && !($reminder->snooze_until && $reminder->snooze_until->isFuture()))
                        <button onclick="snoozeReminder({{ $reminder->id }})" class="btn-modern btn-warning">
                            <i class="fas fa-clock"></i>Snooze
                        </button>
                    @endif

                    <form action="{{ route('reminders.duplicate', $reminder) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn-modern btn-info" onclick="return confirm('Create a duplicate of this reminder?')">
                            <i class="fas fa-copy"></i>Duplicate
                        </button>
                    </form>

                    <a href="{{ route('reminders.edit', $reminder) }}" class="btn-modern btn-primary">
                        <i class="fas fa-edit"></i>Edit
                    </a>

                    <form action="{{ route('reminders.destroy', $reminder) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-modern btn-danger" onclick="return confirm('Are you sure you want to delete this reminder?')">
                            <i class="fas fa-trash"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Global functions for reminder actions
function toggleComplete(reminderId) {
    fetch(`/reminders/${reminderId}/toggle-complete`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error updating reminder. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error toggling reminder:', error);
        alert('Error updating reminder. Please try again.');
    });
}

function snoozeReminder(reminderId) {
    const minutes = prompt('Snooze for how many minutes?', '15');

    if (minutes && !isNaN(minutes) && parseInt(minutes) > 0) {
        fetch(`/reminders/${reminderId}/snooze`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                minutes: parseInt(minutes)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error snoozing reminder. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error snoozing reminder:', error);
            alert('Error snoozing reminder. Please try again.');
        });
    }
}
</script>
@endpush
