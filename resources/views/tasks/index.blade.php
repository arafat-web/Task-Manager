@extends('layouts.app')

@section('title', isset($project) ? $project->name . ' — Tasks' : 'My Tasks')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .main-content { padding:14px 16px; background:#f7f8fa; min-height:100vh; }

    .cu-header {
        background:linear-gradient(135deg,var(--primary-600) 0%,var(--primary-700) 100%);
        border-radius:10px; padding:12px 18px; color:white; margin-bottom:14px;
        position:relative; overflow:hidden; border:1px solid var(--primary-500);
        box-shadow:0 2px 8px rgba(99,102,241,.3);
    }
    .cu-header::before {
        content:''; position:absolute; top:0; right:0; width:80px; height:80px;
        background:rgba(255,255,255,.08); border-radius:50%; transform:translate(20px,-20px);
    }
    .cu-header-title{font-weight:700;font-size:17px;margin:0;position:relative;z-index:1;}
    .cu-header-sub  {font-size:12px;opacity:.8;margin:2px 0 0;position:relative;z-index:1;}

    .cu-toolbar {
        display:flex; align-items:center; justify-content:space-between; gap:10px;
        background:white; border:1px solid #e3e4e8; border-radius:9px;
        padding:8px 12px; margin-bottom:14px; flex-wrap:wrap;
    }
    .cu-toolbar-left  {display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
    .cu-toolbar-right {display:flex;align-items:center;gap:8px;}
    .cu-view-toggle{display:flex;background:#f0f1f3;border-radius:7px;padding:3px;gap:2px;}
    .cu-view-btn{
        padding:5px 12px;border:none;background:transparent;border-radius:5px;
        font-size:12px;font-weight:600;color:#8a8f98;cursor:pointer;transition:all .15s;
        display:flex;align-items:center;gap:5px;
    }
    .cu-view-btn.active{background:white;color:#1a1d23;box-shadow:0 1px 3px rgba(0,0,0,.1);}
    .cu-filter-select{
        border:1px solid #e3e4e8;border-radius:7px;padding:5px 10px;
        font-size:12px;color:#3d4149;background:white;cursor:pointer;
    }
    .cu-search-input{
        border:1px solid #e3e4e8;border-radius:7px;padding:5px 10px;
        font-size:12px;color:#3d4149;background:white;width:180px;outline:none;
    }
    .cu-search-input:focus{border-color:#7c3aed;}
    .cu-btn-new{
        display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:7px;
        background:#7c3aed;color:white;border:none;font-size:12px;font-weight:600;
        cursor:pointer;transition:background .15s;
    }
    .cu-btn-new:hover{background:#6d28d9;}

    .cu-kanban{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;align-items:start;}
    @media(max-width:860px){.cu-kanban{grid-template-columns:1fr;}}
    .cu-col{background:#f3f4f6;border-radius:10px;overflow:hidden;}
    .cu-col-head{
        display:flex;align-items:center;justify-content:space-between;
        padding:10px 12px;border-bottom:1px solid #e3e4e8;
    }
    .cu-col-head-left{display:flex;align-items:center;gap:7px;font-size:13px;font-weight:700;color:#1a1d23;}
    .cu-col-dot{width:9px;height:9px;border-radius:50%;flex-shrink:0;}
    .cu-col-count{
        background:white;border:1px solid #e3e4e8;border-radius:20px;
        padding:1px 7px;font-size:11px;font-weight:700;color:#8a8f98;
    }
    .cu-col-add{
        width:26px;height:26px;border-radius:6px;border:1px solid #e3e4e8;
        background:white;cursor:pointer;display:flex;align-items:center;
        justify-content:center;color:#8a8f98;font-size:14px;transition:all .15s;
    }
    .cu-col-add:hover{background:#7c3aed;border-color:#7c3aed;color:white;}
    .cu-col-body{padding:8px;min-height:120px;display:flex;flex-direction:column;gap:7px;}
    .cu-col-body.drop-target{background:#ede9fe;border:1.5px dashed #7c3aed;border-radius:8px;}

    .cu-task-card{
        background:white;border:1px solid #e3e4e8;border-radius:8px;
        padding:10px 12px;cursor:grab;transition:all .15s;position:relative;
    }
    .cu-task-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.1);border-color:#c4b5fd;}
    .cu-task-card.dragging{opacity:.5;}
    .cu-task-title{font-size:13px;font-weight:600;color:#1a1d23;margin-bottom:6px;line-height:1.3;}
    .cu-task-desc{
        font-size:11px;color:#8a8f98;margin-bottom:7px;line-height:1.4;
        display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;
    }
    .cu-task-meta{display:flex;align-items:center;flex-wrap:wrap;gap:5px;margin-bottom:7px;}
    .cu-priority{
        display:inline-flex;align-items:center;gap:3px;padding:2px 7px;border-radius:20px;
        font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;
    }
    .cu-priority.high  {background:#fef2f2;color:#dc2626;}
    .cu-priority.medium{background:#fffbeb;color:#d97706;}
    .cu-priority.low   {background:#f0fdf4;color:#16a34a;}
    .cu-due{display:inline-flex;align-items:center;gap:3px;font-size:11px;color:#8a8f98;}
    .cu-due.overdue{color:#dc2626;}
    .cu-assignee{
        width:20px;height:20px;border-radius:50%;background:#7c3aed;color:white;
        font-size:10px;font-weight:700;display:inline-flex;align-items:center;
        justify-content:center;margin-left:auto;
    }
    .cu-task-foot{
        display:flex;align-items:center;justify-content:space-between;
        margin-top:5px;padding-top:7px;border-top:1px solid #f0f1f3;
    }
    .cu-task-proj{font-size:10px;color:#8a8f98;display:flex;align-items:center;gap:3px;}
    .cu-task-actions{display:flex;gap:4px;}
    .cu-task-btn{
        width:24px;height:24px;border-radius:5px;border:1px solid #e3e4e8;
        background:white;display:flex;align-items:center;justify-content:center;
        font-size:11px;color:#8a8f98;text-decoration:none;transition:all .15s;
    }
    .cu-task-btn:hover{border-color:#7c3aed;background:#f5f3ff;color:#7c3aed;}
    .cu-col-empty{text-align:center;padding:20px;color:#c4b5fd;font-size:12px;}

    .cu-list-view{display:none;}
    .cu-list-head{
        display:grid;grid-template-columns:2fr 1.2fr .8fr .8fr .8fr 80px;
        gap:8px;padding:8px 14px;background:white;border:1px solid #e3e4e8;
        border-radius:9px;margin-bottom:4px;
        font-size:11px;font-weight:700;color:#8a8f98;text-transform:uppercase;letter-spacing:.5px;
    }
    .cu-list-row{
        display:grid;grid-template-columns:2fr 1.2fr .8fr .8fr .8fr 80px;
        gap:8px;padding:9px 14px;background:white;border:1px solid #e3e4e8;
        border-radius:8px;margin-bottom:4px;align-items:center;
        font-size:13px;transition:border-color .15s;
    }
    .cu-list-row:hover{border-color:#c4b5fd;}
    .cu-list-title{font-weight:600;color:#1a1d23;}
    .cu-list-sub{font-size:10px;color:#8a8f98;margin-top:2px;display:flex;align-items:center;gap:3px;}
    .cu-list-project{font-size:12px;color:#3d4149;display:flex;align-items:center;gap:5px;}
    .cu-list-actions{display:flex;gap:5px;justify-content:flex-end;}
    .cu-status-chip{
        display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:20px;
        font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.3px;
    }
    .cu-status-chip.to_do      {background:#f1f5f9;color:#64748b;}
    .cu-status-chip.in_progress{background:#ede9fe;color:#7c3aed;}
    .cu-status-chip.completed  {background:#dcfce7;color:#16a34a;}

    .cu-empty{
        text-align:center;padding:60px 20px;background:white;
        border:1px solid #e3e4e8;border-radius:12px;
    }
    .cu-empty-icon{
        width:56px;height:56px;border-radius:14px;background:#f5f3ff;color:#7c3aed;
        font-size:24px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;
    }
    .cu-empty h5{font-weight:700;color:#1a1d23;margin-bottom:6px;}
    .cu-empty p {color:#8a8f98;font-size:13px;margin-bottom:16px;}

    .cu-modal .modal-content{border:none;border-radius:12px;box-shadow:0 20px 40px rgba(0,0,0,.15);border:1px solid #e3e4e8;}
    .cu-modal .modal-header{background:linear-gradient(135deg,var(--primary-600),var(--primary-700));color:white;border:none;border-radius:12px 12px 0 0;padding:14px 20px;}
    .cu-modal .modal-title{font-weight:700;font-size:15px;}
    .cu-modal .modal-body {padding:18px 20px;}
    .cu-modal .modal-footer{padding:12px 20px;border-top:1px solid #e3e4e8;background:#f7f8fa;border-radius:0 0 12px 12px;}
    .cu-field{margin-bottom:14px;}
    .cu-label{font-size:12px;font-weight:700;color:#3d4149;margin-bottom:5px;display:block;}
    .cu-input{
        width:100%;border:1px solid #e3e4e8;border-radius:7px;
        padding:7px 10px;font-size:13px;color:#1a1d23;background:white;
        transition:border .15s;outline:none;
    }
    .cu-input:focus{border-color:#7c3aed;box-shadow:0 0 0 3px rgba(124,58,237,.08);}
    .cu-select{appearance:auto;}
    .ql-toolbar{border-color:#e3e4e8 !important;border-radius:7px 7px 0 0;}
    .ql-container.ql-snow{border-color:#e3e4e8 !important;border-radius:0 0 7px 7px;}
    #task-quill-editor{height:120px;}
</style>
@endpush

@section('content')
<div class="main-content">

    <div class="cu-header">
        <div class="d-flex align-items-center gap-2" style="position:relative;z-index:1;">
            @if(isset($project))
                <a href="{{ route('projects.show', $project) }}" class="text-decoration-none" style="color:rgba(255,255,255,.7);">
                    <i class="bi bi-arrow-left" style="font-size:16px;"></i>
                </a>
            @endif
            <div>
                <div class="cu-header-title">{{ isset($project) ? $project->name . ' — Tasks' : 'My Tasks' }}</div>
                <div class="cu-header-sub">{{ isset($project) ? 'Manage and track tasks for this project' : 'All active tasks across your projects' }}</div>
            </div>
        </div>
    </div>

    @php
        $todoCnt     = count($tasks['to_do'] ?? []);
        $progressCnt = count($tasks['in_progress'] ?? []);
        $completedCnt= count($tasks['completed'] ?? []);
        $totalCnt    = $todoCnt + $progressCnt + $completedCnt;
        $hasAny      = $totalCnt > 0;
    @endphp

    <div class="cu-toolbar">
        <div class="cu-toolbar-left">
            <div class="cu-view-toggle">
                <button class="cu-view-btn active" data-view="kanban"><i class="bi bi-kanban"></i> Board</button>
                <button class="cu-view-btn" data-view="list"><i class="bi bi-list-ul"></i> List</button>
            </div>
            <input type="text" class="cu-search-input" id="cuSearch" placeholder="Search tasks…">
            <select class="cu-filter-select" id="cuPriority">
                <option value="">All priorities</option>
                <option value="high">High</option>
                <option value="medium">Medium</option>
                <option value="low">Low</option>
            </select>
            <select class="cu-filter-select" id="cuProject">
                <option value="">All projects</option>
                @foreach($projects as $proj)
                    <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="cu-toolbar-right">
            <span style="font-size:12px;color:#8a8f98;">{{ $totalCnt }} task{{ $totalCnt != 1 ? 's' : '' }}</span>
            <button class="cu-btn-new" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                <i class="bi bi-plus-lg"></i> New Task
            </button>
        </div>
    </div>

    @if(!$hasAny)
        <div class="cu-empty">
            <div class="cu-empty-icon"><i class="bi bi-list-task"></i></div>
            <h5>No tasks yet</h5>
            <p>{{ isset($project) ? 'Create the first task for ' . $project->name . '.' : 'No tasks found. Create one to get started.' }}</p>
            <button class="cu-btn-new" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                <i class="bi bi-plus-lg"></i> Create Task
            </button>
        </div>
    @else

    {{-- KANBAN --}}
    <div class="cu-kanban" id="cuKanban">

        <div class="cu-col">
            <div class="cu-col-head">
                <div class="cu-col-head-left">
                    <span class="cu-col-dot" style="background:#94a3b8;"></span>
                    To Do
                    <span class="cu-col-count" id="cnt-to_do">{{ $todoCnt }}</span>
                </div>
                <button class="cu-col-add" data-bs-toggle="modal" data-bs-target="#createTaskModal" data-status="to_do">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>
            <div class="cu-col-body" id="col-to_do" data-status="to_do">
                @forelse($tasks['to_do'] ?? [] as $task)
                    @include('tasks._card', ['task' => $task])
                @empty
                    <div class="cu-col-empty"><i class="bi bi-circle" style="font-size:22px;display:block;margin-bottom:6px;"></i>No tasks here</div>
                @endforelse
            </div>
        </div>

        <div class="cu-col">
            <div class="cu-col-head">
                <div class="cu-col-head-left">
                    <span class="cu-col-dot" style="background:#7c3aed;"></span>
                    In Progress
                    <span class="cu-col-count" id="cnt-in_progress">{{ $progressCnt }}</span>
                </div>
                <button class="cu-col-add" data-bs-toggle="modal" data-bs-target="#createTaskModal" data-status="in_progress">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>
            <div class="cu-col-body" id="col-in_progress" data-status="in_progress">
                @forelse($tasks['in_progress'] ?? [] as $task)
                    @include('tasks._card', ['task' => $task])
                @empty
                    <div class="cu-col-empty"><i class="bi bi-arrow-clockwise" style="font-size:22px;display:block;margin-bottom:6px;"></i>Nothing active</div>
                @endforelse
            </div>
        </div>

        <div class="cu-col">
            <div class="cu-col-head">
                <div class="cu-col-head-left">
                    <span class="cu-col-dot" style="background:#16a34a;"></span>
                    Completed
                    <span class="cu-col-count" id="cnt-completed">{{ $completedCnt }}</span>
                </div>
                <button class="cu-col-add" data-bs-toggle="modal" data-bs-target="#createTaskModal" data-status="completed">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>
            <div class="cu-col-body" id="col-completed" data-status="completed">
                @forelse($tasks['completed'] ?? [] as $task)
                    @include('tasks._card', ['task' => $task])
                @empty
                    <div class="cu-col-empty"><i class="bi bi-check-circle" style="font-size:22px;display:block;margin-bottom:6px;"></i>Nothing done yet</div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- LIST VIEW --}}
    <div class="cu-list-view" id="cuList">
        <div class="cu-list-head">
            <div>Task</div><div>Project</div><div>Priority</div>
            <div>Assignee</div><div>Due Date</div><div></div>
        </div>
        @foreach(collect($tasks)->flatten() as $task)
        <div class="cu-list-row" data-title="{{ strtolower($task->title) }}" data-priority="{{ $task->priority }}" data-project="{{ $task->project_id }}">
            <div>
                <div class="cu-list-title">{{ $task->title }}</div>
                <div class="cu-list-sub">
                    <span class="cu-status-chip {{ $task->status }}">
                        <i class="bi bi-circle-fill" style="font-size:5px;"></i>
                        {{ ucwords(str_replace('_',' ',$task->status)) }}
                    </span>
                </div>
            </div>
            <div class="cu-list-project">
                @if($task->project)
                    <i class="bi bi-folder" style="color:#8a8f98;font-size:11px;"></i>
                    {{ $task->project->name }}
                @else
                    <span style="color:#c4c9d4;">—</span>
                @endif
            </div>
            <div><span class="cu-priority {{ $task->priority }}">{{ ucfirst($task->priority) }}</span></div>
            <div style="font-size:12px;color:#3d4149;">
                @if($task->user)
                    <div style="display:flex;align-items:center;gap:6px;">
                        <div class="cu-assignee" style="width:24px;height:24px;font-size:10px;">{{ strtoupper(substr($task->user->name,0,1)) }}</div>
                        <span>{{ $task->user->name }}</span>
                    </div>
                @else
                    <span style="color:#c4c9d4;">Unassigned</span>
                @endif
            </div>
            <div class="cu-due {{ $task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status !== 'completed' ? 'overdue' : '' }}" style="font-size:12px;">
                @if($task->due_date)
                    <i class="bi bi-calendar-event" style="font-size:11px;"></i>
                    {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                @else
                    <span style="color:#c4c9d4;">—</span>
                @endif
            </div>
            <div class="cu-list-actions">
                <a href="{{ route('tasks.show', $task->id) }}" class="cu-task-btn" title="View"><i class="bi bi-eye"></i></a>
                <a href="{{ route('tasks.edit', $task->id) }}" class="cu-task-btn" title="Edit"><i class="bi bi-pencil"></i></a>
            </div>
        </div>
        @endforeach
    </div>

    @endif
</div>

{{-- Modal --}}
<div class="modal fade cu-modal" id="createTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>New Task</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ isset($project) ? route('projects.tasks.store', $project) : route('tasks.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="cu-field">
                                <label class="cu-label">Title <span style="color:#dc2626;">*</span></label>
                                <input type="text" name="title" class="cu-input" placeholder="Task title…" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="cu-field">
                                <label class="cu-label">Priority <span style="color:#dc2626;">*</span></label>
                                <select name="priority" class="cu-input cu-select" required>
                                    <option value="low">🟢 Low</option>
                                    <option value="medium" selected>🟡 Medium</option>
                                    <option value="high">🔴 High</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="cu-field">
                        <label class="cu-label">Description</label>
                        <div id="task-quill-editor"></div>
                        <textarea name="description" id="task_description" style="display:none;"></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="cu-field">
                                <label class="cu-label">Project <span style="color:#dc2626;">*</span></label>
                                <select name="project_id" class="cu-input cu-select" required>
                                    <option value="">Select…</option>
                                    @foreach($projects as $proj)
                                        <option value="{{ $proj->id }}" {{ isset($project) && $project->id == $proj->id ? 'selected' : '' }}>{{ $proj->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="cu-field">
                                <label class="cu-label">Due Date</label>
                                <input type="date" name="due_date" class="cu-input">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="cu-field">
                                <label class="cu-label">Assign To <span style="color:#dc2626;">*</span></label>
                                <select name="user_id" class="cu-input cu-select" required>
                                    <option value="{{ auth()->id() }}" selected>Me</option>
                                    @foreach($users as $u)
                                        @if($u->id !== auth()->id())
                                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="cu-field">
                                <label class="cu-label">Est. Hours</label>
                                <input type="number" name="estimated_hours" class="cu-input" min="0.5" step="0.5" placeholder="e.g. 2.5">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="status" id="task_status" value="to_do">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="cu-btn-new" style="border-radius:7px;">
                        <i class="bi bi-check-lg"></i> Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const kanban = document.getElementById('cuKanban');
    const list   = document.getElementById('cuList');

    document.querySelectorAll('.cu-view-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.cu-view-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const v = btn.dataset.view;
            if (kanban) kanban.style.display = v === 'kanban' ? 'grid' : 'none';
            if (list)   list.style.display   = v === 'list'   ? 'block' : 'none';
        });
    });

    const searchInput    = document.getElementById('cuSearch');
    const prioritySelect = document.getElementById('cuPriority');
    const projectSelect  = document.getElementById('cuProject');

    function applyFilters() {
        const term     = (searchInput?.value || '').toLowerCase();
        const priority = prioritySelect?.value || '';
        const project  = projectSelect?.value  || '';
        document.querySelectorAll('.cu-task-card').forEach(card => {
            const show = card.dataset.title.includes(term)
                      && (!priority || card.dataset.priority === priority)
                      && (!project  || card.dataset.project  === project);
            card.style.display = show ? '' : 'none';
        });
        document.querySelectorAll('.cu-list-row').forEach(row => {
            const show = row.dataset.title.includes(term)
                      && (!priority || row.dataset.priority === priority)
                      && (!project  || row.dataset.project  === project);
            row.style.display = show ? '' : 'none';
        });
    }
    searchInput?.addEventListener('input', applyFilters);
    prioritySelect?.addEventListener('change', applyFilters);
    projectSelect?.addEventListener('change', applyFilters);

    let taskQuill = null;
    const modal = document.getElementById('createTaskModal');
    modal?.addEventListener('show.bs.modal', e => {
        const status = e.relatedTarget?.dataset.status || 'to_do';
        const si = document.getElementById('task_status');
        if (si) si.value = status;
        if (!taskQuill) {
            setTimeout(() => {
                taskQuill = new Quill('#task-quill-editor', {
                    theme: 'snow',
                    placeholder: 'Describe the task…',
                    modules: { toolbar: [['bold','italic','underline'],[{'list':'ordered'},{'list':'bullet'}],['link'],['clean']] }
                });
                taskQuill.on('text-change', () => {
                    const el = document.getElementById('task_description');
                    if (el) el.value = taskQuill.root.innerHTML;
                });
            }, 80);
        }
    });

    document.querySelectorAll('.cu-task-card').forEach(card => {
        card.addEventListener('dragstart', e => {
            card.classList.add('dragging');
            e.dataTransfer.setData('text/plain', card.dataset.id);
        });
        card.addEventListener('dragend', () => {
            card.classList.remove('dragging');
            document.querySelectorAll('.cu-col-body').forEach(c => c.classList.remove('drop-target'));
        });
    });

    document.querySelectorAll('.cu-col-body').forEach(col => {
        col.addEventListener('dragover',  e => { e.preventDefault(); col.classList.add('drop-target'); });
        col.addEventListener('dragleave', () => col.classList.remove('drop-target'));
        col.addEventListener('drop', e => {
            e.preventDefault();
            col.classList.remove('drop-target');
            const taskId = e.dataTransfer.getData('text/plain');
            const card   = document.querySelector(`.cu-task-card[data-id="${taskId}"]`);
            if (!card) return;
            const placeholder = col.querySelector('.cu-col-empty');
            if (placeholder) placeholder.remove();
            col.appendChild(card);
            updateCounts();
            updateStatus(taskId, col.dataset.status);
        });
    });

    function updateCounts() {
        ['to_do','in_progress','completed'].forEach(s => {
            const col = document.getElementById(`col-${s}`);
            const cnt = document.getElementById(`cnt-${s}`);
            if (col && cnt) cnt.textContent = col.querySelectorAll('.cu-task-card').length;
        });
    }

    function updateStatus(taskId, status) {
        fetch(`/tasks/${taskId}/update-status`, {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({ status })
        }).catch(() => location.reload());
    }
});
</script>
@endpush
