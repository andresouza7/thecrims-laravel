<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-gray-800 pb-3">
        <h2 class="text-2xl font-bold text-white">🎒 Meu Inventário</h2>
        <div class="text-xs text-gray-400">Equipe ou venda seus itens adquiridos</div>
    </div>

    <!-- Armaduras -->
    <div class="space-y-4">
        <h3 class="text-lg font-bold text-gray-200">Armaduras no Inventário</h3>
        @if ($armors && $armors->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($armors as $armor)
                    <div class="p-4 bg-gray-800 border border-gray-700 rounded-lg space-y-3">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-sky-400">{{ $armor->name }}</h4>
                            @if ($armor->pivot->is_active)
                                <span class="px-2 py-0.5 bg-emerald-900/80 border border-emerald-500 text-emerald-300 text-xs font-semibold rounded">Equipado</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 pt-2 border-t border-gray-700/50">
                            @if ($armor->pivot->is_active)
                                <button wire:click="deactivate({{ $armor->pivot->id }})" class="w-full py-1.5 bg-amber-600 hover:bg-amber-500 text-white text-xs font-semibold rounded">
                                    Desequipar
                                </button>
                            @else
                                <button wire:click="activate({{ $armor->pivot->id }})" class="w-full py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded">
                                    Equipar
                                </button>
                            @endif
                            <button wire:click="sell({{ $armor->pivot->id }})" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white text-xs font-semibold rounded">
                                Vender
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 italic">Nenhuma armadura em seu inventário.</p>
        @endif
    </div>

    <!-- Armas -->
    <div class="space-y-4 pt-4 border-t border-gray-800">
        <h3 class="text-lg font-bold text-gray-200">Armas no Inventário</h3>
        @if ($weapons && $weapons->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($weapons as $weapon)
                    <div class="p-4 bg-gray-800 border border-gray-700 rounded-lg space-y-3">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-red-400">{{ $weapon->name }}</h4>
                            @if ($weapon->pivot->is_active)
                                <span class="px-2 py-0.5 bg-emerald-900/80 border border-emerald-500 text-emerald-300 text-xs font-semibold rounded">Equipada</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 pt-2 border-t border-gray-700/50">
                            @if ($weapon->pivot->is_active)
                                <button wire:click="deactivate({{ $weapon->pivot->id }})" class="w-full py-1.5 bg-amber-600 hover:bg-amber-500 text-white text-xs font-semibold rounded">
                                    Desequipar
                                </button>
                            @else
                                <button wire:click="activate({{ $weapon->pivot->id }})" class="w-full py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded">
                                    Equipar
                                </button>
                            @endif
                            <button wire:click="sell({{ $weapon->pivot->id }})" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white text-xs font-semibold rounded">
                                Vender
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 italic">Nenhuma arma em seu inventário.</p>
        @endif
    </div>
</div>
