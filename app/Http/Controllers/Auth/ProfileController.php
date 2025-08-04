<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
    // Hiển thị giao diện thông tin cá nhân
    public function show()
    {
        return view('profile.show'); // View tại resources/views/profile/show.blade.php
    }

    // Xử lý cập nhật tên người dùng và mật khẩu
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->username = $request->name;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.show')->with('status', 'Cập nhật thành công!');
    }
}

