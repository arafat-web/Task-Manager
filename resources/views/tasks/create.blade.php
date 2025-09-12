@extends('layouts.app')

@section('title', 'Create New Task')

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

    .priority-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-top: 10px;
    }

    .priority-option {
        position: relative;
    }

    .priority-option input {
        position: absolute;
        opacity: 0;
    }

    .priority-option label {
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

    .priority-option input:checked + label {
        border-color: var(--primary-500);
        background: var(--primary-50);
        color: var(--primary-700);
        box-shadow: var(--shadow-sm);
    }

    .priority-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 8px;
        flex-shrink: 0;
    }

    .priority-low .priority-dot { background: var(--success-500); }
    .priority-medium .priority-dot { background: #f59e0b; }
    .priority-high .priority-dot { background: var(--error-500); }

    .form-actions {
        display: flex;
        gap: 14px;
        justify-content: flex-end;
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

    .content-header {
        margin-bottom: 32px;
        padding-bottom: 24px;
        border-bottom: 2px solid var(--gray-200);
    }

    .content-title {
        color: var(--gray-900);
        font-weight: 700;
        font-size: 28px;
        margin-bottom: 4px;
    }

    .content-subtitle {
        color: var(--gray-500);
        font-size: 16px;
        margin: 0;
        font-weight: 500;
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

        .priority-options {
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
