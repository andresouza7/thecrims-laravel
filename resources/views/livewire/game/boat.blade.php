<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-gray-800 pb-3">
        <h2 class="text-2xl font-bold text-white">🚢 Docas & Navios Cargueiros</h2>
        <div class="text-xs text-gray-400">Venda drogas em atacado para navios cargueiros</div>
    </div>

    @if (isset($data['boats']) && count($data['boats']))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($data['boats'] as $boat)
                <div class="p-5 bg-gray-800 border border-gray-700 rounded-lg space-y-4 shadow">
                    <div>
                        <h3 class="font-bold text-sky-400 text-xl">{{ $boat->name ?? 'Navio Cargueiro' }}</h3>
                        <p class="text-xs text-gray-400">Procura por: <strong class="text-white">{{ $boat->drug?->name ?? 'Droga' }}</strong></p>
                        <p class="text-xs text-emerald-400 font-mono font-semibold">Preço pago por unid: ${{ number_format($boat->price ?? 0) }}</p>
                    </div>

                    <div class="flex items-center gap-2 pt-2 border-t border-gray-700">
                        <input type="number" min="1" wire:model="amounts.{{ $boat->id }}" placeholder="Quantidade"
                               class="w-full p-2.5 bg-gray-900 border border-gray-700 rounded text-sm text-gray-100">
                        <button wire:click="sell({{ $boat->id }})" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded transition whitespace-nowrap">
                            Vender para o Navio
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-6 bg-gray-800/60 border border-gray-700 rounded-lg text-center space-y-2">
            <p class="text-lg font-semibold text-gray-300">Nenhum navio no porto no momento.</p>
            <p class="text-xs text-gray-500">Aguarde o próximo dia de jogo para novas embarcações aportarem nas docas.</p>
        </div>
    @endif
</div>
