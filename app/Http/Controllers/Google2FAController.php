<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;


use PragmaRX\Google2FAQRCode\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Writer;
class Google2FAController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    // B1: Hiển thị QR code để bật Google 2FA
    public function show2FASetupForm()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Bạn chưa đăng nhập']);
        }

        // Tạo secret nếu chưa có
        if (!$user->google2fa_secret) {
            $secret = $this->google2fa->generateSecretKey();
            $user->google2fa_secret = Crypt::encrypt($secret);
            $user->save();
        } else {
            $secret = Crypt::decrypt($user->google2fa_secret);
        }

        // Tạo QR code base64
        $qrInline = $this->google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $secret
        );

        return view('auth.google2fa_setup', [
            'qrInline' => $qrInline,
            'secret' => $secret,
            'isEnabled' => $user->google2fa_enable,
        ]);
    }

    // B2: Kích hoạt 2FA
    public function enable2FA(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = Auth::user();
        $secret = Crypt::decrypt($user->google2fa_secret);

        if ($this->google2fa->verifyKey($secret, $request->otp)) {
            $user->google2fa_enable = true;
            $user->save();

            return redirect()->route('2fa.setup')->with('status', 'Kích hoạt 2FA thành công!');
        }

        return back()->withErrors(['otp' => 'Mã không hợp lệ.']);
    }

    // B3: Tắt 2FA
    public function disable2FA(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = Auth::user();
        $secret = Crypt::decrypt($user->google2fa_secret);

        if ($this->google2fa->verifyKey($secret, $request->otp)) {
            $user->google2fa_enable = false;
            $user->google2fa_secret = null;
            $user->save();

            return redirect()->route('2fa.setup')->with('status', 'Đã tắt 2FA.');
        }

        return back()->withErrors(['otp' => 'Mã không hợp lệ.']);
    }

    // B4: Hiển thị form xác thực 2FA sau đăng nhập
    public function show2FAVerifyForm()
    {
        return view('auth.google2fa_verify');
    }

    // B5: Xác thực mã 2FA sau đăng nhập
    public function verify2FA(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = Auth::user();

        if (!$user || !$user->google2fa_enable) {
            return redirect()->route('login')->withErrors(['email' => 'Không xác thực được.']);
        }

        $secret = Crypt::decrypt($user->google2fa_secret);

        if ($this->google2fa->verifyKey($secret, $request->otp)) {
            session(['2fa_verified' => true]);
            return redirect()->intended('/dashboard')->with('success', 'Xác thực 2FA thành công!');
        }

        return back()->withErrors(['otp' => 'Mã không đúng.']);
    }
}
