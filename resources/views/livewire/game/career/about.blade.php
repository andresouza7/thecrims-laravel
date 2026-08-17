<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-gray-800 pb-3">
        <h2 class="text-2xl font-bold text-white">💼 Especialização & Carreira</h2>
        <div class="text-xs text-gray-400">Evolua seu personagem no crime organizado</div>
    </div>

    @if ($userCareer)
        <div class="p-4 bg-gray-800 border border-gray-700 rounded-lg space-y-4">
            <div>
                <h3 class="text-xl font-bold text-amber-400">{{ $userCareer->name }}</h3>
                <p class="text-xs text-gray-400">Progresso de níveis e recompensas</p>
            </div>

            <div class="space-y-4 pt-2">
                @foreach ($userCareer->levels as $level)
                    <div class="p-4 bg-gray-900 border border-gray-700/80 rounded-lg space-y-2">
                        <div class="flex justify-between items-center border-b border-gray-800 pb-2">
                            <h4 class="font-bold text-white text-base">Nível {{ $level['level'] }}: {{ $level['name'] }}</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs pt-2">
                            <div>
                                <h5 class="font-bold text-rose-400 mb-1 uppercase tracking-wider">Requisitos</h5>
                                <ul class="list-disc list-inside text-gray-300 space-y-1">
                                    @forelse ($level['requirements'] as $req)
                                        <li>{{ is_array($req) ? json_encode($req) : $req }}</li>
                                    @empty
                                        <li class="italic text-gray-500">Sem requisitos específicos.</li>
                                    @endforelse
                                </ul>
                            </div>

                            <div>
                                <h5 class="font-bold text-emerald-400 mb-1 uppercase tracking-wider">Recompensas</h5>
                                <ul class="list-disc list-inside text-gray-300 space-y-1">
                                    @forelse ($level['rewards'] as $rew)
                                        <li>{{ is_array($rew) ? json_encode($rew) : $rew }}</li>
                                    @empty
                                        <li class="italic text-gray-500">Sem recompensas específicas.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <!-- Escolher Carreira -->
        <div class="p-6 bg-gray-800 border border-gray-700 rounded-lg space-y-4 max-w-lg mx-auto">
            <h3 class="text-xl font-bold text-white text-center">Escolha sua Carreira Criminal</h3>
            <p class="text-xs text-gray-400 text-center">Defina sua especialização para desbloquear bônus exclusivos.</p>

            <div class="space-y-3">
                <select wire:model="selectedCareerId" class="w-full p-2.5 bg-gray-900 border border-gray-700 rounded text-sm text-gray-100">
                    <option value="">Selecione uma carreira...</option>
                    @foreach ($careers as $career)
                        <option value="{{ $career->id }}">{{ $career->name }}</option>
                    @endforeach
                </select>

                <button wire:click="selectCareer" wire:loading.attr="disabled"
                        class="w-full py-2.5 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded text-sm transition">
                    Confirmar Escolha de Carreira
                </button>
            </div>
        </div>
    @endif
</div>
