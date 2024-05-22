<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectFileController extends Controller
{public function index()
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
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx',
        ]);

        $filePath = $request->file('file')->store('project_files');

        $project->files()->create([
            'file_path' => $filePath,
        ]);

        return redirect()->route('projects.show', $project)->with('success', 'File uploaded successfully.');
    }

    public function destroy(ProjectFile $file)
    {
        Storage::delete($file->file_path);
        $file->delete();

        return back()->with('success', 'File deleted successfully.');
    }
}

