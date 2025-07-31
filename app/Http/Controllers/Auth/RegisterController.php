<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register'); 
    }

    public function register(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.unique' => 'Tên người dùng đã tồn tại.',
            'email.unique' => 'Email đã được sử dụng.',
        ])->validate();

    session([
        'otp_email' => $request->email,
        'register_data' => [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]
    ]);

      
        // Gửi OTP
        $otpCode = rand(100000, 999999);
        $expiresAt = now()->addSeconds(120);

        DB::table('otps')->updateOrInsert(
            ['email' => $request->email, 'user_id' => null],
            [
                'otp' => $otpCode,
                'expires_at' => $expiresAt,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        Mail::to($request->email)->send(new OtpMail($otpCode)); 

        
         session(['otp_email' => $request->email]);

        return redirect()->route('otp.form')->with('status', 'OTP đã gửi, vui lòng xác thực.');
    }
}
