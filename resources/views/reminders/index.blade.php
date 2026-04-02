@extends('layouts.app')

@section('title', 'Reminders')

@push('styles')
<style>
/* ── cu-reminders page ── */
.cu-rem-page { padding: 1rem 0; }

/* Header */
.cu-header {
    background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
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
.cu-btn-add-rem {
    background: rgba(255,255,255,.18);
    color: #fff; border: 1.5px solid rgba(255,255,255,.4);
    border-radius: 6px; padding: 6px 14px;
    font-size: 12px; font-weight: 700;
    text-decoration: none; transition: background .15s;
    display: inline-flex; align-items: center; gap: 5px;
    white-space: nowrap;
}
.cu-btn-add-rem:hover { background: rgba(255,255,255,.28); color: #fff; }

/* Stat tiles */
.cu-rem-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: .6rem;
    margin-bottom: 1rem;
}
.cu-rem-tile {
    background: #fff; border-radius: 10px;
    padding: .8rem 1rem;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
    display: flex; align-items: center; gap: .7rem;
    cursor: pointer; transition: transform .15s, box-shadow .15s;
}
.cu-rem-tile:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.1); }
.cu-rem-tile.cu-tile-active  { border-left: 4px solid #f59e0b; }
.cu-rem-tile.cu-tile-ok      { border-left: 4px solid #10b981; }
.cu-rem-tile.cu-tile-done    { border-left: 4px solid #3b82f6; }
.cu-rem-tile.cu-tile-late    { border-left: 4px solid #ef4444; }
.cu-rem-tile.cu-tile-today   { border-left: 4px solid #8b5cf6; }
.cu-tile-ico {
    width: 36px; height: 36px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.cu-tile-ico.amber  { background: #fef3c7; color: #d97706; }
.cu-tile-ico.green  { background: #d1fae5; color: #059669; }
.cu-tile-ico.blue   { background: #dbeafe; color: #2563eb; }
.cu-tile-ico.red    { background: #fee2e2; color: #dc2626; }
.cu-tile-ico.violet { background: #ede9fe; color: #7c3aed; }
.cu-tile-val { font-size: 1.4rem; font-weight: 700; color: #111827; line-height: 1; }
.cu-tile-lbl { font-size: .7rem; font-weight: 600; text-transform: uppercase;
               letter-spacing: .04em; color: #6b7280; }

/* Filter bar */
.cu-rem-filter-bar {
    background: #fff; border-radius: 10px;
    border: 1px solid #e5e7eb;
    padding: .65rem 1rem;
    margin-bottom: 1rem;
    display: flex; align-items: center; gap: .6rem; flex-wrap: wrap;
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
}
.cu-rem-search {
    flex: 1; min-width: 180px;
    border: 1px solid #d1d5db; border-radius: 7px;
    padding: .45rem .8rem; font-size: .85rem;
    transition: border-color .2s;
}
.cu-rem-search:focus { outline: none; border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,.15); }
.cu-rem-select {
    border: 1px solid #d1d5db; border-radius: 7px;
    padding: .45rem .7rem; font-size: .85rem;
    background: #fff; color: #374151; cursor: pointer;
    transition: border-color .2s;
}
.cu-rem-select:focus { outline: none; border-color: #f59e0b; }
.cu-count-label {
    margin-left: auto; font-size: .8rem; font-weight: 600; color: #6b7280;
    background: #f3f4f6; border-radius: 20px; padding: .25rem .75rem;
    white-space: nowrap;
}

/* Grid of cards */
.cu-rem-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: .9rem;
}

/* Individual card */
.cu-rem-card {
    background: #fff; border-radius: 10px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
    padding: 1rem 1.1rem;
    display: flex; flex-direction: column; gap: .55rem;
    transition: transform .15s, box-shadow .15s;
}
.cu-rem-card:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,.1); }
.cu-rem-card.cu-rem-done { opacity: .72; }
.cu-rem-card.cu-rem-late { background: #fff8f8; }

.cu-rem-head  { display: flex; align-items: flex-start; justify-content: space-between; gap: .5rem; }
.cu-rem-title { font-size: .95rem; font-weight: 600; color: #111827; margin: 0; flex: 1; line-height: 1.35; }
.cu-rem-desc  { font-size: .82rem; color: #6b7280; margin: 0; line-height: 1.5; }

/* Priority badges */
.cu-pri-badge   { font-size: .68rem; font-weight: 700; text-transform: uppercase;
                  letter-spacing: .05em; padding: .18rem .55rem; border-radius: 20px; white-space: nowrap; }
.cu-pri-low     { background: #d1fae5; color: #065f46; }
.cu-pri-medium  { background: #fef3c7; color: #92400e; }
.cu-pri-high    { background: #fee2e2; color: #991b1b; }
.cu-pri-urgent  { background: #dc2626; color: #fff; }

/* Meta items */
.cu-rem-meta { display: flex; flex-wrap: wrap; gap: .35rem .7rem; }
.cu-meta-item {
    font-size: .76rem; color: #6b7280;
    display: flex; align-items: center; gap: .25rem;
}
.cu-meta-item.cu-meta-danger { color: #dc2626; }
.cu-meta-item.cu-meta-warn   { color: #d97706; }

/* Tags */
.cu-rem-tags { display: flex; flex-wrap: wrap; gap: .3rem; }
.cu-tag-pill {
    font-size: .7rem; padding: .15rem .5rem; border-radius: 20px;
    background: #fef3c7; color: #92400e; font-weight: 500;
}
.cu-tag-more { font-size: .7rem; color: #9ca3af; padding: .15rem .4rem; }

/* Status badge */
.cu-status-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .74rem; font-weight: 600; padding: .2rem .6rem; border-radius: 20px;
}
.cu-sb-danger  { background: #fee2e2; color: #dc2626; }
.cu-sb-warn    { background: #fef3c7; color: #d97706; }
.cu-sb-success { background: #d1fae5; color: #065f46; }
.cu-sb-muted   { background: #f3f4f6; color: #6b7280; }

/* Action buttons */
.cu-rem-actions { display: flex; flex-wrap: wrap; gap: .35rem; margin-top: .2rem; }
.cu-act {
    display: inline-flex; align-items: center; gap: .25rem;
    font-size: .76rem; font-weight: 600; padding: .3rem .65rem;
    border-radius: 6px; border: none; cursor: pointer;
    text-decoration: none; transition: opacity .15s, transform .1s;
}
.cu-act:hover { opacity: .82; transform: translateY(-1px); }
.cu-act-success { background: #d1fae5; color: #065f46; }
.cu-act-warn    { background: #fef3c7; color: #92400e; }
.cu-act-muted   { background: #f3f4f6; color: #6b7280; }
.cu-act-info    { background: #dbeafe; color: #1d4ed8; }
.cu-act-primary { background: #fef3c7; color: #b45309; }
.cu-act-danger  { background: #fee2e2; color: #dc2626; }

/* Empty state */
.cu-rem-empty { text-align: center; padding: 4rem 2rem; grid-column: 1/-1; }
.cu-empty-ico { font-size: 3.5rem; color: #d1d5db; display: block; margin-bottom: 1rem; }
.cu-btn-new-empty {
    display: inline-flex; align-items: center; gap: .4rem;
    margin-top: 1rem; background: linear-gradient(135deg, #f59e0b, #f97316);
    color: #fff; border-radius: 8px; padding: .6rem 1.5rem;
    font-weight: 600; font-size: .9rem; text-decoration: none;
    transition: opacity .2s;
}
.cu-btn-new-empty:hover { opacity: .87; color: #fff; }

@media (max-width: 576px) {
    .cu-rem-grid  { grid-template-columns: 1fr; }
    .cu-rem-stats { grid-template-columns: repeat(3, 1fr); }
}
</style>
@endpush

@section('content')
<div class="cu-rem-page">

    {{-- Header --}}
    <div class="cu-header">
        <div class="cu-header-inner">
            <div>
                <h1 class="cu-header-title"><i class="bi bi-bell-fill me-2"></i>Reminders</h1>
                <p class="cu-header-sub">{{ $stats['active'] }} active &middot; {{ $stats['due_today'] }} due today</p>
            </div>
            <a href="{{ route('reminders.create') }}" class="cu-btn-add-rem">
                <i class="bi bi-plus-lg"></i> New Reminder
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="cu-rem-stats">
        <div class="cu-rem-tile cu-tile-active" onclick="setStatus('')" title="Show all">
            <div class="cu-tile-ico amber"><i class="bi bi-bell"></i></div>
            <div>
                <div class="cu-tile-val">{{ $stats['total'] }}</div>
                <div class="cu-tile-lbl">Total</div>
            </div>
        </div>
        <div class="cu-rem-tile cu-tile-ok" onclick="setStatus('active')" title="Active only">
            <div class="cu-tile-ico green"><i class="bi bi-check2-circle"></i></div>
            <div>
                <div class="cu-tile-val">{{ $stats['active'] }}</div>
                <div class="cu-tile-lbl">Active</div>
            </div>
        </div>
        <div class="cu-rem-tile cu-tile-done" onclick="setStatus('completed')" title="Completed">
            <div class="cu-tile-ico blue"><i class="bi bi-check-circle-fill"></i></div>
            <div>
                <div class="cu-tile-val">{{ $stats['completed'] }}</div>
                <div class="cu-tile-lbl">Completed</div>
            </div>
        </div>
        <div class="cu-rem-tile cu-tile-late" onclick="setStatus('overdue')" title="Overdue">
            <div class="cu-tile-ico red"><i class="bi bi-exclamation-circle-fill"></i></div>
            <div>
                <div class="cu-tile-val">{{ $stats['overdue'] }}</div>
                <div class="cu-tile-lbl">Overdue</div>
            </div>
        </div>
        <div class="cu-rem-tile cu-tile-today" onclick="setStatus('due_today')" title="Due today">
            <div class="cu-tile-ico violet"><i class="bi bi-calendar-day-fill"></i></div>
            <div>
                <div class="cu-tile-val">{{ $stats['due_today'] }}</div>
                <div class="cu-tile-lbl">Due Today</div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="cu-rem-filter-bar">
        <input type="text" id="rem-search" class="cu-rem-search"
               placeholder="Search reminders&#x2026;" value="{{ request('search') }}">

        <select id="rem-category" class="cu-rem-select">
            <option value="all">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                    {{ $cat }}
                </option>
            @endforeach
        </select>

        <select id="rem-priority" class="cu-rem-select">
            <option value="all">All Priorities</option>
            @foreach($priorities as $val => $lbl)
                <option value="{{ $val }}" {{ request('priority') === $val ? 'selected' : '' }}>
                    {{ $lbl }}
                </option>
            @endforeach
        </select>

        <select id="rem-status" class="cu-rem-select">
            <option value="active"    {{ (request('status', 'active') === 'active')    ? 'selected' : '' }}>Active</option>
            <option value=""          {{ (request('status') === null)                   ? 'selected' : '' }}>All</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="overdue"   {{ request('status') === 'overdue'   ? 'selected' : '' }}>Overdue</option>
            <option value="due_today" {{ request('status') === 'due_today' ? 'selected' : '' }}>Due Today</option>
            <option value="due_soon"  {{ request('status') === 'due_soon'  ? 'selected' : '' }}>Due Soon</option>
        </select>

        <span class="cu-count-label">
            <span id="rem-count">{{ $reminders->count() }}</span> reminders
        </span>
    </div>

    {{-- Results container --}}
    <div id="rem-container">
        @include('reminders.partials.reminders-grid', ['reminders' => $reminders])
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    let debounce;

    function applyFilters() {
        const search   = document.getElementById('rem-search').value;
        const category = document.getElementById('rem-category').value;
        const priority = document.getElementById('rem-priority').value;
        const status   = document.getElementById('rem-status').value;

        const params = new URLSearchParams({ search, category, priority, status });

        fetch('/reminders?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            document.getElementById('rem-container').innerHTML = data.html;
            document.getElementById('rem-count').textContent   = data.count;
        })
        .catch(function (err) { console.error('Filter failed:', err); });
    }

    document.getElementById('rem-search').addEventListener('input', function () {
        clearTimeout(debounce);
        debounce = setTimeout(applyFilters, 350);
    });

    ['rem-category', 'rem-priority', 'rem-status'].forEach(function (id) {
        document.getElementById(id).addEventListener('change', applyFilters);
    });

    /* Clickable stat tiles */
    window.setStatus = function (val) {
        document.getElementById('rem-status').value = val;
        applyFilters();
    };

    /* Toggle complete */
    window.toggleComplete = function (reminderId) {
        fetch('/reminders/' + reminderId + '/toggle-complete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(function () { applyFilters(); })
        .catch(function (err) { console.error('Toggle failed:', err); });
    };

    /* Snooze */
    window.snoozeReminder = function (reminderId, minutes) {
        minutes = minutes || 15;
        fetch('/reminders/' + reminderId + '/snooze', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ minutes: minutes })
        })
        .then(function () { applyFilters(); })
        .catch(function (err) { console.error('Snooze failed:', err); });
    };
})();
</script>
@endpush
