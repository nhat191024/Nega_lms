<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::with('children', 'parent')->get();
        return view('categories.index', compact('categories'));
    }


    public function create()
    {
        $categories = Category::all();
        return view('categories.create', compact('categories'));
    }
    public function store(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,inactive',
        ]);

        // Tạo mới danh mục
        Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'status' => $request->status,
        ]);

        // Chuyển hướng với thông báo thành công
        return redirect()->route('categories.index')->with('success', 'Danh mục đã được thêm thành công!');
    }
}

