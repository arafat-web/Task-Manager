<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Task Manager</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/logo-circle.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #f0f0ff;
            background-image:
                radial-gradient(ellipse 80% 60% at 20% 10%, rgba(99,102,241,.18) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 90%, rgba(139,92,246,.14) 0%, transparent 55%);
        }

        .lc-wrap {
            width: 100%;
            max-width: 420px;
        }

        /* ── Card ── */
        .lc-card {
            background: #ffffff;
            border: 1px solid #e3e4e8;
            border-radius: 16px;
            overflow: hidden;
            box-shadow:
                0 4px 6px -1px rgba(0,0,0,.07),
                0 20px 40px -10px rgba(99,102,241,.18);
        }

        /* ── Header ── */
        .lc-header {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            padding: 28px 28px 26px;
            position: relative;
            overflow: hidden;
            text-align: center;
        }
        .lc-header::before {
            content: '';
            position: absolute;
            top: -30px; right: -30px;
            width: 100px; height: 100px;
            background: rgba(255,255,255,.08);
            border-radius: 50%;
        }
        .lc-header::after {
            content: '';
            position: absolute;
            bottom: -20px; left: -20px;
            width: 70px; height: 70px;
            background: rgba(255,255,255,.06);
            border-radius: 50%;
        }
        .lc-logo {
            position: relative; z-index: 1;
            width: 56px; height: 56px;
            background: rgba(255,255,255,.18);
            border: 1.5px solid rgba(255,255,255,.35);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
            font-size: 1.6rem; color: #fff;
        }
        .lc-title {
            position: relative; z-index: 1;
            font-size: 1.35rem; font-weight: 800; color: #fff;
            letter-spacing: -.3px; margin-bottom: 4px;
        }
        .lc-sub {
            position: relative; z-index: 1;
            font-size: .78rem; color: rgba(255,255,255,.78);
        }

        /* ── Body ── */
        .lc-body { padding: 26px 28px 20px; }

        /* Alert */
        .lc-alert {
            background: #fee2e2; border: 1px solid #fca5a5;
            border-radius: 8px; padding: 10px 14px; margin-bottom: 18px;
            font-size: 12px; color: #991b1b;
            display: flex; align-items: center; gap: 7px;
        }

        /* Field */
        .lc-field { margin-bottom: 15px; }
        .lc-label {
            display: block; font-size: 12px; font-weight: 600;
            color: #374151; margin-bottom: 5px;
        }
        .lc-input-wrap { position: relative; }
        .lc-input-icon {
            position: absolute; left: 10px; top: 50%;
            transform: translateY(-50%);
            font-size: 14px; color: #9ca3af; pointer-events: none;
        }
        .lc-input {
            width: 100%;
            border: 1.5px solid #d3d5db;
            border-radius: 8px;
            padding: 9px 36px;
            font-size: 13px; color: #111827;
            background: #fcfcfd;
            transition: border-color .15s, box-shadow .15s;
            outline: none;
            font-family: inherit;
        }
        .lc-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,.15);
            background: #fff;
        }
        .lc-input.is-invalid { border-color: #dc2626; }
        .lc-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(220,38,38,.12); }
        .lc-pw-toggle {
            position: absolute; right: 9px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: #9ca3af; font-size: 14px; padding: 0;
            transition: color .15s;
        }
        .lc-pw-toggle:hover { color: #6366f1; }
        .lc-err {
            font-size: 11px; color: #dc2626; margin-top: 4px;
            display: flex; align-items: center; gap: 4px;
        }

        /* Remember row */
        .lc-check-row {
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 18px;
        }
        .lc-check {
            width: 15px; height: 15px; cursor: pointer;
            border-radius: 4px; border: 1.5px solid #d3d5db;
            accent-color: #6366f1;
        }
        .lc-check-lbl { font-size: 12px; color: #6b7280; cursor: pointer; }

        /* Submit */
        .lc-btn {
            width: 100%;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border: none; border-radius: 8px;
            padding: 10px 20px;
            font-size: 13px; font-weight: 700; color: #fff;
            cursor: pointer; font-family: inherit;
            display: flex; align-items: center; justify-content: center; gap: 7px;
            transition: opacity .15s, box-shadow .15s, transform .12s;
            box-shadow: 0 4px 14px rgba(99,102,241,.35);
        }
        .lc-btn:hover {
            opacity: .92;
            box-shadow: 0 6px 20px rgba(99,102,241,.45);
            transform: translateY(-1px);
        }
        .lc-btn:active { transform: translateY(0); }

        /* Footer */
        .lc-footer {
            padding: 14px 28px;
            background: #fafafa;
            border-top: 1px solid #f0f0f3;
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
        }
        .lc-footer a { color: #6366f1; text-decoration: none; font-weight: 600; }
        .lc-footer a:hover { text-decoration: underline; }

        /* App tag above card */
        .lc-app-tag {
            text-align: center;
            margin-bottom: 16px;
            font-size: 11px; font-weight: 700; letter-spacing: .8px;
            text-transform: uppercase; color: #6366f1; opacity: .7;
        }
    </style>
</head>
<body>

<div class="lc-wrap">
    <div class="lc-app-tag"><i class="bi bi-check2-square"></i> &nbsp;Task Manager</div>

    <div class="lc-card">
        <div class="lc-header">
            <div class="lc-logo"><i class="bi bi-person-check"></i></div>
            <h1 class="lc-title">Welcome back</h1>
            <p class="lc-sub">Sign in to your account to continue</p>
        </div>

        <div class="lc-body">
            @if($errors->has('email') && $errors->first('email') === 'These credentials do not match our records.')
            <div class="lc-alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                Invalid email or password. Please try again.
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="lc-field">
                    <label for="email" class="lc-label">Email Address</label>
                    <div class="lc-input-wrap">
                        <i class="bi bi-envelope lc-input-icon"></i>
                        <input type="email" id="email" name="email"
                               class="lc-input @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="you@example.com"
                               required autofocus autocomplete="email">
                    </div>
                    @error('email')
                        <p class="lc-err"><i class="bi bi-exclamation-circle"></i>{{ $message }}</p>
                    @enderror
                </div>

                <div class="lc-field">
                    <label for="password" class="lc-label">Password</label>
                    <div class="lc-input-wrap">
                        <i class="bi bi-lock lc-input-icon"></i>
                        <input type="password" id="password" name="password"
                               class="lc-input @error('password') is-invalid @enderror"
                               placeholder="••••••••"
                               required autocomplete="current-password">
                        <button type="button" class="lc-pw-toggle" id="pw-toggle" aria-label="Toggle password">
                            <i class="bi bi-eye" id="pw-icon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="lc-err"><i class="bi bi-exclamation-circle"></i>{{ $message }}</p>
                    @enderror
                </div>

                <div class="lc-check-row">
                    <input type="checkbox" id="remember" name="remember" class="lc-check"
                           {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" class="lc-check-lbl">Remember me for 30 days</label>
                </div>

                <button type="submit" class="lc-btn">
                    <i class="bi bi-arrow-right-circle"></i> Sign In
                </button>
            </form>
        </div>

        <div class="lc-footer">
            Developed with <i class="bi bi-heart-fill" style="color:#e53e3e;font-size:10px;"></i> by
            <a href="https://github.com/arafat-web" target="_blank">Arafat Hossain</a>
        </div>
    </div>
</div>

<script>
document.getElementById('pw-toggle').addEventListener('click', function () {
    var inp = document.getElementById('password');
    var ico = document.getElementById('pw-icon');
    inp.type = inp.type === 'password' ? 'text' : 'password';
    ico.className = inp.type === 'text' ? 'bi bi-eye-slash' : 'bi bi-eye';
});
</script>
</body>
</html>
