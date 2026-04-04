@extends('layouts.app')

@section('title', 'Create Note')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
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

    /* Two-panel grid */
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
    .cu-panel-name { text-align: center; font-size: 13px; font-weight: 700; color: #1a1d23; margin-bottom: 4px; }
    .cu-panel-sub  { text-align: center; font-size: 11px; color: #adb0b8; margin-bottom: 12px; }
    .cu-meta-row {
        display: flex; align-items: flex-start; gap: 8px;
        padding: 7px 0; border-top: 1px solid #f0f1f3;
        font-size: 12px; color: #6b7385;
    }
    .cu-meta-row i { font-size: 13px; color: #adb0b8; flex-shrink: 0; margin-top: 1px; }
    .cu-meta-row strong { color: #1a1d23; font-weight: 600; }

    /* Right sections */
    .cu-sections { display: flex; flex-direction: column; gap: 10px; }
    .cu-section { background: white; border: 1px solid #e3e4e8; border-radius: 8px; overflow: hidden; }
    .cu-section-header {
        display: flex; align-items: center; gap: 8px;
        padding: 10px 16px; background: #fafbfc; border-bottom: 1px solid #e3e4e8;
    }
    .cu-section-icon {
        width: 26px; height: 26px; border-radius: 6px;
        display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0;
    }
    .cu-section-icon.purple { background: #ede9fe; color: #7c3aed; }
    .cu-section-icon.blue   { background: #dbeafe; color: #2563eb; }
    .cu-section-icon.green  { background: #dcfce7; color: #16a34a; }
    .cu-section-icon.amber  { background: #fef3c7; color: #d97706; }
    .cu-section-title { font-size: 13px; font-weight: 700; color: #1a1d23; margin: 0; }
    .cu-section-sub   { font-size: 11px; color: #8a8f98; margin: 0 0 0 auto; }
    .cu-section-body  { padding: 16px; }

    /* Fields */
    .cu-field { margin-bottom: 14px; }
    .cu-field:last-child { margin-bottom: 0; }
    .cu-field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    @media(max-width:500px) { .cu-field-row { grid-template-columns: 1fr; } }
    .cu-label {
        display: block; font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .7px; color: #8a8f98; margin-bottom: 5px;
    }
    .cu-input {
        width: 100%; height: 34px; padding: 0 10px 0 34px;
        border: 1px solid #d3d5db; border-radius: 6px; background: white;
        font-size: 13px; color: #1a1d23; outline: none;
        transition: border-color .15s, box-shadow .15s; box-sizing: border-box;
    }
    .cu-input.no-icon { padding-left: 10px; }
    .cu-input:focus { border-color: #7c3aed; box-shadow: 0 0 0 2px rgba(124,58,237,.15); }
    .cu-input.is-invalid { border-color: #dc2626; }
    .cu-input-wrap { position: relative; }
    .cu-input-wrap > i {
        position: absolute; left: 10px; top: 50%;
        transform: translateY(-50%); font-size: 13px; color: #adb0b8; pointer-events: none;
    }
    .invalid-feedback { display: block; margin-top: 4px; font-size: 11px; color: #dc2626; font-weight: 500; }

    /* Quill editor */
    .cu-quill-wrap { border: 1px solid #d3d5db; border-radius: 6px; overflow: hidden; }
    .cu-quill-wrap:focus-within { border-color: #7c3aed; box-shadow: 0 0 0 2px rgba(124,58,237,.15); }
    .cu-quill-wrap .ql-toolbar { border: none; border-bottom: 1px solid #e3e4e8; background: #fafbfc; padding: 6px 8px; }
    .cu-quill-wrap .ql-container { border: none; font-size: 13px; }
    .cu-quill-wrap .ql-editor { min-height: 200px; padding: 10px 12px; }
    .cu-quill-wrap .ql-editor.ql-blank::before { color: #adb0b8; font-style: normal; font-size: 13px; }

    /* Tags */
    .cu-tags-box {
        display: flex; flex-wrap: wrap; gap: 6px; align-items: center;
        min-height: 36px; padding: 5px 8px;
        border: 1px solid #d3d5db; border-radius: 6px; background: white;
        cursor: text; transition: border-color .15s, box-shadow .15s;
    }
    .cu-tags-box:focus-within { border-color: #7c3aed; box-shadow: 0 0 0 2px rgba(124,58,237,.15); }
    .cu-tag-pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 2px 8px; border-radius: 20px; background: #ede9fe; color: #5b21b6;
        font-size: 11px; font-weight: 600;
    }
    .cu-tag-remove {
        background: none; border: none; padding: 0; cursor: pointer;
        color: #7c3aed; font-size: 11px; line-height: 1; display: flex; align-items: center;
    }
    .cu-tag-remove:hover { color: #dc2626; }
    .cu-tag-input {
        border: none; outline: none; font-size: 12px; color: #1a1d23;
        background: transparent; min-width: 100px; flex: 1; padding: 2px 4px;
    }

    /* Favorite toggle */
    .cu-fav-label {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 6px 14px; border: 1px solid #d3d5db; border-radius: 20px;
        font-size: 12px; font-weight: 600; color: #6b7385; cursor: pointer;
        background: white; transition: all .15s; user-select: none;
    }
    .cu-fav-label i { font-size: 13px; color: #d3d5db; transition: color .15s; }
    .cu-fav-check { display: none; }
    .cu-fav-check:checked + .cu-fav-label { border-color: #f59e0b; background: #fef3c7; color: #b45309; }
    .cu-fav-check:checked + .cu-fav-label i { color: #f59e0b; }

    /* Action bar */
    .cu-action-bar {
        display: flex; align-items: center; justify-content: flex-end; gap: 8px;
        padding: 12px 16px; background: #fafbfc; border-top: 1px solid #e3e4e8;
    }
    .cu-btn-cancel {
        padding: 6px 16px; border: 1px solid #d3d5db; background: white; color: #6b7385;
        border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none;
        transition: all .15s; line-height: 1.4;
    }
    .cu-btn-cancel:hover { border-color: #adb0b8; color: #1a1d23; }
    .cu-btn-save {
        padding: 6px 18px; background: #7c3aed; border: 1px solid #7c3aed;
        color: white; border-radius: 6px; font-size: 13px; font-weight: 600;
        cursor: pointer; transition: all .15s; line-height: 1.4;
    }
    .cu-btn-save:hover { background: #6d28d9; box-shadow: 0 2px 6px rgba(109,40,217,.35); }
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
                <h1 class="cu-header-title">Create Note</h1>
                <p class="cu-header-sub">Capture your thoughts with rich formatting</p>
            </div>
        </div>
    </div>

    {{-- Layout --}}
    <div class="cu-layout">

        {{-- Left panel --}}
        <div class="cu-info-panel">
            <div class="cu-info-panel-header"><span>New Note</span></div>
            <div class="cu-info-body">
                <div class="cu-avatar"><i class="bi bi-journal-plus"></i></div>
                <div class="cu-panel-name">New Note</div>
                <div class="cu-panel-sub">Fill in the form to create</div>

                <div class="cu-meta-row">
                    <i class="bi bi-person"></i>
                    <span>Author&nbsp;<strong>{{ auth()->user()->name }}</strong></span>
                </div>
                <div class="cu-meta-row">
                    <i class="bi bi-calendar3"></i>
                    <span>Date&nbsp;<strong>{{ now()->format('M d, Y') }}</strong></span>
                </div>
                <div class="cu-meta-row">
                    <i class="bi bi-info-circle"></i>
                    <span style="font-size:11px;line-height:1.5;">Add a category and tags to keep your notes organized and easy to find.</span>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('notes.store') }}" method="POST" id="noteForm">
            @csrf
            <div class="cu-sections">

                {{-- Basic Info --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon purple"><i class="bi bi-card-text"></i></span>
                        <span class="cu-section-title">Basic Info</span>
                    </div>
                    <div class="cu-section-body">
                        <div class="cu-field-row">
                            <div class="cu-field" style="margin-bottom:0;">
                                <label for="title" class="cu-label">Title <span style="color:#dc2626;">*</span></label>
                                <div class="cu-input-wrap">
                                    <i class="bi bi-card-heading"></i>
                                    <input type="text" name="title" id="title"
                                           class="cu-input {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                           value="{{ old('title') }}"
                                           placeholder="Note title" required>
                                </div>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="cu-field" style="margin-bottom:0;">
                                <label for="category" class="cu-label">Category</label>
                                <div class="cu-input-wrap">
                                    <i class="bi bi-tag"></i>
                                    <input type="text" name="category" id="category"
                                           class="cu-input {{ $errors->has('category') ? 'is-invalid' : '' }}"
                                           value="{{ old('category') }}"
                                           placeholder="e.g. Work, Personal"
                                           list="cat-list">
                                    <datalist id="cat-list">
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat }}">
                                        @endforeach
                                    </datalist>
                                </div>
                                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon blue"><i class="bi bi-pencil-square"></i></span>
                        <span class="cu-section-title">Content</span>
                        <span class="cu-section-sub">Rich text supported</span>
                    </div>
                    <div class="cu-section-body">
                        <div class="cu-field" style="margin-bottom:0;">
                            <div class="cu-quill-wrap">
                                <div id="editor"></div>
                            </div>
                            <textarea name="content" id="content" style="display:none;">{{ old('content') }}</textarea>
                            @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Tags & Scheduling --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon green"><i class="bi bi-tags"></i></span>
                        <span class="cu-section-title">Tags &amp; Scheduling</span>
                        <span class="cu-section-sub">Optional metadata</span>
                    </div>
                    <div class="cu-section-body">

                        {{-- Tags --}}
                        <div class="cu-field">
                            <label class="cu-label">Tags</label>
                            <div class="cu-tags-box" id="tags-box">
                                <input type="text" class="cu-tag-input" id="tag-input" placeholder="Type and press Enter to add tag">
                            </div>
                            <input type="hidden" name="tags" id="tags-hidden">
                            @error('tags')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Date + Time --}}
                        <div class="cu-field-row">
                            <div class="cu-field" style="margin-bottom:0;">
                                <label for="date" class="cu-label">Date</label>
                                <div class="cu-input-wrap">
                                    <i class="bi bi-calendar3"></i>
                                    <input type="date" name="date" id="date"
                                           class="cu-input {{ $errors->has('date') ? 'is-invalid' : '' }}"
                                           value="{{ old('date') }}">
                                </div>
                                @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="cu-field" style="margin-bottom:0;">
                                <label for="time" class="cu-label">Time</label>
                                <div class="cu-input-wrap">
                                    <i class="bi bi-clock"></i>
                                    <input type="time" name="time" id="time"
                                           class="cu-input {{ $errors->has('time') ? 'is-invalid' : '' }}"
                                           value="{{ old('time') }}">
                                </div>
                                @error('time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Favourite --}}
                        <div style="margin-top:14px;">
                            <input type="checkbox" name="is_favorite" id="is_favorite" value="1"
                                   class="cu-fav-check" {{ old('is_favorite') ? 'checked' : '' }}>
                            <label for="is_favorite" class="cu-fav-label">
                                <i class="bi bi-star-fill"></i> Mark as Favourite
                            </label>
                        </div>

                    </div>
                    <div class="cu-action-bar">
                        <a href="{{ route('notes.index') }}" class="cu-btn-cancel">Cancel</a>
                        <button type="submit" class="cu-btn-save">
                            <i class="bi bi-plus-lg me-1"></i>Create Note
                        </button>
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Quill
    const quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Start writing your note...',
        modules: {
            toolbar: [
                [{ header: [1,2,3,false] }],
                ['bold','italic','underline','strike'],
                [{ color:[] },{ background:[] }],
                [{ list:'ordered' },{ list:'bullet' }],
                ['blockquote','code-block'],
                ['link'],
                ['clean']
            ]
        }
    });

    @if(old('content'))
        quill.root.innerHTML = `{!! addslashes(old('content')) !!}`;
    @endif

    // Tags
    const tagsBox   = document.getElementById('tags-box');
    const tagInput  = document.getElementById('tag-input');
    const tagsHidden = document.getElementById('tags-hidden');
    let tags = [];

    tagInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            const val = this.value.trim().replace(/,$/, '');
            if (val && !tags.includes(val)) {
                tags.push(val);
                renderTags();
            }
            this.value = '';
        }
    });

    function renderTags() {
        const pills = tags.map(t => `
            <span class="cu-tag-pill">
                ${t}
                <button type="button" class="cu-tag-remove" data-tag="${t}">
                    <i class="bi bi-x"></i>
                </button>
            </span>
        `).join('');
        tagsBox.innerHTML = pills + '<input type="text" class="cu-tag-input" id="tag-input" placeholder="Type and press Enter to add tag">';

        tagsBox.querySelector('#tag-input').addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                const val = this.value.trim().replace(/,$/, '');
                if (val && !tags.includes(val)) {
                    tags.push(val);
                    renderTags();
                }
                this.value = '';
            }
        });

        tagsBox.querySelectorAll('.cu-tag-remove').forEach(btn => {
            btn.addEventListener('click', function () {
                tags = tags.filter(t => t !== this.dataset.tag);
                renderTags();
            });
        });

        tagsHidden.value = tags.join(',');
    }

    tagsBox.addEventListener('click', () => tagsBox.querySelector('input').focus());

    // Submit
    document.getElementById('noteForm').addEventListener('submit', function () {
        document.getElementById('content').value = quill.root.innerHTML;
        tagsHidden.value = tags.join(',');
    });
});
</script>
@endpush
