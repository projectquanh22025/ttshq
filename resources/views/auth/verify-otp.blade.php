@extends('layouts.app')

@section('content')
<div class="login-form pt-11">
    {{-- Hiển thị thông báo --}}
    <div class="text-center pb-8">
        <h2 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Xác thực OTP</h2>
        <p class="text-muted font-weight-bold font-size-h4">Nhập mã OTP đã gửi đến email của bạn</p>
    </div>

    {{-- Hiển thị status --}}
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    {{-- Hiển thị lỗi validate --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form xác thực OTP --}}
    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf

        {{-- Input ẩn email --}}
        <input type="hidden" name="email" value="{{ $email }}">

        {{-- Nhập mã OTP --}}
        <div class="form-group">
            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" 
                   type="text" 
                   placeholder="Mã OTP" 
                   name="code" 
                   maxlength="6" 
                   value="{{ old('code') }}" 
                   required autofocus />
        </div>

        {{-- Submit và Gửi lại OTP --}}
        <div class="form-group d-flex flex-wrap flex-center pb-lg-0 pb-3">
            <button type="submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">
                Xác thực
            </button>

            <a href="{{ route('otp.resend', ['email' => $email]) }}" 
               class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">
                Gửi lại OTP
            </a>
        </div>
    </form>
</div>
@endsection


