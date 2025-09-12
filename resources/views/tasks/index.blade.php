@extends('layouts.app')

@section('title', isset($project) ? $project->name . ' Tasks' : 'My Tasks')

@push('styles')
<style>
    .tasks-header {
        background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
        border-radius: 18px;
        padding: 28px 36px;
        color: white;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
        border: 2px solid var(--primary-500);
        box-shadow: var(--shadow-lg);
    }

    .tasks-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(25px, -25px);
    }

    .tasks-header h1 {
        margin: 0;
        font-size: 32px;
        font-weight: 700;
        position: relative;
        z-index: 1;
    }

    .tasks-header p {
        margin: 8px 0 0;
        opacity: 0.9;
        font-size: 16px;
        position: relative;
        z-index: 1;
    }

    .view-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        background: white;
        padding: 20px 28px;
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
        border: 2px solid var(--gray-200);
    }

    .view-toggle {
        display: flex;
        background: var(--gray-100);
        border-radius: 10px;
        padding: 4px;
        gap: 2px;
    }

    .view-toggle button {
        padding: 10px 18px;
        border: none;
        background: transparent;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        color: var(--gray-600);
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .view-toggle button.active {
        background: var(--primary-600);
        color: white;
        box-shadow: var(--shadow-sm);
    }

    .task-filters {
        display: flex;
        gap: 16px;
        align-items: center;
    }

    .filter-select {
        padding: 8px 14px;
        border: 2px solid var(--gray-200);
        border-radius: 8px;
        background: white;
        font-size: 14px;
        color: var(--gray-700);
        min-width: 120px;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--primary-500);
    }

    .kanban-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        min-height: 600px;
    }

    .kanban-column {
        background: var(--gray-50);
        border-radius: 16px;
        padding: 0;
        border: 2px solid var(--gray-200);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .column-header {
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 700;
        font-size: 16px;
        color: white;
        position: relative;
    }

    .column-header.todo {
        background: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-600) 100%);
    }

    .column-header.in-progress {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .column-header.completed {
        background: linear-gradient(135deg, var(--success-500) 0%, var(--success-600) 100%);
    }

    .column-header .task-count {
        background: rgba(255, 255, 255, 0.2);
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .add-task-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .add-task-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
    }

    .kanban-list {
        padding: 24px;
        min-height: 500px;
        background: var(--gray-50);
    }

    .task-card {
        background: white;
        border: 2px solid var(--gray-200);
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 16px;
        cursor: move;
        transition: all 0.2s ease;
        position: relative;
        box-shadow: var(--shadow-sm);
    }

    .task-card:hover {
        border-color: var(--primary-500);
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }

    .task-card.dragging {
        opacity: 0.5;
        transform: rotate(5deg);
        z-index: 1000;
    }

    .task-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--gray-900);
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .task-description {
        font-size: 14px;
        color: var(--gray-600);
        margin-bottom: 14px;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .task-meta {
        display: flex;
        justify-content: between;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .priority-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .priority-low {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success-600);
    }

    .priority-medium {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .priority-high {
        background: rgba(239, 68, 68, 0.1);
        color: var(--error-600);
    }

    .due-date {
        font-size: 12px;
        color: var(--gray-500);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .due-date.overdue {
        color: var(--error-600);
        font-weight: 600;
    }

    .task-assignee {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--primary-500);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
        margin-left: auto;
    }

    .task-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid var(--gray-200);
    }

    .task-btn {
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-view {
        background: var(--primary-50);
        color: var(--primary-600);
        border: 1px solid var(--primary-200);
    }

    .btn-view:hover {
        background: var(--primary-100);
        color: var(--primary-700);
    }

    .btn-edit {
        background: var(--gray-100);
        color: var(--gray-700);
        border: 1px solid var(--gray-200);
    }

    .btn-edit:hover {
        background: var(--gray-200);
        color: var(--gray-800);
    }

    .list-view {
        display: none;
    }

    .list-view.active {
        display: block;
    }

    .kanban-view.active {
        display: grid;
    }

    .tasks-table {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 2px solid var(--gray-200);
    }

    .table-header {
        background: var(--gray-100);
        padding: 16px 24px;
        border-bottom: 2px solid var(--gray-200);
        font-weight: 600;
        color: var(--gray-700);
    }

    .main-content {
        padding: 32px;
        background: var(--gray-25);
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--gray-600);
        text-decoration: none;
        margin-bottom: 24px;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .back-link:hover {
        color: var(--primary-600);
    }

    @media (max-width: 768px) {
        .kanban-container {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .view-controls {
            flex-direction: column;
            gap: 16px;
            align-items: stretch;
        }

        .task-filters {
            justify-content: center;
        }

        .main-content {
            padding: 20px;
        }
    }

    .empty-state {
        text-align: center;
        color: var(--gray-400);
        font-style: italic;
        padding: 40px 20px;
        background: var(--gray-50);
        border: 2px dashed var(--gray-200);
        border-radius: 12px;
        margin-top: 16px;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 12px;
        color: var(--gray-300);
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <!-- Tasks Header -->
    <div class="tasks-header">
        @if(isset($project))
            <h1>{{ $project->name }} Tasks</h1>
            <p>Manage tasks for {{ $project->name }} project</p>
        @else
            <h1>My Tasks</h1>
            <p>Manage and track all your tasks across projects</p>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- View Controls -->
    <div class="view-controls">
        <div class="view-toggle">
            <button type="button" class="active" data-view="kanban">
                <i class="bi bi-kanban me-2"></i>Kanban
            </button>
            <button type="button" data-view="list">
                <i class="bi bi-list-ul me-2"></i>List
            </button>
            <button type="button" data-view="calendar">
                <i class="bi bi-calendar3 me-2"></i>Calendar
            </button>
        </div>

        <div class="task-filters">
            <select class="filter-select" id="priorityFilter">
                <option value="">All Priorities</option>
                <option value="high">High Priority</option>
                <option value="medium">Medium Priority</option>
                <option value="low">Low Priority</option>
            </select>
            <select class="filter-select" id="assigneeFilter">
                <option value="">All Assignees</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                <i class="bi bi-plus-lg me-2"></i>New Task
            </button>
        </div>
    </div>

    @php
        $hasAnyTasks = collect($tasks)->flatten()->isNotEmpty();
    @endphp

    @if(!$hasAnyTasks)
        <div class="empty-state" style="margin-top: 40px;">
            <i class="bi bi-list-task"></i>
            <h4>{{ isset($project) ? 'No tasks in ' . $project->name : 'No tasks found' }}</h4>
            <p>{{ isset($project) ? 'This project doesn\'t have any tasks yet.' : 'Tasks from completed projects are hidden. Create a new task or check your active projects.' }}</p>
            <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                <i class="bi bi-plus-lg me-2"></i>Create Your First Task
            </button>
        </div>
    @else

    <!-- Kanban View -->
    <div class="kanban-view active kanban-container">
        <!-- To Do Column -->
        <div class="kanban-column">
            <div class="column-header todo">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-circle"></i>
                    <span>To Do</span>
                    <span class="task-count">{{ count($tasks['to_do'] ?? []) }}</span>
                </div>
                <button type="button" class="add-task-btn" data-bs-toggle="modal" data-bs-target="#createTaskModal" data-status="to_do">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>

            <div class="kanban-list" id="to_do">
                @forelse ($tasks['to_do'] ?? [] as $task)
                    <div class="task-card" data-id="{{ $task->id }}" draggable="true">
                        <div class="task-title">{{ $task->title }}</div>

                        @if($task->description)
                            <div class="task-description">{!! strip_tags($task->description) !!}</div>
                        @endif

                        <div class="task-meta">
                            <span class="priority-badge priority-{{ $task->priority }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                            @if($task->due_date)
                                <span class="due-date {{ \Carbon\Carbon::parse($task->due_date)->isPast() ? 'overdue' : '' }}">
                                    <i class="bi bi-calendar-event"></i>
                                    {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                                </span>
                            @endif
                            @if($task->user)
                                <div class="task-assignee" title="{{ $task->user->name }}">
                                    {{ strtoupper(substr($task->user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        @if($task->project)
                            <div class="task-project" style="font-size: 12px; color: var(--gray-500); margin-bottom: 8px; display: flex; align-items: center; gap: 4px;">
                                <i class="bi bi-folder"></i>
                                {{ $task->project->name }}
                            </div>
                        @endif

                        <div class="task-actions">
                            <a href="{{ route('tasks.show', $task->id) }}" class="task-btn btn-view">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="{{ route('tasks.edit', $task->id) }}" class="task-btn btn-edit">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-circle"></i>
                        <p>No tasks to do{{ isset($project) ? ' in ' . $project->name : '' }}. Great work!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- In Progress Column -->
        <div class="kanban-column">
            <div class="column-header in-progress">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-arrow-clockwise"></i>
                    <span>In Progress</span>
                    <span class="task-count">{{ count($tasks['in_progress'] ?? []) }}</span>
                </div>
                <button type="button" class="add-task-btn" data-bs-toggle="modal" data-bs-target="#createTaskModal" data-status="in_progress">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>

            <div class="kanban-list" id="in_progress">
                @forelse ($tasks['in_progress'] ?? [] as $task)
                    <div class="task-card" data-id="{{ $task->id }}" draggable="true">
                        <div class="task-title">{{ $task->title }}</div>

                        @if($task->description)
                            <div class="task-description">{!! strip_tags($task->description) !!}</div>
                        @endif

                        <div class="task-meta">
                            <span class="priority-badge priority-{{ $task->priority }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                            @if($task->due_date)
                                <span class="due-date {{ \Carbon\Carbon::parse($task->due_date)->isPast() ? 'overdue' : '' }}">
                                    <i class="bi bi-calendar-event"></i>
                                    {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                                </span>
                            @endif
                            @if($task->user)
                                <div class="task-assignee" title="{{ $task->user->name }}">
                                    {{ strtoupper(substr($task->user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        @if($task->project)
                            <div class="task-project" style="font-size: 12px; color: var(--gray-500); margin-bottom: 8px; display: flex; align-items: center; gap: 4px;">
                                <i class="bi bi-folder"></i>
                                {{ $task->project->name }}
                            </div>
                        @endif

                        <div class="task-actions">
                            <a href="{{ route('tasks.show', $task->id) }}" class="task-btn btn-view">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="{{ route('tasks.edit', $task->id) }}" class="task-btn btn-edit">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-arrow-clockwise"></i>
                        <p>No tasks in progress{{ isset($project) ? ' in ' . $project->name : '' }}. Start working on something!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Completed Column -->
        <div class="kanban-column">
            <div class="column-header completed">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle"></i>
                    <span>Completed</span>
                    <span class="task-count">{{ count($tasks['completed'] ?? []) }}</span>
                </div>
                <button type="button" class="add-task-btn" data-bs-toggle="modal" data-bs-target="#createTaskModal" data-status="completed">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>

            <div class="kanban-list" id="completed">
                @forelse ($tasks['completed'] ?? [] as $task)
                    <div class="task-card" data-id="{{ $task->id }}" draggable="true">
                        <div class="task-title">{{ $task->title }}</div>

                        @if($task->description)
                            <div class="task-description">{!! strip_tags($task->description) !!}</div>
                        @endif

                        <div class="task-meta">
                            <span class="priority-badge priority-{{ $task->priority }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                            @if($task->due_date)
                                <span class="due-date">
                                    <i class="bi bi-calendar-check"></i>
                                    {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                                </span>
                            @endif
                            @if($task->user)
                                <div class="task-assignee" title="{{ $task->user->name }}">
                                    {{ strtoupper(substr($task->user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        @if($task->project)
                            <div class="task-project" style="font-size: 12px; color: var(--gray-500); margin-bottom: 8px; display: flex; align-items: center; gap: 4px;">
                                <i class="bi bi-folder"></i>
                                {{ $task->project->name }}
                            </div>
                        @endif

                        <div class="task-actions">
                            <a href="{{ route('tasks.show', $task->id) }}" class="task-btn btn-view">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="{{ route('tasks.edit', $task->id) }}" class="task-btn btn-edit">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-check-circle"></i>
                        <p>No completed tasks{{ isset($project) ? ' in ' . $project->name : '' }}. Keep working!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- List View -->
    <div class="list-view">
        <div class="tasks-table">
            <div class="table-header">
                <div class="row align-items-center">
                    <div class="col-3"><strong>Task</strong></div>
                    <div class="col-2"><strong>Project</strong></div>
                    <div class="col-1"><strong>Priority</strong></div>
                    <div class="col-2"><strong>Assignee</strong></div>
                    <div class="col-2"><strong>Due Date</strong></div>
                    <div class="col-2"><strong>Actions</strong></div>
                </div>
            </div>
            <div class="table-body" style="padding: 0;">
                @php
                    $allTasks = collect($tasks)->flatten();
                @endphp
                @foreach ($allTasks as $task)
                    <div class="task-row" style="padding: 16px 24px; border-bottom: 1px solid var(--gray-200); display: flex; align-items: center;">
                        <div class="col-3">
                            <div class="task-title" style="font-weight: 600; margin-bottom: 4px;">{{ $task->title }}</div>
                            <div class="task-status" style="font-size: 12px; color: var(--gray-500);">
                                <span class="status-badge status-{{ str_replace(' ', '-', strtolower($task->status)) }}">
                                    {{ ucwords(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-2">
                            @if($task->project)
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-folder" style="color: var(--gray-500);"></i>
                                    <span style="font-size: 14px;">{{ $task->project->name }}</span>
                                </div>
                            @else
                                <span class="text-muted">No project</span>
                            @endif
                        </div>
                        <div class="col-1">
                            <span class="priority-badge priority-{{ $task->priority }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </div>
                        <div class="col-2">
                            @if($task->user)
                                <div class="d-flex align-items-center gap-2">
                                    <div class="task-assignee">
                                        {{ strtoupper(substr($task->user->name, 0, 1)) }}
                                    </div>
                                    <span style="font-size: 14px;">{{ $task->user->name }}</span>
                                </div>
                            @else
                                <span class="text-muted">Unassigned</span>
                            @endif
                        </div>
                        <div class="col-2">
                            @if($task->due_date)
                                <span class="due-date {{ \Carbon\Carbon::parse($task->due_date)->isPast() ? 'overdue' : '' }}">
                                    <i class="bi bi-calendar-event"></i>
                                    {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-muted">No due date</span>
                            @endif
                        </div>
                        <div class="col-2">
                            <div class="d-flex gap-2">
                                <a href="{{ route('tasks.show', $task->id) }}" class="task-btn btn-view">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('tasks.edit', $task->id) }}" class="task-btn btn-edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Calendar View -->
    <div class="calendar-view" style="display: none;">
        <div class="calendar-container" style="background: white; border-radius: 16px; padding: 28px; box-shadow: var(--shadow-sm); border: 2px solid var(--gray-200);">
            <div class="calendar-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h3 style="margin: 0; color: var(--gray-900);">Task Calendar</h3>
                <div class="calendar-nav" style="display: flex; gap: 12px; align-items: center;">
                    <button class="btn btn-outline-primary btn-sm" id="prevMonth">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <span id="currentMonth" style="font-weight: 600; min-width: 150px; text-align: center;"></span>
                    <button class="btn btn-outline-primary btn-sm" id="nextMonth">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
            <div id="calendar" style="min-height: 500px; border: 1px solid var(--gray-200); border-radius: 8px;"></div>
        </div>
    </div>
</div>

<!-- Enhanced Create Task Modal -->
<div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border: none; border-radius: 16px; box-shadow: var(--shadow-xl);">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%); color: white; border: none; border-radius: 16px 16px 0 0; padding: 24px 28px;">
                <h5 class="modal-title" id="createTaskModalLabel" style="font-weight: 700; margin: 0;">
                    <i class="bi bi-plus-circle me-2"></i>Create New Task
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ isset($project) ? route('projects.tasks.store', $project) : route('tasks.store') }}" method="POST">
                @csrf
                <div class="modal-body" style="padding: 28px;">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="title" class="form-label" style="font-weight: 600; color: var(--gray-700); margin-bottom: 8px;">Task Title *</label>
                                <input type="text" name="title" id="title" class="form-control" style="padding: 12px 16px; border: 2px solid var(--gray-200); border-radius: 8px;" placeholder="Enter task title" required>
                                @error('title')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="priority" class="form-label" style="font-weight: 600; color: var(--gray-700); margin-bottom: 8px;">Priority *</label>
                                <select name="priority" id="priority" class="form-select" style="padding: 12px 16px; border: 2px solid var(--gray-200); border-radius: 8px;" required>
                                    <option value="low">🟢 Low Priority</option>
                                    <option value="medium" selected>🟡 Medium Priority</option>
                                    <option value="high">🔴 High Priority</option>
                                </select>
                                @error('priority')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="description" class="form-label" style="font-weight: 600; color: var(--gray-700); margin-bottom: 8px;">Description</label>
                        <div class="editor-container" style="border: 2px solid var(--gray-200); border-radius: 8px; overflow: hidden;">
                            <div id="task-quill-editor" style="height: 150px;"></div>
                            <textarea name="description" id="task_description" style="display: none;"></textarea>
                        </div>
                        @error('description')
                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="project_id" class="form-label" style="font-weight: 600; color: var(--gray-700); margin-bottom: 8px;">Project *</label>
                                <select name="project_id" id="project_id" class="form-select" style="padding: 12px 16px; border: 2px solid var(--gray-200); border-radius: 8px;" required>
                                    <option value="">Select Project</option>
                                    @foreach ($projects as $proj)
                                        <option value="{{ $proj->id }}" {{ (isset($project) && $project->id == $proj->id) ? 'selected' : '' }}>
                                            📁 {{ $proj->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="due_date" class="form-label" style="font-weight: 600; color: var(--gray-700); margin-bottom: 8px;">Due Date</label>
                                <input type="date" name="due_date" id="due_date" class="form-control" style="padding: 12px 16px; border: 2px solid var(--gray-200); border-radius: 8px;">
                                @error('due_date')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="user_id" class="form-label" style="font-weight: 600; color: var(--gray-700); margin-bottom: 8px;">Assign To</label>
                                <select name="user_id" id="user_id" class="form-select" style="padding: 12px 16px; border: 2px solid var(--gray-200); border-radius: 8px;" required>
                                    <option value="{{ auth()->user()->id }}">👤 Assign to Me</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">👤 {{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="estimated_hours" class="form-label" style="font-weight: 600; color: var(--gray-700); margin-bottom: 8px;">Estimated Hours</label>
                                <input type="number" name="estimated_hours" id="estimated_hours" class="form-control" style="padding: 12px 16px; border: 2px solid var(--gray-200); border-radius: 8px;" min="0.5" step="0.5" placeholder="2.5">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="status" id="task_status" value="to_do">
                </div>

                <div class="modal-footer" style="padding: 20px 28px; border: none; background: var(--gray-50); border-radius: 0 0 16px 16px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 10px 20px; font-weight: 600;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="padding: 10px 24px; font-weight: 600;">
                        <i class="bi bi-check-lg me-2"></i>Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Quill editor for task description
    let taskQuill = null;

    function initializeQuillEditor() {
        if (!taskQuill) {
            taskQuill = new Quill('#task-quill-editor', {
                theme: 'snow',
                placeholder: 'Describe the task requirements, goals, and any specific instructions...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link'],
                        ['clean']
                    ]
                }
            });

            // Update hidden textarea when content changes
            taskQuill.on('text-change', function() {
                document.getElementById('task_description').value = taskQuill.root.innerHTML;
            });
        }
    }

    // View Toggle Functionality
    const viewToggleButtons = document.querySelectorAll('.view-toggle button');
    const kanbanView = document.querySelector('.kanban-view');
    const listView = document.querySelector('.list-view');
    const calendarView = document.querySelector('.calendar-view');

    viewToggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const view = this.getAttribute('data-view');

            // Update active button
            viewToggleButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Show/hide views
            kanbanView.style.display = view === 'kanban' ? 'grid' : 'none';
            listView.style.display = view === 'list' ? 'block' : 'none';
            calendarView.style.display = view === 'calendar' ? 'block' : 'none';

            if (view === 'calendar') {
                initializeCalendar();
            }
        });
    });

    // Filter Functionality
    const priorityFilter = document.getElementById('priorityFilter');
    const assigneeFilter = document.getElementById('assigneeFilter');

    function applyFilters() {
        const priorityValue = priorityFilter.value;
        const assigneeValue = assigneeFilter.value;

        const taskCards = document.querySelectorAll('.task-card');
        const taskRows = document.querySelectorAll('.task-row');

        // Filter Kanban cards
        taskCards.forEach(card => {
            const taskPriority = card.querySelector('.priority-badge').textContent.toLowerCase();
            const taskAssignee = card.querySelector('.task-assignee')?.title || '';

            let showCard = true;

            if (priorityValue && !taskPriority.includes(priorityValue)) {
                showCard = false;
            }

            if (assigneeValue && !taskAssignee.includes(document.querySelector(`option[value="${assigneeValue}"]`)?.textContent || '')) {
                showCard = false;
            }

            card.style.display = showCard ? 'block' : 'none';
        });

        // Filter List rows
        taskRows.forEach(row => {
            const taskPriority = row.querySelector('.priority-badge').textContent.toLowerCase();
            const taskAssigneeElement = row.querySelector('.task-assignee');
            const taskAssignee = taskAssigneeElement ? taskAssigneeElement.nextElementSibling?.textContent || '' : '';

            let showRow = true;

            if (priorityValue && !taskPriority.includes(priorityValue)) {
                showRow = false;
            }

            if (assigneeValue && !taskAssignee.includes(document.querySelector(`option[value="${assigneeValue}"]`)?.textContent || '')) {
                showRow = false;
            }

            row.style.display = showRow ? 'flex' : 'none';
        });
    }

    priorityFilter.addEventListener('change', applyFilters);
    assigneeFilter.addEventListener('change', applyFilters);

    // Enhanced Drag and Drop
    const taskCards = document.querySelectorAll('.task-card');
    const kanbanLists = document.querySelectorAll('.kanban-list');

    taskCards.forEach(card => {
        card.addEventListener('dragstart', handleDragStart);
        card.addEventListener('dragend', handleDragEnd);
    });

    kanbanLists.forEach(list => {
        list.addEventListener('dragover', handleDragOver);
        list.addEventListener('drop', handleDrop);
        list.addEventListener('dragenter', handleDragEnter);
        list.addEventListener('dragleave', handleDragLeave);
    });

    function handleDragStart(e) {
        e.dataTransfer.setData('text/plain', e.target.dataset.id);
        e.target.classList.add('dragging');

        // Add visual feedback to drop zones
        kanbanLists.forEach(list => {
            if (list !== e.target.closest('.kanban-list')) {
                list.style.background = 'var(--primary-50)';
                list.style.border = '2px dashed var(--primary-300)';
            }
        });
    }

    function handleDragEnd(e) {
        e.target.classList.remove('dragging');

        // Remove visual feedback
        kanbanLists.forEach(list => {
            list.style.background = 'var(--gray-50)';
            list.style.border = 'none';
        });
    }

    function handleDragOver(e) {
        e.preventDefault();
    }

    function handleDragEnter(e) {
        e.preventDefault();
        if (e.target.classList.contains('kanban-list')) {
            e.target.style.background = 'var(--primary-100)';
        }
    }

    function handleDragLeave(e) {
        if (e.target.classList.contains('kanban-list')) {
            e.target.style.background = 'var(--primary-50)';
        }
    }

    function handleDrop(e) {
        e.preventDefault();
        const taskId = e.dataTransfer.getData('text');
        const taskCard = document.querySelector(`.task-card[data-id='${taskId}']`);
        const dropZone = e.target.closest('.kanban-list');

        if (dropZone && taskCard) {
            dropZone.appendChild(taskCard);
            const newStatus = dropZone.id;

            // Update task count in headers
            updateTaskCounts();

            // Update status on server
            updateTaskStatus(taskId, newStatus);
        }
    }

    function updateTaskCounts() {
        const columns = ['to_do', 'in_progress', 'completed'];

        columns.forEach(status => {
            const column = document.getElementById(status);
            const taskCount = column.querySelectorAll('.task-card').length;
            const countElement = column.closest('.kanban-column').querySelector('.task-count');
            countElement.textContent = taskCount;
        });
    }

    function updateTaskStatus(taskId, status) {
        fetch(`/tasks/${taskId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to update task status');
            }
            return response.json();
        })
        .then(data => {
            // Show success notification
            showNotification('Task status updated successfully!', 'success');
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to update task status', 'error');
            // Revert the move if it failed
            location.reload();
        });
    }

    // Modal functionality
    const createTaskModal = document.getElementById('createTaskModal');
    const taskStatusInput = document.getElementById('task_status');

    createTaskModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const status = button.getAttribute('data-status') || 'to_do';
        taskStatusInput.value = status;

        // Initialize Quill editor when modal opens
        setTimeout(initializeQuillEditor, 100);
    });

    // Calendar functionality
    function initializeCalendar() {
        const calendarEl = document.getElementById('calendar');
        const currentMonthEl = document.getElementById('currentMonth');
        const prevMonthBtn = document.getElementById('prevMonth');
        const nextMonthBtn = document.getElementById('nextMonth');

        let currentDate = new Date();

        function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            // Update month display
            currentMonthEl.textContent = new Intl.DateTimeFormat('en-US', {
                month: 'long',
                year: 'numeric'
            }).format(currentDate);

            // Clear calendar
            calendarEl.innerHTML = '';

            // Create calendar grid
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const firstDayOfMonth = new Date(year, month, 1).getDay();

            // Calendar header
            const headerRow = document.createElement('div');
            headerRow.style.cssText = 'display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; background: var(--gray-200); padding: 12px; font-weight: 600; text-align: center; color: var(--gray-700);';
            ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].forEach(day => {
                const dayEl = document.createElement('div');
                dayEl.textContent = day;
                headerRow.appendChild(dayEl);
            });
            calendarEl.appendChild(headerRow);

            // Calendar body
            const calendarBody = document.createElement('div');
            calendarBody.style.cssText = 'display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; background: var(--gray-200);';

            // Empty cells for days before month start
            for (let i = 0; i < firstDayOfMonth; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.style.cssText = 'background: white; min-height: 80px; padding: 8px;';
                calendarBody.appendChild(emptyDay);
            }

            // Days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                const dayEl = document.createElement('div');
                dayEl.style.cssText = 'background: white; min-height: 80px; padding: 8px; position: relative; border: 1px solid transparent;';

                const dayNumber = document.createElement('div');
                dayNumber.textContent = day;
                dayNumber.style.cssText = 'font-weight: 600; color: var(--gray-700); margin-bottom: 4px;';
                dayEl.appendChild(dayNumber);

                // Add tasks for this day
                const dayTasks = getAllTasks().filter(task => {
                    if (!task.due_date) return false;
                    const taskDate = new Date(task.due_date);
                    return taskDate.getDate() === day &&
                           taskDate.getMonth() === month &&
                           taskDate.getFullYear() === year;
                });

                dayTasks.forEach(task => {
                    const taskEl = document.createElement('div');
                    taskEl.style.cssText = `
                        background: var(--primary-100);
                        color: var(--primary-700);
                        padding: 2px 6px;
                        border-radius: 4px;
                        font-size: 11px;
                        margin-bottom: 2px;
                        cursor: pointer;
                        border-left: 3px solid ${task.priority === 'high' ? 'var(--error-500)' : task.priority === 'medium' ? '#d97706' : 'var(--success-500)'}
                    `;
                    taskEl.textContent = task.title.substring(0, 15) + (task.title.length > 15 ? '...' : '');
                    taskEl.title = task.title;
                    taskEl.onclick = () => window.location.href = `/tasks/${task.id}`;
                    dayEl.appendChild(taskEl);
                });

                calendarBody.appendChild(dayEl);
            }

            calendarEl.appendChild(calendarBody);
        }

        prevMonthBtn.onclick = () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        };

        nextMonthBtn.onclick = () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        };

        renderCalendar();
    }

    function getAllTasks() {
        // Get all tasks from the page data
        const tasks = [];
        document.querySelectorAll('.task-card').forEach(card => {
            const dueDateEl = card.querySelector('.due-date');
            const priorityEl = card.querySelector('.priority-badge');

            tasks.push({
                id: card.dataset.id,
                title: card.querySelector('.task-title').textContent,
                due_date: dueDateEl ? dueDateEl.textContent.includes('M') ?
                    new Date().getFullYear() + '-' +
                    (new Date(dueDateEl.textContent.replace(/.*(\w{3} \d+).*/, '$1 ' + new Date().getFullYear())).getMonth() + 1).toString().padStart(2, '0') + '-' +
                    dueDateEl.textContent.replace(/.*(\d+).*/, '$1').padStart(2, '0') : null : null,
                priority: priorityEl ? priorityEl.textContent.toLowerCase() : 'medium'
            });
        });
        return tasks;
    }

    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
});
</script>

<!-- Quill.js CSS and JS for rich text editing -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
@endpush
