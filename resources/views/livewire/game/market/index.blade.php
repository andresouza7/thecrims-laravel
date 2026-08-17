<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-gray-800 pb-3">
        <h2 class="text-2xl font-bold text-white">🛍️ Mercado Negro</h2>
        <div class="text-xs text-gray-400">Adquira armas, armaduras e componentes</div>
    </div>

    <!-- Tabs do Mercado -->
    <div class="flex flex-wrap gap-2 border-b border-gray-800 pb-3">
        <button wire:click="$set('tab', 'armors')"
                class="px-4 py-2 rounded text-sm font-semibold transition {{ $tab === 'armors' ? 'bg-indigo-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }}">
            🛡️ Armaduras
        </button>
        <button wire:click="$set('tab', 'weapons')"
                class="px-4 py-2 rounded text-sm font-semibold transition {{ $tab === 'weapons' ? 'bg-indigo-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }}">
            ⚔️ Armas
        </button>
        <button wire:click="$set('tab', 'components')"
                class="px-4 py-2 rounded text-sm font-semibold transition {{ $tab === 'components' ? 'bg-indigo-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }}">
            🧱 Componentes
        </button>
        <button wire:click="$set('tab', 'items')"
                class="px-4 py-2 rounded text-sm font-semibold transition {{ $tab === 'items' ? 'bg-indigo-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }}">
            📦 Itens Especiais
        </button>
    </div>

    <!-- Conteúdo da Tab -->
    @if ($tab === 'armors')
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-gray-200">Armaduras Disponíveis</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($armors as $armor)
                    <div class="p-4 bg-gray-800 border border-gray-700 rounded-lg flex flex-col justify-between space-y-3">
                        <div>
                            <h4 class="font-bold text-sky-400 text-lg">{{ $armor->name }}</h4>
                            <p class="text-xs text-gray-400">Preço: <strong class="text-emerald-400 font-mono">${{ number_format($armor->price) }}</strong></p>
                        </div>
                        <button wire:click="buy({{ $armor->id }})" class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded">
                            Comprar Armadura
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif ($tab === 'weapons')
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-gray-200">Armas Disponíveis</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($weapons as $weapon)
                    <div class="p-4 bg-gray-800 border border-gray-700 rounded-lg flex flex-col justify-between space-y-3">
                        <div>
                            <h4 class="font-bold text-red-400 text-lg">{{ $weapon->name }}</h4>
                            <p class="text-xs text-gray-400">Preço: <strong class="text-emerald-400 font-mono">${{ number_format($weapon->price) }}</strong></p>
                        </div>
                        <button wire:click="buy({{ $weapon->id }})" class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded">
                            Comprar Arma
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif ($tab === 'components')
        <div class="p-4 bg-gray-800 border border-gray-700 rounded-lg text-gray-300">
            <h3 class="font-bold text-white text-lg mb-2">Leilão & Mercado de Componentes</h3>
            <p class="text-xs text-gray-400">Componentes de laboratório estão disponíveis para negociação durante os leilões do dia de jogo.</p>
        </div>
    @elseif ($tab === 'items')
        <div class="p-4 bg-gray-800 border border-gray-700 rounded-lg text-gray-300">
            <h3 class="font-bold text-white text-lg mb-2">Itens Especiais</h3>
            <p class="text-xs text-gray-400">Nenhum item especial disponível na loja hoje.</p>
        </div>
    @endif
</div>
