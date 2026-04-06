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
        $context = $this->buildContext($user);
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
            ])->timeout(20)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'       => 'llama3-8b-8192',
                'messages'    => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user',   'content' => $request->message],
                ],
                'max_tokens'  => 512,
                'temperature' => 0.5,
            ]);
        } catch (\Exception $e) {
            return response()->json(['reply' => 'Could not reach the AI service. Please check your internet connection or try again later.'], 200);
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
        $noteLines = $notes->map(fn($n) =>
            "- {$n->title}" . ($n->tags ? " [tags: {$n->tags}]" : '') . ": " . strip_tags(substr($n->content ?? '', 0, 120))
        )->join("\n");

        // Reminders
        $reminders = Reminder::where('user_id', $user->id)->get(['title', 'remind_at', 'status']);
        $reminderLines = $reminders->map(fn($r) =>
            "- [{$r->status}] {$r->title}" . ($r->remind_at ? " at {$r->remind_at}" : '')
        )->join("\n");

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
