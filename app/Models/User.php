<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shop_id',
        'username',
        'fullname',
        'account_type',
        'email',
        'email_verified_at',
        'password',
        'password2',
        'is_active',
        'balance',
        'remember_token',
        'status',
        'required_login_gmail',
        'provider_id',
        'google2fa_enable',
        'google2fa_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'password2',
        'remember_token',
        'google2fa_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'balance' => 'decimal:2',
        'google2fa_enable' => 'boolean',
        'required_login_gmail' => 'boolean',
        'status' => 'integer',
        'account_type' => 'integer',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Relationship: User has many OTPs
     */
    public function otps()
    {
        return $this->hasMany(Otp::class);
    }
}
