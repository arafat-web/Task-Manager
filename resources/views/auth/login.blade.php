<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Task Manager</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/logo-circle.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <style>
        :root {
            /* Modern Color System - Consistent with dashboard */
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

            /* Gradients */
            --gradient-primary: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
            --gradient-surface: linear-gradient(135deg, #ffffff 0%, var(--gray-50) 100%);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--primary-50) 0%, var(--gray-100) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 1rem;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
        }

        .login-card {
            background: var(--gradient-surface);
            border: 1px solid var(--gray-200);
            border-radius: 24px;
            box-shadow:
                0 20px 25px -5px rgba(0, 0, 0, 0.1),
                0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .login-header {
            background: var(--gradient-primary);
            color: white;
            text-align: center;
            padding: 2rem 1.5rem;
            position: relative;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='7' cy='7' r='1'/%3E%3Ccircle cx='53' cy='7' r='1'/%3E%3Ccircle cx='7' cy='53' r='1'/%3E%3Ccircle cx='53' cy='53' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            opacity: 0.1;
        }

        .login-logo {
            position: relative;
            z-index: 1;
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .login-title {
            position: relative;
            z-index: 1;
            font-size: 1.75rem;
            font-weight: 600;
            margin: 0 0 0.5rem 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .login-subtitle {
            position: relative;
            z-index: 1;
            opacity: 0.9;
            font-size: 0.875rem;
            margin: 0;
        }

        .login-body {
            padding: 2rem 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control {
            background: var(--gray-25);
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            color: var(--gray-800);
        }

        .form-control:focus {
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            background: white;
            transform: translateY(-1px);
        }

        .form-control::placeholder {
            color: var(--gray-400);
            transition: opacity 0.2s ease;
        }

        .form-control:focus::placeholder {
            opacity: 0.7;
        }

        .form-control.is-invalid {
            border-color: var(--error-500);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .form-check {
            margin-bottom: 1.5rem;
        }

        .form-check-input {
            margin-top: 0.125rem;
            border-radius: 6px;
            border: 2px solid var(--gray-300);
        }

        .form-check-input:checked {
            background-color: var(--primary-500);
            border-color: var(--primary-500);
        }

        .form-check-label {
            font-size: 0.875rem;
            color: var(--gray-600);
            margin-left: 0.5rem;
        }

        .btn-login {
            background: var(--gradient-primary);
            border: none;
            border-radius: 12px;
            padding: 0.875rem 1.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            color: white;
            width: 100%;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            padding: 1.5rem;
            text-align: center;
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
        }

        .login-footer p {
            margin: 0;
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        .login-footer a {
            color: var(--primary-600);
            text-decoration: none;
            font-weight: 500;
        }

        .login-footer a:hover {
            color: var(--primary-700);
            text-decoration: underline;
        }

        .error-message {
            font-size: 0.75rem;
            color: var(--error-500);
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .login-header {
                padding: 1.5rem 1rem;
            }

            .login-title {
                font-size: 1.5rem;
            }

            .login-body {
                padding: 1.5rem 1rem;
            }

            .login-footer {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Header Section -->
            <div class="login-header">
                <div class="login-logo">
                    <i class="bi bi-check2-square" style="font-size: 1.5rem;"></i>
                </div>
                <h1 class="login-title">Welcome Back</h1>
                <p class="login-subtitle">Sign in to your Task Manager account</p>
            </div>

            <!-- Login Form -->
            <div class="login-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1"></i>
                            Email Address
                        </label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Enter your email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                        @error('email')
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock me-1"></i>
                            Password
                        </label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Enter your password"
                            required
                        >
                        @error('password')
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-check">
                        <input
                            type="checkbox"
                            name="remember"
                            id="remember"
                            class="form-check-input"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <label for="remember" class="form-check-label">
                            Remember me for 30 days
                        </label>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="bi bi-arrow-right-circle me-2"></i>
                        Sign In
                    </button>
                </form>
            </div>

            <!-- Footer Section -->
            <div class="login-footer">
                <p>
                    Developed with <i class="bi bi-heart-fill text-danger"></i> by
                    <a href="https://github.com/arafat-web" target="_blank">Arafat Hossain</a>
                </p>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
