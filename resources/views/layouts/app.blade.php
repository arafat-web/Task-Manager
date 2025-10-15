<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> @yield('title') | Task Manager </title>
    <link rel="shortcut icon" href="{{ asset('assets/img/logo-circle.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('styles')

    <style>
        :root {
            --primary-50: #f0f4ff;
            --primary-100: #e0edff;
            --primary-500: #6366f1;
            --primary-600: #4f46e5;
            --primary-700: #4338ca;
            --primary-900: #312e81;

            --gray-25: #fcfcfd;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;

            --success-500: #10b981;
            --success-600: #059669;
            --warning-500: #f59e0b;
            --warning-600: #d97706;
            --error-500: #ef4444;
            --error-600: #dc2626;

            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);

            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
        }

        * {
            box-sizing: border-box;
        }

        body {
            display: flex;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            background-color: var(--gray-25);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: var(--gray-700);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: var(--radius-md);
            border: 1px solid transparent;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--primary-600);
            color: white;
            border-color: var(--primary-600);
        }

        .btn-primary:hover {
            background-color: var(--primary-700);
            border-color: var(--primary-700);
            color: white;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-success {
            background-color: var(--success-600);
            color: white;
            border-color: var(--success-600);
        }

        .btn-success:hover {
            background-color: var(--success-600);
            border-color: var(--success-600);
            color: white;
        }

        .btn-warning {
            background-color: var(--warning-500);
            color: white;
            border-color: var(--warning-500);
        }

        .btn-warning:hover {
            background-color: var(--warning-600);
            border-color: var(--warning-600);
            color: white;
        }

        .btn-danger {
            background-color: var(--error-500);
            color: white;
            border-color: var(--error-500);
        }

        .btn-danger:hover {
            background-color: var(--error-600);
            border-color: var(--error-600);
            color: white;
        }

        .btn-outline {
            background-color: white;
            color: var(--gray-700);
            border-color: var(--gray-200);
        }

        .btn-outline:hover {
            background-color: var(--gray-50);
            border-color: var(--gray-300);
            color: var(--gray-800);
        }

        .sidebar {
            width: 280px;
            background-color: white;
            color: var(--gray-700);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--gray-200);
            position: relative;
        }

        .sidebar-header {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
            background-color: white;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--gray-900);
            font-weight: 600;
            font-size: 1.125rem;
        }

        .sidebar-brand img {
            height: 32px;
            width: auto;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
        }

        .nav-section {
            padding: 0 1.25rem;
            margin-bottom: 1.5rem;
        }

        .nav-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-500);
            margin-bottom: 0.75rem;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            margin: 0 1.25rem;
            color: var(--gray-600);
            text-decoration: none;
            border-radius: var(--radius-md);
            font-weight: 500;
            transition: all 0.15s ease;
            position: relative;
        }

        .nav-link:hover {
            background-color: var(--gray-100);
            color: var(--gray-800);
        }

        .nav-link.active {
            background-color: var(--primary-50);
            color: var(--primary-700);
            font-weight: 600;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: -1.25rem;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 20px;
            background-color: var(--primary-600);
            border-radius: 0 2px 2px 0;
        }

        .nav-link i {
            font-size: 1.125rem;
            width: 20px;
            text-align: center;
        }

        .nav-badge {
            margin-left: auto;
            background-color: var(--gray-200);
            color: var(--gray-600);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.125rem 0.5rem;
            border-radius: 12px;
            min-width: 20px;
            text-align: center;
        }

        .nav-link.active .nav-badge {
            background-color: var(--primary-100);
            color: var(--primary-700);
        }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--gray-200);
            background-color: white;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            border-radius: var(--radius-md);
            transition: background-color 0.15s ease;
            cursor: pointer;
        }

        .user-profile:hover {
            background-color: var(--gray-50);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-weight: 500;
            font-size: 0.875rem;
            color: var(--gray-900);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-email {
            font-size: 0.75rem;
            color: var(--gray-500);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background-color: var(--gray-25);
        }

        .topnav {
            flex-shrink: 0;
            background-color: white;
            border-bottom: 1px solid var(--gray-200);
            padding: 1rem 1.5rem;
        }

        .topnav-container {
            display: flex;
            align-items: center;
            justify-content: between;
            max-width: 100%;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0;
        }

        .topnav-actions {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .current-time {
            font-size: 0.875rem;
            color: var(--gray-500);
            font-weight: 500;
        }

        .dropdown-menu {
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            padding: 0.5rem;
            min-width: 200px;
        }

        .dropdown-item {
            padding: 0.5rem 0.75rem;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            color: var(--gray-700);
            transition: background-color 0.15s ease;
        }

        .dropdown-item:hover {
            background-color: var(--gray-100);
            color: var(--gray-800);
        }

        main {
            flex-grow: 1;
            overflow-y: auto;
            padding: 1.5rem;
        }

        .card {
            background-color: white;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: box-shadow 0.15s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            background-color: var(--gray-50);
            font-weight: 600;
            color: var(--gray-900);
        }

        footer {
            background-color: white;
            border-top: 1px solid var(--gray-200);
            flex-shrink: 0;
            padding: 1rem 1.5rem;
            text-align: center;
        }

        .footer-text {
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        .footer-text a {
            color: var(--primary-600);
            text-decoration: none;
        }

        .footer-text a:hover {
            color: var(--primary-700);
        }

        /* Form Controls */
        .form-control {
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-md);
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: all 0.15s ease;
        }

        .form-control:focus {
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgb(99 102 241 / 0.1);
            outline: none;
        }

        .form-label {
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        /* Alert Styles */
        .alert {
            border-radius: var(--radius-md);
            border: 1px solid;
            padding: 1rem;
            font-size: 0.875rem;
        }

        .alert-success {
            background-color: #f0fdf4;
            border-color: #bbf7d0;
            color: #166534;
        }

        .alert-danger {
            background-color: #fef2f2;
            border-color: #fecaca;
            color: #dc2626;
        }

        .alert-warning {
            background-color: #fffbeb;
            border-color: #fed7aa;
            color: #d97706;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                height: 100vh;
                z-index: 1000;
                transition: left 0.3s ease;
            }

            .sidebar.open {
                left: 0;
            }

            .content {
                margin-left: 0;
            }

            main {
                padding: 1rem;
            }

            .topnav {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="sidebar-brand">
                <img src="{{ asset('assets/img/logo-circle.png') }}" alt="TaskManager">
                TaskManager
            </a>
        </div>

        <div class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-house-door-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('projects*') ? 'active' : '' }}"
                            href="{{ route('projects.index') }}">
                            <i class="bi bi-folder-fill"></i>
                            <span>Projects</span>
                            <span
                                class="nav-badge">{{ \App\Models\Project::where('user_id', auth()->id())->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        @php
                            $hasProjects = \App\Models\Project::where('user_id', auth()->id())->exists();
                            $taskCount = $hasProjects ? \App\Models\Task::where('user_id', auth()->id())->whereHas('project', function ($q) {$q->where('status', '!=', 'completed');})->where('status', '!=', 'completed')->count() : 0;
                        @endphp
                        <a class="nav-link {{ request()->is('tasks*') ? 'active' : '' }}"
                            href="{{ $hasProjects ? route('tasks.index') : route('projects.index') . '?message=create_project_first' }}">
                            <i class="bi bi-check-square-fill"></i>
                            <span>Tasks</span>
                            @if($hasProjects)
                                <span class="nav-badge">{{ $taskCount }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Organize</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('routines*') ? 'active' : '' }}"
                            href="{{ route('routines.index') }}">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Routines</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('notes*') ? 'active' : '' }}"
                            href="{{ route('notes.index') }}">
                            <i class="bi bi-journal-text"></i>
                            <span>Notes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('reminders*') ? 'active' : '' }}"
                            href="{{ route('reminders.index') }}">
                            <i class="bi bi-bell-fill"></i>
                            <span>Reminders</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('files*') ? 'active' : '' }}"
                            href="{{ route('files.index') }}">
                            <i class="bi bi-file-earmark-fill"></i>
                            <span>Files</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="sidebar-footer">
            <div class="user-profile" data-bs-toggle="dropdown" aria-expanded="false">
                @if(Auth::user()->avatar)
                    <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="user-avatar" style="object-fit: cover;">
                @else
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-email">{{ Auth::user()->email }}</div>
                </div>
                <i class="bi bi-three-dots"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                <li><a class="dropdown-item" href="{{ route('profile.password') }}"><i class="bi bi-key me-2"></i>Change Password</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
    <div class="content">
        <header class="topnav">
            <div class="topnav-container">
                {{-- <h1 class="page-title">@yield('page-title')</h1> --}}
                <div class="topnav-actions">
                    <span class="current-time" id="currentDateTime"></span>
                    <div class="dropdown">
                        <button class="btn btn-outline dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-plus-lg"></i>
                            <span class="d-none d-md-inline">Quick Add</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('projects.create') }}"><i
                                        class="bi bi-folder-plus me-2"></i>New Project</a></li>
                            <li><a class="dropdown-item" href="{{ route('notes.create') }}"><i
                                        class="bi bi-journal-plus me-2"></i>New Note</a></li>
                            <li><a class="dropdown-item" href="{{ route('reminders.create') }}"><i
                                        class="bi bi-bell me-2"></i>New Reminder</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="container-fluid px-4 mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container-fluid px-4 mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="container-fluid px-4 mt-3">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="container-fluid px-4 mt-3">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <main>
            @yield('content')
        </main>
        <footer>
            <div class="footer-text">
                &copy; {{ date('Y') }} TaskManager | Crafted with ❤️ by <a href="https://github.com/arafat-web"
                    target="_blank">Arafat Hossain Ar</a>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update current time
        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'short',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            document.getElementById('currentDateTime').innerText = now.toLocaleDateString('en-US', options);
        }

        updateDateTime();
        setInterval(updateDateTime, 1000); // Update every second

        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('open');
        }

        // Add mobile menu button for responsive design
        if (window.innerWidth <= 768) {
            const topnav = document.querySelector('.topnav-container');
            const menuBtn = document.createElement('button');
            menuBtn.className = 'btn btn-outline d-md-none';
            menuBtn.innerHTML = '<i class="bi bi-list"></i>';
            menuBtn.onclick = toggleSidebar;
            topnav.insertBefore(menuBtn, topnav.firstChild);
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isMenuButton = event.target.closest('.btn') && event.target.querySelector('.bi-list');

            if (!isClickInsideSidebar && !isMenuButton && window.innerWidth <= 768) {
                sidebar.classList.remove('open');
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
