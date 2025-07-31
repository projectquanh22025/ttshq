<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            Vui lòng nhập mã OTP đã được gửi tới email của bạn.
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('otp.verify') }}">
            @csrf

            <div>
                <x-label for="otp_code" value="Mã OTP" />
                <x-input id="otp_code" class="block mt-1 w-full" type="text" name="otp_code" required autofocus maxlength="6" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    Xác thực
                </x-button>
                
                <a href="{{ route('otp.resend') }}" class="underline text-sm text-gray-600 hover:text-gray-900 ml-4">
                Gửi lại OTP
                 </a>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>

