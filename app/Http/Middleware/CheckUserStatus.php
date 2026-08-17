<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('livewire*') || $request->hasHeader('X-Livewire')) {
            return $next($request);
        }

        $user = Auth::user() ?? User::first();
        if (! $user) return $next($request);

        if (!$user->canAccessPath($request->path())) {
            if ($user->in_jail) {
                return to_route('jail.index');
            }
            if ($user->in_hospital) {
                return to_route('hospital.index');
            }
        }

        return $next($request);
    }
}
