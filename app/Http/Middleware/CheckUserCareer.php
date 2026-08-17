<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserCareer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('livewire*') || $request->hasHeader('X-Livewire')) {
            return $next($request);
        }

        $user = $request->user() ?? User::first();
        
        if ($user && !$user->career && !$request->is('career*')) {
            return to_route('career.index');
        }

        return $next($request);
    }
}
