<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
    // Hiển thị giao diện thông tin cá nhân
    public function edit()
{
    return view('profile.edit');
}

    // Xử lý cập nhật tên người dùng và mật khẩu
    public function update(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'password' => 'nullable|string|min:6|confirmed',
    ]);

    $user = auth()->user();
    $user->username = $request->name;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return back()->with('status', 'Cập nhật thông tin thành công!');
}
}

