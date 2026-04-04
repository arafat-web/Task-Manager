@extends('layouts.app')

@section('title', 'Create New Task')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/tasks/style.css') }}">
@endpush

@section('content')
<div class="main-content">
    <!-- Back Link -->
    <a href="{{ route('tasks.index') }}" class="back-link">
        <i class="bi bi-arrow-left"></i>
        Back to Tasks
    </a>

    <div class="form-container">
        <div class="form-card">
            <div class="form-header">
                <div class="task-icon">
                    <i class="bi bi-plus-circle"></i>
                </div>
                <h2>Create New Task</h2>
                <p>Define task requirements and assign to team members</p>
            </div>

            <div class="form-body">
                <form action="{{ route('tasks.store') }}" method="POST" id="createTaskForm">
                    @csrf

                    <div class="form-group">
                        <label for="title" class="form-label">Task Title *</label>
                        <div class="input-icon">
                            <i class="bi bi-card-text"></i>
                            <input type="text"
                                   name="title"
                                   id="title"
                                   class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                   value="{{ old('title') }}"
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
                            <textarea name="description" id="description" style="display: none;">{{ old('description') }}</textarea>
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
                                       value="{{ old('due_date') }}">
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
                                       value="{{ old('estimated_hours') }}"
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
                        <label class="form-label">Priority *</label>
                        <div class="priority-options">
                            <div class="priority-option">
                                <input type="radio"
                                       name="priority"
                                       id="priority_low"
                                       value="low"
                                       {{ old('priority') == 'low' ? 'checked' : '' }}>
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
                                       {{ old('priority') == 'medium' || !old('priority') ? 'checked' : '' }}>
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
                                       {{ old('priority') == 'high' ? 'checked' : '' }}>
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
                        <a href="{{ route('tasks.index') }}" class="btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Create Task
                        </button>
                    </div>
                </form>
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
    document.querySelector('#createTaskForm').addEventListener('submit', function() {
        document.getElementById('description').value = quill.root.innerHTML;
    });

    // Form validation
    const form = document.getElementById('createTaskForm');
    const dueDateInput = document.getElementById('due_date');

    form.addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();

        if (!title) {
            e.preventDefault();
            alert('Please enter a task title.');
            return;
        }

        // Check if due date is not in the past
        if (dueDateInput.value) {
            const dueDate = new Date(dueDateInput.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (dueDate < today) {
                e.preventDefault();
                alert('Due date cannot be in the past.');
                return;
            }
        }
    });

    // Set minimum date for due date input to today
    const today = new Date().toISOString().split('T')[0];
    dueDateInput.min = today;
});
</script>

<!-- Quill.js CSS and JS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
@endpush
