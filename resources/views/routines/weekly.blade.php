@extends('layouts.app')

@section('title', 'Weekly Routines')

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

    .weekly-header {
        background: linear-gradient(135deg, var(--routine-secondary) 0%, var(--routine-primary) 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px var(--routine-shadow-lg);
    }

    .breadcrumb-modern {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px var(--routine-shadow);
        border: 1px solid var(--routine-border);
    }

    .breadcrumb-modern .breadcrumb {
        margin: 0;
        background: none;
        padding: 0;
    }

    .breadcrumb-modern .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: var(--routine-gray);
        font-weight: 600;
    }

    .routine-card {
        background: white;
        border: 1px solid var(--routine-border);
        border-radius: 12px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 6px -1px var(--routine-shadow);
        margin-bottom: 1.5rem;
    }

    .routine-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px var(--routine-shadow-lg);
        border-color: var(--routine-secondary);
    }

    .routine-card-body {
        padding: 1.5rem;
    }

    .routine-title {
        font-weight: 700;
        color: var(--routine-dark);
        margin-bottom: 0.5rem;
        font-size: 1.25rem;
    }

    .routine-description {
        color: var(--routine-gray);
        margin-bottom: 1rem;
        line-height: 1.5;
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
        padding: 0.25rem 0.5rem;
        background: var(--routine-light);
        border-radius: 6px;
    }

    .routine-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
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

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 12px;
        border: 1px solid var(--routine-border);
        box-shadow: 0 4px 6px -1px var(--routine-shadow);
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: var(--routine-gray);
        opacity: 0.5;
    }

    .empty-state h3 {
        color: var(--routine-dark);
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: var(--routine-gray);
        margin-bottom: 2rem;
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="weekly-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-2">
                    <i class="fas fa-calendar-week me-3"></i>Weekly Routines
                </h1>
                <p class="mb-0 opacity-75">Manage your weekly recurring routines and schedules</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('routines.create') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-plus me-2"></i>Add Routine
                </a>
                <a href="{{ route('routines.index') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <nav class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('routines.index') }}" style="color: var(--routine-primary);">Routines</a></li>
            <li class="breadcrumb-item active">Weekly Routines</li>
        </ol>
    </nav>

    <div class="row">
        @forelse($weeklyRoutines as $routine)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="routine-card">
                    <div class="routine-card-body">
                        <h5 class="routine-title">{{ $routine->title }}</h5>
                        @if($routine->description)
                            <p class="routine-description">{{ Str::limit($routine->description, 120) }}</p>
                        @endif

                        <div class="routine-meta">
                            <div class="meta-item">
                                <i class="fas fa-calendar-week"></i>
                                <span>{{ implode(', ', array_slice(json_decode($routine->weeks, true) ?? [], 0, 3)) }}{{ count(json_decode($routine->weeks, true) ?? []) > 3 ? '...' : '' }}</span>
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
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="fas fa-calendar-week"></i>
                    <h3>No Weekly Routines Found</h3>
                    <p>You haven't created any weekly routines yet. Start by adding your first routine to organize your weekly schedule.</p>
                    <a href="{{ route('routines.create') }}" class="btn-modern btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>Create Your First Weekly Routine
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
