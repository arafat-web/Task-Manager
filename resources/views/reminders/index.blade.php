@extends('layouts.app')

@section('title', 'Reminders')

@section('content')
    <style>
        :root {
            --reminder-primary: #667eea;
            --reminder-secondary: #764ba2;
            --reminder-success: #10b981;
            --reminder-warning: #f59e0b;
            --reminder-danger: #ef4444;
            --reminder-info: #3b82f6;
            --reminder-light: #f8fafc;
            --reminder-dark: #1e293b;
            --reminder-gray: #64748b;
            --reminder-border: #e2e8f0;
            --reminder-shadow: rgba(0, 0, 0, 0.1);
            --reminder-shadow-lg: rgba(0, 0, 0, 0.15);
        }

        .reminders-header {
            background: linear-gradient(135deg, var(--reminder-primary) 0%, var(--reminder-secondary) 100%);
            color: white;
            border-radius: 12px;
            padding: 0.875rem 1.25rem;
            margin-bottom: 0.875rem;
            box-shadow: 0 4px 12px var(--reminder-shadow-lg);
        }

        .search-filter-bar {
            background: white;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            margin-bottom: 0.875rem;
            box-shadow: 0 2px 4px -1px var(--reminder-shadow);
            border: 1px solid var(--reminder-border);
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.75rem;
            margin-bottom: 0.875rem;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 0.875rem;
            border: 1px solid var(--reminder-border);
            box-shadow: 0 2px 4px -1px var(--reminder-shadow);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px var(--reminder-shadow-lg);
        }

        .stat-card.primary {
            border-left: 4px solid var(--reminder-primary);
        }

        .stat-card.success {
            border-left: 4px solid var(--reminder-success);
        }

        .stat-card.warning {
            border-left: 4px solid var(--reminder-warning);
        }

        .stat-card.danger {
            border-left: 4px solid var(--reminder-danger);
        }

        .stat-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-info h3 {
            font-size: 1.625rem;
            font-weight: 700;
            margin: 0;
            color: var(--reminder-dark);
        }

        .stat-info p {
            margin: 0;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--reminder-gray);
        }

        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.3;
        }

        .reminders-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 0.875rem;
        }

        .reminder-card {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--reminder-border);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .reminder-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px var(--reminder-shadow-lg);
            border-color: var(--reminder-primary);
        }

        .reminder-card.priority-urgent {
            border-left: 4px solid var(--reminder-danger);
        }

        .reminder-card.priority-high {
            border-left: 4px solid #fd7e14;
        }

        .reminder-card.priority-medium {
            border-left: 4px solid var(--reminder-warning);
        }

        .reminder-card.priority-low {
            border-left: 4px solid var(--reminder-gray);
        }

        .reminder-card.reminder-completed {
            opacity: 0.7;
        }

        .reminder-card.reminder-overdue {
            background-color: #fef2f2;
        }

        .reminder-card-header {
            padding: 0.75rem 0.75rem 0.375rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .reminder-title {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--reminder-dark);
            margin: 0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .reminder-completed .reminder-title {
            text-decoration: line-through;
        }

        .priority-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .priority-badge.urgent {
            background: #fef2f2;
            color: var(--reminder-danger);
        }

        .priority-badge.high {
            background: #fff7ed;
            color: #ea580c;
        }

        .priority-badge.medium {
            background: #fffbeb;
            color: #d97706;
        }

        .priority-badge.low {
            background: #f8fafc;
            color: var(--reminder-gray);
        }

        .reminder-content {
            padding: 0 0.75rem;
            color: var(--reminder-gray);
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 0.375rem;
        }

        .reminder-meta {
            padding: 0.5rem 0.75rem;
            background: var(--reminder-light);
            border-top: 1px solid var(--reminder-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8125rem;
            color: var(--reminder-gray);
        }

        .reminder-actions {
            padding: 0.5rem 0.75rem;
            border-top: 1px solid var(--reminder-border);
            display: flex;
            gap: 0.375rem;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--reminder-border);
            border-radius: 6px;
            background: white;
            color: var(--reminder-gray);
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .action-btn:hover {
            background: var(--reminder-light);
            color: var(--reminder-dark);
            text-decoration: none;
            border-color: var(--reminder-primary);
        }

        .action-btn.success {
            border-color: var(--reminder-success);
            color: var(--reminder-success);
        }

        .action-btn.success:hover {
            background: var(--reminder-success);
            color: white;
        }

        .action-btn.warning {
            border-color: var(--reminder-warning);
            color: var(--reminder-warning);
        }

        .action-btn.warning:hover {
            background: var(--reminder-warning);
            color: white;
        }

        .action-btn.info {
            border-color: var(--reminder-info);
            color: var(--reminder-info);
        }

        .action-btn.info:hover {
            background: var(--reminder-info);
            color: white;
        }

        .action-btn.primary {
            border-color: var(--reminder-primary);
            color: var(--reminder-primary);
        }

        .action-btn.primary:hover {
            background: var(--reminder-primary);
            color: white;
        }

        .action-btn.danger {
            border-color: var(--reminder-danger);
            color: var(--reminder-danger);
        }

        .action-btn.danger:hover {
            background: var(--reminder-danger);
            color: white;
        }

        .tag {
            display: inline-block;
            background: var(--reminder-light);
            color: var(--reminder-gray);
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            margin: 0.125rem;
            border: 1px solid var(--reminder-border);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            border: 1px solid var(--reminder-border);
            box-shadow: 0 4px 6px -1px var(--reminder-shadow);
        }

        .empty-state-icon {
            font-size: 4rem;
            color: var(--reminder-gray);
            opacity: 0.5;
            margin-bottom: 1rem;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
    </style>

    <div class="container-fluid">
        <!-- Header Section -->
        <div class="reminders-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-2">
                        <i class="fas fa-bell me-3"></i>Reminders
                    </h1>
                    <p class="mb-0 opacity-75">Manage your reminders and stay on track with all your important tasks</p>
                </div>
                <div>
                    <a href="{{ route('reminders.create') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-plus me-2"></i>New Reminder
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-cards">
            <div class="stat-card primary">
                <div class="stat-content">
                    <div class="stat-info">
                        <h3>{{ $stats['total'] }}</h3>
                        <p>Total Reminders</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-bell" style="color: var(--reminder-primary);"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card success">
                <div class="stat-content">
                    <div class="stat-info">
                        <h3>{{ $stats['active'] }}</h3>
                        <p>Active</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-clock" style="color: var(--reminder-success);"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card warning">
                <div class="stat-content">
                    <div class="stat-info">
                        <h3>{{ $stats['due_today'] }}</h3>
                        <p>Due Today</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-calendar-day" style="color: var(--reminder-warning);"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card danger">
                <div class="stat-content">
                    <div class="stat-info">
                        <h3>{{ $stats['overdue'] }}</h3>
                        <p>Overdue</p>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-triangle" style="color: var(--reminder-danger);"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="search-filter-bar">
            <form id="reminder-filters" class="row align-items-end g-3">
                <div class="col-lg-3 col-md-4">
                    <label for="search" class="form-label fw-semibold">Search Reminders</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0 bg-transparent">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="search" name="search" class="form-control border-start-0 ps-0"
                            placeholder="Search titles, descriptions..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-lg-2 col-md-3">
                    <label for="status" class="form-label fw-semibold">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="active" {{ request('status', 'active') === 'active' ? 'selected' : '' }}>Active
                        </option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed
                        </option>
                        <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="due_today" {{ request('status') === 'due_today' ? 'selected' : '' }}>Due Today
                        </option>
                        <option value="due_soon" {{ request('status') === 'due_soon' ? 'selected' : '' }}>Due Soon</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-3">
                    <label for="priority" class="form-label fw-semibold">Priority</label>
                    <select id="priority" name="priority" class="form-select">
                        <option value="all">All Priorities</option>
                        @foreach ($priorities as $key => $label)
                            <option value="{{ $key }}" {{ request('priority') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-3">
                    <label for="category" class="form-label fw-semibold">Category</label>
                    <select id="category" name="category" class="form-select">
                        <option value="all">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-3">
                    <label for="view" class="form-label fw-semibold">View</label>
                    <select id="view" name="view" class="form-select">
                        <option value="list" {{ $view === 'list' ? 'selected' : '' }}>Grid View</option>
                        <option value="calendar" {{ $view === 'calendar' ? 'selected' : '' }}>Calendar View</option>
                    </select>
                </div>

                <div class="col-lg-1 col-md-2">
                    <button type="button" id="clear-filters" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Content Area -->
        <div id="content-area">
            @if ($view === 'calendar')
                @include('reminders.partials.calendar-view')
            @else
                @include('reminders.partials.list-view', ['reminders' => $reminders])
            @endif
        </div>

        <!-- Loading Overlay -->
        <div id="loading-overlay" class="loading-overlay">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form elements
            const searchInput = document.getElementById('search');
            const statusSelect = document.getElementById('status');
            const prioritySelect = document.getElementById('priority');
            const categorySelect = document.getElementById('category');
            const viewSelect = document.getElementById('view');
            const clearFiltersBtn = document.getElementById('clear-filters');
            const loadingOverlay = document.getElementById('loading-overlay');

            // Search timeout for debouncing
            let searchTimeout;

            // Filter change handlers
            function handleFilterChange() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    filterReminders();
                }, 300);
            }

            // Add event listeners
            searchInput?.addEventListener('input', handleFilterChange);
            statusSelect?.addEventListener('change', filterReminders);
            prioritySelect?.addEventListener('change', filterReminders);
            categorySelect?.addEventListener('change', filterReminders);
            viewSelect?.addEventListener('change', filterReminders);

            // Clear filters
            clearFiltersBtn?.addEventListener('click', function() {
                searchInput.value = '';
                statusSelect.value = 'active';
                prioritySelect.value = 'all';
                categorySelect.value = 'all';
                viewSelect.value = 'list';
                filterReminders();
            });

            // Filter reminders function
            function filterReminders() {
                const formData = {
                    search: searchInput?.value || '',
                    status: statusSelect?.value || 'active',
                    priority: prioritySelect?.value || 'all',
                    category: categorySelect?.value || 'all',
                    view: viewSelect?.value || 'list'
                };

                const params = new URLSearchParams(formData);

                showLoading();

                fetch(`{{ route('reminders.index') }}?${params}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.html) {
                            document.getElementById('content-area').innerHTML = data.html;
                        } else if (Array.isArray(data)) {
                            // Handle calendar view
                            updateCalendar(data);
                        }

                        // Update URL without page reload
                        const newUrl = `{{ route('reminders.index') }}?${params}`;
                        history.pushState(null, '', newUrl);
                    })
                    .catch(error => {
                        console.error('Error filtering reminders:', error);
                        showNotification('Error loading reminders. Please try again.', 'error');
                    })
                    .finally(() => {
                        hideLoading();
                    });
            }

            // Show loading overlay
            function showLoading() {
                if (loadingOverlay) {
                    loadingOverlay.style.display = 'flex';
                }
            }

            // Hide loading overlay
            function hideLoading() {
                if (loadingOverlay) {
                    loadingOverlay.style.display = 'none';
                }
            }

            // Show notification
            function showNotification(message, type = 'success') {
                const alertClass = type === 'error' ? 'alert-danger' : 'alert-success';
                const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 1050; max-width: 400px;">
                <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

                document.body.insertAdjacentHTML('beforeend', alertHtml);

                // Auto-dismiss after 5 seconds
                setTimeout(() => {
                    const alerts = document.querySelectorAll('.alert');
                    const lastAlert = alerts[alerts.length - 1];
                    if (lastAlert && lastAlert.classList.contains('show')) {
                        lastAlert.remove();
                    }
                }, 5000);
            }

            // Global functions for reminder actions
            window.toggleComplete = function(reminderId) {
                fetch(`/reminders/${reminderId}/toggle-complete`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            filterReminders();
                            const status = data.is_completed ? 'completed' : 'reactivated';
                            showNotification(`Reminder ${status} successfully!`);
                        } else {
                            throw new Error('Server returned unsuccessful response');
                        }
                    })
                    .catch(error => {
                        console.error('Error toggling reminder:', error);
                        showNotification('Error updating reminder. Please try again.', 'error');
                    });
            };

            window.snoozeReminder = function(reminderId) {
                // Create a modern modal-like prompt
                const minutes = prompt('Snooze for how many minutes?', '15');

                if (minutes && !isNaN(minutes) && parseInt(minutes) > 0) {
                    fetch(`/reminders/${reminderId}/snooze`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                minutes: parseInt(minutes)
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                filterReminders();
                                showNotification(`Reminder snoozed until ${data.snooze_until}`);
                            } else {
                                throw new Error('Server returned unsuccessful response');
                            }
                        })
                        .catch(error => {
                            console.error('Error snoozing reminder:', error);
                            showNotification('Error snoozing reminder. Please try again.', 'error');
                        });
                }
            };

            // Handle browser back/forward buttons
            window.addEventListener('popstate', function(e) {
                location.reload();
            });
        });
    </script>
@endpush
