@extends('layouts.app')

@section('title', $note->title)

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

    /* Layout */
    .cu-layout { display: grid; grid-template-columns: 220px 1fr; gap: 14px; align-items: start; }
    @media(max-width:768px) { .cu-layout { grid-template-columns: 1fr; } }

    /* Left panel */
    .cu-info-panel {
        background: white; border: 1px solid #e3e4e8; border-radius: 8px;
        overflow: hidden; position: sticky; top: 14px;
    }
    .cu-info-panel-header { background: #f7f8fa; border-bottom: 1px solid #e3e4e8; padding: 10px 14px; }
    .cu-info-panel-header span { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: #8a8f98; }
    .cu-info-body { padding: 14px; }
    .cu-avatar {
        width: 48px; height: 48px; border-radius: 10px; background: #7c3aed;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; color: white; margin: 0 auto 10px;
    }
    .cu-panel-name { text-align: center; font-size: 13px; font-weight: 700; color: #1a1d23; margin-bottom: 4px; word-break: break-word; }
    .cu-panel-sub  { text-align: center; font-size: 11px; color: #adb0b8; margin-bottom: 10px; }
    .cu-meta-row {
        display: flex; align-items: flex-start; gap: 8px;
        padding: 7px 0; border-top: 1px solid #f0f1f3;
        font-size: 12px; color: #6b7385;
    }
    .cu-meta-row i { font-size: 13px; color: #adb0b8; flex-shrink: 0; margin-top: 1px; }
    .cu-meta-row strong { color: #1a1d23; font-weight: 600; }

    /* Action buttons in panel */
    .cu-quick-actions { display: flex; flex-direction: column; gap: 6px; margin-top: 12px; border-top: 1px solid #f0f1f3; padding-top: 12px; }
    .cu-action-link {
        display: flex; align-items: center; gap: 7px; padding: 6px 10px;
        border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none;
        border: 1px solid transparent; cursor: pointer; transition: all .15s; background: white;
    }
    .cu-action-link.edit   { border-color: #fde68a; color: #d97706; background: #fffbeb; }
    .cu-action-link.edit:hover   { background: #fef3c7; }
    .cu-action-link.copy   { border-color: #a7f3d0; color: #059669; background: #ecfdf5; }
    .cu-action-link.copy:hover   { background: #d1fae5; }
    .cu-action-link.del    { border-color: #fecaca; color: #dc2626; background: #fef2f2; }
    .cu-action-link.del:hover    { background: #fee2e2; }
    .cu-fav-btn {
        display: flex; align-items: center; gap: 7px; padding: 6px 10px;
        border-radius: 6px; font-size: 12px; font-weight: 600;
        border: 1px solid #d3d5db; color: #6b7385; background: white;
        cursor: pointer; transition: all .15s; width: 100%;
    }
    .cu-fav-btn.active    { border-color: #f59e0b; color: #b45309; background: #fef3c7; }
    .cu-fav-btn.active i  { color: #f59e0b; }
    .cu-fav-btn i         { color: #d3d5db; font-size: 13px; }

    /* Right content area */
    .cu-content-card {
        background: white; border: 1px solid #e3e4e8; border-radius: 8px; overflow: hidden;
    }
    .cu-content-header {
        display: flex; align-items: center; gap: 8px;
        padding: 10px 16px; background: #fafbfc; border-bottom: 1px solid #e3e4e8;
    }
    .cu-content-header-icon {
        width: 26px; height: 26px; border-radius: 6px; background: #ede9fe; color: #7c3aed;
        display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0;
    }
    .cu-content-title { font-size: 13px; font-weight: 700; color: #1a1d23; margin: 0; }
    .cu-content-body {
        padding: 20px 24px; font-size: 14px; line-height: 1.8; color: #374151;
    }
    /* Style rich text content */
    .cu-content-body h1, .cu-content-body h2, .cu-content-body h3 { color: #1a1d23; margin: 1em 0 .5em; }
    .cu-content-body p   { margin: 0 0 .75em; }
    .cu-content-body ul, .cu-content-body ol { padding-left: 1.5em; margin-bottom: .75em; }
    .cu-content-body blockquote { border-left: 3px solid #7c3aed; padding-left: 12px; color: #6b7385; margin: .75em 0; font-style: italic; }
    .cu-content-body pre { background: #f7f8fa; border: 1px solid #e3e4e8; border-radius: 6px; padding: 12px; font-size: 12px; overflow-x: auto; }
    .cu-content-body a   { color: #7c3aed; text-decoration: underline; }
    .cu-content-body strong { color: #1a1d23; }

    /* Badges */
    .cu-cat-badge {
        display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px;
        font-size: 11px; font-weight: 700; background: #ede9fe; color: #6d28d9; margin-right: 4px;
    }
    .cu-tag-pill {
        display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 20px;
        font-size: 11px; font-weight: 600; background: #f3f4f6; color: #6b7385;
        border: 1px solid #e3e4e8; margin: 2px;
    }
    .cu-badges-row { padding: 10px 24px 0; display: flex; flex-wrap: wrap; align-items: center; gap: 4px; }

    /* Stats bar */
    .cu-stats-bar {
        display: flex; gap: 16px; padding: 8px 24px; border-top: 1px solid #f0f1f3;
        background: #fafbfc; font-size: 11px; color: #8a8f98; flex-wrap: wrap;
    }
    .cu-stats-bar span { display: flex; align-items: center; gap: 4px; }
</style>
@endpush

@section('content')
<div class="main-content">

    {{-- Header --}}
    <div class="cu-header">
        <div class="d-flex align-items-center" style="position:relative;z-index:1;">
            <a href="{{ route('notes.index') }}" class="me-3 text-decoration-none">
                <i class="bi bi-arrow-left fs-5" style="color:rgba(255,255,255,.8);"></i>
            </a>
            <div>
                <h1 class="cu-header-title">{{ $note->title }}</h1>
                <p class="cu-header-sub">
                    Created {{ $note->created_at->diffForHumans() }}
                    @if($note->updated_at->ne($note->created_at))
                        &middot; Updated {{ $note->updated_at->diffForHumans() }}
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Layout --}}
    <div class="cu-layout">

        {{-- Left panel --}}
        <div class="cu-info-panel">
            <div class="cu-info-panel-header"><span>Note Details</span></div>
            <div class="cu-info-body">
                <div class="cu-avatar"><i class="bi bi-journal-text"></i></div>
                <div class="cu-panel-name">{{ $note->title }}</div>
                @if($note->category)
                    <div class="cu-panel-sub">{{ $note->category }}</div>
                @else
                    <div class="cu-panel-sub">No category</div>
                @endif

                <div class="cu-meta-row">
                    <i class="bi bi-file-text"></i>
                    <span><strong>{{ $note->word_count }}</strong> words &middot; {{ strlen(strip_tags($note->content)) }} chars</span>
                </div>
                <div class="cu-meta-row">
                    <i class="bi bi-calendar3"></i>
                    <span>Created <strong>{{ $note->created_at->format('M d, Y') }}</strong></span>
                </div>
                @if($note->updated_at->ne($note->created_at))
                <div class="cu-meta-row">
                    <i class="bi bi-pencil"></i>
                    <span>Modified <strong>{{ $note->updated_at->format('M d, Y') }}</strong></span>
                </div>
                @endif
                @if($note->date)
                <div class="cu-meta-row">
                    <i class="bi bi-calendar-event"></i>
                    <span>{{ $note->formatted_date }}
                        @if($note->time) &middot; {{ $note->formatted_time }} @endif
                    </span>
                </div>
                @endif

                {{-- Quick actions --}}
                <div class="cu-quick-actions">
                    <button class="cu-fav-btn {{ $note->is_favorite ? 'active' : '' }}" id="fav-btn" data-note-id="{{ $note->id }}">
                        <i class="bi bi-star-fill"></i>
                        <span id="fav-label">{{ $note->is_favorite ? 'Unfavourite' : 'Favourite' }}</span>
                    </button>
                    <a href="{{ route('notes.edit', $note->id) }}" class="cu-action-link edit">
                        <i class="bi bi-pencil"></i> Edit Note
                    </a>
                    <button class="cu-action-link copy" onclick="duplicateNote({{ $note->id }})">
                        <i class="bi bi-files"></i> Duplicate
                    </button>
                    <button class="cu-action-link copy" onclick="copyContent()" style="border-color:#bfdbfe;color:#2563eb;background:#eff6ff;">
                        <i class="bi bi-clipboard"></i> Copy Text
                    </button>
                    <form action="{{ route('notes.destroy', $note->id) }}" method="POST" id="deleteForm">
                        @csrf @method('DELETE')
                        <button type="button" class="cu-action-link del" style="width:100%;" onclick="confirmDelete()">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Note content --}}
        <div class="cu-content-card">
            <div class="cu-content-header">
                <div class="cu-content-header-icon"><i class="bi bi-journal-text"></i></div>
                <span class="cu-content-title">{{ $note->title }}</span>
                @if($note->is_favorite)
                <i class="bi bi-star-fill ms-auto" style="color:#f59e0b;font-size:14px;" title="Favourite"></i>
                @endif
            </div>

            {{-- Category + tags --}}
            @if($note->category || ($note->tags && count($note->tags)))
            <div class="cu-badges-row">
                @if($note->category)
                    <span class="cu-cat-badge"><i class="bi bi-tag" style="font-size:10px;"></i> {{ $note->category }}</span>
                @endif
                @if($note->tags)
                    @foreach($note->tags as $tag)
                        <span class="cu-tag-pill">{{ $tag }}</span>
                    @endforeach
                @endif
            </div>
            @endif

            {{-- Content body --}}
            <div class="cu-content-body" id="note-content">
                {!! $note->content !!}
            </div>

            {{-- Stats bar --}}
            <div class="cu-stats-bar">
                <span><i class="bi bi-file-text"></i> {{ $note->word_count }} words</span>
                <span><i class="bi bi-clock"></i> {{ $note->created_at->format('M d, Y g:i A') }}</span>
                @if($note->date)
                <span><i class="bi bi-calendar-event"></i> {{ $note->formatted_date }}{{ $note->time ? ' at ' . $note->formatted_time : '' }}</span>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const favBtn   = document.getElementById('fav-btn');
    const favLabel = document.getElementById('fav-label');

    favBtn.addEventListener('click', function () {
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
            if (data.success) {
                favBtn.classList.toggle('active', data.is_favorite);
                favLabel.textContent = data.is_favorite ? 'Unfavourite' : 'Favourite';
            }
        });
    });
});

function duplicateNote(noteId) {
    fetch(`/notes/${noteId}/duplicate`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => {
        window.location.href = '{{ route("notes.index") }}';
    })
    .catch(err => console.error('Duplicate failed:', err));
}

function copyContent() {
    const text = document.getElementById('note-content').innerText;
    const btn = document.querySelector('[onclick="copyContent()"]');
    const orig = btn.innerHTML;

    function showCopied() {
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Copied!';
        setTimeout(() => btn.innerHTML = orig, 2000);
    }

    // Modern clipboard API (HTTPS / localhost)
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(showCopied).catch(() => fallbackCopy(text, showCopied));
    } else {
        fallbackCopy(text, showCopied);
    }
}

function fallbackCopy(text, callback) {
    const ta = document.createElement('textarea');
    ta.value = text;
    ta.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0;';
    document.body.appendChild(ta);
    ta.focus();
    ta.select();
    try { document.execCommand('copy'); callback(); } catch(e) { alert('Copy not supported in this browser.'); }
    document.body.removeChild(ta);
}

function confirmDelete() {
    if (confirm('Delete this note? This cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endpush
