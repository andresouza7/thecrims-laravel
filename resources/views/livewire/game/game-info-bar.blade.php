<div wire:poll.2s class="p-3 bg-gray-900 border border-gray-800 rounded-lg flex items-center justify-between text-sm">
    <div class="flex items-center gap-4">
        <span class="text-gray-400 font-medium">Tempo de Jogo:</span>
        <span class="bg-gray-800 text-amber-400 font-mono px-2.5 py-0.5 rounded border border-gray-700 font-semibold">Dia {{ $gameDay }}</span>
        <span class="bg-gray-800 text-sky-400 font-mono px-2.5 py-0.5 rounded border border-gray-700 font-semibold">{{ $gameTime }}</span>
    </div>
    <div class="text-xs text-gray-500 flex items-center gap-1">
        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
        <span>Tempo Real</span>
    </div>
</div>
