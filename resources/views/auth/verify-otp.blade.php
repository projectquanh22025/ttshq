@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Nhập mã OTP') }}</div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('otp.verify') }}">
                        @csrf
                        <div class="form-group">
                            <label for="otp">{{ __('Mã OTP') }}</label>
                            <input type="text" name="otp" id="otp" class="form-control" required autofocus>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">{{ __('Xác nhận') }}</button>
                    </form>

                    <form method="POST" action="{{ route('otp.resend') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-link">{{ __('Gửi lại mã OTP') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
