<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OfflineController extends Controller
{
    /**
     * Display offline reading page
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's borrowed online books
        $offlineBooks = $user->loans()
            ->where('status', 'borrowed')
            ->whereHas('book', function($query) {
                $query->where('type', 'online');
            })
            ->with('book')
            ->get();

        return view('offline.index', compact('offlineBooks'));
    }

    /**
     * Download book for offline reading
     */
    public function download(Book $book)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized']);
        }

        $user = Auth::user();
        
        // Check if user has borrowed this book
        $loan = $book->loans()
            ->where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->first();

        if (!$loan) {
            return response()->json(['success' => false, 'message' => 'Bạn chưa mượn cuốn sách này']);
        }

        if ($book->type !== 'online') {
            return response()->json(['success' => false, 'message' => 'Chỉ có thể tải xuống sách online']);
        }

        // Create offline content
        $offlineContent = [
            'book' => [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'description' => $book->description,
                'content' => $book->content,
                'downloaded_at' => now()->toISOString(),
            ],
            'loan' => [
                'id' => $loan->id,
                'loan_date' => $loan->loan_date->toISOString(),
                'due_date' => $loan->due_date->toISOString(),
                'digital_signature' => $loan->digital_signature,
                'content_hash' => $loan->content_hash_on_loan,
            ]
        ];

        // Save to storage
        $filename = "offline/books/book-{$book->id}-user-{$user->id}.json";
        Storage::disk('public')->put($filename, json_encode($offlineContent));

        return response()->json([
            'success' => true,
            'message' => 'Tải xuống thành công',
            'filename' => $filename,
            'book' => $offlineContent['book']
        ]);
    }

    /**
     * Get offline book content
     */
    public function getOfflineContent(Book $book)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized']);
        }

        $user = Auth::user();
        $filename = "offline/books/book-{$book->id}-user-{$user->id}.json";

        if (!Storage::disk('public')->exists($filename)) {
            return response()->json(['success' => false, 'message' => 'Sách chưa được tải xuống']);
        }

        $content = Storage::disk('public')->get($filename);
        $offlineData = json_decode($content, true);

        return response()->json([
            'success' => true,
            'content' => $offlineData
        ]);
    }

    /**
     * Sync offline changes
     */
    public function sync(Request $request, Book $book)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized']);
        }

        $user = Auth::user();
        
        // Check if user has borrowed this book
        $loan = $book->loans()
            ->where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->first();

        if (!$loan) {
            return response()->json(['success' => false, 'message' => 'Bạn chưa mượn cuốn sách này']);
        }

        $request->validate([
            'content' => 'required|string',
            'offline_hash' => 'required|string'
        ]);

        // Verify content integrity
        $currentHash = hash('sha256', $request->content);
        
        if ($currentHash !== $request->offline_hash) {
            return response()->json(['success' => false, 'message' => 'Nội dung đã bị thay đổi']);
        }

        // Update book content
        $book->update(['content' => $request->content]);

        // Update loan with new hash
        $loan->update(['content_hash_on_loan' => $currentHash]);

        return response()->json([
            'success' => true,
            'message' => 'Đồng bộ thành công'
        ]);
    }

    /**
     * Check offline status
     */
    public function status()
    {
        return response()->json([
            'success' => true,
            'offline' => !request()->header('X-Requested-With'),
            'timestamp' => now()->toISOString()
        ]);
    }
}