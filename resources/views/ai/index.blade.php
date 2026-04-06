@extends('layouts.app')

@section('title', 'Lina AI')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>if(typeof marked!=='undefined') marked.setOptions({ breaks: true, gfm: true });</script>

<style>
/* ── Override layout shell for full-height chat ── */
main {
    padding: 0 !important;
    overflow: hidden !important;
    display: flex !important;
    flex-direction: column !important;
}
footer { display: none !important; }
.topnav { display: none !important; }

/* ── Page layout ── */
.lina-page {
    display: flex; flex: 1; overflow: hidden; height: 100%;
}

/* ── Left: conversations panel (future multi-chat) ── */
.lina-sidebar {
    width: 240px; flex-shrink: 0;
    border-right: 1px solid var(--gray-200);
    background: var(--gray-50);
    display: flex; flex-direction: column;
    overflow: hidden;
}
.lina-sidebar-head {
    padding: 18px 16px 12px;
    border-bottom: 1px solid var(--gray-200);
}
.lina-sidebar-head h2 {
    font-size: 13px; font-weight: 700;
    color: var(--gray-700); margin: 0 0 10px;
    text-transform: uppercase; letter-spacing: .5px;
}
.lina-new-btn {
    width: 100%; padding: 8px 12px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    border: none; border-radius: 10px; color: #fff;
    font-size: 13px; font-weight: 600; cursor: pointer;
    display: flex; align-items: center; gap: 6px;
    transition: opacity .15s;
}
.lina-new-btn:hover { opacity: .9; }
.lina-conv-list {
    flex: 1; overflow-y: auto; padding: 10px 8px;
    display: flex; flex-direction: column; gap: 2px;
}
.lina-conv-list::-webkit-scrollbar { width: 4px; }
.lina-conv-list::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 4px; }
.lina-conv-item {
    padding: 9px 10px; border-radius: 8px;
    cursor: pointer; display: flex; align-items: center;
    gap: 8px; font-size: 12.5px; color: var(--gray-600);
    transition: background .12s; position: relative;
}
.lina-conv-item:hover { background: var(--gray-200); color: var(--gray-800); }
.lina-conv-item.active {
    background: #ede9fe; color: #5b21b6; font-weight: 600;
}
.lina-conv-item i { font-size: 13px; flex-shrink: 0; color: #7c3aed; }
.lina-conv-label {
    flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.lina-conv-del {
    opacity: 0; background: none; border: none; cursor: pointer;
    color: var(--gray-400); font-size: 12px; padding: 2px 4px; border-radius: 4px;
    transition: all .12s;
}
.lina-conv-item:hover .lina-conv-del { opacity: 1; }
.lina-conv-del:hover { background: #fef2f2; color: #ef4444; }

/* ── Right: main chat ── */
.lina-main {
    flex: 1; display: flex; flex-direction: column; overflow: hidden;
}

/* ── Chat header ── */
.lina-head {
    flex-shrink: 0; padding: 16px 24px;
    background: #fff; border-bottom: 1px solid var(--gray-200);
    display: flex; align-items: center; gap: 14px;
}
.lina-head-avatar {
    width: 44px; height: 44px; border-radius: 14px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: #fff; flex-shrink: 0;
}
.lina-head-title { font-size: 17px; font-weight: 800; color: var(--gray-900); }
.lina-head-status {
    display: flex; align-items: center; gap: 5px;
    font-size: 11.5px; color: var(--gray-500); margin-top: 1px;
}
.lina-status-dot {
    width: 7px; height: 7px; border-radius: 50%; background: #10b981;
    animation: linaPulse 2s infinite;
}
@keyframes linaPulse { 0%,100%{opacity:1} 50%{opacity:.4} }
.lina-head-right { margin-left: auto; display: flex; gap: 8px; }
.lina-icon-btn {
    width: 36px; height: 36px; border-radius: 10px;
    border: 1px solid var(--gray-200); background: #fff;
    color: var(--gray-500); font-size: 15px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all .15s;
}
.lina-icon-btn:hover { background: var(--gray-100); color: var(--gray-700); border-color: var(--gray-300); }
#linaModelPill { display: none; }

/* ── Messages ── */
.lina-messages {
    flex: 1; overflow-y: auto; padding: 24px;
    display: flex; flex-direction: column; gap: 16px;
    scroll-behavior: smooth; background: var(--gray-25);
}
.lina-messages::-webkit-scrollbar { width: 5px; }
.lina-messages::-webkit-scrollbar-thumb { background: var(--gray-200); border-radius: 4px; }

/* Empty / welcome */
.lina-welcome {
    display: flex; flex-direction: column; align-items: center;
    justify-content: center; flex: 1; text-align: center;
    padding: 40px 20px; gap: 14px;
}
.lina-welcome-icon {
    width: 80px; height: 80px; border-radius: 24px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    display: flex; align-items: center; justify-content: center;
    font-size: 38px; color: #fff; margin-bottom: 6px;
    box-shadow: 0 8px 24px rgba(99,102,241,.3);
}
.lina-welcome h3 { font-size: 22px; font-weight: 800; color: var(--gray-900); margin: 0; }
.lina-welcome p { font-size: 14px; color: var(--gray-500); margin: 0; max-width: 380px; line-height: 1.6; }
.lina-welcome-chips {
    display: flex; flex-wrap: wrap; gap: 8px; justify-content: center;
    margin-top: 8px;
}

/* Message bubbles */
.lina-msg-wrap { display: flex; flex-direction: column; gap: 4px; }
.lina-msg-wrap.user { align-items: flex-end; }
.lina-msg-wrap.bot  { align-items: flex-start; }

.lina-msg-meta {
    display: flex; align-items: center; gap: 8px;
    font-size: 11px; color: var(--gray-400);
}
.lina-model-tag {
    background: #ede9fe; color: #5b21b6;
    border-radius: 12px; padding: 2px 8px; font-size: 10.5px; font-weight: 600;
}

.lina-msg {
    max-width: 72%; font-size: 14px; line-height: 1.7;
    padding: 12px 16px; border-radius: 16px; word-break: break-word;
}
.lina-msg.user {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: #fff; border-bottom-right-radius: 4px;
    box-shadow: 0 2px 8px rgba(99,102,241,.25);
}
.lina-msg.bot {
    background: #fff; color: var(--gray-800);
    border-bottom-left-radius: 4px;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
}
.lina-msg.bot p { margin: 0 0 8px; }
.lina-msg.bot p:last-child { margin: 0; }
.lina-msg.bot ul, .lina-msg.bot ol { margin: 4px 0 8px 20px; padding: 0; }
.lina-msg.bot li { margin-bottom: 3px; }
.lina-msg.bot code {
    background: var(--gray-100); padding: 1px 6px; border-radius: 4px;
    font-size: 12.5px; font-family: 'Courier New', monospace;
}
.lina-msg.bot pre {
    background: #1e293b; color: #e2e8f0;
    border-radius: 10px; padding: 12px 14px;
    overflow-x: auto; margin: 8px 0; font-size: 12.5px;
}
.lina-msg.bot pre code { background: none; padding: 0; color: inherit; }
.lina-msg.bot strong { color: var(--gray-900); }
.lina-msg.bot h1,.lina-msg.bot h2,.lina-msg.bot h3 {
    font-size: 15px; font-weight: 700; margin: 8px 0 4px; color: var(--gray-900);
}

.lina-msg-actions { display: flex; gap: 4px; opacity: 0; transition: opacity .15s; }
.lina-msg-wrap:hover .lina-msg-actions { opacity: 1; }
.lina-copy-btn {
    background: #fff; border: 1px solid var(--gray-200); border-radius: 7px;
    padding: 3px 9px; font-size: 11.5px; color: var(--gray-500); cursor: pointer;
    display: flex; align-items: center; gap: 4px; transition: all .15s;
}
.lina-copy-btn:hover { background: var(--gray-100); color: var(--gray-700); }

/* Typing */
.lina-typing-wrap { display: flex; flex-direction: column; align-items: flex-start; gap: 4px; }
.lina-typing {
    background: #fff; border: 1px solid var(--gray-200); border-radius: 16px;
    border-bottom-left-radius: 4px; padding: 14px 18px;
    box-shadow: var(--shadow-sm);
}
.lina-dots span {
    display: inline-block; width: 8px; height: 8px; border-radius: 50%;
    background: var(--gray-400); margin: 0 2px;
    animation: linaBounce 1s infinite ease-in-out;
}
.lina-dots span:nth-child(2) { animation-delay: .18s; }
.lina-dots span:nth-child(3) { animation-delay: .36s; }
@keyframes linaBounce { 0%,80%,100%{transform:translateY(0)} 40%{transform:translateY(-7px)} }

/* ── Input area ── */
.lina-foot {
    flex-shrink: 0;
    padding: 12px 24px 18px;
    background: #fff;
    border-top: 1px solid var(--gray-100);
}
.lina-input-box {
    background: #fff;
    border: 1.5px solid var(--gray-200);
    border-radius: 18px;
    box-shadow: 0 2px 14px rgba(0,0,0,.06);
    transition: border-color .18s, box-shadow .18s;
    overflow: hidden;
}
.lina-input-box:focus-within {
    border-color: #6366f1;
    box-shadow: 0 2px 20px rgba(99,102,241,.14);
}
#linaInput {
    display: block; width: 100%;
    border: none; outline: none; resize: none;
    font-size: 14.5px; line-height: 1.65;
    color: var(--gray-800); background: transparent;
    padding: 14px 16px 4px;
    min-height: 52px; max-height: 180px;
    overflow-y: hidden;
    font-family: inherit;
}
#linaInput::placeholder { color: var(--gray-400); }
.lina-input-toolbar {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 6px 10px 10px;
}
.lina-input-hints {
    display: flex; align-items: center; gap: 10px;
    font-size: 11px; color: var(--gray-400);
}
.lina-input-hints kbd {
    background: var(--gray-50); border: 1px solid var(--gray-200);
    border-radius: 4px; padding: 1px 6px;
    font-size: 10.5px; font-family: inherit; color: var(--gray-500);
}
.lina-input-right {
    display: flex; align-items: center; gap: 8px;
}
#linaCharCount {
    font-size: 11px; color: var(--gray-300);
}
#linaCharCount.warn { color: #f59e0b; }
#linaCharCount.over { color: #ef4444; }
.lina-send-btn {
    width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    border: none; color: #fff; font-size: 15px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: opacity .15s, transform .12s;
}
.lina-send-btn:hover:not(:disabled) { opacity: .9; transform: scale(1.06); }
.lina-send-btn:disabled { opacity: .28; cursor: not-allowed; transform: none; }

/* Chips */
.lina-chip {
    background: #fff; border: 1px solid var(--gray-200); border-radius: 20px;
    padding: 7px 14px; font-size: 13px; color: var(--gray-600); cursor: pointer;
    transition: all .15s; white-space: nowrap;
    box-shadow: var(--shadow-sm);
}
.lina-chip:hover { background: #ede9fe; border-color: #c4b5fd; color: #5b21b6; }

/* Error */
.lina-error {
    text-align: center; font-size: 13px; color: #ef4444;
    padding: 8px 16px; background: #fef2f2;
    border-radius: 10px; border: 1px solid #fca5a5;
    margin: 0 auto; max-width: 400px;
}

@media (max-width: 768px) {
    .lina-sidebar { display: none; }
    .lina-msg { max-width: 90%; }
}
</style>
@endpush

@section('content')
<div class="lina-page">

    {{-- Left conversations sidebar --}}
    <div class="lina-sidebar">
        <div class="lina-sidebar-head">
            <h2>Conversations</h2>
            <button class="lina-new-btn" onclick="newConversation()">
                <i class="bi bi-plus-lg"></i> New Chat
            </button>
        </div>
        <div class="lina-conv-list" id="linaConvList"></div>
    </div>

    {{-- Main chat area --}}
    <div class="lina-main">

        {{-- Header --}}
        <div class="lina-head">
            <div class="lina-head-avatar"><i class="bi bi-stars"></i></div>
            <div>
                <div class="lina-head-title">Lina</div>
                <div class="lina-head-status">
                    <span class="lina-status-dot"></span>
                    <span>Online &mdash; knows your workspace data</span>
                </div>
            </div>
            <div class="lina-head-right"></div>
        </div>

        {{-- Messages --}}
        <div class="lina-messages" id="linaMessages">
            <div class="lina-welcome" id="linaWelcome">
                <div class="lina-welcome-icon"><i class="bi bi-stars"></i></div>
                <h3>Hi, I'm Lina!</h3>
                <p>I'm your AI assistant. I have full access to your tasks, projects, notes, reminders, and routines. Ask me anything.</p>
                <div class="lina-welcome-chips">
                    <span class="lina-chip" onclick="askChip(this)">What tasks are due today?</span>
                    <span class="lina-chip" onclick="askChip(this)">Show high priority tasks</span>
                    <span class="lina-chip" onclick="askChip(this)">Summarize my projects</span>
                    <span class="lina-chip" onclick="askChip(this)">Any overdue reminders?</span>
                    <span class="lina-chip" onclick="askChip(this)">What routines do I have?</span>
                    <span class="lina-chip" onclick="askChip(this)">Show my recent notes</span>
                </div>
            </div>
        </div>

        {{-- Input --}}
        <div class="lina-foot">
            <div class="lina-input-box">
                <textarea id="linaInput"
                    placeholder="Message Lina…"
                    rows="1" maxlength="2000"></textarea>
                <div class="lina-input-toolbar">
                    <div class="lina-input-hints">
                        <span><kbd>Enter</kbd> send</span>
                        <span><kbd>Shift+Enter</kbd> new line</span>
                    </div>
                    <div class="lina-input-right">
                        <span id="linaCharCount"></span>
                        <button class="lina-send-btn" id="linaSend" onclick="window.sendMessage()" title="Send">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    /* ── Config ── */
    const CHAT_URL    = "{{ route('ai.chat') }}";
    const CSRF        = "{{ csrf_token() }}";
    const STORAGE_KEY = 'lina_conversations_v1';
    const MAX_HISTORY = 20;
    const MAX_CHARS   = 2000;

    /* ── State ── */
    let conversations = {};  // { id: { id, label, messages: [] } }
    let activeId      = null;
    let isBusy        = false;

    /* ── DOM ── */
    const msgsEl    = document.getElementById('linaMessages');
    const welcome   = document.getElementById('linaWelcome');
    const input     = document.getElementById('linaInput');
    const sendBtn   = document.getElementById('linaSend');
    const charCount = document.getElementById('linaCharCount');
    const convList  = document.getElementById('linaConvList');

    /* ── Conversations management ── */
    function loadConversations() {
        try { conversations = JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}'); }
        catch { conversations = {}; }
        renderConvList();
        // Reopen last active
        const ids = Object.keys(conversations);
        if (ids.length) switchConversation(ids[ids.length - 1]);
    }

    function saveConversations() {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(conversations));
    }

    function renderConvList() {
        convList.innerHTML = '';
        const ids = Object.keys(conversations).reverse();
        if (!ids.length) {
            convList.innerHTML = '<div style="font-size:12px;color:var(--gray-400);padding:10px;text-align:center;">No conversations yet</div>';
            return;
        }
        ids.forEach(id => {
            const conv = conversations[id];
            const item = document.createElement('div');
            item.className = 'lina-conv-item' + (id === activeId ? ' active' : '');
            item.dataset.id = id;
            item.innerHTML = `
                <i class="bi bi-chat-left-text"></i>
                <span class="lina-conv-label">${escHtml(conv.label || 'New Chat')}</span>
                <button class="lina-conv-del" onclick="deleteConv('${id}', event)" title="Delete"><i class="bi bi-x"></i></button>
            `;
            item.addEventListener('click', () => switchConversation(id));
            convList.appendChild(item);
        });
    }

    window.newConversation = function () {
        const id = 'conv_' + Date.now();
        conversations[id] = { id, label: 'New Chat', messages: [] };
        saveConversations();
        renderConvList();
        switchConversation(id);
    };

    window.switchConversation = function (id) {
        activeId = id;
        renderConvList();
        renderMessages();
    };

    window.deleteConv = function (id, e) {
        e.stopPropagation();
        if (!confirm('Delete this conversation?')) return;
        delete conversations[id];
        saveConversations();
        const ids = Object.keys(conversations);
        if (ids.length) { switchConversation(ids[ids.length - 1]); }
        else { newConversation(); }
        renderConvList();
    };

    function activeConv() { return conversations[activeId] || null; }

    /* ── Messages rendering ── */
    function renderMessages() {
        msgsEl.innerHTML = '';
        const conv = activeConv();
        if (!conv || !conv.messages.length) {
            msgsEl.appendChild(welcome);
            return;
        }
        conv.messages.forEach(m => renderBubble(m.role, m.content, m.time, false));
        scrollBottom();
    }

    function renderBubble(role, content, time, animate) {
        const wrap = document.createElement('div');
        wrap.className = 'lina-msg-wrap ' + (role === 'user' ? 'user' : 'bot');
        if (animate) { wrap.style.opacity = '0'; wrap.style.transform = 'translateY(8px)'; wrap.style.transition = 'all .2s'; }

        // Meta
        const meta = document.createElement('div');
        meta.className = 'lina-msg-meta';
        const ts = document.createElement('span');
        ts.textContent = formatTime(time);
        meta.appendChild(ts);
        wrap.appendChild(meta);

        // Bubble
        const bubble = document.createElement('div');
        bubble.className = 'lina-msg ' + (role === 'user' ? 'user' : 'bot');
        if (role !== 'user' && typeof marked !== 'undefined') {
            bubble.innerHTML = marked.parse(content);
        } else {
            bubble.textContent = content;
        }
        wrap.appendChild(bubble);

        // Copy action
        const actions = document.createElement('div');
        actions.className = 'lina-msg-actions';
        const copyBtn = document.createElement('button');
        copyBtn.className = 'lina-copy-btn';
        copyBtn.innerHTML = '<i class="bi bi-clipboard"></i> Copy';
        copyBtn.onclick = () => copyText(content, copyBtn);
        actions.appendChild(copyBtn);
        wrap.appendChild(actions);

        msgsEl.appendChild(wrap);
        if (animate) requestAnimationFrame(() => { wrap.style.opacity = '1'; wrap.style.transform = 'translateY(0)'; });
        return wrap;
    }

    function appendTyping() {
        const wrap = document.createElement('div');
        wrap.className = 'lina-typing-wrap';
        const meta = document.createElement('div');
        meta.className = 'lina-msg-meta'; meta.textContent = 'Lina is thinking…';
        wrap.appendChild(meta);
        const t = document.createElement('div');
        t.className = 'lina-typing';
        t.innerHTML = '<div class="lina-dots"><span></span><span></span><span></span></div>';
        wrap.appendChild(t);
        msgsEl.appendChild(wrap);
        scrollBottom();
        return wrap;
    }

    function appendError(msg) {
        const div = document.createElement('div');
        div.className = 'lina-error';
        div.textContent = '⚠ ' + msg;
        msgsEl.appendChild(div);
        scrollBottom();
    }

    /* ── Send ── */
    window.sendMessage = async function () {
        const text = input.value.trim();
        if (!text || isBusy || text.length > MAX_CHARS) return;

        const conv = activeConv();
        if (!conv) return;

        // Remove welcome if present
        if (welcome.parentNode) welcome.parentNode.removeChild(welcome);

        const timestamp = new Date().toISOString();
        conv.messages.push({ role: 'user', content: text, time: timestamp });
        // Auto-label from first message
        if (conv.messages.length === 1) {
            conv.label = text.slice(0, 36) + (text.length > 36 ? '…' : '');
            renderConvList();
        }
        saveConversations();
        renderBubble('user', text, timestamp, true);
        scrollBottom();

        input.value = ''; autoResize(); updateCharCount();

        const typingEl = appendTyping();
        isBusy = true; sendBtn.disabled = true;

        const historyPayload = conv.messages.slice(0, -1).slice(-MAX_HISTORY).map(m => ({
            role: m.role === 'assistant' ? 'assistant' : 'user',
            content: m.content,
        }));

        try {
            const res = await fetch(CHAT_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ message: text, history: historyPayload }),
            });

            typingEl.remove();

            if (!res.ok) {
                appendError('Server error ' + res.status + '. Please try again.');
                conv.messages.pop(); saveConversations();
            } else {
                const data = await res.json();
                const reply = data.reply || 'No response.';
                const model = data.model || null;
                const ts    = new Date().toISOString();
                conv.messages.push({ role: 'assistant', content: reply, model, time: ts });
                saveConversations();
                renderBubble('assistant', reply, ts, true);
                scrollBottom();
            }
        } catch (e) {
            typingEl.remove();
            appendError('Network error. Check your connection.');
            conv.messages.pop(); saveConversations();
        } finally {
            isBusy = false; sendBtn.disabled = false; input.focus();
        }
    };

    window.askChip = function (el) {
        input.value = el.textContent.trim();
        autoResize(); updateCharCount(); sendMessage();
    };

    /* ── Toolbar actions ── */
    window.clearConversation = function () {
        const conv = activeConv();
        if (!conv || !conv.messages.length) return;
        if (!confirm('Clear this conversation?')) return;
        conv.messages = []; conv.label = 'New Chat';
        saveConversations(); renderConvList(); renderMessages();
    };

    window.exportChat = function () {
        const conv = activeConv();
        if (!conv || !conv.messages.length) return;
        const lines = conv.messages.map(m =>
            '[' + formatTime(m.time) + '] ' +
            (m.role === 'user' ? 'You' : 'Lina' + (m.model ? ' (' + m.model + ')' : '')) +
            ':\n' + m.content + '\n'
        );
        const blob = new Blob([lines.join('\n')], { type: 'text/plain' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'lina-chat-' + new Date().toISOString().slice(0,10) + '.txt';
        a.click();
    };

    /* ── Helpers ── */
    function scrollBottom() { setTimeout(() => msgsEl.scrollTop = msgsEl.scrollHeight, 30); }

    function formatTime(iso) {
        if (!iso) return '';
        try { return new Date(iso).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }); }
        catch { return ''; }
    }

    function copyText(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            btn.innerHTML = '<i class="bi bi-check2"></i> Copied!';
            setTimeout(() => btn.innerHTML = '<i class="bi bi-clipboard"></i> Copy', 1800);
        });
    }

    function escHtml(s) {
        return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    function autoResize() {
        input.style.height = 'auto';
        const h = Math.min(input.scrollHeight, 180);
        input.style.height = h + 'px';
        input.style.overflowY = input.scrollHeight > 180 ? 'auto' : 'hidden';
    }

    function updateCharCount() {
        const len = input.value.length;
        if (len === 0) { charCount.textContent = ''; charCount.className = ''; return; }
        charCount.textContent = len + ' / ' + MAX_CHARS;
        charCount.className = len > MAX_CHARS ? 'over' : len > MAX_CHARS * 0.85 ? 'warn' : '';
    }

    input.addEventListener('input', () => { autoResize(); updateCharCount(); });
    input.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); window.sendMessage(); }
    });

    /* ── Boot (must be last — window.* assignments above are not hoisted) ── */
    loadConversations();
    if (!activeId) window.newConversation();
    autoResize();
})();
</script>
@endpush
