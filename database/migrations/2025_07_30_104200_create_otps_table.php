<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('email'); // Email dùng để gửi và xác thực OTP
            $table->string('otp', 6); // OTP 6 chữ số
            $table->timestamp('expires_at'); // Thời gian hết hạn OTP
            $table->json('register_data')->nullable(); 
            $table->boolean('is_verified')->default(false); 
            $table->timestamps();

            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
