<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Loan;
use App\Models\User;

class Book extends Model
{
    use HasFactory;
    // Một cuốn sách có thể được mượn nhiều lần
public function loans()
{
    return $this->hasMany(Loan::class);
}

// Một cuốn sách được thêm bởi một người dùng
public function user()
{
    return $this->belongsTo(User::class);
}
public function categories()
{
    return $this->belongsToMany(Category::class, 'book_category');
}
public function favoritedByUsers()
{
    return $this->belongsToMany(User::class, 'favorites');
}

public function reviews()
{
    return $this->hasMany(Review::class);
}

public function verifiedReviews()
{
    return $this->hasMany(Review::class)->where('is_verified_purchase', true);
}

public function averageRating()
{
    return $this->reviews()->avg('rating') ?? 0;
}

public function totalReviews()
{
    return $this->reviews()->count();
}

public function ratingDistribution()
{
    return $this->reviews()
        ->selectRaw('rating, COUNT(*) as count')
        ->groupBy('rating')
        ->orderBy('rating', 'desc')
        ->get()
        ->pluck('count', 'rating')
        ->toArray();
}

/**
 * Generate QR code for this book
 */
public function generateQRCode()
{
    $qrService = new \App\Services\QRCodeService();
    $qrCode = $qrService->generateBookQRCode($this->id, $this->title);
    $qrPath = $qrService->saveQRCode($this->id, $qrCode);
    
    $this->update([
        'qr_code' => $qrCode,
        'qr_code_path' => $qrPath,
    ]);
    
    return $qrCode;
}

/**
 * Get QR code URL for scanning
 */
public function getQRCodeURL()
{
    $qrService = new \App\Services\QRCodeService();
    return $qrService->generateQRCodeURL($this->id);
}

/**
 * Get full URL for cover image with graceful fallback when file is missing.
 */
public function getCoverUrlAttribute()
{
    $placeholder = 'https://via.placeholder.com/400x600.png/003366?text=BookHaven';
    if (empty($this->cover_image_path)) {
        return $placeholder;
    }

    $raw = trim((string) $this->cover_image_path);
    // Trường hợp đã lưu URL tuyệt đối
    if (stripos($raw, 'http://') === 0 || stripos($raw, 'https://') === 0) {
        return $raw;
    }

    $relativePath = ltrim(str_replace(['\\', '..'], ['/', ''], $raw), '/');
    // Một số bản ghi cũ có thể chứa 'storage/' hoặc 'public/' ở đầu
    if (strpos($relativePath, 'storage/') === 0) {
        $relativePath = substr($relativePath, strlen('storage/'));
    }
    if (strpos($relativePath, 'public/') === 0) {
        $relativePath = substr($relativePath, strlen('public/'));
    }

    if (Storage::disk('public')->exists($relativePath)) {
        return asset('storage/' . $relativePath);
    }

    return $placeholder;
}
    protected $fillable = [
        'title',
        'author',
        'cover_image_path',
        'description',
        'type',
        'content',
        'quantity',
        'user_id',
        'qr_code',
        'qr_code_path',
    ];
}
