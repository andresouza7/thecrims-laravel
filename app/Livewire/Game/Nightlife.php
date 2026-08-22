<?php

namespace App\Livewire\Game;

use App\Models\User;
use App\Services\GameFacade;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Nightlife extends Component
{
    protected $listeners = ['user-stats-updated' => '$refresh'];

    public string $activeTab = 'selection'; // 'selection', 'boate', 'mansao'

    public function selectTab(string $tab)
    {
        if (in_array($tab, ['selection', 'boate', 'mansao'])) {
            $this->activeTab = $tab;
        }
    }

    public function buyStamina(GameFacade $game)
    {
        try {
            $user = $game->user;
            $user->refresh();

            if ($user->tickets < 1) {
                throw new \RuntimeException("Você não possui ingressos (tickets) suficientes!");
            }

            if ($user->stamina >= 100) {
                throw new \RuntimeException("Sua stamina já está cheia!");
            }

            $cost = $this->calculateStaminaCost($user);
            $user->validateFunds($cost);

            DB::transaction(function () use ($user, $cost) {
                $user->adjustCash(-$cost);
                $user->tickets -= 1;
                $user->stamina = 100;
                $user->addiction = min(100, $user->addiction + 15);
                $user->save();
            });

            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: 'Você consumiu drogas na boate! Sua stamina foi restaurada a 100%, mas seu vício aumentou.');
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function buyHooker(GameFacade $game)
    {
        try {
            $user = $game->user;
            $user->refresh();

            if ($user->tickets < 1) {
                throw new \RuntimeException("Você não possui ingressos (tickets) suficientes!");
            }

            if ($user->stamina >= 100) {
                throw new \RuntimeException("Sua stamina já está cheia!");
            }

            $cost = $this->calculateHookerCost($user);
            $user->validateFunds($cost);

            $diseaseContracted = rand(1, 100) <= 10; // 10% chance of contracting a disease

            DB::transaction(function () use ($user, $cost, $diseaseContracted) {
                $user->adjustCash(-$cost);
                $user->tickets -= 1;
                $user->stamina = 100;

                if ($diseaseContracted) {
                    $user->hospital_end_time = Carbon::now()->addMinutes(2);
                    $user->health = 0; // Send to hospital in critical condition
                }

                $user->save();
            });

            $this->dispatch('user-stats-updated');

            if ($diseaseContracted) {
                $this->dispatch('toast', type: 'error', message: '⚠️ Má sorte! Você contraiu uma doença na mansão e foi internado no hospital de campanha por 2 minutos!');
                return $this->redirect(route('hospital.index'), navigate: true);
            } else {
                $this->dispatch('toast', type: 'success', message: 'Você contratou uma acompanhante! Sua stamina foi restaurada a 100% com muito prazer.');
            }
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

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
                return $this->redirect(route('hospital.index'), navigate: true);
            }
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function calculateStaminaCost(User $user): int
    {
        $missingStamina = 100 - $user->stamina;
        return max(50, (int) (($user->respect * 3) * ($missingStamina / 100)));
    }

    public function calculateHookerCost(User $user): int
    {
        return max(100, (int) ($user->respect * 5));
    }

    public function render(GameFacade $game)
    {
        $user = $game->user;
        $user->refresh();

        $foe = User::whereNot('id', $user->id)
            ->inRandomOrder()
            ->first();

        $staminaCost = $this->calculateStaminaCost($user);
        $hookerCost = $this->calculateHookerCost($user);

        return view('livewire.game.nightlife', [
            'user' => $user,
            'foe' => $foe,
            'staminaCost' => $staminaCost,
            'hookerCost' => $hookerCost,
        ])->layout('layouts.app');
    }
}
