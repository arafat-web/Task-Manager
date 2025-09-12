@extends('layouts.app')

@section('title', 'Projects')

@push('styles')
<style>
    .project-card {
        background: white;
        border: 2px solid var(--gray-200);
        border-radius: 12px;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .project-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-500);
    }

    .project-color-bar {
        height: 5px;
        width: 100%;
        position: absolute;
        top: 0;
        left: 0;
    }

    .project-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 18px;
        color: white;
        margin-bottom: 16px;
        flex-shrink: 0;
        box-shadow: var(--shadow-sm);
    }

    .project-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid;
    }

    .status-not-started {
        background: var(--gray-100);
        color: var(--gray-700);
        border-color: var(--gray-300);
    }

    .status-in-progress {
        background: var(--primary-100);
        color: var(--primary-700);
        border-color: var(--primary-400);
    }

    .status-completed {
        background: rgba(16, 185, 129, 0.15);
        color: var(--success-700);
        border-color: var(--success-400);
    }

    .project-progress {
        height: 10px;
        background: var(--gray-200);
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid var(--gray-300);
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
    }

    .project-progress-bar {
        height: 100%;
        border-radius: 5px;
        transition: width 0.3s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    .view-toggle {
        background: var(--gray-100);
        border: 2px solid var(--gray-200);
        border-radius: 10px;
        padding: 6px;
        display: flex;
        gap: 4px;
    }

    .view-toggle-btn {
        padding: 10px 14px;
        border: none;
        background: transparent;
        border-radius: 6px;
        color: var(--gray-600);
        transition: all 0.2s ease;
        font-size: 14px;
        font-weight: 500;
    }

    .view-toggle-btn.active {
        background: white;
        color: var(--gray-800);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
    }

    .search-filters {
        background: white;
        border: 2px solid var(--gray-200);
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 28px;
        box-shadow: var(--shadow-sm);
    }

    .filter-group {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        align-items: center;
    }

    .search-input {
        flex: 1;
        min-width: 250px;
        border: 2px solid var(--gray-200);
        border-radius: 10px;
        padding: 12px 18px;
        background: white;
        transition: all 0.2s ease;
        font-size: 14px;
        color: var(--gray-700);
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 4px var(--primary-100);
    }

    .search-input::placeholder {
        color: var(--gray-400);
    }

    .filter-select {
        padding: 12px 18px;
        border: 2px solid var(--gray-200);
        border-radius: 10px;
        background: white;
        color: var(--gray-700);
        min-width: 160px;
        font-size: 14px;
        font-weight: 500;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 4px var(--primary-100);
    }

    .list-view .project-card {
        display: flex;
        align-items: center;
        padding: 24px 28px;
        margin-bottom: 14px;
    }

    .list-view .project-avatar {
        margin-bottom: 0;
        margin-right: 20px;
    }

    .list-view .project-info {
        flex: 1;
    }

    .project-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-top: 14px;
        font-size: 13px;
        color: var(--gray-500);
        font-weight: 500;
    }

    .project-meta span {
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--gray-600);
    }

    .project-actions {
        display: flex;
        gap: 10px;
        margin-top: 18px;
    }

    .btn-action {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: 2px solid var(--gray-200);
        background: white;
        color: var(--gray-500);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        font-size: 16px;
        text-decoration: none;
    }

    .btn-action:hover {
        color: var(--primary-600);
        border-color: var(--primary-500);
        background: var(--primary-50);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .btn-action.danger:hover {
        color: var(--error-600);
        border-color: var(--error-500);
        background: rgba(239, 68, 68, 0.05);
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: var(--gray-500);
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: var(--gray-100);
        border: 2px solid var(--gray-200);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        font-size: 32px;
        color: var(--gray-400);
    }

    .project-team-avatars {
        display: flex;
        margin-left: -8px;
    }

    .team-avatar {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: var(--primary-500);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 600;
        margin-left: -8px;
        border: 3px solid white;
        box-shadow: var(--shadow-sm);
    }

    .card-title {
        color: var(--gray-800);
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 8px;
    }

    .text-secondary {
        color: var(--gray-500) !important;
    }

    .text-muted {
        color: var(--gray-400) !important;
    }

    .text-danger {
        color: var(--error-500) !important;
        font-weight: 600;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.5;
        max-height: 3em;
    }

    .content-header {
        margin-bottom: 32px;
        padding-bottom: 24px;
        border-bottom: 2px solid var(--gray-200);
    }

    .content-title {
        color: var(--gray-900);
        font-weight: 700;
        font-size: 28px;
        margin-bottom: 4px;
    }

    .content-subtitle {
        color: var(--gray-500);
        font-size: 16px;
        margin: 0;
        font-weight: 500;
    }

    .main-content {
        padding: 32px;
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

    @media (max-width: 768px) {
        .filter-group {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input {
            min-width: 100%;
        }

        .project-meta {
            flex-direction: column;
            gap: 8px;
            align-items: flex-start;
        }

        .project-actions {
            justify-content: flex-start;
        }

        .main-content {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title">Projects</h1>
                <p class="content-subtitle">Manage and track your project progress</p>
            </div>
            <a href="{{ route('projects.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>New Project
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search and Filters -->
    <div class="search-filters">
        <div class="filter-group">
            <input type="text"
                   class="search-input"
                   id="projectSearch"
                   placeholder="Search projects..."
                   autocomplete="off">

            <select class="filter-select" id="statusFilter">
                <option value="">All Status</option>
                <option value="not_started">Not Started</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>

            <select class="filter-select" id="sortFilter">
                <option value="name">Sort by Name</option>
                <option value="created_at">Sort by Created</option>
                <option value="end_date">Sort by Deadline</option>
                <option value="progress">Sort by Progress</option>
            </select>

            <div class="view-toggle">
                <button class="view-toggle-btn active" data-view="grid">
                    <i class="bi bi-grid-3x3-gap"></i>
                </button>
                <button class="view-toggle-btn" data-view="list">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Projects Container -->
    <div id="projectsContainer" class="projects-grid">
        @if($projects->count() > 0)
            <div class="row" id="projectsList">
                @foreach($projects as $project)
                    @php
                        // Calculate project progress
                        $totalTasks = $project->tasks->count();
                        $completedTasks = $project->tasks->where('status', 'completed')->count();
                        $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;

                        // Generate project color based on name
                        $colors = ['#6366f1', '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#ec4899'];
                        $colorIndex = strlen($project->name) % count($colors);
                        $projectColor = $colors[$colorIndex];

                        // Format status
                        $statusClass = match($project->status) {
                            'not_started' => 'status-not-started',
                            'in_progress' => 'status-in-progress',
                            'completed' => 'status-completed',
                            default => 'status-not-started'
                        };

                        $statusText = match($project->status) {
                            'not_started' => 'Not Started',
                            'in_progress' => 'In Progress',
                            'completed' => 'Completed',
                            default => 'Not Started'
                        };

                        // Team members count
                        $teamCount = $project->teamMembers ? $project->teamMembers->count() : 0;
                    @endphp

                    <div class="col-lg-4 col-md-6 col-12 mb-4 project-item"
                         data-name="{{ strtolower($project->name) }}"
                         data-status="{{ $project->status }}"
                         data-progress="{{ $progress }}"
                         data-created="{{ $project->created_at->timestamp }}">
                        <div class="project-card h-100">
                            <div class="project-color-bar" style="background: {{ $projectColor }};"></div>

                            <div class="card-body p-4">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="project-avatar" style="background: {{ $projectColor }};">
                                        {{ strtoupper(substr($project->name, 0, 1)) }}
                                    </div>

                                    <div class="flex-grow-1 min-w-0">
                                        <h5 class="card-title mb-2 text-truncate" style="color: var(--gray-900); font-weight: 600;">{{ $project->name }}</h5>
                                        <p class="text-secondary mb-3 line-clamp-2" style="color: var(--gray-600) !important; line-height: 1.4;">
                                            {{ strip_tags($project->description) ?: 'No description available' }}
                                        </p>

                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <span class="project-status {{ $statusClass }}">
                                                <i class="bi bi-circle-fill" style="font-size: 8px;"></i>
                                                {{ $statusText }}
                                            </span>
                                            <small class="text-muted" style="color: var(--gray-600) !important; font-weight: 500;">{{ round($progress) }}% complete</small>
                                        </div>

                                        <div class="project-progress mb-3">
                                            <div class="project-progress-bar"
                                                 style="background: {{ $projectColor }}; width: {{ $progress }}%;"></div>
                                        </div>

                                        <div class="project-meta">
                                            @if($project->end_date)
                                                <span>
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    @if($project->end_date->isFuture())
                                                        Due {{ $project->end_date->diffForHumans() }}
                                                    @else
                                                        <span class="text-danger">Overdue</span>
                                                    @endif
                                                </span>
                                            @endif

                                            @if($totalTasks > 0)
                                                <span>
                                                    <i class="bi bi-list-task me-1"></i>
                                                    {{ $totalTasks }} tasks
                                                </span>
                                            @endif

                                            @if($teamCount > 0)
                                                <span class="d-flex align-items-center">
                                                    <div class="project-team-avatars me-1">
                                                        @foreach($project->teamMembers->take(3) as $member)
                                                            <div class="team-avatar" title="{{ $member->name }}">
                                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                                            </div>
                                                        @endforeach
                                                        @if($teamCount > 3)
                                                            <div class="team-avatar">+{{ $teamCount - 3 }}</div>
                                                        @endif
                                                    </div>
                                                    {{ $teamCount }} members
                                                </span>
                                            @endif
                                        </div>

                                        <div class="project-actions">
                                            <a href="{{ route('projects.tasks.index', $project->id) }}"
                                               class="btn-action"
                                               title="View Tasks">
                                                <i class="bi bi-list-task"></i>
                                            </a>
                                            <a href="{{ route('projects.show', $project->id) }}"
                                               class="btn-action"
                                               title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('projects.edit', $project->id) }}"
                                               class="btn-action"
                                               title="Edit Project">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('projects.destroy', $project->id) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this project? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn-action danger"
                                                        title="Delete Project">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-folder-plus"></i>
                </div>
                <h3>No Projects Found</h3>
                <p class="mb-4">Get started by creating your first project and begin organizing your work.</p>
                <a href="{{ route('projects.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Create Your First Project
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('projectSearch');
    const statusFilter = document.getElementById('statusFilter');
    const sortFilter = document.getElementById('sortFilter');
    const viewToggleBtns = document.querySelectorAll('.view-toggle-btn');
    const projectsContainer = document.getElementById('projectsContainer');
    const projectsList = document.getElementById('projectsList');
    let currentView = 'grid';

    // Search functionality
    function filterProjects() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const projectItems = document.querySelectorAll('.project-item');

        projectItems.forEach(item => {
            const name = item.dataset.name;
            const status = item.dataset.status;

            const matchesSearch = name.includes(searchTerm);
            const matchesStatus = !statusValue || status === statusValue;

            if (matchesSearch && matchesStatus) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Sort functionality
    function sortProjects() {
        const sortValue = sortFilter.value;
        const projectItems = Array.from(document.querySelectorAll('.project-item'));

        projectItems.sort((a, b) => {
            switch(sortValue) {
                case 'name':
                    return a.dataset.name.localeCompare(b.dataset.name);
                case 'created_at':
                    return parseInt(b.dataset.created) - parseInt(a.dataset.created);
                case 'end_date':
                    // Implement date sorting if needed
                    return 0;
                case 'progress':
                    return parseInt(b.dataset.progress) - parseInt(a.dataset.progress);
                default:
                    return 0;
            }
        });

        // Re-append sorted items
        projectItems.forEach(item => {
            projectsList.appendChild(item);
        });
    }

    // View toggle functionality
    function toggleView(view) {
        currentView = view;

        // Update active button
        viewToggleBtns.forEach(btn => {
            if (btn.dataset.view === view) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        // Update container classes
        if (view === 'list') {
            projectsContainer.classList.add('list-view');
            projectsContainer.classList.remove('projects-grid');
            // Change column classes for list view
            document.querySelectorAll('.project-item').forEach(item => {
                item.className = 'col-12 mb-3 project-item';
            });
        } else {
            projectsContainer.classList.remove('list-view');
            projectsContainer.classList.add('projects-grid');
            // Restore original column classes
            document.querySelectorAll('.project-item').forEach(item => {
                item.className = 'col-lg-4 col-md-6 col-12 mb-4 project-item';
            });
        }
    }

    // Event listeners
    searchInput.addEventListener('input', filterProjects);
    statusFilter.addEventListener('change', filterProjects);
    sortFilter.addEventListener('change', sortProjects);

    viewToggleBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            toggleView(btn.dataset.view);
        });
    });
});
</script>
@endpush
