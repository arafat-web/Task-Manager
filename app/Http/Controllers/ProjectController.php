<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Auth::user()->projects()->withCount(['tasks as to_do_tasks' => function ($query) {
            $query->where('status', 'to_do');
        }, 'tasks as in_progress_tasks' => function ($query) {
            $query->where('status', 'in_progress');
        }, 'tasks as completed_tasks' => function ($query) {
            $query->where('status', 'completed');
        }])->get();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:not_started,in_progress,completed',
            'budget' => 'nullable|numeric',
        ]);

        Auth::user()->projects()->create($request->all());

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $teamMembers = $project->users()->get();
        $users = User::all();
        return view('projects.show', compact('project', 'teamMembers', 'users'));
    }
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:not_started,in_progress,completed',
            'budget' => 'nullable|numeric',
        ]);

        $project->update($request->all());

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    public function addMember(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
        ]);
       
        $project = Project::find($request->project_id);
        $project->teamProjects()->attach($request->user_id);
        return redirect()->back()->with('success', 'User added successfully.');
    }
}
