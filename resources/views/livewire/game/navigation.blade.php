<nav class="p-2 bg-gray-900 border border-gray-800 rounded-lg flex flex-wrap gap-2 text-sm font-medium">
    <a href="{{ route('home') }}" wire:navigate.hover
       class="px-3 py-1.5 rounded transition {{ request()->routeIs('home') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
        Home
    </a>

    <a href="{{ route('bank.index') }}" wire:navigate.hover
       class="px-3 py-1.5 rounded transition {{ request()->routeIs('bank.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
        Banco
    </a>

    <a href="{{ route('nightclub.index') }}" wire:navigate.hover
       class="px-3 py-1.5 rounded transition {{ request()->routeIs('nightclub.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
        Boate
    </a>

    <a href="{{ route('hooker.indexs') }}" wire:navigate.hover
       class="px-3 py-1.5 rounded transition {{ request()->routeIs('hooker.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
        Prostitutas
    </a>

    <a href="{{ route('drug.index') }}" wire:navigate.hover
       class="px-3 py-1.5 rounded transition {{ request()->routeIs('drug.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
        Boca de Fumo
    </a>

    <a href="{{ route('factory.index') }}" wire:navigate.hover
       class="px-3 py-1.5 rounded transition {{ request()->routeIs('factory.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
        Fábricas
    </a>

    <a href="{{ route('boat.index') }}" wire:navigate.hover
       class="px-3 py-1.5 rounded transition {{ request()->routeIs('boat.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
        Docas
    </a>

    <a href="{{ route('jail.index') }}" wire:navigate.hover
       class="px-3 py-1.5 rounded transition {{ request()->routeIs('jail.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
        Prisão
    </a>

    <a href="{{ route('hospital.index') }}" wire:navigate.hover
       class="px-3 py-1.5 rounded transition {{ request()->routeIs('hospital.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
        Hospital
    </a>

    <a href="{{ route('market.index') }}" wire:navigate.hover
       class="px-3 py-1.5 rounded transition {{ request()->routeIs('market.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
        Mercado
    </a>

    <a href="{{ route('inventory.index') }}" wire:navigate.hover
       class="px-3 py-1.5 rounded transition {{ request()->routeIs('inventory.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
        Inventário
    </a>

    <a href="{{ route('career.about') }}" wire:navigate.hover
       class="px-3 py-1.5 rounded transition {{ request()->routeIs('career.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
        Carreira
    </a>

    <a href="{{ route('admin.index') }}" wire:navigate.hover
       class="px-3 py-1.5 rounded transition {{ request()->routeIs('admin.*') ? 'bg-rose-600 text-white font-semibold' : 'text-rose-400 hover:bg-gray-800 hover:text-rose-300' }}">
        Admin
    </a>
</nav>
