<div class="p-4 bg-gray-900 border border-gray-800 rounded-lg shadow text-gray-100">
    <h4 class="font-semibold text-gray-300 mb-3 border-b border-gray-800 pb-1">Status do Jogador</h4>

    @if ($user)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 text-sm">
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
                            style="width: {{ min(100, max(0, ($user->health / max(1, $user->max_health)) * 100)) }}%"></div>
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
                <div><span class="text-gray-400">Nome:</span> <span class="font-semibold text-white">{{ $user->name }}</span></div>
                <div><span class="text-gray-400">Carreira:</span> <span class="text-amber-400 font-medium">{{ $user->career?->name ?? 'Nenhuma' }}</span></div>
                <div><span class="text-gray-400">Grana:</span> <span class="text-emerald-400 font-semibold">${{ number_format($user->cash) }}</span></div>
                <div><span class="text-gray-400">Respeito:</span> <span class="text-yellow-400 font-semibold">{{ number_format($user->respect) }}</span></div>
                @if ($user->in_jail)
                    <div class="text-xs text-rose-400 font-semibold mt-1">🔒 Preso até: {{ $user->jail_end_time }}</div>
                @endif
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
                <div>Assalto Solo: <span class="font-mono font-semibold text-amber-300">{{ $user->single_robbery_power }}</span></div>
                <div>Assalto Gangue: <span class="font-mono font-semibold text-amber-300">{{ $user->gang_robbery_power }}</span></div>
                <div>Ataque Direto: <span class="font-mono font-semibold text-red-400">{{ $user->assault_power }}</span></div>
            </div>

            <!-- Equipment -->
            <div class="space-y-1">
                <h5 class="font-semibold text-xs text-gray-400 uppercase tracking-wider mb-1">Equipamento</h5>
                <div>Armadura: <span class="font-medium text-blue-300">{{ $user->armor?->name ?? '-' }}</span></div>
                <div>Arma: <span class="font-medium text-red-300">{{ $user->weapon?->name ?? '-' }}</span></div>
            </div>
        </div>
    @endif
</div>
