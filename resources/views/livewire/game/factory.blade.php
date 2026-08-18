<div class="space-y-4">
    <div class="flex justify-between items-center border-b border-gray-800 pb-2">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">🏭 Suas Fábricas e Laboratórios</h2>
        <button wire:click="collectProduction" wire:loading.attr="disabled"
                class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded shadow transition text-sm">
            Coletar Produção das Fábricas
        </button>
    </div>

    <!-- Guia de Fábricas -->
    <div class="p-3 bg-gray-900/40 border border-gray-750 rounded-lg">
        <p class="text-xs text-gray-300 italic">
            🏭 Bem-vindo ao seu distrito industrial clandestino! Compre fábricas para produzir drogas automaticamente a cada dia de jogo, ou adquira um laboratório para produções especializadas. Lembre-se: manter as máquinas funcionando custa dinheiro de manutenção diária!
        </p>
    </div>

    <!-- Suas Fábricas -->
    <div class="space-y-2.5">
        <h3 class="text-base font-bold text-gray-200">Fábricas Adquiridas</h3>
        @if($owned && $owned->count())
            <div class="space-y-2">
                @foreach ($owned as $item)
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between p-3 bg-gray-800 border border-gray-700 rounded-lg gap-3">
                        <div class="flex items-center gap-3 w-full">
                            <img src="{{ $item->factory->avatar ?: 'https://picsum.photos/seed/'.$item->factory->id.'/48' }}" alt=""
                                 class="w-10 h-10 rounded-full object-cover border border-gray-600 shrink-0">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-sky-400 text-base leading-tight">
                                    @if ($item->factory->is_lab)
                                        <a href="{{ route('factory.show', $item->id) }}" wire:navigate.hover class="hover:underline flex items-center gap-1.5">
                                            {{ $item->factory->name }}
                                            <span class="text-[10px] text-amber-400 font-mono font-normal bg-amber-950/40 px-1.5 py-0.5 rounded border border-amber-950/60">(Acessar Laboratório 🧪)</span>
                                        </a>
                                    @else
                                        <span class="text-gray-200">{{ $item->factory->name }}</span>
                                    @endif
                                </h4>
                                <div class="text-xs text-gray-400 flex flex-wrap gap-x-4 gap-y-1 mt-1">
                                    <span>Nível: <strong class="text-white font-mono">{{ $item->level }}</strong></span>
                                    <span>Estoque: <strong class="text-white font-mono">{{ number_format($item->stash) }}</strong></span>
                                    <span>Investimento: <strong class="text-emerald-400 font-mono">${{ number_format($item->investment) }}</strong></span>
                                    <span>Manutenção: <strong class="text-rose-400 font-mono">${{ number_format($item->factory->maintenance) }}</strong></span>
                                    <span>Produção Diária: 
                                        @if ($item->factory->is_lab)
                                            <strong class="text-teal-400 font-semibold">Sob Demanda</strong>
                                        @else
                                            <strong class="text-emerald-400 font-mono">{{ number_format($item->factory->production * $item->level) }}</strong>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 shrink-0 self-end md:self-center">
                            @if ($item->level >= 3)
                                <div class="flex flex-col items-end">
                                    <button disabled class="px-3 py-1 bg-gray-700 text-gray-500 text-[11px] font-semibold rounded cursor-not-allowed">
                                        Nível Máximo
                                    </button>
                                    <span class="text-[9px] text-gray-500 mt-0.5">Nível 3 Atingido</span>
                                </div>
                            @else
                                <div class="flex flex-col items-end">
                                    <button wire:click="upgradeFactory({{ $item->id }})" class="px-3 py-1 bg-blue-600 hover:bg-blue-500 text-white text-[11px] font-semibold rounded shadow-sm">
                                        Upgrade
                                    </button>
                                    <span class="text-[9px] text-gray-500 mt-0.5">Custo: <strong class="text-emerald-400 font-mono">${{ number_format($item->getUpgradeCost()) }}</strong></span>
                                </div>
                            @endif
                            <button wire:click="sellFactory({{ $item->id }})" class="px-3 py-1 bg-rose-600 hover:bg-rose-500 text-white text-[11px] font-semibold rounded shadow-sm self-start">
                                Vender
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-gray-500 italic">Você não possui fábricas no momento.</p>
        @endif
    </div>

    <!-- Fábricas Disponíveis na Loja -->
    <div class="space-y-2.5 pt-3 border-t border-gray-800">
        <h3 class="text-base font-bold text-gray-200">Mercado de Fábricas</h3>
        <div class="space-y-2">
            @foreach ($factories as $factory)
                <div class="flex items-center justify-between p-3 bg-gray-800 border border-gray-700 rounded-lg gap-3">
                    <div class="flex items-center gap-3 w-full">
                        <img src="{{ $factory->avatar ?: 'https://picsum.photos/seed/'.$factory->id.'/48' }}" alt=""
                             class="w-10 h-10 rounded-full object-cover border border-gray-600 shrink-0">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-white text-sm leading-tight">{{ $factory->name }}</h4>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-400 mt-1">
                                <span>Preço: <strong class="text-emerald-400 font-mono">${{ number_format($factory->price) }}</strong></span>
                                <span>Produz: <strong class="text-sky-300 font-medium">{{ $factory->drug?->name ?? 'Drogas' }}</strong></span>
                                <span>Produção Diária Base: 
                                    @if ($factory->is_lab)
                                        <strong class="text-teal-400 font-semibold">Sob Demanda (Laboratório)</strong>
                                    @else
                                        <strong class="text-emerald-400 font-mono">{{ number_format($factory->production) }}</strong>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <button wire:click="buyFactory({{ $factory->id }})" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded shadow-sm shrink-0">
                        Comprar
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</div>
