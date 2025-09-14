<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    protected array $allowedPaths = ['/', 'admin*', 'bank*'];

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user() ?? User::first();
        if (! $user) return $next($request);

        foreach ($this->allowedPaths as $allowed) {
            if ($request->is($allowed)) {
                return $next($request);
            }
        }

        if ($user->in_jail && !$request->is('jail*')) return to_route('jail.index');
        if ($user->in_hospital && !$request->is('hospital*')) return to_route('hospital.index');

        return $next($request);
    }
}
