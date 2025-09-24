<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\QRCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QRCodeController extends Controller
{
    protected $qrService;

    public function __construct(QRCodeService $qrService)
    {
        $this->qrService = $qrService;
    }

    /**
     * Display QR code scanner page
     */
    public function scanner()
    {
        return view('qr-code.scanner');
    }

    /**
     * Handle QR code scan result
     */
    public function scan(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string'
        ]);

        try {
            $qrData = json_decode(base64_decode($request->qr_data), true);
            
            if (!$qrData || !isset($qrData['data'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR code không hợp lệ'
                ]);
            }

            // Extract book ID from URL
            $bookId = $this->extractBookIdFromURL($qrData['data']);
            
            if (!$bookId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin sách'
                ]);
            }

            $book = Book::find($bookId);
            
            if (!$book) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sách không tồn tại'
                ]);
            }

            return response()->json([
                'success' => true,
                'book' => [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'type' => $book->type,
                    'quantity' => $book->quantity,
                    'url' => route('books.show', $book)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xử lý QR code: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Generate QR code for book
     */
    public function generate(Book $book)
    {
        if (!$book->qr_code) {
            $book->generateQRCode();
        }

        return response()->json([
            'success' => true,
            'qr_code' => $book->qr_code,
            'qr_url' => $book->getQRCodeURL()
        ]);
    }

    /**
     * Display QR code for book
     */
    public function show(Book $book)
    {
        if (!$book->qr_code) {
            $book->generateQRCode();
        }

        return view('qr-code.show', compact('book'));
    }

    /**
     * Quick borrow via QR code
     */
    public function quickBorrow(Book $book)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để mượn sách'
            ]);
        }

        $user = Auth::user();

        // Check if user already has this book borrowed
        $existingLoan = $book->loans()
            ->where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->first();

        if ($existingLoan) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã mượn cuốn sách này rồi'
            ]);
        }

        // Check book availability
        if ($book->type === 'physical' && $book->quantity <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Sách đã hết, không thể mượn'
            ]);
        }

        // Create loan
        $loan = $book->loans()->create([
            'user_id' => $user->id,
            'loan_date' => now(),
            'due_date' => now()->addDays(7), // 7 days loan period
            'status' => 'borrowed',
        ]);

        // Generate digital signature for online books
        if ($book->type === 'online' && $book->content) {
            $contentHash = hash('sha256', $book->content);
            $privateKey = file_get_contents(storage_path('app/private.key'));
            
            openssl_sign($contentHash, $signature, $privateKey, OPENSSL_ALGO_SHA256);
            
            $loan->update([
                'content_hash_on_loan' => $contentHash,
                'digital_signature' => base64_encode($signature)
            ]);
        }

        // Update book quantity for physical books
        if ($book->type === 'physical') {
            $book->decrement('quantity');
        }

        return response()->json([
            'success' => true,
            'message' => 'Mượn sách thành công!',
            'loan' => [
                'id' => $loan->id,
                'due_date' => $loan->due_date->format('d/m/Y'),
                'book_title' => $book->title
            ]
        ]);
    }

    /**
     * Extract book ID from URL
     */
    private function extractBookIdFromURL($url)
    {
        // Extract book ID from route like /books/123
        if (preg_match('/\/books\/(\d+)/', $url, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
}