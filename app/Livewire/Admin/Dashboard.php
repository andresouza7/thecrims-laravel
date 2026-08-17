<?php

namespace App\Livewire\Admin;

use App\Services\GameService;
use Livewire\Component;

class Dashboard extends Component
{
    public function createRound()
    {
        try {
            GameService::createRound();
            $this->dispatch('user-stats-updated');
            session()->flash('message', 'Novo round iniciado com sucesso!');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
