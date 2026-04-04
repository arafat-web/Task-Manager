@extends('layouts.app')

@section('title', 'Notes')

@push('styles')
<style>
    .main-content { padding: 14px 16px; background: #f7f8fa; min-height: 100vh; }

    /* Header */
    .cu-header {
        background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        border-radius: 10px; padding: 12px 18px; color: white;
        margin-bottom: 14px; position: relative; overflow: hidden;
        border: 1px solid #6d28d9; box-shadow: 0 2px 8px rgba(124,58,237,.3);
    }
    .cu-header::before {
        content: ''; position: absolute; top: 0; right: 0;
        width: 80px; height: 80px; background: rgba(255,255,255,.08);
        border-radius: 50%; transform: translate(20px,-20px);
    }
    .cu-header-title { font-weight: 700; font-size: 17px; margin: 0; position: relative; z-index: 1; }
    .cu-header-sub   { font-size: 12px; opacity: .8; margin: 2px 0 0; position: relative; z-index: 1; }
    .cu-btn-new {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 14px; border-radius: 7px; background: rgba(255,255,255,.2);
        color: white; border: 1px solid rgba(255,255,255,.3); font-size: 12px;
        font-weight: 600; text-decoration: none; transition: background .15s; position: relative; z-index: 1;
    }
    .cu-btn-new:hover { background: rgba(255,255,255,.3); color: white; }

    /* Stats */
    .cu-stats { display: grid; grid-template-columns: repeat(4,1fr); gap: 10px; margin-bottom: 14px; }
    @media(max-width:700px) { .cu-stats { grid-template-columns: repeat(2,1fr); } }
    .cu-stat {
        background: white; border: 1px solid #e3e4e8; border-radius: 8px;
        padding: 12px 14px; display: flex; align-items: center; gap: 12px;
    }
    .cu-stat-icon {
        width: 36px; height: 36px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0;
    }
    .cu-stat-label { font-size: 11px; color: #8a8f98; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
    .cu-stat-val   { font-size: 20px; font-weight: 800; color: #1a1d23; line-height: 1; }

    /* Filter bar */
    .cu-filter-bar {
        background: white; border: 1px solid #e3e4e8; border-radius: 8px;
        padding: 10px 14px; margin-bottom: 14px;
        display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    }
    .cu-search-wrap { position: relative; flex: 1; min-width: 180px; }
    .cu-search-wrap i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); font-size: 13px; color: #adb0b8; }
    .cu-search {
        width: 100%; height: 34px; padding: 0 10px 0 32px;
        border: 1px solid #d3d5db; border-radius: 6px; background: white;
        font-size: 13px; color: #1a1d23; outline: none;
        transition: border-color .15s, box-shadow .15s; box-sizing: border-box;
    }
    .cu-search:focus { border-color: #7c3aed; box-shadow: 0 0 0 2px rgba(124,58,237,.15); }
    .cu-select {
        height: 34px; padding: 0 28px 0 10px; border: 1px solid #d3d5db; border-radius: 6px;
        background: white; font-size: 13px; color: #1a1d23; outline: none;
        transition: border-color .15s; appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%238a8f98' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 8px center;
    }
    .cu-select:focus { border-color: #7c3aed; }
    .cu-fav-toggle {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 12px; border: 1px solid #d3d5db; border-radius: 6px;
        font-size: 12px; font-weight: 600; color: #6b7385; background: white;
        cursor: pointer; transition: all .15s; user-select: none; white-space: nowrap;
    }
    .cu-fav-toggle.active { border-color: #f59e0b; color: #b45309; background: #fef3c7; }
    .cu-fav-toggle i { font-size: 13px; }

    /* Notes grid */
    .cu-notes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 12px;
    }

    /* Note card */
    .cu-note-card {
        background: white; border: 1px solid #e3e4e8; border-radius: 8px;
        overflow: hidden; cursor: pointer; transition: all .15s; position: relative;
        display: flex; flex-direction: column;
    }
    .cu-note-card:hover { box-shadow: 0 6px 18px rgba(0,0,0,.1); border-color: #c4b5fd; transform: translateY(-2px); }
    .cu-note-accent { height: 3px; background: #7c3aed; }
    .cu-note-head { padding: 10px 12px 6px; display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; }
    .cu-note-title { font-size: 13px; font-weight: 700; color: #1a1d23; line-height: 1.4; margin: 0;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .cu-fav-btn { background: none; border: none; padding: 2px; cursor: pointer; color: #d3d5db; font-size: 15px; flex-shrink: 0; transition: color .15s; }
    .cu-fav-btn.active { color: #f59e0b; }
    .cu-fav-btn:hover { color: #f59e0b; }
    .cu-note-badges { padding: 0 12px 6px; display: flex; flex-wrap: wrap; gap: 5px; }
    .cu-cat-badge {
        display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 20px;
        font-size: 11px; font-weight: 600; background: #ede9fe; color: #6d28d9;
    }
    .cu-tag-pill {
        display: inline-flex; align-items: center; padding: 2px 7px; border-radius: 20px;
        font-size: 10px; font-weight: 600; background: #f3f4f6; color: #6b7385;
        border: 1px solid #e3e4e8;
    }
    .cu-note-excerpt {
        padding: 0 12px 8px; font-size: 12px; color: #8a8f98; line-height: 1.5; flex: 1;
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
    }
    .cu-note-footer {
        padding: 8px 12px; border-top: 1px solid #f0f1f3; background: #fafbfc;
        display: flex; align-items: center; justify-content: space-between; gap: 6px;
    }
    .cu-note-meta { font-size: 11px; color: #adb0b8; display: flex; align-items: center; gap: 4px; }
    .cu-note-actions { display: flex; gap: 4px; }
    .cu-note-btn {
        display: inline-flex; align-items: center; gap: 3px; padding: 3px 8px;
        border-radius: 5px; font-size: 11px; font-weight: 600;
        border: 1px solid transparent; cursor: pointer; text-decoration: none; transition: all .15s;
    }
    .cu-note-btn.edit   { background: #fffbeb; color: #d97706; border-color: #fde68a; }
    .cu-note-btn.edit:hover   { background: #fef3c7; }
    .cu-note-btn.copy   { background: #ecfdf5; color: #059669; border-color: #a7f3d0; }
    .cu-note-btn.copy:hover   { background: #d1fae5; }
    .cu-note-btn.del    { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
    .cu-note-btn.del:hover    { background: #fee2e2; }

    /* Empty */
    .cu-empty { text-align: center; padding: 40px 20px; color: #adb0b8; grid-column: 1/-1; }
    .cu-empty i { font-size: 36px; display: block; margin-bottom: 10px; opacity: .4; }
    .cu-empty p { font-size: 13px; margin: 0 0 14px; }
    .cu-empty a {
        display: inline-flex; align-items: center; gap: 6px; padding: 7px 18px;
        background: #7c3aed; color: white; border-radius: 7px; font-size: 13px;
        font-weight: 600; text-decoration: none; transition: background .15s;
    }
    .cu-empty a:hover { background: #6d28d9; }

    /* Count badge */
    .cu-count-badge {
        font-size: 11px; color: #8a8f98; font-weight: 600; margin-left: auto;
    }
</style>
@endpush

@section('content')
<div class="main-content">

    {{-- Header --}}
    <div class="cu-header">
        <div class="d-flex align-items-center justify-content-between" style="position:relative;z-index:1;">
            <div>
                <h1 class="cu-header-title"><i class="bi bi-journal-text me-2"></i>Notes</h1>
                <p class="cu-header-sub">Organize your thoughts and ideas</p>
            </div>
            <a href="{{ route('notes.create') }}" class="cu-btn-new">
                <i class="bi bi-plus-lg"></i> New Note
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="cu-stats">
        <div class="cu-stat">
            <div class="cu-stat-icon" style="background:#ede9fe;color:#7c3aed;"><i class="bi bi-journal-text"></i></div>
            <div>
                <div class="cu-stat-val" id="stat-total">{{ $notes->count() }}</div>
                <div class="cu-stat-label">Total Notes</div>
            </div>
        </div>
        <div class="cu-stat">
            <div class="cu-stat-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-star-fill"></i></div>
            <div>
                <div class="cu-stat-val">{{ $notes->where('is_favorite', true)->count() }}</div>
                <div class="cu-stat-label">Favorites</div>
            </div>
        </div>
        <div class="cu-stat">
            <div class="cu-stat-icon" style="background:#dbeafe;color:#2563eb;"><i class="bi bi-tag"></i></div>
            <div>
                <div class="cu-stat-val">{{ $categories->count() }}</div>
                <div class="cu-stat-label">Categories</div>
            </div>
        </div>
        <div class="cu-stat">
            <div class="cu-stat-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-file-text"></i></div>
            <div>
                <div class="cu-stat-val">{{ number_format($notes->sum('word_count')) }}</div>
                <div class="cu-stat-label">Total Words</div>
            </div>
        </div>
    </div>

    {{-- Filter bar --}}
    <div class="cu-filter-bar">
        <div class="cu-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" class="cu-search" id="search" placeholder="Search notes..." value="{{ request('search') }}">
        </div>
        <select class="cu-select" id="category">
            <option value="all">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <button type="button" class="cu-fav-toggle {{ request('favorites') == '1' ? 'active' : '' }}" id="fav-toggle">
            <i class="bi bi-star-fill"></i> Favorites
        </button>
        <span class="cu-count-badge" id="notes-count">{{ $notes->count() }} notes</span>
    </div>

    {{-- Notes container --}}
    <div id="notes-container">
        @include('notes.partials.notes-grid', ['notes' => $notes])
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput  = document.getElementById('search');
    const categorySel  = document.getElementById('category');
    const favToggle    = document.getElementById('fav-toggle');
    const container    = document.getElementById('notes-container');
    const countBadge   = document.getElementById('notes-count');
    let favActive      = favToggle.classList.contains('active');
    let searchTimeout;

    function filterNotes() {
        const params = new URLSearchParams({
            search:    searchInput.value,
            category:  categorySel.value,
            favorites: favActive ? '1' : '',
        });

        fetch(`{{ route('notes.index') }}?${params}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            container.innerHTML = data.html;
            countBadge.textContent = data.count + ' note' + (data.count !== 1 ? 's' : '');
            attachListeners();
        })
        .catch(console.error);
    }

    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterNotes, 300);
    });

    categorySel.addEventListener('change', filterNotes);

    favToggle.addEventListener('click', () => {
        favActive = !favActive;
        favToggle.classList.toggle('active', favActive);
        filterNotes();
    });

    function attachListeners() {
        // Favorite toggle
        document.querySelectorAll('.cu-fav-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                const noteId = this.dataset.noteId;
                fetch(`/notes/${noteId}/toggle-favorite`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) this.classList.toggle('active', data.is_favorite);
                });
            });
        });

        // Card click → show page
        document.querySelectorAll('.cu-note-card').forEach(card => {
            card.addEventListener('click', function (e) {
                if (!e.target.closest('.cu-note-actions') && !e.target.closest('.cu-fav-btn')) {
                    window.location.href = `/notes/${this.dataset.noteId}`;
                }
            });
        });

        // Duplicate
        document.querySelectorAll('.cu-note-btn.copy').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                const noteId = this.dataset.noteId;
                fetch(`/notes/${noteId}/duplicate`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                }).then(() => window.location.reload());
            });
        });
    }

    attachListeners();
});
</script>
@endpush
