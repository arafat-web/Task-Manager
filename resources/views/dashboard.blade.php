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
<link rel="stylesheet" href="{{ asset('assets/dashboard/style.css') }}">
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
