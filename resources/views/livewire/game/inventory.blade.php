<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-gray-800 pb-3">
        <div>
            <h2 class="text-2xl font-bold text-white">🎒 Meu Inventário</h2>
            <p class="text-xs text-gray-400">Gerencie seus equipamentos, equipe itens para combate ou venda por 50% do valor</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-3 bg-emerald-900/50 border border-emerald-700 text-emerald-300 rounded text-xs">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-3 bg-rose-900/50 border border-rose-700 text-rose-300 rounded text-xs">
            {{ session('error') }}
        </div>
    @endif

    <!-- Armaduras -->
    <div class="space-y-4">
        <div class="flex items-center gap-2">
            <span class="text-lg">🛡️</span>
            <h3 class="text-lg font-bold text-gray-200">Armaduras</h3>
        </div>

        @if ($armors && $armors->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($armors as $armor)
                    @php
                        $isEquipped = $armor->pivot->active || $user->armor_id === $armor->id;
                        $sellPrice = floor($armor->price / 2);
                    @endphp
                    <div class="p-4 bg-gray-900 border {{ $isEquipped ? 'border-emerald-500/60 shadow-lg shadow-emerald-950/20' : 'border-gray-800' }} rounded-xl space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-sky-400 text-base">{{ $armor->name }}</h4>
                                <span class="text-[11px] text-gray-400">Proteção: +{{ number_format($armor->base_damage) }}</span>
                            </div>
                            @if ($isEquipped)
                                <span class="px-2 py-0.5 bg-emerald-500/20 border border-emerald-500/50 text-emerald-300 text-xs font-bold rounded">
                                    ✓ Equipado
                                </span>
                            @else
                                <span class="px-2 py-0.5 bg-gray-800 text-gray-400 text-xs rounded border border-gray-700">
                                    Inativo
                                </span>
                            @endif
                        </div>

                        <div class="bg-gray-950 p-2.5 rounded-lg border border-gray-800/80 text-xs space-y-1">
                            <div class="flex justify-between text-gray-400">
                                <span>Preço Original:</span>
                                <span class="font-medium text-gray-300">${{ number_format($armor->price) }}</span>
                            </div>
                            <div class="flex justify-between text-amber-400 font-semibold">
                                <span>Preço de Venda (50%):</span>
                                <span>${{ number_format($sellPrice) }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            @if ($isEquipped)
                                <button wire:click="deactivate({{ $armor->pivot->id }})"
                                        wire:loading.attr="disabled"
                                        class="w-full py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold rounded-lg transition">
                                    🔓 Desequipar
                                </button>
                            @else
                                <button wire:click="activate({{ $armor->pivot->id }})"
                                        wire:loading.attr="disabled"
                                        class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-lg transition">
                                    ⚡ Equipar
                                </button>
                            @endif

                            <button wire:click="sell({{ $armor->pivot->id }})"
                                    wire:loading.attr="disabled"
                                    class="w-full py-2 bg-rose-900/80 hover:bg-rose-700 text-rose-200 border border-rose-700/50 text-xs font-bold rounded-lg transition">
                                💰 Vender (${{ number_format($sellPrice) }})
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 italic bg-gray-900/50 p-4 rounded-lg border border-gray-800">
                Nenhuma armadura em seu inventário.
            </p>
        @endif
    </div>

    <!-- Armas -->
    <div class="space-y-4 pt-4 border-t border-gray-800">
        <div class="flex items-center gap-2">
            <span class="text-lg">⚔️</span>
            <h3 class="text-lg font-bold text-gray-200">Armas</h3>
        </div>

        @if ($weapons && $weapons->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($weapons as $weapon)
                    @php
                        $isEquipped = $weapon->pivot->active || $user->weapon_id === $weapon->id;
                        $sellPrice = floor($weapon->price / 2);
                    @endphp
                    <div class="p-4 bg-gray-900 border {{ $isEquipped ? 'border-emerald-500/60 shadow-lg shadow-emerald-950/20' : 'border-gray-800' }} rounded-xl space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-red-400 text-base">{{ $weapon->name }}</h4>
                                <span class="text-[11px] text-gray-400">Dano Base: +{{ number_format($weapon->base_damage) }}</span>
                            </div>
                            @if ($isEquipped)
                                <span class="px-2 py-0.5 bg-emerald-500/20 border border-emerald-500/50 text-emerald-300 text-xs font-bold rounded">
                                    ✓ Equipada
                                </span>
                            @else
                                <span class="px-2 py-0.5 bg-gray-800 text-gray-400 text-xs rounded border border-gray-700">
                                    Inativa
                                </span>
                            @endif
                        </div>

                        <div class="bg-gray-950 p-2.5 rounded-lg border border-gray-800/80 text-xs space-y-1">
                            <div class="flex justify-between text-gray-400">
                                <span>Preço Original:</span>
                                <span class="font-medium text-gray-300">${{ number_format($weapon->price) }}</span>
                            </div>
                            <div class="flex justify-between text-amber-400 font-semibold">
                                <span>Preço de Venda (50%):</span>
                                <span>${{ number_format($sellPrice) }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            @if ($isEquipped)
                                <button wire:click="deactivate({{ $weapon->pivot->id }})"
                                        wire:loading.attr="disabled"
                                        class="w-full py-2 bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold rounded-lg transition">
                                    🔓 Desequipar
                                </button>
                            @else
                                <button wire:click="activate({{ $weapon->pivot->id }})"
                                        wire:loading.attr="disabled"
                                        class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-lg transition">
                                    ⚡ Equipar
                                </button>
                            @endif

                            <button wire:click="sell({{ $weapon->pivot->id }})"
                                    wire:loading.attr="disabled"
                                    class="w-full py-2 bg-rose-900/80 hover:bg-rose-700 text-rose-200 border border-rose-700/50 text-xs font-bold rounded-lg transition">
                                💰 Vender (${{ number_format($sellPrice) }})
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 italic bg-gray-900/50 p-4 rounded-lg border border-gray-800">
                Nenhuma arma em seu inventário.
            </p>
        @endif
    </div>
</div>
