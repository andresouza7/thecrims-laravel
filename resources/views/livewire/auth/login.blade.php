<div class="max-w-md mx-auto my-12 p-6 bg-gray-900 border border-gray-800 rounded-lg shadow space-y-6">
    <div class="text-center">
        <h2 class="text-2xl font-bold text-white">Entrar no The Crims</h2>
        <p class="text-xs text-gray-400 mt-1">Acesse sua conta para continuar jogando</p>
    </div>

    <form wire:submit="login" class="space-y-4">
        <div>
            <label class="block text-xs font-medium text-gray-300 mb-1">E-mail</label>
            <input type="email" wire:model="email" required autofocus
                   class="w-full p-2.5 bg-gray-800 border border-gray-700 rounded text-sm text-gray-100 focus:border-indigo-500 focus:outline-none">
            @error('email') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-300 mb-1">Senha</label>
            <input type="password" wire:model="password" required
                   class="w-full p-2.5 bg-gray-800 border border-gray-700 rounded text-sm text-gray-100 focus:border-indigo-500 focus:outline-none">
            @error('password') <span class="text-rose-400 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center justify-between text-xs">
            <label class="flex items-center gap-2 text-gray-400 cursor-pointer">
                <input type="checkbox" wire:model="remember" class="rounded bg-gray-800 border-gray-700 text-indigo-600">
                Lembrar de mim
            </label>
        </div>

        <button type="submit" wire:loading.attr="disabled"
                class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded text-sm transition">
            Entrar
        </button>

        <div class="text-center text-xs text-gray-400 pt-2">
            Não tem uma conta? <a href="{{ route('register') }}" wire:navigate.hover class="text-indigo-400 hover:underline">Cadastre-se</a>
        </div>
    </form>
</div>
