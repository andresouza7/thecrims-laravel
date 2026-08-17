<div class="space-y-6">
    <!-- Header com Métricas de Rendimento -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-800 pb-4 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                💃 Zonas & Prostitutas
            </h2>
            <p class="text-xs text-gray-400">Gerencie suas garotas, acompanhe rendimentos diários e colete os lucros</p>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
            <!-- Renda Pendente para Coletar -->
            <div class="bg-gray-900 border border-gray-800 px-3.5 py-1.5 rounded-lg text-right flex-1 sm:flex-none shadow">
                <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400 block">Renda Pendente</span>
                <span class="text-base font-mono font-bold text-emerald-400">${{ number_format($user->hooker_income ?? 0) }}</span>
            </div>

            <!-- Lucro Acumulado Histórico -->
            <div class="bg-gray-900 border border-gray-800 px-3.5 py-1.5 rounded-lg text-right flex-1 sm:flex-none shadow">
                <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400 block">Lucro Total Coletado</span>
                <span class="text-base font-mono font-bold text-amber-400">${{ number_format($user->hooker_profits ?? 0) }}</span>
            </div>

            <!-- Botão de Coletar -->
            <button wire:click="collectIncome" wire:loading.attr="disabled"
                    class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white font-bold rounded-lg shadow-md transition text-xs whitespace-nowrap self-stretch flex items-center justify-center gap-1.5">
                💰 Coletar Renda
            </button>
        </div>
    </div>

    <!-- Minhas Prostitutas -->
    <div class="space-y-4">
        <h3 class="text-base font-bold text-gray-200 border-b border-gray-800 pb-2">
            Suas Garotas
        </h3>
        @if($owned && $owned->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($owned as $item)
                    <div class="p-4 bg-gray-900 border border-gray-800 rounded-xl flex items-center justify-between shadow-lg">
                        <div class="space-y-1">
                            <h4 class="font-bold text-pink-400 text-base">{{ $item->name }}</h4>
                            <p class="text-xs text-gray-400">
                                Quantidade: <span class="text-white font-mono font-bold text-sm">{{ $item->pivot->amount }}</span>
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="number" min="1" wire:model="sellAmounts.{{ $item->id }}" placeholder="Qtd"
                                   class="w-16 p-2 bg-gray-950 border border-gray-800 rounded-lg text-center text-xs text-gray-100 font-mono focus:outline-none focus:border-pink-500">
                            <button wire:click="sellHooker({{ $item->id }})" wire:loading.attr="disabled"
                                    class="px-3 py-2 bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold rounded-lg shadow transition whitespace-nowrap">
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
        <h3 class="text-base font-bold text-gray-200 border-b border-gray-800 pb-2">
            Garotas Disponíveis no Mercado
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($hookers as $hooker)
                <div class="p-4 bg-gray-900 border border-gray-800 rounded-xl flex items-center justify-between shadow-lg">
                    <div class="space-y-1">
                        <h4 class="font-bold text-white text-base">{{ $hooker->name }}</h4>
                        <div class="flex flex-col text-xs space-y-0.5">
                            <span class="text-emerald-400 font-mono font-semibold">Preço: ${{ number_format($hooker->price) }}</span>
                            <span class="text-sky-400 font-mono font-medium">Rendimento: ${{ number_format($hooker->income) }}/dia</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="number" min="1" wire:model="buyAmounts.{{ $hooker->id }}" placeholder="Qtd"
                               class="w-16 p-2 bg-gray-950 border border-gray-800 rounded-lg text-center text-xs text-gray-100 font-mono focus:outline-none focus:border-emerald-500">
                        <button wire:click="buyHooker({{ $hooker->id }})" wire:loading.attr="disabled"
                                class="px-3 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-lg shadow transition whitespace-nowrap">
                            Comprar
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
