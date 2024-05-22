<?php
namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        $files = Auth::user()->files()->latest()->get();
        return view('files.index', compact('files'));
    }

    public function create()
    {
        return view('files.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg,doc,docx,pdf,txt,html,css,js,php,java,c,cpp',
            'type' => 'required|string|in:project,docs,txt,code,image',
        ]);

        $path = $request->file('file')->store('uploads', 'public');

        Auth::user()->files()->create([
            'name' => $request->name,
            'path' => $path,
            'type' => $request->type,
        ]);

        return redirect()->route('files.index')->with('success', 'File uploaded successfully.');
    }

    public function edit(File $file)
    {
        return view('files.edit', compact('file'));
    }

    public function update(Request $request, File $file)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,doc,docx,pdf,txt,html,css,js,php,java,c,cpp',
            'type' => 'required|string|in:project,docs,txt,code,image',
        ]);

        $data = $request->only(['name', 'type']);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($file->path);
            $data['path'] = $request->file('file')->store('uploads', 'public');
        }

        $file->update($data);

        return redirect()->route('files.index')->with('success', 'File updated successfully.');
    }

    public function destroy(File $file)
    {
        Storage::disk('public')->delete($file->path);
        $file->delete();
        return redirect()->route('files.index')->with('success', 'File deleted successfully.');
    }
}
