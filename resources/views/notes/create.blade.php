@extends('layouts.app')

@section('title', 'Create Note')

@section('content')
<style>
    :root {
        --note-primary: #667eea;
        --note-secondary: #764ba2;
        --note-success: #10b981;
        --note-warning: #f59e0b;
        --note-danger: #ef4444;
        --note-info: #3b82f6;
        --note-light: #f8fafc;
        --note-dark: #1e293b;
        --note-gray: #64748b;
        --note-border: #e2e8f0;
        --note-shadow: rgba(0, 0, 0, 0.1);
        --note-shadow-lg: rgba(0, 0, 0, 0.15);
    }

    .create-header {
        background: linear-gradient(135deg, var(--note-primary) 0%, var(--note-secondary) 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px var(--note-shadow-lg);
    }

    .form-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--note-border);
        box-shadow: 0 4px 6px -1px var(--note-shadow);
        overflow: hidden;
    }

    .form-card-body {
        padding: 2rem;
    }

    .breadcrumb-modern {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px var(--note-shadow);
        border: 1px solid var(--note-border);
    }

    .breadcrumb-modern .breadcrumb {
        margin: 0;
    }

    .breadcrumb-modern .breadcrumb-item a {
        color: var(--note-primary);
        text-decoration: none;
        font-weight: 500;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--note-dark);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-control {
        border: 2px solid var(--note-border);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
        font-size: 1rem;
    }

    .form-control:focus {
        border-color: var(--note-primary);
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .form-select {
        border: 2px solid var(--note-border);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
    }

    .form-select:focus {
        border-color: var(--note-primary);
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .editor-container {
        border: 2px solid var(--note-border);
        border-radius: 8px;
        transition: border-color 0.2s ease;
    }

    .editor-container:focus-within {
        border-color: var(--note-primary);
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .ql-editor {
        min-height: 200px;
        font-size: 1rem;
        line-height: 1.6;
    }

    .tags-input {
        position: relative;
    }

    .tags-display {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        min-height: 38px;
        padding: 0.5rem;
        border: 2px solid var(--note-border);
        border-radius: 8px;
        cursor: text;
        transition: border-color 0.2s ease;
    }

    .tags-display:focus-within {
        border-color: var(--note-primary);
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .tag-item {
        background: var(--note-primary);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tag-remove {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 0.75rem;
        padding: 0;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .tag-remove:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .tag-input {
        border: none;
        outline: none;
        padding: 0.25rem 0.5rem;
        flex: 1;
        min-width: 120px;
        background: transparent;
    }

    .btn-primary {
        background: var(--note-primary);
        border-color: var(--note-primary);
        padding: 0.75rem 2rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background: var(--note-secondary);
        border-color: var(--note-secondary);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px var(--note-shadow);
    }

    .btn-outline-secondary {
        border: 2px solid var(--note-border);
        color: var(--note-gray);
        padding: 0.75rem 2rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .btn-outline-secondary:hover {
        background: var(--note-light);
        border-color: var(--note-gray);
        color: var(--note-dark);
    }

    .form-section {
        background: var(--note-light);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--note-border);
    }

    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--note-dark);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-help {
        font-size: 0.875rem;
        color: var(--note-gray);
        margin-top: 0.25rem;
    }

    .editor-container.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .validation-error {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .create-header {
            padding: 1.5rem;
        }

        .form-card-body {
            padding: 1.5rem;
        }
    }
</style>

<!-- Quill.js CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="create-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="mb-2">
                    <i class="fas fa-plus me-3"></i>Create New Note
                </h1>
                <p class="mb-0 opacity-90">Capture your thoughts and ideas with rich formatting</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('notes.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Back to Notes
                </a>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-card">
                <div class="form-card-body">
                    <form action="{{ route('notes.store') }}" method="POST" id="note-form">
                        @csrf

                        <!-- Basic Information Section -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-info-circle"></i>
                                Basic Information
                            </h3>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="title" class="form-label">
                                            <i class="fas fa-heading"></i>
                                            Note Title *
                                        </label>
                                        <input type="text"
                                               name="title"
                                               id="title"
                                               class="form-control @error('title') is-invalid @enderror"
                                               value="{{ old('title') }}"
                                               placeholder="Enter a descriptive title for your note"
                                               required>
                                        <div class="form-help">Give your note a clear, descriptive title</div>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="category" class="form-label">
                                            <i class="fas fa-folder"></i>
                                            Category
                                        </label>
                                        <input type="text"
                                               name="category"
                                               id="category"
                                               class="form-control @error('category') is-invalid @enderror"
                                               value="{{ old('category') }}"
                                               placeholder="e.g., Personal, Work, Ideas"
                                               list="categories-list">
                                        <datalist id="categories-list">
                                            @foreach($categories as $category)
                                                <option value="{{ $category }}">
                                            @endforeach
                                        </datalist>
                                        <div class="form-help">Organize your notes by category</div>
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-edit"></i>
                                Note Content
                            </h3>

                            <div class="form-group">
                                <label for="content" class="form-label">
                                    <i class="fas fa-align-left"></i>
                                    Content *
                                </label>
                                <div class="editor-container">
                                    <div id="editor"></div>
                                </div>
                                <textarea name="content" id="content" style="display: none;">{{ old('content') }}</textarea>
                                <div class="form-help">Use the rich text editor to format your note content</div>
                                @error('content')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Metadata Section -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-tags"></i>
                                Tags & Scheduling
                            </h3>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-tags"></i>
                                            Tags
                                        </label>
                                        <div class="tags-input">
                                            <div class="tags-display" id="tags-display">
                                                <input type="text"
                                                       class="tag-input"
                                                       id="tag-input"
                                                       placeholder="Type a tag and press Enter">
                                            </div>
                                            <input type="hidden" name="tags" id="tags-hidden">
                                        </div>
                                        <div class="form-help">Add tags to help organize and find your notes (press Enter to add)</div>
                                        @error('tags')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date" class="form-label">
                                            <i class="fas fa-calendar"></i>
                                            Date
                                        </label>
                                        <input type="date"
                                               name="date"
                                               id="date"
                                               class="form-control @error('date') is-invalid @enderror"
                                               value="{{ old('date') }}">
                                        <div class="form-help">Optional reference date</div>
                                        @error('date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="time" class="form-label">
                                            <i class="fas fa-clock"></i>
                                            Time
                                        </label>
                                        <input type="time"
                                               name="time"
                                               id="time"
                                               class="form-control @error('time') is-invalid @enderror"
                                               value="{{ old('time') }}">
                                        <div class="form-help">Optional reference time</div>
                                        @error('time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="is_favorite"
                                               id="is_favorite"
                                               value="1"
                                               {{ old('is_favorite') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_favorite">
                                            <i class="fas fa-star text-warning me-1"></i>
                                            Mark as Favorite
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('notes.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary" onclick="saveDraft()">
                                    <i class="fas fa-save me-2"></i>Save Draft
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Create Note
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quill.js JavaScript -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Quill editor
    const quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Start writing your note...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'align': [] }],
                ['blockquote', 'code-block'],
                ['link'],
                ['clean']
            ]
        }
    });

    // Set initial content if editing
    @if(old('content'))
        quill.root.innerHTML = `{!! addslashes(old('content')) !!}`;
    @endif

    // Tags functionality
    const tagInput = document.getElementById('tag-input');
    const tagsDisplay = document.getElementById('tags-display');
    const tagsHidden = document.getElementById('tags-hidden');
    let tags = [];

    tagInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addTag(this.value.trim());
            this.value = '';
        }
    });

    function addTag(tagText) {
        if (tagText && !tags.includes(tagText)) {
            tags.push(tagText);
            updateTagsDisplay();
            updateHiddenInput();
        }
    }

    function removeTag(tagText) {
        tags = tags.filter(tag => tag !== tagText);
        updateTagsDisplay();
        updateHiddenInput();
    }

    function updateTagsDisplay() {
        const tagElements = tags.map(tag => `
            <span class="tag-item">
                ${tag}
                <button type="button" class="tag-remove" onclick="removeTag('${tag}')">
                    <i class="fas fa-times"></i>
                </button>
            </span>
        `).join('');

        tagsDisplay.innerHTML = tagElements + '<input type="text" class="tag-input" id="tag-input" placeholder="Type a tag and press Enter">';

        // Re-attach event listener to new input
        const newTagInput = tagsDisplay.querySelector('.tag-input');
        newTagInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addTag(this.value.trim());
                this.value = '';
            }
        });
    }

    function updateHiddenInput() {
        tagsHidden.value = tags.join(',');
    }

    // Make removeTag function global
    window.removeTag = removeTag;

    // Form submission
    document.getElementById('note-form').addEventListener('submit', function(e) {
        // Update hidden content field with Quill content
        const contentField = document.getElementById('content');
        contentField.value = quill.root.innerHTML;

        // Basic validation
        const title = document.getElementById('title').value.trim();
        const content = quill.getText().trim();

        if (!title) {
            e.preventDefault();
            document.getElementById('title').focus();
            document.getElementById('title').classList.add('is-invalid');
            showValidationError('Please enter a title for your note.');
            return;
        } else {
            document.getElementById('title').classList.remove('is-invalid');
        }

        if (!content) {
            e.preventDefault();
            quill.focus();
            document.querySelector('.editor-container').style.borderColor = '#dc3545';
            showValidationError('Please enter some content for your note.');
            return;
        } else {
            document.querySelector('.editor-container').style.borderColor = '';
        }
    });

    function showValidationError(message) {
        // Remove existing error message
        const existingError = document.querySelector('.validation-error');
        if (existingError) {
            existingError.remove();
        }

        // Create and show error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger validation-error mt-3';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${message}`;

        const formCard = document.querySelector('.form-card-body');
        formCard.insertBefore(errorDiv, formCard.firstChild);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.remove();
            }
        }, 5000);

        // Scroll to top of form
        document.querySelector('.form-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // Auto-save functionality (save draft)
    let autoSaveTimeout;

    function autoSave() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            saveDraft();
        }, 30000); // Auto-save every 30 seconds
    }

    quill.on('text-change', autoSave);
    document.getElementById('title').addEventListener('input', autoSave);

    // Save draft function
    window.saveDraft = function() {
        const draftData = {
            title: document.getElementById('title').value,
            content: quill.root.innerHTML,
            category: document.getElementById('category').value,
            tags: tags.join(','),
            date: document.getElementById('date').value,
            time: document.getElementById('time').value,
            is_favorite: document.getElementById('is_favorite').checked
        };

        localStorage.setItem('note_draft', JSON.stringify(draftData));

        // Show toast notification
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-info border-0 position-fixed';
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-save me-2"></i>Draft saved successfully!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        document.body.appendChild(toast);

        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        toast.addEventListener('hidden.bs.toast', () => {
            document.body.removeChild(toast);
        });
    };

    // Load draft on page load
    const savedDraft = localStorage.getItem('note_draft');
    if (savedDraft && !document.getElementById('title').value) {
        const draftData = JSON.parse(savedDraft);

        if (confirm('A draft was found. Would you like to restore it?')) {
            document.getElementById('title').value = draftData.title || '';
            quill.root.innerHTML = draftData.content || '';
            document.getElementById('category').value = draftData.category || '';
            document.getElementById('date').value = draftData.date || '';
            document.getElementById('time').value = draftData.time || '';
            document.getElementById('is_favorite').checked = draftData.is_favorite || false;

            if (draftData.tags) {
                tags = draftData.tags.split(',').filter(tag => tag.trim());
                updateTagsDisplay();
                updateHiddenInput();
            }
        }
    }

    // Clear draft when form is successfully submitted
    document.getElementById('note-form').addEventListener('submit', function() {
        localStorage.removeItem('note_draft');
    });
});
</script>
@endsection
