<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();
        return response()->json(['data' => $user], Response::HTTP_OK);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'sometimes|string',
            'gender' => 'sometimes|string',
            'birthday' => 'sometimes|date|before:today',
        ];

        $messages = [
            'name.string' => 'Tên phải là chuỗi.',
            'name.max' => 'Tên không được vượt quá 255 ký tự.',
            'email.string' => 'Email phải là chuỗi.',
            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.unique' => 'Email đã tồn tại.',
            'phone.string' => 'Số điện thoại phải là chuỗi.',
            'gender.string' => 'Giới tính phải là chuỗi.',
            'birthday.date' => 'Ngày sinh phải là ngày.',
            'birthday.before' => 'Ngày sinh không được lớn hơn ngày hiện tại.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $user = User::find($user->id);
        $user->update($request->only(['name', 'email', 'phone', 'gender', 'birthday']));

        return response()->json([
            'message' => 'Cập nhật thông tin thành công',
            'data' => $user,
        ], Response::HTTP_OK);
    }
}
