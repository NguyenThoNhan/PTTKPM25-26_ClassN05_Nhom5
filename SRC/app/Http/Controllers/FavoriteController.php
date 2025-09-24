<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Hiển thị trang "Tủ sách của tôi" với danh sách các sách yêu thích.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Lấy danh sách sách yêu thích của user, có phân trang
        $favoriteBooks = $user->favoriteBooks()->with('categories')->paginate(12);

        return view('favorites.index', compact('favoriteBooks'));
    }

    /**
     * Thêm hoặc xóa một cuốn sách khỏi danh sách yêu thích.
     * Hàm này được thiết kế để trả về JSON cho các request AJAX.
     */
    public function toggle(Book $book, Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        // toggle() là một phương thức tiện lợi của Eloquent cho mối quan hệ nhiều-nhiều.
        // Nó sẽ tự động kiểm tra: nếu đã tồn tại thì xóa đi (detach), nếu chưa có thì thêm vào (attach).
        $result = $user->favoriteBooks()->toggle($book->id);

        // Kiểm tra kết quả để trả về trạng thái
        // 'attached' nghĩa là vừa thêm vào, 'detached' là vừa xóa đi.
        $isFavorited = count($result['attached']) > 0;

        return response()->json([
            'is_favorited' => $isFavorited,
            'message' => $isFavorited ? 'Đã thêm vào tủ sách!' : 'Đã xóa khỏi tủ sách.'
        ]);
    }
}