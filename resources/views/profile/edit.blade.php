@extends('layouts.app')
@section('title', 'Edit Profile')
@push('styles')
<style>

.main-content { padding: 14px 16px; background: #f7f8fa; min-height: 100vh; }

.cu-header {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    border-radius: 8px; padding: 12px 16px; margin-bottom: 14px;
    position: relative; overflow: hidden;
}
.cu-header::before {
    content: ''; position: absolute; top: -20px; right: -20px;
    width: 80px; height: 80px; background: rgba(255,255,255,.08); border-radius: 50%;
}
.cu-header-inner { display: flex; align-items: center; justify-content: space-between; position: relative; z-index: 1; }
.cu-header-title { font-weight: 700; font-size: 17px; margin: 0; color: #fff; }
.cu-header-sub   { font-size: 12px; opacity: .8; margin: 2px 0 0; color: #fff; }
.cu-btn-hdr {
    background: rgba(255,255,255,.18); border: 1.5px solid rgba(255,255,255,.4);
    color: #fff; font-size: 12px; font-weight: 700; border-radius: 6px;
    padding: 6px 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
    transition: background .15s;
}
.cu-btn-hdr:hover { background: rgba(255,255,255,.28); color: #fff; }

.cu-layout { display: grid; grid-template-columns: 220px 1fr; gap: 14px; align-items: start; }
@media(max-width:768px) { .cu-layout { grid-template-columns: 1fr; } }

.cu-info-panel {
    background: white; border: 1px solid #e3e4e8; border-radius: 8px;
    overflow: hidden; position: sticky; top: 1rem;
}
.cu-info-panel-header { background: #f7f8fa; border-bottom: 1px solid #e3e4e8; padding: 10px 14px; }
.cu-info-panel-header span { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: #8a8f98; }
.cu-info-body { padding: 14px; }

.cu-sections { display: flex; flex-direction: column; gap: 14px; }
.cu-section { background: white; border: 1px solid #e3e4e8; border-radius: 8px; overflow: hidden; }
.cu-section-header {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 16px; background: #fafbfc; border-bottom: 1px solid #e3e4e8;
}
.cu-section-icon {
    width: 24px; height: 24px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0;
}
.cu-section-icon.violet { background: #ede9fe; color: #7c3aed; }
.cu-section-icon.blue   { background: #dbeafe; color: #2563eb; }
.cu-section-icon.green  { background: #dcfce7; color: #16a34a; }
.cu-section-icon.red    { background: #fee2e2; color: #dc2626; }
.cu-section-icon.amber  { background: #fef3c7; color: #d97706; }
.cu-section-title { font-size: 13px; font-weight: 700; color: #1a1d23; margin: 0; }
.cu-section-sub   { font-size: 11px; color: #8a8f98; margin: 0 0 0 auto; }
.cu-section-body  { padding: 16px; }

.cu-field { margin-bottom: 14px; }
.cu-field:last-child { margin-bottom: 0; }
.cu-field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
@media(max-width:500px) { .cu-field-row { grid-template-columns: 1fr; } }
.cu-label { display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 4px; }
.cu-input, .cu-textarea {
    width: 100%; border: 1px solid #d3d5db; border-radius: 6px;
    padding: 7px 10px; font-size: 13px; color: #111827; background: white;
    transition: border-color .15s, box-shadow .15s;
}
.cu-input:focus, .cu-textarea:focus {
    outline: none; border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99,102,241,.18);
}
.cu-input.is-invalid, .cu-textarea.is-invalid { border-color: #dc2626; }
.cu-textarea { resize: vertical; min-height: 80px; }
.cu-err  { font-size: 11px; color: #dc2626; margin-top: 3px; }
.cu-hint { font-size: 11px; color: #9ca3af; margin-top: 3px; }

.cu-action-bar {
    background: white; border: 1px solid #e3e4e8; border-radius: 8px;
    padding: 12px 16px; display: flex; justify-content: flex-end; gap: 8px;
}
.cu-btn-cancel {
    padding: 6px 14px; border: 1.5px solid #d3d5db; border-radius: 6px;
    background: white; font-size: 13px; font-weight: 600; color: #6b7280;
    text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
    transition: border-color .15s, color .15s;
}
.cu-btn-cancel:hover { border-color: #adb0b8; color: #1a1d23; }
.cu-btn-save {
    padding: 6px 18px; background: #6366f1; border: 1px solid #6366f1;
    color: white; border-radius: 6px; font-size: 13px; font-weight: 600;
    cursor: pointer; transition: all .15s; display: inline-flex; align-items: center; gap: 5px;
}
.cu-btn-save:hover { background: #4f46e5; border-color: #4f46e5; box-shadow: 0 2px 6px rgba(99,102,241,.4); }
.cu-btn-danger {
    padding: 6px 18px; background: #dc2626; border: 1px solid #dc2626;
    color: white; border-radius: 6px; font-size: 13px; font-weight: 600;
    cursor: pointer; transition: all .15s; display: inline-flex; align-items: center; gap: 5px;
}
.cu-btn-danger:hover { background: #b91c1c; border-color: #b91c1c; box-shadow: 0 2px 6px rgba(220,38,38,.4); }

.cu-alert-success {
    background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 8px;
    padding: 10px 14px; margin-bottom: 14px;
    display: flex; align-items: center; gap: 8px; font-size: 13px; color: #065f46;
}
.cu-meta-row {
    display: flex; align-items: flex-start; gap: 8px;
    font-size: 12px; color: #6b7280; padding: 5px 0;
    border-top: 1px solid #f3f4f6;
}
.cu-meta-row i { font-size: 13px; color: #adb0b8; flex-shrink: 0; margin-top: 1px; }
.cu-meta-row strong { color: #1a1d23; font-weight: 600; }

.cu-avatar-wrap { position: relative; width: 72px; height: 72px; margin: 0 auto 10px; }
.cu-avatar-img  { width: 72px; height: 72px; border-radius: 50%; object-fit: cover; border: 3px solid #e3e4e8; display: block; }
.cu-avatar-init {
    width: 72px; height: 72px; border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff; font-size: 1.6rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}
.cu-panel-name  { text-align: center; font-size: 13px; font-weight: 700; color: #1a1d23; margin-bottom: 2px; }
.cu-panel-email { text-align: center; font-size: 11px; color: #adb0b8; margin-bottom: 12px; }
.cu-panel-nav { display: flex; flex-direction: column; gap: 4px; margin-top: 10px; border-top: 1px solid #f3f4f6; padding-top: 10px; }
.cu-nav-link {
    display: flex; align-items: center; gap: 8px; padding: 7px 10px;
    border-radius: 6px; font-size: 12px; font-weight: 600; color: #374151;
    text-decoration: none; transition: background .15s;
}
.cu-nav-link:hover, .cu-nav-link.active { background: #ede9fe; color: #6366f1; }
.cu-nav-link i { font-size: 13px; }

/* Avatar upload */
.cu-avatar-drop {
    border: 2px dashed #d3d5db; border-radius: 8px; padding: 14px;
    text-align: center; cursor: pointer; background: #f9fafb;
    transition: border-color .15s; margin-top: 8px; position: relative;
}
.cu-avatar-drop:hover { border-color: #6366f1; background: rgba(99,102,241,.04); }
.cu-avatar-drop input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
.cu-avatar-drop i { display: block; font-size: 1.2rem; color: #6366f1; margin-bottom: 4px; }
.cu-avatar-drop span { font-size: 11px; color: #9ca3af; }
.cu-avatar-sel {
    display: none; margin-top: 8px; background: #ede9fe; border: 1px solid #c4b5fd;
    border-radius: 6px; padding: 6px 10px; font-size: 12px; color: #5b21b6;
    align-items: center; gap: 8px;
}
.cu-avatar-sel.show { display: flex; }
.cu-avatar-sel button { border: none; background: none; font-size: 14px; cursor: pointer; color: #5b21b6; margin-left: auto; padding: 0; }
.cu-btn-del-avatar {
    width: 100%; margin-top: 6px; padding: 5px 10px; background: #fee2e2;
    border: 1px solid #fca5a5; color: #dc2626; border-radius: 6px;
    font-size: 11px; font-weight: 600; cursor: pointer; display: flex;
    align-items: center; justify-content: center; gap: 4px; transition: background .15s;
}
.cu-btn-del-avatar:hover { background: #dc2626; color: #fff; border-color: #dc2626; }
</style>
@endpush

@section('content')
<div class="main-content">

    <div class="cu-header">
        <div class="d-flex align-items-center" style="position:relative;z-index:1;">
            <a href="{{ route('profile.show') }}" class="me-3 text-decoration-none">
                <i class="bi bi-arrow-left fs-5" style="color:rgba(255,255,255,.8);"></i>
            </a>
            <div>
                <h1 class="cu-header-title">Edit Profile</h1>
                <p class="cu-header-sub">Update your personal information</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="cu-alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profile-form">
        @csrf
        @method('PUT')

        <div class="cu-layout">
            {{-- Left panel --}}
            <div class="cu-info-panel">
                <div class="cu-info-panel-header"><span>Profile Picture</span></div>
                <div class="cu-info-body">
                    <div class="cu-avatar-wrap">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="cu-avatar-img" id="avatar-preview-img">
                        @else
                            <div class="cu-avatar-init" id="avatar-init">{{ strtoupper(substr($user->name,0,2)) }}</div>
                        @endif
                    </div>
                    <div class="cu-panel-name">{{ $user->name }}</div>
                    <div class="cu-panel-email">{{ $user->email }}</div>

                    <div class="cu-avatar-drop" id="avatar-drop">
                        <i class="bi bi-camera"></i>
                        <span>Click to upload new photo<br>JPG, PNG or GIF · max 2 MB</span>
                        <input type="file" name="avatar" id="avatar-input" accept="image/*">
                    </div>
                    <div class="cu-avatar-sel" id="avatar-sel">
                        <i class="bi bi-image" style="font-size:13px;"></i>
                        <span id="avatar-sel-name" style="font-weight:600;"></span>
                        <button type="button" onclick="clearAvatar()" title="Remove">&times;</button>
                    </div>
                    @if($user->avatar)
                    <button type="button" class="cu-btn-del-avatar" id="del-avatar-btn">
                        <i class="bi bi-trash"></i> Remove Current Photo
                    </button>
                    @endif

                    <div class="cu-panel-nav" style="margin-top:12px;">
                        <a href="{{ route('profile.show') }}" class="cu-nav-link"><i class="bi bi-person"></i> View Profile</a>
                        <a href="{{ route('profile.edit') }}" class="cu-nav-link active"><i class="bi bi-pencil"></i> Edit Info</a>
                        <a href="{{ route('profile.password') }}" class="cu-nav-link"><i class="bi bi-key"></i> Password</a>
                    </div>
                </div>
            </div>

            {{-- Right sections --}}
            <div class="cu-sections">

                {{-- Basic Info --}}
                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon violet"><i class="bi bi-person"></i></span>
                        <span class="cu-section-title">Basic Information</span>
                    </div>
                    <div class="cu-section-body">
                        <div class="cu-field-row">
                            <div class="cu-field">
                                <label for="name" class="cu-label">Full Name <span style="color:#dc2626;">*</span></label>
                                <input type="text" id="name" name="name"
                                       class="cu-input @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}" required autofocus>
                                @error('name')<p class="cu-err">{{ $message }}</p>@enderror
                            </div>
                            <div class="cu-field">
                                <label for="email" class="cu-label">Email Address <span style="color:#dc2626;">*</span></label>
                                <input type="email" id="email" name="email"
                                       class="cu-input @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email')<p class="cu-err">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="cu-field-row">
                            <div class="cu-field">
                                <label for="phone" class="cu-label">Phone</label>
                                <input type="tel" id="phone" name="phone"
                                       class="cu-input @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $user->phone) }}" placeholder="+1 555 000 0000">
                                @error('phone')<p class="cu-err">{{ $message }}</p>@enderror
                            </div>
                            <div class="cu-field">
                                <label for="location" class="cu-label">Location</label>
                                <input type="text" id="location" name="location"
                                       class="cu-input @error('location') is-invalid @enderror"
                                       value="{{ old('location', $user->location) }}" placeholder="City, Country">
                                @error('location')<p class="cu-err">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="cu-field">
                            <label for="website" class="cu-label">Website</label>
                            <input type="url" id="website" name="website"
                                   class="cu-input @error('website') is-invalid @enderror"
                                   value="{{ old('website', $user->website) }}" placeholder="https://example.com">
                            @error('website')<p class="cu-err">{{ $message }}</p>@enderror
                        </div>
                        <div class="cu-field">
                            <label for="bio" class="cu-label">Bio</label>
                            <textarea id="bio" name="bio" rows="4"
                                      class="cu-textarea @error('bio') is-invalid @enderror"
                                      placeholder="Tell us about yourself…" maxlength="500">{{ old('bio', $user->bio) }}</textarea>
                            <p class="cu-hint">Max 500 characters</p>
                            @error('bio')<p class="cu-err">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="cu-action-bar">
                    <a href="{{ route('profile.show') }}" class="cu-btn-cancel"><i class="bi bi-x-lg"></i> Cancel</a>
                    <button type="submit" class="cu-btn-save"><i class="bi bi-check-lg"></i> Update Profile</button>
                </div>

            </div>{{-- /cu-sections --}}
        </div>{{-- /cu-layout --}}
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var avatarInput = document.getElementById('avatar-input');
    var avatarSel   = document.getElementById('avatar-sel');
    var avatarSelName = document.getElementById('avatar-sel-name');
    var avatarPreviewImg = document.getElementById('avatar-preview-img');
    var avatarInit  = document.getElementById('avatar-init');
    var delBtn = document.getElementById('del-avatar-btn');

    avatarInput.addEventListener('change', function () {
        if (!this.files.length) return;
        var file = this.files[0];
        if (file.size > 2*1024*1024) { alert('Max file size is 2 MB'); this.value=''; return; }
        avatarSelName.textContent = file.name;
        avatarSel.classList.add('show');

        var reader = new FileReader();
        reader.onload = function(e) {
            if (avatarPreviewImg) {
                avatarPreviewImg.src = e.target.result;
            } else if (avatarInit) {
                var img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'cu-avatar-img';
                img.id = 'avatar-preview-img';
                avatarInit.replaceWith(img);
            }
        };
        reader.readAsDataURL(file);
    });

    window.clearAvatar = function () {
        avatarInput.value = '';
        avatarSel.classList.remove('show');
    };

    if (delBtn) {
        delBtn.addEventListener('click', function () {
            if (!confirm('Remove your profile picture?')) return;
            fetch('{{ route("profile.avatar.delete") }}', {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            }).then(r => r.json()).then(d => { if (d.success) location.reload(); });
        });
    }
});
</script>
@endpush
