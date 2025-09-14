<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BoatController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\DrugController;
use App\Http\Controllers\FactoryController;
use App\Http\Controllers\HookerController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\JailController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\NightclubController;
use App\Services\GameService;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('game/Home');
})->name('home');

Route::get('/info', function (GameService $game) {
    return response()->json([
        'day'  => $game::getGameDay(),
        'time' => $game::getGameTime(),
    ]);
})->name('info');

Route::prefix('/admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/round', [AdminController::class, 'createRound'])->name('admin.round');
});

Route::prefix('/bank')->group(function () {
    Route::get('/', [BankController::class, 'index'])->name('bank.index');
    Route::post('/deposit', [BankController::class, 'deposit'])->name('bank.deposit');
    Route::post('/withdraw', [BankController::class, 'withdraw'])->name('bank.withdraw');
});

Route::prefix('/hooker')->group(function () {
    Route::get('/', [HookerController::class, 'index'])->name('hooker.indexs');
    Route::post('/buy/{hooker}', [HookerController::class, 'buyHooker'])->name('hooker.buy');
    Route::post('/sell/{hooker}', [HookerController::class, 'sellHooker'])->name('hooker.sell');
    Route::post('/collect', [HookerController::class, 'collectIncome'])->name('hooker.collect');
});

Route::prefix('/drug')->group(function () {
    Route::get('/', [DrugController::class, 'index'])->name('drug.index');
    Route::post('/sell/{drug}', [DrugController::class, 'sell'])->name('drug.sell');
    Route::post('/reward', [DrugController::class, 'reward'])->name('drug.reward');
});

Route::prefix('/factory')->group(function () {
    Route::get('/', [FactoryController::class, 'index'])->name('factory.index');
    Route::get('/lab/{userFactory}', [FactoryController::class, 'showFactory'])->name('factory.show');
    Route::post('/lab/create/{userFactory}', [FactoryController::class, 'createLabProduction'])->name('factory.create');
    Route::post('/lab/cancel/{production}', [FactoryController::class, 'cancelLabProduction'])->name('factory.cancel');
    Route::post('/lab/claim/{production}', [FactoryController::class, 'claimLabProduction'])->name('factory.claim');
    Route::post('/buy/{factory}', [FactoryController::class, 'buyFactory'])->name('factory.buy');
    Route::post('/sell/{userFactory}', [FactoryController::class, 'sellFactory'])->name('factory.sell');
    Route::post('/upgrade/{userFactory}', [FactoryController::class, 'upgradeFactory'])->name('factory.upgrade');
    Route::post('/collect', [FactoryController::class, 'collectProduction'])->name('factory.collect');
});

Route::prefix('/nightclub')->group(function () {
    Route::get('/', [NightclubController::class, 'index'])->name('nightclub.index');
    Route::post('/fight/{user}', [NightclubController::class, 'fight'])->name('nightclub.fight');
});

Route::prefix('/boat')->group(function () {
    Route::get('/', [BoatController::class, 'index'])->name('boat.index');
    Route::post('/sell/{boat}', [BoatController::class, 'sell'])->name('boat.sell');
});

Route::prefix('/jail')->group(function () {
    Route::get('/', [JailController::class, 'index'])->name('jail.index');
    Route::post('/bribe', [JailController::class, 'bribe'])->name('jail.bribe');
    Route::post('/release', [JailController::class, 'release'])->name('jail.release');
});

Route::prefix('/hospital')->group(function () {
    Route::get('/', [HospitalController::class, 'index'])->name('hospital.index');
    Route::post('/release', [HospitalController::class, 'release'])->name('hospital.release');
});

Route::prefix('/market')->group(function () {
    Route::get('/', [MarketController::class, 'index'])->name('market.index');
    Route::get('/components', [MarketController::class, 'components'])->name('market.components');
    Route::get('/components/{component}', [MarketController::class, 'componentsAuction'])->name('market.components.auction');
    Route::get('/armors', [MarketController::class, 'armors'])->name('market.armors');
    Route::get('/weapons', [MarketController::class, 'weapons'])->name('market.weapons');
    Route::get('/items', [MarketController::class, 'items'])->name('market.items');
    Route::post('/buy/{equipment}', [MarketController::class, 'buy'])->name('market.buy');
});

Route::prefix('inventory')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/activate/{equipment}', [InventoryController::class, 'activate'])->name('inventory.activate');
    Route::post('/deactivate/{equipment}', [InventoryController::class, 'deactivate'])->name('inventory.deactivate');
    Route::post('/sell/{equipment}', [InventoryController::class, 'sell'])->name('inventory.sell');
});

Route::prefix('career')->group(function() {
    Route::get('/', [CareerController::class, 'index'])->name('career.index');
    Route::post('/', [CareerController::class, 'select'])->name('career.select');
    Route::get('/tasks', [CareerController::class, 'tasks'])->name('career.tasks');
    Route::get('/about', [CareerController::class, 'about'])->name('career.about');
});

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
