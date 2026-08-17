<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-gray-800 pb-3">
        <h2 class="text-2xl font-bold text-white">🏭 Suas Fábricas e Laboratórios</h2>
        <button wire:click="collectProduction" wire:loading.attr="disabled"
                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded shadow transition text-sm">
            Coletar Produção das Fábricas
        </button>
    </div>

    <!-- Suas Fábricas -->
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-gray-200">Fábricas Adquiridas</h3>
        @if($owned && $owned->count())
            <div class="space-y-3">
                @foreach ($owned as $item)
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between p-4 bg-gray-800 border border-gray-700 rounded-lg gap-4">
                        <div class="flex items-center gap-4">
                            <img src="{{ $item->avatar ?: 'https://picsum.photos/seed/'.$item->id.'/48' }}" alt=""
                                 class="w-12 h-12 rounded-full object-cover border border-gray-600">
                            <div>
                                <h4 class="font-bold text-sky-400 text-lg">
                                    <a href="{{ route('factory.show', $item->pivot->id) }}" wire:navigate.hover class="hover:underline flex items-center gap-1">
                                        {{ $item->name }}
                                        <span class="text-xs text-amber-400 font-mono">(Acessar Laboratório 🧪)</span>
                                    </a>
                                </h4>
                                <div class="text-xs text-gray-400 flex flex-wrap gap-x-4 gap-y-1 mt-1">
                                    <span>Nível: <strong class="text-white font-mono">{{ $item->pivot->level }}</strong></span>
                                    <span>Estoque: <strong class="text-white font-mono">{{ number_format($item->pivot->stash) }}</strong></span>
                                    <span>Investimento: <strong class="text-emerald-400 font-mono">${{ number_format($item->pivot->investment) }}</strong></span>
                                    <span>Manutenção: <strong class="text-rose-400 font-mono">${{ number_format($item->maintenance) }}</strong></span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button wire:click="upgradeFactory({{ $item->pivot->id }})" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold rounded">
                                Upgrade
                            </button>
                            <button wire:click="sellFactory({{ $item->pivot->id }})" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white text-xs font-semibold rounded">
                                Vender
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 italic">Você não possui fábricas no momento.</p>
        @endif
    </div>

    <!-- Fábricas Disponíveis na Loja -->
    <div class="space-y-4 pt-4 border-t border-gray-800">
        <h3 class="text-lg font-bold text-gray-200">Mercado de Fábricas</h3>
        <div class="space-y-3">
            @foreach ($factories as $factory)
                <div class="flex items-center justify-between p-4 bg-gray-800 border border-gray-700 rounded-lg">
                    <div class="flex items-center gap-4">
                        <img src="{{ $factory->avatar ?: 'https://picsum.photos/seed/'.$factory->id.'/48' }}" alt=""
                             class="w-12 h-12 rounded-full object-cover border border-gray-600">
                        <div>
                            <h4 class="font-bold text-white">{{ $factory->name }}</h4>
                            <p class="text-xs text-gray-400">
                                Preço: <strong class="text-emerald-400 font-mono">${{ number_format($factory->price) }}</strong> |
                                Produz: <strong class="text-sky-300">{{ $factory->drug?->name ?? 'Drogas' }}</strong>
                            </p>
                        </div>
                    </div>

                    <button wire:click="buyFactory({{ $factory->id }})" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded">
                        Comprar
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</div>
