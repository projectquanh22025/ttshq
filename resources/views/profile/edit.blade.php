@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Chỉnh sửa thông tin</h2>

    {{-- Thông báo lỗi/ thành công --}}
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Tên người dùng</label>
            <input type="text" name="name" id="name" class="form-control"
                   value="{{ old('name', auth()->user()->username) }}" required>
        </div>

        <div class="form-group mt-3">
            <label for="password">Mật khẩu mới (nếu đổi)</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>

        <div class="form-group mt-3">
            <label for="password_confirmation">Xác nhận mật khẩu</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-success mt-4">Lưu thay đổi</button>
    </form>
</div>
@endsection
