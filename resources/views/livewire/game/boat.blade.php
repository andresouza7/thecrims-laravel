<div class="space-y-6">
    <!-- Header e Descrição -->
    <div class="flex flex-col gap-2 border-b border-gray-800 pb-4">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-white flex items-center gap-2">🚢 Docas & Navios Cargueiros</h2>
            <span class="text-xs px-2.5 py-1 bg-gray-800 text-gray-400 border border-gray-700 rounded-full font-mono">Porto Ativo</span>
        </div>
        <p class="text-sm text-gray-400 leading-relaxed">
            Bem-vindo às <strong>Docas</strong>. Navios de carga atracam no porto periodicamente procurando por drogas específicas para comprar em atacado. Vender para os navios é extremamente lucrativo e concede um bônus com base na sua reputação de negócios. Planeje seu estoque e aproveite os multiplicadores!
        </p>
    </div>

    <!-- Grid Principal: Barco Atual e Próximo Barco + Lucros -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Lado Esquerdo/Centro: Barco Ativo (Ocupa 2 colunas no desktop) -->
        <div class="lg:col-span-2 space-y-6">
            <h3 class="text-lg font-bold text-gray-200 flex items-center gap-2">⚓ Embarcação no Porto</h3>
            
            @if (isset($data['boats']) && count($data['boats']))
                @foreach ($data['boats'] as $boat)
                    <div class="p-6 bg-gray-900 border border-gray-800 rounded-xl space-y-6 shadow-xl relative overflow-hidden group">
                        <!-- Efeito visual de container -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-sky-500/5 rounded-full blur-3xl pointer-events-none transition-all group-hover:bg-sky-500/10"></div>
                        
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <span class="px-2 py-0.5 bg-sky-950 text-sky-400 border border-sky-900 rounded text-[10px] uppercase tracking-wider font-bold">Atracado</span>
                                <h4 class="font-extrabold text-white text-2xl mt-1">{{ $boat->name ?? 'Navio Cargueiro ' . $boat->id }}</h4>
                                <p class="text-xs text-gray-400 mt-1">Interesse de Compra: <span class="text-sky-400 font-bold">{{ $boat->drug?->name ?? 'Droga' }}</span></p>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] text-gray-500 uppercase font-mono">Preço por Unidade</span>
                                <p class="text-2xl font-black text-emerald-400 font-mono">${{ number_format($boat->price ?? 0) }}</p>
                            </div>
                        </div>

                        <!-- Info de Estoque -->
                        <div class="p-4 bg-gray-950/60 border border-gray-800/80 rounded-lg flex justify-between items-center text-sm">
                            <span class="text-gray-400">Seu estoque de <strong class="text-white">{{ $boat->drug?->name }}</strong>:</span>
                            <span class="font-bold font-mono text-gray-200">{{ number_format($data['owned_amount'] ?? 0) }} unidades</span>
                        </div>

                        <!-- Ações de Venda -->
                        <div class="space-y-3 pt-2">
                            <div class="flex items-center gap-3">
                                <div class="relative flex-1">
                                    <input type="number" min="1" max="{{ $data['owned_amount'] ?? 0 }}" wire:model="amounts.{{ $boat->id }}" placeholder="Quantidade para vender"
                                           class="w-full pl-3 pr-20 py-3 bg-gray-950 border border-gray-800 focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 rounded-lg text-sm text-gray-100 placeholder-gray-600 transition font-mono font-bold">
                                    <button type="button" @click="$wire.set('amounts.{{ $boat->id }}', {{ $data['owned_amount'] ?? 0 }})" 
                                            class="absolute right-2 top-2 px-2.5 py-1 bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white rounded text-[10px] font-bold uppercase transition">
                                        Max
                                    </button>
                                </div>
                                <button wire:click="sell({{ $boat->id }})" 
                                        class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-lg shadow-lg hover:shadow-emerald-900/20 transition whitespace-nowrap">
                                    🚢 Vender Carregamento
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="p-8 bg-gray-900/60 border border-gray-800/80 rounded-xl text-center space-y-4 shadow-inner">
                    <div class="text-4xl text-gray-600">🌊</div>
                    <div>
                        <p class="text-lg font-bold text-gray-300">Nenhum navio no porto hoje</p>
                        <p class="text-xs text-gray-500 max-w-sm mx-auto mt-1">
                            Aguarde as atualizações dos dias de jogo para que novas embarcações cheguem às docas.
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Lado Direito: Lucros e Próxima Embarcação (Ocupa 1 coluna no desktop) -->
        <div class="space-y-6">
            <h3 class="text-lg font-bold text-gray-200 flex items-center gap-2">📊 Relatório do Porto</h3>

            <!-- Card de Lucro Total -->
            <div class="p-5 bg-gradient-to-br from-gray-900 to-gray-950 border border-gray-800 rounded-xl shadow space-y-2">
                <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Lucro Total com Barcos</span>
                <div class="text-3xl font-black text-amber-500 font-mono">${{ number_format($data['boat_profits'] ?? 0) }}</div>
                <p class="text-[10px] text-gray-500">Representa todo o dinheiro acumulado de exportações neste round.</p>
            </div>

            <!-- Card do Próximo Barco -->
            <div class="p-5 bg-gray-900 border border-gray-800 rounded-xl shadow space-y-4">
                <span class="text-[10px] text-sky-400 uppercase tracking-wider font-bold">⚓ Próxima Chegada</span>
                
                @if (isset($data['next_boat']))
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-400">Dia de Chegada:</span>
                            <span class="font-bold text-white font-mono">Dia {{ $data['next_boat']['day'] }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-400">Procura por:</span>
                            <span class="font-bold text-white">{{ $data['next_boat']['drug']['name'] ?? 'Droga' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-400">Preço Base:</span>
                            <span class="font-mono text-emerald-400">${{ number_format($data['next_boat']['price'] ?? 0) }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-xs text-gray-500 italic">Nenhum navio programado para os próximos dias.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Seção de Reputação & Multiplicadores -->
    <div class="p-6 bg-gray-900 border border-gray-800 rounded-xl space-y-5 shadow-lg">
        <div>
            <h3 class="text-lg font-bold text-gray-200">🏆 Multiplicador e Reputação</h3>
            <p class="text-xs text-gray-400 mt-1">Seu multiplicador aumenta conforme você acumula lucros totais negociando com barcos.</p>
        </div>

        <!-- Lista/Grid de Boosts -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @if (isset($data['all_boosts']))
                @foreach ($data['all_boosts'] as $boost)
                    @php
                        $isCurrent = isset($data['current_boost']) && $data['current_boost']['label'] === $boost['label'];
                        $isLocked = ($data['boat_profits'] ?? 0) < $boost['min'];
                    @endphp
                    <div class="p-4 rounded-lg border transition-all {{ $isCurrent ? 'bg-sky-950/40 border-sky-500/80 shadow-sky-950/20' : 'bg-gray-950/40 border-gray-800/60' }} space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-sm {{ $isCurrent ? 'text-sky-300' : 'text-gray-300' }}">{{ $boost['label'] }}</h4>
                                <p class="text-[10px] text-gray-500 mt-0.5">
                                    @if ($boost['min'] == 0)
                                        Inicial
                                    @elseif (is_infinite($boost['max']))
                                        Mais de ${{ number_format($boost['min']) }}
                                    @else
                                        ${{ number_format($boost['min']) }} a ${{ number_format($boost['max']) }}
                                    @endif
                                </p>
                            </div>
                            <span class="text-lg font-black font-mono {{ $isCurrent ? 'text-sky-400' : 'text-gray-500' }}">
                                {{ number_format($boost['multiplier'], 1) }}x
                            </span>
                        </div>

                        <!-- Status Badge -->
                        <div class="flex justify-between items-center text-[10px] pt-1">
                            @if ($isCurrent)
                                <span class="px-2 py-0.5 bg-sky-900/60 text-sky-300 border border-sky-850 rounded font-bold uppercase">Ativo</span>
                                <span class="text-gray-400 font-mono">Seu status atual</span>
                            @elseif ($isLocked)
                                <span class="px-2 py-0.5 bg-gray-900 text-gray-500 border border-gray-850 rounded font-bold uppercase">Bloqueado</span>
                                @if (isset($data['next_boost']['remaining']) && $data['next_boost']['nextBoostLabel'] === $boost['label'])
                                    <span class="text-amber-500 font-semibold font-mono">Falta: ${{ number_format($data['next_boost']['remaining']) }}</span>
                                @endif
                            @else
                                <span class="px-2 py-0.5 bg-emerald-950/40 text-emerald-400 border border-emerald-900 rounded font-bold uppercase">Superado</span>
                                <span class="text-gray-500">Concluído</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
