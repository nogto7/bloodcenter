<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    
        if (!in_array(Auth::user()->role, $roles)) {
    
            if (Auth::user()->role === 'editor' || Auth::user()->role === 'publisher') {
                return redirect('/admin/news'); // ✅ зөв URL
            }
    
            return redirect('/');
        }
    
        return $next($request);
    }
}
