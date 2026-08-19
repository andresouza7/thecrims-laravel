<?php

namespace App\Livewire\Game;

use App\Services\GameFacade;
use App\Services\GameService;
use Livewire\Component;
use Livewire\Attributes\On;

class UserHeader extends Component
{
    public int $lastKnownDay = 0;

    #[On('user-stats-updated')]
    public function refresh()
    {
        // Triggers re-render
    }

    public function mount()
    {
        $this->lastKnownDay = GameService::getGameDay();
    }

    public function render(GameFacade $game)
    {
        $user = $game->user;
        $user->refresh();

        $referer = request()->header('Referer');
        $isLivewire = str_starts_with(request()->path(), 'livewire') || request()->hasHeader('X-Livewire');
        $url = ($isLivewire && $referer) ? parse_url($referer, PHP_URL_PATH) : request()->path();
        $currentPath = trim(str_replace(request()->getBaseUrl(), '', $url), '/');

        $shouldRedirect = false;
        $redirectUrl = '';

        if ($user) {
            if (!$user->career_id && !str_starts_with($currentPath, 'career')) {
                $shouldRedirect = true;
                $redirectUrl = route('career.about');
            } elseif (!$user->canAccessPath($currentPath)) {
                $shouldRedirect = true;
                $redirectUrl = $user->in_jail ? route('jail.index') : route('hospital.index');
            }
        }

        $currentDay = GameService::getGameDay();
        if ($this->lastKnownDay > 0 && $currentDay !== $this->lastKnownDay) {
            $this->lastKnownDay = $currentDay;
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'info', message: "🗓️ O Dia {$currentDay} começou! Rendimentos e produções foram atualizados.");
        } else {
            $this->lastKnownDay = $currentDay;
        }

        $gameTime = GameService::getGameTime();

        return view('livewire.game.user-header', [
            'user' => $user,
            'shouldRedirect' => $shouldRedirect,
            'redirectUrl' => $redirectUrl,
            'gameDay' => $currentDay,
            'gameTime' => $gameTime,
        ]);
    }
}
