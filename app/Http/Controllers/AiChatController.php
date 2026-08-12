<?php

namespace App\Http\Controllers;

use App\Models\AiConversation;
use App\Models\AiMessage;
use App\Models\File;
use App\Models\Note;
use App\Models\Project;
use App\Models\Reminder;
use App\Models\Routine;
use App\Models\Task;
use App\Services\AiProviderService;
use App\Services\LinaFallbackBrain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AiChatController extends Controller
{
    protected AiProviderService $ai;

    public function __construct(AiProviderService $ai)
    {
        $this->ai = $ai;
    }

    public function index()
    {
        $user = Auth::user();
        $resolved = $this->ai->resolve($user);
        $enabledMap = $this->ai->enabledMap($user);
        $providers = $this->ai->allProviders();
        return view('ai.index', compact('resolved', 'enabledMap', 'providers'));
    }

    /* ── Status endpoint for frontend ── */
    public function status()
    {
        $user = Auth::user();
        return response()->json([
            'resolved' => $this->ai->resolve($user),
            'enabled'  => $this->ai->enabledMap($user),
            'providers'=> collect($this->ai->allProviders())->map(fn($c) => [
                'label' => $c['label'],
                'models'=> $c['models'],
                'default_model' => $c['default_model'],
            ]),
        ]);
    }

    /* ── Conversation CRUD ── */

    public function conversations()
    {
        $convs = AiConversation::where('user_id', Auth::id())
            ->orderByDesc('updated_at')
            ->get(['id', 'label', 'updated_at']);
        return response()->json($convs);
    }

    public function createConversation(Request $request)
    {
        $conv = AiConversation::create([
            'user_id' => Auth::id(),
            'label'   => $request->input('label', 'New Chat'),
        ]);
        return response()->json($conv);
    }

    public function getConversation(AiConversation $conversation)
    {
        abort_if($conversation->user_id !== Auth::id(), 403);
        $conversation->load('messages');
        return response()->json($conversation);
    }

    public function renameConversation(Request $request, AiConversation $conversation)
    {
        abort_if($conversation->user_id !== Auth::id(), 403);
        $request->validate(['label' => 'required|string|max:120']);
        $conversation->update(['label' => $request->label]);
        return response()->json(['ok' => true]);
    }

    public function deleteConversation(AiConversation $conversation)
    {
        abort_if($conversation->user_id !== Auth::id(), 403);
        $conversation->delete();
        return response()->json(['ok' => true]);
    }

    public function clearConversation(AiConversation $conversation)
    {
        abort_if($conversation->user_id !== Auth::id(), 403);
        $conversation->messages()->delete();
        $conversation->update(['label' => 'New Chat']);
        return response()->json(['ok' => true]);
    }

    /* ── Non-streaming chat ── */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'history' => 'nullable|array|max:40',
            'history.*.role'    => 'required|in:user,assistant',
            'history.*.content' => 'required|string|max:4000',
        ]);

        $user = Auth::user();
        $resolved = $this->ai->resolve($user);

        if (!$resolved) {
            return response()->json(['reply' => 'No AI provider is configured. Go to AI Settings and add an API key for OpenAI, Gemini, Claude, DeepSeek or Meta.'], 200);
        }

        try {
            $context = $this->buildContext($user);
        } catch (\Exception $e) {
            \Log::error('AI buildContext error', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            $context = '(Could not load user data)';
        }

        $messages = $this->buildMessages($user, $context, $request->input('history', []), $request->message);

        try {
            $reply = $this->callProviderSync($resolved, $messages);
            \Log::info('AI chat response', ['user_id' => $user->id, 'provider' => $resolved['provider'], 'model' => $resolved['model']]);
            return response()->json(['reply' => $reply, 'model' => $resolved['model'], 'provider' => $resolved['provider']]);
        } catch (\Exception $e) {
            \Log::error('AI chat failed', ['provider' => $resolved['provider'], 'model' => $resolved['model'], 'error' => $e->getMessage()]);
            return response()->json(['reply' => 'AI error: ' . $e->getMessage()], 200);
        }
    }

    /* ── Streaming ── */
    public function stream(Request $request)
    {
        $request->validate([
            'message'         => 'required|string|max:2000',
            'conversation_id' => 'nullable|integer',
            'history'         => 'nullable|array|max:40',
            'history.*.role'    => 'required|in:user,assistant',
            'history.*.content' => 'required|string|max:4000',
        ]);

        $user = Auth::user();
        $resolved = $this->ai->resolve($user);

        // Resolve or create conversation
        $convId = $request->input('conversation_id');
        if ($convId) {
            $conversation = AiConversation::where('id', $convId)->where('user_id', $user->id)->first();
        }
        if (empty($conversation)) {
            $conversation = AiConversation::create(['user_id' => $user->id, 'label' => 'New Chat']);
        }

        // Save user message
        AiMessage::create([
            'conversation_id' => $conversation->id,
            'role'            => 'user',
            'content'         => $request->message,
        ]);
        if ($conversation->messages()->count() === 1) {
            $conversation->update(['label' => mb_substr($request->message, 0, 60)]);
        }

        // No provider -> offline fallback
        if (!$resolved) {
            $fallbackText = (new LinaFallbackBrain($user, $request->message))->respond();
            $offlineConvId = $conversation->id;
            $sseFlush = $this->sseFlushClosure();
            return response()->stream(function () use ($sseFlush, $offlineConvId, $fallbackText) {
                echo "data: " . json_encode(['model' => 'lina-offline', 'provider' => 'offline', 'conversation_id' => $offlineConvId]) . "\n\n";
                $sseFlush();
                foreach (str_split($fallbackText, 4) as $chunk) {
                    echo "data: " . json_encode(['choices' => [['delta' => ['content' => $chunk]]]]) . "\n\n";
                    $sseFlush();
                    usleep(14000);
                }
                try {
                    AiMessage::create(['conversation_id' => $offlineConvId, 'role' => 'assistant', 'content' => $fallbackText, 'model' => 'lina-offline']);
                    AiConversation::where('id', $offlineConvId)->touch();
                } catch (\Exception $e) {}
                echo "data: [DONE]\n\n";
                $sseFlush();
            }, 200, ['Content-Type' => 'text/event-stream', 'Cache-Control' => 'no-cache', 'X-Accel-Buffering' => 'no', 'Connection' => 'keep-alive']);
        }

        try {
            $context = $this->buildContext($user);
        } catch (\Exception $e) {
            \Log::error('AI stream buildContext error', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            $context = '(Could not load user data)';
        }

        $messages = $this->buildMessages($user, $context, $request->input('history', []), $request->message);

        // For OpenAI-compatible providers, do true streaming. For Gemini/Anthropic, do sync then chunk.
        if (($resolved['type'] ?? 'openai') === 'openai') {
            return $this->streamOpenAi($resolved, $messages, $conversation);
        }

        // Non-OpenAI: sync call then simulate streaming
        try {
            $fullText = $this->callProviderSync($resolved, $messages);
        } catch (\Exception $e) {
            \Log::error('AI stream sync failed', ['provider' => $resolved['provider'], 'error' => $e->getMessage()]);
            $fullText = "AI error: " . $e->getMessage();
        }

        $conversationId = $conversation->id;
        $model = $resolved['model'];
        $provider = $resolved['provider'];
        $sseFlush = $this->sseFlushClosure();

        return response()->stream(function () use ($sseFlush, $conversationId, $fullText, $model, $provider) {
            echo "data: " . json_encode(['model' => $model, 'provider' => $provider, 'conversation_id' => $conversationId]) . "\n\n";
            $sseFlush();
            foreach (str_split($fullText, 5) as $chunk) {
                echo "data: " . json_encode(['choices' => [['delta' => ['content' => $chunk]]]]) . "\n\n";
                $sseFlush();
                usleep(12000);
            }
            try {
                AiMessage::create(['conversation_id' => $conversationId, 'role' => 'assistant', 'content' => $fullText, 'model' => $model]);
                AiConversation::where('id', $conversationId)->touch();
            } catch (\Exception $e) {}
            echo "data: [DONE]\n\n";
            $sseFlush();
        }, 200, ['Content-Type' => 'text/event-stream', 'Cache-Control' => 'no-cache', 'X-Accel-Buffering' => 'no', 'Connection' => 'keep-alive']);
    }

    /* ── Provider dispatch (sync) ── */
    private function callProviderSync(array $resolved, array $messages): string
    {
        $provider = $resolved['provider'];
        $type = $resolved['type'] ?? 'openai';
        $key = $resolved['key'];
        $model = $resolved['model'];
        $cfg = $resolved['config'];

        if ($type === 'openai') {
            return $this->callOpenAiSync($key, $cfg['base_url'], $messages, $model);
        }
        if ($type === 'gemini') {
            return $this->callGeminiSync($key, $cfg['base_url'], $messages, $model);
        }
        if ($type === 'anthropic') {
            return $this->callAnthropicSync($key, $cfg['base_url'], $messages, $model);
        }
        throw new \Exception("Unknown provider type: {$type}");
    }

    private function callOpenAiSync(string $key, string $baseUrl, array $messages, string $model): string
    {
        $payload = $this->ai->openAiPayload($messages, $model, false);
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $key,
            'Content-Type'  => 'application/json',
        ])->withOptions(['verify' => false])->timeout(60)->post($baseUrl, $payload);

        if ($response->failed()) {
            $msg = $response->json('error.message') ?? $response->body() ?: "HTTP {$response->status()}";
            throw new \Exception($msg);
        }
        $text = trim($response->json('choices.0.message.content') ?? '');
        if ($text === '') throw new \Exception('Empty response from provider');
        return $text;
    }

    private function callGeminiSync(string $key, string $baseUrl, array $messages, string $model): string
    {
        [$contents, $systemText] = $this->ai->toGeminiContents($messages);

        $body = [
            'contents' => $contents,
            'generationConfig' => [
                'maxOutputTokens' => 2048,
                'temperature' => 0.7,
            ],
        ];
        if ($systemText) {
            $body['systemInstruction'] = ['parts' => [['text' => $systemText]]];
        }

        $url = rtrim($baseUrl, '/') . '/' . $model . ':generateContent?key=' . $key;

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->withOptions(['verify' => false])->timeout(60)->post($url, $body);

        if ($response->failed()) {
            $msg = $response->json('error.message') ?? $response->body() ?: "HTTP {$response->status()}";
            throw new \Exception($msg);
        }
        $text = $response->json('candidates.0.content.parts.0.text');
        if (!$text) throw new \Exception('Empty response from Gemini');
        return trim($text);
    }

    private function callAnthropicSync(string $key, string $baseUrl, array $messages, string $model): string
    {
        [$system, $anthropicMessages] = $this->ai->toAnthropicMessages($messages);

        $payload = [
            'model' => $model,
            'max_tokens' => 2048,
            'temperature' => 0.7,
            'messages' => $anthropicMessages,
        ];
        if ($system) $payload['system'] = $system;

        $response = Http::withHeaders([
            'x-api-key' => $key,
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ])->withOptions(['verify' => false])->timeout(60)->post($baseUrl, $payload);

        if ($response->failed()) {
            $msg = $response->json('error.message') ?? $response->json('error') ?? $response->body() ?: "HTTP {$response->status()}";
            if (is_array($msg)) $msg = json_encode($msg);
            throw new \Exception($msg);
        }
        $blocks = $response->json('content');
        $text = '';
        if (is_array($blocks)) {
            foreach ($blocks as $b) {
                if (($b['type'] ?? '') === 'text') $text .= $b['text'] ?? '';
            }
        }
        if (!$text) throw new \Exception('Empty response from Claude');
        return trim($text);
    }

    /* ── OpenAI streaming ── */
    private function streamOpenAi(array $resolved, array $messages, AiConversation $conversation)
    {
        $key = $resolved['key'];
        $cfg = $resolved['config'];
        $model = $resolved['model'];
        $provider = $resolved['provider'];

        $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 60]);
        $response = null;

        try {
            $response = $client->post($cfg['base_url'], [
                'http_errors' => false,
                'headers' => [
                    'Authorization' => 'Bearer ' . $key,
                    'Content-Type'  => 'application/json',
                ],
                'json' => $this->ai->openAiPayload($messages, $model, true),
                'stream' => true,
            ]);
            $status = $response->getStatusCode();
            if ($status !== 200) {
                throw new \Exception("Provider returned HTTP {$status}: " . (string) $response->getBody());
            }
        } catch (\Exception $e) {
            // Fall back to sync + chunk
            \Log::warning('AI stream openai failed, falling back to sync', ['provider' => $provider, 'error' => $e->getMessage()]);
            try {
                $fullText = $this->callOpenAiSync($key, $cfg['base_url'], $messages, $model);
            } catch (\Exception $e2) {
                $fullText = "AI error: " . $e2->getMessage();
            }
            $conversationId = $conversation->id;
            $sseFlush = $this->sseFlushClosure();
            return response()->stream(function () use ($sseFlush, $conversationId, $fullText, $model, $provider) {
                echo "data: " . json_encode(['model' => $model, 'provider' => $provider, 'conversation_id' => $conversationId]) . "\n\n";
                $sseFlush();
                foreach (str_split($fullText, 5) as $chunk) {
                    echo "data: " . json_encode(['choices' => [['delta' => ['content' => $chunk]]]]) . "\n\n";
                    $sseFlush();
                    usleep(12000);
                }
                try {
                    AiMessage::create(['conversation_id' => $conversationId, 'role' => 'assistant', 'content' => $fullText, 'model' => $model]);
                    AiConversation::where('id', $conversationId)->touch();
                } catch (\Exception $e) {}
                echo "data: [DONE]\n\n";
                $sseFlush();
            }, 200, ['Content-Type' => 'text/event-stream', 'Cache-Control' => 'no-cache', 'X-Accel-Buffering' => 'no', 'Connection' => 'keep-alive']);
        }

        $body = $response->getBody();
        $conversationId = $conversation->id;
        $userId = Auth::id();
        $sseFlush = $this->sseFlushClosure();

        return response()->stream(function () use ($body, $model, $provider, $userId, $conversationId, $sseFlush) {
            echo "data: " . json_encode(['model' => $model, 'provider' => $provider, 'conversation_id' => $conversationId]) . "\n\n";
            $sseFlush();
            $buffer = '';
            $accumulatedText = '';
            try {
                while (!$body->eof()) {
                    $chunk = $body->read(256);
                    $buffer .= $chunk;
                    while (($pos = strpos($buffer, "\n")) !== false) {
                        $line = substr($buffer, 0, $pos);
                        $buffer = substr($buffer, $pos + 1);
                        $line = trim($line);
                        if ($line === '') continue;
                        if (!str_starts_with($line, 'data: ')) continue;
                        $data = substr($line, 6);
                        if ($data === '[DONE]') {
                            if ($accumulatedText) {
                                AiMessage::create(['conversation_id' => $conversationId, 'role' => 'assistant', 'content' => $accumulatedText, 'model' => $model]);
                                AiConversation::where('id', $conversationId)->touch();
                            }
                            echo "data: [DONE]\n\n";
                            $sseFlush();
                            return;
                        }
                        $decoded = json_decode($data, true);
                        $token = $decoded['choices'][0]['delta']['content'] ?? '';
                        if ($token) $accumulatedText .= $token;
                        echo "data: {$data}\n\n";
                        $sseFlush();
                    }
                }
            } catch (\Exception $e) {
                \Log::error('AI stream read error', ['model' => $model, 'provider' => $provider, 'user_id' => $userId, 'error' => $e->getMessage()]);
                echo "data: " . json_encode(['error' => 'Stream interrupted.']) . "\n\n";
                $sseFlush();
            }
            // Persist if we exited without [DONE]
            if ($accumulatedText) {
                try {
                    $exists = AiMessage::where('conversation_id', $conversationId)->where('role', 'assistant')->where('content', $accumulatedText)->exists();
                    if (!$exists) {
                        AiMessage::create(['conversation_id' => $conversationId, 'role' => 'assistant', 'content' => $accumulatedText, 'model' => $model]);
                        AiConversation::where('id', $conversationId)->touch();
                    }
                } catch (\Exception $e) {}
            }
            echo "data: [DONE]\n\n";
            $sseFlush();
        }, 200, ['Content-Type' => 'text/event-stream', 'Cache-Control' => 'no-cache', 'X-Accel-Buffering' => 'no', 'Connection' => 'keep-alive']);
    }

    /* ── Helpers ── */
    private function buildMessages($user, string $context, array $history, string $newMessage): array
    {
        $today = now()->format('l, F j, Y');
        $creatorName = $user->name;
        $systemPrompt = <<<PROMPT
You are Lina, a smart personal AI assistant built into this Task Manager app by {$creatorName}.
If asked your name, say your name is Lina. If asked who created or built you, say you were created by {$creatorName}.
Today is {$today}.

You can help the user with:
- Their workspace data: tasks, projects, notes, reminders, routines, and files (full data provided below)
- Coding, programming, software development, and any technical / technology questions
- General knowledge

Guidelines:
- Use markdown formatting — bullet points, code blocks, bold headings where helpful
- For code, always use fenced code blocks with the language specified
- For workspace data, only refer to what is in the context below — do not invent data
- Be concise and practical

--- USER WORKSPACE DATA ---
{$context}
--- END WORKSPACE DATA ---
PROMPT;
        $messages = [['role' => 'system', 'content' => $this->cleanUtf8($systemPrompt)]];
        foreach ($history as $turn) {
            $messages[] = ['role' => $turn['role'], 'content' => $this->cleanUtf8($turn['content'])];
        }
        $messages[] = ['role' => 'user', 'content' => $this->cleanUtf8($newMessage)];
        return $messages;
    }

    private function sseFlushClosure(): callable
    {
        return function () { if (ob_get_level() > 0) ob_flush(); flush(); };
    }

    private function cleanUtf8(string $str): string
    {
        $clean = mb_convert_encoding($str, 'UTF-8', 'UTF-8');
        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $clean);
    }

    private function buildContext($user): string
    {
        $projects = Project::where('user_id', $user->id)->get(['name', 'status', 'end_date', 'budget']);
        $projectLines = $projects->map(fn($p) => "- {$p->name} (status: {$p->status}" . ($p->end_date ? ", due: {$p->end_date->format('Y-m-d')}" : '') . ")")->join("\n");

        $tasks = Task::where('user_id', $user->id)->with('project:id,name')->get(['title', 'status', 'priority', 'due_date', 'project_id']);
        $taskLines = $tasks->map(fn($t) => "- [{$t->status}] {$t->title} (priority: {$t->priority}" . ($t->due_date ? ", due: {$t->due_date}" : '') . ($t->project ? ", project: {$t->project->name}" : '') . ")")->join("\n");

        $notes = Note::where('user_id', $user->id)->get(['title', 'content', 'tags']);
        $noteLines = $notes->map(function ($n) {
            $tags = is_array($n->tags) ? implode(', ', $n->tags) : ($n->tags ?? '');
            return "- {$n->title}" . ($tags ? " [tags: {$tags}]" : '') . ": " . strip_tags(substr($n->content ?? '', 0, 120));
        })->join("\n");

        $reminders = Reminder::where('user_id', $user->id)->get(['title', 'date', 'time', 'priority', 'is_completed', 'tags']);
        $reminderLines = $reminders->map(function ($r) {
            $tags = is_array($r->tags) ? implode(', ', $r->tags) : ($r->tags ?? '');
            $status = $r->is_completed ? 'done' : 'pending';
            $when = $r->date ? $r->date->format('Y-m-d') . ($r->time ? " {$r->time}" : '') : '';
            return "- [{$status}] {$r->title}" . ($when ? " at {$when}" : '') . ($tags ? " [tags: {$tags}]" : '');
        })->join("\n");

        $routines = Routine::where('user_id', $user->id)->get(['title', 'frequency']);
        $routineLines = $routines->map(fn($r) => "- {$r->title} ({$r->frequency})")->join("\n");

        $files = File::where('user_id', $user->id)->get(['name', 'type']);
        $fileLines = $files->map(fn($f) => "- {$f->name} (type: {$f->type})")->join("\n");

        return implode("\n\n", array_filter([
            $projects->count()  ? "PROJECTS ({$projects->count()}):\n{$projectLines}"    : null,
            $tasks->count()     ? "TASKS ({$tasks->count()}):\n{$taskLines}"              : null,
            $notes->count()     ? "NOTES ({$notes->count()}):\n{$noteLines}"              : null,
            $reminders->count() ? "REMINDERS ({$reminders->count()}):\n{$reminderLines}"  : null,
            $routines->count()  ? "ROUTINES ({$routines->count()}):\n{$routineLines}"     : null,
            $files->count()     ? "FILES ({$files->count()}):\n{$fileLines}"              : null,
        ]));
    }
}
