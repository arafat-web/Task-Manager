@extends('layouts.app')

@section('title', 'File Manager')

@push('styles')
<style>
.main-content { padding: 14px 16px; background: #f7f8fa; min-height: 100vh; }

/* Top header bar */
.cu-header {
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    border-radius: 8px; padding: 12px 16px; margin-bottom: 14px;
    position: relative; overflow: hidden;
}
.cu-header::before {
    content: ''; position: absolute; top: -20px; right: -20px;
    width: 80px; height: 80px; background: rgba(255,255,255,.08); border-radius: 50%;
}
.cu-header-inner { display: flex; align-items: center; justify-content: space-between; position: relative; z-index: 1; }
.cu-header-title { font-weight: 700; font-size: 17px; margin: 0; color: #fff; }
.cu-header-sub   { font-size: 12px; opacity: .8; margin: 2px 0 0; color: #fff; }
.cu-btn-new {
    background: rgba(255,255,255,.18); border: 1.5px solid rgba(255,255,255,.4);
    color: #fff; font-size: 12px; font-weight: 700; border-radius: 6px;
    padding: 6px 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
    transition: background .15s;
}
.cu-btn-new:hover { background: rgba(255,255,255,.28); color: #fff; }

/* Stat tiles */
.cu-stats { display: flex; gap: 10px; margin-bottom: 14px; flex-wrap: wrap; }
.cu-stat {
    flex: 1 1 0; min-width: 90px; background: white;
    border: 1px solid #e3e4e8; border-radius: 8px;
    padding: 10px 12px; text-align: center; cursor: pointer;
    transition: border-color .15s, box-shadow .15s;
}
.cu-stat:hover, .cu-stat.active { border-color: #059669; box-shadow: 0 0 0 2px rgba(5,150,105,.12); }
.cu-stat-num  { font-size: 22px; font-weight: 800; color: #1a1d23; line-height: 1; }
.cu-stat-lbl  { font-size: 10px; color: #8a8f98; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; margin-top: 3px; }
.cu-stat-icon { font-size: 18px; margin-bottom: 4px; }
.cu-stat.total   .cu-stat-icon { color: #059669; }
.cu-stat.project .cu-stat-icon { color: #3b82f6; }
.cu-stat.docs    .cu-stat-icon { color: #f59e0b; }
.cu-stat.code    .cu-stat-icon { color: #ef4444; }
.cu-stat.image   .cu-stat-icon { color: #10b981; }

/* Filter bar */
.cu-filter-bar {
    background: white; border: 1px solid #e3e4e8; border-radius: 8px;
    padding: 10px 14px; margin-bottom: 14px;
    display: flex; gap: 10px; align-items: center; flex-wrap: wrap;
}
.cu-search-wrap { position: relative; flex: 1; min-width: 180px; }
.cu-search-wrap i { position: absolute; left: 9px; top: 50%; transform: translateY(-50%); color: #adb0b8; font-size: 13px; }
.cu-search {
    width: 100%; padding: 6px 10px 6px 30px;
    border: 1px solid #d3d5db; border-radius: 6px;
    font-size: 12px; color: #111827; background: white;
    transition: border-color .15s, box-shadow .15s;
}
.cu-search:focus { outline: none; border-color: #059669; box-shadow: 0 0 0 2px rgba(5,150,105,.15); }
.cu-sel {
    padding: 6px 28px 6px 10px; border: 1px solid #d3d5db; border-radius: 6px;
    font-size: 12px; color: #374151; appearance: none; background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23aaa'/%3E%3C/svg%3E") no-repeat right 8px center;
    min-width: 120px; transition: border-color .15s;
}
.cu-sel:focus { outline: none; border-color: #059669; }
.cu-count { font-size: 11px; color: #8a8f98; margin-left: auto; white-space: nowrap; }

/* File grid */
.cu-file-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 12px;
}

/* File card */
.cu-file-card {
    background: white; border: 1px solid #e3e4e8; border-radius: 8px;
    overflow: hidden; transition: box-shadow .15s, border-color .15s;
    display: flex; flex-direction: column;
}
.cu-file-card:hover { border-color: #059669; box-shadow: 0 4px 16px rgba(5,150,105,.12); }
.cu-file-card-accent { height: 3px; }
.cu-file-card-accent.project { background: #3b82f6; }
.cu-file-card-accent.docs    { background: #f59e0b; }
.cu-file-card-accent.txt     { background: #8b5cf6; }
.cu-file-card-accent.code    { background: #ef4444; }
.cu-file-card-accent.image   { background: #10b981; }

.cu-file-body { padding: 14px; display: flex; flex-direction: column; flex: 1; }
.cu-file-top  { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px; }
.cu-file-icon {
    width: 38px; height: 38px; border-radius: 8px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: #fff;
}
.cu-file-icon.project { background: #3b82f6; }
.cu-file-icon.docs    { background: #f59e0b; }
.cu-file-icon.txt     { background: #8b5cf6; }
.cu-file-icon.code    { background: #ef4444; }
.cu-file-icon.image   { background: #10b981; }

.cu-file-meta  { flex: 1; min-width: 0; }
.cu-file-name  { font-size: 13px; font-weight: 700; color: #1a1d23; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 3px; }
.cu-file-badge {
    font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px;
    padding: 2px 8px; border-radius: 20px;
    display: inline-block;
}
.cu-file-badge.project { background: #dbeafe; color: #1d4ed8; }
.cu-file-badge.docs    { background: #fef3c7; color: #92400e; }
.cu-file-badge.txt     { background: #ede9fe; color: #5b21b6; }
.cu-file-badge.code    { background: #fee2e2; color: #991b1b; }
.cu-file-badge.image   { background: #d1fae5; color: #065f46; }

.cu-file-info { font-size: 11px; color: #9ca3af; margin-top: auto; margin-bottom: 10px; display: flex; align-items: center; gap: 4px; }
.cu-file-actions { display: flex; gap: 6px; }
.cu-file-btn {
    flex: 1; padding: 5px 8px; border-radius: 6px; border: 1px solid;
    font-size: 12px; font-weight: 600; cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center; gap: 4px;
    text-decoration: none; transition: all .15s;
}
.cu-file-btn.view   { background: #dbeafe; border-color: #93c5fd; color: #1d4ed8; }
.cu-file-btn.view:hover  { background: #2563eb; border-color: #2563eb; color: #fff; }
.cu-file-btn.download { background: #d1fae5; border-color: #6ee7b7; color: #065f46; }
.cu-file-btn.download:hover { background: #059669; border-color: #059669; color: #fff; }
.cu-file-btn.edit  { background: #fef3c7; border-color: #fcd34d; color: #92400e; }
.cu-file-btn.edit:hover  { background: #f59e0b; border-color: #f59e0b; color: #fff; }
.cu-file-btn.delete { background: #fee2e2; border-color: #fca5a5; color: #991b1b; }
.cu-file-btn.delete:hover { background: #dc2626; border-color: #dc2626; color: #fff; }

/* Alert */
.cu-alert {
    background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 8px;
    padding: 10px 14px; margin-bottom: 14px;
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; color: #065f46;
}

/* Empty state */
.cu-empty {
    background: white; border: 1px dashed #d1d5db; border-radius: 8px;
    text-align: center; padding: 48px 24px;
}
.cu-empty i { font-size: 2.5rem; color: #d1d5db; display: block; margin-bottom: 12px; }
.cu-empty h4 { font-size: 15px; font-weight: 700; color: #374151; margin-bottom: 6px; }
.cu-empty p  { font-size: 13px; color: #9ca3af; margin-bottom: 16px; }
.cu-btn-upload {
    background: #059669; color: #fff; border: none;
    padding: 7px 18px; border-radius: 6px; font-size: 13px; font-weight: 600;
    text-decoration: none; display: inline-flex; align-items: center; gap: 5px; transition: background .15s;
}
.cu-btn-upload:hover { background: #047857; color: #fff; }
</style>
@endpush

@section('content')
<div class="main-content">

    {{-- Top header bar --}}
    <div class="cu-header">
        <div class="cu-header-inner">
            <div>
                <h1 class="cu-header-title"><i class="bi bi-folder2-open me-2"></i>File Manager</h1>
                <p class="cu-header-sub">Upload and manage your project files</p>
            </div>
            <a href="{{ route('files.create') }}" class="cu-btn-new">
                <i class="bi bi-cloud-upload"></i> Upload File
            </a>
        </div>
    </div>

    {{-- Flash message --}}
    @if(session('success'))
    <div class="cu-alert">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Stat tiles --}}
    <div class="cu-stats">
        <div class="cu-stat total" onclick="filterType('')">
            <div class="cu-stat-icon"><i class="bi bi-files"></i></div>
            <div class="cu-stat-num">{{ $files->count() }}</div>
            <div class="cu-stat-lbl">All Files</div>
        </div>
        <div class="cu-stat project" onclick="filterType('project')">
            <div class="cu-stat-icon"><i class="bi bi-kanban"></i></div>
            <div class="cu-stat-num">{{ $files->where('type','project')->count() }}</div>
            <div class="cu-stat-lbl">Project</div>
        </div>
        <div class="cu-stat docs" onclick="filterType('docs')">
            <div class="cu-stat-icon"><i class="bi bi-file-earmark-text"></i></div>
            <div class="cu-stat-num">{{ $files->where('type','docs')->count() }}</div>
            <div class="cu-stat-lbl">Docs</div>
        </div>
        <div class="cu-stat code" onclick="filterType('code')">
            <div class="cu-stat-icon"><i class="bi bi-code-slash"></i></div>
            <div class="cu-stat-num">{{ $files->whereIn('type',['code','txt'])->count() }}</div>
            <div class="cu-stat-lbl">Code &amp; Text</div>
        </div>
        <div class="cu-stat image" onclick="filterType('image')">
            <div class="cu-stat-icon"><i class="bi bi-image"></i></div>
            <div class="cu-stat-num">{{ $files->where('type','image')->count() }}</div>
            <div class="cu-stat-lbl">Images</div>
        </div>
    </div>

    {{-- Filter bar --}}
    <div class="cu-filter-bar">
        <div class="cu-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" class="cu-search" id="file-search" placeholder="Search files…" oninput="applyFilters()">
        </div>
        <select class="cu-sel" id="type-filter" onchange="applyFilters()">
            <option value="">All Types</option>
            <option value="project">Project</option>
            <option value="docs">Docs</option>
            <option value="txt">Text</option>
            <option value="code">Code</option>
            <option value="image">Image</option>
        </select>
        <span class="cu-count" id="file-count">{{ $files->count() }} file(s)</span>
    </div>

    {{-- Files grid --}}
    @if($files->count() > 0)
    <div class="cu-file-grid" id="file-grid">
        @foreach($files as $file)
        @php
            $icons = [
                'project' => 'bi-kanban',
                'docs'    => 'bi-file-earmark-text',
                'txt'     => 'bi-file-earmark',
                'code'    => 'bi-code-slash',
                'image'   => 'bi-image',
            ];
            $icon = $icons[$file->type] ?? 'bi-file-earmark';
        @endphp
        <div class="cu-file-card"
             data-name="{{ strtolower($file->name) }}"
             data-type="{{ $file->type }}">
            <div class="cu-file-card-accent {{ $file->type }}"></div>
            <div class="cu-file-body">
                <div class="cu-file-top">
                    <div class="cu-file-icon {{ $file->type }}">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    <div class="cu-file-meta">
                        <div class="cu-file-name" title="{{ $file->name }}">{{ Str::limit($file->name, 32) }}</div>
                        <span class="cu-file-badge {{ $file->type }}">{{ ucfirst($file->type) }}</span>
                    </div>
                </div>
                <div class="cu-file-info">
                    <i class="bi bi-calendar3"></i>
                    {{ $file->created_at->format('M d, Y') }}
                </div>
                <div class="cu-file-actions">
                    <a href="{{ route('files.show', $file->id) }}" class="cu-file-btn view" title="View Details">
                        <i class="bi bi-eye"></i> View
                    </a>
                    <a href="{{ Storage::url($file->path) }}" target="_blank" class="cu-file-btn download" title="Download / View">
                        <i class="bi bi-download"></i>
                    </a>
                    <a href="{{ route('files.edit', $file->id) }}" class="cu-file-btn edit" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('files.destroy', $file->id) }}" method="POST"
                          onsubmit="return confirm('Delete this file permanently?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="cu-file-btn delete" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="cu-empty">
        <i class="bi bi-folder2-open"></i>
        <h4>No Files Yet</h4>
        <p>Upload your first file to get started!</p>
        <a href="{{ route('files.create') }}" class="cu-btn-upload">
            <i class="bi bi-cloud-upload"></i> Upload File
        </a>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
function filterType(type) {
    document.getElementById('type-filter').value = type;
    // mark active stat tile
    document.querySelectorAll('.cu-stat').forEach(function(el){ el.classList.remove('active'); });
    if (!type) {
        document.querySelector('.cu-stat.total').classList.add('active');
    } else {
        var map = { project:'project', docs:'docs', code:'code', txt:'code', image:'image' };
        var cls = map[type] || type;
        var el = document.querySelector('.cu-stat.' + cls);
        if (el) el.classList.add('active');
    }
    applyFilters();
}

function applyFilters() {
    var search = document.getElementById('file-search').value.toLowerCase().trim();
    var type   = document.getElementById('type-filter').value;
    var cards  = document.querySelectorAll('.cu-file-card');
    var visible = 0;
    cards.forEach(function(card) {
        var nameMatch = !search || card.dataset.name.includes(search);
        var typeMatch = !type || card.dataset.type === type ||
                        (type === 'code' && (card.dataset.type === 'code' || card.dataset.type === 'txt'));
        var show = nameMatch && typeMatch;
        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    var countEl = document.getElementById('file-count');
    if (countEl) countEl.textContent = visible + ' file(s)';
}

// Set total tile active by default
document.addEventListener('DOMContentLoaded', function(){
    document.querySelector('.cu-stat.total').classList.add('active');
});
</script>
@endpush
