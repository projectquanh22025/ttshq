<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\OtpMail;

class ForgotPasswordController extends Controller
{
    public function showEmailForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email không tồn tại.']);
        }

        $otpCode = rand(100000, 999999);
        $expiresAt = now()->addSeconds(120);

        Otp::updateOrCreate(
            ['email' => $user->email],
            ['code' => $otpCode, 'status' => 0, 'expires_at' => $expiresAt]
        );

        Mail::to($user->email)->send(new OtpMail($otpCode));

        return redirect()->route('forgot.password.verifyOtpForm', [
            'email' => $user->email,
            'flow' => 'forgot_password'  // Truyền flow để dùng chung form OTP
        ])->with('status', 'Mã OTP đã được gửi qua email.');
    }

    public function showOtpForm(Request $request)
    {
        $email = $request->email;
        $flow = $request->flow;

        if (!$email || !$flow) {
            return redirect()->route('login')->withErrors(['error' => 'Thiếu thông tin email.']);
        }

        return view('auth.verify-otp', compact('email', 'flow'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|size:6',
            'flow' => 'required|in:forgot_password'
        ]);

        $email = $request->email;
        $flow = $request->flow;

        $otp = Otp::where('email', $email)
                  ->where('code', $request->otp_code)
                  ->where('status', 0)
                  ->where('expires_at', '>', now())
                  ->first();

        if (!$otp) {
            return back()->withErrors(['otp_code' => 'Mã OTP không hợp lệ hoặc đã hết hạn.']);
        }

        // Đánh dấu OTP đã dùng
        $otp->status = 1;
        $otp->save();

        // Sau khi xác thực đúng OTP → chuyển sang form đổi mật khẩu
        return redirect()->route('forgot.password.resetForm', ['email' => $email]);
    }

    public function showResetForm(Request $request)
    {
        $email = $request->email;
        return view('auth.reset-password', compact('email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Tài khoản không tồn tại.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('status', 'Đổi mật khẩu thành công. Vui lòng đăng nhập.');
    }
}
