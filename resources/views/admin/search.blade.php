@extends('layouts.admin')

@section('title', 'Kết quả tìm kiếm tài khoản')

@section('content')
<div class="container-fluid py-4">
    {{-- Tiêu đề --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 fw-bold text-primary">
            <i class="fas fa-search"></i> Kết quả tìm kiếm
        </h1>
        <a href="{{ route('admin.users.search') }}" class="btn btn-sm btn-gradient-secondary text-white">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    {{-- Thông báo --}}
    @if(session('status'))
        <div class="alert alert-info">{{ session('status') }}</div>
    @endif

    {{-- Bảng kết quả --}}
    <div class="card shadow border-0 rounded-4 mt-3">
        <div class="card-header bg-gradient-info text-white fw-bold fs-5">
            <i class="fas fa-user"></i> Thông tin tài khoản
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-gradient-danger">Admin</span>
                                @else
                                    <span class="badge bg-gradient-secondary">User</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-gradient-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-gradient-warning text-dark">Chưa kích hoạt</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.user.view', $user->id) }}" class="btn btn-sm btn-gradient-info text-white">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- CSS bổ sung --}}
<style>
    .bg-gradient-info {
        background: linear-gradient(135deg, #17a2b8, #007bff);
    }
    .bg-gradient-secondary {
        background: linear-gradient(to right, #6c757d, #adb5bd);
    }
    .bg-gradient-danger {
        background: linear-gradient(to right, #ff416c, #ff4b2b);
    }
    .bg-gradient-success {
        background: linear-gradient(to right, #28a745, #8fd19e);
    }
    .bg-gradient-warning {
        background: linear-gradient(to right, #f7971e, #ffd200);
    }
    .btn-gradient-info {
        background: linear-gradient(to right, #17a2b8, #5bc0de);
        border: none;
    }
    .btn-gradient-secondary {
        background: linear-gradient(to right, #6c757d, #adb5bd);
        border: none;
    }
</style>
@endsection
