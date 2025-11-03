<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->isSuperAdmin()) {
                return redirect()->route('dashboard');
            } else if ($user->isCashier()) {
                return redirect()->route('pos.index');
            }
            
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
