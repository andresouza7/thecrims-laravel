<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-gray-800 pb-3">
        <h2 class="text-2xl font-bold text-white">🏦 Banco da Cidade</h2>
        <div class="text-xs text-gray-400">Guarde seu dinheiro e evite roubos</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Depositar -->
        <div class="p-4 bg-gray-800/80 border border-gray-700 rounded-lg space-y-4">
            <h3 class="font-semibold text-emerald-400 text-lg">Depositar Grana</h3>
            <div class="space-y-3">
                <input type="number" min="1" wire:model="amount" placeholder="Quantidade para depositar"
                       class="w-full p-2.5 bg-gray-900 border border-gray-700 rounded text-gray-100 focus:outline-none focus:border-emerald-500">

                <button wire:click="deposit" wire:loading.attr="disabled"
                        class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white font-semibold rounded transition flex justify-center items-center gap-2">
                    <span wire:loading.remove wire:target="deposit">Depositar</span>
                    <span wire:loading wire:target="deposit" class="flex items-center gap-1">
                        <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Processando...
                    </span>
                </button>
            </div>
        </div>

        <!-- Sacar -->
        <div class="p-4 bg-gray-800/80 border border-gray-700 rounded-lg space-y-4">
            <h3 class="font-semibold text-rose-400 text-lg">Sacar Grana</h3>
            <div class="space-y-3">
                <input type="number" min="1" wire:model="amount" placeholder="Quantidade para sacar"
                       class="w-full p-2.5 bg-gray-900 border border-gray-700 rounded text-gray-100 focus:outline-none focus:border-rose-500">

                <button wire:click="withdraw" wire:loading.attr="disabled"
                        class="w-full py-2.5 bg-rose-600 hover:bg-rose-500 disabled:opacity-50 text-white font-semibold rounded transition flex justify-center items-center gap-2">
                    <span wire:loading.remove wire:target="withdraw">Sacar</span>
                    <span wire:loading wire:target="withdraw" class="flex items-center gap-1">
                        <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Processando...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
