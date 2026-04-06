{{-- AI Chat Widget --}}
<style>
/* ── AI Chat Widget ── */
#aiChatBtn {
    position: fixed; bottom: 24px; right: 24px; z-index: 9998;
    width: 52px; height: 52px; border-radius: 50%;
    background: linear-gradient(135deg, #6366f1 0%, #7c3aed 100%);
    border: none; color: #fff; font-size: 22px;
    box-shadow: 0 4px 20px rgba(99,102,241,.45);
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    transition: transform .2s, box-shadow .2s;
}
#aiChatBtn:hover { transform: scale(1.1); box-shadow: 0 6px 28px rgba(99,102,241,.55); }
#aiChatBtn .ai-ping {
    position: absolute; top: 2px; right: 2px;
    width: 12px; height: 12px; border-radius: 50%; background: #10b981;
    border: 2px solid #fff;
}

#aiChatPanel {
    position: fixed; bottom: 88px; right: 24px; z-index: 9999;
    width: 370px; max-height: 560px;
    background: #fff; border-radius: 16px;
    box-shadow: 0 12px 48px rgba(0,0,0,.18);
    display: flex; flex-direction: column;
    border: 1px solid #e5e7eb;
    transform: scale(.92) translateY(12px);
    opacity: 0; pointer-events: none;
    transition: transform .22s cubic-bezier(.34,1.56,.64,1), opacity .18s;
}
#aiChatPanel.open {
    transform: scale(1) translateY(0);
    opacity: 1; pointer-events: all;
}

.ai-panel-head {
    background: linear-gradient(135deg, #6366f1 0%, #7c3aed 100%);
    border-radius: 16px 16px 0 0; padding: 14px 16px;
    display: flex; align-items: center; gap: 10px; flex-shrink: 0;
}
.ai-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: rgba(255,255,255,.2); display: flex; align-items: center;
    justify-content: center; font-size: 18px; color: #fff; flex-shrink: 0;
}
.ai-panel-title { color: #fff; font-weight: 700; font-size: 14px; line-height: 1.2; }
.ai-panel-sub   { color: rgba(255,255,255,.75); font-size: 11px; }
.ai-close {
    margin-left: auto; background: rgba(255,255,255,.18); border: none;
    color: #fff; width: 28px; height: 28px; border-radius: 8px;
    cursor: pointer; font-size: 16px; display: flex; align-items: center; justify-content: center;
    transition: background .15s;
}
.ai-close:hover { background: rgba(255,255,255,.32); }

.ai-messages {
    flex: 1; overflow-y: auto; padding: 14px;
    display: flex; flex-direction: column; gap: 10px;
    scroll-behavior: smooth;
}
.ai-messages::-webkit-scrollbar { width: 4px; }
.ai-messages::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }

.ai-msg {
    max-width: 86%; line-height: 1.5; font-size: 13px;
    padding: 9px 13px; border-radius: 14px; word-break: break-word;
    white-space: pre-wrap;
}
.ai-msg.bot {
    background: #f3f4f6; color: #1f2937; border-bottom-left-radius: 4px; align-self: flex-start;
}
.ai-msg.user {
    background: linear-gradient(135deg, #6366f1, #7c3aed); color: #fff;
    border-bottom-right-radius: 4px; align-self: flex-end;
}
.ai-msg.typing {
    background: #f3f4f6; align-self: flex-start; padding: 12px 16px;
}
.ai-dots span {
    display: inline-block; width: 7px; height: 7px; border-radius: 50%;
    background: #9ca3af; margin: 0 2px;
    animation: aiBounce .9s infinite ease-in-out;
}
.ai-dots span:nth-child(2) { animation-delay: .15s; }
.ai-dots span:nth-child(3) { animation-delay: .3s; }
@keyframes aiBounce { 0%,80%,100%{transform:translateY(0)} 40%{transform:translateY(-6px)} }

.ai-panel-foot {
    padding: 10px 12px; border-top: 1px solid #f0f1f3; display: flex; gap: 8px; flex-shrink: 0;
}
#aiInput {
    flex: 1; border: 1px solid #e5e7eb; border-radius: 10px;
    padding: 8px 12px; font-size: 13px; outline: none; resize: none;
    color: #1f2937; line-height: 1.4; max-height: 100px;
    transition: border-color .15s;
}
#aiInput:focus { border-color: #7c3aed; }
#aiSend {
    width: 38px; height: 38px; border-radius: 10px;
    background: linear-gradient(135deg, #6366f1, #7c3aed);
    border: none; color: #fff; font-size: 16px;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    transition: opacity .15s; flex-shrink: 0;
}
#aiSend:disabled { opacity: .55; cursor: not-allowed; }

.ai-suggestions {
    padding: 0 14px 10px; display: flex; flex-wrap: wrap; gap: 6px; flex-shrink: 0;
}
.ai-chip {
    background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 20px;
    padding: 4px 10px; font-size: 11px; color: #6b7280; cursor: pointer;
    transition: all .15s; white-space: nowrap;
}
.ai-chip:hover { background: #ede9fe; border-color: #c4b5fd; color: #7c3aed; }

@media (max-width: 480px) {
    #aiChatPanel { width: calc(100vw - 32px); right: 16px; bottom: 80px; }
    #aiChatBtn   { right: 16px; bottom: 16px; }
}
</style>

{{-- Toggle Button --}}
<button id="aiChatBtn" title="Ask AI about your tasks" onclick="toggleAiChat()">
    <i class="bi bi-stars"></i>
    <span class="ai-ping"></span>
</button>

{{-- Chat Panel --}}
<div id="aiChatPanel">
    <div class="ai-panel-head">
        <div class="ai-avatar"><i class="bi bi-robot"></i></div>
        <div>
            <div class="ai-panel-title">AI Assistant</div>
            <div class="ai-panel-sub">Knows your tasks, projects & more</div>
        </div>
        <button class="ai-close" onclick="toggleAiChat()"><i class="bi bi-x"></i></button>
    </div>

    <div class="ai-messages" id="aiMessages">
        <div class="ai-msg bot">👋 Hi! I'm your AI assistant. Ask me anything about your tasks, projects, reminders, or notes!</div>
    </div>

    <div class="ai-suggestions" id="aiSuggestions">
        <span class="ai-chip" onclick="askChip(this)">What tasks are due today?</span>
        <span class="ai-chip" onclick="askChip(this)">Show my high priority tasks</span>
        <span class="ai-chip" onclick="askChip(this)">Summarize my projects</span>
        <span class="ai-chip" onclick="askChip(this)">Any overdue reminders?</span>
    </div>

    <div class="ai-panel-foot">
        <textarea id="aiInput" placeholder="Ask anything about your workspace…" rows="1"></textarea>
        <button id="aiSend" onclick="sendAiMessage()" title="Send"><i class="bi bi-send-fill"></i></button>
    </div>
</div>

<script>
(function () {
    const panel     = document.getElementById('aiChatPanel');
    const messages  = document.getElementById('aiMessages');
    const input     = document.getElementById('aiInput');
    const sendBtn   = document.getElementById('aiSend');
    const sugg      = document.getElementById('aiSuggestions');
    let   isOpen    = false;
    let   isBusy    = false;

    window.toggleAiChat = function () {
        isOpen = !isOpen;
        panel.classList.toggle('open', isOpen);
        if (isOpen) setTimeout(() => input.focus(), 220);
    };

    window.askChip = function (el) {
        input.value = el.textContent;
        sendAiMessage();
    };

    window.sendAiMessage = async function () {
        const text = input.value.trim();
        if (!text || isBusy) return;

        appendMsg(text, 'user');
        input.value = '';
        autoResize();
        sugg.style.display = 'none';

        const typingEl = appendTyping();
        isBusy = true;
        sendBtn.disabled = true;

        try {
            const res = await fetch('{{ route("ai.chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ message: text }),
            });
            const data = await res.json();
            typingEl.remove();
            appendMsg(data.reply || 'No response.', 'bot');
        } catch (e) {
            typingEl.remove();
            appendMsg('Connection error. Please try again.', 'bot');
        } finally {
            isBusy = false;
            sendBtn.disabled = false;
            input.focus();
        }
    };

    function appendMsg(text, role) {
        const div = document.createElement('div');
        div.className = `ai-msg ${role}`;
        div.textContent = text;
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
        return div;
    }

    function appendTyping() {
        const div = document.createElement('div');
        div.className = 'ai-msg typing';
        div.innerHTML = '<div class="ai-dots"><span></span><span></span><span></span></div>';
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
        return div;
    }

    function autoResize() {
        input.style.height = 'auto';
        input.style.height = Math.min(input.scrollHeight, 100) + 'px';
    }

    input.addEventListener('input', autoResize);
    input.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendAiMessage(); }
    });
})();
</script>
