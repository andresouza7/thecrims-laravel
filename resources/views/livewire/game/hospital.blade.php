<div class="space-y-6">
    <div class="border-b border-gray-800 pb-3 text-center">
        <h2 class="text-2xl font-bold text-red-400">🏥 Hospital Central</h2>
        <p class="text-xs text-gray-400">Recupere sua saúde após batalhas intensas</p>
    </div>

    @if ($user && $user->in_hospital)
        <div class="p-6 bg-gray-800 border border-gray-700 rounded-lg space-y-4 text-center">
            <p class="text-sm text-gray-300">
                Sua saúde chegou a zero em combate ou overdose. Fique em repouso até ser liberado pelo médico.
            </p>

            <button wire:click="release" wire:loading.attr="disabled"
                    class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded transition text-sm">
                🩺 Receber Alta Médica
            </button>
        </div>
    @else
        <div class="p-6 bg-gray-800/40 border border-gray-800 rounded-lg space-y-4 text-center">
            <p class="text-sm text-gray-400">
                Você está perfeitamente saudável! Não há necessidade de internação médica no momento.
            </p>
            <div class="text-emerald-500 text-4xl py-2">😊</div>
            <a href="{{ route('home') }}" wire:navigate
               class="inline-block px-6 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded transition">
                Voltar ao Crime
            </a>
        </div>
    @endif
</div>
