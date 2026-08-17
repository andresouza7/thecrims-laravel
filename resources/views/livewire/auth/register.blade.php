<div class="max-w-md mx-auto my-12 p-6 bg-gray-900 border border-gray-800 rounded-lg shadow space-y-6">
    <div class="text-center">
        <h2 class="text-2xl font-bold text-white">Criar Conta no The Crims</h2>
        <p class="text-xs text-gray-400 mt-1">Crie seu personagem e comece a jogar</p>
    </div>

    <form wire:submit="register" class="space-y-4">
        <div>
            <label class="block text-xs font-medium text-gray-300 mb-1">Nome do Jogador</label>
            <input type="text" wire:model="name" required autofocus
                   class="w-full p-2.5 bg-gray-800 border border-gray-700 rounded text-sm text-gray-100 focus:border-indigo-500 focus:outline-none">
            @error('name') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-300 mb-1">E-mail</label>
            <input type="email" wire:model="email" required
                   class="w-full p-2.5 bg-gray-800 border border-gray-700 rounded text-sm text-gray-100 focus:border-indigo-500 focus:outline-none">
            @error('email') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-300 mb-1">Senha</label>
            <input type="password" wire:model="password" required
                   class="w-full p-2.5 bg-gray-800 border border-gray-700 rounded text-sm text-gray-100 focus:border-indigo-500 focus:outline-none">
            @error('password') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-300 mb-1">Confirmar Senha</label>
            <input type="password" wire:model="password_confirmation" required
                   class="w-full p-2.5 bg-gray-800 border border-gray-700 rounded text-sm text-gray-100 focus:border-indigo-500 focus:outline-none">
        </div>

        <button type="submit" wire:loading.attr="disabled"
                class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded text-sm transition">
            Cadastrar
        </button>

        <div class="text-center text-xs text-gray-400 pt-2">
            Já tem uma conta? <a href="{{ route('login') }}" wire:navigate.hover class="text-indigo-400 hover:underline">Faça Login</a>
        </div>
    </form>
</div>
