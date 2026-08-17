<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-gray-800 pb-3">
        <div>
            <h2 class="text-2xl font-bold text-white">🏦 Banco da Cidade</h2>
            <p class="text-xs text-gray-400">Guarde seu dinheiro em segurança e evite roubos e perdas</p>
        </div>
    </div>

    <!-- Destaque Exclusivo do Saldo no Banco -->
    <div class="p-6 bg-gradient-to-br from-gray-900 via-sky-950/40 to-gray-900 border border-sky-500/40 rounded-2xl shadow-xl shadow-sky-950/20 relative overflow-hidden">
        <div class="absolute -right-6 -bottom-6 opacity-10 text-sky-400 pointer-events-none">
            <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M4 10h16v10H4V10zm2 2v6h12v-6H6zm-2-8h16v4H4V4zm2 2v0h12V6H6v0z"/></svg>
        </div>

        <div class="flex justify-between items-center relative z-10">
            <div>
                <span class="text-xs font-bold text-sky-400 uppercase tracking-widest block mb-1">
                    💼 Saldo no Banco
                </span>
                <div class="text-4xl sm:text-5xl font-black text-white tracking-tight drop-shadow">
                    ${{ number_format($user->bank) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário Único de Transação -->
    <div class="p-6 bg-gray-900 border border-gray-800 rounded-xl space-y-5 shadow-xl">
        <h3 class="font-bold text-gray-200 text-base border-b border-gray-800 pb-2">
            Realizar Transação Financeira
        </h3>

        <form wire:submit.prevent="executeTransaction" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Tipo de Operação -->
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-gray-300">Operação desejada:</label>
                    <select wire:model="operation"
                            class="w-full p-3 bg-gray-950 border border-gray-800 rounded-lg text-sm text-gray-100 focus:outline-none focus:border-sky-500">
                        <option value="deposit">📥 Depositar no Banco</option>
                        <option value="withdraw">📤 Sacar do Banco</option>
                    </select>
                </div>

                <!-- Quantidade -->
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-gray-300">Valor da transação ($):</label>
                    <input type="number" min="0" wire:model="amount" placeholder="0"
                           class="w-full p-3 bg-gray-950 border border-gray-800 rounded-lg text-sm text-gray-100 focus:outline-none focus:border-sky-500 font-mono">
                </div>
            </div>

            <div class="bg-gray-950/60 p-3 rounded-lg border border-gray-800/60 text-xs text-gray-400 flex items-center gap-2">
                <span>💡</span>
                <span>Mantendo o valor em <strong>0</strong> ao confirmar a transação, <strong>TODO</strong> o dinheiro disponível na operação será utilizado.</span>
            </div>

            <button type="submit" wire:loading.attr="disabled" wire:target="executeTransaction"
                    class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-500 shadow-lg shadow-emerald-950/50 disabled:opacity-50 text-white font-bold rounded-lg text-sm transition flex justify-center items-center gap-2">
                <span wire:loading.remove wire:target="executeTransaction">
                    Confirmar
                </span>
                <span wire:loading wire:target="executeTransaction" class="flex items-center gap-1">
                    <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    Processando...
                </span>
            </button>
        </form>
    </div>
</div>
