@extends('layouts.app')

@section('content')
<div class="forgot-password-form py-10">
    <h2 class="mb-4">Quên mật khẩu</h2>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('forgot.password.sendOtp') }}">
        @csrf
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>

        <button type="submit" class="btn btn-primary">Gửi mã OTP</button>
    </form>
</div>
@endsection


