@extends('layouts.app')

@section('title', 'Files')

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

    .files-header {
        background: linear-gradient(135deg, var(--file-primary) 0%, var(--file-secondary) 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px var(--file-shadow-lg);
    }

    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        border: 1px solid var(--file-border);
        box-shadow: 0 4px 6px -1px var(--file-shadow);
        transition: all 0.2s ease;
        height: 100%;
    }

    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px var(--file-shadow-lg);
    }

    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
        color: white;
    }

    .stats-icon.total { background: var(--file-primary); }
    .stats-icon.project { background: var(--file-info); }
    .stats-icon.docs { background: var(--file-warning); }
    .stats-icon.code { background: var(--file-danger); }

    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--file-dark);
        margin-bottom: 0.5rem;
    }

    .stats-label {
        color: var(--file-gray);
        font-weight: 500;
    }

    .file-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--file-border);
        box-shadow: 0 4px 6px -1px var(--file-shadow);
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
    }

    .file-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px var(--file-shadow-lg);
        border-color: var(--file-primary);
    }

    .file-card-header {
        padding: 1.5rem 1.5rem 1rem;
        border-bottom: 1px solid var(--file-border);
    }

    .file-card-body {
        padding: 1rem 1.5rem 1.5rem;
    }

    .file-type-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .file-type-badge.project { background: rgba(59, 130, 246, 0.1); color: var(--file-info); }
    .file-type-badge.docs { background: rgba(245, 158, 11, 0.1); color: var(--file-warning); }
    .file-type-badge.txt { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
    .file-type-badge.code { background: rgba(239, 68, 68, 0.1); color: var(--file-danger); }
    .file-type-badge.image { background: rgba(34, 197, 94, 0.1); color: var(--file-success); }

    .file-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .btn-modern {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        text-decoration: none;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .btn-modern.btn-primary {
        background: var(--file-primary);
        color: white;
    }

    .btn-modern.btn-primary:hover {
        background: var(--file-secondary);
        color: white;
    }

    .btn-modern.btn-warning {
        background: var(--file-warning);
        color: white;
    }

    .btn-modern.btn-warning:hover {
        background: #f59e0b;
        color: white;
    }

    .btn-modern.btn-danger {
        background: var(--file-danger);
        color: white;
    }

    .btn-modern.btn-danger:hover {
        background: #dc2626;
        color: white;
    }

    .file-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        margin-bottom: 1rem;
    }

    .file-icon.project { background: var(--file-info); }
    .file-icon.docs { background: var(--file-warning); }
    .file-icon.txt { background: #8b5cf6; }
    .file-icon.code { background: var(--file-danger); }
    .file-icon.image { background: var(--file-success); }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--file-gray);
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    .alert-modern {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid var(--file-success);
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="files-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-2">
                    <i class="fas fa-folder-open me-3"></i>File Manager
                </h1>
                <p class="mb-0 opacity-75">Manage and organize your uploaded files</p>
            </div>
            <a href="{{ route('files.create') }}" class="btn btn-light btn-lg">
                <i class="fas fa-plus me-2"></i>Upload New File
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-modern">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon total">
                    <i class="fas fa-file"></i>
                </div>
                <div class="stats-number">{{ $files->count() }}</div>
                <div class="stats-label">Total Files</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon project">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="stats-number">{{ $files->where('type', 'project')->count() }}</div>
                <div class="stats-label">Project Files</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon docs">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stats-number">{{ $files->where('type', 'docs')->count() }}</div>
                <div class="stats-label">Documents</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon code">
                    <i class="fas fa-code"></i>
                </div>
                <div class="stats-number">{{ $files->whereIn('type', ['code', 'txt'])->count() }}</div>
                <div class="stats-label">Code & Text</div>
            </div>
        </div>
    </div>

    <!-- Files Grid -->
    @if($files->count() > 0)
        <div class="row">
            @foreach($files as $file)
                <div class="col-xl-4 col-lg-6 mb-4">
                    <div class="file-card">
                        <div class="file-card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="file-icon {{ $file->type }}">
                                    @switch($file->type)
                                        @case('project')
                                            <i class="fas fa-project-diagram"></i>
                                            @break
                                        @case('docs')
                                            <i class="fas fa-file-alt"></i>
                                            @break
                                        @case('txt')
                                            <i class="fas fa-file-text"></i>
                                            @break
                                        @case('code')
                                            <i class="fas fa-code"></i>
                                            @break
                                        @case('image')
                                            <i class="fas fa-image"></i>
                                            @break
                                        @default
                                            <i class="fas fa-file"></i>
                                    @endswitch
                                </div>
                                <span class="file-type-badge {{ $file->type }}">{{ ucfirst($file->type) }}</span>
                            </div>
                            <h5 class="mb-2 mt-2" style="color: var(--file-dark);">{{ Str::limit($file->name, 30) }}</h5>
                        </div>
                        <div class="file-card-body">
                            <p class="text-muted small mb-2">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $file->created_at->format('M d, Y') }}
                            </p>
                            <div class="file-actions">
                                <a href="{{ Storage::url($file->path) }}" target="_blank" class="btn-modern btn-primary" title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="{{ route('files.edit', $file->id) }}" class="btn-modern btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('files.destroy', $file->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this file?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-modern btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-folder-open"></i>
            </div>
            <h3 style="color: var(--file-dark);">No Files Found</h3>
            <p class="mb-4">Start by uploading your first file to get organized!</p>
            <a href="{{ route('files.create') }}" class="btn-modern btn-primary">
                <i class="fas fa-plus me-2"></i>Upload First File
            </a>
        </div>
    @endif
</div>

@endsection
