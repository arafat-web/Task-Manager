<!-- Reminders Grid View -->
<div class="reminders-grid" id="reminders-grid">
    @forelse($reminders as $reminder)
        <div class="reminder-card priority-{{ $reminder->priority }} {{ $reminder->is_completed ? 'reminder-completed' : '' }} {{ $reminder->is_overdue ? 'reminder-overdue' : '' }}">
            <!-- Card Header -->
            <div class="reminder-card-header">
                <h3 class="reminder-title">
                    {{ $reminder->title }}
                </h3>
                <span class="priority-badge {{ $reminder->priority }}">
                    {{ ucfirst($reminder->priority) }}
                </span>
            </div>

            <!-- Card Content -->
            @if($reminder->description)
                <div class="reminder-content">
                    {{ $reminder->description }}
                </div>
            @endif

            <!-- Card Meta -->
            <div class="reminder-meta">
                <div class="reminder-details">
                    @if($reminder->formatted_date_time)
                        <div class="reminder-date">
                            <i class="fas fa-calendar-alt me-1"></i>
                            <span class="{{ $reminder->is_overdue ? 'text-danger' : '' }}">
                                {{ $reminder->formatted_date_time->format('M j, Y') }}
                                @if($reminder->time)
                                    at {{ $reminder->formatted_date_time->format('g:i A') }}
                                @endif
                            </span>
                        </div>
                    @endif

                    @if($reminder->category)
                        <div class="reminder-category mt-1">
                            <i class="fas fa-folder me-1"></i>
                            <span>{{ $reminder->category }}</span>
                        </div>
                    @endif

                    @if($reminder->location)
                        <div class="reminder-location mt-1">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            <span>{{ Str::limit($reminder->location, 50) }}</span>
                        </div>
                    @endif

                    @if($reminder->is_recurring)
                        <div class="reminder-recurring mt-1">
                            <i class="fas fa-repeat me-1"></i>
                            <span>Repeats {{ $reminder->recurrence_type }}</span>
                        </div>
                    @endif

                    @if($reminder->is_completed)
                        <div class="reminder-completed-status mt-1">
                            <i class="fas fa-check-circle me-1"></i>
                            <span class="text-success">Completed {{ $reminder->completed_at->diffForHumans() }}</span>
                        </div>
                    @endif

                    @if($reminder->snooze_until && $reminder->snooze_until->isFuture())
                        <div class="reminder-snoozed mt-1">
                            <i class="fas fa-clock me-1"></i>
                            <span class="text-warning">Snoozed until {{ $reminder->snooze_until->format('M j, g:i A') }}</span>
                        </div>
                    @endif

                    @if($reminder->tags && count($reminder->tags) > 0)
                        <div class="reminder-tags mt-2">
                            @foreach($reminder->tags as $tag)
                                <span class="tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="reminder-time-info">
                    <small class="text-muted">
                        @if($reminder->is_overdue)
                            <span class="text-danger">Overdue</span>
                        @elseif($reminder->formatted_date_time)
                            {{ $reminder->formatted_date_time->diffForHumans() }}
                        @else
                            No date set
                        @endif
                    </small>
                </div>
            </div>

            <!-- Card Actions -->
            <div class="reminder-actions">
                @if(!$reminder->is_completed)
                    <button onclick="toggleComplete({{ $reminder->id }})" class="action-btn success" title="Mark as Complete">
                        <i class="fas fa-check"></i>
                        <span>Complete</span>
                    </button>
                @else
                    <button onclick="toggleComplete({{ $reminder->id }})" class="action-btn" title="Mark as Incomplete">
                        <i class="fas fa-undo"></i>
                        <span>Reactivate</span>
                    </button>
                @endif

                @if(!$reminder->is_completed && !($reminder->snooze_until && $reminder->snooze_until->isFuture()))
                    <button onclick="snoozeReminder({{ $reminder->id }})" class="action-btn warning" title="Snooze">
                        <i class="fas fa-clock"></i>
                        <span>Snooze</span>
                    </button>
                @endif

                <a href="{{ route('reminders.show', $reminder) }}" class="action-btn info" title="View Details">
                    <i class="fas fa-eye"></i>
                    <span>View</span>
                </a>

                <a href="{{ route('reminders.edit', $reminder) }}" class="action-btn primary" title="Edit">
                    <i class="fas fa-edit"></i>
                    <span>Edit</span>
                </a>

                <form action="{{ route('reminders.destroy', $reminder) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this reminder?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn danger" title="Delete">
                        <i class="fas fa-trash"></i>
                        <span>Delete</span>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-bell-slash"></i>
            </div>
            <h3 class="mb-3">No reminders found</h3>
            <p class="text-muted mb-4">
                @if(request('search') || request('category') !== 'all' || request('priority') !== 'all')
                    Try adjusting your search terms or filters to find what you're looking for.
                @else
                    Create your first reminder to stay organized and never miss important tasks.
                @endif
            </p>
            @if(!request('search') && request('category') === 'all' && request('priority') === 'all')
                <a href="{{ route('reminders.create') }}" class="btn" style="background: var(--reminder-primary); color: white; border-radius: 8px; padding: 0.75rem 2rem;">
                    <i class="fas fa-plus me-2"></i>Create Your First Reminder
                </a>
            @endif
        </div>
    @endforelse
</div>

@if($reminders->count() > 0)
    <div class="text-center mt-4">
        <small class="text-muted">{{ $reminders->count() }} reminder(s) found</small>
    </div>
@endif
