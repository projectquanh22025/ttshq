<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Otp;
use App\Mail\OtpMail;
class LoginController extends Controller
{
    public function showLoginForm() {
    return view('auth.login');
}
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        // Bước 1: Validate dữ liệu
        //  $credentials = $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required|string',
        // ]);

        // Bước 2: Tìm user theo email
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email không đúng hoặc chưa đăng ký.']);
        }

         $lockoutSeconds = $this->checkLockout($user->id);
         if ($lockoutSeconds > 0) {
        return back()->withErrors(['email' => "Tài khoản bị tạm khóa. Vui lòng thử lại sau {$lockoutSeconds} giây."]);
        }


        // if (!Hash::check($credentials['password'], $user->password)) {
        //     return back()->withErrors(['password' => 'Mật khẩu không đúng.']);
        // }

        //   $this->resetLoginAttempts($user->id);

        if (!Hash::check($credentials['password'], $user->password)) {
    $result = $this->recordFailedAttempt($user);
    
   
    
    if ($result['status'] === 'warning') {
        return back()->withErrors(['password' => $result['message']]);
    }

    if ($result['status'] === 'lockout') {
        return back()->withErrors(['email' => $result['message']]);
    }

    if ($result['status'] === 'blocked') {
        return back()->withErrors(['email' => $result['message']]);
    }
}


        // Bước 4: Kiểm tra tài khoản có bị khóa không
        if ($user->status == 0 || $user->is_active == 0) {
            
            $otpCode = rand(100000, 999999);
            $expiresAt = now()->addSeconds(120);

            Otp::updateOrCreate(
                ['user_id' => $user->id, 'email' => $user->email],
                ['code' => $otpCode, 'status' => 0, 'expires_at' => $expiresAt]
            );

            Mail::to($user->email)->send(new OtpMail($otpCode));

            return redirect()->route('otp.form', ['email' => $user->email])
                             ->with('status', 'Tài khoản chưa kích hoạt. OTP đã được gửi, vui lòng xác thực.');
        }

        // Bước 5: Đăng nhập thành công
        Auth::login($user);

        return redirect()->route('dashboard')->with('status', 'Đăng nhập thành công!');
    }
 public function checkLockout($userId)
    {
        $lockoutKey = 'lockout_' . $userId;
        $lockoutTime = Cache::get($lockoutKey, 0);

        if ($lockoutTime > now()->timestamp) {
            return $lockoutTime - now()->timestamp;
        }

        return 0;  // không bị khóa
    }

    // Ghi nhận 1 lần sai, xử lý khóa tạm và khóa tài khoản nếu cần
    public function recordFailedAttempt(User $user)
    {
        $userId = $user->id;
        $attemptsKey = 'login_attempts_' . $userId;
        $lockoutKey = 'lockout_' . $userId;

        $attempts = Cache::get($attemptsKey, 0) + 1;
        Cache::put($attemptsKey, $attempts, now()->addMinutes(15));

        if ($attempts <= 5) {
            return [
                'status' => 'warning',
                'message' => 'Mật khẩu không đúng.'
            ];
        }

        if ($attempts <= 8) {
            $delay = ($attempts - 5) * 30; // 30s, 60s, 90s
            $lockoutUntil = now()->addSeconds($delay);
            Cache::put($lockoutKey, $lockoutUntil->timestamp, $lockoutUntil);

            $remainingTries = 8 - $attempts + 1;

            return [
                'status' => 'lockout',
                'message' => "Bạn còn {$remainingTries} lần nhập. Hệ thống tạm khóa {$delay} giây."
            ];
        }

        // Sai quá 8 lần → khóa tài khoản
        $user->is_active = 0;
        $user->status = 0;
        $user->save();

        Cache::forget($attemptsKey);

        return [
            'status' => 'blocked',
            'message' => 'Bạn đã nhập sai quá nhiều lần. Tài khoản bị khóa. Vui lòng xác thực lại tài khoản.'
        ];
    }

    // Reset đếm sai và khóa tạm khi đăng nhập đúng
    public function resetLoginAttempts($userId)
    {
        Cache::forget('login_attempts_' . $userId);
        Cache::forget('lockout_' . $userId);
    }
}

