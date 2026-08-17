<div class="space-y-6 max-w-4xl mx-auto">
    <div class="flex justify-between items-center border-b border-gray-800 pb-3">
        <div>
            <h2 class="text-2xl font-bold text-white">🧪 Boca de Fumo / Drogas</h2>
            <p class="text-xs text-gray-400">Gerencie seu estoque e venda drogas para obter lucros no mercado negro</p>
        </div>
        <button wire:click="rewardItem" wire:loading.attr="disabled"
                class="px-3.5 py-1.5 bg-amber-600 hover:bg-amber-500 text-white font-semibold rounded-lg text-xs transition shadow-md flex items-center gap-1.5">
            🎁 Recompensa Diária (Testes)
        </button>
    </div>

    <div class="space-y-4">
        <h3 class="text-base font-bold text-gray-200 border-b border-gray-800 pb-2">
            Seu Estoque de Drogas
        </h3>

        @if($drugs && $drugs->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($drugs as $drug)
                    <div class="p-4 bg-gray-900 border border-gray-800 rounded-xl space-y-3 shadow-lg">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-sky-400 text-base">{{ $drug->name }}</h4>
                            <span class="text-xs bg-gray-950 border border-gray-800 px-2.5 py-1 rounded-md font-mono text-emerald-400 font-bold">
                                Preço: ${{ number_format($drug->price) }}
                            </span>
                        </div>

                        <div class="text-xs text-gray-400">
                            Estoque atual:
                            <span class="font-mono font-bold text-sm {{ $drug->user_amount > 0 ? 'text-emerald-400' : 'text-gray-500' }}">
                                {{ number_format($drug->user_amount) }}
                            </span> unidades
                        </div>

                        <div class="flex items-center gap-2 pt-2 border-t border-gray-800">
                            <input type="number" min="1" max="{{ $drug->user_amount }}" wire:model="amounts.{{ $drug->id }}" placeholder="Qtd"
                                   class="w-full p-2 bg-gray-950 border border-gray-800 rounded-lg text-xs text-gray-100 font-mono focus:outline-none focus:border-sky-500">
                            <button wire:click="sell({{ $drug->id }})" wire:loading.attr="disabled"
                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white text-xs font-bold rounded-lg transition whitespace-nowrap shadow-md">
                                Vender
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 italic">Nenhuma droga disponível no sistema.</p>
        @endif
    </div>
</div>
