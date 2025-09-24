<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
protected $fillable = [
    'title',
    'author',
    'cover_image_path',
    'description',
    'type',
    'content',
    'quantity',
    'user_id',
];
}
