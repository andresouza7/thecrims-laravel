<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-gray-800 pb-3">
        <div>
            <h2 class="text-2xl font-bold text-white">🧪 Boca de Fumo / Drogas</h2>
            <p class="text-xs text-gray-400">Gerencie seu estoque e venda drogas para obter lucros no mercado negro</p>
        </div>
    </div>

    <div class="space-y-3">
        <h3 class="text-base font-bold text-gray-200 border-b border-gray-800 pb-2">
            Seu Estoque de Drogas
        </h3>

        @if($drugs && $drugs->count())
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden shadow-lg">
                <!-- Header (Hidden on mobile, perfectly aligned on desktop) -->
                <div class="hidden md:grid grid-cols-[2fr_1.2fr_1.2fr_1.2fr] items-center gap-4 px-4 py-2 bg-gray-950 border-b border-gray-800 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                    <div>Nome da Droga</div>
                    <div>Preço Unitário</div>
                    <div>Estoque Atual</div>
                    <div class="text-right">Ação</div>
                </div>

                <!-- Rows (Compact and aligned) -->
                <div class="divide-y divide-gray-800/60">
                    @foreach ($drugs as $drug)
                        <div class="grid grid-cols-1 md:grid-cols-[2fr_1.2fr_1.2fr_1.2fr] items-center gap-2 md:gap-4 py-1.5 px-4 hover:bg-gray-850/40 transition">
                            <!-- Coluna 1: Nome -->
                            <div class="font-bold text-sky-400 text-sm md:text-base py-0.5">
                                {{ $drug->name }}
                            </div>

                            <!-- Coluna 2: Preço Unitário -->
                            <div class="flex md:block items-center justify-between text-xs md:text-sm">
                                <span class="md:hidden text-gray-500 font-medium">Preço:</span>
                                <span class="font-mono text-emerald-400 font-bold bg-gray-950/50 border border-gray-800/40 px-2 py-0.5 rounded">
                                    ${{ number_format($drug->price) }}
                                </span>
                            </div>

                            <!-- Coluna 3: Estoque Atual -->
                            <div class="flex md:block items-center justify-between text-xs md:text-sm text-gray-300">
                                <span class="md:hidden text-gray-500 font-medium">Estoque:</span>
                                <span class="font-mono font-semibold {{ $drug->user_amount > 0 ? 'text-emerald-400' : 'text-gray-500' }}">
                                    {{ number_format($drug->user_amount) }}
                                </span>
                            </div>

                            <!-- Coluna 4: Input + Botão de Venda -->
                            <div class="flex items-center gap-2 justify-between md:justify-end py-0.5">
                                <input type="number" min="1" max="{{ $drug->user_amount }}" wire:model="amounts.{{ $drug->id }}" placeholder="Qtd"
                                       class="w-20 p-1 bg-gray-950 border border-gray-800 rounded text-xs text-gray-100 font-mono focus:outline-none focus:border-sky-500 text-center">
                                <button wire:click="sell({{ $drug->id }})" wire:loading.attr="disabled"
                                        class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-50 text-white text-xs font-bold rounded transition whitespace-nowrap shadow-md">
                                    Vender
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <p class="text-sm text-gray-500 italic">Nenhuma droga disponível no sistema.</p>
        @endif
    </div>
</div>
