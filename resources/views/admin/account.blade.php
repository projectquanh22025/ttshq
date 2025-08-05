@extends('layouts.admin')

@section('title', 'Quản lý Tài khoản')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-primary mb-0">
            <i class="fas fa-users-cog"></i> Quản lý Tài khoản 
        </h1>
        <span class="badge bg-gradient-pink text-white px-4 py-2 shadow fs-6">
            Tổng: {{ count($users) }} người dùng
        </span>
    </div>

    {{-- Breadcrumb --}}
 

    {{-- Bảng người dùng --}}
    <div class="card shadow border-0 rounded-4 mt-3">
        <div class="card-header bg-gradient-info text-white fw-bold fs-5">
            <i class="fas fa-table"></i> Danh sách Người dùng 
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover text-center align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 25%;"> Tên người dùng</th>
                            <th style="width: 30%;"> Email</th>
                            <th style="width: 20%;"> Vai trò & Trạng thái</th>
                            <th style="width: 20%;"> Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td class="fw-bold text-primary text-center">
                                    <i class="fas fa-user"></i> {{ $user->username }}
                                </td>

                                <td class="text-muted text-center">
                                    <i class="fas fa-envelope"></i> {{ $user->email }}
                                </td>

                                <td class="text-center">
                                    @if($user->role === 'admin')
                                        <span class="badge bg-gradient-danger text-white mb-1 px-3 py-1 rounded-pill">
                                            <i class="fas fa-user-shield"></i> Admin 
                                        </span>
                                    @else
                                        <span class="badge bg-gradient-secondary text-white mb-1 px-3 py-1 rounded-pill">
                                            <i class="fas fa-user"></i> User 
                                        </span>
                                    @endif
                                    <br>
                                    @if($user->is_active)
                                        <span class="badge bg-gradient-success text-white px-2 py-1 rounded-pill mt-1">
                                             Hoạt động
                                        </span>
                                    @else
                                        <span class="badge bg-gradient-warning text-dark px-2 py-1 rounded-pill mt-1">
                                             Chưa kích hoạt
                                        </span>
                                    @endif
                                </td>

                                <td>
                                   <div class="d-flex justify-content-center gap-2">
        {{-- Nút Xem (button, không dùng link) --}}
        <form action="{{ route('admin.user.view', $user->id) }}" method="GET">
            <button type="submit" class="btn btn-sm btn-gradient-info text-white">
                <i class="fas fa-eye"></i> Xem
            </button>
        </form>

        {{-- Nút Khóa hoặc Mở khóa --}}
        @if($user->is_active)
            <form action="{{ route('admin.user.lock', $user->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-gradient-warning text-dark">
                    <i class="fas fa-lock"></i> Khóa
                </button>
            </form>
        
          
        @endif
    </div>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted py-4">
                                    <i class="fas fa-info-circle"></i> Không có người dùng nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Custom Styles --}}
<style>
    .bg-gradient-info {
        background: linear-gradient(135deg, #17a2b8, #007bff);
    }

    .bg-gradient-pink {
        background: linear-gradient(to right, #ff758c, #ff7eb3);
    }

    .bg-gradient-danger {
        background: linear-gradient(to right, #ff416c, #ff4b2b);
    }

    .bg-gradient-success {
        background: linear-gradient(to right, #56ab2f, #a8e063);
    }

    .bg-gradient-warning {
        background: linear-gradient(to right, #f7971e, #ffd200);
    }

    .bg-gradient-secondary {
        background: linear-gradient(to right, #6c757d, #adb5bd);
    }

    .btn-gradient-info {
        background: linear-gradient(to right, #17a2b8, #5bc0de);
        border: none;
    }

    .btn-gradient-info:hover {
        opacity: 0.9;
    }

    .table td, .table th {
        vertical-align: middle !important;
        text-align: center;
        white-space: nowrap;
    }
    .btn-gradient-info {
    background: linear-gradient(to right, #17a2b8, #5bc0de);
    border: none;
}
.btn-gradient-warning {
    background: linear-gradient(to right, #f7971e, #ffd200);
    border: none;
}
.btn-gradient-success {
    background: linear-gradient(to right, #28a745, #8fd19e);
    border: none;
}
.btn-gradient-info:hover,
.btn-gradient-warning:hover,
.btn-gradient-success:hover {
    opacity: 0.9;
}
</style>
@endsection

