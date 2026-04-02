@extends('layouts.app')

@section('title', 'Edit: ' . $file->name)

@push('styles')
<style>
.main-content { padding: 14px 16px; background: #f7f8fa; min-height: 100vh; }

/* Top header */
.cu-header {
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    border-radius: 8px; padding: 12px 16px; margin-bottom: 14px;
    position: relative; overflow: hidden;
}
.cu-header::before {
    content: ''; position: absolute; top: -20px; right: -20px;
    width: 80px; height: 80px; background: rgba(255,255,255,.08); border-radius: 50%;
}
.cu-header-title { font-weight: 700; font-size: 17px; margin: 0; color: #fff; position: relative; z-index: 1; }
.cu-header-sub   { font-size: 12px; opacity: .8; margin: 2px 0 0; color: #fff; position: relative; z-index: 1; }

/* Two-column layout */
.cu-layout { display: grid; grid-template-columns: 240px 1fr; gap: 14px; align-items: start; }
@media(max-width:768px) { .cu-layout { grid-template-columns: 1fr; } }

/* Left info panel */
.cu-info-panel {
    background: white; border: 1px solid #e3e4e8; border-radius: 8px;
    overflow: hidden; position: sticky; top: 1rem;
}
.cu-info-panel-header { background: #f7f8fa; border-bottom: 1px solid #e3e4e8; padding: 10px 14px; }
.cu-info-panel-header span { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: #8a8f98; }
.cu-info-body { padding: 14px; }
.cu-avatar {
    width: 48px; height: 48px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; color: #fff; margin: 0 auto 10px;
}
.cu-avatar.project { background: #3b82f6; }
.cu-avatar.docs    { background: #f59e0b; }
.cu-avatar.txt     { background: #8b5cf6; }
.cu-avatar.code    { background: #ef4444; }
.cu-avatar.image   { background: #10b981; }

.cu-panel-name { text-align: center; font-size: 13px; font-weight: 700; color: #1a1d23; margin-bottom: 4px; line-height: 1.35; }
.cu-panel-sub  { text-align: center; font-size: 11px; color: #adb0b8; margin-bottom: 10px; }
.cu-type-badge {
    display: block; text-align: center; font-size: 10px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .5px;
    padding: 3px 10px; border-radius: 20px; margin: 0 auto 10px; width: fit-content;
}
.cu-type-badge.project { background: #dbeafe; color: #1d4ed8; }
.cu-type-badge.docs    { background: #fef3c7; color: #92400e; }
.cu-type-badge.txt     { background: #ede9fe; color: #5b21b6; }
.cu-type-badge.code    { background: #fee2e2; color: #991b1b; }
.cu-type-badge.image   { background: #d1fae5; color: #065f46; }

.cu-meta-row {
    display: flex; align-items: flex-start; gap: 8px;
    font-size: 12px; color: #6b7280; padding: 5px 0;
    border-top: 1px solid #f3f4f6;
}
.cu-meta-row i { font-size: 13px; color: #adb0b8; flex-shrink: 0; margin-top: 1px; }
.cu-meta-row strong { color: #1a1d23; font-weight: 600; }

/* Preview area in panel */
.cu-preview-wrap { margin: 10px 0; border-top: 1px solid #f3f4f6; padding-top: 10px; }
.cu-preview-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #8a8f98; margin-bottom: 6px; }
.cu-preview-img {
    width: 100%; border-radius: 6px; object-fit: cover;
    border: 1px solid #e3e4e8; display: block; max-height: 160px;
}
.cu-preview-pdf {
    width: 100%; height: 150px; border-radius: 6px;
    border: 1px solid #e3e4e8;
}
.cu-preview-generic {
    background: #f9fafb; border: 1px dashed #d3d5db; border-radius: 6px;
    padding: 16px 10px; text-align: center;
}
.cu-preview-generic i { font-size: 2rem; display: block; margin-bottom: 6px; }
.cu-preview-generic span { font-size: 11px; color: #6b7280; font-weight: 600; }

/* Download link */
.cu-dl-btn {
    display: flex; align-items: center; justify-content: center; gap: 5px;
    background: #d1fae5; border: 1px solid #6ee7b7;
    color: #065f46; border-radius: 6px; padding: 6px 10px;
    font-size: 12px; font-weight: 600; text-decoration: none;
    transition: background .15s; margin-top: 8px; width: 100%;
}
.cu-dl-btn:hover { background: #059669; border-color: #059669; color: #fff; }

/* Sections */
.cu-sections { display: flex; flex-direction: column; gap: 14px; }
.cu-section { background: white; border: 1px solid #e3e4e8; border-radius: 8px; overflow: hidden; }
.cu-section-header {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 16px; background: #fafbfc; border-bottom: 1px solid #e3e4e8;
}
.cu-section-icon {
    width: 24px; height: 24px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0;
}
.cu-section-icon.green  { background: #dcfce7; color: #16a34a; }
.cu-section-icon.blue   { background: #dbeafe; color: #2563eb; }
.cu-section-icon.violet { background: #ede9fe; color: #7c3aed; }
.cu-section-icon.red    { background: #fee2e2; color: #dc2626; }
.cu-section-title { font-size: 13px; font-weight: 700; color: #1a1d23; margin: 0; }
.cu-section-sub   { font-size: 11px; color: #8a8f98; margin: 0 0 0 auto; }
.cu-section-body  { padding: 16px; }

/* Fields */
.cu-field { margin-bottom: 14px; }
.cu-field:last-child { margin-bottom: 0; }
.cu-label { display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 4px; }
.cu-input {
    width: 100%; border: 1px solid #d3d5db; border-radius: 6px;
    padding: 7px 10px; font-size: 13px; color: #111827; background: white;
    transition: border-color .15s, box-shadow .15s;
}
.cu-input:focus { outline: none; border-color: #059669; box-shadow: 0 0 0 2px rgba(5,150,105,.18); }
.cu-input.is-invalid { border-color: #dc2626; }
.cu-err  { font-size: 11px; color: #dc2626; margin-top: 3px; }
.cu-hint { font-size: 11px; color: #9ca3af; margin-top: 3px; }

/* Drop zone */
.cu-drop-zone {
    border: 2px dashed #d3d5db; border-radius: 8px;
    padding: 24px 16px; text-align: center; cursor: pointer;
    background: #f9fafb; transition: border-color .15s, background .15s;
    position: relative;
}
.cu-drop-zone:hover, .cu-drop-zone.dragover {
    border-color: #059669; background: rgba(5,150,105,.04);
}
.cu-drop-zone input[type="file"] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
}
.cu-drop-icon { font-size: 1.8rem; color: #059669; display: block; margin-bottom: 6px; }
.cu-drop-text { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 3px; }
.cu-drop-hint { font-size: 11px; color: #9ca3af; }

/* Selected file pill */
.cu-selected-pill {
    display: none; align-items: center; gap: 8px;
    margin-top: 8px; background: #fef3c7; border: 1px solid #fcd34d;
    border-radius: 6px; padding: 7px 10px; font-size: 12px; color: #92400e;
}
.cu-selected-pill.show { display: flex; }
.cu-selected-pill button {
    border: none; background: none; font-size: 14px; cursor: pointer;
    color: #92400e; margin-left: auto; padding: 0; line-height: 1;
}

/* New-file live preview */
.cu-new-preview { display: none; margin-top: 8px; }
.cu-new-preview.show { display: block; }
.cu-new-preview img {
    max-width: 100%; border-radius: 6px; border: 1px solid #e3e4e8;
    max-height: 200px; object-fit: contain;
}

/* Type chips */
.cu-type-grid { display: grid; grid-template-columns: repeat(5,1fr); gap: 8px; }
@media(max-width:500px) { .cu-type-grid { grid-template-columns: repeat(3,1fr); } }
.cu-type-chip {
    border: 2px solid #e5e7eb; border-radius: 8px; padding: 10px 6px;
    text-align: center; cursor: pointer; transition: all .15s; background: white;
}
.cu-type-chip:hover { border-color: #059669; background: rgba(5,150,105,.04); }
.cu-type-chip.selected { border-color: #059669; background: rgba(5,150,105,.1); }
.cu-type-chip i { display: block; font-size: 1.3rem; margin-bottom: 4px; }
.cu-type-chip span { font-size: 11px; font-weight: 700; color: #374151; }
.cu-type-chip[data-type="project"] i { color: #3b82f6; }
.cu-type-chip[data-type="docs"]    i { color: #f59e0b; }
.cu-type-chip[data-type="txt"]     i { color: #8b5cf6; }
.cu-type-chip[data-type="code"]    i { color: #ef4444; }
.cu-type-chip[data-type="image"]   i { color: #10b981; }

/* Action bar */
.cu-action-bar {
    background: white; border: 1px solid #e3e4e8; border-radius: 8px;
    padding: 12px 16px; display: flex; justify-content: flex-end; gap: 8px;
}
.cu-btn-cancel {
    padding: 6px 14px; border: 1.5px solid #d3d5db; border-radius: 6px;
    background: white; font-size: 13px; font-weight: 600; color: #6b7280;
    text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
    transition: border-color .15s, color .15s;
}
.cu-btn-cancel:hover { border-color: #adb0b8; color: #1a1d23; }
.cu-btn-save {
    padding: 6px 18px; background: #059669; border: 1px solid #059669;
    color: white; border-radius: 6px; font-size: 13px; font-weight: 600;
    cursor: pointer; transition: all .15s; display: inline-flex; align-items: center; gap: 5px;
}
.cu-btn-save:hover { background: #047857; border-color: #047857; box-shadow: 0 2px 6px rgba(5,150,105,.4); }

/* Danger zone */
.cu-danger-zone { background: white; border: 1px solid #fca5a5; border-radius: 8px; overflow: hidden; }
.cu-danger-zone .cu-section-header { background: #fff5f5; border-bottom: 1px solid #fca5a5; }
.cu-danger-zone .cu-section-body   { padding: 14px 16px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.cu-danger-desc { font-size: 12px; color: #6b7280; margin: 0; }
.cu-btn-delete {
    padding: 6px 14px; background: #fee2e2; border: 1px solid #fca5a5;
    color: #dc2626; border-radius: 6px; font-size: 13px; font-weight: 600;
    cursor: pointer; display: inline-flex; align-items: center; gap: 5px;
    transition: background .15s, border-color .15s;
}
.cu-btn-delete:hover { background: #dc2626; border-color: #dc2626; color: #fff; }
</style>
@endpush

@section('content')
@php
    $ext = strtolower(pathinfo($file->path, PATHINFO_EXTENSION));
    $isImage = in_array($ext, ['jpg','jpeg','png','gif','svg','webp']);
    $isPdf   = $ext === 'pdf';
    $icons   = ['project'=>'bi-kanban','docs'=>'bi-file-earmark-text','txt'=>'bi-file-earmark','code'=>'bi-code-slash','image'=>'bi-image'];
    $icon    = $icons[$file->type] ?? 'bi-file-earmark';
@endphp
<div class="main-content">

    {{-- Top header --}}
    <div class="cu-header">
        <div class="d-flex align-items-center" style="position:relative;z-index:1;">
            <a href="{{ route('files.index') }}" class="me-3 text-decoration-none">
                <i class="bi bi-arrow-left fs-5" style="color:rgba(255,255,255,.8);"></i>
            </a>
            <div>
                <h1 class="cu-header-title">Edit File</h1>
                <p class="cu-header-sub">{{ Str::limit($file->name, 55) }}</p>
            </div>
        </div>
    </div>

    <div class="cu-layout">

        {{-- Left info panel --}}
        <div class="cu-info-panel">
            <div class="cu-info-panel-header"><span>File Info</span></div>
            <div class="cu-info-body">
                <div class="cu-avatar {{ $file->type }}">
                    <i class="bi {{ $icon }}"></i>
                </div>
                <div class="cu-panel-name">{{ Str::limit($file->name, 28) }}</div>
                <span class="cu-type-badge {{ $file->type }}">{{ ucfirst($file->type) }}</span>

                <div class="cu-meta-row">
                    <i class="bi bi-person"></i>
                    <span>{{ auth()->user()->name }}</span>
                </div>
                <div class="cu-meta-row">
                    <i class="bi bi-calendar3"></i>
                    <span>Uploaded <strong>{{ $file->created_at->format('M d, Y') }}</strong></span>
                </div>
                <div class="cu-meta-row">
                    <i class="bi bi-clock-history"></i>
                    <span>Updated {{ $file->updated_at->diffForHumans() }}</span>
                </div>

                {{-- Current file preview --}}
                <div class="cu-preview-wrap">
                    <div class="cu-preview-label">Current File Preview</div>
                    @if($isImage)
                        <img src="{{ Storage::url($file->path) }}"
                             alt="{{ $file->name }}" class="cu-preview-img"
                             onerror="this.style.display='none'">
                    @elseif($isPdf)
                        <iframe src="{{ Storage::url($file->path) }}"
                                class="cu-preview-pdf" title="PDF Preview"></iframe>
                    @else
                        <div class="cu-preview-generic">
                            <i class="bi {{ $icon }}"
                               style="color:{{ ['project'=>'#3b82f6','docs'=>'#f59e0b','txt'=>'#8b5cf6','code'=>'#ef4444','image'=>'#10b981'][$file->type] ?? '#6b7280' }};"></i>
                            <span>.{{ strtoupper($ext ?: '???') }} file</span>
                        </div>
                    @endif
                    <a href="{{ Storage::url($file->path) }}" target="_blank" class="cu-dl-btn">
                        <i class="bi bi-download"></i> Download / View
                    </a>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('files.update', $file->id) }}" method="POST"
              enctype="multipart/form-data" id="edit-form">
            @csrf
            @method('PUT')
            <div class="cu-sections">

                {{-- File Name --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon green"><i class="bi bi-tag"></i></span>
                        <span class="cu-section-title">File Name</span>
                    </div>
                    <div class="cu-section-body">
                        <div class="cu-field">
                            <label for="name" class="cu-label">Display Name <span style="color:#dc2626;">*</span></label>
                            <input type="text" id="name" name="name"
                                   class="cu-input @error('name') is-invalid @enderror"
                                   value="{{ old('name', $file->name) }}"
                                   required autofocus>
                            @error('name')<p class="cu-err">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Replace File --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon blue"><i class="bi bi-cloud-upload"></i></span>
                        <span class="cu-section-title">Replace File</span>
                        <span class="cu-section-sub">Optional — leave blank to keep current</span>
                    </div>
                    <div class="cu-section-body">
                        <div class="cu-drop-zone" id="drop-zone">
                            <i class="bi bi-cloud-arrow-up cu-drop-icon"></i>
                            <div class="cu-drop-text">Drag &amp; drop a new file here</div>
                            <div class="cu-drop-hint">or click to browse — replaces the current file</div>
                            <input type="file" name="file" id="file-input"
                                   class="@error('file') is-invalid @enderror"
                                   accept=".jpeg,.jpg,.png,.gif,.svg,.doc,.docx,.pdf,.txt,.html,.css,.js,.php,.java,.c,.cpp">
                        </div>
                        <div class="cu-selected-pill" id="selected-pill">
                            <i class="bi bi-file-earmark-check" style="font-size:14px;"></i>
                            <span id="sel-filename" style="font-weight:600;"></span>
                            <button type="button" onclick="clearFile()" title="Remove">&times;</button>
                        </div>
                        {{-- New image preview --}}
                        <div class="cu-new-preview" id="new-preview">
                            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#8a8f98;margin-bottom:4px;">New File Preview</div>
                            <img id="new-preview-img" src="" alt="Preview">
                        </div>
                        @error('file')<p class="cu-err" style="margin-top:6px;">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- File Type --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon violet"><i class="bi bi-grid-3x3-gap"></i></span>
                        <span class="cu-section-title">File Type</span>
                        <span class="cu-section-sub">Required</span>
                    </div>
                    <div class="cu-section-body">
                        <input type="hidden" name="type" id="type-hidden" value="{{ old('type', $file->type) }}">
                        <div class="cu-type-grid">
                            @foreach(['project'=>'Project','docs'=>'Docs','txt'=>'Text','code'=>'Code','image'=>'Image'] as $t => $lbl)
                            @php $tIcon = ['project'=>'bi-kanban','docs'=>'bi-file-earmark-text','txt'=>'bi-file-earmark','code'=>'bi-code-slash','image'=>'bi-image'][$t]; @endphp
                            <div class="cu-type-chip {{ old('type', $file->type) === $t ? 'selected' : '' }}" data-type="{{ $t }}">
                                <i class="bi {{ $tIcon }}"></i>
                                <span>{{ $lbl }}</span>
                            </div>
                            @endforeach
                        </div>
                        @error('type')<p class="cu-err" style="margin-top:8px;">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Action bar --}}
                <div class="cu-action-bar">
                    <a href="{{ route('files.index') }}" class="cu-btn-cancel">
                        <i class="bi bi-x-lg"></i> Cancel
                    </a>
                    <button type="submit" class="cu-btn-save">
                        <i class="bi bi-check-lg"></i> Update File
                    </button>
                </div>

                {{-- Danger zone --}}
                <div class="cu-danger-zone">
                    <div class="cu-section-header">
                        <span class="cu-section-icon red"><i class="bi bi-exclamation-triangle"></i></span>
                        <span class="cu-section-title" style="color:#dc2626;">Danger Zone</span>
                    </div>
                    <div class="cu-section-body">
                        <p class="cu-danger-desc">Permanently delete this file. This action cannot be undone.</p>
                        <form action="{{ route('files.destroy', $file->id) }}" method="POST"
                              onsubmit="return confirm('Delete this file permanently?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cu-btn-delete">
                                <i class="bi bi-trash"></i> Delete File
                            </button>
                        </form>
                    </div>
                </div>

            </div>{{-- /cu-sections --}}
        </form>

    </div>{{-- /cu-layout --}}
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    var dropZone    = document.getElementById('drop-zone');
    var fileInput   = document.getElementById('file-input');
    var selPill     = document.getElementById('selected-pill');
    var selFilename = document.getElementById('sel-filename');
    var newPreview  = document.getElementById('new-preview');
    var newPrevImg  = document.getElementById('new-preview-img');
    var typeHidden  = document.getElementById('type-hidden');

    /* Drag events */
    dropZone.addEventListener('dragover', function(e){
        e.preventDefault(); dropZone.classList.add('dragover');
    });
    dropZone.addEventListener('dragleave', function(){
        dropZone.classList.remove('dragover');
    });
    dropZone.addEventListener('drop', function(e){
        e.preventDefault(); dropZone.classList.remove('dragover');
        fileInput.files = e.dataTransfer.files;
        handleFile();
    });

    fileInput.addEventListener('change', handleFile);

    function handleFile() {
        if (!fileInput.files.length) return;
        var file = fileInput.files[0];
        selFilename.textContent = file.name;
        selPill.classList.add('show');
        dropZone.style.display = 'none';

        /* Live image preview for newly chosen image */
        if (file.type.startsWith('image/')) {
            var reader = new FileReader();
            reader.onload = function(e) {
                newPrevImg.src = e.target.result;
                newPreview.classList.add('show');
            };
            reader.readAsDataURL(file);
        } else {
            newPreview.classList.remove('show');
        }
    }

    window.clearFile = function () {
        fileInput.value = '';
        selPill.classList.remove('show');
        newPreview.classList.remove('show');
        dropZone.style.display = '';
    };

    /* Type chips */
    document.querySelectorAll('.cu-type-chip').forEach(function(chip) {
        chip.addEventListener('click', function() {
            document.querySelectorAll('.cu-type-chip').forEach(function(c){ c.classList.remove('selected'); });
            chip.classList.add('selected');
            typeHidden.value = chip.dataset.type;
        });
    });
});
</script>
@endpush
