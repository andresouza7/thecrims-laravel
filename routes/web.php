<?php

use App\Models\Component;
use App\Models\Drug;
use App\Models\Factory;
use App\Models\Hooker;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    // return Inertia::render('Welcome');
    $hooker = Hooker::first();
    $drug = Drug::first();
    $user = User::first();
    $component = Component::first();
    $factory = Factory::first();
    $service = new UserService($user);
    dd($user->adjustStat('strength', 1000));
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
