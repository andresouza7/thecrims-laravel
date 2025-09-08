<?php

use App\Models\Boat;
use App\Models\Component;
use App\Models\Drug;
use App\Models\Factory;
use App\Models\Hooker;
use App\Models\User;
use App\Services\DockService;
use App\Services\UserService;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    // return Inertia::render('Welcome');
    $hooker = Hooker::first();
    $user = User::first();
    $drug = $user->drugs()->first();
    // dd($drug->getAmountForUser($user));
    $component = Component::first();
    $factory = Factory::first();
    $service = new UserService($user);
    $boat = Boat::first();
    try {
        //code...
        // DockService::sellDrugOnBoat($drug, $boat, 10);
        $service->sell($drug, 2);
    } catch (\Throwable $th) {
        echo $th->getMessage();
        //throw $th;
    }
    return "home";
})->name('home');

Route::get('/debug', function() {
    $user = User::first();

    return view('index', compact('user'));
});

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
