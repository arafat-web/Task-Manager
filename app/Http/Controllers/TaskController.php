<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Project $project = null)
    {
        $user = Auth::user();

        if ($project) {
            // Show tasks for a specific project
            $tasks = Task::where('user_id', $user->id)
                        ->where('project_id', $project->id)
                        ->with('project')
                        ->get()
                        ->groupBy('status');
        } else {
            // Show all tasks excluding those from completed projects
            $tasks = Task::where('user_id', $user->id)
                        ->whereHas('project', function ($query) {
                            $query->where('status', '!=', 'completed');
                        })
                        ->with('project')
                        ->get()
                        ->groupBy('status');
        }

        $projects = Project::all();
        $users = User::all();

        return view('tasks.index', compact('tasks', 'projects', 'users', 'project'));
    }

    public function create()
    {
        $projects = Project::all();
        $users = User::all();

        return view('tasks.create', compact('projects', 'users'));
    }

    public function store(Request $request, Project $project = null)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:to_do,in_progress,on_hold,in_review,completed',
            'estimated_hours' => 'nullable|numeric|min:0.5',
        ]);

        Task::create($request->all());

        // Redirect based on context
        if ($project) {
            return redirect()->route('projects.tasks.index', $project)->with('success', 'Task created successfully.');
        } else {
            return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
        }
    }

    public function show(Task $task)
    {
        $task->load('user', 'project', 'checklistItems');
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $projects = Project::all();
        $users = User::all();

        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:to_do,in_progress,on_hold,in_review,completed',
            'estimated_hours' => 'nullable|numeric|min:0.5',
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.show', $task->id)->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $task->status = $request->input('status');
        $task->save();

        return response()->json(['message' => 'Task status updated successfully.']);
    }
}
