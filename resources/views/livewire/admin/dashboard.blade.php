<div class="space-y-6 max-w-xl mx-auto">
    <div class="border-b border-gray-800 pb-3 text-center">
        <h2 class="text-2xl font-bold text-rose-400">⚡ Painel de Administração do Jogo</h2>
        <p class="text-xs text-gray-400">Controle global de rodadas e servidor</p>
    </div>

    <div class="p-6 bg-gray-800 border border-gray-700 rounded-lg space-y-4 text-center">
        <p class="text-sm text-gray-300">
            Reiniciar o estado do jogo e iniciar uma nova rodada para todos os jogadores ativos.
        </p>

        <button wire:click="createRound" wire:loading.attr="disabled"
                class="w-full py-3 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded shadow transition text-sm flex justify-center items-center gap-2">
            <span wire:loading.remove wire:target="createRound">🚀 Iniciar Novo Round</span>
            <span wire:loading wire:target="createRound" class="animate-spin">🌀 Reiniciando...</span>
        </button>
    </div>
</div>
