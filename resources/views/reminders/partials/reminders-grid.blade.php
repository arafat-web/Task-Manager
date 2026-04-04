<div class="cu-rem-grid">
    @forelse($reminders as $reminder)
        <div class="cu-rem-card {{ $reminder->is_completed ? 'cu-rem-done' : '' }} {{ $reminder->is_overdue ? 'cu-rem-late' : '' }}"
             style="border-left: 4px solid {{ $reminder->priority_color }};">

            {{-- Header --}}
            <div class="cu-rem-head">
                <span class="cu-rem-title">{{ $reminder->title }}</span>
                <span class="cu-pri-badge cu-pri-{{ $reminder->priority }}">
                    {{ ucfirst($reminder->priority) }}
                </span>
            </div>

            {{-- Description --}}
            @if($reminder->description)
                <p class="cu-rem-desc">{{ Str::limit($reminder->description, 110) }}</p>
            @endif

            {{-- Meta row --}}
            <div class="cu-rem-meta">
                @if($reminder->formatted_date_time)
                    <span class="cu-meta-item {{ $reminder->is_overdue ? 'cu-meta-danger' : '' }}">
                        <i class="bi bi-calendar-event"></i>
                        {{ $reminder->formatted_date_time->format('M j, Y') }}
                        @if($reminder->time) · {{ $reminder->formatted_time }}@endif
                    </span>
                @endif
                @if($reminder->category)
                    <span class="cu-meta-item">
                        <i class="bi bi-folder2"></i> {{ $reminder->category }}
                    </span>
                @endif
                @if($reminder->location)
                    <span class="cu-meta-item">
                        <i class="bi bi-geo-alt"></i> {{ Str::limit($reminder->location, 30) }}
                    </span>
                @endif
                @if($reminder->is_recurring)
                    <span class="cu-meta-item">
                        <i class="bi bi-arrow-repeat"></i> Repeats {{ $reminder->recurrence_type }}
                    </span>
                @endif
                @if($reminder->snooze_until && $reminder->snooze_until->isFuture())
                    <span class="cu-meta-item cu-meta-warn">
                        <i class="bi bi-clock-history"></i>
                        Snoozed until {{ $reminder->snooze_until->format('M j, g:i A') }}
                    </span>
                @endif
            </div>

            {{-- Tags --}}
            @if($reminder->tags && count($reminder->tags) > 0)
                <div class="cu-rem-tags">
                    @foreach(array_slice($reminder->tags, 0, 3) as $tag)
                        <span class="cu-tag-pill">{{ $tag }}</span>
                    @endforeach
                    @if(count($reminder->tags) > 3)
                        <span class="cu-tag-more">+{{ count($reminder->tags) - 3 }}</span>
                    @endif
                </div>
            @endif

            {{-- Status badge --}}
            <div class="cu-rem-status">
                @if($reminder->is_overdue)
                    <span class="cu-status-badge cu-sb-danger">
                        <i class="bi bi-exclamation-circle-fill"></i> Overdue
                    </span>
                @elseif($reminder->is_due_soon)
                    <span class="cu-status-badge cu-sb-warn">
                        <i class="bi bi-clock-fill"></i> Due Soon
                    </span>
                @elseif($reminder->is_completed)
                    <span class="cu-status-badge cu-sb-success">
                        <i class="bi bi-check-circle-fill"></i>
                        Completed {{ $reminder->completed_at ? $reminder->completed_at->diffForHumans() : '' }}
                    </span>
                @elseif($reminder->formatted_date_time)
                    <span class="cu-status-badge cu-sb-muted">
                        <i class="bi bi-clock"></i> {{ $reminder->formatted_date_time->diffForHumans() }}
                    </span>
                @else
                    <span class="cu-status-badge cu-sb-muted">
                        <i class="bi bi-bell"></i> No date set
                    </span>
                @endif
            </div>

            {{-- Actions --}}
            <div class="cu-rem-actions">
                @if(!$reminder->is_completed)
                    <button onclick="toggleComplete({{ $reminder->id }})" class="cu-act cu-act-success" title="Mark Complete">
                        <i class="bi bi-check-lg"></i> Complete
                    </button>
                @else
                    <button onclick="toggleComplete({{ $reminder->id }})" class="cu-act cu-act-muted" title="Reactivate">
                        <i class="bi bi-arrow-counterclockwise"></i> Reactivate
                    </button>
                @endif

                @if(!$reminder->is_completed && !($reminder->snooze_until && $reminder->snooze_until->isFuture()))
                    <button onclick="snoozeReminder({{ $reminder->id }})" class="cu-act cu-act-warn" title="Snooze 15 min">
                        <i class="bi bi-clock"></i> Snooze
                    </button>
                @endif

                <a href="{{ route('reminders.show', $reminder) }}" class="cu-act cu-act-info" title="View">
                    <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('reminders.edit', $reminder) }}" class="cu-act cu-act-primary" title="Edit">
                    <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('reminders.destroy', $reminder) }}" method="POST"
                      style="display:inline;" onsubmit="return confirm('Delete this reminder?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="cu-act cu-act-danger" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="cu-rem-empty">
            <i class="bi bi-bell-slash cu-empty-ico"></i>
            <h4>No reminders found</h4>
            <p class="text-muted">Try adjusting your filters, or create a new reminder.</p>
            <a href="{{ route('reminders.create') }}" class="cu-btn-new-empty">
                <i class="bi bi-plus-lg"></i> New Reminder
            </a>
        </div>
    @endforelse
</div>
