@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-lg border-0" style="width: 400px;">
        <div class="card-body">
            <h4 class="card-title text-center mb-3">
                <i class="bi bi-shield-lock-fill text-primary"></i> Xác thực Google Authenticator
            </h4>

            @if (session('status'))
                <div class="alert alert-success text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('2fa.verify') }}">
                @csrf
                <div class="mb-3">
                    <label for="otp" class="form-label">Nhập mã gồm 6 chữ số</label>
                    <input type="text" name="otp" id="otp" class="form-control text-center" maxlength="6" required autofocus placeholder="••••••">
                    @error('otp')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-check-circle-fill me-1"></i> Xác thực
                </button>
            </form>

            <div class="text-center mt-3">
                <small class="text-muted">Bạn cần mở ứng dụng Google Authenticator để lấy mã.</small>
            </div>
        </div>
    </div>
</div>
@endsection
