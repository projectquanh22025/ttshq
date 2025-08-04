@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3>Kích hoạt bảo mật Google Authenticator</h3>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if (!$isEnabled)
        <p>Quét mã QR dưới đây bằng ứng dụng Google Authenticator:</p>
        <div class="mb-4">
            <img src="{{  $qrInline }}" alt="Base64 Image">
            <!-- {!! $qrInline !!} -->
        </div>
        <p><strong>Mã bí mật:</strong> {{ $secret }}</p>

        <form method="POST" action="{{ route('2fa.enable') }}">
            @csrf
            <div class="form-group">
                <label for="otp">Nhập mã 6 số:</label>
                <input type="text" name="otp" class="form-control" required>
                @error('otp') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button class="btn btn-success mt-2">Kích hoạt 2FA</button>
        </form>
    @else
        <div class="alert alert-info">2FA đã được bật.</div>

        <form method="POST" action="{{ route('2fa.disable') }}">
            @csrf
            <div class="form-group">
                <label for="otp">Nhập mã 6 số để tắt 2FA:</label>
                <input type="text" name="otp" class="form-control" required>
                @error('otp') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button class="btn btn-danger mt-2">Tắt 2FA</button>
        </form>
    @endif
</div>
@endsection
