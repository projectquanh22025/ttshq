<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $table = 'otps';

    
    protected $fillable = [
        'user_id',
        'email',         
        'code',
        'status',
        'expires_at'
        
    ];

    protected $date = [
       'created_at',
         'updated_at'
    ];
}

