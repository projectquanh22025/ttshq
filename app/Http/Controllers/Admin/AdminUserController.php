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



}

