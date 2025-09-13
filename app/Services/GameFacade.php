<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GameFacade
{
    public User $user;

    protected $actionService;
    protected $boatService;

    public function __construct()
    {
        $this->user = Auth::user() ?? User::first();
        $this->actionService = new ActionService($this->user);
        $this->boatService = new BoatService($this->user, $this->actionService);
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
