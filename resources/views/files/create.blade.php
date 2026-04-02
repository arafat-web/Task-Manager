@extends('layouts.app')

@section('title', 'Upload File')

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
.cu-layout { display: grid; grid-template-columns: 220px 1fr; gap: 14px; align-items: start; }
@media(max-width:768px) { .cu-layout { grid-template-columns: 1fr; } }

/* Left info panel */
.cu-info-panel {
    background: white; border: 1px solid #e3e4e8; border-radius: 8px;
    overflow: hidden; position: sticky; top: 1rem;
}
.cu-info-panel-header { background: #f7f8fa; border-bottom: 1px solid #e3e4e8; padding: 10px 14px; }
.cu-info-panel-header span { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: #8a8f98; }
.cu-info-body { padding: 16px 14px; }
.cu-avatar {
    width: 48px; height: 48px; border-radius: 10px; background: #059669;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; color: #fff; margin: 0 auto 10px;
}
.cu-panel-name { text-align: center; font-size: 13px; font-weight: 700; color: #1a1d23; margin-bottom: 4px; }
.cu-panel-sub  { text-align: center; font-size: 11px; color: #adb0b8; margin-bottom: 12px; }
.cu-meta-row {
    display: flex; align-items: flex-start; gap: 8px;
    font-size: 12px; color: #6b7280; padding: 5px 0;
    border-top: 1px solid #f3f4f6;
}
.cu-meta-row i { font-size: 13px; color: #adb0b8; flex-shrink: 0; margin-top: 1px; }
.cu-meta-row strong { color: #1a1d23; font-weight: 600; }

/* Accepted formats list */
.cu-fmt-list { margin: 0; padding: 0; list-style: none; }
.cu-fmt-list li {
    font-size: 11px; color: #6b7280; padding: 3px 0;
    border-top: 1px solid #f3f4f6; display: flex; align-items: center; gap: 5px;
}
.cu-fmt-list li i { color: #adb0b8; font-size: 11px; }

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

/* Drag-and-drop upload zone */
.cu-drop-zone {
    border: 2px dashed #d3d5db; border-radius: 8px;
    padding: 28px 16px; text-align: center; cursor: pointer;
    background: #f9fafb; transition: border-color .15s, background .15s;
    position: relative;
}
.cu-drop-zone:hover, .cu-drop-zone.dragover {
    border-color: #059669; background: rgba(5,150,105,.04);
}
.cu-drop-zone input[type="file"] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
}
.cu-drop-icon { font-size: 2.2rem; color: #059669; display: block; margin-bottom: 8px; }
.cu-drop-text { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 4px; }
.cu-drop-hint { font-size: 11px; color: #9ca3af; }

/* Selected file pill */
.cu-selected-file {
    display: none; align-items: center; gap: 8px;
    margin-top: 10px; background: #d1fae5; border: 1px solid #6ee7b7;
    border-radius: 6px; padding: 7px 10px; font-size: 12px; color: #065f46;
}
.cu-selected-file.show { display: flex; }
.cu-selected-file button {
    border: none; background: none; font-size: 14px; cursor: pointer;
    color: #065f46; margin-left: auto; padding: 0; line-height: 1;
}

/* Type chips */
.cu-type-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 8px; }
@media(max-width:500px) { .cu-type-grid { grid-template-columns: repeat(3, 1fr); } }
.cu-type-chip {
    border: 2px solid #e5e7eb; border-radius: 8px; padding: 10px 6px;
    text-align: center; cursor: pointer; transition: all .15s;
    background: white;
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
</style>
@endpush

@section('content')
<div class="main-content">

    {{-- Top header bar --}}
    <div class="cu-header">
        <div class="d-flex align-items-center" style="position:relative;z-index:1;">
            <a href="{{ route('files.index') }}" class="me-3 text-decoration-none">
                <i class="bi bi-arrow-left fs-5" style="color:rgba(255,255,255,.8);"></i>
            </a>
            <div>
                <h1 class="cu-header-title">Upload File</h1>
                <p class="cu-header-sub">Add a new file to your collection</p>
            </div>
        </div>
    </div>

    <div class="cu-layout">

        {{-- Left info panel --}}
        <div class="cu-info-panel">
            <div class="cu-info-panel-header"><span>Upload Info</span></div>
            <div class="cu-info-body">
                <div class="cu-avatar"><i class="bi bi-cloud-upload"></i></div>
                <div class="cu-panel-name">{{ auth()->user()->name }}</div>
                <div class="cu-panel-sub">Uploading on {{ now()->format('M d, Y') }}</div>

                <div class="cu-meta-row">
                    <i class="bi bi-info-circle"></i>
                    <span>Files are stored securely and only visible to you.</span>
                </div>

                <div class="cu-meta-row" style="margin-top:6px; border-top: none;">
                    <i class="bi bi-paperclip"></i>
                    <span><strong>Accepted formats</strong></span>
                </div>
                <ul class="cu-fmt-list" style="margin-top:4px;">
                    <li><i class="bi bi-image"></i> jpg, png, gif, svg</li>
                    <li><i class="bi bi-file-earmark-text"></i> pdf, doc, docx, txt</li>
                    <li><i class="bi bi-code-slash"></i> html, css, js, php</li>
                    <li><i class="bi bi-file-earmark-code"></i> java, c, cpp</li>
                </ul>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data" id="upload-form">
            @csrf
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
                                   value="{{ old('name') }}"
                                   placeholder="Enter a descriptive name for this file…"
                                   required autofocus>
                            @error('name')<p class="cu-err">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- File Upload --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon blue"><i class="bi bi-cloud-upload"></i></span>
                        <span class="cu-section-title">Choose File</span>
                        <span class="cu-section-sub">Required</span>
                    </div>
                    <div class="cu-section-body">
                        <div class="cu-drop-zone" id="drop-zone">
                            <i class="bi bi-cloud-arrow-up cu-drop-icon"></i>
                            <div class="cu-drop-text">Drag &amp; drop a file here</div>
                            <div class="cu-drop-hint">or click anywhere in this area to browse</div>
                            <input type="file" name="file" id="file-input"
                                   class="@error('file') is-invalid @enderror" required>
                        </div>
                        <div class="cu-selected-file" id="selected-file">
                            <i class="bi bi-file-earmark-check" style="font-size:15px;"></i>
                            <span id="selected-filename" style="font-weight:600;"></span>
                            <button type="button" onclick="clearFile()" title="Remove">&times;</button>
                        </div>
                        @error('file')<p class="cu-err" style="margin-top:6px;">{{ $message }}</p>@enderror
                        <p class="cu-hint">Max file size depends on your server configuration</p>
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
                        <input type="hidden" name="type" id="type-hidden" value="{{ old('type', 'project') }}">
                        <div class="cu-type-grid">
                            <div class="cu-type-chip selected" data-type="project">
                                <i class="bi bi-kanban"></i>
                                <span>Project</span>
                            </div>
                            <div class="cu-type-chip" data-type="docs">
                                <i class="bi bi-file-earmark-text"></i>
                                <span>Docs</span>
                            </div>
                            <div class="cu-type-chip" data-type="txt">
                                <i class="bi bi-file-earmark"></i>
                                <span>Text</span>
                            </div>
                            <div class="cu-type-chip" data-type="code">
                                <i class="bi bi-code-slash"></i>
                                <span>Code</span>
                            </div>
                            <div class="cu-type-chip" data-type="image">
                                <i class="bi bi-image"></i>
                                <span>Image</span>
                            </div>
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
                        <i class="bi bi-cloud-upload"></i> Upload File
                    </button>
                </div>

            </div>{{-- /cu-sections --}}
        </form>

    </div>{{-- /cu-layout --}}
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    var dropZone   = document.getElementById('drop-zone');
    var fileInput  = document.getElementById('file-input');
    var selFile    = document.getElementById('selected-file');
    var selName    = document.getElementById('selected-filename');
    var typeHidden = document.getElementById('type-hidden');

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
        showFile();
    });

    fileInput.addEventListener('change', showFile);

    function showFile() {
        if (!fileInput.files.length) return;
        selName.textContent = fileInput.files[0].name;
        selFile.classList.add('show');
        dropZone.style.display = 'none';
    }

    window.clearFile = function () {
        fileInput.value = '';
        selFile.classList.remove('show');
        dropZone.style.display = '';
    };

    /* Restore old type selection on validation error */
    var oldType = typeHidden.value;
    document.querySelectorAll('.cu-type-chip').forEach(function(chip) {
        if (chip.dataset.type === oldType) {
            chip.classList.add('selected');
        } else {
            chip.classList.remove('selected');
        }
        chip.addEventListener('click', function() {
            document.querySelectorAll('.cu-type-chip').forEach(function(c){ c.classList.remove('selected'); });
            chip.classList.add('selected');
            typeHidden.value = chip.dataset.type;
        });
    });
});
</script>
@endpush
