<div class="space-y-6 max-w-xl mx-auto">
    <div class="border-b border-gray-800 pb-3 text-center">
        <h2 class="text-2xl font-bold text-rose-400">🔒 Prisão Estadual</h2>
        <p class="text-xs text-gray-400">Você foi pego pela polícia ou traído</p>
    </div>

    <div class="p-6 bg-gray-800 border border-gray-700 rounded-lg space-y-4 text-center">
        <p class="text-sm text-gray-300">
            Você está atrás das grades. Pode esperar o cumprimento da sua pena ou tentar subornar os guardas da prisão.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 pt-2">
            <button wire:click="bribe" wire:loading.attr="disabled"
                    class="w-full py-2.5 bg-amber-600 hover:bg-amber-500 text-white font-semibold rounded transition text-sm">
                💵 Subornar Guarda (Lanche)
            </button>

            <button wire:click="release" wire:loading.attr="disabled"
                    class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded transition text-sm">
                🔓 Solicitar Soltura (Se Cumprido)
            </button>
        </div>
    </div>
</div>
