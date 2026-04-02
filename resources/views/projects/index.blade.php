@extends('layouts.app')

@section('title', 'Projects')

@push('styles')
<style>
    /* ─── Page Shell ───────────────────────────────────────────── */
    .main-content {
        padding: 14px 16px;
        background: #f7f8fa;
        min-height: 100vh;
    }

    /* ─── Gradient Page Header ─────────────────────────────────── */
    .content-header {
        background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
        border-radius: 10px;
        padding: 14px 20px;
        color: white;
        margin-bottom: 12px;
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
        font-size: 18px;
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

    .cu-new-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: white;
        color: #6d28d9;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all .15s;
        position: relative;
        z-index: 1;
    }

    .cu-new-btn:hover { background: #f5f3ff; color: #5b21b6; }

    /* ─── Stat Strip ───────────────────────────────────────────── */
    .cu-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-bottom: 12px;
    }

    .cu-stat {
        background: white;
        border: 1px solid #e3e4e8;
        border-radius: 8px;
        padding: 12px 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .cu-stat-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }

    .cu-stat-icon.total    { background: #ede9fe; color: #7c3aed; }
    .cu-stat-icon.active   { background: #dbeafe; color: #2563eb; }
    .cu-stat-icon.done     { background: #dcfce7; color: #16a34a; }
    .cu-stat-icon.overdue  { background: #fee2e2; color: #dc2626; }

    .cu-stat-num {
        font-size: 20px;
        font-weight: 800;
        color: #1a1d23;
        line-height: 1;
    }

    .cu-stat-label {
        font-size: 11px;
        color: #8a8f98;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    /* ─── Toolbar ──────────────────────────────────────────────── */
    .cu-toolbar {
        background: white;
        border: 1px solid #e3e4e8;
        border-radius: 8px;
        padding: 8px 12px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .cu-search-wrap {
        position: relative;
        flex: 1;
        min-width: 180px;
    }

    .cu-search-wrap > i {
        position: absolute;
        left: 9px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 13px;
        color: #adb0b8;
    }

    .cu-search {
        width: 100%;
        height: 30px;
        padding: 0 10px 0 30px;
        border: 1px solid #d3d5db;
        border-radius: 6px;
        background: #fafbfc;
        font-size: 13px;
        color: #1a1d23;
        outline: none;
    }

    .cu-search:focus { border-color: #7c3aed; background: white; box-shadow: 0 0 0 2px rgba(124,58,237,.12); }
    .cu-search::placeholder { color: #b0b4be; }

    .cu-filter {
        height: 30px;
        padding: 0 10px;
        border: 1px solid #d3d5db;
        border-radius: 6px;
        background: white;
        font-size: 12px;
        color: #374151;
        outline: none;
        cursor: pointer;
    }

    .cu-filter:focus { border-color: #7c3aed; }

    .cu-view-toggle {
        display: flex;
        border: 1px solid #d3d5db;
        border-radius: 6px;
        overflow: hidden;
    }

    .cu-view-btn {
        width: 30px;
        height: 30px;
        border: none;
        background: white;
        color: #8a8f98;
        font-size: 13px;
        cursor: pointer;
        transition: all .15s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cu-view-btn:first-child { border-right: 1px solid #d3d5db; }
    .cu-view-btn.active { background: #7c3aed; color: white; }
    .cu-view-btn:not(.active):hover { background: #f3f4f6; color: #374151; }

    /* ─── Grid View ────────────────────────────────────────────── */
    .cu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 10px;
    }

    .cu-card {
        background: white;
        border: 1px solid #e3e4e8;
        border-radius: 8px;
        overflow: hidden;
        transition: box-shadow .15s, border-color .15s;
        position: relative;
    }

    .cu-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.1); border-color: #c4b5fd; }

    .cu-color-bar {
        height: 3px;
        width: 100%;
    }

    .cu-card-body {
        padding: 12px 14px;
    }

    .cu-card-top {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 8px;
    }

    .cu-avatar {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 16px;
        color: white;
        flex-shrink: 0;
    }

    .cu-card-name {
        font-size: 13px;
        font-weight: 700;
        color: #1a1d23;
        margin: 0 0 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cu-card-desc {
        font-size: 11px;
        color: #8a8f98;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.5;
    }

    .cu-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .cu-chip.not-started { background: #f1f2f4; color: #6b7385; }
    .cu-chip.in-progress { background: #e8f0fe; color: #2563eb; }
    .cu-chip.completed   { background: #e6f9f0; color: #16a34a; }

    .cu-progress-wrap {
        margin: 8px 0;
    }

    .cu-progress-bar-bg {
        height: 4px;
        background: #f0f1f3;
        border-radius: 4px;
        overflow: hidden;
    }

    .cu-progress-bar-fill {
        height: 100%;
        border-radius: 4px;
        transition: width .3s;
    }

    .cu-card-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 11px;
        color: #8a8f98;
        margin-bottom: 8px;
        flex-wrap: wrap;
    }

    .cu-card-meta i { font-size: 11px; }
    .cu-card-meta .overdue { color: #dc2626; font-weight: 600; }

    .cu-team-avatars {
        display: flex;
    }

    .cu-team-avatar {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #7c3aed;
        color: white;
        font-size: 9px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: -5px;
        border: 2px solid white;
    }

    .cu-team-avatar:first-child { margin-left: 0; }

    .cu-card-actions {
        display: flex;
        gap: 4px;
        padding-top: 8px;
        border-top: 1px solid #f0f1f3;
    }

    .cu-action-btn {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        border: 1px solid #e3e4e8;
        background: white;
        color: #8a8f98;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        text-decoration: none;
        transition: all .15s;
        cursor: pointer;
    }

    .cu-action-btn:hover { background: #f3f4f6; color: #374151; border-color: #adb0b8; }
    .cu-action-btn.danger:hover { background: #fee2e2; color: #dc2626; border-color: #fca5a5; }

    /* ─── List View ────────────────────────────────────────────── */
    .cu-list { display: none; flex-direction: column; gap: 0; }

    .cu-list-header {
        display: grid;
        grid-template-columns: 2fr 1fr 120px 80px 90px;
        gap: 12px;
        padding: 6px 14px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: #8a8f98;
        background: #fafbfc;
        border: 1px solid #e3e4e8;
        border-radius: 8px 8px 0 0;
    }

    .cu-list-row {
        display: grid;
        grid-template-columns: 2fr 1fr 120px 80px 90px;
        gap: 12px;
        padding: 10px 14px;
        background: white;
        border: 1px solid #e3e4e8;
        border-top: none;
        align-items: center;
        transition: background .12s;
    }

    .cu-list-row:last-child { border-radius: 0 0 8px 8px; }
    .cu-list-row:hover { background: #fafbfc; }

    .cu-list-name {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #1a1d23;
        min-width: 0;
    }

    .cu-list-name span {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cu-list-avatar {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 11px;
        color: white;
        flex-shrink: 0;
    }

    .cu-list-meta {
        font-size: 11px;
        color: #8a8f98;
    }

    .cu-list-progress {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        color: #6b7385;
    }

    .cu-list-pb {
        flex: 1;
        height: 4px;
        background: #f0f1f3;
        border-radius: 4px;
        overflow: hidden;
    }

    .cu-list-pb-fill {
        height: 100%;
        border-radius: 4px;
    }

    .cu-list-actions {
        display: flex;
        gap: 4px;
        justify-content: flex-end;
    }

    /* ─── Empty State ──────────────────────────────────────────── */
    .cu-empty {
        text-align: center;
        padding: 40px 20px;
        background: white;
        border: 1px dashed #d3d5db;
        border-radius: 8px;
    }

    .cu-empty-icon {
        width: 52px;
        height: 52px;
        background: #ede9fe;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        font-size: 22px;
        color: #7c3aed;
    }

    .cu-empty h5 { font-size: 15px; font-weight: 700; color: #1a1d23; margin-bottom: 4px; }
    .cu-empty p  { font-size: 12px; color: #8a8f98; margin-bottom: 14px; }

    .cu-empty-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 16px;
        background: #7c3aed;
        color: white;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .cu-empty-btn:hover { background: #6d28d9; color: white; }

    /* ─── Alert ────────────────────────────────────────────────── */
    .cu-alert {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        background: #e6f9f0;
        border: 1px solid #6ee7b7;
        color: #065f46;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 10px;
    }

    .cu-alert-close {
        margin-left: auto;
        background: none;
        border: none;
        color: #065f46;
        cursor: pointer;
        font-size: 14px;
    }

    /* ─── Responsive ───────────────────────────────────────────── */
    @media (max-width: 768px) {
        .cu-stats { grid-template-columns: repeat(2, 1fr); }
        .cu-grid  { grid-template-columns: 1fr; }
        .cu-list-header, .cu-list-row { grid-template-columns: 1fr auto auto; }
        .cu-list-header .col-meta,
        .cu-list-header .col-prog,
        .cu-list-row .col-meta,
        .cu-list-row .col-prog { display: none; }
    }
</style>
@endpush

@section('content')
<div class="main-content">

    {{-- ── Gradient Header ─────────────────────────────────────── --}}
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center" style="position:relative;z-index:1;">
            <div>
                <h1 class="content-title">Projects</h1>
                <p class="content-subtitle">Manage and track your project progress</p>
            </div>
            <a href="{{ route('projects.create') }}" class="cu-new-btn">
                <i class="bi bi-plus-lg"></i>New Project
            </a>
        </div>
    </div>

    {{-- ── Stat Strip ──────────────────────────────────────────── --}}
    @php
        $totalProjects    = $projects->count();
        $activeProjects   = $projects->where('status', 'in_progress')->count();
        $doneProjects     = $projects->where('status', 'completed')->count();
        $overdueProjects  = $projects->filter(fn($p) => $p->end_date && $p->end_date->isPast() && $p->status !== 'completed')->count();
    @endphp
    <div class="cu-stats">
        <div class="cu-stat">
            <div class="cu-stat-icon total"><i class="bi bi-folder2"></i></div>
            <div><div class="cu-stat-num">{{ $totalProjects }}</div><div class="cu-stat-label">Total</div></div>
        </div>
        <div class="cu-stat">
            <div class="cu-stat-icon active"><i class="bi bi-lightning-charge"></i></div>
            <div><div class="cu-stat-num">{{ $activeProjects }}</div><div class="cu-stat-label">Active</div></div>
        </div>
        <div class="cu-stat">
            <div class="cu-stat-icon done"><i class="bi bi-check-circle"></i></div>
            <div><div class="cu-stat-num">{{ $doneProjects }}</div><div class="cu-stat-label">Completed</div></div>
        </div>
        <div class="cu-stat">
            <div class="cu-stat-icon overdue"><i class="bi bi-exclamation-circle"></i></div>
            <div><div class="cu-stat-num">{{ $overdueProjects }}</div><div class="cu-stat-label">Overdue</div></div>
        </div>
    </div>

    {{-- ── Toolbar ─────────────────────────────────────────────── --}}
    <div class="cu-toolbar">
        <div class="cu-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" class="cu-search" id="projectSearch" placeholder="Search projects…" autocomplete="off">
        </div>
        <select class="cu-filter" id="statusFilter">
            <option value="">All Status</option>
            <option value="not_started">Not Started</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
        </select>
        <select class="cu-filter" id="sortFilter">
            <option value="name">Name A–Z</option>
            <option value="created_at">Newest</option>
            <option value="progress">Progress</option>
        </select>
        <div class="cu-view-toggle">
            <button class="cu-view-btn active" data-view="grid" title="Grid"><i class="bi bi-grid-3x3-gap"></i></button>
            <button class="cu-view-btn" data-view="list" title="List"><i class="bi bi-list-ul"></i></button>
        </div>
    </div>

    {{-- ── Projects ─────────────────────────────────────────────── --}}
    @if($projects->count() > 0)

        {{-- Grid --}}
        <div class="cu-grid" id="cuGrid">
            @foreach($projects as $project)
                @php
                    $totalTasks     = $project->tasks->count();
                    $completedTasks = $project->tasks->where('status', 'completed')->count();
                    $progress       = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                    $colors         = ['#6366f1','#8b5cf6','#06b6d4','#10b981','#f59e0b','#ef4444','#ec4899'];
                    $projectColor   = $colors[strlen($project->name) % count($colors)];
                    $chipClass      = match($project->status) {
                        'in_progress' => 'in-progress',
                        'completed'   => 'completed',
                        default       => 'not-started'
                    };
                    $chipLabel = match($project->status) {
                        'in_progress' => 'In Progress',
                        'completed'   => 'Completed',
                        default       => 'Not Started'
                    };
                    $teamCount = $project->teamMembers ? $project->teamMembers->count() : 0;
                    $isOverdue = $project->end_date && $project->end_date->isPast() && $project->status !== 'completed';
                @endphp
                <div class="cu-card project-item"
                     data-name="{{ strtolower($project->name) }}"
                     data-status="{{ $project->status }}"
                     data-progress="{{ $progress }}"
                     data-created="{{ $project->created_at->timestamp }}">
                    <div class="cu-color-bar" style="background:{{ $projectColor }};"></div>
                    <div class="cu-card-body">
                        <div class="cu-card-top">
                            <div class="cu-avatar" style="background:{{ $projectColor }};">
                                {{ strtoupper(substr($project->name, 0, 1)) }}
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="cu-card-name" title="{{ $project->name }}">{{ $project->name }}</div>
                                <div class="cu-card-desc">{{ strip_tags($project->description) ?: 'No description' }}</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="cu-chip {{ $chipClass }}">
                                <i class="bi bi-circle-fill" style="font-size:6px;"></i>{{ $chipLabel }}
                            </span>
                            <span style="font-size:11px; color:#8a8f98;">{{ round($progress) }}%</span>
                        </div>

                        <div class="cu-progress-wrap">
                            <div class="cu-progress-bar-bg">
                                <div class="cu-progress-bar-fill" style="background:{{ $projectColor }}; width:{{ $progress }}%;"></div>
                            </div>
                        </div>

                        <div class="cu-card-meta">
                            @if($project->end_date)
                                <span class="{{ $isOverdue ? 'overdue' : '' }}">
                                    <i class="bi bi-calendar3"></i>
                                    @if($isOverdue) Overdue
                                    @else {{ $project->end_date->format('M d') }}
                                    @endif
                                </span>
                            @endif
                            @if($totalTasks > 0)
                                <span><i class="bi bi-check2-square"></i>{{ $completedTasks }}/{{ $totalTasks }}</span>
                            @endif
                            @if($teamCount > 0)
                                <span class="d-flex align-items-center gap-1">
                                    <div class="cu-team-avatars">
                                        @foreach($project->teamMembers->take(3) as $member)
                                            <div class="cu-team-avatar" title="{{ $member->name }}">{{ strtoupper(substr($member->name,0,1)) }}</div>
                                        @endforeach
                                        @if($teamCount > 3)<div class="cu-team-avatar">+{{ $teamCount - 3 }}</div>@endif
                                    </div>
                                    {{ $teamCount }}
                                </span>
                            @endif
                        </div>

                        <div class="cu-card-actions">
                            <a href="{{ route('projects.tasks.index', $project) }}" class="cu-action-btn" title="Tasks"><i class="bi bi-list-task"></i></a>
                            <a href="{{ route('projects.show', $project) }}" class="cu-action-btn" title="View"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('projects.edit', $project) }}" class="cu-action-btn" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this project? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="cu-action-btn danger" title="Delete"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- List --}}
        <div class="cu-list" id="cuList">
            <div class="cu-list-header">
                <div>Project</div>
                <div class="col-meta">Due Date</div>
                <div class="col-prog">Progress</div>
                <div>Status</div>
                <div></div>
            </div>
            @foreach($projects as $project)
                @php
                    $totalTasks     = $project->tasks->count();
                    $completedTasks = $project->tasks->where('status', 'completed')->count();
                    $progress       = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                    $colors         = ['#6366f1','#8b5cf6','#06b6d4','#10b981','#f59e0b','#ef4444','#ec4899'];
                    $projectColor   = $colors[strlen($project->name) % count($colors)];
                    $chipClass      = match($project->status) { 'in_progress'=>'in-progress','completed'=>'completed',default=>'not-started'};
                    $chipLabel      = match($project->status) { 'in_progress'=>'In Progress','completed'=>'Completed',default=>'Not Started'};
                    $isOverdue      = $project->end_date && $project->end_date->isPast() && $project->status !== 'completed';
                @endphp
                <div class="cu-list-row project-item"
                     data-name="{{ strtolower($project->name) }}"
                     data-status="{{ $project->status }}"
                     data-progress="{{ $progress }}"
                     data-created="{{ $project->created_at->timestamp }}">
                    <div class="cu-list-name">
                        <div class="cu-list-avatar" style="background:{{ $projectColor }};">{{ strtoupper(substr($project->name,0,1)) }}</div>
                        <span title="{{ $project->name }}">{{ $project->name }}</span>
                    </div>
                    <div class="cu-list-meta col-meta">
                        @if($project->end_date)
                            <span class="{{ $isOverdue ? 'text-danger fw-semibold' : '' }}" style="font-size:11px;">
                                @if($isOverdue)<i class="bi bi-exclamation-circle me-1"></i>Overdue
                                @else {{ $project->end_date->format('M d, Y') }}
                                @endif
                            </span>
                        @else <span style="color:#d3d5db;">—</span>
                        @endif
                    </div>
                    <div class="cu-list-progress col-prog">
                        <div class="cu-list-pb">
                            <div class="cu-list-pb-fill" style="background:{{ $projectColor }}; width:{{ $progress }}%;"></div>
                        </div>
                        <span>{{ round($progress) }}%</span>
                    </div>
                    <div><span class="cu-chip {{ $chipClass }}"><i class="bi bi-circle-fill" style="font-size:6px;"></i>{{ $chipLabel }}</span></div>
                    <div class="cu-list-actions">
                        <a href="{{ route('projects.show', $project) }}" class="cu-action-btn" title="View"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('projects.edit', $project) }}" class="cu-action-btn" title="Edit"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this project?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="cu-action-btn danger" title="Delete"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

    @else
        <div class="cu-empty">
            <div class="cu-empty-icon"><i class="bi bi-folder-plus"></i></div>
            <h5>No projects yet</h5>
            <p>Create your first project to start organising your work.</p>
            <a href="{{ route('projects.create') }}" class="cu-empty-btn"><i class="bi bi-plus-lg"></i>Create Project</a>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput  = document.getElementById('projectSearch');
    const statusFilter = document.getElementById('statusFilter');
    const sortFilter   = document.getElementById('sortFilter');
    const viewBtns     = document.querySelectorAll('.cu-view-btn');
    const cuGrid       = document.getElementById('cuGrid');
    const cuList       = document.getElementById('cuList');

    function getItems() {
        return Array.from(document.querySelectorAll('.project-item'));
    }

    function filterAndSort() {
        const term   = searchInput.value.toLowerCase();
        const status = statusFilter.value;
        const sort   = sortFilter.value;

        let items = getItems();

        // Filter
        items.forEach(item => {
            const nameMatch   = item.dataset.name.includes(term);
            const statusMatch = !status || item.dataset.status === status;
            item.style.display = (nameMatch && statusMatch) ? '' : 'none';
        });

        // Sort visible items in grid
        const gridItems = Array.from(cuGrid.querySelectorAll('.project-item')).filter(i => i.style.display !== 'none');
        gridItems.sort((a, b) => {
            if (sort === 'name')       return a.dataset.name.localeCompare(b.dataset.name);
            if (sort === 'created_at') return parseInt(b.dataset.created) - parseInt(a.dataset.created);
            if (sort === 'progress')   return parseFloat(b.dataset.progress) - parseFloat(a.dataset.progress);
            return 0;
        });
        gridItems.forEach(i => cuGrid.appendChild(i));
    }

    function setView(view) {
        viewBtns.forEach(b => b.classList.toggle('active', b.dataset.view === view));
        if (view === 'list') {
            cuGrid.style.display = 'none';
            cuList.style.display = 'flex';
        } else {
            cuGrid.style.display = 'grid';
            cuList.style.display = 'none';
        }
    }

    searchInput.addEventListener('input', filterAndSort);
    statusFilter.addEventListener('change', filterAndSort);
    sortFilter.addEventListener('change', filterAndSort);
    viewBtns.forEach(b => b.addEventListener('click', () => setView(b.dataset.view)));
});
</script>
@endpush
