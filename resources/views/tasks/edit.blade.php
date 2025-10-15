@extends('layouts.app')

@section('title', 'Edit ' . $task->title)

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/tasks/style.css') }}">
@endpush


@section('content')
<div class="main-content">
    <!-- Back Link -->
    <a href="{{ route('tasks.show', $task->id) }}" class="back-link">
        <i class="bi bi-arrow-left"></i>
        Back to Task Details
    </a>

    <div class="form-container">
        <div class="form-card">
            <div class="form-header">
                <div class="task-icon">
                    <i class="bi bi-pencil"></i>
                </div>
                <h2>Edit Task</h2>
                <p>Update task details and requirements</p>
            </div>

            <div class="form-body">
                <form action="{{ route('tasks.update', $task->id) }}" method="POST" id="editTaskForm">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="title" class="form-label">Task Title *</label>
                        <div class="input-icon">
                            <i class="bi bi-card-text"></i>
                            <input type="text"
                                   name="title"
                                   id="title"
                                   class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                   value="{{ old('title', $task->title) }}"
                                   placeholder="Enter task title"
                                   required>
                        </div>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <div class="editor-container">
                            <div id="quill-editor" style="height: 150px;"></div>
                            <textarea name="description" id="description" style="display: none;">{{ old('description', $task->description) }}</textarea>
                        </div>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="due_date" class="form-label">Due Date</label>
                            <div class="input-icon">
                                <i class="bi bi-calendar-event"></i>
                                <input type="date"
                                       name="due_date"
                                       id="due_date"
                                       class="form-control {{ $errors->has('due_date') ? 'is-invalid' : '' }}"
                                       value="{{ old('due_date', $task->due_date) }}">
                            </div>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="estimated_hours" class="form-label">Estimated Hours</label>
                            <div class="input-icon">
                                <i class="bi bi-clock"></i>
                                <input type="number"
                                       name="estimated_hours"
                                       id="estimated_hours"
                                       class="form-control {{ $errors->has('estimated_hours') ? 'is-invalid' : '' }}"
                                       value="{{ old('estimated_hours', $task->estimated_hours) }}"
                                       min="0.5"
                                       step="0.5"
                                       placeholder="2.5">
                            </div>
                            @error('estimated_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Task Status *</label>
                        <div class="status-options">
                            <div class="status-option">
                                <input type="radio"
                                       name="status"
                                       id="status_to_do"
                                       value="to_do"
                                       {{ old('status', $task->status) == 'to_do' ? 'checked' : '' }}>
                                <label for="status_to_do" class="status-to-do">
                                    <span class="status-dot"></span>
                                    To Do
                                </label>
                            </div>

                            <div class="status-option">
                                <input type="radio"
                                       name="status"
                                       id="status_in_progress"
                                       value="in_progress"
                                       {{ old('status', $task->status) == 'in_progress' ? 'checked' : '' }}>
                                <label for="status_in_progress" class="status-in-progress">
                                    <span class="status-dot"></span>
                                    In Progress
                                </label>
                            </div>

                            <div class="status-option">
                                <input type="radio"
                                       name="status"
                                       id="status_completed"
                                       value="completed"
                                       {{ old('status', $task->status) == 'completed' ? 'checked' : '' }}>
                                <label for="status_completed" class="status-completed">
                                    <span class="status-dot"></span>
                                    Completed
                                </label>
                            </div>
                        </div>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Priority *</label>
                        <div class="priority-options">
                            <div class="priority-option">
                                <input type="radio"
                                       name="priority"
                                       id="priority_low"
                                       value="low"
                                       {{ old('priority', $task->priority) == 'low' ? 'checked' : '' }}>
                                <label for="priority_low" class="priority-low">
                                    <span class="priority-dot"></span>
                                    Low Priority
                                </label>
                            </div>

                            <div class="priority-option">
                                <input type="radio"
                                       name="priority"
                                       id="priority_medium"
                                       value="medium"
                                       {{ old('priority', $task->priority) == 'medium' ? 'checked' : '' }}>
                                <label for="priority_medium" class="priority-medium">
                                    <span class="priority-dot"></span>
                                    Medium Priority
                                </label>
                            </div>

                            <div class="priority-option">
                                <input type="radio"
                                       name="priority"
                                       id="priority_high"
                                       value="high"
                                       {{ old('priority', $task->priority) == 'high' ? 'checked' : '' }}>
                                <label for="priority_high" class="priority-high">
                                    <span class="priority-dot"></span>
                                    High Priority
                                </label>
                            </div>
                        </div>
                        @error('priority')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <div>
                            <a href="{{ route('tasks.show', $task->id) }}" class="btn-secondary">
                                Cancel
                            </a>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Update Task
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Danger Zone -->
                <div class="danger-zone">
                    <h6><i class="bi bi-exclamation-triangle me-2"></i>Danger Zone</h6>
                    <p>Permanently delete this task. This action cannot be undone.</p>
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn-danger" onclick="confirmDelete()">
                            <i class="bi bi-trash me-2"></i>Delete Task
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
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Quill editor
    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Describe the task requirements, goals, and any specific instructions...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'header': 1 }, { 'header': 2 }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                [{ 'color': [] }, { 'background': [] }],
                ['clean']
            ]
        }
    });

    // Set initial content if available
    var initialContent = document.getElementById('description').value;
    if (initialContent) {
        quill.root.innerHTML = initialContent;
    }

    // Update hidden textarea when content changes
    quill.on('text-change', function() {
        document.getElementById('description').value = quill.root.innerHTML;
    });

    // Update hidden textarea before form submission
    document.querySelector('#editTaskForm').addEventListener('submit', function() {
        document.getElementById('description').value = quill.root.innerHTML;
    });

    // Form validation
    const form = document.getElementById('editTaskForm');
    const dueDateInput = document.getElementById('due_date');

    form.addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();

        if (!title) {
            e.preventDefault();
            alert('Please enter a task title.');
            return;
        }
    });
});

function confirmDelete() {
    if (confirm('Are you sure you want to delete this task? This action cannot be undone.')) {
        if (confirm('This is your final warning. Are you absolutely sure you want to permanently delete this task?')) {
            document.getElementById('deleteForm').submit();
        }
    }
}
</script>

<!-- Quill.js CSS and JS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
@endpush
