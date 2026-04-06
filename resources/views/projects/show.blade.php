@extends('layouts.app')

@section('title', $project->name . ' — Project Details')

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

    /* ─── Two-panel layout ───────────────────────────────────── */
    .cu-layout {
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 14px;
        align-items: start;
    }
    @media (max-width: 900px) {
        .cu-layout { grid-template-columns: 1fr; }
    }

    /* ─── Left Info Panel ────────────────────────────────────── */
    .cu-left-panel {
        background: white;
        border: 1px solid #e3e4e8;
        border-radius: 10px;
        overflow: hidden;
        position: sticky;
        top: 14px;
    }
    .cu-panel-top {
        background: #f7f8fa;
        border-bottom: 1px solid #e3e4e8;
        padding: 18px 14px 16px;
        text-align: center;
    }
    .cu-panel-avatar {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 24px;
        color: white;
        margin: 0 auto 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,.15);
    }
    .cu-panel-name {
        font-weight: 700;
        font-size: 14px;
        color: #1a1d23;
        margin-bottom: 6px;
        word-break: break-word;
    }
    .cu-panel-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .chip-not-started  { background:#f1f5f9; color:#64748b; }
    .chip-in-progress  { background:#ede9fe; color:#7c3aed; }
    .chip-completed    { background:#dcfce7; color:#16a34a; }
    .chip-closed       { background:#f3f4f6; color:#9ca3af; }
    .chip-pending      { background:#fef9c3; color:#a16207; }
    .chip-on-going     { background:#dbeafe; color:#1d4ed8; }
    .chip-unfinished   { background:#fee2e2; color:#dc2626; }
    .chip-finished     { background:#dcfce7; color:#16a34a; }

    .cu-panel-rows {
        padding: 4px 0;
    }
    .cu-panel-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 14px;
        border-bottom: 1px solid #f0f1f3;
        font-size: 12px;
    }
    .cu-panel-row:last-child { border-bottom: none; }
    .cu-panel-row-label {
        color: #8a8f98;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .cu-panel-row-value {
        color: #1a1d23;
        font-weight: 600;
        text-align: right;
    }
    .cu-panel-actions {
        border-top: 1px solid #e3e4e8;
        padding: 10px 14px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .cu-panel-action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid #e3e4e8;
        background: white;
        color: #3d4149;
        cursor: pointer;
        transition: all .15s;
    }
    .cu-panel-action-btn:hover {
        border-color: #7c3aed;
        background: #f5f3ff;
        color: #7c3aed;
    }
    .cu-panel-action-btn.primary {
        background: #7c3aed;
        border-color: #7c3aed;
        color: white;
    }
    .cu-panel-action-btn.primary:hover {
        background: #6d28d9;
        border-color: #6d28d9;
        color: white;
    }
    .cu-panel-action-btn.danger {
        border-color: #fca5a5;
        color: #dc2626;
    }
    .cu-panel-action-btn.danger:hover {
        background: #fef2f2;
        border-color: #dc2626;
    }

    /* ─── Right: Section Cards ───────────────────────────────── */
    .cu-section {
        background: white;
        border: 1px solid #e3e4e8;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 12px;
    }
    .cu-section:last-child { margin-bottom: 0; }
    .cu-section-header {
        background: #f7f8fa;
        border-bottom: 1px solid #e3e4e8;
        padding: 10px 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .cu-section-icon {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        background: #ede9fe;
        color: #7c3aed;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
    }
    .cu-section-icon.green  { background: #dcfce7; color: #16a34a; }
    .cu-section-icon.blue   { background: #dbeafe; color: #1d4ed8; }
    .cu-section-icon.orange { background: #fef3c7; color: #d97706; }
    .cu-section-title {
        font-weight: 700;
        font-size: 13px;
        color: #1a1d23;
    }
    .cu-section-body {
        padding: 16px;
    }

    /* ─── Progress Ring ──────────────────────────────────────── */
    .cu-ring-wrap {
        position: relative;
        width: 90px;
        height: 90px;
        flex-shrink: 0;
    }
    .cu-ring-wrap svg {
        width: 90px;
        height: 90px;
        transform: rotate(-90deg);
    }
    .cu-ring-wrap circle {
        fill: transparent;
        stroke-width: 7;
        r: 38;
        cx: 45;
        cy: 45;
        stroke-dasharray: 238.76;
        stroke-linecap: round;
    }
    .cu-ring-wrap .bg   { stroke: #f0f1f3; }
    .cu-ring-wrap .fg   { stroke: #7c3aed; stroke-dashoffset: 238.76; transition: stroke-dashoffset .8s ease; }
    .cu-ring-pct {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 800;
        color: #1a1d23;
    }
    .cu-stat-tiles {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        flex: 1;
    }
    .cu-stat-tile {
        background: #f7f8fa;
        border: 1px solid #e3e4e8;
        border-radius: 8px;
        padding: 12px;
        text-align: center;
    }
    .cu-stat-num {
        font-size: 26px;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 4px;
    }
    .cu-stat-label {
        font-size: 11px;
        color: #8a8f98;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .cu-progress-bar-wrap {
        background: #f0f1f3;
        border-radius: 4px;
        height: 6px;
        margin-top: 14px;
        overflow: hidden;
    }
    .cu-progress-bar-fill {
        height: 6px;
        border-radius: 4px;
        transition: width .6s ease;
    }

    /* ─── Description ────────────────────────────────────────── */
    .cu-desc {
        line-height: 1.7;
        color: #3d4149;
        font-size: 14px;
    }
    .cu-desc h1, .cu-desc h2 { color: #1a1d23; margin: 18px 0 8px; }
    .cu-desc h1 { font-size: 1.3rem; }
    .cu-desc h2 { font-size: 1.1rem; }
    .cu-desc p  { margin-bottom: 10px; }
    .cu-desc ul, .cu-desc ol { padding-left: 22px; margin-bottom: 10px; }
    .cu-desc li { margin-bottom: 5px; }
    .cu-desc blockquote {
        border-left: 3px solid #7c3aed;
        background: #f5f3ff;
        padding: 12px 16px;
        border-radius: 0 6px 6px 0;
        margin: 12px 0;
    }
    .cu-desc code {
        background: #f0f1f3;
        padding: 2px 5px;
        border-radius: 4px;
        font-size: .88em;
    }
    .cu-desc pre {
        background: #f0f1f3;
        padding: 14px;
        border-radius: 7px;
        overflow-x: auto;
        margin: 12px 0;
    }

    /* ─── Team Members ───────────────────────────────────────── */
    .cu-member {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 0;
        border-bottom: 1px solid #f0f1f3;
    }
    .cu-member:last-child { border-bottom: none; }
    .cu-member-av {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        color: white;
        flex-shrink: 0;
    }
    .cu-member-name {
        font-weight: 600;
        font-size: 13px;
        color: #1a1d23;
        line-height: 1.2;
    }
    .cu-member-email {
        font-size: 11px;
        color: #8a8f98;
    }

    /* ─── Modal ──────────────────────────────────────────────── */
    .modal-content {
        border: none;
        border-radius: 14px;
        box-shadow: 0 25px 50px rgba(0,0,0,.2);
        border: 1px solid #e3e4e8;
    }
    .modal-header {
        border-bottom: 1px solid #e3e4e8;
        padding: 12px 16px;
        background: #f7f8fa;
        border-radius: 14px 14px 0 0;
    }
    .modal-body   { padding: 16px; }
    .modal-footer {
        border-top: 1px solid #e3e4e8;
        padding: 10px 16px;
        background: #f7f8fa;
        border-radius: 0 0 14px 14px;
    }
</style>
@endpush

@section('content')
<div class="main-content">

    {{-- ── Page Header ────────────────────────────────────────── --}}
    <div class="content-header">
        <div class="d-flex align-items-center" style="position:relative;z-index:1;">
            <a href="{{ route('projects.index') }}" class="me-3 text-decoration-none">
                <i class="bi bi-arrow-left fs-5" style="color:rgba(255,255,255,.8);"></i>
            </a>
            <div>
                <h1 class="content-title mb-0">{{ $project->name }}</h1>
                <p class="content-subtitle">Project Overview</p>
            </div>
        </div>
    </div>

    @php
        $colors = ['#6366f1','#8b5cf6','#06b6d4','#10b981','#f59e0b','#ef4444','#ec4899'];
        $projectColor = $colors[strlen($project->name) % count($colors)];

        $totalTasks     = $project->tasks->count();
        $completedTasks = $project->tasks->where('status', 'completed')->count();
        $inProgressTasks= $project->tasks->where('status', 'in_progress')->count();
        $todoTasks      = $project->tasks->whereNotIn('status', ['completed','in_progress'])->count();
        $progress       = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
        $ringOffset     = 238.76 - (238.76 * $progress / 100);

        $rawStatus = $project->status;
        $chipMap = [
            'not_started' => ['label'=>'Not Started', 'class'=>'chip-not-started'],
            'in_progress' => ['label'=>'In Progress',  'class'=>'chip-in-progress'],
            'completed'   => ['label'=>'Completed',    'class'=>'chip-completed'],
            'closed'      => ['label'=>'Closed',       'class'=>'chip-closed'],
            'pending'     => ['label'=>'Pending',      'class'=>'chip-pending'],
            'on_going'    => ['label'=>'On Going',     'class'=>'chip-on-going'],
            'unfinished'  => ['label'=>'Unfinished',   'class'=>'chip-unfinished'],
            'finished'    => ['label'=>'Finished',     'class'=>'chip-finished'],
        ];
        $chip = $chipMap[$rawStatus] ?? ['label' => ucwords(str_replace('_',' ',$rawStatus)), 'class' => 'chip-not-started'];
    @endphp

    <div class="cu-layout">

        {{-- ── LEFT: Info Panel ─────────────────────────────────── --}}
        <div class="cu-left-panel">
            <div class="cu-panel-top">
                <div class="cu-panel-avatar" style="background:{{ $projectColor }};">
                    {{ strtoupper(substr($project->name,0,1)) }}
                </div>
                <div class="cu-panel-name">{{ $project->name }}</div>
                <span class="cu-panel-chip {{ $chip['class'] }}">
                    <i class="bi bi-circle-fill" style="font-size:6px;"></i>
                    {{ $chip['label'] }}
                </span>
            </div>

            <div class="cu-panel-rows">
                <div class="cu-panel-row">
                    <span class="cu-panel-row-label"><i class="bi bi-calendar-event"></i> Start</span>
                    <span class="cu-panel-row-value">
                        {{ $project->start_date ? $project->start_date->format('M d, Y') : '—' }}
                    </span>
                </div>
                <div class="cu-panel-row">
                    <span class="cu-panel-row-label"><i class="bi bi-calendar-check"></i> Due</span>
                    <span class="cu-panel-row-value" style="{{ $project->end_date && $project->end_date->isPast() ? 'color:#dc2626;' : '' }}">
                        {{ $project->end_date ? $project->end_date->format('M d, Y') : '—' }}
                        @if($project->end_date && $project->end_date->isPast())
                            <small class="d-block" style="font-size:10px;">Overdue</small>
                        @elseif($project->end_date)
                            <small class="d-block" style="font-size:10px;color:#8a8f98;">{{ $project->end_date->diffForHumans() }}</small>
                        @endif
                    </span>
                </div>
                <div class="cu-panel-row">
                    <span class="cu-panel-row-label"><i class="bi bi-currency-dollar"></i> Budget</span>
                    <span class="cu-panel-row-value">${{ number_format($project->budget ?? 0, 0) }}</span>
                </div>
                <div class="cu-panel-row">
                    <span class="cu-panel-row-label"><i class="bi bi-list-task"></i> Tasks</span>
                    <span class="cu-panel-row-value">{{ $totalTasks }}</span>
                </div>
                <div class="cu-panel-row">
                    <span class="cu-panel-row-label"><i class="bi bi-people"></i> Team</span>
                    <span class="cu-panel-row-value">{{ $teamMembers->count() }} members</span>
                </div>
                <div class="cu-panel-row">
                    <span class="cu-panel-row-label"><i class="bi bi-clock-history"></i> Updated</span>
                    <span class="cu-panel-row-value">{{ $project->updated_at->diffForHumans() }}</span>
                </div>
            </div>

            <div class="cu-panel-actions">
                <a href="{{ route('projects.tasks.index', $project) }}" class="cu-panel-action-btn primary">
                    <i class="bi bi-list-task"></i> View Tasks
                </a>
                <a href="{{ route('projects.edit', $project) }}" class="cu-panel-action-btn">
                    <i class="bi bi-pencil"></i> Edit Project
                </a>
                <button type="button" class="cu-panel-action-btn" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                    <i class="bi bi-person-plus"></i> Add Member
                </button>
                <button type="button" class="cu-panel-action-btn danger" onclick="confirmDelete()">
                    <i class="bi bi-trash"></i> Delete Project
                </button>
                <form id="deleteForm" action="{{ route('projects.destroy', $project) }}" method="POST" class="d-none">
                    @csrf @method('DELETE')
                </form>
            </div>
        </div>

        {{-- ── RIGHT: Sections ──────────────────────────────────── --}}
        <div>

            {{-- ── Progress Section ──────────────────────────────── --}}
            <div class="cu-section">
                <div class="cu-section-header">
                    <span class="cu-section-icon green"><i class="bi bi-bar-chart-line"></i></span>
                    <span class="cu-section-title">Progress Overview</span>
                </div>
                <div class="cu-section-body">
                    <div class="d-flex align-items-center gap-4">
                        <div class="cu-ring-wrap">
                            <svg>
                                <circle class="bg"></circle>
                                <circle class="fg" id="progressRing"
                                        style="stroke:{{ $projectColor }}; stroke-dashoffset:{{ $ringOffset }};"></circle>
                            </svg>
                            <div class="cu-ring-pct">{{ round($progress) }}%</div>
                        </div>
                        <div class="cu-stat-tiles">
                            <div class="cu-stat-tile">
                                <div class="cu-stat-num" style="color:#16a34a;">{{ $completedTasks }}</div>
                                <div class="cu-stat-label">Done</div>
                            </div>
                            <div class="cu-stat-tile">
                                <div class="cu-stat-num" style="color:#7c3aed;">{{ $inProgressTasks }}</div>
                                <div class="cu-stat-label">Active</div>
                            </div>
                            <div class="cu-stat-tile">
                                <div class="cu-stat-num" style="color:#8a8f98;">{{ $todoTasks }}</div>
                                <div class="cu-stat-label">To Do</div>
                            </div>
                        </div>
                    </div>
                    @if($totalTasks > 0)
                        <div class="cu-progress-bar-wrap">
                            <div class="cu-progress-bar-fill" id="progressBar"
                                 style="background:{{ $projectColor }}; width:0%;"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1" style="font-size:11px;color:#8a8f98;">
                            <span>{{ $completedTasks }} of {{ $totalTasks }} tasks completed</span>
                            <span>{{ round($progress) }}%</span>
                        </div>
                    @else
                        <p class="mt-3 mb-0" style="font-size:13px;color:#8a8f98;">
                            <i class="bi bi-info-circle me-1"></i>No tasks yet.
                            <a href="{{ route('projects.tasks.index', $project) }}" style="color:#7c3aed;">Add your first task →</a>
                        </p>
                    @endif
                </div>
            </div>

            {{-- ── Description Section ────────────────────────────── --}}
            <div class="cu-section">
                <div class="cu-section-header">
                    <span class="cu-section-icon blue"><i class="bi bi-file-text"></i></span>
                    <span class="cu-section-title">Description</span>
                </div>
                <div class="cu-section-body">
                    @if($project->description)
                        <div class="cu-desc">
                            {!! $project->description !!}
                        </div>
                    @else
                        <p class="mb-0" style="color:#8a8f98;font-size:13px;font-style:italic;">
                            No description provided.
                            <a href="{{ route('projects.edit', $project) }}" style="color:#7c3aed;">Add one →</a>
                        </p>
                    @endif
                </div>
            </div>

            {{-- ── Team Members Section ───────────────────────────── --}}
            <div class="cu-section">
                <div class="cu-section-header" style="justify-content:space-between;">
                    <div class="d-flex align-items-center gap-2">
                        <span class="cu-section-icon orange"><i class="bi bi-people"></i></span>
                        <span class="cu-section-title">Team Members ({{ $teamMembers->count() }})</span>
                    </div>
                    <button class="cu-panel-action-btn" style="padding:4px 10px;font-size:11px;"
                            data-bs-toggle="modal" data-bs-target="#addMemberModal">
                        <i class="bi bi-plus-lg"></i> Add
                    </button>
                </div>
                <div class="cu-section-body" style="padding-top:8px;padding-bottom:8px;">
                    @forelse($teamMembers as $member)
                        <div class="cu-member">
                            <div class="cu-member-av" style="background:{{ $colors[strlen($member->name) % count($colors)] }};">
                                {{ strtoupper(substr($member->name,0,1)) }}
                            </div>
                            <div>
                                <div class="cu-member-name">{{ $member->name }}</div>
                                <div class="cu-member-email">{{ $member->email }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="bi bi-people" style="font-size:28px;color:#e3e4e8;display:block;margin-bottom:8px;"></i>
                            <p style="font-size:13px;color:#8a8f98;margin-bottom:10px;">No team members yet.</p>
                            <button class="cu-panel-action-btn primary" style="display:inline-flex;max-width:160px;margin:0 auto;"
                                    data-bs-toggle="modal" data-bs-target="#addMemberModal">
                                <i class="bi bi-person-plus"></i> Add First Member
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>{{-- end right col --}}
    </div>{{-- end cu-layout --}}
</div>

{{-- ── Add Member Modal ──────────────────────────────────────────── --}}
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title mb-0" id="addMemberModalLabel">
                    <i class="bi bi-person-plus me-2 text-primary"></i>Add Team Member
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('projects.addMember') }}" method="POST">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <div class="modal-body">
                    <label for="user_id" class="form-label" style="font-size:13px;font-weight:600;color:#3d4149;">
                        Select User
                    </label>
                    <select class="form-select" name="user_id" id="user_id" required style="font-size:13px;">
                        <option value="">Choose a user…</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Add Member</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress ring
    const ring = document.getElementById('progressRing');
    const bar  = document.getElementById('progressBar');
    if (ring) {
        const target = parseFloat(ring.style.strokeDashoffset);
        ring.style.strokeDashoffset = '238.76';
        setTimeout(() => { ring.style.strokeDashoffset = target; }, 200);
    }
    if (bar) {
        const w = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => { bar.style.width = w; }, 200);
    }
});

function confirmDelete() {
    if (confirm('Delete "{{ addslashes($project->name) }}"? All tasks, files and data will be permanently removed. This cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endpush
