@extends('layouts.app')
@section('title', 'Change Password')
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

.cu-panel-name  { text-align: center; font-size: 13px; font-weight: 700; color: #1a1d23; margin-bottom: 2px; }
.cu-panel-email { text-align: center; font-size: 11px; color: #adb0b8; margin-bottom: 12px; }
.cu-avatar-init {
    width: 48px; height: 48px; border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff; font-size: 1.1rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 10px;
}
.cu-avatar-img-sm { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 3px solid #e3e4e8; display: block; margin: 0 auto 10px; }
.cu-panel-nav { display: flex; flex-direction: column; gap: 4px; margin-top: 10px; border-top: 1px solid #f3f4f6; padding-top: 10px; }
.cu-nav-link {
    display: flex; align-items: center; gap: 8px; padding: 7px 10px;
    border-radius: 6px; font-size: 12px; font-weight: 600; color: #374151;
    text-decoration: none; transition: background .15s;
}
.cu-nav-link:hover, .cu-nav-link.active { background: #ede9fe; color: #6366f1; }
.cu-nav-link i { font-size: 13px; }

/* Password field */
.cu-pw-wrap { position: relative; }
.cu-pw-wrap .cu-input { padding-right: 36px; }
.cu-pw-toggle {
    position: absolute; right: 9px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer;
    color: #adb0b8; font-size: 14px; padding: 0;
}
.cu-pw-toggle:hover { color: #6366f1; }

/* Strength meter */
.cu-strength-bar { height: 4px; background: #e5e7eb; border-radius: 2px; overflow: hidden; margin: 6px 0 4px; }
.cu-strength-fill { height: 100%; border-radius: 2px; transition: width .3s, background .3s; width: 0; }
.cu-strength-text { font-size: 11px; font-weight: 600; }

/* Requirements */
.cu-req-list { margin: 8px 0 0; padding: 0; list-style: none; }
.cu-req-list li { font-size: 11px; color: #9ca3af; display: flex; align-items: center; gap: 5px; padding: 2px 0; }
.cu-req-list li.met { color: #059669; }
.cu-req-list li i { font-size: 10px; }

/* Match indicator */
.cu-match { font-size: 11px; margin-top: 4px; font-weight: 600; }
.cu-match.ok  { color: #059669; }
.cu-match.bad { color: #dc2626; }

/* Security tip */
.cu-tip {
    background: #fef3c7; border: 1px solid #fcd34d; border-radius: 8px;
    padding: 10px 14px; display: flex; align-items: flex-start; gap: 8px;
    font-size: 12px; color: #92400e;
}
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
                <h1 class="cu-header-title">Change Password</h1>
                <p class="cu-header-sub">Keep your account secure</p>
            </div>
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
                @if(auth()->user()->avatar)
                    <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="" class="cu-avatar-img-sm">
                @else
                    <div class="cu-avatar-init">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
                @endif
                <div class="cu-panel-name">{{ auth()->user()->name }}</div>
                <div class="cu-panel-email">{{ auth()->user()->email }}</div>

                <div class="cu-tip">
                    <i class="bi bi-shield-check" style="font-size:14px;flex-shrink:0;margin-top:1px;"></i>
                    <span>Use at least 8 characters with a mix of letters, numbers, and symbols.</span>
                </div>

                <div class="cu-panel-nav" style="margin-top:12px;">
                    <a href="{{ route('profile.show') }}" class="cu-nav-link"><i class="bi bi-person"></i> View Profile</a>
                    <a href="{{ route('profile.edit') }}" class="cu-nav-link"><i class="bi bi-pencil"></i> Edit Info</a>
                    <a href="{{ route('profile.password') }}" class="cu-nav-link active"><i class="bi bi-key"></i> Password</a>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('profile.password.update') }}" method="POST" id="pw-form">
            @csrf
            @method('PUT')
            <div class="cu-sections">

                <div class="cu-section">
                    <div class="cu-section-header">
                        <span class="cu-section-icon red"><i class="bi bi-key"></i></span>
                        <span class="cu-section-title">Update Password</span>
                    </div>
                    <div class="cu-section-body">

                        <div class="cu-field">
                            <label for="current_password" class="cu-label">Current Password <span style="color:#dc2626;">*</span></label>
                            <div class="cu-pw-wrap">
                                <input type="password" id="current_password" name="current_password"
                                       class="cu-input @error('current_password') is-invalid @enderror"
                                       required autocomplete="current-password">
                                <button type="button" class="cu-pw-toggle" data-target="current_password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('current_password')<p class="cu-err">{{ $message }}</p>@enderror
                        </div>

                        <div class="cu-field">
                            <label for="password" class="cu-label">New Password <span style="color:#dc2626;">*</span></label>
                            <div class="cu-pw-wrap">
                                <input type="password" id="password" name="password"
                                       class="cu-input @error('password') is-invalid @enderror"
                                       required autocomplete="new-password" oninput="checkStrength(this.value)">
                                <button type="button" class="cu-pw-toggle" data-target="password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="cu-strength-bar"><div class="cu-strength-fill" id="strength-fill"></div></div>
                            <div class="cu-strength-text" id="strength-text"></div>
                            <ul class="cu-req-list" id="req-list">
                                <li id="req-len"><i class="bi bi-circle"></i> At least 8 characters</li>
                                <li id="req-upper"><i class="bi bi-circle"></i> One uppercase letter</li>
                                <li id="req-lower"><i class="bi bi-circle"></i> One lowercase letter</li>
                                <li id="req-num"><i class="bi bi-circle"></i> One number</li>
                                <li id="req-sym"><i class="bi bi-circle"></i> One special character</li>
                            </ul>
                            @error('password')<p class="cu-err">{{ $message }}</p>@enderror
                        </div>

                        <div class="cu-field">
                            <label for="password_confirmation" class="cu-label">Confirm New Password <span style="color:#dc2626;">*</span></label>
                            <div class="cu-pw-wrap">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="cu-input" required autocomplete="new-password"
                                       oninput="checkMatch()">
                                <button type="button" class="cu-pw-toggle" data-target="password_confirmation">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="cu-match" id="match-msg"></div>
                        </div>

                    </div>
                </div>

                <div class="cu-action-bar">
                    <a href="{{ route('profile.show') }}" class="cu-btn-cancel"><i class="bi bi-x-lg"></i> Cancel</a>
                    <button type="submit" class="cu-btn-danger"><i class="bi bi-key"></i> Update Password</button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle visibility
    document.querySelectorAll('.cu-pw-toggle').forEach(function(btn) {
        btn.addEventListener('click', function () {
            var inp = document.getElementById(this.dataset.target);
            var ico = this.querySelector('i');
            inp.type = inp.type === 'password' ? 'text' : 'password';
            ico.className = inp.type === 'text' ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    });
});

function req(id, met) {
    var el = document.getElementById(id);
    el.classList.toggle('met', met);
    el.querySelector('i').className = met ? 'bi bi-check-circle' : 'bi bi-circle';
}

function checkStrength(val) {
    var hasLen   = val.length >= 8;
    var hasUpper = /[A-Z]/.test(val);
    var hasLower = /[a-z]/.test(val);
    var hasNum   = /\d/.test(val);
    var hasSym   = /[!@#$%^&*(),.?":{}|<>_\-]/.test(val);
    req('req-len',   hasLen);
    req('req-upper', hasUpper);
    req('req-lower', hasLower);
    req('req-num',   hasNum);
    req('req-sym',   hasSym);

    var score = [hasLen, hasUpper, hasLower, hasNum, hasSym].filter(Boolean).length;
    var fill  = document.getElementById('strength-fill');
    var text  = document.getElementById('strength-text');
    var labels = ['', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
    var colors = ['', '#dc2626', '#f59e0b', '#3b82f6', '#10b981', '#059669'];
    fill.style.width   = (score * 20) + '%';
    fill.style.background = colors[score] || '#e5e7eb';
    text.textContent   = val.length ? labels[score] || '' : '';
    text.style.color   = colors[score] || '#9ca3af';
    checkMatch();
}

function checkMatch() {
    var pw  = document.getElementById('password').value;
    var con = document.getElementById('password_confirmation').value;
    var msg = document.getElementById('match-msg');
    if (!con.length) { msg.textContent = ''; return; }
    if (pw === con)  { msg.textContent = '✓ Passwords match'; msg.className = 'cu-match ok'; }
    else             { msg.textContent = '✕ Passwords do not match'; msg.className = 'cu-match bad'; }
}
</script>
@endpush
