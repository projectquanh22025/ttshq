<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class OtpController extends Controller
{
    public function showOtpForm()
    {
        if (!session()->has('otp_email') || !session()->has('register_data')) {
        return redirect('/register')->withErrors(['error' => 'Không tìm thấy thông tin OTP.']);
    }

        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|digits:6',
        ]);

         $email = session('otp_email');

        $otpRecord = DB::table('otps')
            ->where('email', $email)
            ->where('otp', $request->otp_code)
            ->where('expires_at', '>', now())
            ->first();

        if ($otpRecord) {

            $data = session('register_data');

            $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            
            
        ]);
        $user->email_verified_at = now();
        $user->save();


        DB::table('otps')->where('id', $otpRecord->id)->update(['user_id' => $user->id]);

        session()->forget(['register_data', 'otp_email']);
          return redirect('/login')->with('success', 'Xác thực OTP thành công. Vui lòng đăng nhập.');
        } else {
            return back()->withErrors(['otp_code' => 'Mã OTP không đúng hoặc đã hết hạn.']);
        }
    }
    public function resendOtp()
{
    $email = session('otp_email');
    $data = session('register_data');

    if (!$email || !$data) {
        return redirect('/register')->withErrors(['error' => 'Không tìm thấy thông tin đăng ký.']);
    }

    // Tạo mã OTP mới
    $otpCode = rand(100000, 999999);
    $expiresAt = now()->addSeconds(120);

    DB::table('otps')->updateOrInsert(
        ['email' => $email, 'user_id' => null],
        [
            'otp' => $otpCode,
            'expires_at' => $expiresAt,
            'created_at' => now(),
            'updated_at' => now()
        ]
    );

    // Gửi lại email OTP
   Mail::to($email)->send(new \App\Mail\OtpMail($otpCode));

    return back()->with('status', 'Đã gửi lại mã OTP, vui lòng kiểm tra email.');
}

}
