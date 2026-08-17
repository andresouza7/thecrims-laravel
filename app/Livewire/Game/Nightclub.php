<?php

namespace App\Livewire\Game;

use App\Models\User;
use App\Services\GameFacade;
use Livewire\Component;

class Nightclub extends Component
{
    public ?string $combatMessage = null;
    public ?string $combatStatus = null;

    public function fight($userId, GameFacade $game)
    {
        try {
            $user = User::findOrFail($userId);
            $result = $game->action()->fight($user);

            $this->dispatch('user-stats-updated');

            if ($result['loser'] === $user->id) {
                $this->combatStatus = 'success';
                $this->combatMessage = 'Você venceu a luta contra ' . $user->name . '!';
            } else {
                $this->combatStatus = 'error';
                $this->combatMessage = 'Você perdeu a luta para ' . $user->name . '!';
            }
        } catch (\Throwable $th) {
            $this->combatStatus = 'error';
            $this->combatMessage = $th->getMessage();
        }
    }

    public function render(GameFacade $game)
    {
        $foe = User::whereNot('id', $game->user->id)
            ->inRandomOrder()
            ->first();

        return view('livewire.game.nightclub', [
            'foe' => $foe,
        ]);
    }
}
