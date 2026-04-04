@extends('layouts.app')

@section('title', 'Create Project')

@push('styles')
<style>
    /* ─── Page Shell ─────────────────────────────────────────── */
    .main-content {
        padding: 14px 16px;
        background: #f7f8fa;
        min-height: 100vh;
    }

    /* ─── Gradient Page Header ───────────────────────────────── */
    .content-header {
        background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
        border-radius: 10px;
        padding: 12px 18px;
        color: white;
        margin-bottom: 14px;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--primary-500);
        box-shadow: 0 2px 8px rgba(99,102,241,.3);
    }

    .content-header::before {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 90px; height: 90px;
        background: rgba(255,255,255,.08);
        border-radius: 50%;
        transform: translate(22px,-22px);
    }

    .content-title {
        color: white;
        font-weight: 700;
        font-size: 17px;
        margin-bottom: 2px;
        position: relative;
        z-index: 1;
    }

    .content-subtitle {
        color: rgba(255,255,255,.8);
        font-size: 12px;
        margin: 0;
        font-weight: 400;
        position: relative;
        z-index: 1;
    }

    /* ─── Two-Panel Grid ─────────────────────────────────────── */
    .cu-layout {
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 14px;
        align-items: start;
    }

    /* ─── Left Info Panel ────────────────────────────────────── */
    .cu-info-panel {
        background: white;
        border: 1px solid #e3e4e8;
        border-radius: 8px;
        overflow: hidden;
        position: sticky;
        top: 14px;
    }

    .cu-info-panel-header {
        background: #f7f8fa;
        border-bottom: 1px solid #e3e4e8;
        padding: 10px 14px;
    }

    .cu-info-panel-header span {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .8px;
        color: #8a8f98;
    }

    .cu-info-body {
        padding: 14px;
    }

    .cu-project-avatar-preview {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 22px;
        color: white;
        background: #7c3aed;
        margin: 0 auto 10px;
        transition: background .2s;
    }

    .cu-preview-name {
        text-align: center;
        font-size: 13px;
        font-weight: 700;
        color: #1a1d23;
        margin-bottom: 4px;
        word-break: break-word;
        min-height: 18px;
    }

    .cu-preview-hint {
        text-align: center;
        font-size: 11px;
        color: #adb0b8;
        margin-bottom: 12px;
    }

    .cu-meta-row {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 7px 0;
        border-top: 1px solid #f0f1f3;
        font-size: 12px;
        color: #6b7385;
    }

    .cu-meta-row i { font-size: 13px; color: #adb0b8; flex-shrink: 0; }
    .cu-meta-row strong { color: #1a1d23; font-weight: 600; }

    /* ─── Right — Form Sections ──────────────────────────────── */
    .cu-sections {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .cu-section {
        background: white;
        border: 1px solid #e3e4e8;
        border-radius: 8px;
        overflow: hidden;
    }

    .cu-section-header {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        background: #fafbfc;
        border-bottom: 1px solid #e3e4e8;
    }

    .cu-section-icon {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
    }

    .cu-section-icon.purple { background: #ede9fe; color: #7c3aed; }
    .cu-section-icon.blue   { background: #dbeafe; color: #2563eb; }
    .cu-section-icon.green  { background: #dcfce7; color: #16a34a; }

    .cu-section-title {
        font-size: 13px;
        font-weight: 700;
        color: #1a1d23;
        margin: 0;
    }

    .cu-section-subtitle {
        font-size: 11px;
        color: #8a8f98;
        margin: 0 0 0 auto;
    }

    .cu-section-body {
        padding: 16px;
    }

    /* ─── Field Rows ─────────────────────────────────────────── */
    .cu-field {
        margin-bottom: 14px;
    }

    .cu-field:last-child { margin-bottom: 0; }

    .cu-field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .cu-label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .7px;
        color: #8a8f98;
        margin-bottom: 5px;
    }

    .cu-input {
        width: 100%;
        height: 34px;
        padding: 0 10px 0 34px;
        border: 1px solid #d3d5db;
        border-radius: 6px;
        background: white;
        font-size: 13px;
        color: #1a1d23;
        outline: none;
        transition: border-color .15s, box-shadow .15s;
        box-sizing: border-box;
    }

    .cu-input.no-icon { padding-left: 10px; }

    .cu-input:focus {
        border-color: #7c3aed;
        box-shadow: 0 0 0 2px rgba(124,58,237,.15);
    }

    .cu-input.is-invalid { border-color: #dc2626; }

    .cu-input-wrap {
        position: relative;
    }

    .cu-input-wrap > i {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 13px;
        color: #adb0b8;
        pointer-events: none;
    }

    .invalid-feedback {
        display: block;
        margin-top: 4px;
        font-size: 11px;
        color: #dc2626;
        font-weight: 500;
    }

    /* ─── Quill Editor ───────────────────────────────────────── */
    .cu-editor-wrap {
        border: 1px solid #d3d5db;
        border-radius: 6px;
        overflow: hidden;
        transition: border-color .15s, box-shadow .15s;
    }

    .cu-editor-wrap:focus-within {
        border-color: #7c3aed;
        box-shadow: 0 0 0 2px rgba(124,58,237,.15);
    }

    .cu-editor-wrap .ql-toolbar {
        border: none;
        border-bottom: 1px solid #e3e4e8;
        background: #fafbfc;
        padding: 6px 10px;
    }

    .cu-editor-wrap .ql-toolbar .ql-formats { margin-right: 8px; }

    .cu-editor-wrap .ql-container {
        border: none;
        font-family: inherit;
        font-size: 13px;
    }

    .cu-editor-wrap .ql-editor {
        min-height: 130px;
        padding: 10px 12px;
        color: #1a1d23;
        line-height: 1.6;
    }

    .cu-editor-wrap .ql-editor.ql-blank::before {
        color: #b0b4be;
        font-style: normal;
        left: 12px;
    }

    /* ─── Status Chips ───────────────────────────────────────── */
    .cu-status-chips {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .cu-chip-option { position: relative; }

    .cu-chip-option input {
        position: absolute;
        opacity: 0;
        width: 0; height: 0;
    }

    .cu-chip-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border: 1px solid #d3d5db;
        border-radius: 20px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        color: #6b7385;
        background: white;
        transition: all .15s;
        user-select: none;
    }

    .cu-chip-label:hover { border-color: #7c3aed; color: #7c3aed; background: #faf5ff; }

    .cu-chip-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .chip-not-started .cu-chip-dot  { background: #8a8f98; }
    .chip-in-progress .cu-chip-dot  { background: #3b66d4; }
    .chip-completed   .cu-chip-dot  { background: #16a34a; }

    .chip-not-started input:checked + .cu-chip-label  { color:#6b7385; border-color:#8a8f98; background:#f1f2f4; }
    .chip-in-progress input:checked + .cu-chip-label  { color:#2563eb; border-color:#3b66d4; background:#e8f0fe; }
    .chip-completed   input:checked + .cu-chip-label  { color:#16a34a; border-color:#16a34a; background:#e6f9f0; }

    /* ─── Bottom Action Bar ──────────────────────────────────── */
    .cu-action-bar {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        padding: 12px 16px;
        background: #fafbfc;
        border-top: 1px solid #e3e4e8;
    }

    .cu-btn-cancel {
        padding: 6px 16px;
        border: 1px solid #d3d5db;
        background: white;
        color: #6b7385;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all .15s;
        line-height: 1.4;
    }

    .cu-btn-cancel:hover { border-color: #adb0b8; color: #1a1d23; background: #f7f8fa; }

    .cu-btn-save {
        padding: 6px 18px;
        background: #7c3aed;
        border: 1px solid #7c3aed;
        color: white;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all .15s;
        line-height: 1.4;
    }

    .cu-btn-save:hover { background: #6d28d9; border-color: #6d28d9; box-shadow: 0 2px 6px rgba(109,40,217,.35); }

    /* ─── Responsive ─────────────────────────────────────────── */
    @media (max-width: 768px) {
        .cu-layout { grid-template-columns: 1fr; }
        .cu-info-panel { position: static; }
        .cu-field-row { grid-template-columns: 1fr; }
    }

</style>
@endpush

@section('content')
<div class="main-content">

    {{-- ── Gradient Page Header ──────────────────────────────── --}}
    <div class="content-header">
        <div class="d-flex align-items-center" style="position:relative;z-index:1;">
            <a href="{{ route('projects.index') }}" class="me-3 text-decoration-none">
                <i class="bi bi-arrow-left fs-5" style="color:rgba(255,255,255,.8);"></i>
            </a>
            <div>
                <h1 class="content-title mb-1">Create New Project</h1>
                <p class="content-subtitle">Set up a new project to organize your tasks and team</p>
            </div>
        </div>
    </div>

    {{-- ── Two-Panel Layout ──────────────────────────────────── --}}
    <div class="cu-layout">

        {{-- ── Left Preview Panel ───────────────────────────── --}}
        <div class="cu-info-panel">
            <div class="cu-info-panel-header">
                <span>Preview</span>
            </div>
            <div class="cu-info-body">
                <div class="cu-project-avatar-preview" id="previewAvatar">N</div>
                <div class="cu-preview-name" id="previewName">New Project</div>
                <div class="cu-preview-hint">Start typing a name&hellip;</div>

                <div class="cu-meta-row">
                    <i class="bi bi-circle-fill" style="font-size:7px; color:#8a8f98;"></i>
                    <span id="previewStatus">Not Started</span>
                </div>
                <div class="cu-meta-row">
                    <i class="bi bi-calendar-event"></i>
                    <span>Start&nbsp;<strong id="previewStart">—</strong></span>
                </div>
                <div class="cu-meta-row">
                    <i class="bi bi-calendar-x"></i>
                    <span>Due&nbsp;<strong id="previewEnd">—</strong></span>
                </div>
                <div class="cu-meta-row">
                    <i class="bi bi-currency-dollar"></i>
                    <span>Budget&nbsp;<strong id="previewBudget">—</strong></span>
                </div>
                <div class="cu-meta-row">
                    <i class="bi bi-person"></i>
                    <span>Owner&nbsp;<strong>{{ auth()->user()->name }}</strong></span>
                </div>
            </div>
        </div>

        {{-- ── Right Form Sections ───────────────────────────── --}}
        <form action="{{ route('projects.store') }}" method="POST" id="createProjectForm">
            @csrf

            <div class="cu-sections">

                {{-- General Info --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon purple"><i class="bi bi-folder2-open"></i></span>
                        <span class="cu-section-title">General Info</span>
                    </div>
                    <div class="cu-section-body">
                        <div class="cu-field">
                            <label for="name" class="cu-label">Project Name <span style="color:#dc2626;">*</span></label>
                            <div class="cu-input-wrap">
                                <i class="bi bi-folder"></i>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       class="cu-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                       value="{{ old('name') }}"
                                       placeholder="Enter project name"
                                       required>
                            </div>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="cu-field">
                            <label class="cu-label">Description</label>
                            <div class="cu-editor-wrap">
                                <div id="quill-editor"></div>
                                <textarea name="description" id="description" style="display:none;">{{ old('description') }}</textarea>
                            </div>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Timeline & Budget --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon blue"><i class="bi bi-calendar3"></i></span>
                        <span class="cu-section-title">Timeline &amp; Budget</span>
                    </div>
                    <div class="cu-section-body">
                        <div class="cu-field-row cu-field">
                            <div class="cu-field" style="margin-bottom:0;">
                                <label for="start_date" class="cu-label">Start Date</label>
                                <div class="cu-input-wrap">
                                    <i class="bi bi-calendar-event"></i>
                                    <input type="date"
                                           name="start_date"
                                           id="start_date"
                                           class="cu-input {{ $errors->has('start_date') ? 'is-invalid' : '' }}"
                                           value="{{ old('start_date', date('Y-m-d')) }}">
                                </div>
                                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="cu-field" style="margin-bottom:0;">
                                <label for="end_date" class="cu-label">End Date</label>
                                <div class="cu-input-wrap">
                                    <i class="bi bi-calendar-x"></i>
                                    <input type="date"
                                           name="end_date"
                                           id="end_date"
                                           class="cu-input {{ $errors->has('end_date') ? 'is-invalid' : '' }}"
                                           value="{{ old('end_date') }}">
                                </div>
                                @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="cu-field" style="margin-bottom:0; margin-top:12px;">
                            <label for="budget" class="cu-label">Budget (USD)</label>
                            <div class="cu-input-wrap">
                                <i class="bi bi-currency-dollar"></i>
                                <input type="number"
                                       name="budget"
                                       id="budget"
                                       class="cu-input {{ $errors->has('budget') ? 'is-invalid' : '' }}"
                                       value="{{ old('budget') }}"
                                       step="0.01" min="0"
                                       placeholder="0.00">
                            </div>
                            @error('budget')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon green"><i class="bi bi-ui-checks"></i></span>
                        <span class="cu-section-title">Status</span>
                        <span class="cu-section-subtitle">Select initial state</span>
                    </div>
                    <div class="cu-section-body">
                        <div class="cu-status-chips">
                            <div class="cu-chip-option chip-not-started">
                                <input type="radio" name="status" id="status_not_started" value="not_started"
                                    {{ old('status', 'not_started') == 'not_started' ? 'checked' : '' }}>
                                <label for="status_not_started" class="cu-chip-label">
                                    <span class="cu-chip-dot"></span> Not Started
                                </label>
                            </div>
                            <div class="cu-chip-option chip-in-progress">
                                <input type="radio" name="status" id="status_in_progress" value="in_progress"
                                    {{ old('status') == 'in_progress' ? 'checked' : '' }}>
                                <label for="status_in_progress" class="cu-chip-label">
                                    <span class="cu-chip-dot"></span> In Progress
                                </label>
                            </div>
                            <div class="cu-chip-option chip-completed">
                                <input type="radio" name="status" id="status_completed" value="completed"
                                    {{ old('status') == 'completed' ? 'checked' : '' }}>
                                <label for="status_completed" class="cu-chip-label">
                                    <span class="cu-chip-dot"></span> Completed
                                </label>
                            </div>
                        </div>
                        @error('status')<div class="invalid-feedback mt-2">{{ $message }}</div>@enderror
                    </div>
                    <div class="cu-action-bar">
                        <a href="{{ route('projects.index') }}" class="cu-btn-cancel">Cancel</a>
                        <button type="submit" class="cu-btn-save">
                            <i class="bi bi-plus-lg me-1"></i>Create Project
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createProjectForm');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const nameInput = document.getElementById('name');
    const budgetInput = document.getElementById('budget');

    // ── Live preview helpers ──────────────────────────────────
    const avatarColors = ['#6366f1','#8b5cf6','#06b6d4','#10b981','#f59e0b','#ef4444','#ec4899'];
    const previewAvatar = document.getElementById('previewAvatar');
    const previewName   = document.getElementById('previewName');
    const previewStatus = document.getElementById('previewStatus');
    const previewStart  = document.getElementById('previewStart');
    const previewEnd    = document.getElementById('previewEnd');
    const previewBudget = document.getElementById('previewBudget');

    function fmtDate(val) {
        if (!val) return '—';
        const d = new Date(val + 'T00:00:00');
        return d.toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' });
    }

    nameInput.addEventListener('input', function() {
        const v = this.value.trim();
        previewName.textContent = v || 'New Project';
        const letter = (v || 'N')[0].toUpperCase();
        previewAvatar.textContent = letter;
        previewAvatar.style.background = avatarColors[(v.length || 1) % avatarColors.length];
    });

    startDateInput.addEventListener('change', function() {
        previewStart.textContent = fmtDate(this.value);
        endDateInput.min = this.value;
        if (endDateInput.value && endDateInput.value < this.value) endDateInput.value = '';
    });

    endDateInput.addEventListener('change', function() {
        previewEnd.textContent = fmtDate(this.value);
    });

    budgetInput.addEventListener('input', function() {
        const v = parseFloat(this.value);
        previewBudget.textContent = isNaN(v) ? '—' : '$' + v.toLocaleString('en-US', {maximumFractionDigits:0});
    });

    const statusLabels = { not_started:'Not Started', in_progress:'In Progress', completed:'Completed' };
    document.querySelectorAll('input[name="status"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            previewStatus.textContent = statusLabels[this.value] || this.value;
        });
    });

    // Initialise preview from defaults
    previewStart.textContent = fmtDate(startDateInput.value);

    // ── Validation ────────────────────────────────────────────
    form.addEventListener('submit', function(e) {
        if (!nameInput.value.trim()) {
            e.preventDefault();
            alert('Please enter a project name.');
            return;
        }
        if (startDateInput.value && endDateInput.value) {
            if (new Date(endDateInput.value) < new Date(startDateInput.value)) {
                e.preventDefault();
                alert('End date cannot be before start date.');
                return;
            }
        }
    });

    // ── Quill ─────────────────────────────────────────────────
    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Describe your project goals, objectives, and key deliverables...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'header': 1 }, { 'header': 2 }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                ['link'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                ['clean']
            ]
        }
    });

    var initialContent = document.getElementById('description').value;
    if (initialContent) quill.root.innerHTML = initialContent;

    quill.on('text-change', function() {
        document.getElementById('description').value = quill.root.innerHTML;
    });

    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('description').value = quill.root.innerHTML;
    });
});
</script>

<!-- Quill.js CSS and JS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
@endpush
