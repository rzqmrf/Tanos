@extends('layouts.app')

@section('title', 'Tanos AI Copilot — Tanos ERP')

@section('content')
<style>
    #chat-feed::-webkit-scrollbar { display: none; }
    #chat-feed { -ms-overflow-style: none; scrollbar-width: none; }

    /* Container */
    .cop-wrap {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        display: flex;
        flex-direction: column;
        height: calc(100vh - 130px);
        min-height: 580px;
    }
    html.dark .cop-wrap { background: #0f172a; border-color: #1e293b; }

    /* Header */
    .cop-header {
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        padding: 14px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        flex-shrink: 0;
    }
    html.dark .cop-header { background: #0f172a; border-bottom-color: #1e293b; }

    .cop-title { font-size: 15px; font-weight: 700; color: #1e293b; }
    html.dark .cop-title { color: #f1f5f9; }

    .cop-sub { font-size: 10px; color: #94a3b8; margin-top: 1px; }
    html.dark .cop-sub { color: #475569; }

    /* Feed */
    .cop-feed {
        flex: 1;
        overflow-y: auto;
        padding: 20px 22px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    /* Input bar */
    .cop-bar {
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
        padding: 12px 22px;
        flex-shrink: 0;
    }
    html.dark .cop-bar { background: #0f172a; border-top-color: #1e293b; }

    .cop-input {
        flex: 1;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 12px;
        color: #1e293b;
        outline: none;
        font-family: inherit;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    html.dark .cop-input { background: #1e293b; border-color: #334155; color: #e2e8f0; }
    .cop-input::placeholder { color: #94a3b8; }
    html.dark .cop-input::placeholder { color: #475569; }
    .cop-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }

    .cop-send {
        width: 38px; height: 38px;
        background: #4f46e5;
        border: none; border-radius: 10px;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background 0.15s;
        flex-shrink: 0;
    }
    .cop-send:hover { background: #4338ca; }
    .cop-send:disabled { background: #e2e8f0; cursor: not-allowed; }
    html.dark .cop-send:disabled { background: #1e293b; }

    /* Avatars */
    .av-ai {
        width: 28px; height: 28px; border-radius: 8px; flex-shrink: 0;
        background: #eef2ff; border: 1px solid #c7d2fe; color: #6366f1;
        display: flex; align-items: center; justify-content: center;
    }
    html.dark .av-ai { background: rgba(79,70,229,0.15); border-color: rgba(99,102,241,0.3); color: #818cf8; }

    .av-user {
        width: 28px; height: 28px; border-radius: 8px; flex-shrink: 0;
        background: #f1f5f9; border: 1px solid #e2e8f0; color: #94a3b8;
        display: flex; align-items: center; justify-content: center;
    }
    html.dark .av-user { background: #1e293b; border-color: #334155; color: #475569; }

    /* Bubbles */
    .bub-user {
        background: #4f46e5;
        color: #ffffff;
        border-radius: 16px 16px 4px 16px;
        padding: 10px 14px;
        font-size: 12px;
        line-height: 1.6;
        max-width: 65%;
    }
    .bub-ai {
        background: #f1f5f9;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        border-radius: 16px 16px 16px 4px;
        padding: 10px 14px;
        font-size: 12px;
        line-height: 1.6;
        max-width: 85%;
    }
    html.dark .bub-ai { background: #1e293b; color: #e2e8f0; border-color: #334155; }

    /* AI table inside bubble */
    .bub-ai table { width: 100%; border-collapse: collapse; font-size: 11px; margin-top: 8px; }
    .bub-ai th { padding: 6px 10px; background: #e2e8f0; color: #64748b; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; border-bottom: 1px solid #cbd5e1; text-align: left; }
    .bub-ai td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; color: #334155; }
    .bub-ai tr:last-child td { border-bottom: none; }
    .bub-ai tr:hover td { background: #f8faff; }
    html.dark .bub-ai th { background: #0f172a; color: #94a3b8; border-bottom-color: #334155; }
    html.dark .bub-ai td { color: #cbd5e1; border-bottom-color: #1e293b; }
    html.dark .bub-ai tr:hover td { background: rgba(15,23,42,0.4); }

    /* Timestamp */
    .msg-ts { font-size: 10px; color: #cbd5e1; }
    html.dark .msg-ts { color: #334155; }

    /* Typing */
    .typing-wrap {
        background: #f1f5f9; border: 1px solid #e2e8f0;
        border-radius: 16px 16px 16px 4px;
        padding: 11px 14px;
        display: flex; align-items: center; gap: 5px;
    }
    html.dark .typing-wrap { background: #1e293b; border-color: #334155; }
    .typing-dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; }
    html.dark .typing-dot { background: #475569; }

    /* Prompt cards */
    .p-card {
        text-align: left; padding: 14px;
        background: #f8fafc; border: 1px solid #e2e8f0;
        border-radius: 12px; cursor: pointer;
        transition: all 0.15s; font-family: inherit;
    }
    .p-card:hover { background: #eef2ff; border-color: #c7d2fe; }
    html.dark .p-card { background: #1e293b; border-color: #334155; }
    html.dark .p-card:hover { background: rgba(79,70,229,0.12); border-color: #6366f1; }
    .p-card-title { font-size: 12px; font-weight: 600; color: #334155; margin-top: 8px; }
    html.dark .p-card-title { color: #e2e8f0; }
    .p-card-sub { font-size: 10px; color: #94a3b8; margin-top: 3px; }

    /* Welcome */
    .w-title { font-size: 16px; font-weight: 700; color: #1e293b; }
    html.dark .w-title { color: #f1f5f9; }
    .w-sub { font-size: 12px; color: #94a3b8; margin-top: 6px; max-width: 380px; line-height: 1.6; }

    /* Status */
    .dot-on { width: 7px; height: 7px; border-radius: 50%; background: #22c55e; display: inline-block; }
    .dot-off { width: 7px; height: 7px; border-radius: 50%; background: #f59e0b; display: inline-block; }
    .badge-on { font-size: 10px; font-weight: 600; color: #16a34a; }
    html.dark .badge-on { color: #4ade80; }
    .badge-off { font-size: 10px; font-weight: 600; color: #d97706; }
    html.dark .badge-off { color: #fbbf24; }

    /* Clear button */
    .btn-clear {
        font-size: 11px; font-weight: 600; color: #94a3b8;
        background: #ffffff; border: 1px solid #e2e8f0;
        border-radius: 8px; padding: 6px 12px;
        cursor: pointer; transition: all 0.15s;
        display: flex; align-items: center; gap: 5px;
        font-family: inherit;
    }
    .btn-clear:hover { color: #ef4444; border-color: #fecaca; background: #fff5f5; }
    html.dark .btn-clear { background: #0f172a; border-color: #1e293b; color: #475569; }
    html.dark .btn-clear:hover { color: #f87171; border-color: #450a0a; background: rgba(127,29,29,0.1); }

    /* Animations */
    @keyframes bubIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    .bub-in { animation: bubIn 0.2s ease-out; }
    @keyframes dbounce { 0%,80%,100% { transform: translateY(0); opacity: 0.4; } 40% { transform: translateY(-4px); opacity: 1; } }
    .d1 { animation: dbounce 1.2s ease-in-out infinite 0s; }
    .d2 { animation: dbounce 1.2s ease-in-out infinite 0.2s; }
    .d3 { animation: dbounce 1.2s ease-in-out infinite 0.4s; }
</style>

<div x-data="copilotChat()">
<div class="cop-wrap">

    {{-- HEADER --}}
    <div class="cop-header">
        <div style="display:flex;align-items:center;gap:12px;">
            <div class="av-ai" style="width:36px;height:36px;border-radius:10px;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px;">
                    <path d="M9.813 15.904L9 21l-.813-5.096L3 15l5.096-.813L9 9l.813 5.096L15 15l-5.187.904zM18.097 5.196L17.5 8l-.597-2.804L14 5l2.903-.597L17.5 2l.597 2.403L21 5l-2.903.196zM11.666 4.086l-.416 1.914-.416-1.914L9 3.5l1.834-.416.416-1.914.416 1.914L13.5 3.5l-1.834.586z"/>
                </svg>
            </div>
            <div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <span class="cop-title">Tanos AI Copilot</span>
                    <span style="padding:2px 7px;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;border-radius:5px;background:#eef2ff;color:#4f46e5;border:1px solid #c7d2fe;">Gemini 3.5</span>
                </div>
                <div style="display:flex;align-items:center;gap:5px;margin-top:3px;">
                    @if($hasKey)
                        <span class="dot-on"></span>
                        <span class="badge-on">Connected to Gemini</span>
                    @else
                        <span class="dot-off"></span>
                        <span class="badge-off">Simulation Mode</span>
                    @endif
                </div>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
            @if(!$hasKey)
                <a href="https://aistudio.google.com/" target="_blank" style="font-size:11px;font-weight:600;color:#d97706;text-decoration:none;">⚠ Pasang API Key</a>
            @endif
            <button @click="clearChat()" class="btn-clear">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:12px;height:12px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
                Hapus Chat
            </button>
        </div>
    </div>

    {{-- FEED --}}
    <div id="chat-feed" class="cop-feed">

        {{-- Welcome --}}
        <template x-if="messages.length === 0">
            <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;flex:1;text-align:center;gap:20px;padding:40px 16px;user-select:none;">
                <div class="av-ai" style="width:54px;height:54px;border-radius:16px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:26px;height:26px;">
                        <path d="M9.813 15.904L9 21l-.813-5.096L3 15l5.096-.813L9 9l.813 5.096L15 15l-5.187.904zM18.097 5.196L17.5 8l-.597-2.804L14 5l2.903-.597L17.5 2l.597 2.403L21 5l-2.903.196zM11.666 4.086l-.416 1.914-.416-1.914L9 3.5l1.834-.416.416-1.914.416 1.914L13.5 3.5l-1.834.586z"/>
                    </svg>
                </div>
                <div>
                    <div class="w-title">Halo! Saya Tanos Copilot</div>
                    <div class="w-sub" style="margin:6px auto 0;">Saya siap membantu Anda menganalisis data operasional Pelindo secara real-time. Pilih topik di bawah atau ketik pertanyaan Anda.</div>
                </div>
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:10px;width:100%;max-width:620px;">
                    <button @click="submitPrompt('Berapa project aktif bulan ini dan tampilkan project dengan budget terbesar')" class="p-card">
                        <div style="font-size:20px;">📊</div>
                        <div class="p-card-title">Proyek & Anggaran RAB</div>
                        <div class="p-card-sub">Project aktif & budget terbesar</div>
                    </button>
                    <button @click="submitPrompt('Berapa pegawai yang ditempatkan di tiap project dan berapa total payroll bulan ini?')" class="p-card">
                        <div style="font-size:20px;">👥</div>
                        <div class="p-card-title">Penempatan SDM & Payroll</div>
                        <div class="p-card-sub">Distribusi pegawai & total gaji</div>
                    </button>
                    <button @click="submitPrompt('Project mana yang memiliki jumlah pegawai terbanyak dan berapa total payroll-nya?')" class="p-card" style="grid-column: span 2;">
                        <div style="font-size:20px;">🔀</div>
                        <div class="p-card-title">Analisis Lintas Modul (Cross-Module Join)</div>
                        <div class="p-card-sub">Proyek dengan pegawai terbanyak + total THP payroll-nya</div>
                    </button>
                    <button @click="submitPrompt('Buatkan ringkasan kondisi project di regional jawa')" class="p-card" style="grid-column: span 2;">
                        <div style="font-size:20px;">📝</div>
                        <div class="p-card-title">Ringkasan Eksekutif Regional</div>
                        <div class="p-card-sub">Sintesis holistik proyek, pegawai, budget & billing Regional Jawa</div>
                    </button>
                </div>
            </div>
        </template>

        {{-- Messages --}}
        <template x-for="(msg, index) in messages" :key="index">
            <div class="bub-in">
                <div :style="msg.sender === 'user' ? 'text-align:right;margin-bottom:4px;' : 'text-align:left;margin-bottom:4px;'">
                    <span class="msg-ts" x-text="msg.time"></span>
                </div>

                {{-- User --}}
                <template x-if="msg.sender === 'user'">
                    <div style="display:flex;justify-content:flex-end;align-items:flex-end;gap:8px;">
                        <div class="bub-user">
                            <div style="white-space:pre-line;" x-html="formatMarkdown(msg.text)"></div>
                        </div>
                        <div class="av-user">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px;">
                                <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </template>

                {{-- AI --}}
                <template x-if="msg.sender === 'ai'">
                    <div style="display:flex;justify-content:flex-start;align-items:flex-end;gap:8px;">
                        <div class="av-ai">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px;">
                                <path d="M9.813 15.904L9 21l-.813-5.096L3 15l5.096-.813L9 9l.813 5.096L15 15l-5.187.904z"/>
                            </svg>
                        </div>
                        <div class="bub-ai">
                            <div style="overflow-x:auto;white-space:pre-line;" x-html="formatMarkdown(msg.text)"></div>
                        </div>
                    </div>
                </template>
            </div>
        </template>

        {{-- Typing --}}
        <template x-if="loading">
            <div style="display:flex;align-items:flex-end;gap:8px;">
                <div class="av-ai">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px;">
                        <path d="M9.813 15.904L9 21l-.813-5.096L3 15l5.096-.813L9 9l.813 5.096L15 15l-5.187.904z"/>
                    </svg>
                </div>
                <div class="typing-wrap">
                    <div class="typing-dot d1"></div>
                    <div class="typing-dot d2"></div>
                    <div class="typing-dot d3"></div>
                </div>
            </div>
        </template>
    </div>

    {{-- INPUT --}}
    <div class="cop-bar">
        <form @submit.prevent="submitChat()" style="display:flex;align-items:center;gap:10px;">
            <input type="text" class="cop-input" x-model="inputText" :disabled="loading" placeholder="Ketik pertanyaan Anda di sini...">
            <button type="submit" class="cop-send" :disabled="loading || !inputText.trim()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" style="width:15px;height:15px;">
                    <path d="M3.478 2.405a.75.75 0 0 0-.926.94l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.405Z"/>
                </svg>
            </button>
        </form>
        <p style="text-align:center;font-size:9px;color:#cbd5e1;margin-top:8px;letter-spacing:0.04em;">Powered by Google Gemini 3.5 Flash • Data dari Tanos ERP</p>
    </div>

</div>
</div>

<script>
/**
 * State & logic chat untuk Tanos AI Copilot (Alpine).
 * Nama method/property tidak diubah agar kompatibel dengan binding x-* di markup.
 */
function copilotChat() {
    return {
        // -- State --
        messages: [],
        inputText: '',
        loading: false,

        // -- Lifecycle --
        init() {
            const saved = localStorage.getItem('tanos_copilot_chat');
            if (saved) {
                try {
                    this.messages = JSON.parse(saved);
                } catch (e) {
                    this.messages = [];
                }
            }
        },

        clearChat() {
            this.messages = [];
            localStorage.removeItem('tanos_copilot_chat');
        },

        // -- Persistence --
        save() {
            localStorage.setItem('tanos_copilot_chat', JSON.stringify(this.messages));
        },

        // -- Helpers --
        now() {
            const d = new Date();
            return d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const el = document.getElementById('chat-feed');
                if (el) el.scrollTo({ top: el.scrollHeight, behavior: 'smooth' });
            });
        },

        isHtmlContent(text) {
            return /<\/?[a-z][\s\S]*>/i.test(text);
        },

        addMessage(sender, text, html) {
            this.messages.push({ sender, text, html: !!html, time: this.now() });
            this.save();
            this.scrollToBottom();
        },

        // -- Actions --
        submitPrompt(text) {
            this.inputText = text;
            this.$nextTick(() => this.submitChat());
        },

        submitChat() {
            const message = this.inputText.trim();
            if (!message || this.loading) return;

            this.addMessage('user', message, false);
            this.inputText = '';
            this.loading = true;
            this.scrollToBottom();

            fetch("{{ route('copilot.chat') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ message }),
            })
                .then(async r => {
                    const data = await r.json().catch(() => null);
                    if (!r.ok || !data) {
                        throw new Error((data && data.response) ? data.response : `HTTP Error ${r.status}`);
                    }
                    return data;
                })
                .then(data => {
                    const text = data.response || 'Maaf, tidak ada respon dari AI.';
                    this.addMessage('ai', text, this.isHtmlContent(text));
                })
                .catch(err => {
                    const errorMsg = (err && err.message) ? err.message : 'Gagal menghubungi API. Silakan coba lagi.';
                    this.addMessage('ai', '⚠️ ' + errorMsg, false);
                })
                .finally(() => {
                    this.loading = false;
                    this.scrollToBottom();
                });
        },

        // -- Rendering --
        formatMarkdown(text) {
            if (!text) return '';
            return text
                // **tebal**
                .replace(/\*\*([\s\S]*?)\*\*/g, '<strong style="font-weight:700;">$1</strong>')
                // *miring*
                .replace(/\*([^\*\n]+)\*/g, '<em>$1</em>')
                // `kode inline`
                .replace(/`(.*?)`/g, '<code style="padding:1px 6px;background:rgba(99,102,241,0.12);color:#6366f1;border-radius:4px;font-size:10px;font-family:monospace;">$1</code>')
                // # judul
                .replace(/^#{1,3}\s+(.*?)$/gm, '<div style="font-weight:700;font-size:13px;margin-top:8px;margin-bottom:4px;">$1</div>')
                // - bullet
                .replace(/^\s*[-*]\s+(.*?)$/gm, '<div style="display:flex;align-items:flex-start;gap:6px;margin:2px 0;"><span style="color:#6366f1;flex-shrink:0;">&#8226;</span><span>$1</span></div>');
        },
    };
}
</script>

@endsection
