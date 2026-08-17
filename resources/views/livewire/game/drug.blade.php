<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-gray-800 pb-3">
        <h2 class="text-2xl font-bold text-white">🧪 Boca de Fumo / Drogas</h2>
        <button wire:click="reward" wire:loading.attr="disabled"
                class="px-3.5 py-1.5 bg-amber-600 hover:bg-amber-500 text-white font-semibold rounded text-sm transition">
            🎁 Recompensa Diária (Testes)
        </button>
    </div>

    <div class="space-y-4">
        <h3 class="text-lg font-bold text-gray-200">Suas Drogas Estocadas</h3>
        @if($drugs && $drugs->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($drugs as $drug)
                    <div class="p-4 bg-gray-800 border border-gray-700 rounded-lg space-y-3">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-sky-400 text-lg">{{ $drug->name }}</h4>
                            <span class="text-xs bg-gray-900 border border-gray-700 px-2 py-0.5 rounded font-mono text-emerald-400">
                                Preço: ${{ number_format($drug->price) }}
                            </span>
                        </div>

                        <div class="text-xs text-gray-400">
                            Estoque atual: <span class="text-white font-mono font-semibold text-sm">{{ number_format($drug->pivot->amount) }}</span> unidades
                        </div>

                        <div class="flex items-center gap-2 pt-2 border-t border-gray-700/50">
                            <input type="number" min="1" max="{{ $drug->pivot->amount }}" wire:model="amounts.{{ $drug->id }}" placeholder="Quantidade"
                                   class="w-full p-2 bg-gray-900 border border-gray-700 rounded text-sm text-gray-100">
                            <button wire:click="sell({{ $drug->id }})" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded transition whitespace-nowrap">
                                Vender
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 italic">Você não possui nenhuma droga no momento.</p>
        @endif
    </div>
</div>
