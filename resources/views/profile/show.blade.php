@extends('layouts.app')
@section('title', 'My Profile')
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
.cu-panel-name { text-align: center; font-size: 14px; font-weight: 700; color: #1a1d23; margin-bottom: 2px; }
.cu-panel-email { text-align: center; font-size: 11px; color: #adb0b8; margin-bottom: 12px; }
.cu-panel-nav { display: flex; flex-direction: column; gap: 4px; margin-top: 10px; border-top: 1px solid #f3f4f6; padding-top: 10px; }
.cu-nav-link {
    display: flex; align-items: center; gap: 8px; padding: 7px 10px;
    border-radius: 6px; font-size: 12px; font-weight: 600; color: #374151;
    text-decoration: none; transition: background .15s;
}
.cu-nav-link:hover, .cu-nav-link.active { background: #ede9fe; color: #6366f1; }
.cu-nav-link i { font-size: 13px; }
.cu-detail-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.cu-detail-table tr td { padding: 8px 0; border-bottom: 1px solid #f3f4f6; vertical-align: top; }
.cu-detail-table tr:last-child td { border-bottom: none; }
.cu-detail-table td:first-child { font-weight: 600; color: #374151; width: 35%; display: flex; align-items: center; gap: 6px; }
.cu-detail-table td:last-child  { color: #6b7280; }
.cu-stat-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(100px,1fr)); gap: 10px; }
.cu-stat-box { background: #f9fafb; border: 1px solid #e3e4e8; border-radius: 8px; padding: 12px; text-align: center; }
.cu-stat-box-num { font-size: 20px; font-weight: 800; color: #1a1d23; line-height: 1; }
.cu-stat-box-lbl { font-size: 10px; color: #8a8f98; text-transform: uppercase; letter-spacing: .5px; margin-top: 3px; }
</style>
@endpush

@section('content')
<div class="main-content">

    <div class="cu-header">
        <div class="cu-header-inner">
            <div>
                <h1 class="cu-header-title"><i class="bi bi-person-circle me-2"></i>My Profile</h1>
                <p class="cu-header-sub">View and manage your account details</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="cu-btn-hdr">
                <i class="bi bi-pencil"></i> Edit Profile
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="cu-alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif

    <div class="cu-layout">
        {{-- Left panel --}}
        <div class="cu-info-panel">
            <div class="cu-info-panel-header"><span>Account</span></div>
            <div class="cu-info-body">
                <div class="cu-avatar-wrap">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="cu-avatar-img">
                    @else
                        <div class="cu-avatar-init">{{ strtoupper(substr($user->name,0,2)) }}</div>
                    @endif
                </div>
                <div class="cu-panel-name">{{ $user->name }}</div>
                <div class="cu-panel-email">{{ $user->email }}</div>

                @if($user->location)
                <div class="cu-meta-row"><i class="bi bi-geo-alt"></i><span>{{ $user->location }}</span></div>
                @endif
                @if($user->phone)
                <div class="cu-meta-row"><i class="bi bi-telephone"></i><span>{{ $user->phone }}</span></div>
                @endif
                @if($user->website)
                <div class="cu-meta-row"><i class="bi bi-globe"></i><a href="{{ $user->website }}" target="_blank" style="color:#6366f1;font-size:11px;word-break:break-all;">{{ Str::limit($user->website,30) }}</a></div>
                @endif
                <div class="cu-meta-row"><i class="bi bi-calendar3"></i><span>Joined <strong>{{ $user->created_at->format('M Y') }}</strong></span></div>

                <div class="cu-panel-nav">
                    <a href="{{ route('profile.show') }}" class="cu-nav-link active"><i class="bi bi-person"></i> Profile</a>
                    <a href="{{ route('profile.edit') }}" class="cu-nav-link"><i class="bi bi-pencil"></i> Edit Info</a>
                    <a href="{{ route('profile.password') }}" class="cu-nav-link"><i class="bi bi-key"></i> Password</a>
                </div>
            </div>
        </div>

        {{-- Right sections --}}
        <div class="cu-sections">

            {{-- Stats --}}
            <div class="cu-section">
                <div class="cu-section-header">
                    <span class="cu-section-icon violet"><i class="bi bi-bar-chart"></i></span>
                    <span class="cu-section-title">Activity Overview</span>
                </div>
                <div class="cu-section-body">
                    <div class="cu-stat-row">
                        <div class="cu-stat-box">
                            <div class="cu-stat-box-num">{{ $user->tasks()->count() }}</div>
                            <div class="cu-stat-box-lbl">Tasks</div>
                        </div>
                        <div class="cu-stat-box">
                            <div class="cu-stat-box-num">{{ $user->projects()->count() }}</div>
                            <div class="cu-stat-box-lbl">Projects</div>
                        </div>
                        <div class="cu-stat-box">
                            <div class="cu-stat-box-num">{{ $user->reminders()->count() }}</div>
                            <div class="cu-stat-box-lbl">Reminders</div>
                        </div>
                        <div class="cu-stat-box">
                            <div class="cu-stat-box-num">{{ $user->notes()->count() }}</div>
                            <div class="cu-stat-box-lbl">Notes</div>
                        </div>
                        <div class="cu-stat-box">
                            <div class="cu-stat-box-num">{{ $user->files()->count() }}</div>
                            <div class="cu-stat-box-lbl">Files</div>
                        </div>
                        <div class="cu-stat-box">
                            <div class="cu-stat-box-num">{{ $user->routines()->count() }}</div>
                            <div class="cu-stat-box-lbl">Routines</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Personal info --}}
            <div class="cu-section">
                <div class="cu-section-header">
                    <span class="cu-section-icon blue"><i class="bi bi-person-lines-fill"></i></span>
                    <span class="cu-section-title">Personal Information</span>
                    <a href="{{ route('profile.edit') }}" style="margin-left:auto;font-size:11px;color:#6366f1;font-weight:600;text-decoration:none;">Edit</a>
                </div>
                <div class="cu-section-body">
                    <table class="cu-detail-table">
                        <tr>
                            <td><i class="bi bi-person text-muted"></i> Name</td>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-envelope text-muted"></i> Email</td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-telephone text-muted"></i> Phone</td>
                            <td>{{ $user->phone ?: '—' }}</td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-geo-alt text-muted"></i> Location</td>
                            <td>{{ $user->location ?: '—' }}</td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-globe text-muted"></i> Website</td>
                            <td>
                                @if($user->website)
                                    <a href="{{ $user->website }}" target="_blank" style="color:#6366f1;">{{ Str::limit($user->website,40) }}</a>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-calendar3 text-muted"></i> Member Since</td>
                            <td>{{ $user->created_at->format('F d, Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Bio --}}
            @if($user->bio)
            <div class="cu-section">
                <div class="cu-section-header">
                    <span class="cu-section-icon amber"><i class="bi bi-chat-quote"></i></span>
                    <span class="cu-section-title">Bio</span>
                </div>
                <div class="cu-section-body">
                    <p style="font-size:13px;color:#374151;line-height:1.7;margin:0;">{{ $user->bio }}</p>
                </div>
            </div>
            @endif

            {{-- Security --}}
            <div class="cu-section">
                <div class="cu-section-header">
                    <span class="cu-section-icon red"><i class="bi bi-shield-lock"></i></span>
                    <span class="cu-section-title">Security</span>
                </div>
                <div class="cu-section-body" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
                    <div style="font-size:13px;color:#374151;">
                        <strong>Password</strong><br>
                        <span style="font-size:11px;color:#9ca3af;">Last updated {{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                    <a href="{{ route('profile.password') }}" class="cu-btn-save" style="text-decoration:none;">
                        <i class="bi bi-key"></i> Change Password
                    </a>
                </div>
            </div>

        </div>{{-- /cu-sections --}}
    </div>{{-- /cu-layout --}}
</div>
@endsection
