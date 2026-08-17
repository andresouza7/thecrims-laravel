<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-gray-800 pb-3">
        <div>
            <h2 class="text-2xl font-bold text-white">🧪 Laboratório: {{ $lab->factory->name }}</h2>
            <p class="text-xs text-gray-400">Nível {{ $lab->level }}</p>
        </div>
        <a href="{{ route('factory.index') }}" wire:navigate.hover class="px-3 py-1.5 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded text-xs font-semibold">
            ← Voltar para Fábricas
        </a>
    </div>

    <!-- Produções Ativas -->
    <div class="space-y-3">
        <h3 class="font-semibold text-lg text-gray-200">Produções Ativas</h3>

        @if ($lab->productions && $lab->productions->count())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($lab->productions as $production)
                    <div class="border border-gray-700 rounded-lg p-4 bg-gray-800 space-y-3 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div class="text-xs space-y-1">
                                <p><strong class="text-gray-400">Droga:</strong> <span class="text-sky-300 font-semibold">{{ $production->drug?->name }}</span></p>
                                <p><strong class="text-gray-400">Quantidade:</strong> <span class="text-white font-mono font-semibold">{{ number_format($production->amount) }}</span></p>
                                <p><strong class="text-gray-400">Termina em:</strong> <span class="text-gray-300">{{ \Carbon\Carbon::parse($production->ends_at)->format('d/m/Y H:i') }}</span></p>
                            </div>

                            @if ($production->progress < 100)
                                <button wire:click="cancelLabProduction({{ $production->id }})" class="text-rose-400 hover:text-rose-300 text-xs font-semibold">
                                    Cancelar ✖
                                </button>
                            @endif
                        </div>

                        @if ($production->progress < 100)
                            <div>
                                <div class="w-full bg-gray-900 rounded h-3 overflow-hidden">
                                    <div class="h-3 bg-emerald-500 transition-all duration-300" style="width: {{ $production->progress }}%"></div>
                                </div>
                                <p class="text-xs text-gray-400 mt-1 font-mono">
                                    Restante: {{ $production->remaining_time }}s
                                </p>
                            </div>
                        @else
                            <button wire:click="claimLabProduction({{ $production->id }})" class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded text-xs font-semibold">
                                Coletar Droga
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500 italic">Nenhuma produção ativa neste laboratório.</p>
        @endif
    </div>

    <!-- Criar Nova Produção -->
    <div class="space-y-4 pt-4 border-t border-gray-800">
        <h3 class="font-semibold text-lg text-gray-200">Nova Produção</h3>
        <div class="p-4 bg-gray-800 border border-gray-700 rounded-lg space-y-4">
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Componente Necessário</label>
                    <select wire:model="component_id" class="w-full p-2.5 bg-gray-900 border border-gray-700 rounded text-sm text-gray-100">
                        <option value="">Selecione um componente...</option>
                        @foreach ($components as $component)
                            <option value="{{ $component->id }}">
                                {{ $component->name }} (Possui: {{ $component->pivot->amount }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Quantidade</label>
                    <input type="number" min="1" wire:model="amount" placeholder="Quantidade"
                           class="w-full p-2.5 bg-gray-900 border border-gray-700 rounded text-sm text-gray-100">
                </div>

                <button wire:click="createLabProduction" wire:loading.attr="disabled"
                        class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded text-sm transition">
                    Produzir Drogas
                </button>
            </div>
        </div>
    </div>
</div>
