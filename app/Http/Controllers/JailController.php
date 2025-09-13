<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class JailController extends Controller
{
    public function index()
    {
        return Inertia::render('game/Jail');
    }
}
