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
    /**
     * Hiển thị form nhập OTP
     */
    public function showOtpForm(Request $request)
    {
        $email = $request->query('email');
        $flow = $request->query('flow');

        if (!$email || !$flow) {
            return redirect('/login')->withErrors(['error' => 'Thiếu thông tin email hoặc flow.']);
        }

        return view('auth.verify-otp', compact('email', 'flow'));
    }

    /**
     * Xác thực mã OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6',
            'flow' => 'required|in:login,register,forgot_password,admin_register'
        ]);

        $email = $request->input('email');
        $code = $request->input('code');
        $flow = $request->input('flow');

        // Tìm OTP hợp lệ
        $otpRecord = Otp::where('email', $email)
                        ->where('code', $code)
                        ->where('status', 0)
                        ->where('expires_at', '>', now())
                        ->first();

        if (!$otpRecord) {
            return back()->withErrors(['code' => 'Mã OTP không đúng hoặc đã hết hạn.']);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Tài khoản không tồn tại.']);
        }

        // Xóa OTP sau khi dùng
        $otpRecord->delete();

        // Kích hoạt tài khoản nếu chưa active
        if (!$user->is_active) {
            $user->update([
                'is_active' => 1,
                'email_verified_at' => now(),
            ]);
        }

        // Cập nhật status nếu đăng ký
        if (in_array($flow, ['register', 'admin_register'])) {
            $user->update(['status' => 1]);
        }

        // Đăng nhập user/admin sau khi OTP
        Auth::login($user);

        // Chuyển hướng theo vai trò
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('status', 'Xác thực thành công, chào mừng Admin!');
        }

        return redirect()->route('dashboard')->with('status', 'Xác thực OTP thành công!');
    }

    /**
     * Gửi lại OTP
     */
    public function resendOtp(Request $request)
    {
        $email = $request->query('email');
        $flow = $request->query('flow');

        if (!$email || !$flow) {
            return redirect('/login')->withErrors(['error' => 'Thiếu thông tin email hoặc flow.']);
        }

        $otpRecord = Otp::where('email', $email)->where('status', 0)->first();

        if (!$otpRecord) {
            return redirect('/login')->withErrors(['error' => 'Không tìm thấy OTP hợp lệ.']);
        }

        // Giới hạn gửi lại trong 30 giây
        if ($otpRecord->updated_at->gt(now()->subSeconds(30))) {
            return back()->withErrors(['error' => 'Vui lòng chờ 30 giây trước khi gửi lại OTP.']);
        }

        // Tạo OTP mới
        $otpCode = rand(100000, 999999);
        $expiresAt = now()->addSeconds(120);

        $otpRecord->update([
            'code' => $otpCode,
            'expires_at' => $expiresAt,
        ]);

        Mail::to($email)->send(new OtpMail($otpCode));

        return back()->with('status', 'OTP mới đã được gửi.');
    }
}
