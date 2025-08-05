@extends('layouts.app')

@section('content')
<div class="login-form pt-11">
    {{-- Tiêu đề --}}
    <div class="text-center pb-8">
        <h2 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Xác thực OTP</h2>
        <p class="text-muted font-weight-bold font-size-h4">
            Nhập mã OTP đã gửi đến email: <strong>{{ $email }}</strong>
        </p>
    </div>

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

    {{-- Form OTP --}}
    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf

        {{-- Input ẩn --}}
        <input type="hidden" name="email" value="{{ $email }}">
        <input type="hidden" name="flow" value="{{ $flow }}">

        {{-- Nhập OTP --}}
        <div class="form-group">
            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" 
                   type="text" 
                   placeholder="Mã OTP 6 chữ số" 
                   name="code" 
                   maxlength="6" 
                   value="{{ old('code') }}" 
                   required autofocus />
        </div>

        {{-- Nút xác thực và gửi lại --}}
        <div class="form-group d-flex flex-wrap flex-center pb-lg-0 pb-3">
            <button type="submit" 
                    class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">
                Xác thực
            </button>

            {{-- Gửi lại OTP có đếm ngược --}}
            <button type="button" 
                    id="resendBtn" 
                    onclick="resendOtp()" 
                    class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">
                Gửi lại OTP (<span id="countdown">30</span>s)
            </button>
        </div>
    </form>
</div>

<script>
    let countdown = 30;
    const countdownEl = document.getElementById('countdown');
    const resendBtn = document.getElementById('resendBtn');

    resendBtn.disabled = true;
    const timer = setInterval(() => {
        countdown--;
        countdownEl.textContent = countdown;
        if (countdown <= 0) {
            clearInterval(timer);
            resendBtn.disabled = false;
            resendBtn.innerHTML = 'Gửi lại OTP';
        }
    }, 1000);

    function resendOtp() {
        resendBtn.disabled = true;
        window.location.href = "{{ route('otp.resend', ['email' => $email, 'flow' => $flow]) }}";
    }
</script>
@endsection
