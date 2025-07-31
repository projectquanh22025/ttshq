<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Otp;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Bước 1: Validate dữ liệu đăng ký
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            // 'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'Email đã được sử dụng.',
            'username.unique' => 'Username đã tồn tại.',
        ]);

        // Bước 2: Tạo user với status = 0 (chưa kích hoạt)
        $user = User::create([
            'username' => $validated['username'],
            'fullname' => $validated['fullname'] ?? null, 
            'email' => $validated['email'],
            'account_type' => 1,
            'is_active' => 0,
            'password' => Hash::make($validated['password']),
            'status' => 0,
        ]);

        // Bước 3: Tạo mã OTP và lưu vào bảng otps
        $otpCode = rand(100000, 999999);
        $expiresAt = now()->addSeconds(120);

        Otp::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'code' => $otpCode,
            'status' => 0, 
            'expires_at' => $expiresAt,
        ]);

        // Bước 4: Gửi OTP qua email
        Mail::to($user->email)->send(new OtpMail($otpCode));

        // Bước 5: Điều hướng tới form nhập OTP
        return redirect()->route('otp.form', ['email' => $user->email])
                         ->with('status', 'OTP đã được gửi. Vui lòng xác thực.');
    }

    public function showOtpForm(Request $request)
    {
        $email = $request->query('email');
        return view('auth.otp_form', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        // Validate dữ liệu từ form
        $validated = $request->validate([
            'email' => 'required|email',
            'code' => 'required|string',
        ]);

        // Lấy OTP chưa xác thực, chưa hết hạn
        $otpRecord = Otp::where('email', $validated['email'])
                        ->where('code', $validated['code'])
                        ->where('status', 0)
                        ->where('expires_at', '>', now())
                        ->first();

        if (!$otpRecord) {
            return back()->withErrors(['code' => 'Mã OTP không đúng hoặc đã hết hạn.']);
        }

        // Xác thực thành công: cập nhật user và OTP
        $user = User::where('id', $otpRecord->user_id)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Tài khoản không tồn tại.']);
        }

        $user->update(['is_active' => 1]); // Kích hoạt tài khoản
        $otpRecord->update(['status' => 1]); // Đánh dấu OTP đã dùng
        $otpRecord->delete(); // Xoá OTP sau xác thực (tuỳ chọn)

        // (Tùy chọn) Đăng nhập user luôn
        // Auth::login($user);

        return redirect()->route('dashboard')->with('status', 'Xác thực thành công. Tài khoản đã được kích hoạt!');
    }
}
