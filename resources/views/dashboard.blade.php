@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Welcome Section -->
        <div class="row mb-6">
            <div class="col-12">
                <div class="welcome-section">
                    <h1 class="welcome-title">Good {{ now()->format('A') === 'AM' ? 'morning' : (now()->format('H') < 18 ? 'afternoon' : 'evening') }}, {{ Auth::user()->name }}! 👋</h1>
                    <p class="welcome-subtitle">Here's what's happening with your tasks today.</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-6">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card stat-card-primary">
                    <div class="stat-card-icon">
                        <i class="bi bi-check-square-fill"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-number">{{ $tasksCount }}</div>
                        <div class="stat-card-label">Active Tasks</div>
                    </div>
                    <a href="{{ route('projects.index') }}" class="stat-card-action">
                        <span>View all</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card stat-card-success">
                    <div class="stat-card-icon">
                        <i class="bi bi-folder-fill"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-number">{{ $projectsCount }}</div>
                        <div class="stat-card-label">Total Projects</div>
                    </div>
                    <a href="{{ route('projects.index') }}" class="stat-card-action">
                        <span>Manage</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card stat-card-warning">
                    <div class="stat-card-icon">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-number">{{ $routinesCount }}</div>
                        <div class="stat-card-label">Today's Routines</div>
                    </div>
                    <a href="{{ route('routines.index') }}" class="stat-card-action">
                        <span>View routines</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card stat-card-info">
                    <div class="stat-card-icon">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <div class="stat-card-content">
                        <div class="stat-card-number">{{ $notesCount }}</div>
                        <div class="stat-card-label">Saved Notes</div>
                    </div>
                    <a href="{{ route('notes.index') }}" class="stat-card-action">
                        <span>Browse notes</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Advanced Analytics Section -->
        <div class="row mb-6">
            <!-- Productivity Overview -->
            <div class="col-xl-8 mb-4">
                <div class="analytics-card">
                    <div class="analytics-card-header">
                        <div class="analytics-card-title">
                            <i class="bi bi-graph-up"></i>
                            <span>Productivity Overview</span>
                        </div>
                        <div class="analytics-card-actions">
                            <select class="form-select form-select-sm" id="periodSelect">
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="year">This Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="analytics-card-content">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="metric-widget">
                                    <div class="metric-value">{{ $completedTasksThisWeek }}</div>
                                    <div class="metric-label">Tasks Completed</div>
                                    <div class="metric-trend positive">+12% from last week</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="metric-widget">
                                    <div class="metric-value">{{ $completionRate }}%</div>
                                    <div class="metric-label">Completion Rate</div>
                                    <div class="metric-trend positive">+5% improvement</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="metric-widget">
                                    <div class="metric-value">{{ $activeProjects }}</div>
                                    <div class="metric-label">Active Projects</div>
                                    <div class="metric-trend neutral">No change</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="metric-widget">
                                    <div class="metric-value">{{ $overdueTasks }}</div>
                                    <div class="metric-label">Overdue Tasks</div>
                                    <div class="metric-trend negative">Needs attention</div>
                                </div>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="productivityChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Calendar -->
            <div class="col-xl-4 mb-4">
                <div class="quick-actions-card mb-4">
                    <div class="quick-actions-header">
                        <h3>Quick Actions</h3>
                    </div>
                    <div class="quick-actions-grid">
                        <a href="{{ route('projects.create') }}" class="quick-action-item">
                            <div class="quick-action-icon primary">
                                <i class="bi bi-folder-plus"></i>
                            </div>
                            <span>New Project</span>
                        </a>
                        <a href="{{ route('notes.create') }}" class="quick-action-item">
                            <div class="quick-action-icon success">
                                <i class="bi bi-journal-plus"></i>
                            </div>
                            <span>Quick Note</span>
                        </a>
                        <a href="{{ route('reminders.create') }}" class="quick-action-item">
                            <div class="quick-action-icon warning">
                                <i class="bi bi-bell-plus"></i>
                            </div>
                            <span>Set Reminder</span>
                        </a>
                        <a href="{{ route('routines.create') }}" class="quick-action-item">
                            <div class="quick-action-icon info">
                                <i class="bi bi-arrow-clockwise"></i>
                            </div>
                            <span>Add Routine</span>
                        </a>
                    </div>
                </div>

                <div class="mini-calendar-card">
                    <div class="mini-calendar-header">
                        <h3>{{ now()->format('F Y') }}</h3>
                        <div class="calendar-nav">
                            <button class="btn btn-sm btn-outline" id="prevMonth">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button class="btn btn-sm btn-outline" id="nextMonth">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mini-calendar" id="miniCalendar">
                        <!-- Calendar will be generated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Status Distribution -->
        <div class="row mb-6">
            <div class="col-xl-4 mb-4">
                <div class="status-distribution-card">
                    <div class="status-distribution-header">
                        <h3>Task Distribution</h3>
                        <span class="total-tasks">{{ $tasksCount }} total tasks</span>
                    </div>
                    <div class="status-distribution-chart">
                        <canvas id="taskStatusChart" width="200" height="200"></canvas>
                    </div>
                    <div class="status-distribution-legend">
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: var(--primary-500);"></span>
                            <span class="legend-label">To Do ({{ $taskStatusDistribution['to_do'] }})</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: var(--warning-500);"></span>
                            <span class="legend-label">In Progress ({{ $taskStatusDistribution['in_progress'] }})</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: var(--success-500);"></span>
                            <span class="legend-label">Completed ({{ $taskStatusDistribution['completed'] }})</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Priority Distribution -->
            <div class="col-xl-4 mb-4">
                <div class="priority-card">
                    <div class="priority-header">
                        <h3>Priority Breakdown</h3>
                    </div>
                    <div class="priority-items">
                        <div class="priority-item high">
                            <div class="priority-indicator"></div>
                            <div class="priority-content">
                                <span class="priority-label">High Priority</span>
                                <span class="priority-count">{{ $priorityDistribution['high'] }}</span>
                            </div>
                            <div class="priority-progress">
                                <div class="progress-bar high" style="width: {{ $priorityPercentages['high'] }}%"></div>
                            </div>
                        </div>
                        <div class="priority-item medium">
                            <div class="priority-indicator"></div>
                            <div class="priority-content">
                                <span class="priority-label">Medium Priority</span>
                                <span class="priority-count">{{ $priorityDistribution['medium'] }}</span>
                            </div>
                            <div class="priority-progress">
                                <div class="progress-bar medium" style="width: {{ $priorityPercentages['medium'] }}%"></div>
                            </div>
                        </div>
                        <div class="priority-item low">
                            <div class="priority-indicator"></div>
                            <div class="priority-content">
                                <span class="priority-label">Low Priority</span>
                                <span class="priority-count">{{ $priorityDistribution['low'] }}</span>
                            </div>
                            <div class="priority-progress">
                                <div class="progress-bar low" style="width: {{ $priorityPercentages['low'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Timeline -->
            <div class="col-xl-4 mb-4">
                <div class="timeline-card">
                    <div class="timeline-header">
                        <h3>Recent Activity</h3>
                        <a href="#" class="view-all-link">View all</a>
                    </div>
                    <div class="timeline">
                        @foreach($recentTasks->take(4) as $task)
                        <div class="timeline-item">
                            <div class="timeline-marker {{ $task->status }}"></div>
                            <div class="timeline-content">
                                <div class="timeline-title">{{ $task->title }}</div>
                                <div class="timeline-subtitle">{{ $task->project->name ?? 'No Project' }}</div>
                                <div class="timeline-time">{{ $task->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Recent Tasks -->
            <div class="col-xl-6 col-lg-12 mb-4">
                <div class="activity-card">
                    <div class="activity-card-header">
                        <div class="activity-card-title">
                            <i class="bi bi-clock-history"></i>
                            <span>Recent Tasks</span>
                        </div>
                        <a href="{{ route('projects.index') }}" class="btn btn-outline btn-sm">
                            View all
                        </a>
                    </div>
                    <div class="activity-card-content">
                        @forelse($recentTasks as $task)
                            <div class="activity-item">
                                <div class="activity-item-icon task-status-{{ $task->status }}">
                                    <i class="bi bi-{{ $task->status == 'completed' ? 'check-circle-fill' : ($task->status == 'in_progress' ? 'arrow-right-circle' : 'circle') }}"></i>
                                </div>
                                <div class="activity-item-content">
                                    <div class="activity-item-title">{{ $task->title }}</div>
                                    <div class="activity-item-meta">
                                        <span class="task-status-badge status-{{ $task->status }}">
                                            {{ ucwords(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                        @if($task->due_date != null)
                                            <span class="activity-item-date">{{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-list-check"></i>
                                <p>No recent tasks found</p>
                                <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">Create Project</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Today's Routines -->
            <div class="col-xl-6 col-lg-12 mb-4">
                <div class="activity-card">
                    <div class="activity-card-header">
                        <div class="activity-card-title">
                            <i class="bi bi-calendar-day"></i>
                            <span>Today's Routines</span>
                        </div>
                        <a href="{{ route('routines.index') }}" class="btn btn-outline btn-sm">
                            View all
                        </a>
                    </div>
                    <div class="activity-card-content">
                        @forelse($todayRoutines as $routine)
                            <div class="activity-item">
                                <div class="activity-item-icon routine-frequency">
                                    <i class="bi bi-arrow-repeat"></i>
                                </div>
                                <div class="activity-item-content">
                                    <div class="activity-item-title">{{ $routine->title }}</div>
                                    <div class="activity-item-meta">
                                        <span class="routine-frequency-badge">{{ ucfirst($routine->frequency) }}</span>
                                        @if($routine->time)
                                            <span class="activity-item-date">{{ $routine->time->format('H:i') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-arrow-repeat"></i>
                                <p>No routines for today</p>
                                <a href="{{ route('routines.create') }}" class="btn btn-primary btn-sm">Create Routine</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Notes -->
            <div class="col-xl-6 col-lg-12 mb-4">
                <div class="activity-card">
                    <div class="activity-card-header">
                        <div class="activity-card-title">
                            <i class="bi bi-journal"></i>
                            <span>Recent Notes</span>
                        </div>
                        <a href="{{ route('notes.index') }}" class="btn btn-outline btn-sm">
                            View all
                        </a>
                    </div>
                    <div class="activity-card-content">
                        @forelse($recentNotes as $note)
                            <div class="activity-item">
                                <div class="activity-item-icon note-icon">
                                    <i class="bi bi-sticky"></i>
                                </div>
                                <div class="activity-item-content">
                                    <div class="activity-item-title">{{ $note->title }}</div>
                                    <div class="activity-item-meta">
                                        <span class="activity-item-date">{{ $note->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-journal-plus"></i>
                                <p>No notes yet</p>
                                <a href="{{ route('notes.create') }}" class="btn btn-primary btn-sm">Create Note</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Upcoming Reminders -->
            <div class="col-xl-6 col-lg-12 mb-4">
                <div class="activity-card">
                    <div class="activity-card-header">
                        <div class="activity-card-title">
                            <i class="bi bi-bell"></i>
                            <span>Upcoming Reminders</span>
                        </div>
                        <a href="{{ route('reminders.index') }}" class="btn btn-outline btn-sm">
                            View all
                        </a>
                    </div>
                    <div class="activity-card-content">
                        @forelse($upcomingReminders as $reminder)
                            <div class="activity-item">
                                <div class="activity-item-icon reminder-{{ $reminder->date->isToday() ? 'today' : ($reminder->date->isPast() ? 'overdue' : 'upcoming') }}">
                                    <i class="bi bi-bell{{ $reminder->date->isToday() ? '-fill' : '' }}"></i>
                                </div>
                                <div class="activity-item-content">
                                    <div class="activity-item-title">{{ $reminder->title }}</div>
                                    <div class="activity-item-meta">
                                        <span class="reminder-status-badge status-{{ $reminder->date->isToday() ? 'today' : ($reminder->date->isPast() ? 'overdue' : 'upcoming') }}">
                                            {{ $reminder->date->isToday() ? 'Today' : ($reminder->date->isPast() ? 'Overdue' : $reminder->date->format('M d')) }}
                                        </span>
                                        @if($reminder->time)
                                            <span class="activity-item-date">{{ \Carbon\Carbon::parse($reminder->time)->format('H:i') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-bell-plus"></i>
                                <p>No upcoming reminders</p>
                                <a href="{{ route('reminders.create') }}" class="btn btn-primary btn-sm">Create Reminder</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .welcome-section {
        padding: 2rem 0;
        margin-bottom: 1rem;
    }

    .welcome-title {
        font-size: 2rem;
        font-weight: 600;
        color: var(--gray-900);
        margin-bottom: 0.5rem;
    }

    .welcome-subtitle {
        font-size: 1.125rem;
        color: var(--gray-500);
        margin: 0;
    }

    .stat-card {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        position: relative;
        transition: all 0.15s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .stat-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .stat-card-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.5rem;
        color: white;
    }

    .stat-card-primary .stat-card-icon {
        background-color: var(--primary-500);
    }

    .stat-card-success .stat-card-icon {
        background-color: var(--success-500);
    }

    .stat-card-warning .stat-card-icon {
        background-color: var(--warning-500);
    }

    .stat-card-info .stat-card-icon {
        background-color: #3b82f6;
    }

    .stat-card-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--gray-900);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-card-label {
        color: var(--gray-500);
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .stat-card-action {
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: var(--primary-600);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid var(--gray-100);
        transition: color 0.15s ease;
    }

    .stat-card-action:hover {
        color: var(--primary-700);
    }

    .activity-card {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-lg);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .activity-card-header {
        padding: 1.5rem 1.5rem 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .activity-card-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: var(--gray-900);
        font-size: 1.125rem;
    }

    .activity-card-content {
        flex: 1;
        padding: 0 1.5rem 1.5rem;
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--gray-100);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-item-icon {
        width: 32px;
        height: 32px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
    }

    .task-status-to_do .activity-item-icon {
        background-color: var(--gray-100);
        color: var(--gray-500);
    }

    .task-status-in_progress .activity-item-icon {
        background-color: var(--primary-100);
        color: var(--primary-600);
    }

    .task-status-completed .activity-item-icon {
        background-color: var(--success-100);
        color: var(--success-600);
    }

    .routine-frequency .activity-item-icon {
        background-color: var(--warning-100);
        color: var(--warning-600);
    }

    .note-icon .activity-item-icon {
        background-color: #ddd6fe;
        color: #7c3aed;
    }

    .reminder-today .activity-item-icon {
        background-color: var(--warning-100);
        color: var(--warning-600);
    }

    .reminder-overdue .activity-item-icon {
        background-color: var(--error-100);
        color: var(--error-600);
    }

    .reminder-upcoming .activity-item-icon {
        background-color: var(--primary-100);
        color: var(--primary-600);
    }

    .activity-item-content {
        flex: 1;
        min-width: 0;
    }

    .activity-item-title {
        font-weight: 500;
        color: var(--gray-900);
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .activity-item-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
    }

    .task-status-badge,
    .routine-frequency-badge,
    .reminder-status-badge {
        padding: 0.125rem 0.5rem;
        border-radius: 12px;
        font-weight: 500;
        font-size: 0.75rem;
    }

    .status-to_do {
        background-color: var(--gray-100);
        color: var(--gray-600);
    }

    .status-in_progress {
        background-color: var(--primary-100);
        color: var(--primary-600);
    }

    .status-completed {
        background-color: var(--success-100);
        color: var(--success-600);
    }

    .status-today {
        background-color: var(--warning-100);
        color: var(--warning-600);
    }

    .status-overdue {
        background-color: var(--error-100);
        color: var(--error-600);
    }

    .status-upcoming {
        background-color: var(--primary-100);
        color: var(--primary-600);
    }

    .routine-frequency-badge {
        background-color: var(--warning-100);
        color: var(--warning-600);
    }

    .activity-item-date {
        color: var(--gray-400);
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--gray-500);
    }

    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state p {
        margin-bottom: 1rem;
        font-size: 0.875rem;
    }

    .mb-6 {
        margin-bottom: 3rem !important;
    }

    /* Analytics Card Styles */
    .analytics-card {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-lg);
        height: 100%;
    }

    .analytics-card-header {
        padding: 1.5rem 1.5rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--gray-200);
    }

    .analytics-card-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: var(--gray-900);
        font-size: 1.125rem;
    }

    .analytics-card-actions .form-select {
        border: 1px solid var(--gray-300);
        font-size: 0.875rem;
        padding: 0.25rem 0.75rem;
    }

    .analytics-card-content {
        padding: 1.5rem;
    }

    .metric-widget {
        text-align: center;
        padding: 1rem;
        border-radius: var(--radius-md);
        background: var(--gray-50);
    }

    .metric-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 0.25rem;
    }

    .metric-label {
        font-size: 0.875rem;
        color: var(--gray-500);
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .metric-trend {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.125rem 0.5rem;
        border-radius: 12px;
    }

    .metric-trend.positive {
        background-color: #f0fdf4;
        color: var(--success-600);
    }

    .metric-trend.negative {
        background-color: #fef2f2;
        color: var(--error-600);
    }

    .metric-trend.neutral {
        background-color: var(--gray-100);
        color: var(--gray-600);
    }

    .chart-container {
        position: relative;
        height: 300px;
        margin-top: 1rem;
    }

    /* Quick Actions Card */
    .quick-actions-card {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
    }

    .quick-actions-header h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--gray-900);
        margin-bottom: 1rem;
    }

    .quick-actions-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .quick-action-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 1rem;
        border-radius: var(--radius-md);
        text-decoration: none;
        transition: all 0.15s ease;
        border: 1px solid var(--gray-200);
    }

    .quick-action-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        text-decoration: none;
    }

    .quick-action-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
        font-size: 1.25rem;
        color: white;
    }

    .quick-action-icon.primary {
        background-color: var(--primary-500);
    }

    .quick-action-icon.success {
        background-color: var(--success-500);
    }

    .quick-action-icon.warning {
        background-color: var(--warning-500);
    }

    .quick-action-icon.info {
        background-color: #3b82f6;
    }

    .quick-action-item span {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--gray-700);
    }

    /* Mini Calendar */
    .mini-calendar-card {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }

    .mini-calendar-header {
        padding: 1rem 1.5rem;
        background: var(--gray-50);
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--gray-200);
    }

    .mini-calendar-header h3 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--gray-900);
        margin: 0;
    }

    .calendar-nav {
        display: flex;
        gap: 0.25rem;
    }

    .mini-calendar {
        padding: 1rem;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.25rem;
    }

    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: background-color 0.15s ease;
    }

    .calendar-day:hover {
        background-color: var(--gray-100);
    }

    .calendar-day.today {
        background-color: var(--primary-500);
        color: white;
    }

    .calendar-day.other-month {
        color: var(--gray-300);
    }

    .calendar-header {
        font-weight: 600;
        color: var(--gray-500);
        font-size: 0.75rem;
        padding: 0.5rem 0;
        text-align: center;
    }

    /* Status Distribution Card */
    .status-distribution-card {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        height: 100%;
    }

    .status-distribution-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .status-distribution-header h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--gray-900);
        margin: 0;
    }

    .total-tasks {
        font-size: 0.875rem;
        color: var(--gray-500);
        font-weight: 500;
    }

    .status-distribution-chart {
        display: flex;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .status-distribution-legend {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .legend-label {
        font-size: 0.875rem;
        color: var(--gray-700);
        font-weight: 500;
    }

    /* Priority Card */
    .priority-card {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        height: 100%;
    }

    .priority-header {
        margin-bottom: 1.5rem;
    }

    .priority-header h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--gray-900);
        margin: 0;
    }

    .priority-items {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .priority-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-radius: var(--radius-md);
        background: var(--gray-50);
    }

    .priority-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .priority-item.high .priority-indicator {
        background-color: var(--error-500);
    }

    .priority-item.medium .priority-indicator {
        background-color: var(--warning-500);
    }

    .priority-item.low .priority-indicator {
        background-color: var(--success-500);
    }

    .priority-content {
        flex: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .priority-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--gray-700);
    }

    .priority-count {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--gray-900);
    }

    .priority-progress {
        width: 60px;
        height: 8px;
        background-color: var(--gray-200);
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        transition: width 0.3s ease;
    }

    .progress-bar.high {
        background-color: var(--error-500);
    }

    .progress-bar.medium {
        background-color: var(--warning-500);
    }

    .progress-bar.low {
        background-color: var(--success-500);
    }

    /* Timeline Card */
    .timeline-card {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        height: 100%;
    }

    .timeline-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .timeline-header h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--gray-900);
        margin: 0;
    }

    .view-all-link {
        font-size: 0.875rem;
        color: var(--primary-600);
        text-decoration: none;
        font-weight: 500;
    }

    .view-all-link:hover {
        color: var(--primary-700);
    }

    .timeline {
        position: relative;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: var(--gray-200);
    }

    .timeline-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-marker {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 2px solid white;
        flex-shrink: 0;
        z-index: 1;
        position: relative;
    }

    .timeline-marker.to_do {
        background-color: var(--gray-400);
    }

    .timeline-marker.in_progress {
        background-color: var(--warning-500);
    }

    .timeline-marker.completed {
        background-color: var(--success-500);
    }

    .timeline-content {
        flex: 1;
        min-width: 0;
    }

    .timeline-title {
        font-weight: 500;
        color: var(--gray-900);
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .timeline-subtitle {
        font-size: 0.75rem;
        color: var(--gray-500);
        margin-bottom: 0.25rem;
    }

    .timeline-time {
        font-size: 0.75rem;
        color: var(--gray-400);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing charts...');

    // Productivity Chart
    const chartCanvas = document.getElementById('productivityChart');

    if (!chartCanvas) {
        console.error('Productivity chart canvas element not found');
        return;
    } else {
        console.log('Productivity chart canvas found');
    }

    const ctx = chartCanvas.getContext('2d');

    // Fetch productivity data
    fetch('{{ route("dashboard.productivity-data") }}')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Productivity chart data received:', data);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Tasks Completed',
                        data: data.data,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            console.log('Productivity chart initialized successfully');
        })
        .catch(error => {
            console.error('Error loading productivity data:', error);
        });

    // Task Status Distribution Chart
    const statusChartCanvas = document.getElementById('taskStatusChart');

    if (statusChartCanvas) {
        console.log('Status chart canvas found');

        const statusCtx = statusChartCanvas.getContext('2d');

        // Data from controller
        const statusData = {
            to_do: {{ $taskStatusDistribution['to_do'] }},
            in_progress: {{ $taskStatusDistribution['in_progress'] }},
            completed: {{ $taskStatusDistribution['completed'] }}
        };

        console.log('Status chart data:', statusData);

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['To Do', 'In Progress', 'Completed'],
                datasets: [{
                    data: [statusData.to_do, statusData.in_progress, statusData.completed],
                    backgroundColor: [
                        '#6366f1',  // Primary color
                        '#f59e0b',  // Warning color
                        '#10b981'   // Success color
                    ],
                    borderWidth: 0,
                    cutout: '65%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        console.log('Status chart initialized successfully');
    } else {
        console.error('Status chart canvas element not found');
    }
});
</script>
@endpush
