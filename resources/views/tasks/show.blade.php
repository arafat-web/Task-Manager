@extends('layouts.app')

@section('title', $task->title . ' - Task Details')

@push('styles')
<style>
/* ── Task Show – ClickUp style ─────────────────────────────── */
.ts-wrap { padding: 14px 16px 40px; }

/* Header */
.ts-header {
    background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
    border-radius: 14px;
    padding: 18px 24px;
    color: #fff;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(124,58,237,.35);
}
.ts-header::before {
    content:'';
    position:absolute;
    top:-30px; right:-30px;
    width:120px; height:120px;
    background:rgba(255,255,255,.08);
    border-radius:50%;
}
.ts-header::after {
    content:'';
    position:absolute;
    bottom:-40px; right:80px;
    width:80px; height:80px;
    background:rgba(255,255,255,.06);
    border-radius:50%;
}
.ts-back-btn {
    display:inline-flex; align-items:center; gap:6px;
    color:rgba(255,255,255,.85); font-size:13px; font-weight:500;
    text-decoration:none; padding:6px 12px;
    background:rgba(255,255,255,.15); border-radius:8px;
    transition:background .2s; white-space:nowrap; z-index:1;
}
.ts-back-btn:hover { background:rgba(255,255,255,.25); color:#fff; }
.ts-header-meta { flex:1; z-index:1; }
.ts-task-id {
    font-size:11px; font-weight:700; letter-spacing:.08em;
    opacity:.75; text-transform:uppercase; margin-bottom:4px;
}
.ts-header-title {
    font-size:20px; font-weight:700; line-height:1.3; margin:0;
}
.ts-header-actions { display:flex; gap:8px; z-index:1; }
.ts-header-btn {
    display:inline-flex; align-items:center; gap:6px;
    padding:8px 14px; border-radius:8px; font-size:13px;
    font-weight:600; text-decoration:none; border:none; cursor:pointer;
    transition:all .2s;
}
.ts-header-btn.edit {
    background:rgba(255,255,255,.2); color:#fff;
}
.ts-header-btn.edit:hover { background:rgba(255,255,255,.3); color:#fff; }
.ts-header-btn.del {
    background:rgba(239,68,68,.3); color:#fff;
}
.ts-header-btn.del:hover { background:rgba(239,68,68,.5); }

/* Two-column layout */
.ts-body { display:grid; grid-template-columns:260px 1fr; gap:20px; align-items:start; }

/* Left panel */
.ts-panel {
    background:#fff; border-radius:14px;
    border:1px solid #e5e7eb; position:sticky;
    top:80px; overflow:hidden;
    box-shadow:0 1px 4px rgba(0,0,0,.06);
}
.ts-panel-icon {
    display:flex; align-items:center; justify-content:center;
    height:12px;
}
.ts-priority-bar {
    height:5px; width:100%;
}
.ts-priority-bar.high  { background:#dc2626; }
.ts-priority-bar.medium { background:#f59e0b; }
.ts-priority-bar.low   { background:#16a34a; }

.ts-panel-body { padding:20px; }
.ts-panel-title { font-size:15px; font-weight:700; color:#111827; margin-bottom:14px; line-height:1.3; word-break:break-word; }

/* Status chip */
.ts-status {
    display:inline-flex; align-items:center; gap:7px;
    padding:6px 12px; border-radius:20px; font-size:12px; font-weight:600;
    cursor:pointer; transition:opacity .2s; margin-bottom:10px;
}
.ts-status:hover { opacity:.8; }
.ts-status.to_do    { background:#f3f4f6; color:#374151; }
.ts-status.in_progress { background:#ede9fe; color:#5b21b6; }
.ts-status.on_hold  { background:#fef3c7; color:#b45309; }
.ts-status.in_review { background:#dbeafe; color:#1d4ed8; }
.ts-status.completed { background:#dcfce7; color:#15803d; }
.ts-status-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
.ts-status.to_do    .ts-status-dot { background:#9ca3af; }
.ts-status.in_progress .ts-status-dot { background:#7c3aed; }
.ts-status.on_hold  .ts-status-dot { background:#b45309; }
.ts-status.in_review .ts-status-dot { background:#1d4ed8; }
.ts-status.completed .ts-status-dot { background:#16a34a; }

/* Priority chip */
.ts-priority {
    display:inline-flex; align-items:center; gap:6px;
    padding:5px 10px; border-radius:20px; font-size:11px; font-weight:600;
    margin-bottom:18px;
}
.ts-priority.high   { background:#fef2f2; color:#dc2626; }
.ts-priority.medium { background:#fffbeb; color:#d97706; }
.ts-priority.low    { background:#f0fdf4; color:#16a34a; }

/* Meta rows */
.ts-divider { height:1px; background:#f3f4f6; margin:0 -20px 16px; }
.ts-meta-row {
    display:flex; align-items:flex-start; gap:10px;
    margin-bottom:13px; font-size:13px;
}
.ts-meta-row:last-child { margin-bottom:0; }
.ts-meta-icon {
    width:28px; height:28px; border-radius:7px;
    background:#f3f4f6; display:flex; align-items:center;
    justify-content:center; color:#6b7280; flex-shrink:0; font-size:13px;
}
.ts-meta-label { font-size:10px; font-weight:600; color:#9ca3af; text-transform:uppercase; letter-spacing:.05em; line-height:1; margin-bottom:2px; }
.ts-meta-val { font-size:13px; font-weight:500; color:#111827; line-height:1.3; }
.ts-meta-val a { color:#7c3aed; text-decoration:none; }
.ts-meta-val a:hover { text-decoration:underline; }
.ts-overdue { color:#dc2626; font-size:11px; font-weight:600; margin-top:2px; }

/* Panel action buttons */
.ts-panel-actions { padding:0 16px 16px; display:flex; flex-direction:column; gap:8px; }
.ts-panel-btn {
    display:flex; align-items:center; gap:8px; padding:10px 14px;
    border-radius:9px; font-size:13px; font-weight:600;
    text-decoration:none; border:none; cursor:pointer;
    transition:all .2s; width:100%; box-sizing:border-box;
}
.ts-panel-btn.edit   { background:#7c3aed; color:#fff; }
.ts-panel-btn.edit:hover { background:#6d28d9; color:#fff; }
.ts-panel-btn.danger { background:#fef2f2; color:#dc2626; }
.ts-panel-btn.danger:hover { background:#fee2e2; }

/* Right content */
.ts-content { display:flex; flex-direction:column; gap:16px; }
.ts-card {
    background:#fff; border-radius:14px;
    border:1px solid #e5e7eb;
    box-shadow:0 1px 4px rgba(0,0,0,.06);
    overflow:hidden;
}
.ts-card-header {
    display:flex; align-items:center; justify-content:space-between;
    padding:14px 20px; border-bottom:1px solid #f3f4f6;
}
.ts-card-title {
    display:flex; align-items:center; gap:8px;
    font-size:14px; font-weight:700; color:#111827;
}
.ts-card-title i { color:#7c3aed; }
.ts-card-body { padding:20px; }

/* Progress */
.ts-progress-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
.ts-stat-tile {
    background:#f8f9fa; border-radius:10px; padding:14px;
    text-align:center; border:1px solid #f3f4f6;
}
.ts-stat-val { font-size:22px; font-weight:800; color:#111827; }
.ts-stat-lbl { font-size:11px; color:#9ca3af; font-weight:600; margin-top:4px; text-transform:uppercase; letter-spacing:.05em; }
.ts-prog-bar-wrap { background:#f3f4f6; border-radius:999px; height:8px; margin-top:16px; overflow:hidden; }
.ts-prog-bar-fill { height:100%; border-radius:999px; background:linear-gradient(90deg,#7c3aed,#a78bfa); transition:width .4s ease; }

/* Description */
.ts-description { font-size:14px; line-height:1.8; color:#374151; }
.ts-empty { text-align:center; padding:32px 20px; color:#9ca3af; }
.ts-empty i { font-size:36px; opacity:.35; display:block; margin-bottom:8px; }
.ts-empty p { font-size:13px; margin:0; }

/* Checklist */
.ts-checklist-progress-bar { font-size:12px; color:#6b7280; }
.ts-cl-prog-wrap { background:#f3f4f6; border-radius:999px; height:6px; margin-top:6px; overflow:hidden; }
.ts-cl-prog-fill { height:100%; border-radius:999px; background:#7c3aed; transition:width .3s; }
.ts-cl-item {
    display:flex; align-items:center; gap:10px;
    padding:9px 12px; border-radius:9px; transition:background .15s;
    margin-bottom:4px;
}
.ts-cl-item:hover { background:#f8f9fa; }
.ts-cl-item.completed .ts-cl-text { text-decoration:line-through; color:#9ca3af; }
.ts-cl-check {
    width:18px; height:18px; border-radius:5px;
    border:2px solid #d1d5db; cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0; transition:all .2s; color:#fff; font-size:11px;
}
.ts-cl-check.checked { background:#7c3aed; border-color:#7c3aed; }
.ts-cl-text { flex:1; font-size:13px; color:#374151; font-weight:500; }
.ts-cl-del {
    opacity:0; background:none; border:none; cursor:pointer;
    color:#9ca3af; padding:4px; border-radius:4px; transition:all .2s;
    font-size:13px;
}
.ts-cl-item:hover .ts-cl-del { opacity:1; }
.ts-cl-del:hover { color:#ef4444; background:#fef2f2; }
.ts-cl-add {
    display:flex; align-items:center; gap:8px;
    margin-top:12px; padding-top:12px; border-top:1px dashed #e5e7eb;
}
.ts-cl-input {
    flex:1; border:1.5px solid #e5e7eb; border-radius:8px;
    padding:8px 12px; font-size:13px; outline:none;
    transition:border-color .2s;
}
.ts-cl-input:focus { border-color:#7c3aed; }
.ts-cl-btn {
    display:flex; align-items:center; gap:5px;
    padding:8px 14px; border-radius:8px; background:#7c3aed;
    color:#fff; font-size:13px; font-weight:600; border:none;
    cursor:pointer; transition:background .2s; white-space:nowrap;
}
.ts-cl-btn:hover { background:#6d28d9; }

/* Status change modal */
.ts-modal-overlay {
    position:fixed; inset:0; background:rgba(0,0,0,.45);
    z-index:9000; display:flex; align-items:center; justify-content:center;
}
.ts-modal-box {
    background:#fff; border-radius:16px; width:420px; max-width:95vw;
    box-shadow:0 20px 60px rgba(0,0,0,.3); overflow:hidden;
}
.ts-modal-head {
    background:linear-gradient(135deg,#7c3aed,#5b21b6);
    color:#fff; padding:18px 20px; display:flex; align-items:center; justify-content:space-between;
}
.ts-modal-head h5 { margin:0; font-size:15px; font-weight:700; }
.ts-modal-body { padding:20px; }
.ts-status-opt {
    display:flex; align-items:center; gap:12px;
    padding:12px; border:2px solid #e5e7eb; border-radius:10px;
    cursor:pointer; margin-bottom:8px; transition:all .2s;
}
.ts-status-opt:last-child { margin-bottom:0; }
.ts-status-opt:hover { border-color:#c4b5fd; background:#faf5ff; }
.ts-status-opt.selected { border-color:#7c3aed; background:#f5f3ff; }
.ts-status-opt input { display:none; }
.ts-status-chip {
    display:inline-flex; align-items:center; gap:6px;
    padding:5px 10px; border-radius:20px; font-size:12px; font-weight:600;
}
.ts-status-chip.to_do    { background:#f3f4f6; color:#374151; }
.ts-status-chip.in_progress { background:#ede9fe; color:#5b21b6; }
.ts-status-chip.on_hold  { background:#fef3c7; color:#b45309; }
.ts-status-chip.in_review { background:#dbeafe; color:#1d4ed8; }
.ts-status-chip.completed { background:#dcfce7; color:#15803d; }
.ts-modal-foot {
    padding:12px 20px; border-top:1px solid #f3f4f6;
    display:flex; justify-content:flex-end; gap:8px;
}
.ts-modal-cancel {
    padding:9px 18px; border-radius:8px; border:1.5px solid #e5e7eb;
    background:#fff; font-size:13px; font-weight:600; color:#374151;
    cursor:pointer;
}
.ts-modal-save {
    padding:9px 18px; border-radius:8px; border:none;
    background:#7c3aed; color:#fff; font-size:13px; font-weight:600;
    cursor:pointer; transition:background .2s;
}
.ts-modal-save:hover { background:#6d28d9; }

@media(max-width:768px) {
    .ts-body { grid-template-columns:1fr; }
    .ts-panel { position:static; }
    .ts-progress-grid { grid-template-columns:repeat(3,1fr); }
    .ts-header { flex-wrap:wrap; gap:10px; }
    .ts-header-actions { flex-wrap:wrap; }
}
</style>
@endpush


@section('content')
@php
    $statusClass = str_replace('_', '_', $task->status);
    $statusLabel = ucwords(str_replace('_', ' ', $task->status));
    $priorityColor = ['high' => '#dc2626', 'medium' => '#f59e0b', 'low' => '#16a34a'][$task->priority] ?? '#7c3aed';

    $checklistTotal = $task->checklistItems->count();
    $checklistDone  = $task->checklistItems->where('completed', true)->count();
    $checklistPct   = $checklistTotal > 0 ? round(($checklistDone / $checklistTotal) * 100) : 0;

    $overdue = $task->due_date
        && \Carbon\Carbon::parse($task->due_date)->isPast()
        && $task->status !== 'completed';

    $progressPct = $task->status === 'completed' ? 100
        : ($task->status === 'in_review'  ? 80
        : ($task->status === 'in_progress' ? 50
        : ($task->status === 'on_hold'    ? 30 : 0)));
@endphp

<div class="ts-wrap">

    {{-- ── Header ───────────────────────────────────────────────── --}}
    <div class="ts-header">
        @if($task->project)
            <a href="{{ route('projects.tasks.index', $task->project) }}" class="ts-back-btn">
                <i class="bi bi-arrow-left"></i> Tasks
            </a>
        @else
            <a href="{{ route('tasks.index') }}" class="ts-back-btn">
                <i class="bi bi-arrow-left"></i> Tasks
            </a>
        @endif

        <div class="ts-header-meta">
            <div class="ts-task-id">TASK-{{ str_pad($task->id, 4, '0', STR_PAD_LEFT) }}</div>
            <h1 class="ts-header-title">{{ $task->title }}</h1>
        </div>

        <div class="ts-header-actions">
            <a href="{{ route('tasks.edit', $task->id) }}" class="ts-header-btn edit">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <button class="ts-header-btn del" onclick="confirmDelete()">
                <i class="bi bi-trash"></i> Delete
            </button>
        </div>
    </div>

    {{-- ── Body ─────────────────────────────────────────────────── --}}
    <div class="ts-body">

        {{-- ── Left panel ──────────────────────────────────────── --}}
        <div class="ts-panel">
            <div class="ts-priority-bar {{ $task->priority }}"></div>
            <div class="ts-panel-body">

                <div class="ts-panel-title">{{ $task->title }}</div>

                {{-- Status chip --}}
                <div>
                    <div class="ts-status {{ $task->status }}" onclick="openStatusModal()" title="Click to change status">
                        <span class="ts-status-dot"></span>
                        {{ $statusLabel }}
                        <i class="bi bi-chevron-down" style="font-size:10px; margin-left:2px;"></i>
                    </div>
                </div>

                {{-- Priority chip --}}
                <div class="ts-priority {{ $task->priority }}">
                    <i class="bi bi-flag-fill" style="font-size:10px;"></i>
                    {{ ucfirst($task->priority) }} Priority
                </div>

                <div class="ts-divider"></div>

                {{-- Meta rows --}}
                <div class="ts-meta-row">
                    <div class="ts-meta-icon"><i class="bi bi-person"></i></div>
                    <div>
                        <div class="ts-meta-label">Assigned To</div>
                        <div class="ts-meta-val">{{ $task->user->name }}</div>
                    </div>
                </div>

                @if($task->project)
                <div class="ts-meta-row">
                    <div class="ts-meta-icon"><i class="bi bi-folder"></i></div>
                    <div>
                        <div class="ts-meta-label">Project</div>
                        <div class="ts-meta-val">
                            <a href="{{ route('projects.show', $task->project) }}">{{ $task->project->name }}</a>
                        </div>
                    </div>
                </div>
                @endif

                @if($task->due_date)
                <div class="ts-meta-row">
                    <div class="ts-meta-icon">
                        <i class="bi bi-calendar-event" style="{{ $overdue ? 'color:#dc2626;' : '' }}"></i>
                    </div>
                    <div>
                        <div class="ts-meta-label">Due Date</div>
                        <div class="ts-meta-val">{{ \Carbon\Carbon::parse($task->due_date)->format('M j, Y') }}</div>
                        @if($overdue)
                            <div class="ts-overdue"><i class="bi bi-exclamation-triangle-fill"></i> {{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }}</div>
                        @else
                            <div style="font-size:11px; color:#9ca3af; margin-top:1px;">{{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }}</div>
                        @endif
                    </div>
                </div>
                @endif

                @if($task->estimated_hours)
                <div class="ts-meta-row">
                    <div class="ts-meta-icon"><i class="bi bi-clock"></i></div>
                    <div>
                        <div class="ts-meta-label">Estimated</div>
                        <div class="ts-meta-val">{{ $task->estimated_hours }} hrs</div>
                    </div>
                </div>
                @endif

                <div class="ts-meta-row">
                    <div class="ts-meta-icon"><i class="bi bi-calendar-plus"></i></div>
                    <div>
                        <div class="ts-meta-label">Created</div>
                        <div class="ts-meta-val">{{ $task->created_at->format('M j, Y') }}</div>
                    </div>
                </div>

                @if($task->updated_at->ne($task->created_at))
                <div class="ts-meta-row">
                    <div class="ts-meta-icon"><i class="bi bi-pencil"></i></div>
                    <div>
                        <div class="ts-meta-label">Updated</div>
                        <div class="ts-meta-val">{{ $task->updated_at->format('M j, Y') }}</div>
                    </div>
                </div>
                @endif

                @if($checklistTotal > 0)
                <div class="ts-divider" style="margin-top:16px;"></div>
                <div style="margin-bottom:4px;">
                    <div style="display:flex; justify-content:space-between; font-size:11px; font-weight:600; color:#9ca3af; text-transform:uppercase; letter-spacing:.05em; margin-bottom:5px;">
                        <span>Checklist</span>
                        <span>{{ $checklistDone }}/{{ $checklistTotal }}</span>
                    </div>
                    <div class="ts-cl-prog-wrap">
                        <div class="ts-cl-prog-fill" style="width:{{ $checklistPct }}%"></div>
                    </div>
                </div>
                @endif

            </div>

            <div class="ts-panel-actions">
                <a href="{{ route('tasks.edit', $task->id) }}" class="ts-panel-btn edit">
                    <i class="bi bi-pencil"></i> Edit Task
                </a>
                <button class="ts-panel-btn danger" onclick="confirmDelete()">
                    <i class="bi bi-trash"></i> Delete Task
                </button>
            </div>
        </div>

        {{-- ── Right content ────────────────────────────────────── --}}
        <div class="ts-content">

            {{-- Progress card --}}
            <div class="ts-card">
                <div class="ts-card-header">
                    <div class="ts-card-title"><i class="bi bi-bar-chart-line"></i> Progress</div>
                </div>
                <div class="ts-card-body">
                    <div class="ts-progress-grid">
                        <div class="ts-stat-tile">
                            <div class="ts-stat-val">{{ $progressPct }}%</div>
                            <div class="ts-stat-lbl">Task Status</div>
                        </div>
                        <div class="ts-stat-tile">
                            <div class="ts-stat-val">{{ $checklistDone }}/{{ $checklistTotal }}</div>
                            <div class="ts-stat-lbl">Checklist</div>
                        </div>
                        <div class="ts-stat-tile">
                            @if($task->due_date)
                                @php
                                    $dueCarbon   = \Carbon\Carbon::parse($task->due_date)->startOfDay();
                                    $isPastDue   = $dueCarbon->isPast();
                                    $daysCount   = (int) \Carbon\Carbon::now()->startOfDay()->diffInDays($dueCarbon);
                                @endphp
                                @if($overdue)
                                    <div class="ts-stat-val" style="color:#dc2626;">{{ $daysCount }}d</div>
                                    <div class="ts-stat-lbl" style="color:#dc2626;">Overdue</div>
                                @elseif($task->status === 'completed')
                                    <div class="ts-stat-val" style="color:#16a34a; font-size:28px;">✓</div>
                                    <div class="ts-stat-lbl">Completed</div>
                                @elseif($daysCount === 0)
                                    <div class="ts-stat-val" style="color:#d97706;">Today</div>
                                    <div class="ts-stat-lbl">Due</div>
                                @else
                                    <div class="ts-stat-val">{{ $daysCount }}d</div>
                                    <div class="ts-stat-lbl">Days Left</div>
                                @endif
                            @else
                                <div class="ts-stat-val">—</div>
                                <div class="ts-stat-lbl">Due Date</div>
                            @endif
                        </div>
                    </div>
                    <div class="ts-prog-bar-wrap">
                        <div class="ts-prog-bar-fill" style="width:{{ $progressPct }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Description card --}}
            <div class="ts-card">
                <div class="ts-card-header">
                    <div class="ts-card-title"><i class="bi bi-file-text"></i> Description</div>
                </div>
                <div class="ts-card-body">
                    @if($task->description)
                        <div class="ts-description">{!! $task->description !!}</div>
                    @else
                        <div class="ts-empty">
                            <i class="bi bi-file-earmark-text"></i>
                            <p>No description provided for this task.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Checklist card --}}
            <div class="ts-card">
                <div class="ts-card-header">
                    <div class="ts-card-title">
                        <i class="bi bi-list-check"></i> Checklist
                        @if($checklistTotal > 0)
                            <span style="font-size:12px; color:#9ca3af; font-weight:400; margin-left:4px;">({{ $checklistDone }} of {{ $checklistTotal }} done)</span>
                        @endif
                    </div>
                    @if($checklistTotal > 0)
                    <div style="display:flex; align-items:center; gap:8px;">
                        <div class="ts-cl-prog-wrap" style="width:100px; margin-top:0; display:inline-block; vertical-align:middle;">
                            <div class="ts-cl-prog-fill" id="cl-bar" style="width:{{ $checklistPct }}%"></div>
                        </div>
                        <span id="cl-pct" style="font-size:12px; font-weight:700; color:#7c3aed; min-width:32px;">{{ $checklistPct }}%</span>
                    </div>
                    @endif
                </div>
                <div class="ts-card-body">

                    <div id="cl-items">
                        @forelse($task->checklistItems as $item)
                            <div class="ts-cl-item {{ $item->completed ? 'completed' : '' }}" data-id="{{ $item->id }}">
                                <div class="ts-cl-check {{ $item->completed ? 'checked' : '' }}"
                                     onclick="toggleChecklistItem({{ $item->id }})">
                                    @if($item->completed)
                                        <i class="bi bi-check" style="font-size:12px;"></i>
                                    @endif
                                </div>
                                <div class="ts-cl-text">{{ $item->name }}</div>
                                <button class="ts-cl-del" onclick="deleteChecklistItem({{ $item->id }})" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        @empty
                            <div id="cl-empty" class="ts-empty" style="padding:20px 0;">
                                <i class="bi bi-list-check"></i>
                                <p>No checklist items yet. Add some below!</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Add form --}}
                    <form class="ts-cl-add" onsubmit="addChecklistItem(event)">
                        <input type="text" class="ts-cl-input" id="cl-input"
                               placeholder="Add checklist item…" required>
                        <button type="submit" class="ts-cl-btn">
                            <i class="bi bi-plus"></i> Add
                        </button>
                    </form>
                </div>
            </div>

        </div>{{-- end .ts-content --}}
    </div>{{-- end .ts-body --}}
</div>{{-- end .ts-wrap --}}

{{-- Delete form --}}
<form id="deleteForm" action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>

{{-- Status Modal --}}
<div id="statusModal" class="ts-modal-overlay" style="display:none;" onclick="if(event.target===this)closeStatusModal()">
    <div class="ts-modal-box">
        <div class="ts-modal-head">
            <h5><i class="bi bi-arrow-repeat" style="margin-right:8px;"></i>Change Status</h5>
            <button onclick="closeStatusModal()" style="background:rgba(255,255,255,.2); border:none; color:#fff; border-radius:6px; width:28px; height:28px; cursor:pointer; font-size:16px; display:flex; align-items:center; justify-content:center;"><i class="bi bi-x"></i></button>
        </div>
        <div class="ts-modal-body">
            @foreach([
                'to_do'       => ['label'=>'To Do',       'dot'=>'#9ca3af', 'desc'=>'Task is ready to start'],
                'in_progress' => ['label'=>'In Progress', 'dot'=>'#7c3aed', 'desc'=>'Currently being worked on'],
                'on_hold'     => ['label'=>'On Hold',     'dot'=>'#b45309', 'desc'=>'Paused, waiting on a blocker'],
                'in_review'   => ['label'=>'In Review',   'dot'=>'#1d4ed8', 'desc'=>'Awaiting review or approval'],
                'completed'   => ['label'=>'Completed',   'dot'=>'#16a34a', 'desc'=>'Task is finished'],
            ] as $val => $opt)
            <div class="ts-status-opt {{ $task->status === $val ? 'selected' : '' }}" onclick="selectStatus('{{ $val }}', this)" data-status="{{ $val }}">
                <input type="radio" name="status" value="{{ $val }}" {{ $task->status === $val ? 'checked' : '' }}>
                <span class="ts-status-chip {{ $val }}">
                    <span style="width:7px;height:7px;border-radius:50%;background:{{ $opt['dot'] }};display:inline-block;"></span>
                    {{ $opt['label'] }}
                </span>
                <span style="font-size:12px; color:#6b7280;">{{ $opt['desc'] }}</span>
            </div>
            @endforeach
        </div>
        <div class="ts-modal-foot">
            <button class="ts-modal-cancel" onclick="closeStatusModal()">Cancel</button>
            <button class="ts-modal-save" onclick="saveStatus()">Update Status</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const TASK_ID = '{{ $task->id }}';
const CSRF    = '{{ csrf_token() }}';
let selectedStatus = '{{ $task->status }}';

/* ── Status modal ─────────────────────────────────────────── */
function openStatusModal() {
    document.getElementById('statusModal').style.display = 'flex';
}
function closeStatusModal() {
    document.getElementById('statusModal').style.display = 'none';
}
function selectStatus(val, el) {
    selectedStatus = val;
    document.querySelectorAll('.ts-status-opt').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
}
function saveStatus() {
    const current = '{{ $task->status }}';
    if (selectedStatus === current) { closeStatusModal(); return; }

    fetch(`/tasks/${TASK_ID}/update-status`, {
        method: 'POST',
        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ status: selectedStatus })
    })
    .then(r => r.json())
    .then(() => { location.reload(); })
    .catch(() => showToast('Failed to update status', false));
}

/* ── Delete ───────────────────────────────────────────────── */
function confirmDelete() {
    if (confirm('Delete this task? This action cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}

/* ── Toast ────────────────────────────────────────────────── */
function showToast(msg, ok = true) {
    const d = document.createElement('div');
    d.style.cssText = `position:fixed;top:20px;right:20px;z-index:9999;padding:12px 18px;
        border-radius:10px;font-size:13px;font-weight:600;color:#fff;
        background:${ok?'#16a34a':'#dc2626'};box-shadow:0 4px 14px rgba(0,0,0,.25);`;
    d.textContent = msg;
    document.body.appendChild(d);
    setTimeout(() => d.remove(), 3500);
}

/* ── Checklist helpers ────────────────────────────────────── */
function updateChecklistUI() {
    const items  = document.querySelectorAll('#cl-items .ts-cl-item');
    const done   = document.querySelectorAll('#cl-items .ts-cl-item.completed');
    const total  = items.length;
    const pct    = total > 0 ? Math.round(done.length / total * 100) : 0;

    const bar = document.getElementById('cl-bar');
    const txt = document.getElementById('cl-pct');
    if (bar) bar.style.width = pct + '%';
    if (txt) txt.textContent = pct + '%';

    const empty = document.getElementById('cl-empty');
    if (empty) empty.style.display = total === 0 ? 'block' : 'none';
}

function toggleChecklistItem(id) {
    const row  = document.querySelector(`[data-id="${id}"]`);
    const box  = row.querySelector('.ts-cl-check');
    fetch(`/checklist-items/${id}/update-status`, {
        headers: { 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            row.classList.toggle('completed');
            box.classList.toggle('checked');
            box.innerHTML = row.classList.contains('completed')
                ? '<i class="bi bi-check" style="font-size:12px;"></i>' : '';
            updateChecklistUI();
        }
    })
    .catch(() => showToast('Failed to update item', false));
}

function addChecklistItem(e) {
    e.preventDefault();
    const input = document.getElementById('cl-input');
    const name  = input.value.trim();
    if (!name) return;

    fetch('/checklist-items', {
        method: 'POST',
        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ task_id: TASK_ID, name })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            const container = document.getElementById('cl-items');
            const div = document.createElement('div');
            div.className = 'ts-cl-item';
            div.setAttribute('data-id', d.data.id);
            div.innerHTML = `
                <div class="ts-cl-check" onclick="toggleChecklistItem(${d.data.id})"></div>
                <div class="ts-cl-text">${d.data.name}</div>
                <button class="ts-cl-del" onclick="deleteChecklistItem(${d.data.id})" title="Delete">
                    <i class="bi bi-trash"></i>
                </button>`;
            container.appendChild(div);
            input.value = '';
            updateChecklistUI();
            showToast('Item added');
        }
    })
    .catch(() => showToast('Failed to add item', false));
}

function deleteChecklistItem(id) {
    if (!confirm('Delete this checklist item?')) return;
    fetch(`/checklist-items/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            document.querySelector(`[data-id="${id}"]`).remove();
            updateChecklistUI();
            showToast('Item removed');
        }
    })
    .catch(() => showToast('Failed to delete item', false));
}

document.addEventListener('DOMContentLoaded', updateChecklistUI);
</script>
@endpush
