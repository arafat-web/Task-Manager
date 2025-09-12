@extends('layouts.app')

@section('title', 'Edit ' . $task->title)

@push('styles')
<style>
    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .form-card {
        background: white;
        border: 2px solid var(--gray-200);
        border-radius: 16px;
        box-shadow: var(--shadow-lg);
        overflow: hidden;
    }

    .form-header {
        background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
        padding: 32px;
        text-align: center;
        color: white;
        position: relative;
    }

    .form-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(20px, -20px);
    }

    .task-icon {
        width: 65px;
        height: 65px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin: 0 auto 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: var(--shadow-md);
        position: relative;
        z-index: 1;
    }

    .form-header h2 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        position: relative;
        z-index: 1;
    }

    .form-header p {
        margin: 8px 0 0;
        opacity: 0.9;
        font-size: 16px;
        position: relative;
        z-index: 1;
    }

    .form-body {
        padding: 32px;
    }

    .form-group {
        margin-bottom: 26px;
    }

    .form-label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        color: var(--gray-700);
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid var(--gray-200);
        border-radius: 10px;
        background: white;
        font-size: 14px;
        color: var(--gray-700);
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 4px var(--primary-100);
    }

    .form-control.is-invalid {
        border-color: var(--error-500);
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    .invalid-feedback {
        display: block;
        margin-top: 8px;
        font-size: 13px;
        color: var(--error-600);
        font-weight: 500;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .status-options, .priority-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-top: 10px;
    }

    .status-option, .priority-option {
        position: relative;
    }

    .status-option input, .priority-option input {
        position: absolute;
        opacity: 0;
    }

    .status-option label, .priority-option label {
        display: flex;
        align-items: center;
        padding: 14px 18px;
        border: 2px solid var(--gray-200);
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: white;
        font-weight: 600;
        font-size: 13px;
        justify-content: center;
    }

    .status-option input:checked + label, .priority-option input:checked + label {
        border-color: var(--primary-500);
        background: var(--primary-50);
        color: var(--primary-700);
        box-shadow: var(--shadow-sm);
    }

    .status-dot, .priority-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 8px;
        flex-shrink: 0;
    }

    .status-to-do .status-dot { background: var(--primary-500); }
    .status-in-progress .status-dot { background: #f59e0b; }
    .status-completed .status-dot { background: var(--success-500); }

    .priority-low .priority-dot { background: var(--success-500); }
    .priority-medium .priority-dot { background: #f59e0b; }
    .priority-high .priority-dot { background: var(--error-500); }

    .form-actions {
        display: flex;
        gap: 14px;
        justify-content: space-between;
        padding-top: 28px;
        border-top: 2px solid var(--gray-100);
        margin-top: 28px;
    }

    .btn-secondary {
        padding: 14px 26px;
        border: 2px solid var(--gray-200);
        background: white;
        color: var(--gray-700);
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
        font-size: 14px;
    }

    .btn-secondary:hover {
        border-color: var(--gray-300);
        background: var(--gray-50);
        color: var(--gray-800);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .btn-primary {
        padding: 14px 34px;
        background: var(--primary-600);
        border: 2px solid var(--primary-600);
        color: white;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.2s ease;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-primary:hover {
        background: var(--primary-700);
        border-color: var(--primary-700);
        transform: translateY(-1px);
        box-shadow: var(--shadow-lg);
    }

    .btn-danger {
        padding: 14px 26px;
        background: var(--error-500);
        border: 2px solid var(--error-500);
        color: white;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.2s ease;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-danger:hover {
        background: var(--error-600);
        border-color: var(--error-600);
        transform: translateY(-1px);
        box-shadow: var(--shadow-lg);
    }

    .input-icon {
        position: relative;
    }

    .input-icon .form-control {
        padding-left: 50px;
    }

    .input-icon i {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-400);
        z-index: 2;
    }

    /* Quill Editor Styling */
    .editor-container {
        border: 2px solid var(--gray-200);
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .editor-container:focus-within {
        border-color: var(--primary-500);
        box-shadow: 0 0 0 4px var(--primary-100);
    }

    .ql-toolbar {
        border: none;
        border-bottom: 1px solid var(--gray-200);
        background: var(--gray-50);
        padding: 12px;
    }

    .ql-container {
        border: none;
        font-family: inherit;
        font-size: 14px;
    }

    .ql-editor {
        min-height: 150px;
        padding: 18px;
        color: var(--gray-700);
        line-height: 1.6;
    }

    .ql-editor.ql-blank::before {
        color: var(--gray-400);
        font-style: normal;
        left: 18px;
        right: 18px;
    }

    .ql-snow .ql-tooltip {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        box-shadow: var(--shadow-lg);
    }

    .ql-snow .ql-picker-options {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        box-shadow: var(--shadow-lg);
    }

    .danger-zone {
        background: rgba(239, 68, 68, 0.05);
        border: 2px solid rgba(239, 68, 68, 0.2);
        border-radius: 14px;
        padding: 24px;
        margin-top: 36px;
    }

    .danger-zone h6 {
        color: var(--error-600);
        margin-bottom: 14px;
        font-weight: 700;
        font-size: 16px;
    }

    .danger-zone p {
        color: var(--gray-600);
        margin-bottom: 18px;
        font-size: 14px;
        line-height: 1.5;
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
        .form-row {
            grid-template-columns: 1fr;
        }

        .status-options, .priority-options {
            grid-template-columns: 1fr;
        }

        .form-body {
            padding: 24px 20px;
        }

        .form-header {
            padding: 24px 20px;
        }

        .form-actions {
            flex-direction: column;
        }

        .main-content {
            padding: 20px;
        }
    }
</style>
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
