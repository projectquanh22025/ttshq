@extends('layouts.app')

@section('content')
<div class="reset-password-form py-10">
    <h2 class="mb-4">Đổi mật khẩu</h2>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('forgot.password.resetPassword') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="form-group">
            <label>Mật khẩu mới</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Nhập lại mật khẩu</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Đổi mật khẩu</button>
    </form>
</div>
@endsection



