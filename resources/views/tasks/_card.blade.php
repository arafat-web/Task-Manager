@php
    $overdue = $task->due_date
        && \Carbon\Carbon::parse($task->due_date)->isPast()
        && $task->status !== 'completed';
    $priorityColors = ['high' => '#dc2626', 'medium' => '#d97706', 'low' => '#16a34a'];
    $leftColor = $priorityColors[$task->priority] ?? '#94a3b8';
@endphp
<div class="cu-task-card"
     data-id="{{ $task->id }}"
     data-title="{{ strtolower($task->title) }}"
     data-priority="{{ $task->priority }}"
     draggable="true"
     style="border-left:3px solid {{ $leftColor }};">

    <div class="cu-task-title">{{ $task->title }}</div>

    @if($task->description)
        <div class="cu-task-desc">{{ strip_tags($task->description) }}</div>
    @endif

    <div class="cu-task-meta">
        <span class="cu-priority {{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
        @if($task->due_date)
            <span class="cu-due {{ $overdue ? 'overdue' : '' }}">
                <i class="bi bi-calendar-event" style="font-size:10px;"></i>
                {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                @if($overdue)
                    &nbsp;• Overdue
                @endif
            </span>
        @endif
        @if($task->user)
            <div class="cu-assignee" title="{{ $task->user->name }}" style="margin-left:auto;">
                {{ strtoupper(substr($task->user->name, 0, 1)) }}
            </div>
        @endif
    </div>

    <div class="cu-task-foot">
        <div class="cu-task-proj">
            @if($task->project)
                <i class="bi bi-folder" style="font-size:10px;"></i>
                {{ $task->project->name }}
            @endif
        </div>
        <div class="cu-task-actions">
            <a href="{{ route('tasks.show', $task->id) }}" class="cu-task-btn" title="View">
                <i class="bi bi-eye"></i>
            </a>
            <a href="{{ route('tasks.edit', $task->id) }}" class="cu-task-btn" title="Edit">
                <i class="bi bi-pencil"></i>
            </a>
        </div>
    </div>
</div>
