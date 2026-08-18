<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-gray-800 pb-3">
        <div>
            <h2 class="text-2xl font-bold text-white">🧪 Laboratório: {{ $lab->factory->name }}</h2>
            <div class="text-xs text-gray-400 flex flex-wrap gap-x-4 gap-y-1 mt-1">
                <span>Nível: <strong class="text-white font-mono">{{ $lab->level }}/3</strong></span>
                <span>Filas de Produção: <strong class="text-white font-mono">{{ $lab->productions->count() }}/{{ $lab->level }}</strong></span>
                <span>Capacidade por Fila: <strong class="text-emerald-400 font-mono">{{ number_format($lab->factory->production * $lab->level * 1000) }}</strong> componentes</span>
            </div>
        </div>
        <a href="{{ route('factory.index') }}" wire:navigate.hover class="px-3 py-1.5 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded text-xs font-semibold">
            ← Voltar para Fábricas
        </a>
    </div>

    <!-- Guia do Laboratório -->
    <div class="p-4 bg-gray-900/40 border border-amber-900/40 rounded-lg space-y-2">
        <p class="text-xs text-gray-300 italic">
            🧪 Bem-vindo ao laboratório secreto! Misture seus componentes químicos para produzir drogas de alta pureza e lucrar alto. Só não beba nada que estiver nos frascos.
        </p>
        <p class="text-[11px] text-gray-400 font-medium">
            💡 <strong>Melhorias por Nível:</strong>
        </p>
        <ul class="text-[11px] text-gray-400 list-disc list-inside space-y-1">
            <li><strong>Nível 1:</strong> 1 fila de produção, tempo base, capacidade base ({{ number_format($lab->factory->production * 1000) }} componentes/fila)</li>
            <li><strong>Nível 2:</strong> Até 2 filas simultâneas, tempo reduzido em 50%, capacidade duplicada ({{ number_format($lab->factory->production * 2 * 1000) }} componentes/fila)</li>
            <li><strong>Nível 3:</strong> Até 3 filas simultâneas, tempo reduzido em 67%, capacidade triplicada ({{ number_format($lab->factory->production * 3 * 1000) }} componentes/fila)</li>
        </ul>
        <p class="text-[11px] text-amber-500 font-medium flex items-center gap-1">
            ⚠️ <strong>Aviso Importante:</strong> Cancelar produções em andamento fará com que os reagentes evaporem! Você perderá todos os componentes utilizados nela.
        </p>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 items-start">
        <!-- Criar Nova Produção (Esquerda - Pega o espaço restante) -->
        <div class="w-full lg:flex-1 space-y-4">
            <h3 class="font-semibold text-lg text-gray-200">Nova Produção</h3>
            <div class="p-4 bg-gray-800 border border-gray-700 rounded-lg space-y-4">
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1">Componente Necessário</label>
                        <select wire:model.live="component_id" class="w-full p-2.5 bg-gray-900 border border-gray-700 rounded text-sm text-gray-100">
                            <option value="">Selecione um componente...</option>
                            @foreach ($components as $component)
                                <option value="{{ $component->id }}">
                                    {{ $component->name }} (Possui: {{ $component->pivot->amount }}) => {{ $component->drug?->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @php
                        $maxDrugByCapacity = $componentsPerUnit > 0 ? (int) floor(($lab->factory->production * $lab->level * 1000) / $componentsPerUnit) : 0;
                    @endphp

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1">Quantidade de Droga a Produzir</label>
                        <input type="number" min="1" @if($maxDrugByCapacity > 0) max="{{ $maxDrugByCapacity }}" @endif wire:model.live="amount" placeholder="Quantidade de Droga"
                               class="w-full p-2.5 bg-gray-900 border border-gray-700 rounded text-sm text-gray-100">
                        <span class="text-[10px] text-gray-500 mt-1 block">
                            Capacidade máxima do lab: {{ number_format($lab->factory->production * $lab->level * 1000) }} componentes por fila
                            @if($maxDrugByCapacity > 0)
                                (equivalente a até {{ number_format($maxDrugByCapacity) }} unidades)
                            @endif
                        </span>
                    </div>

                    @if ($component_id && $amount > 0)
                        <div class="p-3 bg-gray-900/60 border border-gray-700 rounded-lg space-y-1.5 text-xs text-gray-300">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Rendimento Solicitado:</span>
                                <span class="font-bold text-sky-400 font-mono">
                                    {{ number_format($amount) }} {{ $amount == 1 ? 'unidade' : 'unidades' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Componentes Necessários:</span>
                                <span class="font-bold text-emerald-400 font-mono">
                                    {{ number_format($requiredComponents) }} componentes
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-[10px] text-gray-500">
                                <span>Requisito:</span>
                                <span>1 unidade de {{ $selectedDrugName }} = {{ number_format($componentsPerUnit) }} componentes</span>
                            </div>
                            <div class="flex justify-between items-center text-[10px] text-gray-500">
                                <span>Capacidade total com seus componentes:</span>
                                <span class="font-semibold text-amber-500">{{ number_format($maxProduceableDrugs) }} unidades</span>
                            </div>
                            <div class="flex justify-between items-center pt-1 border-t border-gray-800">
                                <span class="text-gray-400">Duração Estimada:</span>
                                <span class="font-bold text-amber-400 font-mono">
                                    {{ $estimatedDuration }} {{ $estimatedDuration == 1 ? 'minuto' : 'minutos' }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <button wire:click="createLabProduction" wire:loading.attr="disabled"
                            class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded text-sm transition">
                        Produzir Drogas
                    </button>
                </div>
            </div>
        </div>

        <!-- Produções Ativas (Direita - Tabela) -->
        <div class="w-full lg:w-7/12 space-y-4">
            <h3 class="font-semibold text-lg text-gray-200">Produções Ativas</h3>

            @if ($lab->productions && $lab->productions->count())
                <div class="overflow-x-auto bg-gray-800 border border-gray-700 rounded-lg p-4 shadow-sm">
                    <table class="w-full text-left text-xs text-gray-300 border-collapse">
                        <thead>
                            <tr class="border-b border-gray-700 text-[10px] text-gray-400 uppercase font-semibold">
                                <th class="pb-2">Droga</th>
                                <th class="pb-2 text-right">Qtd</th>
                                <th class="pb-2 pl-4">Progresso / Tempo</th>
                                <th class="pb-2 text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/55">
                            @foreach ($lab->productions as $production)
                                <tr>
                                    <td class="py-3 font-semibold text-sky-300">
                                        {{ $production->drug?->name }}
                                    </td>
                                    <td class="py-3 text-right font-mono text-white font-semibold">
                                        {{ number_format($production->amount) }}
                                    </td>
                                    <td class="py-3 pl-4">
                                        @if ($production->progress < 100)
                                            <div class="flex items-center gap-2">
                                                <div class="w-20 bg-gray-900 rounded h-1.5 overflow-hidden shrink-0">
                                                    <div class="h-1.5 bg-emerald-500 transition-all duration-300" style="width: {{ $production->progress }}%"></div>
                                                </div>
                                                <span class="text-[10px] text-gray-400 font-mono">{{ $production->remaining_time }}s</span>
                                            </div>
                                        @else
                                            <span class="text-emerald-400 font-semibold flex items-center gap-1">🧪 Pronto</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-right">
                                        @if ($production->progress < 100)
                                            <button wire:click="cancelLabProduction({{ $production->id }})" class="text-rose-400 hover:text-rose-300 font-semibold">
                                                Cancelar ✖
                                            </button>
                                        @else
                                            <button wire:click="claimLabProduction({{ $production->id }})" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-500 text-white rounded text-[10px] font-bold shadow-sm">
                                                Coletar
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-gray-500 italic font-medium">Nenhuma produção ativa neste laboratório.</p>
            @endif
        </div>
    </div>
</div>
