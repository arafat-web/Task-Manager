@extends('layouts.app')

@section('title', 'Edit ' . $project->name)

@push('styles')
<style>
    .form-container {
        max-width: 700px;
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

    .project-avatar-edit {
        width: 65px;
        height: 65px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 26px;
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

    .status-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 14px;
        margin-top: 10px;
    }

    .status-option {
        position: relative;
    }

    .status-option input {
        position: absolute;
        opacity: 0;
    }

    .status-option label {
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
    }

    .status-option input:checked + label {
        border-color: var(--primary-500);
        background: var(--primary-50);
        color: var(--primary-700);
        box-shadow: var(--shadow-sm);
    }

    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 10px;
        flex-shrink: 0;
    }

    .status-not-started .status-dot { background: var(--gray-500); }
    .status-in-progress .status-dot { background: var(--primary-500); }
    .status-completed .status-dot { background: var(--success-500); }

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
        min-height: 200px;
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

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .form-body {
            padding: 24px 20px;
        }

        .form-header {
            padding: 24px 20px;
        }

        .status-options {
            grid-template-columns: 1fr;
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
    <div class="content-header">
        <div class="d-flex align-items-center">
            <a href="{{ route('projects.show', $project->id) }}" class="me-3 text-decoration-none">
                <i class="bi bi-arrow-left fs-4 text-secondary"></i>
            </a>
            <div>
                <h1 class="content-title mb-1">Edit Project</h1>
                <p class="content-subtitle">Update your project information and settings</p>
            </div>
        </div>
    </div>

    <div class="form-container">
        <div class="form-card">
            <div class="form-header">
                @php
                    $colors = ['#6366f1', '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#ec4899'];
                    $colorIndex = strlen($project->name) % count($colors);
                    $projectColor = $colors[$colorIndex];
                @endphp

                <div class="project-avatar-edit">
                    {{ strtoupper(substr($project->name, 0, 1)) }}
                </div>
                <h2>{{ $project->name }}</h2>
                <p>Update project details and configuration</p>
            </div>

            <div class="form-body">
                <form action="{{ route('projects.update', $project->id) }}" method="POST" id="editProjectForm">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name" class="form-label">Project Name *</label>
                        <div class="input-icon">
                            <i class="bi bi-folder"></i>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   value="{{ old('name', $project->name) }}"
                                   placeholder="Enter project name"
                                   required>
                        </div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <div class="editor-container">
                            <div id="quill-editor" style="height: 200px;"></div>
                            <textarea name="description" id="description" style="display: none;">{{ old('description', $project->description) }}</textarea>
                        </div>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date" class="form-label">Start Date</label>
                            <div class="input-icon">
                                <i class="bi bi-calendar-event"></i>
                                <input type="date"
                                       name="start_date"
                                       id="start_date"
                                       class="form-control {{ $errors->has('start_date') ? 'is-invalid' : '' }}"
                                       value="{{ old('start_date', $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '') }}">
                            </div>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="end_date" class="form-label">End Date</label>
                            <div class="input-icon">
                                <i class="bi bi-calendar-x"></i>
                                <input type="date"
                                       name="end_date"
                                       id="end_date"
                                       class="form-control {{ $errors->has('end_date') ? 'is-invalid' : '' }}"
                                       value="{{ old('end_date', $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : '') }}">
                            </div>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="budget" class="form-label">Budget</label>
                        <div class="input-icon">
                            <i class="bi bi-currency-dollar"></i>
                            <input type="number"
                                   name="budget"
                                   id="budget"
                                   class="form-control {{ $errors->has('budget') ? 'is-invalid' : '' }}"
                                   value="{{ old('budget', $project->budget) }}"
                                   step="0.01"
                                   min="0"
                                   placeholder="0.00">
                        </div>
                        @error('budget')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Project Status *</label>
                        <div class="status-options">
                            <div class="status-option">
                                <input type="radio"
                                       name="status"
                                       id="status_not_started"
                                       value="not_started"
                                       {{ old('status', $project->status) == 'not_started' ? 'checked' : '' }}>
                                <label for="status_not_started" class="status-not-started">
                                    <span class="status-dot"></span>
                                    Not Started
                                </label>
                            </div>

                            <div class="status-option">
                                <input type="radio"
                                       name="status"
                                       id="status_in_progress"
                                       value="in_progress"
                                       {{ old('status', $project->status) == 'in_progress' ? 'checked' : '' }}>
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
                                       {{ old('status', $project->status) == 'completed' ? 'checked' : '' }}>
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

                    <div class="form-actions">
                        <a href="{{ route('projects.show', $project->id) }}" class="btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Update Project
                        </button>
                    </div>
                </form>

                <!-- Danger Zone -->
                <div class="danger-zone">
                    <h6><i class="bi bi-exclamation-triangle me-2"></i>Danger Zone</h6>
                    <p>Permanently delete this project and all associated data. This action cannot be undone.</p>
                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn-danger" onclick="confirmDelete()">
                            <i class="bi bi-trash me-2"></i>Delete Project
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
    const form = document.getElementById('editProjectForm');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    // Auto-set minimum end date based on start date
    startDateInput.addEventListener('change', function() {
        if (this.value) {
            endDateInput.min = this.value;
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = '';
            }
        }
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();

        if (!name) {
            e.preventDefault();
            alert('Please enter a project name.');
            return;
        }

        if (startDateInput.value && endDateInput.value) {
            if (new Date(endDateInput.value) < new Date(startDateInput.value)) {
                e.preventDefault();
                alert('End date cannot be before start date.');
                return;
            }
        }
    });

    // Initialize Quill editor
    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Describe your project goals, objectives, and key deliverables...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'header': 1 }, { 'header': 2 }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                ['link'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
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
    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('description').value = quill.root.innerHTML;
    });
});

function confirmDelete() {
    if (confirm('Are you sure you want to delete this project? This action cannot be undone and will remove all associated tasks, files, and data.')) {
        if (confirm('This is your final warning. Are you absolutely sure you want to permanently delete this project?')) {
            document.getElementById('deleteForm').submit();
        }
    }
}
</script>

<!-- Quill.js CSS and JS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
@endpush
