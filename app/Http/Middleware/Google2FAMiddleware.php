<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


use Illuminate\Support\Facades\Auth;
class Google2FAMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
        $user = Auth::user();

        // Nếu đã đăng nhập và bật 2FA nhưng chưa xác thực
        if ($user && $user->google2fa_enable && !session('2fa_verified')) {
            return redirect()->route('2fa.verify.form');
        }

    }
}
