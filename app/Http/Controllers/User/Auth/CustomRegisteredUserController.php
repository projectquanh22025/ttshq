<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomRegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // Validate form input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user); 
        
         $otp = rand(100000, 999999);
         //OTP
        Otp::create([
        'user_id' => $user->id,
        'otp' => $otp,
        'expires_at' => now()->addSeconds(120),
          ]);

         Mail::send('emails.otp', [
        'otp' => $otp,
        'title' => 'Mã xác thực OTP'
    ], function ($message) use ($user) {
        $message->to($user->email)->subject('Mã OTP xác thực');
    });
         
        return redirect()->route('otp.verify.form');
    }

    
}
