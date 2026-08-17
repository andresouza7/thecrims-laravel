<?php

namespace App\Livewire\Game;

use App\Services\GameFacade;
use Livewire\Component;
use Livewire\Attributes\On;

class UserHeader extends Component
{
    #[On('user-stats-updated')]
    public function refresh()
    {
        // Triggers re-render
    }

    public function render(GameFacade $game)
    {
        $user = $game->user;
        $user->refresh();

        $referer = request()->header('Referer');
        $isLivewire = str_starts_with(request()->path(), 'livewire') || request()->hasHeader('X-Livewire');
        $url = ($isLivewire && $referer) ? parse_url($referer, PHP_URL_PATH) : request()->path();
        $currentPath = trim(str_replace(request()->getBaseUrl(), '', $url), '/');

        $shouldRedirect = $user && !$user->canAccessPath($currentPath);
        $redirectUrl = $shouldRedirect ? ($user->in_jail ? route('jail.index') : route('hospital.index')) : '';

        return view('livewire.game.user-header', [
            'user' => $user,
            'shouldRedirect' => $shouldRedirect,
            'redirectUrl' => $redirectUrl,
        ]);
    }
}
