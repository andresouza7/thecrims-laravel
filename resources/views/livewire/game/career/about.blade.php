<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-gray-800 pb-3">
        <div>
            <h2 class="text-2xl font-bold text-white">💼 Especialização & Carreira</h2>
            <p class="text-xs text-gray-400">Evolua seu personagem no crime organizado e conquiste recompensas</p>
        </div>
        @if ($userCareer)
            <div class="text-right">
                <span class="px-3 py-1 bg-amber-500/20 text-amber-300 border border-amber-500/40 rounded-full text-xs font-bold">
                    Nível Atual: {{ $currentLevelNum }}
                </span>
            </div>
        @endif
    </div>



    @if ($userCareer)
        <div class="p-5 bg-gray-900 border border-gray-800 rounded-xl space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gray-800/60 p-4 rounded-lg border border-gray-700/50">
                <div>
                    <h3 class="text-xl font-bold text-amber-400">{{ $userCareer->name }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Cumpra todos os requisitos para ser promovido ao próximo nível.</p>
                </div>

                @if ($hasNextLevel)
                    @if ($canPromote)
                        <button wire:click="promoteLevel" wire:loading.attr="disabled"
                                class="px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold rounded-lg text-sm transition shadow-lg shadow-emerald-950/50 animate-pulse cursor-pointer">
                            🚀 Subir de Nível
                        </button>
                    @else
                        <button disabled
                                class="px-5 py-2.5 bg-gray-800/80 border border-gray-700/80 text-gray-400 font-bold rounded-lg text-sm cursor-not-allowed flex items-center gap-2">
                            <span>🔒</span> Requisitos Pendentes
                        </button>
                    @endif
                @else
                    <span class="px-4 py-2 bg-amber-500/10 border border-amber-500/30 text-amber-400 font-bold rounded-lg text-xs flex items-center gap-1.5">
                        <span>👑</span> Nível Máximo Atingido
                    </span>
                @endif
            </div>

            <div class="space-y-4">
                @foreach ($levelsData as $lvl)
                    <div class="p-4 rounded-lg border transition {{ $lvl['is_current'] ? 'bg-amber-950/20 border-amber-500/60 shadow-lg shadow-amber-950/20' : ($lvl['is_unlocked'] ? 'bg-gray-800/40 border-gray-700/60' : 'bg-gray-900/40 border-gray-800 opacity-75') }}">
                        <div class="flex justify-between items-center border-b border-gray-800 pb-2 mb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold {{ $lvl['is_unlocked'] ? 'bg-amber-500 text-black' : 'bg-gray-800 text-gray-500' }}">
                                    {{ $lvl['level'] }}
                                </span>
                                <h4 class="font-bold text-white text-base">{{ $lvl['name'] }}</h4>
                            </div>
                            @if ($lvl['is_current'])
                                <span class="text-xs text-amber-400 font-semibold px-2 py-0.5 bg-amber-500/10 rounded border border-amber-500/30">Nível Ativo</span>
                            @elseif ($lvl['is_unlocked'])
                                <span class="text-xs text-emerald-400 font-semibold">✓ Concluído</span>
                            @else
                                <span class="text-xs text-gray-500 font-semibold">🔒 Bloqueado</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                            <!-- Requisitos -->
                            <div class="space-y-2">
                                <h5 class="font-bold text-rose-400 uppercase tracking-wider text-[11px] flex items-center gap-1">
                                    🎯 Requisitos para alcançar este Nível
                                </h5>

                                @if (count($lvl['requirements']) > 0)
                                    <div class="space-y-2.5">
                                        @foreach ($lvl['requirements'] as $req)
                                            <div class="bg-gray-950/80 p-2.5 rounded border border-gray-800 space-y-1">
                                                <div class="flex justify-between text-gray-300">
                                                    <span class="font-medium text-gray-200">{{ $req['name'] }}</span>
                                                    <span class="{{ $req['completed'] ? 'text-emerald-400 font-bold' : 'text-gray-400' }}">
                                                        {{ number_format($req['current']) }} / {{ number_format($req['total']) }}
                                                    </span>
                                                </div>
                                                <div class="w-full h-1.5 bg-gray-800 rounded-full overflow-hidden">
                                                    <div class="h-full {{ $req['completed'] ? 'bg-emerald-500' : 'bg-amber-500' }} transition-all duration-300"
                                                         style="width: {{ $req['progress'] }}%"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="italic text-gray-500 py-1">Sem requisitos (Nível inicial de entrada).</p>
                                @endif
                            </div>

                            <!-- Recompensas -->
                            <div class="space-y-2">
                                <h5 class="font-bold text-emerald-400 uppercase tracking-wider text-[11px] flex items-center gap-1">
                                    🎁 Recompensas concedidas neste Nível
                                </h5>

                                @if (count($lvl['rewards']) > 0)
                                    <div class="grid grid-cols-1 gap-2">
                                        @foreach ($lvl['rewards'] as $rew)
                                            <div class="bg-emerald-950/30 border border-emerald-800/40 p-2.5 rounded flex justify-between items-center">
                                                <span class="text-emerald-200 font-medium">{{ $rew['name'] }}</span>
                                                <span class="text-emerald-400 font-bold bg-emerald-950 px-2 py-0.5 rounded border border-emerald-700/50">
                                                    +{{ number_format($rew['value']) }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="italic text-gray-500 py-1">Sem recompensas específicas.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <!-- Escolher Carreira com Preview Interativo -->
        <div class="space-y-6">
            <div class="p-6 bg-gray-900 border border-gray-800 rounded-xl space-y-4 shadow-xl">
                <div class="space-y-1 border-b border-gray-800 pb-3">
                    <h3 class="text-xl font-bold text-white">Escolha sua Carreira Criminal</h3>
                    <p class="text-xs text-gray-400">Selecione uma especialização para visualizar seus requisitos e recompensas por nível antes de confirmar.</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-1">
                    <select wire:model.live="selectedCareerId" class="flex-1 p-3 bg-gray-950 border border-gray-800 rounded-lg text-sm text-gray-100 focus:border-amber-500 focus:outline-none">
                        <option value="">Selecione uma carreira...</option>
                        @foreach ($careers as $career)
                            <option value="{{ $career->id }}">{{ $career->name }}</option>
                        @endforeach
                    </select>

                    <button wire:click="selectCareer" wire:loading.attr="disabled"
                            @disabled(!$selectedCareerId)
                            class="px-6 py-3 bg-amber-600 hover:bg-amber-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold rounded-lg text-sm transition shadow-lg shadow-amber-950/50 flex items-center justify-center gap-2 shrink-0">
                        <span>✓</span> Confirmar Escolha
                    </button>
                </div>
            </div>

            <!-- Seção de Prévia da Carreira Selecionada -->
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-gray-800 pb-2">
                    <h4 class="text-lg font-bold text-amber-400 flex items-center gap-2">
                        <span>👁️</span> Prévia da Carreira: <span class="text-white">{{ $previewCareer?->name ?? 'Selecione uma carreira' }}</span>
                    </h4>
                    <span class="text-xs text-gray-400">Progresso de requisitos e recompensas por nível</span>
                </div>

                @if ($previewCareer && count($previewLevelsData) > 0)
                    <div class="space-y-4">
                        @foreach ($previewLevelsData as $lvl)
                            <div class="p-4 bg-gray-900/90 border border-gray-800 rounded-xl space-y-3">
                                <div class="flex justify-between items-center border-b border-gray-800 pb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="w-7 h-7 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/40 flex items-center justify-center text-xs font-bold">
                                            {{ $lvl['level'] }}
                                        </span>
                                        <h5 class="font-bold text-white text-base">{{ $lvl['name'] }}</h5>
                                    </div>
                                    <span class="text-xs text-amber-400/80 font-semibold px-2 py-0.5 bg-amber-500/10 rounded border border-amber-500/20">
                                        Nível {{ $lvl['level'] }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                                    <!-- Requisitos do Nível -->
                                    <div class="space-y-2">
                                        <h6 class="font-bold text-rose-400 uppercase tracking-wider text-[11px] flex items-center gap-1">
                                            🎯 Requisitos Exigidos
                                        </h6>
                                        @if (count($lvl['requirements']) > 0)
                                            <div class="space-y-1.5">
                                                @foreach ($lvl['requirements'] as $req)
                                                    <div class="bg-gray-950 p-2.5 rounded border border-gray-800 flex justify-between items-center text-gray-300">
                                                        <span class="font-medium text-gray-200">{{ $req['name'] }}</span>
                                                        <span class="font-bold text-amber-400 bg-amber-950/40 px-2 py-0.5 rounded border border-amber-800/40">
                                                            Exige: {{ number_format($req['value']) }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="italic text-gray-500 py-1">Sem requisitos específicos.</p>
                                        @endif
                                    </div>

                                    <!-- Recompensas do Nível -->
                                    <div class="space-y-2">
                                        <h6 class="font-bold text-emerald-400 uppercase tracking-wider text-[11px] flex items-center gap-1">
                                            🎁 Recompensas de Promoção
                                        </h6>
                                        @if (count($lvl['rewards']) > 0)
                                            <div class="space-y-1.5">
                                                @foreach ($lvl['rewards'] as $rew)
                                                    <div class="bg-emerald-950/30 border border-emerald-800/40 p-2.5 rounded flex justify-between items-center">
                                                        <span class="text-emerald-200 font-medium">{{ $rew['name'] }}</span>
                                                        <span class="text-emerald-400 font-bold bg-emerald-950 px-2 py-0.5 rounded border border-emerald-700/50">
                                                            +{{ number_format($rew['value']) }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="italic text-gray-500 py-1">Sem recompensas específicas.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center text-gray-500 bg-gray-900 border border-gray-800 rounded-xl">
                        Selecione uma carreira no campo acima para visualizar seus níveis, requisitos e recompensas.
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
