<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookPageController extends Controller
{
    public function index(Request $request)
{
    // Bắt đầu với một query builder
    $query = Book::query();

    // Nếu có từ khóa tìm kiếm
    if ($request->has('search') && $request->search != '') {
        $searchTerm = $request->search;
        $query->where(function ($q) use ($searchTerm) {
            $q->where('title', 'like', '%' . $searchTerm . '%')
              ->orWhere('author', 'like', '%' . $searchTerm . '%');
        });
    }

    $books = $query->latest()->paginate(12); // Phân trang 12 cuốn

     $popularBooks = Book::with('categories')
        ->withCount('loans') // Tạo ra một cột ảo 'loans_count'
        ->orderBy('loans_count', 'desc') // Sắp xếp theo số lượt mượn
        ->take(4) // Giới hạn lấy 4 cuốn
        ->get();

    return view('home', compact('books', 'popularBooks'));
}
public function show(Book $book)
{
    $currentLoan = null;
    $isFavorited = false; 
    if (auth()->check()) {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $currentLoan = $book->loans()->where('user_id', $user->id)->where('status', 'borrowed')->first();
        $isFavorited = $user->favoriteBooks()->where('book_id', $book->id)->exists(); // <-- Thêm logic kiểm tra
    }
    return view('books.show', compact('book', 'currentLoan', 'isFavorited')); // <-- Truyền biến mới vào view

}
}
