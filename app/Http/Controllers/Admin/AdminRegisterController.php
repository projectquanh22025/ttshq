<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Otp;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminRegisterController extends Controller
{
    /**
     * Hiển thị form đăng ký admin
     */
    public function showRegisterForm()
    {
        return view('admin.register');
    }

    /**
     * Xử lý đăng ký admin
     */
    public function register(Request $request)
    {
        // Bước 1: Validate dữ liệu
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'Email đã được sử dụng.',
            'username.unique' => 'Username đã tồn tại.',
        ]);

        // Bước 2: Tạo admin với trạng thái chưa kích hoạt
        $admin = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'is_active' => 0,
            'status' => 0,
            'account_type' => 1, // Nếu bạn dùng account_type phân biệt
        ]);

        // Bước 3: Tạo và lưu OTP
        $otpCode = rand(100000, 999999);
        $expiresAt = now()->addSeconds(120);

        Otp::create([
            'user_id' => $admin->id,
            'email' => $admin->email,
            'code' => $otpCode,
            'status' => 0,
            'expires_at' => $expiresAt,
        ]);

        // Bước 4: Gửi OTP qua email
        Mail::to($admin->email)->send(new OtpMail($otpCode));

        // Bước 5: Điều hướng đến form nhập OTP
        return redirect()->route('otp.form', [
            'email' => $admin->email,
            'flow' => 'admin_register' // để phân biệt trong OTP xử lý
        ])->with('status', 'OTP đã được gửi. Vui lòng xác thực để kích hoạt tài khoản admin.');
    }
}
