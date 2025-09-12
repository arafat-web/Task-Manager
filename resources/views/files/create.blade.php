@extends('layouts.app')

@section('title', 'Upload File')

@section('content')
<style>
    :root {
        --file-primary: #059669;
        --file-secondary: #10b981;
        --file-success: #22c55e;
        --file-warning: #f59e0b;
        --file-danger: #ef4444;
        --file-info: #3b82f6;
        --file-light: #f0fdf4;
        --file-dark: #1e293b;
        --file-gray: #64748b;
        --file-border: #e2e8f0;
        --file-shadow: rgba(0, 0, 0, 0.1);
        --file-shadow-lg: rgba(0, 0, 0, 0.15);
    }

    .upload-header {
        background: linear-gradient(135deg, var(--file-primary) 0%, var(--file-secondary) 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px var(--file-shadow-lg);
    }

    .form-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--file-border);
        box-shadow: 0 4px 6px -1px var(--file-shadow);
        overflow: hidden;
        max-width: 700px;
        margin: 0 auto;
    }

    .form-card-body {
        padding: 2rem;
    }

    .breadcrumb-modern {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px var(--file-shadow);
        border: 1px solid var(--file-border);
    }

    .breadcrumb-modern .breadcrumb {
        margin: 0;
        background: none;
        padding: 0;
    }

    .breadcrumb-modern .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: var(--file-gray);
        font-weight: 600;
    }

    .form-group-modern {
        margin-bottom: 1.5rem;
    }

    .form-label-modern {
        font-weight: 600;
        color: var(--file-dark);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-control-modern {
        border: 1px solid var(--file-border);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
        background: white;
        width: 100%;
    }

    .form-control-modern:focus {
        border-color: var(--file-primary);
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        outline: none;
    }

    .file-upload-area {
        border: 2px dashed var(--file-border);
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        transition: all 0.2s ease;
        background: var(--file-light);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .file-upload-area:hover {
        border-color: var(--file-primary);
        background: rgba(5, 150, 105, 0.05);
    }

    .file-upload-area.dragover {
        border-color: var(--file-primary);
        background: rgba(5, 150, 105, 0.1);
        transform: scale(1.02);
    }

    .file-upload-icon {
        font-size: 3rem;
        color: var(--file-primary);
        margin-bottom: 1rem;
    }

    .file-upload-text {
        color: var(--file-dark);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .file-upload-hint {
        color: var(--file-gray);
        font-size: 0.875rem;
    }

    .file-input-hidden {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .form-select-modern {
        border: 1px solid var(--file-border);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        background: white;
        transition: all 0.2s ease;
        width: 100%;
    }

    .form-select-modern:focus {
        border-color: var(--file-primary);
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        outline: none;
    }

    .type-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .type-option {
        border: 2px solid var(--file-border);
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: white;
    }

    .type-option:hover {
        border-color: var(--file-primary);
        background: rgba(5, 150, 105, 0.05);
    }

    .type-option.selected {
        border-color: var(--file-primary);
        background: rgba(5, 150, 105, 0.1);
    }

    .type-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .type-icon.project { color: var(--file-info); }
    .type-icon.docs { color: var(--file-warning); }
    .type-icon.txt { color: #8b5cf6; }
    .type-icon.code { color: var(--file-danger); }
    .type-icon.image { color: var(--file-success); }

    .type-label {
        font-weight: 600;
        color: var(--file-dark);
        font-size: 0.875rem;
    }

    .action-buttons {
        background: var(--file-light);
        padding: 1.5rem 2rem;
        border-top: 1px solid var(--file-border);
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    .btn-modern {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-modern.btn-primary {
        background: var(--file-primary);
        color: white;
    }

    .btn-modern.btn-primary:hover {
        background: var(--file-secondary);
        transform: translateY(-1px);
        color: white;
    }

    .btn-modern.btn-secondary {
        background: var(--file-gray);
        color: white;
    }

    .btn-modern.btn-secondary:hover {
        background: var(--file-dark);
        transform: translateY(-1px);
        color: white;
    }

    .selected-file {
        background: rgba(5, 150, 105, 0.1);
        border: 1px solid var(--file-primary);
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
        display: none;
    }

    .selected-file.show {
        display: block;
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="upload-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-2">
                    <i class="fas fa-cloud-upload-alt me-3"></i>Upload File
                </h1>
                <p class="mb-0 opacity-75">Upload and organize your files with ease</p>
            </div>
            <a href="{{ route('files.index') }}" class="btn btn-light btn-lg">
                <i class="fas fa-arrow-left me-2"></i>Back to Files
            </a>
        </div>
    </div>

    <!-- Breadcrumb -->
    <nav class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('files.index') }}" style="color: var(--file-primary);">Files</a></li>
            <li class="breadcrumb-item active">Upload New File</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data" id="upload-form">
                @csrf

                <div class="form-card">
                    <div class="form-card-body">
                        <!-- File Name -->
                        <div class="form-group-modern">
                            <label for="name" class="form-label-modern">
                                <i class="fas fa-tag" style="color: var(--file-primary);"></i>
                                File Name
                                <span style="color: var(--file-danger);">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-control-modern @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Enter a descriptive name for your file..."
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File Upload -->
                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <i class="fas fa-file-upload" style="color: var(--file-primary);"></i>
                                Choose File
                                <span style="color: var(--file-danger);">*</span>
                            </label>
                            <div class="file-upload-area" id="file-upload-area">
                                <div class="file-upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="file-upload-text">Drag and drop your file here</div>
                                <div class="file-upload-hint">or click to browse files</div>
                                <input type="file"
                                       name="file"
                                       id="file"
                                       class="file-input-hidden @error('file') is-invalid @enderror"
                                       required>
                            </div>
                            <div class="selected-file" id="selected-file">
                                <i class="fas fa-file me-2" style="color: var(--file-primary);"></i>
                                <span id="selected-filename"></span>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="clearFile()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File Type -->
                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <i class="fas fa-list" style="color: var(--file-primary);"></i>
                                File Type
                                <span style="color: var(--file-danger);">*</span>
                            </label>
                            <input type="hidden" name="type" id="type" value="{{ old('type', 'project') }}">
                            <div class="type-options">
                                <div class="type-option selected" data-type="project">
                                    <div class="type-icon project">
                                        <i class="fas fa-project-diagram"></i>
                                    </div>
                                    <div class="type-label">Project</div>
                                </div>
                                <div class="type-option" data-type="docs">
                                    <div class="type-icon docs">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="type-label">Documents</div>
                                </div>
                                <div class="type-option" data-type="txt">
                                    <div class="type-icon txt">
                                        <i class="fas fa-file-text"></i>
                                    </div>
                                    <div class="type-label">Text</div>
                                </div>
                                <div class="type-option" data-type="code">
                                    <div class="type-icon code">
                                        <i class="fas fa-code"></i>
                                    </div>
                                    <div class="type-label">Code</div>
                                </div>
                                <div class="type-option" data-type="image">
                                    <div class="type-icon image">
                                        <i class="fas fa-image"></i>
                                    </div>
                                    <div class="type-label">Image</div>
                                </div>
                            </div>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="{{ route('files.index') }}" class="btn-modern btn-secondary">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn-modern btn-primary">
                            <i class="fas fa-cloud-upload-alt"></i>
                            Upload File
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const fileUploadArea = document.getElementById('file-upload-area');
    const selectedFile = document.getElementById('selected-file');
    const selectedFilename = document.getElementById('selected-filename');
    const typeOptions = document.querySelectorAll('.type-option');
    const typeInput = document.getElementById('type');

    // File upload area interactions
    fileUploadArea.addEventListener('click', () => fileInput.click());

    fileUploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        fileUploadArea.classList.add('dragover');
    });

    fileUploadArea.addEventListener('dragleave', () => {
        fileUploadArea.classList.remove('dragover');
    });

    fileUploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        fileUploadArea.classList.remove('dragover');
        fileInput.files = e.dataTransfer.files;
        handleFileSelect();
    });

    // File input change
    fileInput.addEventListener('change', handleFileSelect);

    // Type option selection
    typeOptions.forEach(option => {
        option.addEventListener('click', () => {
            typeOptions.forEach(opt => opt.classList.remove('selected'));
            option.classList.add('selected');
            typeInput.value = option.dataset.type;
        });
    });

    function handleFileSelect() {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            selectedFilename.textContent = file.name;
            selectedFile.classList.add('show');
            fileUploadArea.style.display = 'none';
        }
    }

    window.clearFile = function() {
        fileInput.value = '';
        selectedFile.classList.remove('show');
        fileUploadArea.style.display = 'block';
    };
});
</script>
@endpush
