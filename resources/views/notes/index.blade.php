@extends('layouts.app')

@section('title', 'Notes')

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

    .notes-header {
        background: linear-gradient(135deg, var(--note-primary) 0%, var(--note-secondary) 100%);
        color: white;
        border-radius: 12px;
        padding: 0.875rem 1.25rem;
        margin-bottom: 0.875rem;
        box-shadow: 0 4px 12px var(--note-shadow-lg);
    }

    .search-filter-bar {
        background: white;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        margin-bottom: 0.875rem;
        box-shadow: 0 2px 4px -1px var(--note-shadow);
        border: 1px solid var(--note-border);
    }

    .notes-view-toggle {
        display: flex;
        gap: 0.5rem;
        border: 1px solid var(--note-border);
        border-radius: 8px;
        padding: 0.25rem;
        background: var(--note-light);
    }

    .view-btn {
        padding: 0.5rem 1rem;
        border: none;
        background: transparent;
        border-radius: 6px;
        color: var(--note-gray);
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .view-btn.active {
        background: white;
        color: var(--note-primary);
        box-shadow: 0 2px 4px var(--note-shadow);
    }

    .notes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 0.875rem;
        margin-bottom: 0.875rem;
    }

    .notes-list {
        display: none;
        flex-direction: column;
        gap: 1rem;
    }

    .note-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--note-border);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .note-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px var(--note-shadow-lg);
        border-color: var(--note-primary);
    }

    .note-card-header {
        padding: 0.75rem 0.75rem 0.375rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .note-title {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--note-dark);
        margin: 0;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .note-favorite {
        background: none;
        border: none;
        color: var(--note-gray);
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 0.25rem;
        border-radius: 50%;
    }

    .note-favorite.active {
        color: var(--note-warning);
        transform: scale(1.1);
    }

    .note-content {
        padding: 0 0.75rem;
        color: var(--note-gray);
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 0.375rem;
    }

    .note-meta {
        padding: 0.5rem 0.75rem;
        background: var(--note-light);
        border-top: 1px solid var(--note-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8125rem;
        color: var(--note-gray);
    }

    .note-category {
        background: var(--note-primary);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-block;
        margin-bottom: 0.5rem;
    }

    .note-tags {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 0.5rem;
    }

    .note-tag {
        background: var(--note-light);
        color: var(--note-gray);
        padding: 0.125rem 0.5rem;
        border-radius: 8px;
        font-size: 0.75rem;
        border: 1px solid var(--note-border);
    }

    .note-actions {
        position: absolute;
        top: 1rem;
        right: 1rem;
        display: flex;
        gap: 0.5rem;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .note-card:hover .note-actions {
        opacity: 1;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.875rem;
    }

    .action-btn.edit {
        background: var(--note-info);
    }

    .action-btn.duplicate {
        background: var(--note-success);
    }

    .action-btn.delete {
        background: var(--note-danger);
    }

    .action-btn:hover {
        transform: scale(1.1);
    }

    .empty-notes {
        text-align: center;
        padding: 2rem 1rem;
        color: var(--note-gray);
    }

    .empty-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto 0.75rem;
        background: var(--note-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--note-gray);
    }

    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 0.625rem;
        margin-bottom: 0.875rem;
    }

    .stat-card {
        background: white;
        padding: 0.875rem;
        border-radius: 10px;
        border: 1px solid var(--note-border);
        text-align: center;
        transition: transform 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .stat-number {
        font-size: 1.625rem;
        font-weight: 700;
        color: var(--note-primary);
    }

    .stat-label {
        color: var(--note-gray);
        font-size: 0.75rem;
        margin-top: 0.125rem;
    }

    @media (max-width: 768px) {
        .notes-grid {
            grid-template-columns: 1fr;
        }

        .search-filter-bar {
            padding: 1rem;
        }

        .notes-header {
            padding: 1.5rem;
        }
    }
</style>

<div class="container-fluid px-4">
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="notes-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-2">
                    <i class="fas fa-sticky-note me-3"></i>Notes
                </h1>
                <p class="mb-0 opacity-90">Organize your thoughts and ideas</p>
            </div>
            <a href="{{ route('notes.create') }}" class="btn btn-light btn-lg">
                <i class="fas fa-plus me-2"></i>New Note
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-number">{{ $notes->count() }}</div>
            <div class="stat-label">Total Notes</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $notes->where('is_favorite', true)->count() }}</div>
            <div class="stat-label">Favorites</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $categories->count() }}</div>
            <div class="stat-label">Categories</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $notes->sum('word_count') }}</div>
            <div class="stat-label">Total Words</div>
        </div>
    </div>

    <!-- Search and Filter Bar -->
    <div class="search-filter-bar">
        <form id="filter-form" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" name="search" id="search"
                           placeholder="Search notes..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="category" id="category">
                    <option value="all">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="favorites" id="favorites"
                           value="1" {{ request('favorites') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="favorites">
                        <i class="fas fa-star text-warning me-1"></i>Favorites
                    </label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="notes-view-toggle">
                    <button type="button" class="view-btn active" data-view="grid">
                        <i class="fas fa-th me-1"></i>Grid
                    </button>
                    <button type="button" class="view-btn" data-view="list">
                        <i class="fas fa-list me-1"></i>List
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Notes Container -->
    <div id="notes-container">
        @include('notes.partials.notes-grid', ['notes' => $notes])
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
         background: rgba(255, 255, 255, 0.8); z-index: 9999; align-items: center; justify-content: center;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filter-form');
    const searchInput = document.getElementById('search');
    const categorySelect = document.getElementById('category');
    const favoritesCheck = document.getElementById('favorites');
    const viewButtons = document.querySelectorAll('.view-btn');
    const notesContainer = document.getElementById('notes-container');
    const loadingOverlay = document.getElementById('loading-overlay');

    let searchTimeout;

    // Search with debounce
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterNotes();
        }, 300);
    });

    // Filter changes
    categorySelect.addEventListener('change', filterNotes);
    favoritesCheck.addEventListener('change', filterNotes);

    // View toggle
    viewButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            viewButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const view = this.dataset.view;
            const notesGrid = document.querySelector('.notes-grid');
            const notesList = document.querySelector('.notes-list');

            if (view === 'grid') {
                if (notesGrid) notesGrid.style.display = 'grid';
                if (notesList) notesList.style.display = 'none';
            } else {
                if (notesGrid) notesGrid.style.display = 'none';
                if (notesList) notesList.style.display = 'flex';
            }
        });
    });

    function filterNotes() {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);

        loadingOverlay.style.display = 'flex';

        fetch(`{{ route('notes.index') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            notesContainer.innerHTML = data.html;

            // Re-apply view preference
            const activeView = document.querySelector('.view-btn.active').dataset.view;
            const notesGrid = document.querySelector('.notes-grid');
            const notesList = document.querySelector('.notes-list');

            if (activeView === 'list') {
                if (notesGrid) notesGrid.style.display = 'none';
                if (notesList) notesList.style.display = 'flex';
            }

            // Re-attach event listeners
            attachNoteEventListeners();
        })
        .catch(error => {
            console.error('Error:', error);
        })
        .finally(() => {
            loadingOverlay.style.display = 'none';
        });
    }

    function attachNoteEventListeners() {
        // Favorite toggle
        document.querySelectorAll('.note-favorite').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                e.preventDefault();

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

                        // Update stats
                        const favoritesCount = document.querySelectorAll('.note-favorite.active').length;
                        document.querySelector('.stat-card:nth-child(2) .stat-number').textContent = favoritesCount;
                    }
                });
            });
        });

        // Note card click
        document.querySelectorAll('.note-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (!e.target.closest('.note-actions') && !e.target.closest('.note-favorite')) {
                    window.location.href = `/notes/${this.dataset.noteId}`;
                }
            });
        });

        // Action buttons
        document.querySelectorAll('.action-btn.duplicate').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();

                const noteId = this.dataset.noteId;

                fetch(`/notes/${noteId}/duplicate`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                })
                .then(() => {
                    window.location.reload();
                });
            });
        });
    }

    // Initial event listener attachment
    attachNoteEventListeners();
});
</script>
@endsection
