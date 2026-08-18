<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GameFacade
{
    public User $user;

    protected ActionService $actionService;
    protected BoatService $boatService;

    public function __construct()
    {
        $user = Auth::user() ?? User::with(['armor', 'weapon'])->first() ?? new User();
        $this->user = $user;
        $this->actionService = new ActionService($user);
        $this->boatService = new BoatService($user, $this->actionService);
    }

    public function action(): ActionService
    {
        return $this->actionService;
    }

    public function boat(): BoatService
    {
        return $this->boatService;
    }
}
