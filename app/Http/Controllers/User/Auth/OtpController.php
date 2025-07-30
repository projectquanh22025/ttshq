<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OtpController extends Controller
{
      public function showOtpForm()
    {
        // Trả về trang nhập OTP
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = Auth::user();

        if ($user->otp === $request->otp && now()->lessThan($user->otp_expires_at)) {
            // OTP đúng và chưa hết hạn
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->email_verified_at = now(); // nếu cần đánh dấu xác thực email
            $user->save();

            return redirect()->route('dashboard')->with('success', 'Xác thực OTP thành công!');
        }

        return back()->withErrors(['otp' => 'Mã OTP không đúng hoặc đã hết hạn.']);
    }

    public function resend()
    {
        $user = Auth::user();
        $otp = rand(100000, 999999);

        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(2);
        $user->save();

        return back()->with('success', 'Đã gửi lại mã OTP!');
    }
}
