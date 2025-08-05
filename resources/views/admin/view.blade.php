@extends('layouts.admin')

@section('title', 'Chi tiết tài khoản')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-primary mb-0">Chi tiết Tài khoản</h1>
        
    </div>

    {{-- Bảng thông tin chi tiết --}}
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-gradient-info text-white fw-bold fs-5">
            Thông tin Tài khoản
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle mb-0">
                    <tbody>
                        <tr>
                            <th style="width: 30%;" class="bg-light fw-bold text-muted">Tên người dùng</th>
                            <td class="text-primary">{{ $user->username }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light fw-bold text-muted">Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light fw-bold text-muted">Vai trò</th>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-gradient-danger text-white">Admin</span>
                                @else
                                    <span class="badge bg-gradient-secondary text-white">User</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light fw-bold text-muted">Trạng thái</th>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-gradient-success text-white">Hoạt động</span>
                                @else
                                    <span class="badge bg-gradient-warning text-dark">Chưa kích hoạt</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light fw-bold text-muted">Xác thực 2FA</th>
                            <td>
                                @if($user->google2fa_secret)
                                    <span class="badge bg-gradient-success text-white">Đã bật</span>
                                @else
                                    <span class="badge bg-gradient-secondary text-white">Chưa bật</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light fw-bold text-muted">Ngày tạo</th>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light fw-bold text-muted">Cập nhật gần nhất</th>
                            <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
                <div><a href="{{ route('admin.dashboard') }}" class="btn btn-gradient-info text-white px-4">
            Quay lại
        </a></div>
            </div>
        </div>
    </div>
</div>

{{-- CSS Gradient --}}
<style>
    .bg-gradient-info {
        background: linear-gradient(135deg, #17a2b8, #007bff);
    }
    .bg-gradient-danger {
        background: linear-gradient(to right, #ff416c, #ff4b2b);
    }
    .bg-gradient-secondary {
        background: linear-gradient(to right, #6c757d, #adb5bd);
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
    .btn-gradient-info:hover {
        opacity: 0.9;
    }
    table th, table td {
        vertical-align: middle !important;
    }
</style>
@endsection
