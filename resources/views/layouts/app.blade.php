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
        <!-- Messages/Flash Alerts -->
        @if (session()->has('message'))
            <div class="p-3 bg-emerald-900/60 border border-emerald-600 text-emerald-200 rounded text-sm">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('success'))
            <div class="p-3 bg-emerald-900/60 border border-emerald-600 text-emerald-200 rounded text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="p-3 bg-rose-900/60 border border-rose-600 text-rose-200 rounded text-sm">
                {{ session('error') }}
            </div>
        @endif

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

    @livewireScripts
</body>
</html>
