<?php

namespace App\Models;

// THÊM CÁC DÒNG USE CẦN THIẾT
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Book;

class Loan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * (Các thuộc tính được phép điền vào hàng loạt)
     *
     * @var array<int, string>
     */
    // THÊM THUỘC TÍNH NÀY (BẮT BUỘC)
    protected $fillable = [
        'user_id',
        'book_id',
        'loan_date',
        'due_date',
        'return_date',
        'status',
        'content_hash_on_loan',
        'integrity_status',
        'digital_signature',
    ];

    /**
     * The attributes that should be cast.
     * (Các thuộc tính nên được chuyển đổi kiểu dữ liệu)
     *
     * @var array<string, string>
     */
    // THÊM THUỘC TÍNH NÀY (Rất khuyến khích)
    protected $casts = [
        'loan_date' => 'datetime',
        'due_date' => 'date',
        'return_date' => 'datetime',
    ];

    /**
     * Lấy thông tin người dùng đã mượn.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lấy thông tin sách đã được mượn.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
