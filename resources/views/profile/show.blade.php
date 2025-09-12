@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<style>
    :root {
        --profile-primary: #6366f1;
        --profile-secondary: #8b5cf6;
        --profile-success: #10b981;
        --profile-warning: #f59e0b;
        --profile-danger: #ef4444;
        --profile-info: #3b82f6;
        --profile-light: #f8fafc;
        --profile-dark: #1e293b;
        --profile-gray: #64748b;
        --profile-border: #e2e8f0;
        --profile-shadow: rgba(0, 0, 0, 0.1);
        --profile-shadow-lg: rgba(0, 0, 0, 0.15);
    }

    .profile-header {
        background: linear-gradient(135deg, var(--profile-primary) 0%, var(--profile-secondary) 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px var(--profile-shadow-lg);
    }

    .profile-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--profile-border);
        box-shadow: 0 4px 6px -1px var(--profile-shadow);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .profile-card-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--profile-border);
        background: var(--profile-light);
    }

    .profile-card-body {
        padding: 2rem;
    }

    .avatar-section {
        text-align: center;
        margin-bottom: 2rem;
    }

    .avatar-large {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin-bottom: 1rem;
        border: 4px solid var(--profile-primary);
        object-fit: cover;
    }

    .avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--profile-primary), var(--profile-secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0 auto 1rem;
        border: 4px solid var(--profile-primary);
    }

    .profile-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: var(--profile-light);
        border-radius: 8px;
        border: 1px solid var(--profile-border);
    }

    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: white;
    }

    .info-icon.email { background: var(--profile-info); }
    .info-icon.phone { background: var(--profile-success); }
    .info-icon.location { background: var(--profile-warning); }
    .info-icon.website { background: var(--profile-secondary); }
    .info-icon.bio { background: var(--profile-primary); }

    .info-content h6 {
        margin: 0 0 0.25rem 0;
        color: var(--profile-dark);
        font-weight: 600;
    }

    .info-content p {
        margin: 0;
        color: var(--profile-gray);
        font-size: 0.875rem;
    }

    .stats-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border: 1px solid var(--profile-border);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px var(--profile-shadow);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--profile-primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--profile-gray);
        font-size: 0.875rem;
        font-weight: 500;
    }

    .btn-modern {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-modern.btn-primary {
        background: var(--profile-primary);
        color: white;
    }

    .btn-modern.btn-primary:hover {
        background: var(--profile-secondary);
        transform: translateY(-1px);
        color: white;
    }

    .btn-modern.btn-outline-secondary {
        background: transparent;
        color: var(--profile-gray);
        border: 1px solid var(--profile-border);
    }

    .btn-modern.btn-outline-secondary:hover {
        background: var(--profile-gray);
        color: white;
        transform: translateY(-1px);
    }

    .alert-modern {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid var(--profile-success);
    }

    .joined-date {
        background: rgba(99, 102, 241, 0.1);
        color: var(--profile-primary);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="profile-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-2">
                    <i class="fas fa-user me-3"></i>Profile
                </h1>
                <p class="mb-0 opacity-75">Manage your account settings and preferences</p>
            </div>
            <div>
                <a href="{{ route('profile.edit') }}" class="btn btn-light btn-lg me-2">
                    <i class="fas fa-edit me-2"></i>Edit Profile
                </a>
                <a href="{{ route('profile.password') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-key me-2"></i>Change Password
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-modern">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-4">
            <!-- Profile Card -->
            <div class="profile-card">
                <div class="profile-card-body">
                    <div class="avatar-section">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="avatar-large">
                        @else
                            <div class="avatar-placeholder">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        @endif
                        <h3 style="color: var(--profile-dark);">{{ $user->name }}</h3>
                        <p class="text-muted">{{ $user->email }}</p>
                        <div class="joined-date">
                            <i class="fas fa-calendar-alt"></i>
                            Joined {{ $user->created_at->format('M Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <h5 class="mb-0" style="color: var(--profile-dark);">
                        <i class="fas fa-chart-bar me-2" style="color: var(--profile-primary);"></i>Activity Statistics
                    </h5>
                </div>
                <div class="profile-card-body">
                    <div class="stats-section">
                        <div class="stat-card">
                            <div class="stat-number">{{ $user->projects()->count() }}</div>
                            <div class="stat-label">Projects</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ $user->tasks()->count() }}</div>
                            <div class="stat-label">Tasks</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ $user->notes()->count() }}</div>
                            <div class="stat-label">Notes</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ $user->reminders()->count() }}</div>
                            <div class="stat-label">Reminders</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ $user->routines()->count() }}</div>
                            <div class="stat-label">Routines</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ $user->files()->count() }}</div>
                            <div class="stat-label">Files</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <h5 class="mb-0" style="color: var(--profile-dark);">
                        <i class="fas fa-user-circle me-2" style="color: var(--profile-primary);"></i>Personal Information
                    </h5>
                </div>
                <div class="profile-card-body">
                    <div class="profile-info">
                        <div class="info-item">
                            <div class="info-icon email">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <h6>Email Address</h6>
                                <p>{{ $user->email }}</p>
                            </div>
                        </div>

                        @if($user->phone)
                            <div class="info-item">
                                <div class="info-icon phone">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="info-content">
                                    <h6>Phone Number</h6>
                                    <p>{{ $user->phone }}</p>
                                </div>
                            </div>
                        @endif

                        @if($user->location)
                            <div class="info-item">
                                <div class="info-icon location">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="info-content">
                                    <h6>Location</h6>
                                    <p>{{ $user->location }}</p>
                                </div>
                            </div>
                        @endif

                        @if($user->website)
                            <div class="info-item">
                                <div class="info-icon website">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <div class="info-content">
                                    <h6>Website</h6>
                                    <p><a href="{{ $user->website }}" target="_blank" style="color: var(--profile-primary);">{{ $user->website }}</a></p>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($user->bio)
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <div class="info-icon bio">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <div class="info-content">
                                <h6>Bio</h6>
                                <p>{{ $user->bio }}</p>
                            </div>
                        </div>
                    @endif

                    @if(!$user->phone && !$user->location && !$user->website && !$user->bio)
                        <div class="text-center py-4">
                            <i class="fas fa-user-plus fa-3x mb-3" style="color: var(--profile-border);"></i>
                            <h5 style="color: var(--profile-gray);">Complete Your Profile</h5>
                            <p class="text-muted mb-4">Add more information to help others get to know you better.</p>
                            <a href="{{ route('profile.edit') }}" class="btn-modern btn-primary">
                                <i class="fas fa-edit"></i>Add Information
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Account Security -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <h5 class="mb-0" style="color: var(--profile-dark);">
                        <i class="fas fa-shield-alt me-2" style="color: var(--profile-primary);"></i>Account Security
                    </h5>
                </div>
                <div class="profile-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 style="color: var(--profile-dark);">Password</h6>
                            <p class="text-muted mb-0">Last updated {{ $user->updated_at->format('M d, Y') }}</p>
                        </div>
                        <a href="{{ route('profile.password') }}" class="btn-modern btn-outline-secondary">
                            <i class="fas fa-key"></i>Change Password
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
