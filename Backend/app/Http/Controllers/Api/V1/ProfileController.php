<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    // API SHOW Profile
    public function showProfile($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Không tìm thấy người dùng'], 404);
        }

        return response()->json(['data' => $user]);
    }

    public function updateProfile(Request $request, $id)
    {
        // Kiểm tra dữ liệu đầu vào (debugging)
        Log::info('Data received: ', $request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id, // Kiểm tra tính duy nhất trừ chính người dùng hiện tại
            'phone' => 'required',
            'gender' => 'required',
            'birthday' => 'required|date|before:today',
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại'], 404);
        }

        $updated = $user->update($validated);

        if (!$updated) {
            return response()->json(['message' => 'Cập nhật thông tin thất bại'], 500);
        } else {
            return response()->json(
                [
                    'message' => 'Cập nhật thông tin thành công',
                    'data' => $user,
                ],
                200,
            );
        }
    }
}
