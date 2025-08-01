
@vite(['resources/css/app.css', 'resources/js/app.js'])

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Nếu dùng Tailwind hoặc CSS khác -->
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-8 bg-white rounded shadow">
        <h2 class="text-2xl font-bold text-center mb-6">Đăng ký tài khoản</h2>

        {{-- Hiển thị lỗi --}}
        @if ($errors->any())
            <div class="mb-4">
                <ul class="text-red-600 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Tên đăng nhập</label>
                <input id="username" name="username" type="text" required autofocus
                       value="{{ old('username') }}"
                       class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-indigo-200">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" name="email" type="email" required
                       value="{{ old('email') }}"
                       class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-indigo-200">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                <input id="password" name="password" type="password" required
                       class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-indigo-200">
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Nhập lại mật khẩu</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-indigo-200">
            </div>

        
            <div class="flex items-center justify-between">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-indigo-600 underline">Đã có tài khoản?</a>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Đăng ký</button>
            </div>
        </form>
    </div>
</body>
</html>
