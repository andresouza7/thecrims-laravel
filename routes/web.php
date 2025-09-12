<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\DrugController;
use App\Http\Controllers\FactoryController;
use App\Http\Controllers\HookerController;
use App\Http\Controllers\NightclubController;
use App\Models\Boat;
use App\Models\Component;
use App\Models\Drug;
use App\Models\Factory;
use App\Models\Hooker;
use App\Models\User;
use App\Services\DockService;
use App\Services\GameService;
use App\Services\UserService;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use function Pest\Laravel\json;

Route::get('/do', function () {
    // return Inertia::render('Welcome');
    $hooker = Hooker::first();
    $user = User::first();
    $drug = Drug::first();
    // dd($drug->getAmountForUser($user));
    $component = Component::first();
    $factory = Factory::first();
    $service = new UserService($user);
    $boat = Boat::first();
    try {
        //code...
        // DockService::sellDrugOnBoat($drug, $boat, 10);
        // $service->buy($drug, 20);
        dd(DockService::getNextBoatBoostInfo($user->boat_profits));
    } catch (\Throwable $th) {
        echo $th->getMessage();
        //throw $th;
    }
    return "home";
})->name('do');

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
    Route::post('/sell/{drug}', [DrugController::class, 'sellDrug'])->name('drug.sell');
});

Route::prefix('/factory')->group(function () {
    Route::get('/', [FactoryController::class, 'index'])->name('factory.index');
    Route::get('/lab/{userFactory}', [FactoryController::class, 'showFactory'])->name('factory.show');
    Route::post('/buy/{factory}', [FactoryController::class, 'buyFactory'])->name('factory.buy');
    Route::post('/sell/{userFactory}', [FactoryController::class, 'sellFactory'])->name('factory.sell');
    Route::post('/upgrade/{userFactory}', [FactoryController::class, 'upgradeFactory'])->name('factory.upgrade');
    Route::post('/collect', [FactoryController::class, 'collectProduction'])->name('factory.collect');
});

Route::prefix('/nightclube')->group(function () {
    Route::get('/', [NightclubController::class, 'index'])->name('nightclub.index');
    Route::post('/fight/{user}', [NightclubController::class, 'fight'])->name('nightclub.fight');
});

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
