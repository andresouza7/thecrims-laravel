<div class="space-y-6 max-w-xl mx-auto">
    <div class="border-b border-gray-800 pb-3 text-center">
        <h2 class="text-2xl font-bold text-red-400">🏥 Hospital Central</h2>
        <p class="text-xs text-gray-400">Recupere sua saúde após batalhas intensas</p>
    </div>

    <div class="p-6 bg-gray-800 border border-gray-700 rounded-lg space-y-4 text-center">
        <p class="text-sm text-gray-300">
            Sua saúde chegou a zero em combate ou overdose. Fique em repouso até ser liberado pelo médico.
        </p>

        <button wire:click="release" wire:loading.attr="disabled"
                class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded transition text-sm">
            🩺 Receber Alta Médica
        </button>
    </div>
</div>
