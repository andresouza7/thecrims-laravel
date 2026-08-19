<div class="space-y-6">
    <!-- Header e Descrição Humorada -->
    <div class="flex flex-col gap-2 border-b border-gray-800 pb-4 text-left">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                🏥 Hospital de Campanha
            </h2>
            <span class="text-xs px-2.5 py-1 bg-emerald-950/80 text-emerald-400 border border-emerald-900 rounded-full font-mono font-bold">
                {{ $user && $user->in_hospital ? 'Sob Observação' : 'Disponível' }}
            </span>
        </div>
        <p class="text-sm text-gray-400 leading-relaxed">
            Bem-vindo à ala médica de Crims City, onde o juramento de Hipócrates é meramente sugestivo e a conta do hospital é maior do que o seu saldo bancário. Se você abusou das drogas ou perdeu uma briga de rua, nós remendamos você... contanto que possa pagar a taxa!
        </p>
    </div>

    <!-- Layout Dividido em Duas Colunas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch">
        
        <!-- Esquerda: Imagem que ocupa todo o espaço -->
        <div class="overflow-hidden rounded-xl border border-gray-800 shadow-xl h-64 md:h-auto min-h-[350px] relative">
            <img src="/images/hospital_ward.png" alt="Ala Médica do Hospital" class="w-full h-full object-cover absolute inset-0">
        </div>

        <!-- Direita: Conteúdo Restante (Serviços) -->
        <div class="p-5 bg-gray-900 border border-gray-800 rounded-xl shadow-lg flex flex-col justify-between">
            <div>
                <h3 class="text-xs text-gray-400 uppercase tracking-wider font-bold border-b border-gray-850 pb-2 mb-4">💉 Serviços Médicos & Tratamentos</h3>
                
                @if ($user && $user->in_hospital)
                    <!-- Caso o jogador esteja internado (em estado grave) -->
                    <div class="space-y-4 text-center py-8 flex flex-col justify-center items-center">
                        <div class="w-14 h-14 bg-red-950/50 border border-red-500/30 rounded-full flex items-center justify-center mx-auto text-2xl select-none animate-pulse mb-2">
                            🩺
                        </div>

                        <div class="bg-gray-950/80 border border-gray-800 rounded-xl py-3 px-6 inline-block mx-auto shadow-inner mb-2">
                            <div class="text-[10px] uppercase tracking-wider text-gray-500 font-bold mb-1">Tempo Restante de Internação</div>
                            <div class="font-mono text-xl font-bold text-red-500">
                                {{ $user->hospital_end_time }}
                            </div>
                        </div>

                        <p class="text-sm text-gray-300 leading-relaxed max-w-sm">
                            Você está internado devido a ferimentos graves ou overdose. Você não pode cometer crimes ou circular até receber alta formal.
                        </p>
                        
                        <button wire:click="release" wire:loading.attr="disabled"
                                class="w-full max-w-xs py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg transition text-xs shadow-md mt-2">
                            🩺 Receber Alta Médica
                        </button>
                    </div>
                @else
                    <!-- Caso o jogador esteja saudável e circulando livremente -->
                    <div class="space-y-4">
                        <!-- Serviço 1: Injeção de Adrenalina Sintética (Energetico) -->
                        <div class="p-4 bg-gray-950/80 border border-gray-850 rounded-lg flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div class="space-y-1 mt-1">
                                <h4 class="text-sm font-bold text-amber-400">⚡ Sorinho Turbinado com Adrenalina</h4>
                                <p class="text-xs text-gray-400 leading-relaxed">
                                    Uma injeção concentrada de estimulantes para te deixar elétrico. Restaura <strong>100% da Stamina</strong> imediatamente.
                                </p>
                                <div class="text-[10px] text-purple-400 font-semibold">
                                    ⚠️ Aumenta o Vício em +15%.
                                </div>
                            </div>
                            <div class="w-full sm:w-auto text-right">
                                <button wire:click="buyStamina" wire:loading.attr="disabled"
                                        class="w-full sm:w-auto px-4 py-2 bg-yellow-500 hover:bg-yellow-450 text-gray-950 font-bold text-xs rounded transition shadow-md whitespace-nowrap">
                                    Tomar (${{ number_format($staminaCost) }})
                                </button>
                            </div>
                        </div>

                        <!-- Serviço 2: Desintoxicação -->
                        <div class="p-4 bg-gray-950/80 border border-gray-850 rounded-lg flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div class="space-y-1 mt-1">
                                <h4 class="text-sm font-bold text-sky-400">🧼 Programa de Desintoxicação Intensiva</h4>
                                <p class="text-xs text-gray-400 leading-relaxed">
                                    Uma reabilitação rápida nos fundos do hospital. Limpa completamente seu vício corporal, <strong>zerando o vício</strong>.
                                </p>
                                <div class="text-[10px] text-emerald-400 font-semibold">
                                    🛡️ Reduz o Vício para 0%.
                                </div>
                            </div>
                            <div class="w-full sm:w-auto text-right">
                                <button wire:click="buyDetox" wire:loading.attr="disabled"
                                        class="w-full sm:w-auto px-4 py-2 bg-yellow-500 hover:bg-yellow-450 text-gray-950 font-bold text-xs rounded transition shadow-md whitespace-nowrap">
                                    Limpar (${{ number_format($detoxCost) }})
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @if (!$user->in_hospital)
                <div class="pt-3 text-center border-t border-gray-850 mt-4">
                    <a href="{{ route('home') }}" wire:navigate
                       class="inline-block text-xs text-indigo-400 hover:text-indigo-300 underline font-medium">
                        Voltar para o painel principal
                    </a>
                </div>
            @endif
        </div>

    </div>
</div>
