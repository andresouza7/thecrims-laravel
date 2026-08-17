<?php

namespace App\Livewire\Game;

use App\Models\User;
use App\Services\GameFacade;
use Livewire\Component;

class Nightclub extends Component
{
    protected $listeners = ['user-stats-updated' => '$refresh'];

    public function fight($userId, GameFacade $game)
    {
        try {
            $user = User::findOrFail($userId);
            $result = $game->action()->fight($user);

            $this->dispatch('user-stats-updated');

            if ($result['loser'] === $user->id) {
                $reward = number_format($result['rewardCash']);
                $this->dispatch('toast', type: 'success', message: "Você venceu a luta contra {$user->name}! Roubou \${$reward} e ganhou atributos.");
            } else {
                $this->dispatch('toast', type: 'error', message: "Você perdeu a luta para {$user->name} e foi enviado ao hospital!");
                // Redirect user to hospital index since they are hospitalized now
                return $this->redirect(route('hospital.index'), navigate: true);
            }
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
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
