@extends('layouts.app')

@section('title', 'Edit Profile')

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

    .edit-header {
        background: linear-gradient(135deg, var(--profile-primary) 0%, var(--profile-secondary) 100%);
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
        margin-bottom: 2rem;
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

    .avatar-upload-section {
        text-align: center;
        margin-bottom: 2rem;
        padding: 2rem;
        border: 2px dashed var(--profile-border);
        border-radius: 12px;
        transition: all 0.2s ease;
    }

    .avatar-upload-section:hover {
        border-color: var(--profile-primary);
        background: rgba(99, 102, 241, 0.02);
    }

    .current-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        margin-bottom: 1rem;
        border: 3px solid var(--profile-primary);
        object-fit: cover;
    }

    .avatar-placeholder-edit {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--profile-primary), var(--profile-secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: 700;
        margin: 0 auto 1rem;
        border: 3px solid var(--profile-primary);
    }

    .form-group-modern {
        margin-bottom: 1.5rem;
    }

    .form-label-modern {
        font-weight: 600;
        color: var(--profile-dark);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-control-modern {
        border: 1px solid var(--profile-border);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
        background: white;
        width: 100%;
    }

    .form-control-modern:focus {
        border-color: var(--profile-primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        outline: none;
    }

    .avatar-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 1rem;
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
        font-size: 0.875rem;
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

    .btn-modern.btn-secondary {
        background: var(--profile-gray);
        color: white;
    }

    .btn-modern.btn-secondary:hover {
        background: var(--profile-dark);
        transform: translateY(-1px);
        color: white;
    }

    .btn-modern.btn-danger {
        background: var(--profile-danger);
        color: white;
        font-size: 0.75rem;
        padding: 0.5rem 1rem;
    }

    .btn-modern.btn-danger:hover {
        background: #dc2626;
        color: white;
    }

    .action-buttons {
        background: var(--profile-light);
        padding: 1.5rem 2rem;
        border-top: 1px solid var(--profile-border);
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    .selected-file-info {
        background: rgba(99, 102, 241, 0.1);
        border: 1px solid var(--profile-primary);
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
        display: none;
        text-align: left;
    }

    .selected-file-info.show {
        display: block;
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="edit-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-2">
                    <i class="fas fa-user-edit me-3"></i>Edit Profile
                </h1>
                <p class="mb-0 opacity-75">Update your personal information and preferences</p>
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
            <li class="breadcrumb-item active">Edit Profile</li>
        </ol>
    </nav>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profile-form">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-4">
                <!-- Avatar Upload -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h5 class="mb-0" style="color: var(--profile-dark);">
                            <i class="fas fa-image me-2" style="color: var(--profile-primary);"></i>Profile Picture
                        </h5>
                    </div>
                    <div class="form-card-body">
                        <div class="avatar-upload-section">
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="current-avatar" id="avatar-preview">
                            @else
                                <div class="avatar-placeholder-edit" id="avatar-preview">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif

                            <h6 style="color: var(--profile-dark);">Upload New Picture</h6>
                            <p class="text-muted small mb-3">JPG, PNG or GIF. Max size 2MB.</p>

                            <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*">

                            <div class="avatar-actions">
                                <button type="button" class="btn-modern btn-primary" onclick="document.getElementById('avatar').click()">
                                    <i class="fas fa-upload"></i>Choose File
                                </button>
                                @if($user->avatar)
                                    <button type="button" class="btn-modern btn-danger" id="delete-avatar-btn">
                                        <i class="fas fa-trash"></i>Remove
                                    </button>
                                @endif
                            </div>

                            <div class="selected-file-info" id="selected-file">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-image me-2" style="color: var(--profile-primary);"></i>
                                    <span id="selected-filename"></span>
                                    <button type="button" class="btn btn-sm btn-outline-danger ms-auto" onclick="clearSelectedFile()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <!-- Personal Information -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h5 class="mb-0" style="color: var(--profile-dark);">
                            <i class="fas fa-user me-2" style="color: var(--profile-primary);"></i>Personal Information
                        </h5>
                    </div>
                    <div class="form-card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label for="name" class="form-label-modern">
                                        <i class="fas fa-user" style="color: var(--profile-primary);"></i>
                                        Full Name
                                        <span style="color: var(--profile-danger);">*</span>
                                    </label>
                                    <input type="text"
                                           id="name"
                                           name="name"
                                           class="form-control-modern @error('name') is-invalid @enderror"
                                           value="{{ old('name', $user->name) }}"
                                           placeholder="Enter your full name..."
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label for="email" class="form-label-modern">
                                        <i class="fas fa-envelope" style="color: var(--profile-primary);"></i>
                                        Email Address
                                        <span style="color: var(--profile-danger);">*</span>
                                    </label>
                                    <input type="email"
                                           id="email"
                                           name="email"
                                           class="form-control-modern @error('email') is-invalid @enderror"
                                           value="{{ old('email', $user->email) }}"
                                           placeholder="Enter your email address..."
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label for="phone" class="form-label-modern">
                                        <i class="fas fa-phone" style="color: var(--profile-primary);"></i>
                                        Phone Number
                                    </label>
                                    <input type="tel"
                                           id="phone"
                                           name="phone"
                                           class="form-control-modern @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $user->phone) }}"
                                           placeholder="Enter your phone number...">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label for="location" class="form-label-modern">
                                        <i class="fas fa-map-marker-alt" style="color: var(--profile-primary);"></i>
                                        Location
                                    </label>
                                    <input type="text"
                                           id="location"
                                           name="location"
                                           class="form-control-modern @error('location') is-invalid @enderror"
                                           value="{{ old('location', $user->location) }}"
                                           placeholder="Enter your location...">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <label for="website" class="form-label-modern">
                                <i class="fas fa-globe" style="color: var(--profile-primary);"></i>
                                Website
                            </label>
                            <input type="url"
                                   id="website"
                                   name="website"
                                   class="form-control-modern @error('website') is-invalid @enderror"
                                   value="{{ old('website', $user->website) }}"
                                   placeholder="https://example.com">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group-modern">
                            <label for="bio" class="form-label-modern">
                                <i class="fas fa-user-edit" style="color: var(--profile-primary);"></i>
                                Bio
                            </label>
                            <textarea id="bio"
                                      name="bio"
                                      class="form-control-modern @error('bio') is-invalid @enderror"
                                      rows="4"
                                      placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                            <small class="text-muted">Maximum 500 characters</small>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="{{ route('profile.show') }}" class="btn-modern btn-secondary">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn-modern btn-primary">
                            <i class="fas fa-save"></i>
                            Update Profile
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatar-preview');
    const selectedFile = document.getElementById('selected-file');
    const selectedFilename = document.getElementById('selected-filename');
    const deleteAvatarBtn = document.getElementById('delete-avatar-btn');

    // Avatar file selection
    avatarInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const reader = new FileReader();

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                this.value = '';
                return;
            }

            // Validate file type
            if (!file.type.match('image.*')) {
                alert('Please select an image file');
                this.value = '';
                return;
            }

            reader.onload = function(e) {
                if (avatarPreview.tagName === 'IMG') {
                    avatarPreview.src = e.target.result;
                } else {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'current-avatar';
                    img.id = 'avatar-preview';
                    avatarPreview.parentNode.replaceChild(img, avatarPreview);
                }
            };

            reader.readAsDataURL(file);
            selectedFilename.textContent = file.name;
            selectedFile.classList.add('show');
        }
    });

    // Delete avatar functionality
    if (deleteAvatarBtn) {
        deleteAvatarBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove your profile picture?')) {
                fetch('{{ route("profile.avatar.delete") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    }

    // Clear selected file
    window.clearSelectedFile = function() {
        avatarInput.value = '';
        selectedFile.classList.remove('show');
    };
});
</script>
@endpush
