<?php

namespace App\Console\Commands;

use App\Models\GameState;
use App\Services\GameService;
use Illuminate\Console\Command;

class ProcessGameDay extends Command
{
    // Nome do comando que você vai chamar: php artisan game:process-day
    protected $signature = 'game:process-day';
    protected $description = 'Process the game day (increment current day)';

    public function handle()
    {
        GameService::processDay();

        $this->info('Game day processed successfully!');
    }
}
