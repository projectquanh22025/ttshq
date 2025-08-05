@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-dark font-weight-bold">Thông tin cá nhân</h2>

    {{-- Thông báo thành công --}}
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    {{-- Hiển thị lỗi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form cập nhật hồ sơ --}}
    <form method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="name">Tên người dùng</label>
        <input type="text" name="name" id="name" class="form-control" 
               value="{{ old('name', auth()->user()->username) }}" required>
    </div>

    <div class="form-group mt-3">
        <label for="email">Email</label>
        <input type="email" id="email" class="form-control" 
               value="{{ auth()->user()->email }}" disabled>
    </div>

    <div class="form-group mt-3">
        <label for="password">Mật khẩu mới (nếu muốn đổi)</label>
        <input type="password" name="password" id="password" class="form-control">
    </div>

    <div class="form-group mt-3">
        <label for="password_confirmation">Xác nhận mật khẩu</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary mt-4" >Cập nhật</button>
    
</form>


    {{-- PHẦN XÁC THỰC 2FA --}}
    <hr class="my-5">

    <h4 class="mb-3">Bảo mật tài khoản (Google Authenticator - 2FA)</h4>

    @if (auth()->user()->google2fa_enable)
        <p class="text-success">Bạn đã bật xác thực 2 bước (2FA).</p>

        <form method="POST" action="{{ route('2fa.disable') }}">
            @csrf
            <div class="form-group">
                <label for="otp">Nhập mã OTP từ Google Authenticator để tắt 2FA:</label>
                <input type="text" name="otp" id="otp" class="form-control w-50" placeholder="Mã OTP 6 số" required>
                @error('otp') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-danger mt-2">Tắt 2FA</button>
        </form>
    @else
        <p class="text-warning">⚠ Bạn chưa bật xác thực 2 bước (2FA) để bảo vệ tài khoản an toàn hơn.</p>
        <a href="{{ route('2fa.setup') }}" class="btn btn-primary">Bật 2FA ngay</a>
    @endif
</div>
@endsection





