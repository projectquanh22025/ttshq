<?php
namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        // Gửi OTP và lưu user_id vào session
        $this->sendOtp($user);
        session(['otp_user_id' => $user->id]);

        return $user; // Không login ở đây nếu bạn xác thực OTP trước
    }

    protected function sendOtp(User $user)
    {
        $otpCode = rand(100000, 999999);
        $expiresAt = now()->addSeconds(120);

        DB::table('otps')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'otp' => $otpCode,
                'expires_at' => $expiresAt,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // Gửi email OTP
        Mail::to($user->email)->send(new OtpMail($otpCode));
    }
}

