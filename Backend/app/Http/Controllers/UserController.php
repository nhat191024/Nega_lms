<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $roles = [
            1 => 'Quản trị',
            2 => 'Giảng viên',
            3 => 'Sinh viên',
            4 => 'giám sát viên',
        ];
        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Avatar phải là hình ảnh
            'password' => 'required|string|min:8|confirmed',
            'gender' => 'required|in:male,female,other',
            'role_id' => 'required|in:1,2,3,4',
            'status' => 'required|boolean',
        ], [
            'name.required' => 'Tên người dùng là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email phải là một địa chỉ email hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'avatar.image' => 'Avatar phải là tệp hình ảnh.',
            'avatar.mimes' => 'Avatar phải có định dạng jpeg, png, jpg, hoặc gif.',
            'avatar.max' => 'Avatar không được vượt quá 2MB.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'gender.required' => 'Vui lòng chọn giới tính.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'role_id.required' => 'Vui lòng chọn vai trò người dùng.',
            'role_id.in' => 'Vai trò không hợp lệ.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.boolean' => 'Trạng thái phải là giá trị đúng hoặc sai.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'avatar' => $avatarPath,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('users.index')->with('success', 'Người dùng đã được tạo thành công!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $users = User::findOrFail($id);
        return view('users.edit', compact('users'));
    }

    public function update(Request $request, string $id)
    {
        $users = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . $id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'role_id' => 'required|in:1,2,3,4',
        ],[
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'avatar.image' => 'Avatar phải là tệp hình ảnh.',
            'avatar.mimes' => 'Avatar chỉ chấp nhận các định dạng jpeg, png, jpg, gif.',
            'avatar.max' => 'Avatar không được vượt quá 2MB.',
            'name.required' => 'Tên người dùng là bắt buộc.',
            'gender.required' => 'Giới tính là bắt buộc.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'role_id.required' => 'Vui lòng chọn vai trò.',
            'role_id.in' => 'Vai trò không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.edit', ['id' => $users->id])
                ->withErrors($validator)
                ->withInput();
        }
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $users->avatar = $avatarPath;
        }

        $users->update([
            'email' => $request->input('email'),
        'name' => $request->input('name'),
        'gender' => $request->input('gender'),
        'role_id' => $request->input('role_id'),
        ]);

        return redirect()->route('users.index')->with('success', 'Thông tin người dùng đã được cập nhật thành công!');
    }

    public function status($id)
    {
        $users = User::findOrFail($id);
        $users->status = !$users->status;
        $users->save();

        return redirect()->route('users.index')->with('success', 'Trạng thái người dùng đã được cập nhật!');
    }

    public function destroy(string $id)
    {
        //
    }
}
