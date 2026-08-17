<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-gray-800 pb-3">
        <h2 class="text-2xl font-bold text-white">💃 Zonas & Prostitutas</h2>
        <button wire:click="collectIncome" wire:loading.attr="disabled"
                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded shadow transition text-sm">
            Coletar Renda das Putas
        </button>
    </div>

    <!-- Minhas Prostitutas -->
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-gray-200">Suas Garotas</h3>
        @if($owned && $owned->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($owned as $item)
                    <div class="p-4 bg-gray-800 border border-gray-700 rounded-lg flex items-center justify-between">
                        <div>
                            <h4 class="font-bold text-pink-400">{{ $item->name }}</h4>
                            <p class="text-xs text-gray-400">Quantidade: <span class="text-white font-mono font-semibold">{{ $item->pivot->amount }}</span></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="number" min="1" wire:model="amounts.{{ $item->id }}" placeholder="Qtd"
                                   class="w-16 p-1 bg-gray-900 border border-gray-700 rounded text-center text-xs">
                            <button wire:click="sellHooker({{ $item->id }})" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-500 text-white text-xs font-semibold rounded">
                                Vender
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 italic">Você ainda não gerencia nenhuma garota.</p>
        @endif
    </div>

    <!-- Garotas Disponíveis -->
    <div class="space-y-4 pt-4 border-t border-gray-800">
        <h3 class="text-lg font-bold text-gray-200">Garotas Disponíveis no Mercado</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($hookers as $hooker)
                <div class="p-4 bg-gray-800 border border-gray-700 rounded-lg flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-white">{{ $hooker->name }}</h4>
                        <p class="text-xs text-emerald-400 font-mono font-semibold">Preço: ${{ number_format($hooker->price) }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="number" min="1" wire:model="amounts.{{ $hooker->id }}" placeholder="Qtd"
                               class="w-16 p-1 bg-gray-900 border border-gray-700 rounded text-center text-xs">
                        <button wire:click="buyHooker({{ $hooker->id }})" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded">
                            Comprar
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
