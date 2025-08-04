@extends('layouts.app')

@section('content')
<div class="container py-10">
    <h2 class="mb-6 text-dark font-weight-bold">Thông tin cá nhân</h2>

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
    <form method="POST" >
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Tên</label>
            <input type="text" name="name" id="name" class="form-control" 
                   value="{{ old('name', auth()->user()->name) }}" required>
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

        <button type="submit" class="btn btn-primary mt-4">Cập nhật</button>
    </form>
</div>
@endsection
