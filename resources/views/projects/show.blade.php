@extends('layouts.app')

@section('title', $project->name . ' - Project Details')

@push('styles')
<style>
    .project-header {
        background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
        border-radius: 12px;
        padding: 18px 24px;
        color: white;
        margin-bottom: 16px;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--primary-500);
        box-shadow: var(--shadow-md);
    }

    .project-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 120px;
        height: 120px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(30px, -30px);
    }

    .project-avatar-large {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 22px;
        color: var(--primary-600);
        background: white;
        margin-bottom: 12px;
        box-shadow: var(--shadow-md);
        position: relative;
        z-index: 1;
    }

    .project-description {
        line-height: 1.6;
    }

    .project-description h1,
    .project-description h2 {
        color: var(--gray-800);
        margin-bottom: 12px;
        margin-top: 24px;
    }

    .project-description h1 {
        font-size: 1.5rem;
    }

    .project-description h2 {
        font-size: 1.25rem;
    }

    .project-description p {
        margin-bottom: 12px;
    }

    .project-description ul,
    .project-description ol {
        margin-bottom: 12px;
        padding-left: 24px;
    }

    .project-description li {
        margin-bottom: 6px;
    }

    .project-description blockquote {
        border-left: 4px solid var(--primary-500);
        background: var(--primary-50);
        padding: 16px 20px;
        margin: 16px 0;
        border-radius: 0 8px 8px 0;
    }

    .project-description pre {
        background: var(--gray-100);
        padding: 16px;
        border-radius: 8px;
        margin: 16px 0;
        overflow-x: auto;
    }

    .project-description code {
        background: var(--gray-100);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.9em;
    }

    .project-description a {
        color: var(--primary-600);
        text-decoration: none;
    }

    .project-description a:hover {
        text-decoration: underline;
    }

    .project-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
        margin-top: 14px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255, 255, 255, 0.95);
    }

    .meta-icon {
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .progress-card {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: var(--shadow-sm);
    }

    .progress-ring {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto 14px;
    }

    .progress-ring svg {
        width: 100px;
        height: 100px;
        transform: rotate(-90deg);
    }

    .progress-ring circle {
        fill: transparent;
        stroke-width: 8;
        r: 56;
        cx: 65;
        cy: 65;
        stroke-dasharray: 351.86;
        stroke-linecap: round;
    }

    .progress-ring .bg {
        stroke: var(--gray-200);
    }

    .progress-ring .progress {
        stroke: var(--primary-500);
        stroke-dashoffset: 351.86;
        transition: stroke-dashoffset 0.8s ease;
        filter: drop-shadow(0 2px 4px rgba(99, 102, 241, 0.3));
    }

    .progress-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 22px;
        font-weight: 700;
        color: var(--gray-800);
    }

    .info-card {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: 12px;
        padding: 16px;
        height: fit-content;
        box-shadow: var(--shadow-sm);
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid var(--gray-200);
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: var(--gray-500);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .info-value {
        color: var(--gray-800);
        font-weight: 600;
        text-align: right;
    }

    .team-card {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: var(--shadow-sm);
    }

    .team-member {
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid var(--gray-200);
    }

    .team-member:last-child {
        border-bottom: none;
    }

    .member-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: var(--primary-500);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-right: 10px;
        flex-shrink: 0;
        font-size: 14px;
        border: 2px solid white;
        box-shadow: var(--shadow-sm);
    }

    .member-info h6 {
        margin: 0 0 2px 0;
        color: var(--gray-800);
        font-weight: 600;
        font-size: 14px;
    }

    .member-info p {
        margin: 0;
        color: var(--gray-500);
        font-size: 12px;
        font-weight: 500;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 22px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 2px solid;
    }

    .status-not-started {
        background: var(--gray-50);
        color: var(--gray-600);
        border-color: var(--gray-300);
    }

    .status-in-progress {
        background: var(--primary-50);
        color: var(--primary-700);
        border-color: var(--primary-500);
    }

    .status-completed {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success-600);
        border-color: var(--success-500);
    }

    .quick-actions {
        display: flex;
        gap: 8px;
        margin-top: 14px;
    }

    .action-btn {
        flex: 1;
        padding: 10px;
        border: 1px solid var(--gray-200);
        background: white;
        border-radius: 8px;
        text-align: center;
        text-decoration: none;
        color: var(--gray-700);
        font-weight: 600;
        transition: all 0.2s ease;
        font-size: 13px;
    }

    .action-btn:hover {
        border-color: var(--primary-500);
        background: var(--primary-50);
        color: var(--primary-700);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .action-btn.primary {
        background: var(--primary-600);
        border-color: var(--primary-600);
        color: white;
    }

    .action-btn.primary:hover {
        background: var(--primary-700);
        border-color: var(--primary-700);
        color: white;
    }

    .modal-content {
        border: none;
        border-radius: 18px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 2px solid var(--gray-200);
    }

    .modal-header {
        border-bottom: 1px solid var(--gray-200);
        padding: 14px 18px;
        background: var(--gray-50);
    }

    .modal-body {
        padding: 16px 18px;
    }

    .modal-footer {
        border-top: 1px solid var(--gray-200);
        padding: 12px 18px;
        background: var(--gray-50);
    }

    .content-header {
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--gray-200);
    }

    .content-title {
        color: var(--gray-900);
        font-weight: 700;
        font-size: 20px;
        margin-bottom: 2px;
    }

    .content-subtitle {
        color: var(--gray-500);
        font-size: 13px;
        margin: 0;
        font-weight: 500;
    }

    .main-content {
        padding: 16px;
        background: var(--gray-25);
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        border: 2px solid var(--success-500);
        color: var(--success-600);
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        font-weight: 500;
    }

    .text-success {
        color: var(--success-600) !important;
        font-weight: 600;
    }

    .text-danger {
        color: var(--error-600) !important;
        font-weight: 600;
    }

    .text-primary {
        color: var(--primary-600) !important;
    }

    .text-secondary {
        color: var(--gray-500) !important;
    }

    @media (max-width: 768px) {
        .project-header {
            padding: 24px 20px;
        }

        .project-meta {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .quick-actions {
            flex-direction: column;
        }

        .main-content {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <!-- Back Button -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('projects.index') }}" class="me-3 text-decoration-none">
            <i class="bi bi-arrow-left fs-4 text-secondary"></i>
        </a>
        <span class="text-secondary">Back to Projects</span>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Project Header -->
    <div class="project-header">
        @php
            $colors = ['#6366f1', '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#ec4899'];
            $colorIndex = strlen($project->name) % count($colors);
            $projectColor = $colors[$colorIndex];
        @endphp

        <div class="project-avatar-large">
            {{ strtoupper(substr($project->name, 0, 1)) }}
        </div>

        <h1 class="mb-2">{{ $project->name }}</h1>
        <div class="project-description mb-0 opacity-90">
            {!! $project->description ?: '<em>No description available</em>' !!}
        </div>

        <div class="project-meta">
            <div class="meta-item">
                <div class="meta-icon">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <div>
                    <div class="fw-600">Timeline</div>
                    <small>{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y') : 'Not set' }} - {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('M d, Y') : 'Not set' }}</small>
                </div>
            </div>

            <div class="meta-item">
                <div class="meta-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div>
                    <div class="fw-600">Budget</div>
                    <small>${{ number_format($project->budget ?? 0, 2) }}</small>
                </div>
            </div>

            <div class="meta-item">
                <div class="meta-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="fw-600">Team Size</div>
                    <small>{{ $teamMembers->count() }} members</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Progress Overview -->
            <div class="progress-card">
                <h5 class="mb-4">Project Progress</h5>

                @php
                    $totalTasks = $project->tasks->count();
                    $completedTasks = $project->tasks->where('status', 'completed')->count();
                    $inProgressTasks = $project->tasks->where('status', 'in_progress')->count();
                    $todoTasks = $project->tasks->where('status', 'todo')->count();
                    $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                    $progressOffset = 326.73 - (326.73 * $progress / 100);
                @endphp

                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="progress-ring">
                            <svg>
                                <circle class="bg"></circle>
                                <circle class="progress" style="stroke-dashoffset: {{ $progressOffset }}; stroke: {{ $projectColor }};"></circle>
                            </svg>
                            <div class="progress-text">{{ round($progress) }}%</div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-4 text-center">
                                <div class="fs-3 fw-bold text-success">{{ $completedTasks }}</div>
                                <small class="text-secondary">Completed</small>
                            </div>
                            <div class="col-4 text-center">
                                <div class="fs-3 fw-bold text-primary">{{ $inProgressTasks }}</div>
                                <small class="text-secondary">In Progress</small>
                            </div>
                            <div class="col-4 text-center">
                                <div class="fs-3 fw-bold text-secondary">{{ $todoTasks }}</div>
                                <small class="text-secondary">To Do</small>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Overall Progress</span>
                                <span class="fw-bold">{{ $totalTasks }} total tasks</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <a href="{{ route('projects.tasks.index', $project->id) }}" class="action-btn primary">
                    <i class="bi bi-list-task me-2"></i>View Tasks
                </a>
                <a href="{{ route('projects.edit', $project->id) }}" class="action-btn">
                    <i class="bi bi-pencil me-2"></i>Edit Project
                </a>
                <a href="#" class="action-btn" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                    <i class="bi bi-person-plus me-2"></i>Add Member
                </a>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Project Info -->
            <div class="info-card mb-4">
                <h6 class="mb-3">Project Details</h6>

                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="status-badge {{ 'status-' . str_replace(' ', '-', strtolower($project->status ?? 'not_started')) }}">
                        <i class="bi bi-circle-fill" style="font-size: 8px;"></i>
                        {{ ucwords(str_replace('_', ' ', $project->status ?? 'not_started')) }}
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label">Start Date</span>
                    <span class="info-value">
                        {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y') : 'Not set' }}
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label">End Date</span>
                    <span class="info-value">
                        @if($project->end_date)
                            {{ \Carbon\Carbon::parse($project->end_date)->format('M d, Y') }}
                            @if(\Carbon\Carbon::parse($project->end_date)->isFuture())
                                <small class="text-success d-block">{{ \Carbon\Carbon::parse($project->end_date)->diffForHumans() }}</small>
                            @else
                                <small class="text-danger d-block">Overdue</small>
                            @endif
                        @else
                            Not set
                        @endif
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label">Tasks</span>
                    <span class="info-value">{{ $totalTasks }} total</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Budget</span>
                    <span class="info-value">${{ number_format($project->budget ?? 0, 2) }}</span>
                </div>
            </div>

            <!-- Team Members -->
            <div class="team-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0">Team Members ({{ $teamMembers->count() }})</h6>
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>

                @if($teamMembers->count() > 0)
                    @foreach ($teamMembers as $user)
                        <div class="team-member">
                            <div class="member-avatar" style="background: {{ $projectColor }};">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="member-info flex-grow-1">
                                <h6>{{ $user->name }}</h6>
                                <p>{{ $user->email }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-people fs-2 text-secondary mb-3 d-block"></i>
                        <p class="text-secondary">No team members yet</p>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                            Add First Member
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Member Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMemberModalLabel">Add Team Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('projects.addMember') }}" method="POST">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Select Team Member</label>
                        <select class="form-select" name="user_id" id="user_id" required>
                            <option value="">Choose a user...</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Member</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress ring on load
    const progressCircle = document.querySelector('.progress-ring .progress');
    if (progressCircle) {
        // Reset to 0 and animate to actual value
        const originalOffset = progressCircle.style.strokeDashoffset;
        progressCircle.style.strokeDashoffset = '326.73';

        setTimeout(() => {
            progressCircle.style.strokeDashoffset = originalOffset;
        }, 500);
    }
});
</script>
@endpush
