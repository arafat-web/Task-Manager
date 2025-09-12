@extends('layouts.app')

@section('title', 'Routines')

@section('content')
<style>
    :root {
        --routine-primary: #8b5cf6;
        --routine-secondary: #a855f7;
        --routine-success: #10b981;
        --routine-warning: #f59e0b;
        --routine-danger: #ef4444;
        --routine-info: #3b82f6;
        --routine-light: #faf5ff;
        --routine-dark: #1e293b;
        --routine-gray: #64748b;
        --routine-border: #e2e8f0;
        --routine-shadow: rgba(0, 0, 0, 0.1);
        --routine-shadow-lg: rgba(0, 0, 0, 0.15);
    }

    .routines-header {
        background: linear-gradient(135deg, var(--routine-primary) 0%, var(--routine-secondary) 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px var(--routine-shadow-lg);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid var(--routine-border);
        box-shadow: 0 4px 6px -1px var(--routine-shadow);
        transition: all 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px var(--routine-shadow-lg);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .routine-section {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--routine-border);
        box-shadow: 0 4px 6px -1px var(--routine-shadow);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .section-header {
        background: var(--routine-light);
        padding: 1.5rem;
        border-bottom: 1px solid var(--routine-border);
        display: flex;
        align-items: center;
        justify-content: between;
        gap: 0.75rem;
    }

    .section-title {
        font-weight: 700;
        color: var(--routine-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .routine-card {
        background: white;
        border: 1px solid var(--routine-border);
        border-radius: 8px;
        margin-bottom: 1rem;
        transition: all 0.2s ease;
    }

    .routine-card:hover {
        border-color: var(--routine-primary);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px var(--routine-shadow);
    }

    .routine-card-body {
        padding: 1.25rem;
    }

    .routine-title {
        font-weight: 600;
        color: var(--routine-dark);
        margin-bottom: 0.5rem;
    }

    .routine-description {
        color: var(--routine-gray);
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
    }

    .routine-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1rem;
        font-size: 0.875rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        color: var(--routine-gray);
    }

    .routine-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-modern {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        font-size: 0.875rem;
    }

    .btn-modern.btn-primary {
        background: var(--routine-primary);
        color: white;
    }

    .btn-modern.btn-primary:hover {
        background: var(--routine-secondary);
        transform: translateY(-1px);
        color: white;
    }

    .btn-modern.btn-warning {
        background: var(--routine-warning);
        color: white;
    }

    .btn-modern.btn-warning:hover {
        background: #d97706;
        color: white;
    }

    .btn-modern.btn-danger {
        background: var(--routine-danger);
        color: white;
    }

    .btn-modern.btn-danger:hover {
        background: #dc2626;
        color: white;
    }

    .btn-modern.btn-secondary {
        background: var(--routine-gray);
        color: white;
    }

    .btn-modern.btn-secondary:hover {
        background: var(--routine-dark);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: var(--routine-gray);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .view-all-btn {
        margin-top: 1rem;
        width: 100%;
        justify-content: center;
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="routines-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-2">
                    <i class="fas fa-sync-alt me-3"></i>Routines Dashboard
                </h1>
                <p class="mb-0 opacity-75">Manage your daily, weekly, and monthly routines</p>
            </div>
            <a href="{{ route('routines.create') }}" class="btn btn-light btn-lg">
                <i class="fas fa-plus me-2"></i>Add New Routine
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: var(--routine-primary);">
                <i class="fas fa-calendar-day"></i>
            </div>
            <h3 class="h4 mb-1">{{ count($upcomingDailyRoutines) }}</h3>
            <p class="text-muted mb-0">Daily Routines</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(168, 85, 247, 0.1); color: var(--routine-secondary);">
                <i class="fas fa-calendar-week"></i>
            </div>
            <h3 class="h4 mb-1">{{ count($upcomingWeeklyRoutines) }}</h3>
            <p class="text-muted mb-0">Weekly Routines</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--routine-warning);">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h3 class="h4 mb-1">{{ count($upcomingMonthlyRoutines) }}</h3>
            <p class="text-muted mb-0">Monthly Routines</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--routine-success);">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3 class="h4 mb-1">{{ count($upcomingDailyRoutines) + count($upcomingWeeklyRoutines) + count($upcomingMonthlyRoutines) }}</h3>
            <p class="text-muted mb-0">Total Active</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Daily Routines Section -->
        <div class="col-lg-4">
            <div class="routine-section">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-sun" style="color: var(--routine-primary);"></i>
                        Daily Routines
                    </h3>
                </div>
                <div class="p-3">
                    @forelse($upcomingDailyRoutines as $routine)
                        <div class="routine-card">
                            <div class="routine-card-body">
                                <h5 class="routine-title">{{ $routine->title }}</h5>
                                @if($routine->description)
                                    <p class="routine-description">{{ Str::limit($routine->description, 100) }}</p>
                                @endif

                                <div class="routine-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-calendar-day"></i>
                                        <span>{{ implode(', ', json_decode($routine->days, true) ?? []) }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ $routine->start_time }} - {{ $routine->end_time }}</span>
                                    </div>
                                </div>

                                <div class="routine-actions">
                                    <a href="{{ route('routines.edit', $routine->id) }}" class="btn-modern btn-warning">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('routines.destroy', $routine->id) }}" method="POST" style="display: inline;"
                                          onsubmit="return confirm('Are you sure you want to delete this routine?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-modern btn-danger">
                                            <i class="fas fa-trash"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-sun"></i>
                            <p class="mb-0">No daily routines scheduled</p>
                        </div>
                    @endforelse

                    <a href="{{ route('routines.showDaily') }}" class="btn-modern btn-secondary view-all-btn">
                        <i class="fas fa-eye"></i>
                        View All Daily Routines
                    </a>
                </div>
            </div>
        </div>

        <!-- Weekly Routines Section -->
        <div class="col-lg-4">
            <div class="routine-section">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-calendar-week" style="color: var(--routine-secondary);"></i>
                        Weekly Routines
                    </h3>
                </div>
                <div class="p-3">
                    @forelse($upcomingWeeklyRoutines as $routine)
                        <div class="routine-card">
                            <div class="routine-card-body">
                                <h5 class="routine-title">{{ $routine->title }}</h5>
                                @if($routine->description)
                                    <p class="routine-description">{{ Str::limit($routine->description, 100) }}</p>
                                @endif

                                <div class="routine-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-calendar-week"></i>
                                        <span>{{ implode(', ', json_decode($routine->weeks, true) ?? []) }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ $routine->start_time }} - {{ $routine->end_time }}</span>
                                    </div>
                                </div>

                                <div class="routine-actions">
                                    <a href="{{ route('routines.edit', $routine->id) }}" class="btn-modern btn-warning">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('routines.destroy', $routine->id) }}" method="POST" style="display: inline;"
                                          onsubmit="return confirm('Are you sure you want to delete this routine?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-modern btn-danger">
                                            <i class="fas fa-trash"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-calendar-week"></i>
                            <p class="mb-0">No weekly routines scheduled</p>
                        </div>
                    @endforelse

                    <a href="{{ route('routines.showWeekly') }}" class="btn-modern btn-secondary view-all-btn">
                        <i class="fas fa-eye"></i>
                        View All Weekly Routines
                    </a>
                </div>
            </div>
        </div>

        <!-- Monthly Routines Section -->
        <div class="col-lg-4">
            <div class="routine-section">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-calendar-alt" style="color: var(--routine-warning);"></i>
                        Monthly Routines
                    </h3>
                </div>
                <div class="p-3">
                    @forelse($upcomingMonthlyRoutines as $routine)
                        <div class="routine-card">
                            <div class="routine-card-body">
                                <h5 class="routine-title">{{ $routine->title }}</h5>
                                @if($routine->description)
                                    <p class="routine-description">{{ Str::limit($routine->description, 100) }}</p>
                                @endif

                                <div class="routine-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>{{ implode(', ', array_map(function ($month) {
                                            return DateTime::createFromFormat('!m', $month)->format('F');
                                        }, json_decode($routine->months, true) ?? [])) }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ $routine->start_time }} - {{ $routine->end_time }}</span>
                                    </div>
                                </div>

                                <div class="routine-actions">
                                    <a href="{{ route('routines.edit', $routine->id) }}" class="btn-modern btn-warning">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('routines.destroy', $routine->id) }}" method="POST" style="display: inline;"
                                          onsubmit="return confirm('Are you sure you want to delete this routine?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-modern btn-danger">
                                            <i class="fas fa-trash"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-calendar-alt"></i>
                            <p class="mb-0">No monthly routines scheduled</p>
                        </div>
                    @endforelse

                    <a href="{{ route('routines.showMonthly') }}" class="btn-modern btn-secondary view-all-btn">
                        <i class="fas fa-eye"></i>
                        View All Monthly Routines
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
