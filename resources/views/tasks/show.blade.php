@extends('layouts.app')

@section('title', $task->title . ' - Task Details')

@push('styles')
<style>
    .main-content {
        padding: 32px;
        background: var(--gray-25);
        min-height: 100vh;
    }

    .back-navigation {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        background: white;
        padding: 16px 24px;
        border-radius: 12px;
        border: 2px solid var(--gray-200);
        box-shadow: var(--shadow-sm);
    }

    .nav-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--gray-600);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
        padding: 8px 16px;
        border-radius: 8px;
        border: 1px solid var(--gray-200);
    }

    .back-link:hover {
        color: var(--primary-600);
        background: var(--primary-50);
        border-color: var(--primary-200);
        transform: translateX(-2px);
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--gray-500);
        font-size: 14px;
    }

    .breadcrumb a {
        color: var(--primary-600);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .breadcrumb a:hover {
        color: var(--primary-700);
        text-decoration: underline;
    }

    .breadcrumb-separator {
        color: var(--gray-400);
        margin: 0 4px;
    }

    .breadcrumb-current {
        color: var(--gray-700);
        font-weight: 600;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .nav-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .quick-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border: 1px solid var(--gray-200);
        background: white;
        color: var(--gray-600);
        text-decoration: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .quick-action-btn:hover {
        background: var(--gray-50);
        border-color: var(--gray-300);
        color: var(--gray-700);
    }

    .quick-action-btn.primary {
        background: var(--primary-600);
        color: white;
        border-color: var(--primary-600);
    }

    .quick-action-btn.primary:hover {
        background: var(--primary-700);
        border-color: var(--primary-700);
        color: white;
    }

    .task-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .task-header {
        background: white;
        border: 2px solid var(--gray-200);
        border-radius: 18px;
        padding: 32px;
        box-shadow: var(--shadow-lg);
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }

    .task-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, var(--primary-100) 0%, var(--primary-200) 100%);
        border-radius: 50%;
        transform: translate(40px, -40px);
        opacity: 0.7;
    }

    .task-header-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .task-title-section {
        position: relative;
        z-index: 1;
        flex: 1;
    }

    .task-id {
        background: var(--gray-100);
        color: var(--gray-600);
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        font-family: 'Courier New', monospace;
        margin-bottom: 12px;
        display: inline-block;
    }

    .task-title {
        font-size: 32px;
        font-weight: 800;
        color: var(--gray-900);
        margin-bottom: 16px;
        line-height: 1.2;
    }

    .task-header-actions {
        display: flex;
        gap: 8px;
        position: relative;
        z-index: 1;
    }

    .header-action-btn {
        width: 40px;
        height: 40px;
        border: 2px solid var(--gray-200);
        background: white;
        color: var(--gray-600);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 16px;
    }

    .header-action-btn:hover {
        background: var(--primary-50);
        border-color: var(--primary-200);
        color: var(--primary-600);
        transform: translateY(-1px);
    }

    .header-action-btn.danger:hover {
        background: var(--error-50);
        border-color: var(--error-200);
        color: var(--error-600);
    }

    .task-meta {
        display: flex;
        align-items: center;
        gap: 24px;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }

    .status-badge, .priority-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: var(--shadow-sm);
    }

    .status-badge {
        border: 2px solid;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .status-badge:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .status-to-do {
        background: var(--primary-50);
        color: var(--primary-700);
        border-color: var(--primary-200);
    }

    .status-in-progress {
        background: #fef3c7;
        color: #92400e;
        border-color: #fcd34d;
    }

    .status-completed {
        background: var(--success-50);
        color: var(--success-700);
        border-color: var(--success-200);
    }

    .priority-badge {
        border: 2px solid;
    }

    .priority-low {
        background: var(--success-50);
        color: var(--success-700);
        border-color: var(--success-200);
    }

    .priority-medium {
        background: #fef3c7;
        color: #92400e;
        border-color: #fcd34d;
    }

    .priority-high {
        background: rgba(239, 68, 68, 0.1);
        color: var(--error-700);
        border-color: var(--error-200);
    }

    .status-dot, .priority-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .status-to-do .status-dot { background: var(--primary-500); }
    .status-in-progress .status-dot { background: #f59e0b; }
    .status-completed .status-dot { background: var(--success-500); }

    .priority-low .priority-dot { background: var(--success-500); }
    .priority-medium .priority-dot { background: #f59e0b; }
    .priority-high .priority-dot { background: var(--error-500); }

    .due-date-info {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--gray-600);
        font-weight: 500;
    }

    .task-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 28px;
        margin-bottom: 28px;
    }

    .task-main {
        background: white;
        border: 2px solid var(--gray-200);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .task-sidebar {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .section-header {
        background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
        padding: 20px 28px;
        border-bottom: 2px solid var(--gray-100);
    }

    .section-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: var(--gray-800);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-content {
        padding: 32px;
    }

    .description-content {
        color: var(--gray-700);
        line-height: 1.8;
        font-size: 15px;
        background: var(--gray-25);
        border-radius: 12px;
        padding: 24px;
        border: 1px solid var(--gray-200);
    }

    .description-content p {
        margin-bottom: 16px;
    }

    .description-content ul, .description-content ol {
        margin-bottom: 16px;
        padding-left: 24px;
    }

    .description-content li {
        margin-bottom: 8px;
    }

    .description-content blockquote {
        border-left: 4px solid var(--primary-500);
        padding-left: 20px;
        margin: 20px 0;
        color: var(--gray-600);
        font-style: italic;
        background: var(--primary-25);
        padding: 16px 20px;
        border-radius: 0 8px 8px 0;
    }

    .description-content code {
        background: var(--gray-100);
        padding: 4px 8px;
        border-radius: 6px;
        font-family: 'Courier New', monospace;
        font-size: 13px;
        color: var(--gray-800);
    }

    .empty-description {
        text-align: center;
        color: var(--gray-400);
        font-style: italic;
        padding: 40px 20px;
    }

    .info-card {
        background: white;
        border: 2px solid var(--gray-200);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .info-card-header {
        background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
        padding: 18px 24px;
        border-bottom: 2px solid var(--gray-100);
    }

    .info-card-header h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: var(--gray-800);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-card-content {
        padding: 24px;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 20px;
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: var(--primary-100);
        color: var(--primary-600);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .info-content h5 {
        margin: 0 0 6px;
        font-size: 14px;
        font-weight: 600;
        color: var(--gray-700);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-content p {
        margin: 0;
        color: var(--gray-600);
        font-size: 15px;
        font-weight: 500;
    }

    .time-tracker-card {
        background: linear-gradient(135deg, var(--primary-50) 0%, var(--primary-100) 100%);
        border: 2px solid var(--primary-200);
    }

    .time-tracker-card .info-card-header {
        background: linear-gradient(135deg, var(--primary-100) 0%, var(--primary-200) 100%);
        color: var(--primary-800);
    }

    .time-display {
        font-family: 'Courier New', monospace;
        font-size: 28px;
        font-weight: 700;
        color: var(--primary-700);
        text-align: center;
        margin-bottom: 20px;
        padding: 16px;
        background: white;
        border-radius: 12px;
        border: 2px solid var(--primary-200);
    }

    .time-controls {
        display: flex;
        gap: 12px;
        justify-content: center;
    }

    .time-btn {
        padding: 12px 20px;
        border: 2px solid;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-start {
        background: var(--success-500);
        color: white;
        border-color: var(--success-500);
    }

    .btn-start:hover {
        background: var(--success-600);
        border-color: var(--success-600);
        transform: translateY(-1px);
    }

    .btn-pause {
        background: #f59e0b;
        color: white;
        border-color: #f59e0b;
    }

    .btn-pause:hover {
        background: #d97706;
        border-color: #d97706;
        transform: translateY(-1px);
    }

    .btn-stop {
        background: var(--error-500);
        color: white;
        border-color: var(--error-500);
    }

    .btn-stop:hover {
        background: var(--error-600);
        border-color: var(--error-600);
        transform: translateY(-1px);
    }

    .action-buttons {
        background: white;
        border: 2px solid var(--gray-200);
        border-radius: 16px;
        padding: 24px;
        box-shadow: var(--shadow-lg);
    }

    .action-buttons h4 {
        margin-bottom: 20px;
        font-size: 16px;
        font-weight: 700;
        color: var(--gray-800);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .action-btn {
        padding: 14px 20px;
        border: 2px solid;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        cursor: pointer;
    }

    .btn-edit {
        background: var(--primary-500);
        color: white;
        border-color: var(--primary-500);
    }

    .btn-edit:hover {
        background: var(--primary-600);
        border-color: var(--primary-600);
        color: white;
        transform: translateY(-1px);
        box-shadow: var(--shadow-lg);
    }

    .btn-delete {
        background: white;
        color: var(--error-600);
        border-color: var(--error-200);
    }

    .btn-delete:hover {
        background: var(--error-500);
        border-color: var(--error-500);
        color: white;
        transform: translateY(-1px);
        box-shadow: var(--shadow-lg);
    }

    .progress-section {
        margin-top: 28px;
    }

    .progress-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .progress-label {
        font-weight: 600;
        color: var(--gray-700);
        font-size: 14px;
    }

    .progress-percentage {
        font-weight: 700;
        color: var(--primary-600);
        font-size: 16px;
    }

    .progress-bar-container {
        height: 12px;
        background: var(--gray-200);
        border-radius: 6px;
        overflow: hidden;
        position: relative;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-500) 0%, var(--primary-600) 100%);
        border-radius: 6px;
        transition: width 0.3s ease;
        position: relative;
    }

    .progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.3) 50%, transparent 100%);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    /* Checklist Styles */
    .checklist-section {
        background: white;
        border: 2px solid var(--gray-200);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        margin-bottom: 28px;
    }

    .checklist-header {
        background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
        padding: 20px 28px;
        border-bottom: 2px solid var(--gray-100);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .checklist-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: var(--gray-800);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .checklist-progress {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        color: var(--gray-600);
    }

    .checklist-progress-bar {
        width: 60px;
        height: 6px;
        background: var(--gray-200);
        border-radius: 3px;
        overflow: hidden;
    }

    .checklist-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--success-500) 0%, var(--success-600) 100%);
        border-radius: 3px;
        transition: width 0.3s ease;
    }

    .checklist-content {
        padding: 28px;
    }

    .checklist-items {
        margin-bottom: 24px;
    }

    .checklist-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        border: 2px solid var(--gray-100);
        border-radius: 12px;
        margin-bottom: 12px;
        transition: all 0.2s ease;
        background: var(--gray-25);
    }

    .checklist-item:hover {
        border-color: var(--gray-200);
        background: white;
        box-shadow: var(--shadow-sm);
    }

    .checklist-item.completed {
        background: var(--success-50);
        border-color: var(--success-200);
    }

    .checklist-item.completed .checklist-text {
        text-decoration: line-through;
        opacity: 0.7;
    }

    .checklist-checkbox {
        width: 20px;
        height: 20px;
        border: 2px solid var(--gray-300);
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .checklist-checkbox:hover {
        border-color: var(--primary-500);
        background: var(--primary-50);
    }

    .checklist-checkbox.checked {
        background: var(--success-500);
        border-color: var(--success-500);
        color: white;
    }

    .checklist-text {
        flex: 1;
        font-size: 14px;
        color: var(--gray-700);
        font-weight: 500;
        line-height: 1.4;
    }

    .checklist-actions {
        display: flex;
        gap: 8px;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .checklist-item:hover .checklist-actions {
        opacity: 1;
    }

    .checklist-action-btn {
        width: 28px;
        height: 28px;
        border: none;
        background: var(--gray-200);
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        font-size: 12px;
    }

    .checklist-action-btn:hover {
        background: var(--gray-300);
    }

    .checklist-action-btn.delete:hover {
        background: var(--error-500);
        color: white;
    }

    .add-checklist-form {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .checklist-input {
        flex: 1;
        padding: 12px 16px;
        border: 2px solid var(--gray-200);
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .checklist-input:focus {
        border-color: var(--primary-500);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        outline: none;
    }

    .add-checklist-btn {
        padding: 12px 20px;
        background: var(--primary-500);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .add-checklist-btn:hover {
        background: var(--primary-600);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .empty-checklist {
        text-align: center;
        padding: 40px 20px;
        color: var(--gray-500);
    }

    .empty-checklist i {
        font-size: 48px;
        opacity: 0.3;
        margin-bottom: 16px;
        display: block;
    }

    @media (max-width: 768px) {
        .task-grid {
            grid-template-columns: 1fr;
        }

        .task-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }

        .task-title {
            font-size: 24px;
        }

        .main-content {
            padding: 20px;
        }

        .task-header {
            padding: 24px 20px;
        }

        .section-content {
            padding: 20px;
        }

        .time-controls {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <!-- Enhanced Back Navigation -->
    <div class="back-navigation">
        <div class="nav-left">
            <a href="{{ route('tasks.index') }}" class="back-link">
                <i class="bi bi-arrow-left"></i>
                Back to Tasks
            </a>
        </div>

        <div class="nav-actions">
            @if($task->project)
                <a href="{{ route('projects.show', $task->project->id) }}" class="quick-action-btn" title="View Project">
                    <i class="bi bi-folder"></i>
                    Project
                </a>
            @endif

            <a href="{{ route('tasks.edit', $task->id) }}" class="quick-action-btn primary" title="Edit Task">
                <i class="bi bi-pencil"></i>
                Edit
            </a>
        </div>
    </div>

    <div class="task-container">
        <!-- Enhanced Task Header -->
        <div class="task-header">
            <div class="task-header-top">
                <div class="task-title-section">
                    <div class="task-id">TASK-{{ str_pad($task->id, 4, '0', STR_PAD_LEFT) }}</div>
                    <h1 class="task-title">{{ $task->title }}</h1>
                </div>

                <div class="task-header-actions">
                    <button class="header-action-btn" title="Copy Task Link" onclick="copyTaskLink()">
                        <i class="bi bi-link-45deg"></i>
                    </button>

                    <button class="header-action-btn" title="Print Task" onclick="window.print()">
                        <i class="bi bi-printer"></i>
                    </button>

                    <button class="header-action-btn danger" title="Delete Task" onclick="confirmDelete()">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>

            <div class="task-meta">
                <div class="status-badge status-{{ str_replace(' ', '-', strtolower($task->status)) }}" title="Click to change status" onclick="showStatusModal()">
                    <span class="status-dot"></span>
                    {{ ucwords(str_replace('_', ' ', $task->status)) }}
                </div>

                <div class="priority-badge priority-{{ $task->priority }}" title="{{ ucfirst($task->priority) }} Priority Task">
                    <span class="priority-dot"></span>
                    {{ ucfirst($task->priority) }} Priority
                </div>

                @if($task->due_date)
                    <div class="due-date-info">
                        <i class="bi bi-calendar-event"></i>
                        Due: {{ \Carbon\Carbon::parse($task->due_date)->format('M j, Y') }}
                        @if(\Carbon\Carbon::parse($task->due_date)->isPast() && $task->status !== 'completed')
                            <span style="color: var(--error-600); font-weight: 600; margin-left: 8px;">
                                ({{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }})
                            </span>
                        @else
                            <span style="color: var(--gray-500); margin-left: 8px;">
                                ({{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }})
                            </span>
                        @endif
                    </div>
                @endif

                @if($task->estimated_hours)
                    <div class="due-date-info">
                        <i class="bi bi-clock-history"></i>
                        Estimated: {{ $task->estimated_hours }} hours
                    </div>
                @endif
            </div>
        </div>        <!-- Main Content Grid -->
        <!-- Task Description -->
        <div class="task-main">
            <div class="section-header">
                <h3>
                    <i class="bi bi-file-text"></i>
                    Task Description
                </h3>
            </div>
            <div class="section-content">
                @if($task->description)
                    <div class="description-content">
                        {!! $task->description !!}
                    </div>
                @else
                    <div class="empty-description">
                        <i class="bi bi-file-text" style="font-size: 48px; opacity: 0.3; margin-bottom: 16px;"></i>
                        <p>No description provided for this task.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Checklist Section -->
        <div class="checklist-section">
            <div class="checklist-header">
                <h3>
                    <i class="bi bi-list-check"></i>
                    Task Checklist
                </h3>
                <div class="checklist-progress">
                    <span id="checklist-progress-text">0 of 0 completed</span>
                    <div class="checklist-progress-bar">
                        <div class="checklist-progress-fill" id="checklist-progress-fill" style="width: 0%"></div>
                    </div>
                </div>
            </div>
            <div class="checklist-content">
                <div class="checklist-items" id="checklist-items">
                    @forelse($task->checklistItems as $item)
                        <div class="checklist-item {{ $item->completed ? 'completed' : '' }}" data-id="{{ $item->id }}">
                            <div class="checklist-checkbox {{ $item->completed ? 'checked' : '' }}" onclick="toggleChecklistItem({{ $item->id }})">
                                @if($item->completed)
                                    <i class="bi bi-check"></i>
                                @endif
                            </div>
                            <div class="checklist-text">{{ $item->name }}</div>
                            <div class="checklist-actions">
                                <button class="checklist-action-btn delete" onclick="deleteChecklistItem({{ $item->id }})" title="Delete item">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="empty-checklist" id="empty-checklist">
                            <i class="bi bi-list-check"></i>
                            <p>No checklist items yet. Add some tasks to track your progress!</p>
                        </div>
                    @endforelse
                </div>

                <form class="add-checklist-form" onsubmit="addChecklistItem(event)">
                    <input type="text" class="checklist-input" id="checklist-input" placeholder="Add a new checklist item..." required>
                    <button type="submit" class="add-checklist-btn">
                        <i class="bi bi-plus"></i>
                        Add Item
                    </button>
                </form>
            </div>
        </div>

        <div class="task-grid">
            <!-- Left Column - Placeholder for grid balance -->
            <div></div>

            <!-- Sidebar -->
            <div class="task-sidebar">
                <!-- Task Information -->
                <div class="info-card">
                    <div class="info-card-header">
                        <h4>
                            <i class="bi bi-info-circle"></i>
                            Task Information
                        </h4>
                    </div>
                    <div class="info-card-content">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="info-content">
                                <h5>Assigned To</h5>
                                <p>{{ $task->user->name }}</p>
                            </div>
                        </div>

                        @if($task->project)
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-folder"></i>
                                </div>
                                <div class="info-content">
                                    <h5>Project</h5>
                                    <p>{{ $task->project->name }}</p>
                                </div>
                            </div>
                        @endif

                        @if($task->estimated_hours)
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="info-content">
                                    <h5>Estimated Time</h5>
                                    <p>{{ $task->estimated_hours }} hours</p>
                                </div>
                            </div>
                        @endif

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-calendar-plus"></i>
                            </div>
                            <div class="info-content">
                                <h5>Created</h5>
                                <p>{{ $task->created_at->format('M j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>

                        @if($task->updated_at->ne($task->created_at))
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-pencil"></i>
                                </div>
                                <div class="info-content">
                                    <h5>Last Updated</h5>
                                    <p>{{ $task->updated_at->format('M j, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Time Tracker -->
                <div class="info-card time-tracker-card">
                    <div class="info-card-header">
                        <h4>
                            <i class="bi bi-stopwatch"></i>
                            Time Tracker
                        </h4>
                    </div>
                    <div class="info-card-content">
                        <div class="time-display" id="time-display">00:00:00</div>
                        <div class="time-controls">
                            <button class="time-btn btn-start" id="start-btn">
                                <i class="bi bi-play-fill"></i>
                                Start
                            </button>
                            <button class="time-btn btn-pause" id="pause-btn" style="display: none;">
                                <i class="bi bi-pause-fill"></i>
                                Pause
                            </button>
                            <button class="time-btn btn-stop" id="stop-btn" style="display: none;">
                                <i class="bi bi-stop-fill"></i>
                                Stop
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <h4>
                        <i class="bi bi-gear"></i>
                        Actions
                    </h4>
                    <div class="btn-group">
                        <a href="{{ route('tasks.edit', $task->id) }}" class="action-btn btn-edit">
                            <i class="bi bi-pencil"></i>
                            Edit Task
                        </a>

                        <button type="button" class="action-btn btn-delete" onclick="confirmDelete()">
                            <i class="bi bi-trash"></i>
                            Delete Task
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Section -->
        @if($task->status !== 'to_do')
            <div class="progress-section">
                <div class="info-card">
                    <div class="info-card-header">
                        <h4>
                            <i class="bi bi-bar-chart"></i>
                            Task Progress
                        </h4>
                    </div>
                    <div class="info-card-content">
                        <div class="progress-info">
                            <span class="progress-label">Completion Status</span>
                            <span class="progress-percentage">
                                @if($task->status === 'completed')
                                    100%
                                @elseif($task->status === 'in_progress')
                                    {{ $task->estimated_hours ? '50%' : '25%' }}
                                @else
                                    0%
                                @endif
                            </span>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width:
                                @if($task->status === 'completed')
                                    100%
                                @elseif($task->status === 'in_progress')
                                    {{ $task->estimated_hours ? '50%' : '25%' }}
                                @else
                                    0%
                                @endif
                            "></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Form -->
<form id="deleteForm" action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let startTime = 0;
    let elapsedTime = 0;
    let timerInterval = null;
    let isRunning = false;

    const timeDisplay = document.getElementById('time-display');
    const startBtn = document.getElementById('start-btn');
    const pauseBtn = document.getElementById('pause-btn');
    const stopBtn = document.getElementById('stop-btn');

    function formatTime(milliseconds) {
        const totalSeconds = Math.floor(milliseconds / 1000);
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;

        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    function updateDisplay() {
        if (isRunning) {
            const currentTime = Date.now();
            const totalElapsed = elapsedTime + (currentTime - startTime);
            timeDisplay.textContent = formatTime(totalElapsed);
        }
    }

    function startTimer() {
        if (!isRunning) {
            startTime = Date.now();
            isRunning = true;
            timerInterval = setInterval(updateDisplay, 100);

            startBtn.style.display = 'none';
            pauseBtn.style.display = 'flex';
            stopBtn.style.display = 'flex';
        }
    }

    function pauseTimer() {
        if (isRunning) {
            elapsedTime += Date.now() - startTime;
            isRunning = false;
            clearInterval(timerInterval);

            pauseBtn.style.display = 'none';
            startBtn.style.display = 'flex';
            stopBtn.style.display = 'flex';

            // Change start button text to "Resume"
            startBtn.innerHTML = '<i class="bi bi-play-fill"></i>Resume';
        }
    }

    function stopTimer() {
        elapsedTime = 0;
        isRunning = false;
        clearInterval(timerInterval);
        timeDisplay.textContent = '00:00:00';

        startBtn.style.display = 'flex';
        pauseBtn.style.display = 'none';
        stopBtn.style.display = 'none';

        // Reset start button text
        startBtn.innerHTML = '<i class="bi bi-play-fill"></i>Start';
    }

    if (startBtn) startBtn.addEventListener('click', startTimer);
    if (pauseBtn) pauseBtn.addEventListener('click', pauseTimer);
    if (stopBtn) stopBtn.addEventListener('click', stopTimer);
});

function confirmDelete() {
    if (confirm('Are you sure you want to delete this task? This action cannot be undone.')) {
        if (confirm('This is your final warning. Are you absolutely sure you want to permanently delete this task?')) {
            document.getElementById('deleteForm').submit();
        }
    }
}

function copyTaskLink() {
    const url = window.location.href;

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(() => {
            showNotification('Task link copied to clipboard!', 'success');
        }).catch(() => {
            fallbackCopyTextToClipboard(url);
        });
    } else {
        fallbackCopyTextToClipboard(url);
    }
}

function fallbackCopyTextToClipboard(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";

    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
        document.execCommand('copy');
        showNotification('Task link copied to clipboard!', 'success');
    } catch (err) {
        showNotification('Failed to copy link', 'error');
    }

    document.body.removeChild(textArea);
}

function showStatusModal() {
    const currentStatus = '{{ $task->status }}';
    const taskId = '{{ $task->id }}';

    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content" style="border: none; border-radius: 16px;">
                <div class="modal-header" style="background: var(--primary-600); color: white; border: none; border-radius: 16px 16px 0 0;">
                    <h5 class="modal-title">
                        <i class="bi bi-arrow-repeat me-2"></i>Change Task Status
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <p style="margin-bottom: 20px; color: var(--gray-600);">Select the new status for this task:</p>
                    <div class="status-options" style="display: flex; flex-direction: column; gap: 12px;">
                        <label class="status-option" style="display: flex; align-items: center; padding: 12px; border: 2px solid var(--gray-200); border-radius: 8px; cursor: pointer; transition: all 0.2s ease;" data-status="to_do">
                            <input type="radio" name="new_status" value="to_do" ${currentStatus === 'to_do' ? 'checked' : ''} style="margin-right: 12px;">
                            <div class="status-badge status-to-do" style="margin-right: 12px;">
                                <span class="status-dot"></span>
                                To Do
                            </div>
                            <span style="color: var(--gray-600);">Task is ready to be started</span>
                        </label>
                        <label class="status-option" style="display: flex; align-items: center; padding: 12px; border: 2px solid var(--gray-200); border-radius: 8px; cursor: pointer; transition: all 0.2s ease;" data-status="in_progress">
                            <input type="radio" name="new_status" value="in_progress" ${currentStatus === 'in_progress' ? 'checked' : ''} style="margin-right: 12px;">
                            <div class="status-badge status-in-progress" style="margin-right: 12px;">
                                <span class="status-dot"></span>
                                In Progress
                            </div>
                            <span style="color: var(--gray-600);">Task is currently being worked on</span>
                        </label>
                        <label class="status-option" style="display: flex; align-items: center; padding: 12px; border: 2px solid var(--gray-200); border-radius: 8px; cursor: pointer; transition: all 0.2s ease;" data-status="completed">
                            <input type="radio" name="new_status" value="completed" ${currentStatus === 'completed' ? 'checked' : ''} style="margin-right: 12px;">
                            <div class="status-badge status-completed" style="margin-right: 12px;">
                                <span class="status-dot"></span>
                                Completed
                            </div>
                            <span style="color: var(--gray-600);">Task has been finished</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer" style="border: none; padding: 16px 24px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateTaskStatus()">
                        <i class="bi bi-check-lg me-2"></i>Update Status
                    </button>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
    const bootstrapModal = new bootstrap.Modal(modal);

    // Add hover effects to status options
    modal.querySelectorAll('.status-option').forEach(option => {
        option.addEventListener('mouseenter', function() {
            this.style.borderColor = 'var(--primary-300)';
            this.style.background = 'var(--primary-25)';
        });

        option.addEventListener('mouseleave', function() {
            if (!this.querySelector('input').checked) {
                this.style.borderColor = 'var(--gray-200)';
                this.style.background = 'white';
            }
        });

        option.addEventListener('click', function() {
            modal.querySelectorAll('.status-option').forEach(opt => {
                opt.style.borderColor = 'var(--gray-200)';
                opt.style.background = 'white';
            });
            this.style.borderColor = 'var(--primary-500)';
            this.style.background = 'var(--primary-50)';
            this.querySelector('input').checked = true;
        });
    });

    // Set initial selected state
    const selectedOption = modal.querySelector(`input[value="${currentStatus}"]`)?.closest('.status-option');
    if (selectedOption) {
        selectedOption.style.borderColor = 'var(--primary-500)';
        selectedOption.style.background = 'var(--primary-50)';
    }

    bootstrapModal.show();

    // Cleanup when modal is hidden
    modal.addEventListener('hidden.bs.modal', function() {
        document.body.removeChild(modal);
    });

    // Add update function to global scope
    window.updateTaskStatus = function() {
        const selectedStatus = modal.querySelector('input[name="new_status"]:checked')?.value;
        if (selectedStatus && selectedStatus !== currentStatus) {
            // Update status via AJAX
            fetch(`/tasks/${taskId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: selectedStatus })
            })
            .then(response => response.json())
            .then(data => {
                showNotification('Task status updated successfully!', 'success');
                // Reload page to show updated status
                setTimeout(() => {
                    location.reload();
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to update task status', 'error');
            });
        }
        bootstrapModal.hide();
    };
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 350px;
        box-shadow: var(--shadow-lg);
        border: none;
        border-radius: 12px;
        padding: 16px 20px;
    `;
    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-circle-fill'}" style="font-size: 20px;"></i>
            <div>
                <div style="font-weight: 600; margin-bottom: 2px;">
                    ${type === 'success' ? 'Success!' : 'Error!'}
                </div>
                <div style="font-size: 14px; opacity: 0.9;">
                    ${message}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="margin-left: auto;"></button>
        </div>
    `;

    document.body.appendChild(notification);

    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Add CSRF token to meta tag if it doesn't exist
if (!document.querySelector('meta[name="csrf-token"]')) {
    const meta = document.createElement('meta');
    meta.name = 'csrf-token';
    meta.content = '{{ csrf_token() }}';
    document.head.appendChild(meta);
}

// Checklist Functions
function updateChecklistProgress() {
    const items = document.querySelectorAll('.checklist-item');
    const completedItems = document.querySelectorAll('.checklist-item.completed');
    const total = items.length;
    const completed = completedItems.length;

    // Update progress text
    const progressText = document.getElementById('checklist-progress-text');
    if (progressText) {
        progressText.textContent = `${completed} of ${total} completed`;
    }

    // Update progress bar
    const progressFill = document.getElementById('checklist-progress-fill');
    if (progressFill) {
        const percentage = total > 0 ? (completed / total) * 100 : 0;
        progressFill.style.width = `${percentage}%`;
    }

    // Show/hide empty state
    const emptyState = document.getElementById('empty-checklist');
    if (emptyState) {
        emptyState.style.display = total === 0 ? 'block' : 'none';
    }
}

function toggleChecklistItem(itemId) {
    const item = document.querySelector(`[data-id="${itemId}"]`);
    const checkbox = item.querySelector('.checklist-checkbox');
    const isCompleted = item.classList.contains('completed');

    fetch(`/checklist-items/${itemId}/update-status`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Toggle visual state
            item.classList.toggle('completed');
            checkbox.classList.toggle('checked');

            if (!isCompleted) {
                checkbox.innerHTML = '<i class="bi bi-check"></i>';
            } else {
                checkbox.innerHTML = '';
            }

            updateChecklistProgress();
            showNotification('Checklist item updated!', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to update checklist item', 'error');
    });
}

function addChecklistItem(event) {
    event.preventDefault();

    const input = document.getElementById('checklist-input');
    const name = input.value.trim();

    if (!name) return;

    const taskId = '{{ $task->id }}';

    fetch('/checklist-items', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            task_id: taskId,
            name: name
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add new item to the list
            const checklistItems = document.getElementById('checklist-items');
            const emptyState = document.getElementById('empty-checklist');

            if (emptyState) {
                emptyState.style.display = 'none';
            }

            const newItem = document.createElement('div');
            newItem.className = 'checklist-item';
            newItem.setAttribute('data-id', data.data.id);
            newItem.innerHTML = `
                <div class="checklist-checkbox" onclick="toggleChecklistItem(${data.data.id})"></div>
                <div class="checklist-text">${data.data.name}</div>
                <div class="checklist-actions">
                    <button class="checklist-action-btn delete" onclick="deleteChecklistItem(${data.data.id})" title="Delete item">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;

            checklistItems.appendChild(newItem);
            input.value = '';
            updateChecklistProgress();
            showNotification('Checklist item added!', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to add checklist item', 'error');
    });
}

function deleteChecklistItem(itemId) {
    if (!confirm('Are you sure you want to delete this checklist item?')) return;

    fetch(`/checklist-items/${itemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const item = document.querySelector(`[data-id="${itemId}"]`);
            item.remove();
            updateChecklistProgress();
            showNotification('Checklist item deleted!', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to delete checklist item', 'error');
    });
}

// Initialize checklist progress on page load
document.addEventListener('DOMContentLoaded', function() {
    updateChecklistProgress();
});
</script>
@endpush
