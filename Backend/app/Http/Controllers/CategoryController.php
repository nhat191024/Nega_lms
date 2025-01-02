<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Phương thức index: Hiển thị danh sách danh mục
    public function index()
    {
        $categories = Category::with('children', 'parent')->get();
        return view('categories.index', compact('categories'));
    }

    // Phương thức create: Hiển thị form tạo danh mục
    public function create()
    {
        $categories = Category::all();
        return view('categories.create', compact('categories'));
    }

    // Phương thức store: Lưu danh mục mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,inactive',
        ]);

        // Kiểm tra nếu danh mục cha đã là con của danh mục khác
        if ($request->parent_id) {
            $parentCategory = Category::find($request->parent_id);
            if ($parentCategory->parent_id !== null) {
                return redirect()->back()->with('error', 'Không thể chọn danh mục cha vì nó đã là con của một danh mục khác.');
            }
        }

        // Lưu danh mục mới vào cơ sở dữ liệu
        Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'status' => $request->status,
        ]);

        // Chuyển hướng với thông báo thành công
        return redirect()->route('categories.index')->with('success', 'Danh mục đã được thêm thành công!');
    }

    // Phương thức update: Cập nhật danh mục
    public function update(Request $request, $id)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,inactive',
        ]);

        $category = Category::findOrFail($id);

        // Kiểm tra nếu danh mục cha đã là con của danh mục khác
        if ($request->parent_id) {
            $parentCategory = Category::find($request->parent_id);
            if ($parentCategory->parent_id !== null) {
                return redirect()->back()->with('error', 'Không thể chọn danh mục cha vì nó đã là con của một danh mục khác.');
            }
        }

        // Cập nhật danh mục
        $category->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'status' => $request->status,
        ]);

        // Chuyển hướng với thông báo thành công
        return redirect()->route('categories.index')->with('success', 'Danh mục đã được cập nhật thành công!');
    }
    public function edit($id)
{
    // Tìm danh mục theo ID
    $category = Category::findOrFail($id);

    // Lấy tất cả các danh mục để hiển thị trong danh sách chọn danh mục cha
    $categories = Category::all();

    // Trả về view chỉnh sửa danh mục
    return view('categories.edit', compact('category', 'categories'));
}
public function destroy($id)
{
    $category = Category::findOrFail($id);

    // Kiểm tra nếu danh mục có danh mục con
    if ($category->children()->count() > 0) {
        return redirect()->back()->with('error', 'Không thể xóa danh mục vì nó có danh mục con.');
    }

    // Xóa danh mục
    $category->delete();

    return redirect()->route('categories.index')->with('success', 'Danh mục đã được xóa thành công!');
}
public function status($id)
{
    $category = Category::findOrFail($id);
    $category->status = $category->status === 'active' ? 'inactive' : 'active';
    $category->save();

    return redirect()->route('categories.index')->with('success', 'Trạng thái danh mục đã được cập nhật!');
}


}
