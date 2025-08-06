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
        $email = $request->query('email') ?? $request->input('email');
        $flow = $request->query('flow') ?? $request->input('flow');

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
            'code'  => 'required|digits:6',
            'flow'  => 'required|in:login,register,forgot_password,admin_register'
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

        $user = User::where('email', $email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Tài khoản không tồn tại.']);
        }

        // Đánh dấu OTP đã dùng
        $otpRecord->delete();

        // Nếu quên mật khẩu → chuyển đến form đổi mật khẩu
        if ($flow === 'forgot_password') {
            return redirect()->route('forgot.password.resetForm', ['email' => $email])
                             ->with('status', 'Xác thực OTP thành công. Vui lòng đổi mật khẩu.');
        }

        // Với tất cả các flow còn lại → nếu tài khoản bị khóa thì kích hoạt
        if (!$user->is_active) {
            $user->update([
                'status' => 1,
                'is_active' => 1,
                'email_verified_at' => now(),
            ]);
        }

        // Đăng nhập sau khi xác thực OTP
        Auth::login($user);

        // Chuyển hướng tùy role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('status', 'Xác thực thành công, chào mừng Admin!');
        }

        return redirect()->route('dashboard')->with('status', 'Xác thực OTP thành công!');
    }

    /**
     * Gửi lại mã OTP
     */
    public function resendOtp(Request $request)
    {
        $email = $request->query('email') ?? $request->input('email');
        $flow = $request->query('flow') ?? $request->input('flow');

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

        Mail::to($email)->send(new OtpMail($otpCode));

        return back()->with('status', 'Đã gửi lại mã OTP. Vui lòng kiểm tra email.');
    }
}
