<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - Task Manager</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/logo-circle.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <style>
        :root {
            /* Modern Color System */
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

            --gradient-primary: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
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

        .error-container {
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        .error-card {
            background: linear-gradient(135deg, #ffffff 0%, var(--gray-50) 100%);
            border: 1px solid var(--gray-200);
            border-radius: 24px;
            padding: 3rem 2rem;
            box-shadow:
                0 20px 25px -5px rgba(0, 0, 0, 0.1),
                0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .error-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-primary);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: white;
            font-size: 2rem;
        }

        .error-code {
            font-size: 4rem;
            font-weight: 700;
            color: var(--gray-800);
            margin: 0 0 1rem 0;
            line-height: 1;
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-700);
            margin: 0 0 1rem 0;
        }

        .error-message {
            font-size: 1rem;
            color: var(--gray-500);
            margin: 0 0 2rem 0;
            line-height: 1.6;
        }

        .btn-home {
            background: var(--gradient-primary);
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            font-size: 0.875rem;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .btn-home:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-home:active {
            transform: translateY(0);
        }

        @media (max-width: 480px) {
            .error-card {
                padding: 2rem 1rem;
            }

            .error-code {
                font-size: 3rem;
            }

            .error-title {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-card">
            <div class="error-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>

            <h1 class="error-code">404</h1>
            <h2 class="error-title">Page Not Found</h2>
            <p class="error-message">
                Sorry, we couldn't find the page you're looking for.
                The page might have been moved, deleted, or the URL might be incorrect.
            </p>

            <a href="{{ url('/') }}" class="btn-home">
                <i class="bi bi-house"></i>
                Back to Dashboard
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
