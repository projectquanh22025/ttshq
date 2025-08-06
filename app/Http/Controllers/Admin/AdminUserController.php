<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    /**
     * Hiển thị danh sách người dùng.
     */
    public function index()
    {
        $users = User::all();  

        return view('admin.account', compact('users')); 
    }

    public function view($id)
{
    $user = User::findOrFail($id);  

    return view('admin.view', compact('user'));
}
public function lock($id)
{
    $user = User::findOrFail($id);
    $user->is_active = false;
    $user->save();

    return redirect()->route('admin.user.view', $id)
                     ->with('status', 'Khóa tài khoản thành công.');
}
public function unlock($id)
{
    $user = User::findOrFail($id);

    if ($user->is_active) {
        return redirect()->route('admin.user.view', $id)
                         ->with('status', 'Tài khoản đã hoạt động.');
    }

    $user->is_active = true;
    $user->save();

    return redirect()->route('admin.user.view', $id)
                     ->with('status', 'Mở khóa tài khoản thành công.');
}

public function search(Request $request)
{
    $keyword = trim($request->input('search'));

    $users = User::where('username', 'like', "%$keyword%")
                 ->orWhere('id', $keyword)
                 ->get();

    if ($users->isEmpty()) {
        return redirect()->route('admin.users.index')
                         ->with('status', "Không tìm thấy tài khoản nào với từ khóa: $keyword");
    }

    return view('admin.account', compact('users'))
           ->with('status', "Tìm thấy " . $users->count() . " tài khoản khớp với từ khóa: $keyword");
}

}


