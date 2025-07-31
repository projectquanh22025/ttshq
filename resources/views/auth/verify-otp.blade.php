<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            Vui lòng nhập mã OTP đã được gửi tới email của bạn.
        </div>

        <!-- Hiển thị lỗi validate -->
        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('otp.verify') }}">
            @csrf

            <!-- Input ẩn gửi email -->
            <input type="hidden" name="email" value="{{ $email }}">

            <!-- Nhập OTP -->
            <div>
                <x-label for="otp_code" value="Mã OTP" />
                <x-input id="otp_code" class="block mt-1 w-full" type="text" name="otp_code" required autofocus maxlength="6" />
            </div>

            <div class="flex items-center justify-between mt-4">
                <!-- Nút gửi lại OTP -->
                <a href="{{ route('otp.resend', ['email' => $email]) }}" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Gửi lại OTP
                </a>

                <!-- Nút xác thực -->
                <x-button class="ml-4">
                    Xác thực
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>

