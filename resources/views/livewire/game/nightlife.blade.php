<div class="space-y-6">
    <!-- Header principal -->
    <div class="flex flex-col gap-2 border-b border-gray-800 pb-4 text-left">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                🌃 Vida Noturna de Crims City
            </h2>
            <span class="text-xs px-2.5 py-1 bg-purple-950/80 text-purple-400 border border-purple-900 rounded-full font-mono font-bold">
                🎫 {{ $user->tickets }} Ingressos Disponíveis
            </span>
        </div>
        <p class="text-sm text-gray-400 leading-relaxed">
            As luzes de neon e as vielas escuras escondem os melhores refúgios para recuperar suas forças. Gaste seus ingressos para restaurar sua stamina consumindo substâncias de alta octanagem na Boate ou contratando luxo e prazer na Mansão das Putas. Mas lembre-se: a noite também é perigosa e cheia de rivais!
        </p>
    </div>

    @if ($activeTab === 'selection')
        <!-- Tela de Seleção: Dois Cards Premium -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
            <!-- CARD 1: A BOATE -->
            <div class="group relative overflow-hidden rounded-xl border border-gray-800 bg-gradient-to-br from-gray-900 to-purple-950/20 p-6 shadow-xl transition-all duration-300 hover:border-purple-500/50 hover:shadow-purple-950/30 flex flex-col justify-between h-[320px]">
                <div class="space-y-3">
                    <div class="w-12 h-12 bg-purple-950/60 border border-purple-500/30 rounded-lg flex items-center justify-center text-2xl select-none group-hover:scale-110 transition-transform duration-300">
                        🕺
                    </div>
                    <h3 class="text-xl font-bold text-purple-400 group-hover:text-purple-300 transition-colors">A Boate</h3>
                    <p class="text-xs text-gray-400 leading-relaxed">
                        Um clube underground barulhento regado a música eletrônica e substâncias pesadas. Consuma drogas estimulantes para restaurar sua stamina rapidamente. Custa 1 ticket e dinheiro proporcional ao respeito, mas aumenta consideravelmente seu vício.
                    </p>
                </div>
                <button wire:click="selectTab('boate')"
                        class="w-full mt-4 py-2.5 bg-purple-600 hover:bg-purple-500 text-white font-bold rounded-lg text-xs shadow-md transition duration-200 uppercase tracking-wider">
                    Entrar na Boate 🚪
                </button>
            </div>

            <!-- CARD 2: A MANSÃO DAS PUTAS -->
            <div class="group relative overflow-hidden rounded-xl border border-gray-800 bg-gradient-to-br from-gray-900 to-rose-950/20 p-6 shadow-xl transition-all duration-300 hover:border-rose-500/50 hover:shadow-rose-950/30 flex flex-col justify-between h-[320px]">
                <div class="space-y-3">
                    <div class="w-12 h-12 bg-rose-950/60 border border-rose-500/30 rounded-lg flex items-center justify-center text-2xl select-none group-hover:scale-110 transition-transform duration-300">
                        💋
                    </div>
                    <h3 class="text-xl font-bold text-rose-400 group-hover:text-rose-300 transition-colors">Mansão das Putas</h3>
                    <p class="text-xs text-gray-400 leading-relaxed">
                        O bordel mais luxuoso e exclusivo de Crims City. Contrate os serviços de acompanhantes de alto nível para zerar seu cansaço sem prejudicar seu corpo com vícios químicos. Custa 1 ticket e uma taxa baseada no respeito. Cuidado: há uma chance de 10% de contrair doenças e ir parar no hospital!
                    </p>
                </div>
                <button wire:click="selectTab('mansao')"
                        class="w-full mt-4 py-2.5 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-lg text-xs shadow-md transition duration-200 uppercase tracking-wider">
                    Entrar na Mansão 🚪
                </button>
            </div>
        </div>
    @elseif ($activeTab === 'boate')
        <!-- ABA: A BOATE -->
        <div class="space-y-6">
            <div class="flex justify-between items-center bg-purple-950/20 border border-purple-900/40 p-4 rounded-xl">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🕺</span>
                    <div>
                        <h3 class="font-bold text-purple-400 text-lg">Pista de Dança & Bar</h3>
                        <p class="text-xs text-gray-400">Ambiente carregado de fumaça, neon roxo e batidas graves.</p>
                    </div>
                </div>
                <button wire:click="selectTab('selection')"
                        class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white font-bold rounded-lg text-xs transition">
                    ⬅️ Voltar
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
                <!-- Esquerda: Compra de Stamina -->
                <div class="p-6 bg-gray-900 border border-gray-850 rounded-xl space-y-4 shadow flex flex-col justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-purple-300 uppercase tracking-wider mb-2">⚡ Consumir Drogas Estimulantes</h4>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            Tome uma dose pesada de estimulantes no balcão do bar para zerar seu cansaço e recarregar sua barra de energia para mais assaltos.
                        </p>
                        
                        <div class="mt-4 bg-gray-950/70 border border-gray-800/80 rounded-lg p-3 space-y-2 text-xs">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Custo do Serviço:</span>
                                <span class="text-emerald-400 font-bold font-mono">${{ number_format($staminaCost) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Ingressos Necessários:</span>
                                <span class="text-purple-400 font-bold">1 Ticket</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Efeitos Colaterais:</span>
                                <span class="text-red-400 font-bold">+15% de Vício</span>
                            </div>
                        </div>
                    </div>

                    <button wire:click="buyStamina" wire:loading.attr="disabled"
                            class="w-full py-3 bg-purple-600 hover:bg-purple-550 text-white font-bold rounded-lg text-xs shadow transition">
                        ⚡ Consumir Estimulantes
                    </button>
                </div>

                <!-- Direita: Combate / Lutas -->
                <div class="p-6 bg-gray-900 border border-gray-850 rounded-xl space-y-4 shadow">
                    <h4 class="text-sm font-bold text-red-400 uppercase tracking-wider mb-2">⚔️ Procurar Briga na Pista</h4>
                    <p class="text-xs text-gray-400 leading-relaxed">
                        A boate está cheia de outros criminosos bêbados ou chapados. Escolha um alvo e desafie-o para combate direto para ganhar respeito e roubar parte do cash dele!
                    </p>

                    @if ($foe)
                        <div class="p-4 bg-gray-950 border border-gray-800 rounded-lg space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-purple-900/40 border border-purple-500/20 rounded-full flex items-center justify-center font-bold text-purple-300 text-lg">
                                    {{ strtoupper(substr($foe->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h5 class="font-bold text-white text-sm">{{ $foe->name }}</h5>
                                    <p class="text-[10px] text-gray-400">Respeito: <span class="text-yellow-400 font-mono font-semibold">{{ number_format($foe->respect) }}</span></p>
                                    <p class="text-[10px] text-gray-400">Força Estimada: <span class="text-red-400 font-mono font-semibold">{{ number_format($foe->assault_power) }}</span></p>
                                </div>
                            </div>

                            <button wire:click="fight({{ $foe->id }})" wire:loading.attr="disabled"
                                    class="w-full py-2 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded text-xs transition flex justify-center items-center gap-2">
                                <span wire:loading.remove wire:target="fight">⚔️ Atacar Rival</span>
                                <span wire:loading wire:target="fight" class="animate-spin">🌀 Lutando...</span>
                            </button>
                        </div>
                    @else
                        <p class="text-xs text-gray-500 italic py-4">Nenhum adversário encontrado na pista neste momento.</p>
                    @endif
                </div>
            </div>
        </div>
    @elseif ($activeTab === 'mansao')
        <!-- ABA: A MANSÃO DAS PUTAS -->
        <div class="space-y-6">
            <div class="flex justify-between items-center bg-rose-950/20 border border-rose-900/40 p-4 rounded-xl">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">💋</span>
                    <div>
                        <h3 class="font-bold text-rose-400 text-lg">Mansão das Putas (Área VIP)</h3>
                        <p class="text-xs text-gray-400">Quartos reservados com luz vermelha difusa, champanhe e acompanhantes.</p>
                    </div>
                </div>
                <button wire:click="selectTab('selection')"
                        class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white font-bold rounded-lg text-xs transition">
                    ⬅️ Voltar
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
                <!-- Esquerda: Compra de Stamina -->
                <div class="p-6 bg-gray-900 border border-gray-850 rounded-xl space-y-4 shadow flex flex-col justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-rose-300 uppercase tracking-wider mb-2">💋 Contratar Serviço Acompanhante</h4>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            Relaxe e desfrute do luxo. Acompanhantes profissionais restaurarão toda a sua energia física de forma segura, sem drogas ou vício químico.
                        </p>
                        
                        <div class="mt-4 bg-gray-950/70 border border-gray-800/80 rounded-lg p-3 space-y-2 text-xs">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Custo do Serviço:</span>
                                <span class="text-emerald-400 font-bold font-mono">${{ number_format($hookerCost) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Ingressos Necessários:</span>
                                <span class="text-purple-400 font-bold">1 Ticket</span>
                            </div>
                            <div class="flex justify-between text-rose-400 font-semibold">
                                <span>Risco de Contágio (DST):</span>
                                <span>10% Chance (Internação)</span>
                            </div>
                        </div>
                    </div>

                    <button wire:click="buyHooker" wire:loading.attr="disabled"
                            class="w-full py-3 bg-rose-600 hover:bg-rose-550 text-white font-bold rounded-lg text-xs shadow transition">
                        💋 Contratar Serviço
                    </button>
                </div>

                <!-- Direita: Combate / Lutas -->
                <div class="p-6 bg-gray-900 border border-gray-850 rounded-xl space-y-4 shadow">
                    <h4 class="text-sm font-bold text-red-400 uppercase tracking-wider mb-2">⚔️ Resolver Disputa no Salão</h4>
                    <p class="text-xs text-gray-400 leading-relaxed">
                        Outros cafetões e clientes VIPs estão circulando pelos salões da mansão. Ataque-os caso sinta-se desrespeitado e roube o prestígio e a grana deles!
                    </p>

                    @if ($foe)
                        <div class="p-4 bg-gray-950 border border-gray-800 rounded-lg space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-rose-900/40 border border-rose-500/20 rounded-full flex items-center justify-center font-bold text-rose-300 text-lg">
                                    {{ strtoupper(substr($foe->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h5 class="font-bold text-white text-sm">{{ $foe->name }}</h5>
                                    <p class="text-[10px] text-gray-400">Respeito: <span class="text-yellow-400 font-mono font-semibold">{{ number_format($foe->respect) }}</span></p>
                                    <p class="text-[10px] text-gray-400">Força Estimada: <span class="text-red-400 font-mono font-semibold">{{ number_format($foe->assault_power) }}</span></p>
                                </div>
                            </div>

                            <button wire:click="fight({{ $foe->id }})" wire:loading.attr="disabled"
                                    class="w-full py-2 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded text-xs transition flex justify-center items-center gap-2">
                                <span wire:loading.remove wire:target="fight">⚔️ Atacar Rival</span>
                                <span wire:loading wire:target="fight" class="animate-spin">🌀 Lutando...</span>
                            </button>
                        </div>
                    @else
                        <p class="text-xs text-gray-500 italic py-4">Nenhum adversário encontrado no salão neste momento.</p>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
