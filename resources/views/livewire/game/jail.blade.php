@php
    $user = auth()->user() ?? \App\Models\User::first();
    $jailEndTime = $user?->jail_end_time;
    $inJail = $user?->in_jail ?? false;
@endphp

<div class="space-y-6">
    <div class="border-b border-gray-800 pb-3 text-center">
        <h2 class="text-2xl font-bold {{ $jailEndTime ? 'text-rose-500' : 'text-emerald-500' }}">
            {{ $jailEndTime ? '🔒 Prisão Estadual' : '🚪 Portões da Prisão' }}
        </h2>
        <p class="text-xs text-gray-400">
            {{ $jailEndTime ? 'Você foi pego pela polícia ou traído por um comparsa...' : 'O complexo de detenção estadual' }}
        </p>
    </div>

    @if ($jailEndTime)
        <div class="p-6 bg-gray-900 border border-red-950/60 rounded-xl space-y-4 text-center shadow-lg">
            <div class="w-16 h-16 bg-red-950/50 border border-red-500/30 rounded-full flex items-center justify-center mx-auto text-3xl select-none animate-pulse">
                👮
            </div>

            <!-- Tempo de soltura vindo diretamente do backend -->
            <div class="bg-gray-950/80 border border-gray-800 rounded-xl py-3 px-6 inline-block mx-auto shadow-inner">
                <div class="text-[10px] uppercase tracking-wider text-gray-500 font-bold mb-1">Tempo de Soltura</div>
                <div class="font-mono text-sm font-bold text-red-500">
                    {{ $jailEndTime }}
                </div>
            </div>

            <h3 class="text-lg font-bold text-red-200">Atrás das Grades</h3>
            <p class="text-sm text-gray-300 max-w-md mx-auto leading-relaxed">
                As coisas complicaram para o seu lado. Você pode cumprir a sua pena ou subornar os guardas se quiser sair imediatamente.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 pt-3 justify-center max-w-md mx-auto">
                @if ($inJail)
                    <button wire:click="bribe" wire:loading.attr="disabled"
                            class="w-full py-2.5 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-lg transition text-xs shadow-md">
                        💵 Pagar Suborno (${{ number_format($user->jail_release_cost) }})
                    </button>
                @else
                    <button wire:click="release" wire:loading.attr="disabled"
                            class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg transition text-xs shadow-md animate-bounce">
                        🔓 Sair da Cadeia (Pena Cumprida - Grátis)
                    </button>
                @endif
            </div>
        </div>
    @else
        <div class="p-8 bg-gray-900 border border-gray-800 rounded-xl space-y-4 text-center shadow-lg max-w-lg mx-auto">
            <div class="w-20 h-20 bg-gray-950 border border-gray-800/80 rounded-full flex items-center justify-center mx-auto text-4xl select-none">
                🕊️
            </div>

            <h3 class="text-xl font-bold text-emerald-400">Você está Livre!</h3>
            <p class="text-sm text-gray-300 leading-relaxed">
                Os portões estão abertos e você está solto por aí. Por enquanto...
            </p>
            <blockquote class="text-xs text-amber-500 italic bg-gray-950/60 p-3 rounded-lg border border-gray-850">
                "Confie em mim: você não quer vir parar atrás dessas grades. A comida é péssima e os ratos são do tamanho de gatos."
            </blockquote>
            <p class="text-xs text-gray-500 pt-2">
                Continue fazendo seus negócios nas ruas, mas evite vacilar para a polícia.
            </p>
        </div>
    @endif
</div>
