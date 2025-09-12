<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class ReminderController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->reminders()->latest();

        // Handle search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Handle category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->byCategory($request->category);
        }

        // Handle priority filter
        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->byPriority($request->priority);
        }

        // Handle status filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->active();
                    break;
                case 'completed':
                    $query->completed();
                    break;
                case 'overdue':
                    $query->overdue();
                    break;
                case 'due_today':
                    $query->dueToday();
                    break;
                case 'due_soon':
                    $query->dueSoon();
                    break;
            }
        } else {
            // Default to active reminders
            $query->active();
        }

        // Handle view type (calendar or list)
        $view = $request->get('view', 'list');

        $reminders = $query->get();

        // Get categories and priorities for filters
        $categories = Auth::user()->reminders()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->sort();

        $priorities = [
            Reminder::PRIORITY_LOW => 'Low',
            Reminder::PRIORITY_MEDIUM => 'Medium',
            Reminder::PRIORITY_HIGH => 'High',
            Reminder::PRIORITY_URGENT => 'Urgent'
        ];

        // Statistics
        $stats = [
            'total' => Auth::user()->reminders()->count(),
            'active' => Auth::user()->reminders()->active()->count(),
            'completed' => Auth::user()->reminders()->completed()->count(),
            'overdue' => Auth::user()->reminders()->overdue()->count(),
            'due_today' => Auth::user()->reminders()->dueToday()->count(),
        ];

        if ($request->ajax()) {
            if ($view === 'calendar') {
                // Return calendar events format
                $events = $reminders->map(function ($reminder) {
                    return [
                        'id' => $reminder->id,
                        'title' => $reminder->title,
                        'start' => $reminder->formatted_date_time?->toISOString(),
                        'backgroundColor' => $reminder->priority_color,
                        'borderColor' => $reminder->priority_color,
                        'textColor' => '#fff',
                        'extendedProps' => [
                            'priority' => $reminder->priority,
                            'category' => $reminder->category,
                            'description' => $reminder->description,
                            'is_completed' => $reminder->is_completed,
                            'is_overdue' => $reminder->is_overdue,
                        ]
                    ];
                });

                return response()->json($events);
            } else {
                return response()->json([
                    'html' => view('reminders.partials.reminders-grid', compact('reminders'))->render(),
                    'count' => $reminders->count()
                ]);
            }
        }

        return view('reminders.index', compact('reminders', 'categories', 'priorities', 'stats', 'view'));
    }

    public function create()
    {
        $categories = Auth::user()->reminders()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->sort();

        $priorities = [
            Reminder::PRIORITY_LOW => 'Low',
            Reminder::PRIORITY_MEDIUM => 'Medium',
            Reminder::PRIORITY_HIGH => 'High',
            Reminder::PRIORITY_URGENT => 'Urgent'
        ];

        return view('reminders.create', compact('categories', 'priorities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
            'time' => 'nullable|date_format:H:i',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'is_recurring' => 'boolean',
            'recurrence_type' => 'required_if:is_recurring,1|in:none,daily,weekly,monthly,yearly',
            'recurrence_interval' => 'nullable|integer|min:1',
        ]);

        $data = $request->all();

        // Process tags
        if ($request->filled('tags')) {
            $data['tags'] = array_map('trim', explode(',', $request->tags));
        }

        // Set default recurrence values
        if (!$request->is_recurring) {
            $data['recurrence_type'] = Reminder::RECURRENCE_NONE;
            $data['recurrence_interval'] = 1;
        }

        Auth::user()->reminders()->create($data);

        return redirect()->route('reminders.index')->with('success', 'Reminder created successfully.');
    }

    public function show(Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }

        return view('reminders.show', compact('reminder'));
    }

    public function edit(Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Auth::user()->reminders()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->sort();

        $priorities = [
            Reminder::PRIORITY_LOW => 'Low',
            Reminder::PRIORITY_MEDIUM => 'Medium',
            Reminder::PRIORITY_HIGH => 'High',
            Reminder::PRIORITY_URGENT => 'Urgent'
        ];

        return view('reminders.edit', compact('reminder', 'categories', 'priorities'));
    }

    public function update(Request $request, Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
            'time' => 'nullable|date_format:H:i',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'is_recurring' => 'boolean',
            'recurrence_type' => 'required_if:is_recurring,1|in:none,daily,weekly,monthly,yearly',
            'recurrence_interval' => 'nullable|integer|min:1',
        ]);

        $data = $request->all();

        // Process tags
        if ($request->filled('tags')) {
            $data['tags'] = array_map('trim', explode(',', $request->tags));
        }

        // Set default recurrence values
        if (!$request->is_recurring) {
            $data['recurrence_type'] = Reminder::RECURRENCE_NONE;
            $data['recurrence_interval'] = 1;
        }

        $reminder->update($data);

        return redirect()->route('reminders.index')->with('success', 'Reminder updated successfully.');
    }

    public function destroy(Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }

        $reminder->delete();
        return redirect()->route('reminders.index')->with('success', 'Reminder deleted successfully.');
    }

    public function toggleComplete(Reminder $reminder): \Illuminate\Http\JsonResponse
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }

        if ($reminder->is_completed) {
            $reminder->update([
                'is_completed' => false,
                'completed_at' => null
            ]);
        } else {
            $reminder->markAsCompleted();
        }

        return response()->json([
            'success' => true,
            'is_completed' => $reminder->fresh()->is_completed,
            'completed_at' => $reminder->fresh()->completed_at?->format('M j, Y g:i A')
        ]);
    }

    public function snooze(Request $request, Reminder $reminder): \Illuminate\Http\JsonResponse
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'minutes' => 'required|integer|min:1|max:1440' // Max 24 hours
        ]);

        $reminder->snooze($request->minutes);

        return response()->json([
            'success' => true,
            'snooze_until' => $reminder->fresh()->snooze_until?->format('M j, Y g:i A')
        ]);
    }

    public function duplicate(Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }

        $newReminder = $reminder->replicate([
            'is_completed',
            'completed_at',
            'notification_sent',
            'snooze_until'
        ]);
        $newReminder->title = $reminder->title . ' (Copy)';
        $newReminder->is_completed = false;
        $newReminder->completed_at = null;
        $newReminder->notification_sent = false;
        $newReminder->snooze_until = null;
        $newReminder->save();

        return redirect()->route('reminders.index')->with('success', 'Reminder duplicated successfully.');
    }

    public function calendar()
    {
        $reminders = Auth::user()->reminders()->active()->get();

        $events = $reminders->map(function ($reminder) {
            return [
                'id' => $reminder->id,
                'title' => $reminder->title,
                'start' => $reminder->formatted_date_time?->toISOString(),
                'backgroundColor' => $reminder->priority_color,
                'borderColor' => $reminder->priority_color,
                'textColor' => '#fff',
                'extendedProps' => [
                    'priority' => $reminder->priority,
                    'category' => $reminder->category,
                    'description' => $reminder->description,
                    'is_overdue' => $reminder->is_overdue,
                ]
            ];
        });

        return view('reminders.calendar', compact('events'));
    }
}
