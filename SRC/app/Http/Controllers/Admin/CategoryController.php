<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $categories = Category::withCount('books')->latest()->paginate(10);
    return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:255|unique:categories']);
    Category::create([
        'name' => $validated['name'],
        'slug' => Str::slug($validated['name'])
    ]);
    return redirect()->route('admin.categories.index')->with('success', 'Tạo danh mục thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
{
    return view('admin.categories.edit', compact('category'));
}

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Category $category)
{
    $validated = $request->validate(['name' => 'required|string|max:255|unique:categories,name,' . $category->id]);
    $category->update([
        'name' => $validated['name'],
        'slug' => Str::slug($validated['name'])
    ]);
    return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
}

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Category $category)
{
    // Kiểm tra nếu danh mục còn sách thì không cho xóa (để an toàn)
    if ($category->books()->count() > 0) {
        return back()->with('error', 'Không thể xóa danh mục đang có sách.');
    }
    $category->delete();
    return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công!');
}
}
