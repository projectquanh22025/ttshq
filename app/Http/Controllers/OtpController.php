<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OtpController extends Controller
{
    public function showOtpForm()
    {
        if (!session()->has('otp_user_id')) {
            return redirect('/register')->withErrors(['error' => 'Không tìm thấy thông tin OTP.']);
        }

        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|digits:6',
        ]);

        $userId = session('otp_user_id');

        $otpRecord = DB::table('otps')
            ->where('user_id', $userId)
            ->where('otp', $request->otp_code)
            ->where('expires_at', '>', now())
            ->first();

        if ($otpRecord) {
            $user = User::find($userId);
            Auth::login($user); // Đăng nhập sau khi xác thực OTP
            session()->forget('otp_user_id');

            return redirect()->intended('/dashboard')->with('success', 'Xác thực OTP thành công!');
        } else {
            return back()->withErrors(['otp_code' => 'Mã OTP không đúng hoặc đã hết hạn.']);
        }
    }
}
