<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Project;
use App\Models\Reminder;
use App\Models\Routine;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AiChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $apiKey = config('services.groq.key');
        if (!$apiKey) {
            return response()->json(['reply' => 'AI assistant is not configured. Please add GROQ_API_KEY to your environment.'], 200);
        }

        $user    = Auth::user();

        try {
            $context = $this->buildContext($user);
        } catch (\Exception $e) {
            \Log::error('Groq buildContext error: ' . $e->getMessage());
            $context = '(Could not load user data)';
        }

        $today   = now()->format('l, F j, Y');

        $systemPrompt = <<<PROMPT
You are a helpful assistant built into a Task Manager app. Today is {$today}.
You have full access to the user's data below. Answer questions about their tasks, projects, notes, reminders, and routines clearly and concisely.
When listing items, use short bullet points. Do not make up data that isn't in the context.

--- USER DATA ---
{$context}
--- END USER DATA ---
PROMPT;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->withOptions(['verify' => false])->timeout(20)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'       => 'llama-3.1-8b-instant',
                'messages'    => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user',   'content' => $request->message],
                ],
                'max_tokens'  => 512,
                'temperature' => 0.5,
            ]);
        } catch (\Exception $e) {
            \Log::error('Groq AI error: ' . $e->getMessage());
            return response()->json(['reply' => 'Connection error: ' . $e->getMessage()], 200);
        }

        if ($response->failed()) {
            $error = $response->json('error.message') ?? 'Unknown error';
            return response()->json(['reply' => "AI service error: {$error}"], 200);
        }

        $reply = $response->json('choices.0.message.content') ?? 'No response received.';

        return response()->json(['reply' => trim($reply)]);
    }

    private function buildContext($user): string
    {
        // Projects
        $projects = Project::where('user_id', $user->id)->get(['name', 'status', 'end_date', 'budget']);
        $projectLines = $projects->map(fn($p) =>
            "- {$p->name} (status: {$p->status}" . ($p->end_date ? ", due: {$p->end_date->format('Y-m-d')}" : '') . ")"
        )->join("\n");

        // Tasks
        $tasks = Task::where('user_id', $user->id)->with('project:id,name')->get(['title', 'status', 'priority', 'due_date', 'project_id']);
        $taskLines = $tasks->map(fn($t) =>
            "- [{$t->status}] {$t->title} (priority: {$t->priority}" .
            ($t->due_date ? ", due: {$t->due_date}" : '') .
            ($t->project ? ", project: {$t->project->name}" : '') . ")"
        )->join("\n");

        // Notes
        $notes = Note::where('user_id', $user->id)->get(['title', 'content', 'tags']);
        $noteLines = $notes->map(function ($n) {
            $tags = is_array($n->tags) ? implode(', ', $n->tags) : ($n->tags ?? '');
            return "- {$n->title}" . ($tags ? " [tags: {$tags}]" : '') . ": " . strip_tags(substr($n->content ?? '', 0, 120));
        })->join("\n");

        // Reminders
        $reminders = Reminder::where('user_id', $user->id)->get(['title', 'date', 'time', 'priority', 'is_completed', 'tags']);
        $reminderLines = $reminders->map(function ($r) {
            $tags = is_array($r->tags) ? implode(', ', $r->tags) : ($r->tags ?? '');
            $status = $r->is_completed ? 'done' : 'pending';
            $when = $r->date ? $r->date->format('Y-m-d') . ($r->time ? " {$r->time}" : '') : '';
            return "- [{$status}] {$r->title}" . ($when ? " at {$when}" : '') . ($tags ? " [tags: {$tags}]" : '');
        })->join("\n");

        // Routines
        $routines = Routine::where('user_id', $user->id)->get(['title', 'frequency']);
        $routineLines = $routines->map(fn($r) => "- {$r->title} ({$r->frequency})")->join("\n");

        return implode("\n\n", array_filter([
            $projects->count()  ? "PROJECTS ({$projects->count()}):\n{$projectLines}"    : null,
            $tasks->count()     ? "TASKS ({$tasks->count()}):\n{$taskLines}"              : null,
            $notes->count()     ? "NOTES ({$notes->count()}):\n{$noteLines}"              : null,
            $reminders->count() ? "REMINDERS ({$reminders->count()}):\n{$reminderLines}"  : null,
            $routines->count()  ? "ROUTINES ({$routines->count()}):\n{$routineLines}"     : null,
        ]));
    }
}
