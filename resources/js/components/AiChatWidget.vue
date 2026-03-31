<script setup lang="ts">
import { ref, nextTick } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const role = (page.props as any).auth?.user?.role ?? 'kader';
const aiEndpoint = role === 'admin' ? '/admin/ai-assistant' : (role === 'kades' ? '/kades/ai-assistant' : '/kader/ai-assistant');

const open = ref(false);
const input = ref('');
const loading = ref(false);
const messagesEnd = ref<HTMLElement | null>(null);

interface ChatMessage {
    role: 'user' | 'assistant';
    content: string;
    error?: boolean;
}

const messages = ref<ChatMessage[]>([
    {
        role: 'assistant',
        content: 'Halo! Saya Asisten SIDA-Dompas. Silakan tanya saya tentang data kependudukan, statistik, atau panduan penggunaan sistem.',
    },
]);

async function sendMessage() {
    const text = input.value.trim();
    if (!text || loading.value) return;

    messages.value.push({ role: 'user', content: text });
    input.value = '';
    loading.value = true;
    await scrollDown();

    try {
        const response = await fetch(aiEndpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content ?? '',
            },
            body: JSON.stringify({ message: text }),
        });

        const data = await response.json();
        messages.value.push({
            role: 'assistant',
            content: data.message ?? 'Maaf, terjadi kesalahan.',
            error: !data.success,
        });
    } catch {
        messages.value.push({
            role: 'assistant',
            content: 'Koneksi gagal. Silakan coba lagi.',
            error: true,
        });
    } finally {
        loading.value = false;
        await scrollDown();
    }
}

function handleEnter(e: KeyboardEvent) {
    if (!e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
}

async function scrollDown() {
    await nextTick();
    messagesEnd.value?.scrollIntoView({ behavior: 'smooth' });
}
</script>

<template>
    <!-- Floating button -->
    <div class="fixed bottom-6 right-6 z-50 print:hidden">
        <!-- Chat Panel -->
        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-4 scale-95"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 translate-y-4 scale-95"
        >
            <div
                v-if="open"
                class="mb-4 flex w-80 flex-col overflow-hidden rounded-2xl shadow-2xl ring-1 ring-gray-200"
                style="height: 420px; background: white;"
            >
                <!-- Header -->
                <div class="flex shrink-0 items-center justify-between px-4 py-3" style="background: linear-gradient(135deg, #064e3b, #047857);">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-white/20">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">AI Assistant</p>
                            <p class="text-[10px] text-emerald-200">SIDA-Dompas</p>
                        </div>
                    </div>
                    <button @click="open = false" class="rounded-lg p-1 text-emerald-200 transition hover:bg-white/10 hover:text-white">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Messages -->
                <div class="flex-1 space-y-3 overflow-y-auto p-4 text-sm">
                    <div v-for="(msg, i) in messages" :key="i" :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                        <div
                            :class="[
                                'max-w-[80%] rounded-2xl px-3.5 py-2.5 leading-relaxed',
                                msg.role === 'user'
                                    ? 'bg-emerald-600 text-white rounded-br-sm'
                                    : msg.error
                                        ? 'bg-red-50 text-red-700 ring-1 ring-red-200 rounded-bl-sm'
                                        : 'bg-gray-100 text-gray-700 rounded-bl-sm',
                            ]"
                        >
                            {{ msg.content }}
                        </div>
                    </div>

                    <!-- Typing indicator -->
                    <div v-if="loading" class="flex justify-start">
                        <div class="flex items-center gap-1.5 rounded-2xl rounded-bl-sm bg-gray-100 px-4 py-3">
                            <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400 [animation-delay:0ms]"></span>
                            <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400 [animation-delay:150ms]"></span>
                            <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400 [animation-delay:300ms]"></span>
                        </div>
                    </div>
                    <div ref="messagesEnd"></div>
                </div>

                <!-- Input -->
                <div class="shrink-0 border-t border-gray-100 p-3">
                    <div class="flex items-end gap-2">
                        <textarea
                            v-model="input"
                            @keydown.enter="handleEnter"
                            placeholder="Ketik pesan... (Enter untuk kirim)"
                            rows="1"
                            class="flex-1 resize-none rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition"
                            style="max-height: 80px;"
                        ></textarea>
                        <button
                            @click="sendMessage"
                            :disabled="loading || !input.trim()"
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm transition hover:bg-emerald-700 disabled:opacity-40"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- Toggle Button -->
        <button
            @click="open = !open"
            class="flex h-14 w-14 items-center justify-center rounded-2xl shadow-lg transition-all duration-300 hover:scale-110 hover:shadow-xl active:scale-95"
            :class="open ? 'bg-gray-700' : 'bg-gradient-to-br from-emerald-500 to-teal-600'"
            title="AI Assistant"
        >
            <Transition
                enter-active-class="transition-all duration-200"
                enter-from-class="opacity-0 rotate-90"
                leave-active-class="transition-all duration-200"
                leave-to-class="opacity-0 -rotate-90"
                mode="out-in"
            >
                <svg v-if="!open" key="open" class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                <svg v-else key="close" class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </Transition>
        </button>
    </div>
</template>
