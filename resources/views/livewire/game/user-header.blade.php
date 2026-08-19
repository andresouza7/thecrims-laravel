<div wire:poll.5s class="p-4 bg-gray-900 border border-gray-800 rounded-lg shadow text-gray-100">
    @if ($shouldRedirect)
        <div x-data x-init="Livewire.navigate('{{ $redirectUrl }}')"></div>
    @endif

    <div class="flex justify-between items-center mb-3 border-b border-gray-800 pb-1">
        <h4 class="font-semibold text-gray-300">Status do Jogador</h4>
        <div class="flex items-center gap-2 text-xs">
            <span class="text-gray-400 font-medium">Tempo de Jogo:</span>
            <span class="bg-gray-800 text-amber-400 font-mono px-2 py-0.5 rounded border border-gray-700 font-bold">Dia {{ $gameDay }}</span>
            <span class="bg-gray-800 text-sky-400 font-mono px-2 py-0.5 rounded border border-gray-700 font-bold">{{ $gameTime }}</span>
        </div>
    </div>

    @if ($user)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 text-xs">
            <!-- Bars (Health, Stamina, Addiction) -->
            <div class="flex flex-col gap-2">
                <!-- Health -->
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-400">Vida</span>
                        <span class="font-mono text-red-400">{{ $user->health }} / {{ $user->max_health }}</span>
                    </div>
                    <div class="w-full bg-gray-800 rounded h-2.5 overflow-hidden">
                        <div class="bg-red-500 h-2.5 rounded transition-all duration-300"
                            style="width: {{ min(100, max(0, ($user->health / max(1, $user->max_health)) * 100)) }}%">
                        </div>
                    </div>
                </div>

                <!-- Stamina -->
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-400">Stamina</span>
                        <span class="font-mono text-emerald-400">{{ $user->stamina }}%</span>
                    </div>
                    <div class="w-full bg-gray-800 rounded h-2.5 overflow-hidden">
                        <div class="bg-emerald-500 h-2.5 rounded transition-all duration-300"
                            style="width: {{ min(100, max(0, $user->stamina)) }}%"></div>
                    </div>
                </div>

                <!-- Addiction -->
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-400">Vício</span>
                        <span class="font-mono text-purple-400">{{ $user->addiction }}%</span>
                    </div>
                    <div class="w-full bg-gray-800 rounded h-2.5 overflow-hidden">
                        <div class="bg-purple-500 h-2.5 rounded transition-all duration-300"
                            style="width: {{ min(100, max(0, $user->addiction)) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Basic Info -->
            <div class="space-y-1">
                <div><span class="text-gray-400">Nome:</span> <span
                        class="font-semibold text-white">{{ $user->name }}</span></div>
                <div><span class="text-gray-400">Carreira:</span>
                    <a href="{{ route('career.about') }}" wire:navigate.hover class="text-amber-400 hover:text-amber-300 hover:underline font-medium">
                        {{ $user->career?->name ?? 'Nenhuma' }}
                    </a>
                </div>
                <div><span class="text-gray-400">Grana:</span> <span
                        class="text-emerald-400 font-semibold">${{ number_format($user->cash) }}</span></div>
                <div><span class="text-gray-400">Respeito:</span> <span
                        class="text-yellow-400 font-semibold">{{ number_format($user->respect) }}</span></div>

            </div>

            <!-- Attributes -->
            <div class="space-y-1">
                <h5 class="font-semibold text-xs text-gray-400 uppercase tracking-wider mb-1">Atributos</h5>
                <div>Força: <span class="font-mono font-semibold">{{ $user->strength }}</span></div>
                <div>Inteligência: <span class="font-mono font-semibold">{{ $user->intelligence }}</span></div>
                <div>Carisma: <span class="font-mono font-semibold">{{ $user->charisma }}</span></div>
                <div>Tolerância: <span class="font-mono font-semibold">{{ $user->tolerance }}</span></div>
            </div>

            <!-- Powers -->
            <div class="space-y-1">
                <h5 class="font-semibold text-xs text-gray-400 uppercase tracking-wider mb-1">Poder de Ataque</h5>
                <div>Assalto Solo: <span
                        class="font-mono font-semibold text-amber-300">{{ $user->single_robbery_power }}</span></div>
                <div>Assalto Gangue: <span
                        class="font-mono font-semibold text-amber-300">{{ $user->gang_robbery_power }}</span></div>
                <div>Ataque Direto: <span
                        class="font-mono font-semibold text-red-400">{{ $user->assault_power }}</span></div>
            </div>

            <!-- Equipment -->
            <div class="space-y-1">
                <h5 class="font-semibold text-xs text-gray-400 uppercase tracking-wider mb-1">Equipamento</h5>
                <div>Armadura: <span class="font-medium text-blue-300">{{ $user->armor?->name ?? '-' }}</span></div>
                <div>Arma: <span class="font-medium text-red-300">{{ $user->weapon?->name ?? '-' }}</span></div>
                <div class="pt-1">
                    <a href="{{ route('inventory.index') }}" wire:navigate.hover class="text-yellow-400 hover:text-yellow-350 hover:underline font-semibold text-xs inline-flex items-center gap-1">
                        📦 Inventário
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
