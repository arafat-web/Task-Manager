<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Note;
use App\Models\Reminder;
use App\Models\Routine;
use App\Models\File;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Basic counts
        $tasksCount = $user->tasks()->count();
        $routinesCount = $user->routines()->count();
        $notesCount = $user->notes()->count();
        $remindersCount = $user->reminders()->count();
        $filesCount = $user->files()->count();
        $projectsCount = $user->projects()->count();

        // Recent items
        $recentTasks = $user->tasks()
            ->with('project')
            ->latest()
            ->take(5)
            ->get();

        $recentNotes = $user->notes()
            ->latest()
            ->take(5)
            ->get();

        // Today's routines (based on current day and time)
        $today = now()->format('l'); // Full day name (Monday, Tuesday, etc.)
        $todayRoutines = $user->routines()
            ->where('frequency', 'daily')
            ->whereJsonContains('days', strtolower($today))
            ->get();

        // Upcoming reminders
        $upcomingReminders = $user->reminders()
            ->where('date', '>=', now())
            ->orderBy('date')
            ->take(5)
            ->get();

        // Additional statistics for analytics
        $completedTasksThisWeek = $user->tasks()
            ->where('status', 'completed')
            ->whereDate('updated_at', '>=', now()->startOfWeek())
            ->count();

        $totalTasks = max($user->tasks()->count(), 1);
        $completedTasks = $user->tasks()->where('status', 'completed')->count();
        $completionRate = round(($completedTasks / $totalTasks) * 100);

        $activeProjects = $user->projects()
            ->where('status', 'in_progress')
            ->count();

        $overdueTasks = $user->tasks()
            ->where('due_date', '<', now())
            ->where('status', '!=', 'completed')
            ->count();

        // Task status distribution
        $taskStatusDistribution = [
            'to_do' => $user->tasks()->where('status', 'to_do')->count(),
            'in_progress' => $user->tasks()->where('status', 'in_progress')->count(),
            'completed' => $user->tasks()->where('status', 'completed')->count(),
        ];

        // Priority distribution (only non-completed tasks)
        $priorityDistribution = [
            'high' => $user->tasks()->where('priority', 'high')->where('status', '!=', 'completed')->count(),
            'medium' => $user->tasks()->where('priority', 'medium')->where('status', '!=', 'completed')->count(),
            'low' => $user->tasks()->where('priority', 'low')->where('status', '!=', 'completed')->count(),
        ];

        // Calculate priority percentages for progress bars
        $totalNonCompletedTasks = max($user->tasks()->where('status', '!=', 'completed')->count(), 1);
        $priorityPercentages = [
            'high' => round(($priorityDistribution['high'] / $totalNonCompletedTasks) * 100),
            'medium' => round(($priorityDistribution['medium'] / $totalNonCompletedTasks) * 100),
            'low' => round(($priorityDistribution['low'] / $totalNonCompletedTasks) * 100),
        ];

        return view('dashboard', compact(
            'tasksCount',
            'routinesCount',
            'notesCount',
            'remindersCount',
            'filesCount',
            'projectsCount',
            'recentTasks',
            'todayRoutines',
            'recentNotes',
            'upcomingReminders',
            'completedTasksThisWeek',
            'completionRate',
            'activeProjects',
            'overdueTasks',
            'taskStatusDistribution',
            'priorityDistribution',
            'priorityPercentages'
        ));
    }

    /**
     * Get productivity data for charts (AJAX endpoint).
     */
    public function getProductivityData(Request $request)
    {
        $user = Auth::user();
        $period = $request->get('period', 'week');

        $data = [];
        $labels = [];

        switch ($period) {
            case 'week':
                $startDate = now()->startOfWeek();
                for ($i = 0; $i < 7; $i++) {
                    $date = $startDate->copy()->addDays($i);
                    $labels[] = $date->format('M j');
                    $data[] = $user->tasks()
                        ->where('status', 'completed')
                        ->whereDate('updated_at', $date)
                        ->count();
                }
                break;

            case 'month':
                $startDate = now()->startOfMonth();
                $daysInMonth = now()->daysInMonth;
                for ($i = 0; $i < $daysInMonth; $i++) {
                    $date = $startDate->copy()->addDays($i);
                    $labels[] = $date->format('j');
                    $data[] = $user->tasks()
                        ->where('status', 'completed')
                        ->whereDate('updated_at', $date)
                        ->count();
                }
                break;

            case 'year':
                for ($i = 0; $i < 12; $i++) {
                    $date = now()->startOfYear()->addMonths($i);
                    $labels[] = $date->format('M');
                    $data[] = $user->tasks()
                        ->where('status', 'completed')
                        ->whereYear('updated_at', now()->year)
                        ->whereMonth('updated_at', $date->month)
                        ->count();
                }
                break;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
}
