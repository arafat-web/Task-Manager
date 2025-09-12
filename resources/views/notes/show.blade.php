@extends('layouts.app')

@section('title', $note->title)

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

    .note-header {
        background: linear-gradient(135deg, var(--note-primary) 0%, var(--note-secondary) 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px var(--note-shadow-lg);
    }

    .note-content-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--note-border);
        box-shadow: 0 4px 6px -1px var(--note-shadow);
        overflow: hidden;
    }

    .note-content-body {
        padding: 2rem;
        line-height: 1.8;
        color: var(--note-dark);
    }

    .note-meta-sidebar {
        background: var(--note-light);
        padding: 1.5rem;
        border-left: 1px solid var(--note-border);
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

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .action-btn {
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px var(--note-shadow);
    }

    .note-category-badge {
        background: var(--note-primary);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-block;
    }

    .note-tag {
        background: var(--note-light);
        color: var(--note-gray);
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        border: 1px solid var(--note-border);
        display: inline-block;
        margin: 0.25rem 0.25rem 0.25rem 0;
    }

    .meta-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--note-border);
    }

    .meta-item:last-child {
        border-bottom: none;
    }

    .meta-label {
        font-weight: 600;
        color: var(--note-gray);
        font-size: 0.875rem;
    }

    .meta-value {
        color: var(--note-dark);
        font-weight: 500;
    }

    .favorite-btn {
        background: none;
        border: none;
        color: var(--note-gray);
        font-size: 1.25rem;
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 0.5rem;
        border-radius: 50%;
    }

    .favorite-btn.active {
        color: var(--note-warning);
        transform: scale(1.1);
    }

    .favorite-btn:hover {
        background: rgba(245, 158, 11, 0.1);
    }

    @media (max-width: 768px) {
        .note-header {
            padding: 1.5rem;
        }

        .note-content-body {
            padding: 1.5rem;
        }

        .note-meta-sidebar {
            padding: 1.5rem;
            border-left: none;
            border-top: 1px solid var(--note-border);
        }

        .action-buttons {
            justify-content: center;
        }
    }
</style>

<div class="container-fluid px-4">
    <!-- Breadcrumb -->
    <div class="breadcrumb-modern">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home me-1"></i>Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('notes.index') }}">
                        <i class="fas fa-sticky-note me-1"></i>Notes
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ Str::limit($note->title, 30) }}
                </li>
            </ol>
        </nav>
    </div>

    <!-- Note Header -->
    <div class="note-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center mb-3">
                    <h1 class="mb-0 me-3">{{ $note->title }}</h1>
                    <button class="favorite-btn {{ $note->is_favorite ? 'active' : '' }}"
                            data-note-id="{{ $note->id }}" title="Toggle Favorite">
                        <i class="fas fa-star"></i>
                    </button>
                </div>

                @if($note->category)
                    <div class="mb-3">
                        <span class="note-category-badge">{{ $note->category }}</span>
                    </div>
                @endif

                @if($note->tags && count($note->tags) > 0)
                    <div class="mb-3">
                        @foreach($note->tags as $tag)
                            <span class="note-tag">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                <p class="mb-0 opacity-90">
                    <i class="fas fa-clock me-2"></i>
                    Created {{ $note->created_at->diffForHumans() }}
                    @if($note->updated_at != $note->created_at)
                        • Updated {{ $note->updated_at->diffForHumans() }}
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Note Content -->
    <div class="row">
        <div class="col-lg-8">
            <div class="note-content-card">
                <div class="note-content-body">
                    {!! nl2br(e($note->content)) !!}
                </div>
            </div>
        </div>

        <!-- Meta Sidebar -->
        <div class="col-lg-4">
            <div class="note-content-card">
                <div class="note-meta-sidebar">
                    <h5 class="mb-3">
                        <i class="fas fa-info-circle me-2"></i>Note Details
                    </h5>

                    <div class="meta-item">
                        <span class="meta-label">Word Count</span>
                        <span class="meta-value">{{ $note->word_count }} words</span>
                    </div>

                    <div class="meta-item">
                        <span class="meta-label">Character Count</span>
                        <span class="meta-value">{{ strlen(strip_tags($note->content)) }} chars</span>
                    </div>

                    <div class="meta-item">
                        <span class="meta-label">Created</span>
                        <span class="meta-value">{{ $note->created_at->format('M j, Y g:i A') }}</span>
                    </div>

                    @if($note->updated_at != $note->created_at)
                        <div class="meta-item">
                            <span class="meta-label">Last Modified</span>
                            <span class="meta-value">{{ $note->updated_at->format('M j, Y g:i A') }}</span>
                        </div>
                    @endif

                    @if($note->date)
                        <div class="meta-item">
                            <span class="meta-label">Note Date</span>
                            <span class="meta-value">
                                {{ $note->formatted_date }}
                                @if($note->time)
                                    <br><small class="text-muted">{{ $note->formatted_time }}</small>
                                @endif
                            </span>
                        </div>
                    @endif

                    <div class="meta-item">
                        <span class="meta-label">Status</span>
                        <span class="meta-value">
                            @if($note->is_favorite)
                                <span class="badge bg-warning">Favorite</span>
                            @else
                                <span class="badge bg-secondary">Regular</span>
                            @endif
                        </span>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-4">
                        <h6 class="mb-3">Quick Actions</h6>
                        <div class="d-grid gap-2">
                            <a href="{{ route('notes.edit', $note->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit me-2"></i>Edit Note
                            </a>
                            <button class="btn btn-outline-success btn-sm" onclick="duplicateNote({{ $note->id }})">
                                <i class="fas fa-copy me-2"></i>Duplicate Note
                            </button>
                            <button class="btn btn-outline-info btn-sm" onclick="copyToClipboard()">
                                <i class="fas fa-clipboard me-2"></i>Copy Content
                            </button>
                            <form action="{{ route('notes.destroy', $note->id) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this note?')" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                    <i class="fas fa-trash me-2"></i>Delete Note
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Favorite toggle
    const favoriteBtn = document.querySelector('.favorite-btn');
    if (favoriteBtn) {
        favoriteBtn.addEventListener('click', function() {
            const noteId = this.dataset.noteId;

            fetch(`/notes/${noteId}/toggle-favorite`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.classList.toggle('active', data.is_favorite);

                    // Update status badge
                    const statusBadge = document.querySelector('.meta-value .badge');
                    if (statusBadge) {
                        if (data.is_favorite) {
                            statusBadge.className = 'badge bg-warning';
                            statusBadge.textContent = 'Favorite';
                        } else {
                            statusBadge.className = 'badge bg-secondary';
                            statusBadge.textContent = 'Regular';
                        }
                    }
                }
            });
        });
    }
});

function duplicateNote(noteId) {
    fetch(`/notes/${noteId}/duplicate`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        }
    })
    .then(() => {
        window.location.href = '{{ route("notes.index") }}';
    });
}

function copyToClipboard() {
    const noteContent = `{{ $note->title }}\n\n{{ strip_tags($note->content) }}`;

    if (navigator.clipboard) {
        navigator.clipboard.writeText(noteContent).then(() => {
            // Show toast notification
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed';
            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-check me-2"></i>Note content copied to clipboard!
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
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = noteContent;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('Note content copied to clipboard!');
    }
}
</script>
@endsection
