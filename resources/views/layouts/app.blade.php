<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'The Crims') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
    @livewireStyles
</head>

<body class="bg-gray-950 text-gray-100 min-h-screen font-sans">
    <div class="flex flex-col gap-4 p-4 max-w-7xl mx-auto">

        <!-- Persistent User Header -->
        <livewire:game.user-header />

        <!-- Persistent Game Info Bar -->
        <livewire:game.game-info-bar />

        <!-- Persistent Navigation Bar -->
        <livewire:game.navigation />


        <!-- Dynamic Main Content Slot (Loaded via wire:navigate) -->
        <main class="p-4 bg-gray-900/60 border border-gray-800 rounded-lg shadow">
            {{ $slot }}
        </main>
    </div>

    <!-- Temporary Debug Panel Sidebar -->
    <livewire:game.debug-panel />

    <!-- Sistema Global de Toasts (Canto Inferior Esquerdo) -->
    <div x-data="toastManager()"
        class="fixed bottom-5 left-5 z-50 flex flex-col-reverse gap-2.5 max-w-sm w-full pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 -translate-x-full scale-95"
                x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                x-transition:leave-end="opacity-0 -translate-x-full scale-95"
                class="pointer-events-auto flex items-start gap-3 p-3.5 rounded-xl border shadow-2xl backdrop-blur-md text-xs font-medium text-white shadow-black/80 transition-all"
                :class="{
                    'bg-gray-900/95 border-emerald-500/70 text-emerald-100 shadow-emerald-950/40': toast
                        .type === 'success',
                    'bg-gray-900/95 border-rose-500/70 text-rose-100 shadow-rose-950/40': toast.type === 'error',
                    'bg-gray-900/95 border-amber-500/70 text-amber-100 shadow-amber-950/40': toast.type === 'warning',
                    'bg-gray-900/95 border-sky-500/70 text-sky-100 shadow-sky-950/40': toast.type === 'info'
                }">
                <div class="text-base shrink-0 select-none">
                    <template x-if="toast.type === 'success'"><span>✅</span></template>
                    <template x-if="toast.type === 'error'"><span>⚠️</span></template>
                    <template x-if="toast.type === 'warning'"><span>⚡</span></template>
                    <template x-if="toast.type === 'info'"><span>ℹ️</span></template>
                </div>

                <div class="flex-1 pr-2 leading-relaxed break-words" x-text="toast.message"></div>

                <button @click="removeToast(toast.id)"
                    class="text-gray-400 hover:text-white transition text-sm font-bold shrink-0">
                    ✕
                </button>
            </div>
        </template>
    </div>

    <script>
        function toastManager() {
            return {
                toasts: [],
                addToast(toast) {
                    const message = toast.message || '';
                    if (!message) return;

                    const now = Date.now();
                    const isDuplicate = this.toasts.some(t => t.message === message && (now - t.createdAt) < 500);
                    if (isDuplicate) return;

                    const id = now + Math.random();
                    const type = toast.type || 'info';
                    const timeout = toast.timeout || 4500;

                    const item = {
                        id,
                        type,
                        message,
                        visible: true,
                        createdAt: now
                    };
                    this.toasts.push(item);

                    setTimeout(() => {
                        this.removeToast(id);
                    }, timeout);
                },
                removeToast(id) {
                    const index = this.toasts.findIndex(t => t.id === id);
                    if (index !== -1) {
                        this.toasts[index].visible = false;
                        setTimeout(() => {
                            this.toasts = this.toasts.filter(t => t.id !== id);
                        }, 250);
                    }
                },
                init() {
                    const handler = (detail) => {
                        if (!detail) return;
                        let payload = detail;

                        if (Array.isArray(payload) && payload[0]) {
                            payload = payload[0];
                        }

                        if (typeof payload === 'string') {
                            this.addToast({
                                type: 'info',
                                message: payload
                            });
                        } else if (payload && payload.message) {
                            this.addToast(payload);
                        }
                    };

                    window.addEventListener('toast', (e) => handler(e.detail));

                    @if (session()->has('message'))
                        handler({
                            type: 'success',
                            message: @js(session('message'))
                        });
                    @endif
                    @if (session()->has('success'))
                        handler({
                            type: 'success',
                            message: @js(session('success'))
                        });
                    @endif
                    @if (session()->has('error'))
                        handler({
                            type: 'error',
                            message: @js(session('error'))
                        });
                    @endif
                    @if (session()->has('warning'))
                        handler({
                            type: 'warning',
                            message: @js(session('warning'))
                        });
                    @endif
                    @if (session()->has('info'))
                        handler({
                            type: 'info',
                            message: @js(session('info'))
                        });
                    @endif
                    @if (session()->has('debug_msg'))
                        handler({
                            type: 'info',
                            message: @js(session('debug_msg'))
                        });
                    @endif
                }
            }
        }
    </script>

    @livewireScripts
</body>

</html>
