<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // <-- Đảm bảo có use này
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Hiển thị danh sách các sách/tài liệu.
     */
    public function index()
    {
        $books = Book::with('user')->latest()->paginate(10);
        return view('admin.books.index', compact('books'));
    }

    /**
     * Hiển thị form để tạo mới.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    /**
     * Lưu một sách/tài liệu mới vào CSDL.
     */
    public function store(Request $request)
    {
        // 1. Validate các trường, bỏ validation cho 'content'
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:physical,online',
            'quantity' => 'required_if:type,physical|nullable|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'document_file' => 'required_if:type,online|nullable|file|mimes:txt',
        ]);

        // 2. Xử lý logic cho tài liệu online
        if ($validated['type'] === 'online') {
            // Đọc nội dung file và gán vào mảng $validated
            $validated['content'] = File::get($request->file('document_file')->getRealPath());
        }

        // 3. Xử lý upload ảnh bìa
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('book_covers', 'public');
            $validated['cover_image_path'] = $path;
        }

        // 4. Gán các thông tin còn lại và tạo sách
        $validated['user_id'] = auth()->id();
        $book = Book::create($validated);
        
        if ($request->has('categories')) {
            $book->categories()->sync($request->categories);
        }

        return redirect()->route('admin.books.index')->with('success', 'Thêm sách/tài liệu thành công!');
    }

    /**
     * Hiển thị form để chỉnh sửa.
     */
    public function edit(Book $book)
    {
        $categories = Category::all();
        // Eager load các category đã được chọn của sách này
        $book->load('categories');
        return view('admin.books.edit', compact('book', 'categories'));
    }

    /**
     * Cập nhật một sách/tài liệu đã có.
     */
    public function update(Request $request, Book $book)
    {
        // 1. Validate các trường
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:physical,online',
            'quantity' => 'required_if:type,physical|nullable|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'document_file' => 'nullable|file|mimes:txt',
        ]);

        // 2. Xử lý logic cho tài liệu online
        if ($validated['type'] === 'online') {
            // Nếu có file mới được tải lên, đọc nội dung và ghi đè
            if ($request->hasFile('document_file')) {
                $validated['content'] = File::get($request->file('document_file')->getRealPath());
            } 
            // Nếu không có file mới, nhưng sách này vốn là online và chưa có content
            elseif (empty($book->content)) {
                 return back()->withErrors(['document_file' => 'Bạn phải tải lên file .txt khi chuyển sang loại tài liệu online.'])->withInput();
            }
        } else {
            // Nếu chuyển từ online sang physical, xóa nội dung cũ đi
            $validated['content'] = null;
        }

        // 3. Xử lý ảnh bìa
        if ($request->hasFile('cover_image')) {
            if ($book->cover_image_path) {
                Storage::disk('public')->delete($book->cover_image_path);
            }
            $path = $request->file('cover_image')->store('book_covers', 'public');
            $validated['cover_image_path'] = $path;
        }

        $book->update($validated);
        $book->categories()->sync($request->categories ?? []);

        return redirect()->route('admin.books.index')->with('success', 'Cập nhật sách/tài liệu thành công!');
    }

    /**
     * Xóa một sách/tài liệu.
     */
    public function destroy(Book $book)
    {
        // Xóa ảnh bìa khỏi storage
        if ($book->cover_image_path) {
            Storage::disk('public')->delete($book->cover_image_path);
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Xóa sách/tài liệu thành công!');
    }
}