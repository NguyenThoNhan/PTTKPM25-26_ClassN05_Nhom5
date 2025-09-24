<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Events\BookReturned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LoanController extends Controller
{
    /**
     * Xử lý việc mượn sách và ký tài liệu online.
     * (Phương thức này đã được hoàn thiện ở Giai đoạn 8.2)
     */
    public function store(Book $book)
    {
        // Kiểm tra xem user đã mượn cuốn này và chưa trả hay không
        $existingLoan = Loan::where('user_id', auth()->id())
                            ->where('book_id', $book->id)
                            ->where('status', 'borrowed')
                            ->exists();

        if ($existingLoan) {
            return back()->with('error', 'Bạn đã mượn cuốn sách này rồi.');
        }

        // Kiểm tra số lượng sách vật lý
        if ($book->type === 'physical' && $book->quantity <= 0) {
            return back()->with('error', 'Sách đã hết, vui lòng quay lại sau.');
        }
        
        $digitalSignature = null;
        $flashMessage = 'Mượn thành công!';

        // Xử lý logic cho từng loại sách
        if ($book->type === 'physical') {
            $book->decrement('quantity');
        } 
        elseif ($book->type === 'online') {
            try {
                $originalContent = $book->content;
                $privateKeyPath = storage_path('app/keys/private.key');

                if (!File::exists($privateKeyPath)) {
                    Log::error('Digital Signature Error: Private key not found.');
                    return back()->with('error', 'Lỗi hệ thống: Không thể tạo chữ ký số.');
                }
                $privateKey = File::get($privateKeyPath);

                openssl_sign($originalContent, $signature, $privateKey, OPENSSL_ALGO_SHA256);
                $digitalSignature = base64_encode($signature);
                
                $flashMessage = 'Mượn tài liệu thành công. Chữ ký số đã được áp dụng để đảm bảo tính toàn vẹn.';

            } catch (\Exception $e) {
                Log::error('Digital Signature Failed during signing: ' . $e->getMessage());
                return back()->with('error', 'Lỗi hệ thống: Quá trình ký tài liệu thất bại.');
            }
        }

        // Tạo lượt mượn mới
        Loan::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'loan_date' => now(),
            'due_date' => ($book->type === 'physical') ? now()->addWeeks(2) : null,
            'digital_signature' => $digitalSignature,
        ]);

        return redirect()->route('books.show', $book)->with('success', $flashMessage);
    }

    /**
     * Xử lý việc trả sách và xác thực chữ ký số.
     * (Đây là phương thức chính được cập nhật trong Giai đoạn 8.3)
     */
    public function update(Loan $loan)
    {
        // 1. Phân quyền
        if ($loan->user_id !== auth()->id() || $loan->status !== 'borrowed') {
            abort(403, 'HÀNH ĐỘNG KHÔNG ĐƯỢC PHÉP');
        }

        $book = $loan->book;
        $integrityMessage = 'Trả sách thành công.';
        $integrityStatus = 'passed'; // Mặc định là vẹn toàn

        // 2. Xử lý logic trả sách dựa trên loại
        if ($book->type === 'physical') {
            $book->increment('quantity');
        } 
        elseif ($book->type === 'online') {
            // Chỉ xác thực nếu có chữ ký số tồn tại
            if ($loan->digital_signature) {
                try {
                    // a. Lấy nội dung hiện tại (có thể đã bị sửa)
                    $currentContent = $book->content;

                    // b. Giải mã chữ ký gốc
                    $originalSignature = base64_decode($loan->digital_signature);

                    // c. Đọc Public Key
                    $publicKeyPath = storage_path('app/keys/public.key');
                    if (!File::exists($publicKeyPath)) {
                        Log::error('Digital Signature Error: Public key not found for verification. Loan ID: ' . $loan->id);
                        return back()->with('error', 'Lỗi hệ thống: Không thể xác thực chữ ký số.');
                    }
                    $publicKey = File::get($publicKeyPath);

                    // d. Xác thực bằng Public Key
                    $isVerified = openssl_verify($currentContent, $originalSignature, $publicKey, OPENSSL_ALGO_SHA256);

                    // 3. Cập nhật trạng thái dựa trên kết quả xác thực
                    if ($isVerified === 1) { // 1 = Thành công
                        $integrityStatus = 'passed';
                        $integrityMessage = 'Xác thực thành công! Tài liệu được trả lại vẫn còn VẸN TOÀN.';
                    } elseif ($isVerified === 0) { // 0 = Thất bại
                        $integrityStatus = 'failed';
                        $integrityMessage = 'CẢNH BÁO! Nội dung của tài liệu đã bị THAY ĐỔI so với bản gốc. Tính toàn vẹn không được đảm bảo.';
                    } else { // -1 = Lỗi
                        throw new \Exception('OpenSSL verification returned an error.');
                    }
                } catch (\Exception $e) {
                    Log::error('Digital Signature Failed during verification: ' . $e->getMessage(), ['loan_id' => $loan->id]);
                    return back()->with('error', 'Lỗi hệ thống: Quá trình xác thực tài liệu thất bại.');
                }
            } else {
                // Trường hợp tài liệu online nhưng không có chữ ký (dữ liệu cũ)
                $integrityMessage = 'Đã ghi nhận trả tài liệu. Không có chữ ký số để xác thực.';
            }
        }

        // 4. Cập nhật bản ghi Loan vào CSDL
        $loan->update([
            'status' => 'returned',
            'return_date' => now(),
            'integrity_status' => $integrityStatus,
        ]);
        
        // 5. Phát sự kiện để cộng điểm/trao huy hiệu
        BookReturned::dispatch($loan);

        // 6. Chuyển hướng người dùng với thông báo
        return redirect()->route('books.show', $book)->with('success', $integrityMessage);
    }
}