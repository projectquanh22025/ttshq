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

        if (!$email) {
            return redirect('/register')->withErrors(['error' => 'Không tìm thấy email xác thực.']);
        }

        return view('auth.verify-otp', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6',
        ]);

        $email = $request->input('email');

        $otpRecord = Otp::where('email', $email)
                        ->where('code', $request->code)
                        ->where('status', 0)
                        ->where('expires_at', '>', now())
                        ->first();

        if (!$otpRecord) {
            return back()->withErrors(['code' => 'Mã OTP không đúng hoặc đã hết hạn.']);
        }

        // Tìm user tương ứng
        $user = User::find($otpRecord->user_id);

        if (!$user) {
            return back()->withErrors(['email' => 'Tài khoản không tồn tại.']);
        }

        // Kích hoạt tài khoản
        $user->update([
            'status' => 1,
            'is_active' =>1 ,
            'email_verified_at' => now(),
        ]);

        // Đánh dấu OTP đã sử dụng và xoá
        $otpRecord->update(['status' => 1]);
        $otpRecord->delete();

        // Đăng nhập user
        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Xác thực OTP thành công!');
    }

    public function resendOtp(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect('/register')->withErrors(['error' => 'Không tìm thấy email để gửi lại OTP.']);
        }

        $otpRecord = Otp::where('email', $email)
                        ->where('status', 0)
                        ->first();

        if (!$otpRecord) {
            return redirect('/register')->withErrors(['error' => 'Không tìm thấy yêu cầu xác thực phù hợp.']);
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
