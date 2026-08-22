<?php

use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Game\Bank as GameBank;
use App\Livewire\Game\Boat as GameBoat;
use App\Livewire\Game\Career\About as GameCareerAbout;
use App\Livewire\Game\Drug as GameDrug;
use App\Livewire\Game\Factory as GameFactory;
use App\Livewire\Game\Home as GameHome;
use App\Livewire\Game\Hooker as GameHooker;
use App\Livewire\Game\Hospital as GameHospital;
use App\Livewire\Game\Inventory as GameInventory;
use App\Livewire\Game\Jail as GameJail;
use App\Livewire\Game\Lab as GameLab;
use App\Livewire\Game\Market\Index as GameMarketIndex;
use App\Livewire\Game\Nightclub as GameNightclub;
use App\Livewire\Game\Robbery as GameRobbery;
use App\Livewire\Game\Street as GameStreet;
use App\Services\GameService;
use Illuminate\Support\Facades\Route;

Route::get('/', GameHome::class)->name('home');

Route::get('/info', function (GameService $game) {
    return response()->json([
        'day'  => $game::getGameDay(),
        'time' => $game::getGameTime(),
    ]);
})->name('info');

Route::prefix('/admin')->group(function () {
    Route::get('/', AdminDashboard::class)->name('admin.index');
});

Route::prefix('/bank')->group(function () {
    Route::get('/', GameBank::class)->name('bank.index');
});

Route::prefix('/hooker')->group(function () {
    Route::get('/', GameHooker::class)->name('hooker.indexs');
});

Route::prefix('/drug')->group(function () {
    Route::get('/', GameDrug::class)->name('drug.index');
});

Route::prefix('/factory')->group(function () {
    Route::get('/', GameFactory::class)->name('factory.index');
    Route::get('/lab/{userFactory}', GameLab::class)->name('factory.show');
});

Route::prefix('/nightclub')->group(function () {
    Route::get('/', GameNightclub::class)->name('nightclub.index');
});

Route::prefix('/boat')->group(function () {
    Route::get('/', GameBoat::class)->name('boat.index');
});

Route::prefix('/robbery')->group(function () {
    Route::get('/', GameRobbery::class)->name('robbery.index');
});

Route::prefix('/jail')->group(function () {
    Route::get('/', GameJail::class)->name('jail.index');
});

Route::prefix('/hospital')->group(function () {
    Route::get('/', GameHospital::class)->name('hospital.index');
});

Route::prefix('/market')->group(function () {
    Route::get('/', GameMarketIndex::class)->name('market.index');
});

Route::prefix('inventory')->group(function () {
    Route::get('/', GameInventory::class)->name('inventory.index');
});

Route::prefix('career')->group(function() {
    Route::get('/', GameCareerAbout::class)->name('career.index');
    Route::get('/about', GameCareerAbout::class)->name('career.about');
});

Route::prefix('street')->group(function () {
    Route::get('/', GameStreet::class)->name('street.index');
});

Route::get('dashboard', GameHome::class)->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
