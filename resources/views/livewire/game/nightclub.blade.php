<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-gray-800 pb-3">
        <h2 class="text-2xl font-bold text-white">🕺 Boates & Lutas da Noite</h2>
        <div class="text-xs text-gray-400">Recupere stamina e enfrente adversários</div>
    </div>

    @if ($combatMessage)
        <div class="p-4 rounded-lg border font-medium text-sm flex items-center justify-between {{ $combatStatus === 'success' ? 'bg-emerald-900/60 border-emerald-600 text-emerald-200' : 'bg-rose-900/60 border-rose-600 text-rose-200' }}">
            <span>{{ $combatMessage }}</span>
            <button wire:click="$set('combatMessage', null)" class="text-xs font-bold hover:underline">Fechar ✖</button>
        </div>
    @endif

    <div class="space-y-4">
        <h3 class="text-lg font-bold text-gray-200">Adversário Encontrado na Pista</h3>

        @if ($foe)
            <div class="p-6 bg-gray-800 border border-gray-700 rounded-lg max-w-md space-y-4 shadow">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-purple-900/60 border border-purple-500 rounded-full flex items-center justify-center font-bold text-purple-300 text-xl">
                        {{ strtoupper(substr($foe->name, 0, 2)) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-white text-lg">{{ $foe->name }}</h4>
                        <p class="text-xs text-gray-400">Respeito: <span class="text-yellow-400 font-mono font-semibold">{{ number_format($foe->respect) }}</span></p>
                        <p class="text-xs text-gray-400">Poder Estimado: <span class="text-red-400 font-mono font-semibold">{{ number_format($foe->assault_power) }}</span></p>
                    </div>
                </div>

                <button wire:click="fight({{ $foe->id }})" wire:loading.attr="disabled"
                        class="w-full py-2.5 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded shadow transition flex justify-center items-center gap-2">
                    <span wire:loading.remove wire:target="fight">⚔️ Atacar em Combate</span>
                    <span wire:loading wire:target="fight" class="animate-spin">🌀 Em combate...</span>
                </button>
            </div>
        @else
            <p class="text-sm text-gray-500 italic">Nenhum adversário encontrado na boate neste momento.</p>
        @endif
    </div>
</div>
