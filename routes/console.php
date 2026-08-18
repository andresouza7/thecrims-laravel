<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

$minutes = \App\Services\GameService::GAME_DAY_DURATION_MINUTES;

$schedule = Schedule::command('game:process-day');
match ($minutes) {
    1 => $schedule->everyMinute(),
    2 => $schedule->everyTwoMinutes(),
    3 => $schedule->everyThreeMinutes(),
    4 => $schedule->everyFourMinutes(),
    5 => $schedule->everyFiveMinutes(),
    10 => $schedule->everyTenMinutes(),
    15 => $schedule->everyFifteenMinutes(),
    30 => $schedule->everyThirtyMinutes(),
    default => $schedule->cron("*/{$minutes} * * * *"),
};

Schedule::command('game:regenerate-stats')->everyMinute();
