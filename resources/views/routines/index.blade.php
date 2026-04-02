@extends('layouts.app')

@section('title', 'Routines')

@push('styles')
<style>
    /* ── Page shell ───────────────────────────────────────── */
    .main-content { padding: 14px 16px; background: #f7f8fa; min-height: 100vh; }

    /* ── Gradient header ──────────────────────────────────── */
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
    .cu-btn-new {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 14px; border-radius: 7px; background: rgba(255,255,255,.2);
        color: white; border: 1px solid rgba(255,255,255,.3); font-size: 12px;
        font-weight: 600; text-decoration: none;
        transition: background .15s; position: relative; z-index: 1;
    }
    .cu-btn-new:hover { background: rgba(255,255,255,.3); color: white; }

    /* ── Stats row ────────────────────────────────────────── */
    .cu-stats { display: grid; grid-template-columns: repeat(4,1fr); gap: 10px; margin-bottom: 14px; }
    @media(max-width:700px) { .cu-stats { grid-template-columns: repeat(2,1fr); } }
    .cu-stat {
        background: white; border: 1px solid #e3e4e8; border-radius: 8px;
        padding: 12px 14px; display: flex; align-items: center; gap: 12px;
    }
    .cu-stat-icon {
        width: 36px; height: 36px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 15px; flex-shrink: 0;
    }
    .cu-stat-label { font-size: 11px; color: #8a8f98; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
    .cu-stat-val   { font-size: 20px; font-weight: 800; color: #1a1d23; line-height: 1; }

    /* ── Kanban grid ──────────────────────────────────────── */
    .cu-kanban { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; align-items: start; }
    @media(max-width:860px) { .cu-kanban { grid-template-columns: 1fr; } }

    .cu-col { background: #f3f4f6; border-radius: 10px; overflow: hidden; }
    .cu-col-head {
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 12px; border-bottom: 1px solid #e3e4e8;
    }
    .cu-col-head-left { display: flex; align-items: center; gap: 7px; font-size: 13px; font-weight: 700; color: #1a1d23; }
    .cu-col-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
    .cu-col-count {
        background: white; border: 1px solid #e3e4e8; border-radius: 20px;
        padding: 1px 7px; font-size: 11px; font-weight: 700; color: #8a8f98;
    }
    .cu-col-add {
        width: 26px; height: 26px; border-radius: 6px; border: 1px solid #e3e4e8;
        background: white; display: flex; align-items: center;
        justify-content: center; color: #8a8f98; font-size: 14px;
        transition: all .15s; text-decoration: none;
    }
    .cu-col-add:hover { background: #7c3aed; border-color: #7c3aed; color: white; }
    .cu-col-body { padding: 8px; min-height: 80px; display: flex; flex-direction: column; gap: 7px; }

    /* ── Routine card ─────────────────────────────────────── */
    .cu-routine-card {
        background: white; border: 1px solid #e3e4e8; border-radius: 8px;
        padding: 10px 12px; transition: all .15s;
    }
    .cu-routine-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.1); border-color: #c4b5fd; }
    .cu-routine-title { font-size: 13px; font-weight: 600; color: #1a1d23; margin-bottom: 4px; line-height: 1.3; }
    .cu-routine-desc  {
        font-size: 11px; color: #8a8f98; margin-bottom: 7px; line-height: 1.4;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .cu-routine-meta  { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 8px; }
    .cu-meta-pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 600;
        background: #f3f4f6; color: #6b7385;
    }
    .cu-meta-pill i { font-size: 10px; }
    .cu-routine-footer { display: flex; align-items: center; justify-content: flex-end; gap: 5px; }
    .cu-card-btn {
        display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px;
        border-radius: 5px; font-size: 11px; font-weight: 600; text-decoration: none;
        cursor: pointer; border: 1px solid transparent; transition: all .15s;
    }
    .cu-card-btn.edit   { background: #fffbeb; color: #d97706; border-color: #fde68a; }
    .cu-card-btn.edit:hover   { background: #fef3c7; }
    .cu-card-btn.delete { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
    .cu-card-btn.delete:hover { background: #fee2e2; }

    /* ── Empty state ──────────────────────────────────────── */
    .cu-empty { text-align: center; padding: 20px 12px; color: #adb0b8; }
    .cu-empty i { font-size: 24px; display: block; margin-bottom: 6px; opacity: .5; }
    .cu-empty p { font-size: 12px; margin: 0; }

    /* ── Column footer / view-all ─────────────────────────── */
    .cu-col-footer { padding: 8px; border-top: 1px solid #e3e4e8; }
    .cu-view-all {
        display: flex; align-items: center; justify-content: center; gap: 5px;
        width: 100%; padding: 6px; border-radius: 6px; border: 1px solid #e3e4e8;
        background: white; font-size: 12px; font-weight: 600; color: #6b7385;
        text-decoration: none; transition: all .15s;
    }
    .cu-view-all:hover { border-color: #7c3aed; color: #7c3aed; background: #faf5ff; }
</style>
@endpush

@section('content')
<div class="main-content">

    {{-- ── Gradient Header ──────────────────────────────────── --}}
    <div class="cu-header">
        <div class="d-flex align-items-center justify-content-between" style="position:relative;z-index:1;">
            <div>
                <h1 class="cu-header-title"><i class="bi bi-arrow-repeat me-2"></i>Routines</h1>
                <p class="cu-header-sub">Manage your daily, weekly, and monthly routines</p>
            </div>
            <a href="{{ route('routines.create') }}" class="cu-btn-new">
                <i class="bi bi-plus-lg"></i> New Routine
            </a>
        </div>
    </div>

    {{-- ── Stats Row ─────────────────────────────────────────── --}}
    @php
        $daily   = count($upcomingDailyRoutines);
        $weekly  = count($upcomingWeeklyRoutines);
        $monthly = count($upcomingMonthlyRoutines);
        $total   = $daily + $weekly + $monthly;
    @endphp
    <div class="cu-stats">
        <div class="cu-stat">
            <div class="cu-stat-icon" style="background:#ede9fe;color:#7c3aed;">
                <i class="bi bi-sun"></i>
            </div>
            <div>
                <div class="cu-stat-val">{{ $daily }}</div>
                <div class="cu-stat-label">Daily</div>
            </div>
        </div>
        <div class="cu-stat">
            <div class="cu-stat-icon" style="background:#dbeafe;color:#2563eb;">
                <i class="bi bi-calendar-week"></i>
            </div>
            <div>
                <div class="cu-stat-val">{{ $weekly }}</div>
                <div class="cu-stat-label">Weekly</div>
            </div>
        </div>
        <div class="cu-stat">
            <div class="cu-stat-icon" style="background:#fef3c7;color:#d97706;">
                <i class="bi bi-calendar-month"></i>
            </div>
            <div>
                <div class="cu-stat-val">{{ $monthly }}</div>
                <div class="cu-stat-label">Monthly</div>
            </div>
        </div>
        <div class="cu-stat">
            <div class="cu-stat-icon" style="background:#dcfce7;color:#16a34a;">
                <i class="bi bi-check2-circle"></i>
            </div>
            <div>
                <div class="cu-stat-val">{{ $total }}</div>
                <div class="cu-stat-label">Active Today</div>
            </div>
        </div>
    </div>

    {{-- ── Kanban Columns ────────────────────────────────────── --}}
    <div class="cu-kanban">

        {{-- Daily --}}
        <div class="cu-col">
            <div class="cu-col-head">
                <div class="cu-col-head-left">
                    <span class="cu-col-dot" style="background:#7c3aed;"></span>
                    Daily Routines
                    <span class="cu-col-count">{{ $daily }}</span>
                </div>
                <a href="{{ route('routines.create') }}" class="cu-col-add" title="Add routine">
                    <i class="bi bi-plus"></i>
                </a>
            </div>
            <div class="cu-col-body">
                @forelse($upcomingDailyRoutines as $routine)
                <div class="cu-routine-card">
                    <div class="cu-routine-title">{{ $routine->title }}</div>
                    @if($routine->description)
                    <div class="cu-routine-desc">{{ Str::limit(strip_tags($routine->description), 90) }}</div>
                    @endif
                    <div class="cu-routine-meta">
                        @if($routine->days)
                        <span class="cu-meta-pill">
                            <i class="bi bi-calendar-day"></i>
                            {{ implode(', ', array_map('ucfirst', json_decode($routine->days, true) ?? [])) }}
                        </span>
                        @endif
                        @if($routine->start_time && $routine->end_time)
                        <span class="cu-meta-pill">
                            <i class="bi bi-clock"></i>
                            {{ \Carbon\Carbon::parse($routine->start_time)->format('g:i A') }}
                            &ndash;
                            {{ \Carbon\Carbon::parse($routine->end_time)->format('g:i A') }}
                        </span>
                        @endif
                    </div>
                    <div class="cu-routine-footer">
                        <a href="{{ route('routines.edit', $routine->id) }}" class="cu-card-btn edit">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('routines.destroy', $routine->id) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Delete this routine?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="cu-card-btn delete">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="cu-empty">
                    <i class="bi bi-sun"></i>
                    <p>No daily routines active today</p>
                </div>
                @endforelse
            </div>
            <div class="cu-col-footer">
                <a href="{{ route('routines.showDaily') }}" class="cu-view-all">
                    <i class="bi bi-arrow-right-circle"></i> View all daily
                </a>
            </div>
        </div>

        {{-- Weekly --}}
        <div class="cu-col">
            <div class="cu-col-head">
                <div class="cu-col-head-left">
                    <span class="cu-col-dot" style="background:#2563eb;"></span>
                    Weekly Routines
                    <span class="cu-col-count">{{ $weekly }}</span>
                </div>
                <a href="{{ route('routines.create') }}" class="cu-col-add" title="Add routine">
                    <i class="bi bi-plus"></i>
                </a>
            </div>
            <div class="cu-col-body">
                @forelse($upcomingWeeklyRoutines as $routine)
                <div class="cu-routine-card">
                    <div class="cu-routine-title">{{ $routine->title }}</div>
                    @if($routine->description)
                    <div class="cu-routine-desc">{{ Str::limit(strip_tags($routine->description), 90) }}</div>
                    @endif
                    <div class="cu-routine-meta">
                        @if($routine->weeks)
                        <span class="cu-meta-pill">
                            <i class="bi bi-calendar-week"></i>
                            Week {{ implode(', ', json_decode($routine->weeks, true) ?? []) }}
                        </span>
                        @endif
                        @if($routine->start_time && $routine->end_time)
                        <span class="cu-meta-pill">
                            <i class="bi bi-clock"></i>
                            {{ \Carbon\Carbon::parse($routine->start_time)->format('g:i A') }}
                            &ndash;
                            {{ \Carbon\Carbon::parse($routine->end_time)->format('g:i A') }}
                        </span>
                        @endif
                    </div>
                    <div class="cu-routine-footer">
                        <a href="{{ route('routines.edit', $routine->id) }}" class="cu-card-btn edit">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('routines.destroy', $routine->id) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Delete this routine?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="cu-card-btn delete">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="cu-empty">
                    <i class="bi bi-calendar-week"></i>
                    <p>No weekly routines this week</p>
                </div>
                @endforelse
            </div>
            <div class="cu-col-footer">
                <a href="{{ route('routines.showWeekly') }}" class="cu-view-all">
                    <i class="bi bi-arrow-right-circle"></i> View all weekly
                </a>
            </div>
        </div>

        {{-- Monthly --}}
        <div class="cu-col">
            <div class="cu-col-head">
                <div class="cu-col-head-left">
                    <span class="cu-col-dot" style="background:#d97706;"></span>
                    Monthly Routines
                    <span class="cu-col-count">{{ $monthly }}</span>
                </div>
                <a href="{{ route('routines.create') }}" class="cu-col-add" title="Add routine">
                    <i class="bi bi-plus"></i>
                </a>
            </div>
            <div class="cu-col-body">
                @forelse($upcomingMonthlyRoutines as $routine)
                <div class="cu-routine-card">
                    <div class="cu-routine-title">{{ $routine->title }}</div>
                    @if($routine->description)
                    <div class="cu-routine-desc">{{ Str::limit(strip_tags($routine->description), 90) }}</div>
                    @endif
                    <div class="cu-routine-meta">
                        @if($routine->months)
                        <span class="cu-meta-pill">
                            <i class="bi bi-calendar3"></i>
                            @php
                                $monthNames = array_map(fn($m) => \Carbon\Carbon::createFromDate(null, (int)$m, 1)->format('M'), json_decode($routine->months, true) ?? []);
                            @endphp
                            {{ implode(', ', $monthNames) }}
                        </span>
                        @endif
                        @if($routine->start_time && $routine->end_time)
                        <span class="cu-meta-pill">
                            <i class="bi bi-clock"></i>
                            {{ \Carbon\Carbon::parse($routine->start_time)->format('g:i A') }}
                            &ndash;
                            {{ \Carbon\Carbon::parse($routine->end_time)->format('g:i A') }}
                        </span>
                        @endif
                    </div>
                    <div class="cu-routine-footer">
                        <a href="{{ route('routines.edit', $routine->id) }}" class="cu-card-btn edit">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('routines.destroy', $routine->id) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Delete this routine?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="cu-card-btn delete">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="cu-empty">
                    <i class="bi bi-calendar3"></i>
                    <p>No monthly routines this month</p>
                </div>
                @endforelse
            </div>
            <div class="cu-col-footer">
                <a href="{{ route('routines.showMonthly') }}" class="cu-view-all">
                    <i class="bi bi-arrow-right-circle"></i> View all monthly
                </a>
            </div>
        </div>

    </div>{{-- end .cu-kanban --}}

</div>
@endsection
