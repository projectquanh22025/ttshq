<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Otp;
use App\Mail\OtpMail;

class OtpController extends Controller
{
    public function showOtpForm(Request $request)
    {
        $email = $request->query('email');
        $flow = $request->query('flow');

        if (!$email || !$flow) {
            return redirect('/login')->withErrors(['error' => 'Thiếu thông tin email hoặc flow.']);
        }

        return view('auth.verify-otp', compact('email', 'flow'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6',
            'flow' => 'required|in:login,register,forgot_password'
        ]);

        $email = $request->input('email');
        $flow = $request->input('flow');

        $otpRecord = Otp::where('email', $email)
                        ->where('code', $request->code)
                        ->where('status', 0)
                        ->where('expires_at', '>', now())
                        ->first();

        if (!$otpRecord) {
            return back()->withErrors(['code' => 'Mã OTP không đúng hoặc đã hết hạn.']);
        }

        // Tìm user theo email
        $user = User::where('email', $email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Tài khoản không tồn tại.']);
        }

        // Đánh dấu OTP đã sử dụng
        $otpRecord->update(['status' => 1]);
        $otpRecord->delete();

        // Xử lý theo flow
        if ($flow === 'forgot_password') {
            return redirect()->route('forgot.password.resetForm', ['email' => $email]);
        }

        // Kích hoạt tài khoản nếu là đăng ký
        if ($flow === 'register') {
            $user->update([
                'status' => 1,
                'is_active' => 1,
                'email_verified_at' => now(),
            ]);
        }

        // Đăng nhập
        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Xác thực OTP thành công!');
    }

    public function resendOtp(Request $request)
    {
        $email = $request->query('email');
        $flow = $request->query('flow');

        if (!$email || !$flow) {
            return redirect('/login')->withErrors(['error' => 'Thiếu thông tin email hoặc flow.']);
        }

        $otpRecord = Otp::where('email', $email)
                        ->where('status', 0)
                        ->first();

        if (!$otpRecord) {
            return redirect('/login')->withErrors(['error' => 'Không tìm thấy yêu cầu xác thực phù hợp.']);
        }

        // Tạo mã OTP mới
        $otpCode = rand(100000, 999999);
        $expiresAt = now()->addSeconds(120);

        $otpRecord->update([
            'code' => $otpCode,
            'expires_at' => $expiresAt,
        ]);

        // Gửi lại OTP
        Mail::to($email)->send(new OtpMail($otpCode));

        return back()->with('status', 'Đã gửi lại mã OTP, vui lòng kiểm tra email.');
    }
}
