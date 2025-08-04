@extends('layouts.app')

@section('content')
<div class="login-form py-11">

    {{-- Thông báo lỗi khi tài khoản bị khóa tạm thời --}}
    @if ($errors->has('email') && str_contains($errors->first('email'), 'tạm khóa'))
        @php
            preg_match('/(\d+)\s*giây/', $errors->first('email'), $matches);
            $lockoutSeconds = $matches[1] ?? 0;
        @endphp
        <div class="alert alert-warning" id="lockout-message">
            {{ $errors->first('email') }}<br>
            Vui lòng đợi <span id="countdown">{{ $lockoutSeconds }}</span> giây...
        </div>
    @endif

    {{-- Thông báo lỗi khác --}}
    @if ($errors->any() && !$errors->has('email'))
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Thông báo thành công từ session --}}
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    {{-- Form đăng nhập --}}
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="text-center pb-8">
            <h2 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Sign In</h2>
            <span class="text-muted font-weight-bold font-size-h4">
                Or <a href="{{ route('register') }}" class="text-primary font-weight-bolder">Create An Account</a>
            </span>
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label>Email</label>
            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   @if(isset($lockoutSeconds) && $lockoutSeconds > 0) disabled @endif />
            @error('email')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="form-group">
            <label>Password</label>
            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg"
                   type="password"
                   name="password"
                   required
                   @if(isset($lockoutSeconds) && $lockoutSeconds > 0) disabled @endif />
            @error('password')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Forgot password --}}
        <div class="form-group text-right">
            <a href="{{ route('forgot.password.form') }}" class="text-primary font-weight-bold">
                Forgot Password?
            </a>
        </div>

        {{-- Submit --}}
        <div class="text-center pt-2">
            <button type="submit"
                    id="signInBtn"
                    class="btn btn-dark font-weight-bolder font-size-h6 px-8 py-4 my-3"
                    @if(isset($lockoutSeconds) && $lockoutSeconds > 0) disabled @endif>
                Sign In
            </button>
        </div>
    </form>
</div>

{{-- Countdown Script --}}
@if(isset($lockoutSeconds) && $lockoutSeconds > 0)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let countdown = {{ $lockoutSeconds }};
        const countdownElement = document.getElementById('countdown');
        const signInBtn = document.getElementById('signInBtn');
        const emailInput = document.querySelector('input[name="email"]');
        const passwordInput = document.querySelector('input[name="password"]');
        const lockoutMessage = document.getElementById('lockout-message');

        function updateCountdown() {
            if (countdown > 0) {
                countdownElement.textContent = countdown;
                countdown--;
                setTimeout(updateCountdown, 1000);
            } else {
                // Hết thời gian -> mở khóa form
                emailInput.disabled = false;
                passwordInput.disabled = false;
                signInBtn.disabled = false;
                signInBtn.textContent = 'Sign In';

                // Hiện thông báo đã mở khóa
                const unlockAlert = document.createElement('div');
                unlockAlert.className = 'alert alert-success mt-3';
                unlockAlert.innerHTML = 'Tài khoản đã được mở khóa. Bạn có thể đăng nhập.';
                lockoutMessage.replaceWith(unlockAlert);
            }
        }

        updateCountdown();
    });
</script>
@endif
@endsection


