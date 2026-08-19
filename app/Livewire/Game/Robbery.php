<?php

namespace App\Livewire\Game;

use App\Models\Robbery as RobberyModel;
use App\Services\GameFacade;
use Livewire\Component;

class Robbery extends Component
{
    protected $listeners = ['user-stats-updated' => '$refresh'];

    public ?int $selectedRobberyId = null;

    public function mount()
    {
        $first = RobberyModel::orderBy('required_power', 'asc')->first();
        if ($first) {
            $this->selectedRobberyId = $first->id;
        }
    }

    public function execute(GameFacade $game)
    {
        if (!$this->selectedRobberyId) {
            $this->dispatch('toast', type: 'error', message: 'Selecione um roubo para executar!');
            return;
        }

        try {
            $robbery = RobberyModel::findOrFail($this->selectedRobberyId);
            $result = $game->action()->executeRobbery($robbery);

            $this->dispatch('user-stats-updated');

            if ($result['success']) {
                // Format reward messages
                $rewards = [];
                if ($result['cash'] > 0) {
                    $rewards[] = '$' . number_format($result['cash']);
                }
                foreach ($result['drugs'] as $d) {
                    $drugName = \App\Models\Drug::find($d['drug_id'])?->name ?? 'Droga';
                    $rewards[] = "{$d['amount']}x {$drugName}";
                }
                foreach ($result['components'] as $c) {
                    $compName = \App\Models\Component::find($c['component_id'])?->name ?? 'Componente';
                    $rewards[] = "{$c['amount']}x {$compName}";
                }

                $rewardStr = implode(', ', $rewards);
                $fullMessage = $result['message'] . ($rewardStr ? " Recompensa: {$rewardStr}." : "");

                $this->dispatch('toast', type: 'success', message: $fullMessage);
            } else {
                session()->flash('error', $result['message']);
                return $this->redirect(route('jail.index'), navigate: true);
            }
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }

    public function render(GameFacade $game)
    {
        $user = $game->user;
        $user->refresh();

        $robberies = RobberyModel::orderBy('required_power', 'asc')->get();
        $selectedRobbery = $this->selectedRobberyId ? RobberyModel::find($this->selectedRobberyId) : null;
        $chance = 0;

        if ($selectedRobbery) {
            $chance = $game->action()->calculateSuccessChance($selectedRobbery);
        }

        return view('livewire.game.robbery', [
            'user' => $user,
            'robberies' => $robberies,
            'selectedRobbery' => $selectedRobbery,
            'chance' => $chance,
            'actionService' => $game->action(),
        ]);
    }
}
