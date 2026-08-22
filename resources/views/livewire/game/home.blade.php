<div class="space-y-4">
    <h2 class="text-2xl font-bold text-white">Bem-vindo ao The Crims</h2>
    <p class="text-gray-400">
        Gerencie seu império do crime, invista em fábricas, negocie no mercado negro, lute em boates e construa seu respeito na cidade.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4">
        <a href="{{ route('bank.index') }}" wire:navigate.hover class="p-4 bg-gray-800/80 hover:bg-gray-800 border border-gray-700 rounded-lg transition block space-y-1">
            <h3 class="font-semibold text-emerald-400 text-lg">🏦 Banco</h3>
            <p class="text-xs text-gray-400">Guarde seu dinheiro com segurança e ganhe juros.</p>
        </a>

        <a href="{{ route('factory.index') }}" wire:navigate.hover class="p-4 bg-gray-800/80 hover:bg-gray-800 border border-gray-700 rounded-lg transition block space-y-1">
            <h3 class="font-semibold text-sky-400 text-lg">🏭 Fábricas</h3>
            <p class="text-xs text-gray-400">Compre laboratórios e produza drogas em larga escala.</p>
        </a>

        <a href="{{ route('nightlife.index') }}" wire:navigate.hover class="p-4 bg-gray-800/80 hover:bg-gray-800 border border-gray-700 rounded-lg transition block space-y-1">
            <h3 class="font-semibold text-purple-400 text-lg">🕺 Vida Noturna</h3>
            <p class="text-xs text-gray-400">Restaurar stamina e desafiar outros criminosos para lutas.</p>
        </a>
    </div>
</div>
