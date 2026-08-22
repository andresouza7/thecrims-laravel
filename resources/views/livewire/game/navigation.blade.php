@php
    $user = auth()->user() ?? \App\Models\User::first();
@endphp

<nav class="p-2 bg-gray-900 border border-gray-800 rounded-lg flex flex-wrap gap-2 text-sm font-medium">
    <!-- Home -->
    @if ($user?->canAccessPath(''))
        <a href="{{ route('home') }}" wire:navigate.hover
            class="px-3 py-1.5 rounded transition {{ request()->routeIs('home') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Home
        </a>
    @else
        <span class="px-3 py-1.5 rounded text-gray-500 opacity-40 cursor-not-allowed select-none flex items-center gap-1 bg-gray-950/40 border border-transparent">
            Home 🔒
        </span>
    @endif

    <!-- Banco -->
    @if ($user?->canAccessPath('bank'))
        <a href="{{ route('bank.index') }}" wire:navigate.hover
            class="px-3 py-1.5 rounded transition {{ request()->routeIs('bank.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Banco
        </a>
    @else
        <span class="px-3 py-1.5 rounded text-gray-500 opacity-40 cursor-not-allowed select-none flex items-center gap-1 bg-gray-950/40 border border-transparent">
            Banco 🔒
        </span>
    @endif

    <!-- Boate -->
    @if ($user?->canAccessPath('nightlife'))
        <a href="{{ route('nightlife.index') }}" wire:navigate.hover
            class="px-3 py-1.5 rounded transition {{ request()->routeIs('nightlife.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Vida Noturna
        </a>
    @else
        <span class="px-3 py-1.5 rounded text-gray-500 opacity-40 cursor-not-allowed select-none flex items-center gap-1 bg-gray-950/40 border border-transparent">
            Vida Noturna 🔒
        </span>
    @endif

    <!-- Roubos -->
    @if ($user?->canAccessPath('robbery'))
        <a href="{{ route('robbery.index') }}" wire:navigate.hover
            class="px-3 py-1.5 rounded transition {{ request()->routeIs('robbery.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Roubos
        </a>
    @else
        <span class="px-3 py-1.5 rounded text-gray-500 opacity-40 cursor-not-allowed select-none flex items-center gap-1 bg-gray-950/40 border border-transparent">
            Roubos 🔒
        </span>
    @endif

    <!-- Prostitutas -->
    @if ($user?->canAccessPath('hooker'))
        <a href="{{ route('hooker.indexs') }}" wire:navigate.hover
            class="px-3 py-1.5 rounded transition {{ request()->routeIs('hooker.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Prostitutas
        </a>
    @else
        <span class="px-3 py-1.5 rounded text-gray-500 opacity-40 cursor-not-allowed select-none flex items-center gap-1 bg-gray-950/40 border border-transparent">
            Prostitutas 🔒
        </span>
    @endif

    <!-- Boca de Fumo -->
    @if ($user?->canAccessPath('drug'))
        <a href="{{ route('drug.index') }}" wire:navigate.hover
            class="px-3 py-1.5 rounded transition {{ request()->routeIs('drug.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Boca de Fumo
        </a>
    @else
        <span class="px-3 py-1.5 rounded text-gray-500 opacity-40 cursor-not-allowed select-none flex items-center gap-1 bg-gray-950/40 border border-transparent">
            Boca de Fumo 🔒
        </span>
    @endif

    <!-- Fábricas -->
    @if ($user?->canAccessPath('factory'))
        <a href="{{ route('factory.index') }}" wire:navigate.hover
            class="px-3 py-1.5 rounded transition {{ request()->routeIs('factory.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Fábricas
        </a>
    @else
        <span class="px-3 py-1.5 rounded text-gray-500 opacity-40 cursor-not-allowed select-none flex items-center gap-1 bg-gray-950/40 border border-transparent">
            Fábricas 🔒
        </span>
    @endif

    <!-- Docas -->
    @if ($user?->canAccessPath('boat'))
        <a href="{{ route('boat.index') }}" wire:navigate.hover
            class="px-3 py-1.5 rounded transition {{ request()->routeIs('boat.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Docas
        </a>
    @else
        <span class="px-3 py-1.5 rounded text-gray-500 opacity-40 cursor-not-allowed select-none flex items-center gap-1 bg-gray-950/40 border border-transparent">
            Docas 🔒
        </span>
    @endif

    <!-- Prisão -->
    @if ($user?->canAccessPath('jail'))
        <a href="{{ route('jail.index') }}" wire:navigate.hover
            class="px-3 py-1.5 rounded transition {{ request()->routeIs('jail.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Prisão
        </a>
    @else
        <span class="px-3 py-1.5 rounded text-gray-500 opacity-40 cursor-not-allowed select-none flex items-center gap-1 bg-gray-950/40 border border-transparent">
            Prisão 🔒
        </span>
    @endif

    <!-- Hospital -->
    @if ($user?->canAccessPath('hospital'))
        <a href="{{ route('hospital.index') }}" wire:navigate.hover
            class="px-3 py-1.5 rounded transition {{ request()->routeIs('hospital.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Hospital
        </a>
    @else
        <span class="px-3 py-1.5 rounded text-gray-500 opacity-40 cursor-not-allowed select-none flex items-center gap-1 bg-gray-950/40 border border-transparent">
            Hospital 🔒
        </span>
    @endif

    <!-- Mercado -->
    @if ($user?->canAccessPath('market'))
        <a href="{{ route('market.index') }}" wire:navigate.hover
            class="px-3 py-1.5 rounded transition {{ request()->routeIs('market.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            Mercado
        </a>
    @else
        <span class="px-3 py-1.5 rounded text-gray-500 opacity-40 cursor-not-allowed select-none flex items-center gap-1 bg-gray-950/40 border border-transparent">
            Mercado 🔒
        </span>
    @endif

    <!-- O Beco -->
    @if ($user?->canAccessPath('street'))
        <a href="{{ route('street.index') }}" wire:navigate.hover
            class="px-3 py-1.5 rounded transition {{ request()->routeIs('street.*') ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
            O Beco
        </a>
    @else
        <span class="px-3 py-1.5 rounded text-gray-500 opacity-40 cursor-not-allowed select-none flex items-center gap-1 bg-gray-950/40 border border-transparent">
            O Beco 🔒
        </span>
    @endif



    <!-- Admin -->
    @if ($user?->canAccessPath('admin'))
        <a href="{{ route('admin.index') }}" wire:navigate.hover
            class="px-3 py-1.5 rounded transition {{ request()->routeIs('admin.*') ? 'bg-rose-600 text-white font-semibold' : 'text-rose-400 hover:bg-gray-800 hover:text-rose-300' }}">
            Admin
        </a>
    @else
        <span class="px-3 py-1.5 rounded text-gray-500 opacity-40 cursor-not-allowed select-none flex items-center gap-1 bg-gray-950/40 border border-transparent">
            Admin 🔒
        </span>
    @endif
</nav>
