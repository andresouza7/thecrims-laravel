<div>
    <!-- Always Visible Floating Toggle Button -->
    <button wire:click="toggle"
            type="button"
            style="position: fixed !important; bottom: 20px !important; right: 20px !important; z-index: 999999 !important; background: linear-gradient(135deg, #f59e0b, #d97706) !important; color: #000000 !important; font-weight: 800 !important; padding: 10px 18px !important; border-radius: 9999px !important; border: 2px solid #fef3c7 !important; box-shadow: 0 8px 25px rgba(245, 158, 11, 0.5), 0 4px 10px rgba(0,0,0,0.8) !important; cursor: pointer !important; display: flex !important; align-items: center !important; gap: 8px !important; font-size: 12px !important; text-transform: uppercase !important; letter-spacing: 0.05em !important;">
        <span style="font-size: 15px;">{{ $isOpen ? '✕' : '🛠️' }}</span> {{ $isOpen ? 'Fechar Debug' : 'Painel Debug' }}
    </button>

    <!-- Sidebar Panel (Non-blocking floating overlay) -->
    @if ($isOpen)
        <aside style="position: fixed !important; top: 80px !important; right: 20px !important; max-height: calc(100vh - 160px) !important; width: 360px !important; max-width: 90vw !important; background-color: rgba(3, 7, 18, 0.96) !important; backdrop-filter: blur(12px) !important; border: 1px solid #374151 !important; border-radius: 12px !important; box-shadow: 0 20px 50px rgba(0,0,0,0.9), 0 0 15px rgba(245, 158, 11, 0.2) !important; z-index: 999998 !important; padding: 16px !important; overflow-y: auto !important; color: #e5e7eb !important; font-family: ui-sans-serif, system-ui, sans-serif !important;">
            
            <div class="flex justify-between items-center border-b border-gray-800 pb-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="text-base">🛠️</span>
                    <div>
                        <h3 class="font-bold text-amber-400 text-xs">Painel Debug (Flutuante)</h3>
                        <p class="text-[9px] text-gray-400">Altere atributos e teste a tela ao mesmo tempo</p>
                    </div>
                </div>
                <button wire:click="toggle" type="button" class="text-gray-400 hover:text-white font-bold px-1.5 py-0.5 rounded bg-gray-800 hover:bg-gray-700 text-xs">✕ Fechar</button>
            </div>

            @if (session()->has('debug_msg'))
                <div class="p-2 mb-3 bg-emerald-950/90 border border-emerald-600 text-emerald-300 rounded font-semibold text-[11px]">
                    ✓ {{ session('debug_msg') }}
                </div>
            @endif

            <!-- Quick Career Actions -->
            <div class="space-y-2 p-2.5 mb-3 bg-amber-950/30 border border-amber-500/40 rounded-lg">
                <h4 class="font-bold text-amber-400 uppercase tracking-wider text-[9px]">🚀 Atalhos de Carreira</h4>
                <div class="grid grid-cols-1 gap-1.5 pt-0.5">
                    <button wire:click="autoFulfillNextLevelRequirements" type="button"
                            class="w-full py-1.5 px-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded transition text-[11px]">
                        ⚡ Preencher Requisitos do Próximo Nível
                    </button>
                    <button wire:click="resetCareerLevel" type="button"
                            class="w-full py-1 px-2 bg-gray-800 hover:bg-gray-700 text-gray-300 font-medium rounded transition text-[10px]">
                        ↺ Resetar para Nível 1
                    </button>
                </div>
            </div>

            <!-- Basic Stats Form -->
            <div class="space-y-2.5 p-2.5 mb-3 bg-gray-900/80 border border-gray-800 rounded-lg">
                <h4 class="font-bold text-amber-300 uppercase tracking-wider text-[9px]">💰 Finanças e Atributos</h4>
                <div class="grid grid-cols-2 gap-2 text-[11px]">
                    <div>
                        <label class="block text-gray-400 mb-0.5">Grana na Mão ($)</label>
                        <input type="number" wire:model="cash" class="w-full p-1.5 bg-gray-950 border border-gray-800 rounded text-gray-100 text-xs">
                    </div>
                    <div>
                        <label class="block text-gray-400 mb-0.5">Grana no Banco ($)</label>
                        <input type="number" wire:model="bank" class="w-full p-1.5 bg-gray-950 border border-gray-800 rounded text-gray-100 text-xs">
                    </div>
                    <div>
                        <label class="block text-gray-400 mb-0.5">Vida (Health)</label>
                        <input type="number" wire:model="health" class="w-full p-1.5 bg-gray-950 border border-gray-800 rounded text-gray-100 text-xs">
                    </div>
                    <div>
                        <label class="block text-gray-400 mb-0.5">Stamina (0-100)</label>
                        <input type="number" wire:model="stamina" class="w-full p-1.5 bg-gray-950 border border-gray-800 rounded text-gray-100 text-xs">
                    </div>
                    <div>
                        <label class="block text-gray-400 mb-0.5">Força</label>
                        <input type="number" wire:model="strength" class="w-full p-1.5 bg-gray-950 border border-gray-800 rounded text-gray-100 text-xs">
                    </div>
                    <div>
                        <label class="block text-gray-400 mb-0.5">Tolerância</label>
                        <input type="number" wire:model="tolerance" class="w-full p-1.5 bg-gray-950 border border-gray-800 rounded text-gray-100 text-xs">
                    </div>
                    <div>
                        <label class="block text-gray-400 mb-0.5">Carisma</label>
                        <input type="number" wire:model="charisma" class="w-full p-1.5 bg-gray-950 border border-gray-800 rounded text-gray-100 text-xs">
                    </div>
                    <div>
                        <label class="block text-gray-400 mb-0.5">Inteligência</label>
                        <input type="number" wire:model="intelligence" class="w-full p-1.5 bg-gray-950 border border-gray-800 rounded text-gray-100 text-xs">
                    </div>
                </div>
                <button wire:click="updateStats" type="button" class="w-full py-1.5 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded transition mt-1 text-xs">
                    Salvar Status do Jogador
                </button>

                <div class="grid grid-cols-2 gap-2 mt-2 pt-2 border-t border-gray-800/40">
                    @php
                        $user = auth()->user() ?? \App\Models\User::first();
                        $inJail = $user?->in_jail ?? false;
                        $inHospital = $user?->in_hospital ?? false;
                    @endphp
                    <button wire:click="toggleJailStatus" type="button"
                            class="py-1 px-2 font-semibold rounded text-[10px] transition {{ $inJail ? 'bg-emerald-600 hover:bg-emerald-500 text-white' : 'bg-rose-600 hover:bg-rose-500 text-white' }}">
                        {{ $inJail ? '🔓 Liberar da Cadeia' : '🔒 Mandar p/ Cadeia' }}
                    </button>
                    <button wire:click="toggleHospitalStatus" type="button"
                            class="py-1 px-2 font-semibold rounded text-[10px] transition {{ $inHospital ? 'bg-emerald-600 hover:bg-emerald-500 text-white' : 'bg-rose-600 hover:bg-rose-500 text-white' }}">
                        {{ $inHospital ? '🩺 Dar Alta Médica' : '🏥 Mandar p/ Hospital' }}
                    </button>
                </div>
            </div>

            <!-- Drug Manipulator -->
            <div class="space-y-2.5 p-2.5 mb-3 bg-gray-900/80 border border-gray-800 rounded-lg">
                <h4 class="font-bold text-rose-400 uppercase tracking-wider text-[9px]">💊 Drogas</h4>
                <div class="text-[11px]">
                    <label class="block text-gray-400 mb-0.5">Selecione a Droga</label>
                    <select wire:model.live="selectedDrugId" class="w-full p-1.5 bg-gray-950 border border-gray-800 rounded text-gray-100 text-xs">
                        <option value="">Selecione...</option>
                        @foreach ($drugs as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($selectedDrugId)
                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                        <div>
                            <label class="block text-gray-400 mb-0.5">Em Estoque</label>
                            <input type="number" wire:model="drugAmount" class="w-full p-1.5 bg-gray-950 border border-gray-800 rounded text-gray-100 text-xs">
                        </div>
                        <div>
                            <label class="block text-gray-400 mb-0.5">Total Vendido</label>
                            <input type="number" wire:model="drugTotalSold" class="w-full p-1.5 bg-gray-950 border border-gray-800 rounded text-gray-100 text-xs">
                        </div>
                    </div>
                    <button wire:click="updateDrug" type="button" class="w-full py-1.5 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded transition text-xs">
                        Atualizar Droga
                    </button>
                @endif
            </div>

            <!-- Hooker Manipulator -->
            <div class="space-y-2.5 p-2.5 mb-3 bg-gray-900/80 border border-gray-800 rounded-lg">
                <h4 class="font-bold text-purple-400 uppercase tracking-wider text-[9px]">👠 Prostitutas</h4>
                <div class="text-[11px]">
                    <label class="block text-gray-400 mb-0.5">Selecione o Tipo</label>
                    <select wire:model.live="selectedHookerId" class="w-full p-1.5 bg-gray-950 border border-gray-800 rounded text-gray-100 text-xs">
                        <option value="">Selecione...</option>
                        @foreach ($hookers as $h)
                            <option value="{{ $h->id }}">{{ $h->name }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($selectedHookerId)
                    <div class="text-[11px]">
                        <label class="block text-gray-400 mb-0.5">Quantidade Possuída</label>
                        <input type="number" wire:model="hookerAmount" class="w-full p-1.5 bg-gray-950 border border-gray-800 rounded text-gray-100 text-xs">
                    </div>
                    <button wire:click="updateHooker" type="button" class="w-full py-1.5 bg-purple-600 hover:bg-purple-500 text-white font-bold rounded transition text-xs">
                        Atualizar Prostituta
                    </button>
                @endif
            </div>

            <!-- Component Manipulator -->
            <div class="space-y-2.5 p-2.5 mb-3 bg-gray-900/80 border border-gray-800 rounded-lg">
                <h4 class="font-bold text-teal-400 uppercase tracking-wider text-[9px]">🧪 Componentes</h4>
                <div class="text-[11px]">
                    <label class="block text-gray-400 mb-0.5">Selecione o Componente</label>
                    <select wire:model.live="selectedComponentId" class="w-full p-1.5 bg-gray-950 border border-gray-800 rounded text-gray-100 text-xs">
                        <option value="">Selecione...</option>
                        @foreach ($components as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($selectedComponentId)
                    <div class="text-[11px]">
                        <label class="block text-gray-400 mb-0.5">Quantidade Possuída</label>
                        <input type="number" wire:model="componentAmount" class="w-full p-1.5 bg-gray-950 border border-gray-800 rounded text-gray-100 text-xs">
                    </div>
                    <button wire:click="updateComponent" type="button" class="w-full py-1.5 bg-teal-600 hover:bg-teal-500 text-white font-bold rounded transition text-xs">
                        Atualizar Componente
                    </button>
                @endif
            </div>

            <!-- Equipment Manipulator -->
            <div class="space-y-2.5 p-2.5 mb-3 bg-gray-900/80 border border-gray-800 rounded-lg">
                <h4 class="font-bold text-blue-400 uppercase tracking-wider text-[9px]">🛡️ Equipamentos</h4>
                <div class="text-[11px]">
                    <label class="block text-gray-400 mb-0.5">Selecione o Equipamento</label>
                    <select wire:model.live="selectedEquipmentId" class="w-full p-1.5 bg-gray-950 border border-gray-800 rounded text-gray-100 text-xs">
                        <option value="">Selecione...</option>
                        @foreach ($equipment as $eq)
                            <option value="{{ $eq->id }}">
                                [{{ strtoupper($eq->type) }}] {{ $eq->name }} ({{ in_array($eq->id, $userEquipmentIds) ? 'POSSUI' : 'NÃO POSSUI' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @if ($selectedEquipmentId)
                    <button wire:click="toggleEquipment" type="button" class="w-full py-1.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded transition text-xs">
                        Alternar Equipamento
                    </button>
                @endif
            </div>

        </aside>
    @endif
</div>
