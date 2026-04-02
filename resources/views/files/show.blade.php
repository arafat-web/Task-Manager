@extends('layouts.app')

@section('title', $file->name)

@push('styles')
<style>
.main-content { padding: 14px 16px; background: #f7f8fa; min-height: 100vh; }

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

.cu-layout { display: grid; grid-template-columns: 220px 1fr; gap: 14px; align-items: start; }
@media(max-width:768px) { .cu-layout { grid-template-columns: 1fr; } }

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

.cu-action-btn {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    border-radius: 6px; padding: 7px 10px;
    font-size: 12px; font-weight: 600; text-decoration: none;
    width: 100%; margin-bottom: 6px; border: 1px solid; transition: all .15s;
    cursor: pointer;
}
.cu-action-btn:last-child { margin-bottom: 0; }
.cu-action-btn.dl   { background: #d1fae5; border-color: #6ee7b7; color: #065f46; }
.cu-action-btn.dl:hover  { background: #059669; border-color: #059669; color: #fff; }
.cu-action-btn.edit { background: #fef3c7; border-color: #fcd34d; color: #92400e; }
.cu-action-btn.edit:hover { background: #f59e0b; border-color: #f59e0b; color: #fff; }
.cu-action-btn.del  { background: #fee2e2; border-color: #fca5a5; color: #dc2626; }
.cu-action-btn.del:hover  { background: #dc2626; border-color: #dc2626; color: #fff; }

.cu-divider { border: none; border-top: 1px solid #f3f4f6; margin: 10px 0; }

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
.cu-section-body  { padding: 16px; }

/* Preview */
.cu-preview-img {
    width: 100%; border-radius: 6px; border: 1px solid #e3e4e8;
    display: block; max-height: 500px; object-fit: contain; background: #f9fafb;
}
.cu-preview-pdf {
    width: 100%; height: 550px; border-radius: 6px; border: 1px solid #e3e4e8; display: block;
}
.cu-preview-generic {
    background: #f9fafb; border: 1px dashed #d3d5db; border-radius: 8px;
    padding: 48px 24px; text-align: center;
}
.cu-preview-generic i { font-size: 4rem; display: block; margin-bottom: 12px; }
.cu-preview-generic h4 { font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 6px; }
.cu-preview-generic p  { font-size: 12px; color: #9ca3af; margin-bottom: 16px; }
.cu-open-btn {
    background: #059669; color: #fff; border: none;
    padding: 7px 18px; border-radius: 6px; font-size: 13px; font-weight: 600;
    text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
    transition: background .15s;
}
.cu-open-btn:hover { background: #047857; color: #fff; }

/* Details table */
.cu-detail-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.cu-detail-table tr td { padding: 7px 0; border-bottom: 1px solid #f3f4f6; vertical-align: top; }
.cu-detail-table tr:last-child td { border-bottom: none; }
.cu-detail-table td:first-child { font-weight: 600; color: #374151; width: 38%; }
.cu-detail-table td:last-child  { color: #6b7280; }
</style>
@endpush

@section('content')
@php
    $ext     = strtolower(pathinfo($file->path, PATHINFO_EXTENSION));
    $isImage = in_array($ext, ['jpg','jpeg','png','gif','svg','webp']);
    $isPdf   = $ext === 'pdf';
    $icons   = ['project'=>'bi-kanban','docs'=>'bi-file-earmark-text','txt'=>'bi-file-earmark','code'=>'bi-code-slash','image'=>'bi-image'];
    $icon    = $icons[$file->type] ?? 'bi-file-earmark';
    $typeColors = ['project'=>'#3b82f6','docs'=>'#f59e0b','txt'=>'#8b5cf6','code'=>'#ef4444','image'=>'#10b981'];
    $color   = $typeColors[$file->type] ?? '#6b7280';
    try { $size = \Illuminate\Support\Facades\Storage::disk('public')->size($file->path); } catch (\Exception $e) { $size = null; }
    $sizeStr = $size !== null ? ($size >= 1048576 ? round($size/1048576,1).' MB' : round($size/1024,1).' KB') : 'Unknown';
@endphp
<div class="main-content">

    {{-- Top header --}}
    <div class="cu-header">
        <div class="d-flex align-items-center" style="position:relative;z-index:1;">
            <a href="{{ route('files.index') }}" class="me-3 text-decoration-none">
                <i class="bi bi-arrow-left fs-5" style="color:rgba(255,255,255,.8);"></i>
            </a>
            <div>
                <h1 class="cu-header-title">{{ Str::limit($file->name, 55) }}</h1>
                <p class="cu-header-sub">Uploaded {{ $file->created_at->diffForHumans() }}</p>
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
                <div class="cu-meta-row">
                    <i class="bi bi-hdd"></i>
                    <span><strong>{{ $sizeStr }}</strong></span>
                </div>
                <div class="cu-meta-row">
                    <i class="bi bi-filetype-{{ $ext ?: 'txt' }}"></i>
                    <span>.{{ strtoupper($ext ?: '—') }}</span>
                </div>

                <hr class="cu-divider">

                <a href="{{ Storage::url($file->path) }}" target="_blank" class="cu-action-btn dl">
                    <i class="bi bi-download"></i> Download
                </a>
                <a href="{{ route('files.edit', $file->id) }}" class="cu-action-btn edit">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('files.destroy', $file->id) }}" method="POST"
                      onsubmit="return confirm('Delete this file permanently?')" style="width:100%;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="cu-action-btn del">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        {{-- Right content --}}
        <div class="cu-sections">

            {{-- Preview --}}
            <div class="cu-section">
                <div class="cu-section-header">
                    <span class="cu-section-icon blue"><i class="bi bi-eye"></i></span>
                    <span class="cu-section-title">Preview</span>
                </div>
                <div class="cu-section-body">
                    @if($isImage)
                        <img src="{{ Storage::url($file->path) }}"
                             alt="{{ $file->name }}" class="cu-preview-img">
                    @elseif($isPdf)
                        <iframe src="{{ Storage::url($file->path) }}"
                                class="cu-preview-pdf" title="{{ $file->name }}"></iframe>
                    @else
                        <div class="cu-preview-generic">
                            <i class="bi {{ $icon }}" style="color:{{ $color }};"></i>
                            <h4>{{ Str::limit($file->name, 40) }}</h4>
                            <p>Preview is not available for .{{ strtoupper($ext ?: 'this') }} files.<br>Click below to open or download.</p>
                            <a href="{{ Storage::url($file->path) }}" target="_blank" class="cu-open-btn">
                                <i class="bi bi-box-arrow-up-right"></i> Open File
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Details --}}
            <div class="cu-section">
                <div class="cu-section-header">
                    <span class="cu-section-icon green"><i class="bi bi-info-circle"></i></span>
                    <span class="cu-section-title">File Details</span>
                </div>
                <div class="cu-section-body">
                    <table class="cu-detail-table">
                        <tr>
                            <td>Name</td>
                            <td>{{ $file->name }}</td>
                        </tr>
                        <tr>
                            <td>Type</td>
                            <td><span class="cu-type-badge {{ $file->type }}" style="display:inline-block;margin:0;">{{ ucfirst($file->type) }}</span></td>
                        </tr>
                        <tr>
                            <td>Extension</td>
                            <td>.{{ strtoupper($ext ?: '—') }}</td>
                        </tr>
                        <tr>
                            <td>Size</td>
                            <td>{{ $sizeStr }}</td>
                        </tr>
                        <tr>
                            <td>Uploaded</td>
                            <td>{{ $file->created_at->format('M d, Y \a\t g:i A') }}</td>
                        </tr>
                        <tr>
                            <td>Last Updated</td>
                            <td>{{ $file->updated_at->format('M d, Y \a\t g:i A') }}</td>
                        </tr>
                        <tr>
                            <td>Owner</td>
                            <td>{{ auth()->user()->name }}</td>
                        </tr>
                        <tr>
                            <td>Path</td>
                            <td style="word-break:break-all;font-size:11px;color:#9ca3af;">{{ $file->path }}</td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>{{-- /cu-sections --}}
    </div>{{-- /cu-layout --}}
</div>
@endsection
