<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-800 pb-4">
        <div>
            <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                <span>🕵️</span> O Beco - Tarefas do Submundo
            </h2>
            <p class="text-xs text-gray-400 mt-1">Complete missões e desafios para receber recompensas valiosas que ajudam a progredir no jogo.</p>
        </div>
        @if ($activeCategory)
            <button wire:click="pauseCategory" 
                    class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-200 hover:text-white border border-gray-700 hover:border-gray-600 rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow-md shadow-gray-950/20 cursor-pointer">
                <span>⏸️</span> Pausar Progresso
            </button>
        @endif
    </div>

    <!-- Active Category Screen -->
    @if ($activeCategory)
        <div class="space-y-6">
            <!-- Active Category Header Card -->
            <div class="p-5 bg-gradient-to-r from-amber-500/10 to-transparent border border-amber-500/20 rounded-xl">
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 bg-amber-500/20 text-amber-300 text-[10px] font-bold uppercase tracking-wider rounded border border-amber-500/30">Categoria Ativa</span>
                </div>
                <h3 class="text-xl font-bold text-amber-400 mt-2">{{ $activeCategory->name }}</h3>
                <p class="text-sm text-gray-300 mt-1 leading-relaxed">{{ $activeCategory->description }}</p>
            </div>

            <!-- Tasks Timeline/List -->
            <div class="space-y-4">
                @foreach ($tasksData as $task)
                    <div class="p-5 rounded-xl border transition duration-300 {{ $task['completed'] ? 'bg-emerald-950/5 border-emerald-500/30 shadow shadow-emerald-950/10' : ($task['locked'] ? 'bg-gray-950/40 border-gray-900 opacity-60' : 'bg-gray-900/60 border-gray-800 hover:border-gray-700') }}">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-800/60 pb-3 mb-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-bold {{ $task['completed'] ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : ($task['locked'] ? 'bg-gray-800 text-gray-500' : 'bg-amber-500 text-black') }}">
                                        {{ $task['order'] }}
                                    </span>
                                    <h4 class="font-bold text-white text-base">{{ $task['name'] }}</h4>
                                </div>
                                <p class="text-xs text-gray-400 mt-1 pl-8">{{ $task['description'] }}</p>
                            </div>

                            <div class="pl-8 md:pl-0">
                                @if ($task['completed'])
                                    <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full text-xs font-bold flex items-center gap-1">
                                        <span>✓</span> Concluída
                                    </span>
                                @elseif ($task['locked'])
                                    <span class="px-3 py-1 bg-gray-800/80 text-gray-500 border border-gray-800 rounded-full text-xs font-bold flex items-center gap-1 select-none">
                                        <span>🔒</span> Bloqueada
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded-full text-xs font-bold flex items-center gap-1 animate-pulse">
                                        <span>⚡</span> Em Andamento
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Inside Task Card content -->
                        @if ($task['locked'])
                            <div class="pl-8 py-2">
                                <p class="text-xs text-gray-500 italic flex items-center gap-1">
                                    <span>⚠️</span> Você precisa completar as tarefas anteriores desta categoria antes de poder realizar esta.
                                </p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pl-0 md:pl-8">
                                <!-- Requirements column -->
                                <div class="lg:col-span-2 space-y-3">
                                    <h5 class="text-rose-400 text-[11px] font-bold uppercase tracking-wider flex items-center gap-1">
                                        <span>🎯</span> Requisitos da Missão
                                    </h5>
                                    @if ($task['completed'])
                                        <p class="text-xs text-emerald-400 italic">Requisitos cumpridos com sucesso!</p>
                                    @else
                                        <div class="space-y-2">
                                            @foreach ($task['requirements'] as $req)
                                                <div class="bg-gray-950/70 p-3 rounded-lg border border-gray-800/60 space-y-1.5">
                                                    <div class="flex justify-between text-xs">
                                                        <span class="font-semibold text-gray-200">{{ $req['name'] }}</span>
                                                        <span class="{{ $req['completed'] ? 'text-emerald-400 font-bold' : 'text-gray-400 font-semibold' }}">
                                                            {{ number_format($req['current']) }} / {{ number_format($req['total']) }}
                                                        </span>
                                                    </div>
                                                    <div class="w-full h-1.5 bg-gray-900 rounded-full overflow-hidden">
                                                        <div class="h-full {{ $req['completed'] ? 'bg-emerald-500' : 'bg-amber-500' }} transition-all duration-500" 
                                                             style="width: {{ $req['progress'] }}%"></div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <!-- Rewards and Action Column -->
                                <div class="space-y-3 flex flex-col justify-between">
                                    <div>
                                        <h5 class="text-emerald-400 text-[11px] font-bold uppercase tracking-wider flex items-center gap-1 mb-2">
                                            <span>🎁</span> Recompensas
                                        </h5>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach ($task['rewards'] as $rew)
                                                <span class="px-2.5 py-1 bg-gray-950 text-gray-200 border border-gray-800 rounded-lg text-xs font-semibold flex items-center gap-1.5">
                                                    <span class="text-emerald-400 font-bold">+{{ number_format($rew['value']) }}</span> {{ $rew['name'] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="pt-3">
                                        @if ($task['completed'])
                                            <button disabled class="w-full py-2.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold rounded-lg text-xs cursor-not-allowed text-center select-none flex items-center justify-center gap-1.5">
                                                <span>✓</span> Recompensas Coletadas
                                            </button>
                                        @elseif ($task['can_complete'])
                                            <button wire:click="claimTaskReward({{ $task['id'] }})"
                                                    class="w-full py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold rounded-lg text-xs transition duration-200 shadow-md shadow-emerald-950/40 hover:shadow-emerald-900/40 animate-pulse cursor-pointer flex items-center justify-center gap-1.5">
                                                <span>🚀</span> Completar Tarefa e Resgatar
                                            </button>
                                        @else
                                            <button disabled class="w-full py-2.5 bg-gray-800/50 text-gray-500 border border-gray-850 font-bold rounded-lg text-xs cursor-not-allowed text-center select-none flex items-center justify-center gap-1.5">
                                                <span>🔒</span> Requisitos Pendentes
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <!-- Category Selection Screen -->
        <div class="space-y-4">
            <div class="bg-gray-900/40 border border-gray-800/80 p-4 rounded-xl">
                <p class="text-xs text-gray-400 leading-relaxed">
                    Você pode trabalhar em apenas uma categoria por vez. Se achar os requisitos de alguma tarefa muito difíceis, você pode **pausar** seu progresso nela a qualquer momento e iniciar outra categoria. Seu progresso nas tarefas já completadas será salvo permanentemente.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($categoriesData as $cat)
                    <div class="p-5 bg-gray-900/60 border border-gray-800 rounded-xl hover:border-gray-700 transition duration-300 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <h3 class="text-lg font-bold text-white">{{ $cat['name'] }}</h3>
                            <p class="text-xs text-gray-400 leading-relaxed line-clamp-3">{{ $cat['description'] }}</p>
                        </div>

                        <div class="space-y-3">
                            <!-- Progress Bar -->
                            <div class="space-y-1">
                                <div class="flex justify-between text-[11px] text-gray-400">
                                    <span>Progresso da Categoria</span>
                                    <span class="font-bold text-gray-300">{{ $cat['completed_tasks'] }} / {{ $cat['total_tasks'] }} Tarefas</span>
                                </div>
                                <div class="w-full h-1.5 bg-gray-950 rounded-full overflow-hidden border border-gray-850">
                                    <div class="h-full bg-gradient-to-r from-amber-500 to-amber-400 rounded-full transition-all duration-300" 
                                         style="width: {{ $cat['progress_percent'] }}%"></div>
                                </div>
                            </div>

                            <!-- CTA Button -->
                            <button wire:click="startCategory({{ $cat['id'] }})"
                                    class="w-full py-2 bg-amber-500 hover:bg-amber-400 text-black font-bold rounded-lg text-xs transition duration-200 cursor-pointer text-center flex items-center justify-center gap-1.5 shadow-md shadow-amber-950/20">
                                <span>⚡</span> Iniciar Categoria
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
