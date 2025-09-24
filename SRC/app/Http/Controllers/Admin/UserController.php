<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
{
    // Lấy tất cả user ngoại trừ admin đang đăng nhập, để tránh tự sửa mình
    $users = User::where('id', '!=', auth()->id())->latest()->paginate(10);
    return view('admin.users.index', compact('users'));
}
public function edit(User $user)
{
    // Không cho phép sửa chính mình
    if ($user->id === auth()->id()) {
        abort(403, 'Bạn không thể tự sửa vai trò của mình.');
    }
    return view('admin.users.edit', compact('user'));
}
public function update(Request $request, User $user)
{
    // Không cho phép sửa chính mình
    if ($user->id === auth()->id()) {
         abort(403, 'Bạn không thể tự sửa vai trò của mình.');
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        'role' => 'required|in:admin,user',
    ]);
    
    $user->update($validated);

    return redirect()->route('admin.users.index')->with('success', 'Cập nhật thông tin người dùng thành công!');
}
public function destroy(User $user)
{
    // QUY TẮC BẢO MẬT QUAN TRỌNG:
    // 1. Ngăn không cho admin tự xóa chính mình.
    if ($user->id === auth()->id()) {
        return back()->with('error', 'Bạn không thể tự xóa tài khoản của chính mình!');
    }

    // 2. (Tùy chọn) Ngăn không cho xóa các admin khác để tránh việc lạm dụng quyền lực.
    // Bạn có thể bỏ qua điều kiện này nếu muốn admin cấp cao có thể xóa admin cấp thấp.
    if ($user->role === 'admin') {
        return back()->with('error', 'Không thể xóa tài khoản của một Admin khác.');
    }

    // Thực hiện xóa người dùng
    $user->delete();

    // Chuyển hướng về trang danh sách với thông báo thành công
    return redirect()->route('admin.users.index')->with('success', 'Đã xóa người dùng thành công!');
}
}
