<?php

namespace App\Console\Commands;

use App\Services\GameService;
use Illuminate\Console\Command;

class RegenerateStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:regenerate-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        GameService::regenerateStats();

        $this->info('User stats regenerated successfully!');
    }
}
