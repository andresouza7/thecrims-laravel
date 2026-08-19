@php
    $user = auth()->user() ?? \App\Models\User::first();
    $jailEndTime = $user?->jail_end_time;
    $inJail = $user?->in_jail ?? false;
@endphp

<div class="space-y-6">
    <!-- Header e Descrição Humorada -->
    <div class="flex flex-col gap-2 border-b border-gray-800 pb-4 text-left">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                👮 {{ $jailEndTime ? 'Cela de Detenção Provisória' : 'Portaria do Presídio' }}
            </h2>
            <span class="text-xs px-2.5 py-1 bg-red-950/80 text-red-400 border border-red-900 rounded-full font-mono font-bold">
                {{ $jailEndTime ? 'Atrás das Grades' : 'Liberdade Temporária' }}
            </span>
        </div>
        <p class="text-sm text-gray-400 leading-relaxed">
            Seja bem-vindo à Colônia Penal de Crims City! Onde a comida tem gosto de sabão, os companheiros de cela querem roubar sua escova de dentes e o suborno do guarda custa mais do que o seu almoço. Pelo menos a estadia é temporária... se você tiver dinheiro.
        </p>
    </div>

    <!-- Layout Dividido em Duas Colunas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch">
        
        <!-- Esquerda: Imagem que ocupa todo o espaço -->
        <div class="overflow-hidden rounded-xl border border-gray-800 shadow-xl h-64 md:h-auto min-h-[350px] relative">
            <img src="/images/jail_cell.png" alt="Cela de Prisão" class="w-full h-full object-cover absolute inset-0">
        </div>

        <!-- Direita: Conteúdo Restante -->
        <div class="flex flex-col justify-between">
            @if ($jailEndTime)
                <div class="p-6 bg-gray-900 border border-red-950/60 rounded-xl space-y-6 shadow-lg flex-1 flex flex-col justify-center">
                    <div class="text-center space-y-4">
                        <div class="w-14 h-14 bg-red-950/50 border border-red-500/30 rounded-full flex items-center justify-center mx-auto text-2xl select-none animate-pulse">
                            🔒
                        </div>

                        <div class="bg-gray-950/80 border border-gray-800 rounded-xl py-3 px-6 inline-block mx-auto shadow-inner">
                            <div class="text-[10px] uppercase tracking-wider text-gray-500 font-bold mb-1">Tempo Restante de Pena</div>
                            <div class="font-mono text-xl font-bold text-red-500">
                                {{ $jailEndTime }}
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-red-200">Você rodou na operação!</h3>
                        <p class="text-sm text-gray-300 leading-relaxed max-w-sm mx-auto">
                            O delegado não aceitou conversa e te trancou. Se quiser acelerar a sua volta para as ruas, pode tentar convencer o guarda com um belo lanche gourmet (ou uma maleta de notas).
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 pt-3 justify-center max-w-sm mx-auto w-full">
                        @if ($inJail)
                            <button wire:click="bribe" wire:loading.attr="disabled"
                                    class="w-full py-3 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-lg transition text-xs shadow-md">
                                💵 Subornar Guardas (${{ number_format($user->jail_release_cost) }})
                            </button>
                        @else
                            <button wire:click="release" wire:loading.attr="disabled"
                                    class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg transition text-xs shadow-md animate-bounce">
                                🔓 Reivindicar Liberdade (Pena Cumprida)
                            </button>
                        @endif
                    </div>
                </div>
            @else
                <div class="p-6 bg-gray-900 border border-gray-800 rounded-xl space-y-5 text-center shadow-lg flex-1 flex flex-col justify-center">
                    <div class="w-16 h-16 bg-gray-950 border border-gray-800 rounded-full flex items-center justify-center mx-auto text-3xl select-none">
                        🕊️
                    </div>

                    <h3 class="text-xl font-black text-emerald-400">Ficha Limpa!</h3>
                    <p class="text-sm text-gray-300 leading-relaxed max-w-md mx-auto">
                        Você está livre das grades por enquanto. Os federais não têm nada contra você hoje. Aproveite para voltar à ativa antes que alguém te denuncie!
                    </p>
                    
                    <blockquote class="text-[11px] text-amber-500 italic bg-gray-950/60 p-3 rounded-lg border border-gray-850 max-w-sm mx-auto">
                        "Conselho de amigo: não volte aqui. O café da manhã é pior do que as piadas do guarda do turno da noite."
                    </blockquote>
                    
                    <div class="pt-2">
                        <a href="{{ route('home') }}" wire:navigate
                           class="inline-block px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-lg transition shadow">
                            Voltar ao Crime
                        </a>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
