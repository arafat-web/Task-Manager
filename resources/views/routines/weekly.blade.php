@extends('layouts.app')

@section('title', 'Weekly Routines')

@push('styles')
<style>

    /* ── Page shell ──────────────────────────────────────── */
    .main-content { padding: 14px 16px; background: #f7f8fa; min-height: 100vh; }

    /* ── Gradient header ─────────────────────────────────── */
    .cu-header {
        background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        border-radius: 10px; padding: 12px 18px; color: white;
        margin-bottom: 14px; position: relative; overflow: hidden;
        border: 1px solid #6d28d9; box-shadow: 0 2px 8px rgba(124,58,237,.3);
    }
    .cu-header::before {
        content: ''; position: absolute; top: 0; right: 0;
        width: 80px; height: 80px; background: rgba(255,255,255,.08);
        border-radius: 50%; transform: translate(20px,-20px);
    }
    .cu-header-title { font-weight: 700; font-size: 17px; margin: 0; position: relative; z-index: 1; }
    .cu-header-sub   { font-size: 12px; opacity: .8; margin: 2px 0 0; position: relative; z-index: 1; }
    .cu-btn-glass {
        display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px;
        border-radius: 7px; background: rgba(255,255,255,.2); color: white;
        border: 1px solid rgba(255,255,255,.3); font-size: 12px; font-weight: 600;
        text-decoration: none; transition: background .15s; position: relative; z-index: 1;
    }
    .cu-btn-glass:hover { background: rgba(255,255,255,.3); color: white; }

    /* ── Card grid ───────────────────────────────────────── */
    .cu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 12px;
    }

    /* ── Routine card ────────────────────────────────────── */
    .cu-card {
        background: white; border: 1px solid #e3e4e8; border-radius: 10px;
        overflow: hidden; transition: all .15s;
    }
    .cu-card:hover { box-shadow: 0 6px 18px rgba(0,0,0,.1); border-color: #c4b5fd; transform: translateY(-1px); }
    .cu-card-accent { height: 4px; }
    .cu-card-body   { padding: 14px 14px 10px; }
    .cu-card-title  { font-size: 14px; font-weight: 700; color: #1a1d23; margin-bottom: 5px; line-height: 1.3; }
    .cu-card-desc   {
        font-size: 12px; color: #8a8f98; margin-bottom: 10px; line-height: 1.5;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .cu-card-meta   { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 10px; }
    .cu-pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 600;
        background: #f3f4f6; color: #6b7385;
    }
    .cu-pill i { font-size: 10px; }
    .cu-card-footer {
        display: flex; align-items: center; justify-content: flex-end; gap: 5px;
        padding: 8px 14px; background: #fafbfc; border-top: 1px solid #f0f1f3;
    }
    .cu-btn-edit {
        display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px;
        border-radius: 5px; font-size: 11px; font-weight: 600; text-decoration: none;
        background: #fffbeb; color: #d97706; border: 1px solid #fde68a; transition: all .15s;
    }
    .cu-btn-edit:hover { background: #fef3c7; }
    .cu-btn-del {
        display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px;
        border-radius: 5px; font-size: 11px; font-weight: 600;
        background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;
        cursor: pointer; transition: all .15s;
    }
    .cu-btn-del:hover { background: #fee2e2; }

    /* ── Empty state ─────────────────────────────────────── */
    .cu-empty {
        grid-column: 1 / -1; text-align: center; padding: 48px 24px;
        background: white; border: 1px solid #e3e4e8; border-radius: 10px; color: #adb0b8;
    }
    .cu-empty i    { font-size: 36px; display: block; margin-bottom: 10px; opacity: .45; }
    .cu-empty h5   { font-size: 15px; font-weight: 700; color: #6b7385; margin-bottom: 6px; }
    .cu-empty p    { font-size: 13px; margin-bottom: 16px; }
    .cu-btn-create {
        display: inline-flex; align-items: center; gap: 6px; padding: 7px 18px;
        border-radius: 7px; background: #7c3aed; color: white; font-size: 13px;
        font-weight: 600; text-decoration: none; transition: background .15s;
    }
    .cu-btn-create:hover { background: #6d28d9; color: white; }
</style>
@endpush

@section('content')
<div class="main-content">

    <div class="cu-header">
        <div class="d-flex align-items-center justify-content-between" style="position:relative;z-index:1;">
            <div>
                <h1 class="cu-header-title"><i class="bi bi-calendar-week me-2"></i>Weekly Routines</h1>
                <p class="cu-header-sub">All your weekly recurring schedules</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('routines.create') }}" class="cu-btn-glass">
                    <i class="bi bi-plus-lg"></i> New Routine
                </a>
                <a href="{{ route('routines.index') }}" class="cu-btn-glass">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="cu-grid">
        @forelse($weeklyRoutines as $routine)
        @php
            $weeks = json_decode($routine->weeks, true) ?? [];
            $weekDisplay = count($weeks) > 4
                ? 'Weeks ' . implode(', ', array_slice($weeks, 0, 4)) . ' +' . (count($weeks) - 4) . ' more'
                : (count($weeks) ? 'Week ' . implode(', ', $weeks) : '');
        @endphp
        <div class="cu-card">
            <div class="cu-card-accent" style="background:#2563eb;"></div>
            <div class="cu-card-body">
                <div class="cu-card-title">{{ $routine->title }}</div>
                @if($routine->description)
                <div class="cu-card-desc">{{ Str::limit(strip_tags($routine->description), 100) }}</div>
                @endif
                <div class="cu-card-meta">
                    @if($weekDisplay)
                    <span class="cu-pill">
                        <i class="bi bi-calendar-week"></i>
                        {{ $weekDisplay }}
                    </span>
                    @endif
                    @if($routine->start_time && $routine->end_time)
                    <span class="cu-pill">
                        <i class="bi bi-clock"></i>
                        {{ \Carbon\Carbon::parse($routine->start_time)->format('g:i A') }}
                        &ndash;
                        {{ \Carbon\Carbon::parse($routine->end_time)->format('g:i A') }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="cu-card-footer">
                <a href="{{ route('routines.edit', $routine->id) }}" class="cu-btn-edit">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('routines.destroy', $routine->id) }}" method="POST" style="display:inline;"
                      onsubmit="return confirm('Delete this routine?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="cu-btn-del">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="cu-empty">
            <i class="bi bi-calendar-week"></i>
            <h5>No weekly routines yet</h5>
            <p>Create your first weekly routine to get started.</p>
            <a href="{{ route('routines.create') }}" class="cu-btn-create">
                <i class="bi bi-plus-lg"></i> Create Weekly Routine
            </a>
        </div>
        @endforelse
    </div>

</div>
@endsection
