@extends('layouts.app')

@section('title', 'Edit ' . $task->title)

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    /* ─── Page Shell ─────────────────────────────────────────── */
    .main-content {
        padding: 14px 16px;
        background: #f7f8fa;
        min-height: 100vh;
    }

    /* ─── Gradient Page Header ───────────────────────────────── */
    .content-header {
        background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        border-radius: 10px;
        padding: 12px 18px;
        color: white;
        margin-bottom: 14px;
        position: relative;
        overflow: hidden;
        border: 1px solid #6d28d9;
        box-shadow: 0 2px 8px rgba(124,58,237,.3);
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
        color: white; font-weight: 700; font-size: 17px;
        margin-bottom: 2px; position: relative; z-index: 1;
    }
    .content-subtitle {
        color: rgba(255,255,255,.8); font-size: 12px;
        margin: 0; font-weight: 400; position: relative; z-index: 1;
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
        font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .8px; color: #8a8f98;
    }
    .cu-info-body { padding: 14px; }

    .cu-task-avatar {
        width: 48px; height: 48px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; color: white; margin: 0 auto 10px;
    }
    .cu-task-name {
        text-align: center; font-size: 13px; font-weight: 700;
        color: #1a1d23; margin-bottom: 4px; word-break: break-word; line-height: 1.3;
    }
    .cu-task-id {
        text-align: center; font-size: 10px; font-weight: 700;
        letter-spacing: .08em; color: #adb0b8; text-transform: uppercase; margin-bottom: 10px;
    }

    .cu-status-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 600;
        margin: 0 auto 10px;
    }
    .cu-status-badge.to-do       { background:#f3f4f6; color:#374151; }
    .cu-status-badge.in-progress { background:#ede9fe; color:#5b21b6; }
    .cu-status-badge.on-hold     { background:#fef3c7; color:#b45309; }
    .cu-status-badge.in-review   { background:#dbeafe; color:#1d4ed8; }
    .cu-status-badge.completed   { background:#dcfce7; color:#15803d; }

    .cu-priority-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 600;
        margin: 0 auto 12px;
    }
    .cu-priority-badge.high   { background:#fef2f2; color:#dc2626; }
    .cu-priority-badge.medium { background:#fffbeb; color:#d97706; }
    .cu-priority-badge.low    { background:#f0fdf4; color:#16a34a; }

    .cu-meta-row {
        display: flex; align-items: center; gap: 8px;
        padding: 7px 0; border-top: 1px solid #f0f1f3;
        font-size: 12px; color: #6b7385;
    }
    .cu-meta-row i { font-size: 13px; color: #adb0b8; flex-shrink: 0; }
    .cu-meta-row strong { color: #1a1d23; font-weight: 600; }

    /* ─── Right — Form Sections ──────────────────────────────── */
    .cu-sections { display: flex; flex-direction: column; gap: 10px; }

    .cu-section {
        background: white; border: 1px solid #e3e4e8; border-radius: 8px; overflow: hidden;
    }
    .cu-section-header {
        display: flex; align-items: center; gap: 8px;
        padding: 10px 16px; background: #fafbfc; border-bottom: 1px solid #e3e4e8;
    }
    .cu-section-icon {
        width: 26px; height: 26px; border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; flex-shrink: 0;
    }
    .cu-section-icon.purple { background: #ede9fe; color: #7c3aed; }
    .cu-section-icon.blue   { background: #dbeafe; color: #2563eb; }
    .cu-section-icon.green  { background: #dcfce7; color: #16a34a; }
    .cu-section-icon.amber  { background: #fef3c7; color: #d97706; }
    .cu-section-icon.red    { background: #fee2e2; color: #dc2626; }

    .cu-section-title { font-size: 13px; font-weight: 700; color: #1a1d23; margin: 0; }
    .cu-section-subtitle { font-size: 11px; color: #8a8f98; margin: 0 0 0 auto; }
    .cu-section-body { padding: 16px; }

    /* ─── Fields ─────────────────────────────────────────────── */
    .cu-field { margin-bottom: 14px; }
    .cu-field:last-child { margin-bottom: 0; }
    .cu-field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

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
    select.cu-input { padding-left: 34px; cursor: pointer; }

    .cu-input-wrap { position: relative; }
    .cu-input-wrap > i {
        position: absolute; left: 10px; top: 50%;
        transform: translateY(-50%); font-size: 13px; color: #adb0b8; pointer-events: none;
    }
    .invalid-feedback { display: block; margin-top: 4px; font-size: 11px; color: #dc2626; font-weight: 500; }

    /* ─── Quill ──────────────────────────────────────────────── */
    .cu-editor-wrap {
        border: 1px solid #d3d5db; border-radius: 6px; overflow: hidden;
        transition: border-color .15s, box-shadow .15s;
    }
    .cu-editor-wrap:focus-within { border-color: #7c3aed; box-shadow: 0 0 0 2px rgba(124,58,237,.15); }
    .cu-editor-wrap .ql-toolbar { border: none; border-bottom: 1px solid #e3e4e8; background: #fafbfc; padding: 6px 10px; }
    .cu-editor-wrap .ql-toolbar .ql-formats { margin-right: 8px; }
    .cu-editor-wrap .ql-container { border: none; font-family: inherit; font-size: 13px; }
    .cu-editor-wrap .ql-editor { min-height: 130px; padding: 10px 12px; color: #1a1d23; line-height: 1.6; }
    .cu-editor-wrap .ql-editor.ql-blank::before { color: #b0b4be; font-style: normal; left: 12px; }

    /* ─── Chip Options ───────────────────────────────────────── */
    .cu-status-chips { display: flex; gap: 8px; flex-wrap: wrap; }
    .cu-chip-option { position: relative; }
    .cu-chip-option input { position: absolute; opacity: 0; width: 0; height: 0; }
    .cu-chip-label {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 12px; border: 1px solid #d3d5db; border-radius: 20px;
        cursor: pointer; font-size: 12px; font-weight: 600;
        color: #6b7385; background: white; transition: all .15s; user-select: none;
    }
    .cu-chip-label:hover { border-color: #7c3aed; color: #7c3aed; background: #faf5ff; }
    .cu-chip-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }

    /* Status chips */
    .chip-to-do       .cu-chip-dot { background: #9ca3af; }
    .chip-in-progress .cu-chip-dot { background: #7c3aed; }
    .chip-on-hold     .cu-chip-dot { background: #b45309; }
    .chip-in-review   .cu-chip-dot { background: #1d4ed8; }
    .chip-completed   .cu-chip-dot { background: #16a34a; }
    .chip-to-do       input:checked + .cu-chip-label { color:#374151; border-color:#9ca3af; background:#f3f4f6; }
    .chip-in-progress input:checked + .cu-chip-label { color:#5b21b6; border-color:#7c3aed; background:#ede9fe; }
    .chip-on-hold     input:checked + .cu-chip-label { color:#b45309; border-color:#b45309; background:#fef3c7; }
    .chip-in-review   input:checked + .cu-chip-label { color:#1d4ed8; border-color:#1d4ed8; background:#dbeafe; }
    .chip-completed   input:checked + .cu-chip-label { color:#15803d; border-color:#16a34a; background:#dcfce7; }

    /* Priority chips */
    .chip-low    .cu-chip-dot { background: #16a34a; }
    .chip-medium .cu-chip-dot { background: #f59e0b; }
    .chip-high   .cu-chip-dot { background: #dc2626; }
    .chip-low    input:checked + .cu-chip-label { color:#16a34a; border-color:#16a34a; background:#f0fdf4; }
    .chip-medium input:checked + .cu-chip-label { color:#d97706; border-color:#f59e0b; background:#fffbeb; }
    .chip-high   input:checked + .cu-chip-label { color:#dc2626; border-color:#dc2626; background:#fef2f2; }

    /* ─── Action Bar ─────────────────────────────────────────── */
    .cu-action-bar {
        display: flex; align-items: center; justify-content: flex-end;
        gap: 8px; padding: 12px 16px; background: #fafbfc; border-top: 1px solid #e3e4e8;
    }
    .cu-btn-cancel {
        padding: 6px 16px; border: 1px solid #d3d5db; background: white; color: #6b7385;
        border-radius: 6px; font-size: 13px; font-weight: 600;
        text-decoration: none; transition: all .15s; line-height: 1.4;
    }
    .cu-btn-cancel:hover { border-color: #adb0b8; color: #1a1d23; background: #f7f8fa; }
    .cu-btn-save {
        padding: 6px 18px; background: #7c3aed; border: 1px solid #7c3aed;
        color: white; border-radius: 6px; font-size: 13px; font-weight: 600;
        cursor: pointer; transition: all .15s; line-height: 1.4;
    }
    .cu-btn-save:hover { background: #6d28d9; border-color: #6d28d9; box-shadow: 0 2px 6px rgba(109,40,217,.35); }

    /* ─── Danger Zone ────────────────────────────────────────── */
    .cu-danger-section .cu-section-body {
        display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;
    }
    .cu-danger-text h6 { font-size: 13px; font-weight: 700; color: #1a1d23; margin: 0 0 2px; }
    .cu-danger-text p  { font-size: 12px; color: #6b7385; margin: 0; }
    .cu-btn-danger {
        padding: 6px 16px; background: white; border: 1px solid #dc2626; color: #dc2626;
        border-radius: 6px; font-size: 13px; font-weight: 600;
        cursor: pointer; transition: all .15s; white-space: nowrap; flex-shrink: 0;
    }
    .cu-btn-danger:hover { background: #dc2626; color: white; }

    /* ─── Responsive ─────────────────────────────────────────── */
    @media (max-width: 768px) {
        .cu-layout { grid-template-columns: 1fr; }
        .cu-info-panel { position: static; }
        .cu-field-row { grid-template-columns: 1fr; }
        .cu-danger-section .cu-section-body { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')
<div class="main-content">

    {{-- ── Gradient Header ──────────────────────────────────── --}}
    <div class="content-header">
        <div class="d-flex align-items-center" style="position:relative;z-index:1;">
            <a href="{{ route('tasks.show', $task->id) }}" class="me-3 text-decoration-none">
                <i class="bi bi-arrow-left fs-5" style="color:rgba(255,255,255,.8);"></i>
            </a>
            <div>
                <h1 class="content-title mb-1">Edit Task</h1>
                <p class="content-subtitle">Update task details and requirements</p>
            </div>
        </div>
    </div>

    {{-- ── Two-Panel Layout ──────────────────────────────────── --}}
    @php
        $statusMap = [
            'to_do'       => ['label'=>'To Do',       'class'=>'to-do'],
            'in_progress' => ['label'=>'In Progress', 'class'=>'in-progress'],
            'on_hold'     => ['label'=>'On Hold',     'class'=>'on-hold'],
            'in_review'   => ['label'=>'In Review',   'class'=>'in-review'],
            'completed'   => ['label'=>'Completed',   'class'=>'completed'],
        ];
        $priorityMap = [
            'low'    => ['label'=>'Low',    'class'=>'low'],
            'medium' => ['label'=>'Medium', 'class'=>'medium'],
            'high'   => ['label'=>'High',   'class'=>'high'],
        ];
        $priorityColors  = ['high'=>'#dc2626','medium'=>'#f59e0b','low'=>'#16a34a'];
        $avatarColor     = $priorityColors[$task->priority] ?? '#7c3aed';
        $statusInfo      = $statusMap[$task->status]     ?? $statusMap['to_do'];
        $priorityInfo    = $priorityMap[$task->priority] ?? $priorityMap['medium'];
    @endphp

    <div class="cu-layout">

        {{-- ── Left Panel ───────────────────────────────────── --}}
        <div class="cu-info-panel">
            <div class="cu-info-panel-header"><span>Task</span></div>
            <div class="cu-info-body">

                <div class="cu-task-avatar" style="background:{{ $avatarColor }};">
                    <i class="bi bi-check2-square"></i>
                </div>
                <div class="cu-task-id">TASK-{{ str_pad($task->id, 4, '0', STR_PAD_LEFT) }}</div>
                <div class="cu-task-name">{{ $task->title }}</div>

                <div class="text-center mb-1">
                    <span class="cu-status-badge {{ $statusInfo['class'] }}">
                        <i class="bi bi-circle-fill" style="font-size:7px;"></i>
                        {{ $statusInfo['label'] }}
                    </span>
                </div>
                <div class="text-center mb-2">
                    <span class="cu-priority-badge {{ $priorityInfo['class'] }}">
                        <i class="bi bi-flag-fill" style="font-size:9px;"></i>
                        {{ $priorityInfo['label'] }} Priority
                    </span>
                </div>

                @if($task->project)
                <div class="cu-meta-row">
                    <i class="bi bi-folder"></i>
                    <span>Project&nbsp;<strong>{{ $task->project->name }}</strong></span>
                </div>
                @endif

                @if($task->due_date)
                <div class="cu-meta-row">
                    <i class="bi bi-calendar-event"></i>
                    <span>Due&nbsp;<strong>{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</strong></span>
                </div>
                @endif

                @if($task->estimated_hours)
                <div class="cu-meta-row">
                    <i class="bi bi-clock"></i>
                    <span>Est.&nbsp;<strong>{{ $task->estimated_hours }} hrs</strong></span>
                </div>
                @endif

                <div class="cu-meta-row">
                    <i class="bi bi-person"></i>
                    <span>Assigned&nbsp;<strong>{{ $task->user->name }}</strong></span>
                </div>

                <div class="cu-meta-row">
                    <i class="bi bi-clock-history"></i>
                    <span>Updated&nbsp;<strong>{{ $task->updated_at->diffForHumans() }}</strong></span>
                </div>

            </div>
        </div>

        {{-- ── Right Form Sections ───────────────────────────── --}}
        <form action="{{ route('tasks.update', $task->id) }}" method="POST" id="editTaskForm">
            @csrf
            @method('PUT')

            <div class="cu-sections">

                {{-- General Info --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon purple"><i class="bi bi-card-text"></i></span>
                        <span class="cu-section-title">General Info</span>
                    </div>
                    <div class="cu-section-body">
                        <div class="cu-field">
                            <label for="title" class="cu-label">Task Title <span style="color:#dc2626;">*</span></label>
                            <div class="cu-input-wrap">
                                <i class="bi bi-card-text"></i>
                                <input type="text" name="title" id="title"
                                       class="cu-input {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                       value="{{ old('title', $task->title) }}"
                                       placeholder="Enter task title" required>
                            </div>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="cu-field">
                            <label class="cu-label">Description</label>
                            <div class="cu-editor-wrap">
                                <div id="quill-editor"></div>
                                <textarea name="description" id="description" style="display:none;">{{ old('description', $task->description) }}</textarea>
                            </div>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Schedule & Assignment --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon blue"><i class="bi bi-calendar3"></i></span>
                        <span class="cu-section-title">Schedule &amp; Assignment</span>
                    </div>
                    <div class="cu-section-body">
                        <div class="cu-field-row cu-field">
                            <div class="cu-field" style="margin-bottom:0;">
                                <label for="due_date" class="cu-label">Due Date</label>
                                <div class="cu-input-wrap">
                                    <i class="bi bi-calendar-event"></i>
                                    <input type="date" name="due_date" id="due_date"
                                           class="cu-input {{ $errors->has('due_date') ? 'is-invalid' : '' }}"
                                           value="{{ old('due_date', $task->due_date) }}">
                                </div>
                                @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="cu-field" style="margin-bottom:0;">
                                <label for="estimated_hours" class="cu-label">Estimated Hours</label>
                                <div class="cu-input-wrap">
                                    <i class="bi bi-clock"></i>
                                    <input type="number" name="estimated_hours" id="estimated_hours"
                                           class="cu-input {{ $errors->has('estimated_hours') ? 'is-invalid' : '' }}"
                                           value="{{ old('estimated_hours', $task->estimated_hours) }}"
                                           min="0.5" step="0.5" placeholder="e.g. 2.5">
                                </div>
                                @error('estimated_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="cu-field-row" style="margin-top:12px;">
                            <div class="cu-field" style="margin-bottom:0;">
                                <label for="project_id" class="cu-label">Project</label>
                                <div class="cu-input-wrap">
                                    <i class="bi bi-folder"></i>
                                    <select name="project_id" id="project_id"
                                            class="cu-input {{ $errors->has('project_id') ? 'is-invalid' : '' }}">
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}"
                                                {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="cu-field" style="margin-bottom:0;">
                                <label for="user_id" class="cu-label">Assigned To</label>
                                <div class="cu-input-wrap">
                                    <i class="bi bi-person"></i>
                                    <select name="user_id" id="user_id"
                                            class="cu-input {{ $errors->has('user_id') ? 'is-invalid' : '' }}">
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id', $task->user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon green"><i class="bi bi-ui-checks"></i></span>
                        <span class="cu-section-title">Status</span>
                        <span class="cu-section-subtitle">Current task state</span>
                    </div>
                    <div class="cu-section-body">
                        <div class="cu-status-chips">
                            <div class="cu-chip-option chip-to-do">
                                <input type="radio" name="status" id="status_to_do" value="to_do"
                                    {{ old('status', $task->status) == 'to_do' ? 'checked' : '' }}>
                                <label for="status_to_do" class="cu-chip-label">
                                    <span class="cu-chip-dot"></span> To Do
                                </label>
                            </div>
                            <div class="cu-chip-option chip-in-progress">
                                <input type="radio" name="status" id="status_in_progress" value="in_progress"
                                    {{ old('status', $task->status) == 'in_progress' ? 'checked' : '' }}>
                                <label for="status_in_progress" class="cu-chip-label">
                                    <span class="cu-chip-dot"></span> In Progress
                                </label>
                            </div>
                            <div class="cu-chip-option chip-on-hold">
                                <input type="radio" name="status" id="status_on_hold" value="on_hold"
                                    {{ old('status', $task->status) == 'on_hold' ? 'checked' : '' }}>
                                <label for="status_on_hold" class="cu-chip-label">
                                    <span class="cu-chip-dot"></span> On Hold
                                </label>
                            </div>
                            <div class="cu-chip-option chip-in-review">
                                <input type="radio" name="status" id="status_in_review" value="in_review"
                                    {{ old('status', $task->status) == 'in_review' ? 'checked' : '' }}>
                                <label for="status_in_review" class="cu-chip-label">
                                    <span class="cu-chip-dot"></span> In Review
                                </label>
                            </div>
                            <div class="cu-chip-option chip-completed">
                                <input type="radio" name="status" id="status_completed" value="completed"
                                    {{ old('status', $task->status) == 'completed' ? 'checked' : '' }}>
                                <label for="status_completed" class="cu-chip-label">
                                    <span class="cu-chip-dot"></span> Completed
                                </label>
                            </div>
                        </div>
                        @error('status')<div class="invalid-feedback mt-2">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Priority --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon amber"><i class="bi bi-flag"></i></span>
                        <span class="cu-section-title">Priority</span>
                        <span class="cu-section-subtitle">Task urgency level</span>
                    </div>
                    <div class="cu-section-body">
                        <div class="cu-status-chips">
                            <div class="cu-chip-option chip-low">
                                <input type="radio" name="priority" id="priority_low" value="low"
                                    {{ old('priority', $task->priority) == 'low' ? 'checked' : '' }}>
                                <label for="priority_low" class="cu-chip-label">
                                    <span class="cu-chip-dot"></span> Low
                                </label>
                            </div>
                            <div class="cu-chip-option chip-medium">
                                <input type="radio" name="priority" id="priority_medium" value="medium"
                                    {{ old('priority', $task->priority) == 'medium' ? 'checked' : '' }}>
                                <label for="priority_medium" class="cu-chip-label">
                                    <span class="cu-chip-dot"></span> Medium
                                </label>
                            </div>
                            <div class="cu-chip-option chip-high">
                                <input type="radio" name="priority" id="priority_high" value="high"
                                    {{ old('priority', $task->priority) == 'high' ? 'checked' : '' }}>
                                <label for="priority_high" class="cu-chip-label">
                                    <span class="cu-chip-dot"></span> High
                                </label>
                            </div>
                        </div>
                        @error('priority')<div class="invalid-feedback mt-2">{{ $message }}</div>@enderror
                    </div>
                    <div class="cu-action-bar">
                        <a href="{{ route('tasks.show', $task->id) }}" class="cu-btn-cancel">Cancel</a>
                        <button type="submit" class="cu-btn-save">
                            <i class="bi bi-check-lg me-1"></i>Save Changes
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>

    {{-- ── Danger Zone ───────────────────────────────────────── --}}
    <div class="cu-section cu-danger-section" style="margin-top:10px; border-color:#fecaca;">
        <div class="cu-section-header" style="background:#fff5f5; border-bottom-color:#fecaca;">
            <span class="cu-section-icon red"><i class="bi bi-exclamation-triangle"></i></span>
            <span class="cu-section-title" style="color:#dc2626;">Danger Zone</span>
        </div>
        <div class="cu-section-body">
            <div class="cu-danger-text">
                <h6>Delete this task</h6>
                <p>Permanently removes this task and its checklist items. This cannot be undone.</p>
            </div>
            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <button type="button" class="cu-btn-danger" onclick="confirmDelete()">
                    <i class="bi bi-trash me-1"></i>Delete Task
                </button>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Describe the task requirements, goals, and any specific instructions...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'header': 1 }, { 'header': 2 }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                [{ 'color': [] }, { 'background': [] }],
                ['clean']
            ]
        }
    });

    var initialContent = document.getElementById('description').value;
    if (initialContent) {
        quill.root.innerHTML = initialContent;
    }

    quill.on('text-change', function() {
        document.getElementById('description').value = quill.root.innerHTML;
    });

    document.getElementById('editTaskForm').addEventListener('submit', function() {
        document.getElementById('description').value = quill.root.innerHTML;
    });
});

function confirmDelete() {
    if (confirm('Are you sure you want to delete this task? This action cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endpush
