<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {

        if(!Auth::check()){
            return redirect() -> router('login');

        }
        $user = Auth::user();

        if ($user->role !== $role) {
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('status', 'Bạn không có quyền truy cập!');
            }

            return redirect()->route('dashboard')->with('status', 'Bạn không có quyền truy cập!');
        }
        return $next($request);
    }
}
