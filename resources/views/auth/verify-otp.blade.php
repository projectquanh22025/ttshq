
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực OTP</title>
    <link href="{{ asset('build/assets/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">

    <div class="w-full max-w-md space-y-8 bg-white p-6 rounded-lg shadow-md">
        <!-- Logo -->
        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto">
        </div>

        <!-- Thông báo -->
        <div class="text-sm text-gray-600 mb-4">
            Vui lòng nhập mã OTP đã được gửi tới email của bạn.
        </div>

        <!-- Hiển thị lỗi validate -->
        @if ($errors->any())
            <div class="mb-4 text-red-600 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form nhập OTP -->
        <form method="POST" action="{{ route('otp.verify') }}">
            @csrf

            <!-- Input ẩn gửi email -->
            <input type="hidden" name="email" value="{{ $email }}">

            <!-- Nhập OTP -->
            <div class="mb-4">
                <label for="otp_code" class="block text-gray-700 text-sm font-medium">Mã OTP</label>
                <input id="otp_code" type="text" name="otp_code" maxlength="6" required autofocus
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>

            <div class="flex items-center justify-between">
                <!-- Nút gửi lại OTP -->
                <a href="{{ route('otp.resend', ['email' => $email]) }}" class="text-sm text-indigo-600 hover:text-indigo-900 underline">
                    Gửi lại OTP
                </a>

                <!-- Nút xác thực -->
                <button type="submit"
                        class="ml-4 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Xác thực
                </button>
            </div>
        </form>
    </div>

</body>
</html>

