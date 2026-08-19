<div class="space-y-6">
    <!-- Header e Descrição -->
    <div class="flex flex-col gap-2 border-b border-gray-800 pb-4">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-white flex items-center gap-2">🥷 Roubos & Crimes de Rua</h2>
            <span
                class="text-xs px-2.5 py-1 bg-red-950/80 text-red-400 border border-red-900 rounded-full font-mono font-bold">Modo
                Solo</span>
        </div>
        <p class="text-sm text-gray-400 leading-relaxed">
            Planeje e execute assaltos urbanos para conseguir dinheiro fácil, drogas e componentes químicos. Seu sucesso
            depende do seu <strong>Poder de Roubo Solo</strong>. Tenha cuidado: se a chance de sucesso for menor que
            100%, você corre o risco de ser pego e mandado para a prisão!
        </p>
    </div>

    <!-- Grid Layout: Coluna Menor na Esquerda, Coluna Maior na Direita -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Esquerda (1/3): Formulário e Dropdown -->
        <div class="md:col-span-1 space-y-4">
            <div class="p-5 bg-gray-900 border border-gray-800 rounded-xl space-y-4 shadow">
                <h3 class="text-sm font-bold text-gray-300 uppercase tracking-wider">Selecionar Alvo</h3>

                <div class="space-y-2">
                    <label for="robbery-select" class="text-xs text-gray-500 font-semibold">Selecione o crime que deseja
                        cometer:</label>
                    <select id="robbery-select" wire:model.live="selectedRobberyId"
                        class="w-full p-3 bg-gray-950 border border-gray-800 focus:border-red-500 focus:ring-1 focus:ring-red-500 rounded-lg text-sm text-gray-200 transition font-medium">
                        @foreach ($robberies as $robbery)
                            @php
                                $cChance = $actionService->calculateSuccessChance($robbery);
                            @endphp
                            <option value="{{ $robbery->id }}">
                                {{ $robbery->description }} => {{ $robbery->required_stamina }} Stamina =>
                                {{ $cChance }}% Chance
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-2">
                    <button wire:click="execute" wire:loading.attr="disabled"
                        class="w-full py-3 bg-red-700 hover:bg-red-650 text-white font-bold rounded-lg shadow-lg hover:shadow-red-900/20 transition flex justify-center items-center gap-2 text-sm">
                        <span wire:loading.remove wire:target="execute">⚡ Executar Roubo</span>
                        <span wire:loading wire:target="execute" class="animate-spin text-lg">🌀</span>
                    </button>
                </div>
            </div>

            <!-- Card de Estatísticas do Jogador -->
            <div class="p-5 bg-gray-900/60 border border-gray-800 rounded-xl space-y-3 shadow-inner">
                <span class="text-[10px] text-gray-500 uppercase tracking-wider font-bold">Suas Capacidades</span>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">Poder de Roubo Solo:</span>
                    <span
                        class="font-bold text-red-400 font-mono">{{ number_format($user->single_robbery_power) }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400">Sua Stamina Atual:</span>
                    <span class="font-bold text-amber-500 font-mono">{{ $user->stamina }}%</span>
                </div>
            </div>
        </div>

        <!-- Direita (2/3): Detalhes do Alvo, Requisitos e Recompensas -->
        <div class="md:col-span-2 space-y-6">
            @if ($selectedRobbery)
                <div
                    class="p-6 bg-gray-900 border border-gray-805 rounded-xl space-y-6 shadow-xl relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 w-40 h-40 bg-red-500/5 rounded-full blur-3xl pointer-events-none transition-all group-hover:bg-red-500/10">
                    </div>

                    <!-- Cabeçalho do Detalhe -->
                    <div>
                        <span
                            class="px-2 py-0.5 bg-red-950 text-red-400 border border-red-900 rounded text-[10px] uppercase tracking-wider font-bold">Ficha
                            de Planejamento</span>
                        <h4 class="font-black text-white text-2xl mt-1.5">{{ $selectedRobbery->description }}</h4>
                    </div>

                    <!-- Chance de Sucesso -->
                    <div class="space-y-2 p-4 bg-gray-950/80 border border-gray-800 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-400 font-semibold">Chance Estimada de Sucesso:</span>
                            <span
                                class="text-sm font-black font-mono {{ $chance >= 100 ? 'text-emerald-400' : ($chance >= 50 ? 'text-amber-500' : 'text-rose-500') }}">
                                {{ $chance }}%
                            </span>
                        </div>

                        <div class="w-full bg-gray-900 rounded-full h-2.5 overflow-hidden">
                            <div class="h-2.5 rounded-full {{ $chance >= 100 ? 'bg-emerald-500' : ($chance >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}"
                                style="width: {{ min(100, $chance) }}%"></div>
                        </div>

                        <div class="text-[10px] text-gray-500 mt-1">
                            @if ($chance >= 100)
                                <span class="text-emerald-500 font-bold">🛡️ Sucesso Garantido!</span> Você possui poder
                                suficiente para limpar o local sem riscos de prisão.
                            @else
                                <span class="text-rose-400 font-bold">⚠️ Risco de Prisão: {{ 100 - $chance }}%!</span>
                                Se falhar, você irá passar 2 minutos na cadeia.
                            @endif
                        </div>
                    </div>

                    <!-- Grid de Requisitos e Recompensas -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <!-- Coluna Requisitos -->
                        <div class="p-4 bg-gray-950/40 border border-gray-850/60 rounded-lg space-y-3">
                            <span
                                class="text-[10px] text-gray-400 uppercase tracking-wider font-bold flex items-center gap-1">📋
                                Requisitos Mínimos</span>

                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Poder Necessário:</span>
                                    <span
                                        class="font-bold text-gray-200 font-mono">{{ number_format($selectedRobbery->required_power) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Stamina Necessária:</span>
                                    <span
                                        class="font-bold text-amber-500 font-mono">{{ $selectedRobbery->required_stamina }}%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Coluna Recompensas -->
                        <div class="p-4 bg-gray-950/40 border border-gray-850/60 rounded-lg space-y-3">
                            <span
                                class="text-[10px] text-emerald-400 uppercase tracking-wider font-bold flex items-center gap-1">💎
                                Recompensas Esperadas</span>

                            <div class="space-y-2 text-xs">
                                @if ($selectedRobbery->cash > 0)
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Dinheiro Payout:</span>
                                        <span
                                            class="font-bold text-emerald-400 font-mono">+${{ number_format($selectedRobbery->cash) }}</span>
                                    </div>
                                @endif

                                @if (!empty($selectedRobbery->drugs))
                                    @foreach ($selectedRobbery->drugs as $d)
                                        @php
                                            $drugName = \App\Models\Drug::find($d['drug_id'])?->name ?? 'Droga';
                                        @endphp
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Drogas Concedidas:</span>
                                            <span class="font-bold text-sky-400">+{{ $d['amount'] }}x
                                                {{ $drugName }}</span>
                                        </div>
                                    @endforeach
                                @endif

                                @if (!empty($selectedRobbery->components))
                                    @foreach ($selectedRobbery->components as $c)
                                        @php
                                            $compName =
                                                \App\Models\Component::find($c['component_id'])?->name ?? 'Componente';
                                        @endphp
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Insumos Concedidos:</span>
                                            <span class="font-bold text-amber-500">+{{ $c['amount'] }}x
                                                {{ $compName }}</span>
                                        </div>
                                    @endforeach
                                @endif

                                @if ($selectedRobbery->cash <= 0 && empty($selectedRobbery->drugs) && empty($selectedRobbery->components))
                                    <div class="text-gray-500 italic text-center py-1">Nenhuma recompensa visível.</div>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>
            @else
                <div class="p-8 bg-gray-900/60 border border-gray-800 rounded-xl text-center space-y-2 shadow-inner">
                    <p class="text-lg font-bold text-gray-300">Nenhum crime selecionado</p>
                    <p class="text-xs text-gray-500">Selecione um alvo na lista da esquerda para ver as especificações
                        da operação.</p>
                </div>
            @endif
        </div>

    </div>
</div>
