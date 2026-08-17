<?php

namespace App\Livewire\Game;

use App\Models\Drug as DrugModel;
use App\Services\GameFacade;
use Livewire\Component;

class Drug extends Component
{
    protected $listeners = ['user-stats-updated' => '$refresh'];

    public array $amounts = [];

    public function sell($drugId, GameFacade $game)
    {
        $amount = (int) ($this->amounts[$drugId] ?? 0);

        if ($amount <= 0) {
            $this->dispatch('toast', type: 'error', message: 'Informe uma quantidade válida para vender!');
            return;
        }

        try {
            $drug = DrugModel::findOrFail($drugId);
            $totalProfit = $game->action()->sell($drug, $amount);
            $formattedProfit = number_format($totalProfit);
            $this->amounts[$drugId] = '';
            $this->dispatch('user-stats-updated');
            $this->dispatch('toast', type: 'success', message: "Venda de {$amount}x {$drug->name} por \${$formattedProfit} efetuada com sucesso!");
        } catch (\Throwable $th) {
            $this->dispatch('toast', type: 'error', message: $th->getMessage());
        }
    }


    public function render(GameFacade $game)
    {
        $user = $game->user;
        $user->refresh();

        $userDrugsMap = $user->drugs->keyBy('id');
        $allDrugs = DrugModel::orderBy('name')->get()->map(function ($drug) use ($userDrugsMap) {
            $userDrug = $userDrugsMap->get($drug->id);
            $drug->user_amount = $userDrug ? (int) $userDrug->pivot->amount : 0;
            return $drug;
        });

        return view('livewire.game.drug', [
            'drugs' => $allDrugs,
        ]);
    }
}
