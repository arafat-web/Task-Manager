@extends('layouts.app')

@section('title', isset($project) ? $project->name . ' Tasks' : 'My Tasks')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/tasks/style.css') }}">
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
        </div>

        <div class="task-filters">
            <select class="filter-select" id="priorityFilter">
                <option value="">All Priorities</option>
                <option value="high">High Priority</option>
                <option value="medium">Medium Priority</option>
                <option value="low">Low Priority</option>
            </select>
            {{-- <select class="filter-select" id="assigneeFilter">
                <option value="">All Assignees</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select> --}}
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
</div>

@endif
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

    viewToggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const view = this.getAttribute('data-view');

            // Update active button
            viewToggleButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Show/hide views
            kanbanView.style.display = view === 'kanban' ? 'grid' : 'none';
            listView.style.display = view === 'list' ? 'block' : 'none';

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

    // Add event listeners with null checks
    if (priorityFilter) {
        priorityFilter.addEventListener('change', applyFilters);
    }
    if (assigneeFilter) {
        assigneeFilter.addEventListener('change', applyFilters);
    }

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

    // Only add event listener if modal exists
    if (createTaskModal) {
        createTaskModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const status = button?.getAttribute('data-status') || 'to_do';

            if (taskStatusInput) {
                taskStatusInput.value = status;
            }

            // Initialize Quill editor when modal opens
            setTimeout(initializeQuillEditor, 100);
        });
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
