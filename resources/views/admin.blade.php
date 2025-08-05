<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Dashboard - Quản lý tài khoản</title>

	<!-- CSS -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
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
			<li><a href="#"><i class='bx bxs-log-out icon'></i> Đăng xuất</a></li>
		</ul>
	</section>

	<!-- NỘI DUNG CHÍNH -->
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
					<li><a href="#"><i class='bx bxs-user-circle icon'></i> Profile</a></li>
					<li><a href="#"><i class='bx bxs-log-out-circle'></i> Logout</a></li>
				</ul>
			</div>
		</nav>

		<!-- MAIN -->
		<main>
			<h1 class="title">Danh sách tài khoản</h1>
			<ul class="breadcrumbs">
				<li><a href="#">Trang chủ</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Quản lý tài khoản</a></li>
			</ul>

			<!-- DANH SÁCH TÀI KHOẢN -->
			<div class="card">
				<table class="table">
					<thead>
						<tr>
							<th>#</th>
							<th>Tên người dùng</th>
							<th>Email</th>
							<th>Vai trò</th>
							<th>Thao tác</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1</td>
							<td>Nguyễn Văn A</td>
							<td>a@gmail.com</td>
							<td>User</td>
							<td><a href="#" class="btn-view">Xem</a></td>
						</tr>
						<!-- Thêm các dòng khác -->
					</tbody>
				</table>
			</div>

		</main>
	</section>

	<!-- JS -->
	<script src="{{ asset('admin/js/script.js') }}"></script>
</body>
</html>
