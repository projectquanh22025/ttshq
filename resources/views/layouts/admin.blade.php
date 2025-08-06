<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    @stack('styles')
</head>
<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand"><i class='bx bxs-shield icon'></i> AdminPanel</a>
        <ul class="side-menu">
            <li><a href="#" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
            <li class="divider" data-text="QUẢN LÝ">Quản lý</li>
            <li>
                <a href="#"><i class='bx bxs-user-account icon'></i> Tài khoản <i class='bx bx-chevron-right icon-right'></i></a>
                <ul class="side-dropdown">
                    <li><a href="#">Danh sách tài khoản</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class='bx bxs-log-out icon'></i> Đăng xuất
                </a>
            </li>
        </ul>
    </section>

    <!-- FORM ĐĂNG XUẤT -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu toggle-sidebar'></i>
            <form>
                <div class="form-group">
                    <input type="text" placeholder="Tìm kiếm tài khoản...">
                    <i class='bx bx-search icon'></i>
                </div>
            </form>
            <div class="profile">
                <img src="https://i.pravatar.cc/40" alt="Admin">
                <ul class="profile-link">
                    <li><a href="{{ route('admin.profile') }}"><i class='bx bxs-user-circle icon'></i> {{ auth()->user()->username }}</a></li>
                    <li>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class='bx bxs-log-out-circle'></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- MAIN -->
        <main>
            @yield('content')
        </main>
    </section>

    <!-- JS -->
    <script src="{{ asset('admin/js/script.js') }}"></script>
    @stack('scripts')
</body>
</html>
