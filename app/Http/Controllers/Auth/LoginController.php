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
    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $user = User::where('email', $credentials['email'])->first();

        // Kiểm tra tồn tại
        if (!$user) {
            return back()->withErrors(['email' => 'Email không đúng hoặc chưa đăng ký.']);
        }

        // Kiểm tra lockout
        $lockoutSeconds = $this->checkLockout($user->id);
        if ($lockoutSeconds > 0) {
            return back()->withErrors([
                'email' => "Tài khoản bị tạm khóa. Vui lòng thử lại sau {$lockoutSeconds} giây."
            ]);
        }

        // Kiểm tra mật khẩu
        if (!Hash::check($credentials['password'], $user->password)) {
            $result = $this->recordFailedAttempt($user);
            return $this->handleFailedLoginResponse($result);
        }

        // Reset số lần nhập sai nếu đăng nhập đúng
        $this->resetLoginAttempts($user->id);

        // Kiểm tra tài khoản kích hoạt (status)
        // if ($user->status == 0 || $user->is_active == 0) {
        //     $this->sendOtpForInactiveUser($user);
        //     return redirect()->route('otp.form', [
        //         'email' => $user->email,
        //         'flow' => 'login'
        //     ])->with('status', 'Tài khoản đã khóa');
        // }
        if ($user->status == 0 || $user->is_active == 0) {
    return redirect()->back()->withErrors([
        'email' => 'Tài khoản đã bị khóa. Vui lòng liên hệ quản trị viên.'
    ]);
}

        // Đăng nhập thành công
        Auth::login($user);

        // Kiểm tra 2FA
        if ($user->google2fa_enable) {
            session()->put('2fa_verified', false);
            return redirect()->route('2fa.verify.form');
        }

        // Phân quyền điều hướng
        return $this->redirectAfterLogin($user);
    }

    /**
     * Gửi OTP khi tài khoản chưa kích hoạt
     */
    protected function sendOtpForInactiveUser(User $user)
    {
        $otpCode = rand(100000, 999999);
        $expiresAt = now()->addSeconds(120);

        Otp::updateOrCreate(
            ['user_id' => $user->id, 'email' => $user->email],
            ['code' => $otpCode, 'status' => 0, 'expires_at' => $expiresAt]
        );

        Mail::to($user->email)->send(new OtpMail($otpCode));
    }

    /**
     * Kiểm tra tạm khóa đăng nhập
     */
    protected function checkLockout($userId)
    {
        $lockoutKey = 'lockout_' . $userId;
        $lockoutTime = Cache::get($lockoutKey, 0);

        return ($lockoutTime > now()->timestamp) ? $lockoutTime - now()->timestamp : 0;
    }

    /**
     * Ghi nhận số lần đăng nhập sai, xử lý khóa tạm và khóa tài khoản nếu vượt giới hạn
     */
    protected function recordFailedAttempt(User $user)
    {
        $userId = $user->id;
        $attemptsKey = 'login_attempts_' . $userId;
        $lockoutKey = 'lockout_' . $userId;

        $attempts = Cache::increment($attemptsKey);
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
                'message' => "Bạn còn {$remainingTries} lần nhập. Tạm khóa {$delay} giây."
            ];
        }

        // Khóa tài khoản nếu quá 8 lần sai
        $user->is_active = 0;
        $user->status = 0;
        $user->save();
        Cache::forget($attemptsKey);

        return [
            'status' => 'blocked',
            'message' => 'Bạn đã nhập sai quá nhiều lần. Tài khoản bị khóa, cần xác thực lại.'
        ];
    }

    /**
     * Reset số lần nhập sai và khóa tạm khi đăng nhập đúng
     */
    protected function resetLoginAttempts($userId)
    {
        Cache::forget('login_attempts_' . $userId);
        Cache::forget('lockout_' . $userId);
    }

    /**
     * Xử lý phản hồi khi đăng nhập sai
     */
    protected function handleFailedLoginResponse($result)
    {
        if ($result['status'] === 'warning') {
            return back()->withErrors(['password' => $result['message']]);
        }

        if ($result['status'] === 'lockout' || $result['status'] === 'blocked') {
            return back()->withErrors(['email' => $result['message']]);
        }

        return back()->withErrors(['email' => 'Lỗi không xác định.']);
    }

    /**
     * Điều hướng sau khi đăng nhập dựa theo quyền
     */
    protected function redirectAfterLogin(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('status', 'Chào mừng Admin!');
        }

        return redirect()->route('dashboard')->with('status', 'Đăng nhập thành công!');
    }
}
