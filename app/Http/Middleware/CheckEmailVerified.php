<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckEmailVerified
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->is_active == 0) { // hoặc is_active nếu bạn dùng cột đó
            return redirect()->route('otp.form', ['email' => $user->email])
                             ->with('warning', 'Tài khoản của bạn chưa xác thực email. Vui lòng xác thực để tiếp tục.');
        }

        return $next($request);
    }
}
