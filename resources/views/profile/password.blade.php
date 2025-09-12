@extends('layouts.app')

@section('title', 'Change Password')

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

    .password-header {
        background: linear-gradient(135deg, var(--profile-danger) 0%, #dc2626 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px var(--profile-shadow-lg);
    }

    .form-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--profile-border);
        box-shadow: 0 4px 6px -1px var(--profile-shadow);
        overflow: hidden;
        max-width: 600px;
        margin: 0 auto;
    }

    .form-card-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--profile-border);
        background: var(--profile-light);
    }

    .form-card-body {
        padding: 2rem;
    }

    .breadcrumb-modern {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px var(--profile-shadow);
        border: 1px solid var(--profile-border);
    }

    .breadcrumb-modern .breadcrumb {
        margin: 0;
        background: none;
        padding: 0;
    }

    .breadcrumb-modern .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: var(--profile-gray);
        font-weight: 600;
    }

    .security-notice {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .security-notice h6 {
        color: var(--profile-danger);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .security-notice ul {
        margin: 0;
        color: var(--profile-gray);
        font-size: 0.875rem;
    }

    .form-group-modern {
        margin-bottom: 1.5rem;
        position: relative;
    }

    .form-label-modern {
        font-weight: 600;
        color: var(--profile-dark);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .password-input-wrapper {
        position: relative;
    }

    .form-control-modern {
        border: 1px solid var(--profile-border);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        padding-right: 3rem;
        transition: all 0.2s ease;
        background: white;
        width: 100%;
    }

    .form-control-modern:focus {
        border-color: var(--profile-primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        outline: none;
    }

    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--profile-gray);
        cursor: pointer;
        font-size: 1rem;
    }

    .password-toggle:hover {
        color: var(--profile-primary);
    }

    .password-strength {
        margin-top: 0.5rem;
    }

    .strength-meter {
        height: 4px;
        background: var(--profile-border);
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    .strength-fill {
        height: 100%;
        transition: all 0.3s ease;
        border-radius: 2px;
    }

    .strength-weak { background: var(--profile-danger); width: 25%; }
    .strength-fair { background: var(--profile-warning); width: 50%; }
    .strength-good { background: var(--profile-info); width: 75%; }
    .strength-strong { background: var(--profile-success); width: 100%; }

    .strength-text {
        font-size: 0.75rem;
        font-weight: 600;
    }

    .strength-weak-text { color: var(--profile-danger); }
    .strength-fair-text { color: var(--profile-warning); }
    .strength-good-text { color: var(--profile-info); }
    .strength-strong-text { color: var(--profile-success); }

    .password-requirements {
        margin-top: 0.5rem;
        font-size: 0.75rem;
    }

    .requirement {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.25rem;
        color: var(--profile-gray);
    }

    .requirement.met {
        color: var(--profile-success);
    }

    .requirement i {
        font-size: 0.75rem;
    }

    .action-buttons {
        background: var(--profile-light);
        padding: 1.5rem 2rem;
        border-top: 1px solid var(--profile-border);
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
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
        background: var(--profile-danger);
        color: white;
    }

    .btn-modern.btn-primary:hover {
        background: #dc2626;
        transform: translateY(-1px);
        color: white;
    }

    .btn-modern.btn-secondary {
        background: var(--profile-gray);
        color: white;
    }

    .btn-modern.btn-secondary:hover {
        background: var(--profile-dark);
        transform: translateY(-1px);
        color: white;
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="password-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-2">
                    <i class="fas fa-key me-3"></i>Change Password
                </h1>
                <p class="mb-0 opacity-75">Update your account password for enhanced security</p>
            </div>
            <a href="{{ route('profile.show') }}" class="btn btn-light btn-lg">
                <i class="fas fa-arrow-left me-2"></i>Back to Profile
            </a>
        </div>
    </div>

    <!-- Breadcrumb -->
    <nav class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('profile.show') }}" style="color: var(--profile-primary);">Profile</a></li>
            <li class="breadcrumb-item active">Change Password</li>
        </ol>
    </nav>

    <!-- Security Notice -->
    <div class="security-notice">
        <h6>
            <i class="fas fa-shield-alt"></i>
            Password Security Guidelines
        </h6>
        <ul class="mb-0">
            <li>Use a combination of uppercase and lowercase letters</li>
            <li>Include at least one number and one special character</li>
            <li>Make it at least 8 characters long</li>
            <li>Avoid using personal information or common words</li>
            <li>Don't reuse passwords from other accounts</li>
        </ul>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <form action="{{ route('profile.password.update') }}" method="POST" id="password-form">
                @csrf
                @method('PUT')

                <div class="form-card">
                    <div class="form-card-header">
                        <h5 class="mb-0" style="color: var(--profile-dark);">
                            <i class="fas fa-lock me-2" style="color: var(--profile-danger);"></i>Password Update
                        </h5>
                    </div>

                    <div class="form-card-body">
                        <!-- Current Password -->
                        <div class="form-group-modern">
                            <label for="current_password" class="form-label-modern">
                                <i class="fas fa-unlock" style="color: var(--profile-primary);"></i>
                                Current Password
                                <span style="color: var(--profile-danger);">*</span>
                            </label>
                            <div class="password-input-wrapper">
                                <input type="password"
                                       id="current_password"
                                       name="current_password"
                                       class="form-control-modern @error('current_password') is-invalid @enderror"
                                       placeholder="Enter your current password..."
                                       required>
                                <button type="button" class="password-toggle" data-target="current_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="form-group-modern">
                            <label for="password" class="form-label-modern">
                                <i class="fas fa-lock" style="color: var(--profile-primary);"></i>
                                New Password
                                <span style="color: var(--profile-danger);">*</span>
                            </label>
                            <div class="password-input-wrapper">
                                <input type="password"
                                       id="password"
                                       name="password"
                                       class="form-control-modern @error('password') is-invalid @enderror"
                                       placeholder="Enter your new password..."
                                       required>
                                <button type="button" class="password-toggle" data-target="password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>

                            <!-- Password Strength Meter -->
                            <div class="password-strength">
                                <div class="strength-meter">
                                    <div class="strength-fill" id="strength-fill"></div>
                                </div>
                                <div class="strength-text" id="strength-text">Enter a password to check strength</div>
                            </div>

                            <!-- Password Requirements -->
                            <div class="password-requirements">
                                <div class="requirement" id="req-length">
                                    <i class="fas fa-circle"></i>
                                    At least 8 characters
                                </div>
                                <div class="requirement" id="req-uppercase">
                                    <i class="fas fa-circle"></i>
                                    One uppercase letter
                                </div>
                                <div class="requirement" id="req-lowercase">
                                    <i class="fas fa-circle"></i>
                                    One lowercase letter
                                </div>
                                <div class="requirement" id="req-number">
                                    <i class="fas fa-circle"></i>
                                    One number
                                </div>
                                <div class="requirement" id="req-special">
                                    <i class="fas fa-circle"></i>
                                    One special character
                                </div>
                            </div>

                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group-modern">
                            <label for="password_confirmation" class="form-label-modern">
                                <i class="fas fa-check-double" style="color: var(--profile-primary);"></i>
                                Confirm New Password
                                <span style="color: var(--profile-danger);">*</span>
                            </label>
                            <div class="password-input-wrapper">
                                <input type="password"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       class="form-control-modern"
                                       placeholder="Confirm your new password..."
                                       required>
                                <button type="button" class="password-toggle" data-target="password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="mt-2" id="password-match">
                                <small class="text-muted">Passwords must match</small>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="{{ route('profile.show') }}" class="btn-modern btn-secondary">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn-modern btn-primary" id="submit-btn">
                            <i class="fas fa-key"></i>
                            Update Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password toggle functionality
    document.querySelectorAll('.password-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        });
    });

    // Password strength checker
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const strengthFill = document.getElementById('strength-fill');
    const strengthText = document.getElementById('strength-text');
    const matchDiv = document.getElementById('password-match');
    const submitBtn = document.getElementById('submit-btn');

    // Requirements elements
    const reqLength = document.getElementById('req-length');
    const reqUppercase = document.getElementById('req-uppercase');
    const reqLowercase = document.getElementById('req-lowercase');
    const reqNumber = document.getElementById('req-number');
    const reqSpecial = document.getElementById('req-special');

    function checkPasswordStrength(password) {
        let score = 0;
        let strength = '';
        let strengthClass = '';

        // Check requirements
        const hasLength = password.length >= 8;
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);
        const hasNumber = /\d/.test(password);
        const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);

        // Update requirement indicators
        updateRequirement(reqLength, hasLength);
        updateRequirement(reqUppercase, hasUppercase);
        updateRequirement(reqLowercase, hasLowercase);
        updateRequirement(reqNumber, hasNumber);
        updateRequirement(reqSpecial, hasSpecial);

        // Calculate score
        if (hasLength) score++;
        if (hasUppercase) score++;
        if (hasLowercase) score++;
        if (hasNumber) score++;
        if (hasSpecial) score++;

        // Determine strength
        if (score <= 1) {
            strength = 'Weak';
            strengthClass = 'strength-weak';
        } else if (score <= 2) {
            strength = 'Fair';
            strengthClass = 'strength-fair';
        } else if (score <= 3) {
            strength = 'Good';
            strengthClass = 'strength-good';
        } else {
            strength = 'Strong';
            strengthClass = 'strength-strong';
        }

        // Update UI
        strengthFill.className = 'strength-fill ' + strengthClass;
        strengthText.className = 'strength-text ' + strengthClass + '-text';
        strengthText.textContent = password ? `Password Strength: ${strength}` : 'Enter a password to check strength';

        return score >= 3; // Consider good or strong as acceptable
    }

    function updateRequirement(element, met) {
        const icon = element.querySelector('i');
        if (met) {
            element.classList.add('met');
            icon.className = 'fas fa-check';
        } else {
            element.classList.remove('met');
            icon.className = 'fas fa-circle';
        }
    }

    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirm = confirmInput.value;

        if (confirm && password !== confirm) {
            matchDiv.innerHTML = '<small class="text-danger"><i class="fas fa-times me-1"></i>Passwords do not match</small>';
            return false;
        } else if (confirm && password === confirm) {
            matchDiv.innerHTML = '<small class="text-success"><i class="fas fa-check me-1"></i>Passwords match</small>';
            return true;
        } else {
            matchDiv.innerHTML = '<small class="text-muted">Passwords must match</small>';
            return false;
        }
    }

    // Event listeners
    passwordInput.addEventListener('input', function() {
        checkPasswordStrength(this.value);
        if (confirmInput.value) {
            checkPasswordMatch();
        }
    });

    confirmInput.addEventListener('input', function() {
        checkPasswordMatch();
    });

    // Form validation
    document.getElementById('password-form').addEventListener('submit', function(e) {
        const passwordStrong = checkPasswordStrength(passwordInput.value);
        const passwordsMatch = checkPasswordMatch();

        if (!passwordStrong) {
            e.preventDefault();
            alert('Please ensure your password meets all requirements and has good strength.');
            return false;
        }

        if (!passwordsMatch && confirmInput.value) {
            e.preventDefault();
            alert('Please ensure both passwords match.');
            return false;
        }
    });
});
</script>
@endpush
