<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->notes()->latest();

        // Handle search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Handle category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->byCategory($request->category);
        }

        // Handle favorites filter
        if ($request->filled('favorites') && $request->favorites == '1') {
            $query->favorites();
        }

        $notes = $query->get();

        // Get categories for filter
        $categories = Auth::user()->notes()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->sort();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('notes.partials.notes-grid', compact('notes'))->render(),
                'count' => $notes->count()
            ]);
        }

        return view('notes.index', compact('notes', 'categories'));
    }

    public function create()
    {
        $categories = Auth::user()->notes()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->sort();

        return view('notes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'is_favorite' => 'boolean',
            'date' => 'nullable|date',
            'time' => 'nullable|date_format:H:i',
        ]);

        $data = $request->all();

        // Process tags
        if ($request->filled('tags')) {
            $data['tags'] = array_map('trim', explode(',', $request->tags));
        }

        Auth::user()->notes()->create($data);

        return redirect()->route('notes.index')->with('success', 'Note created successfully.');
    }

    public function show(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }
        return view('notes.show', compact('note'));
    }

    public function edit(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Auth::user()->notes()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->sort();

        return view('notes.edit', compact('note', 'categories'));
    }

    public function update(Request $request, Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'is_favorite' => 'boolean',
            'date' => 'nullable|date',
            'time' => 'nullable|date_format:H:i',
        ]);

        $data = $request->all();

        // Process tags
        if ($request->filled('tags')) {
            $data['tags'] = array_map('trim', explode(',', $request->tags));
        }

        $note->update($data);

        return redirect()->route('notes.index')->with('success', 'Note updated successfully.');
    }

    public function destroy(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $note->delete();
        return redirect()->route('notes.index')->with('success', 'Note deleted successfully.');
    }

    public function toggleFavorite(Note $note): JsonResponse
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $note->update(['is_favorite' => !$note->is_favorite]);

        return response()->json([
            'success' => true,
            'is_favorite' => $note->is_favorite
        ]);
    }

    public function duplicate(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $newNote = $note->replicate();
        $newNote->title = $note->title . ' (Copy)';
        $newNote->save();

        return redirect()->route('notes.index')->with('success', 'Note duplicated successfully.');
    }
}
