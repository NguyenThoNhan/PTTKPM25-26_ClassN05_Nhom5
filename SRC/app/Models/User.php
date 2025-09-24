<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Badge;
use App\Models\Event;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', 
        'experience_points',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    // Một người dùng có thể có nhiều lượt mượn
public function loans()
{
    return $this->hasMany(Loan::class);
}

// Một người dùng (admin) có thể thêm nhiều sách
public function books()
{
    return $this->hasMany(Book::class);
}
public function badges()
{
    return $this->belongsToMany(Badge::class, 'badge_user') // Chỉ định rõ tên bảng trung gian
                 ->withTimestamps('unlocked_at', 'unlocked_at'); // Chỉ định tên cột cho cả created_at và updated_at
}

/**
 * Các sự kiện mà người dùng đã đăng ký.
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
 */
public function registeredEvents()
{
    return $this->belongsToMany(Event::class, 'event_registrations');
}
public function favoriteBooks()
{
    return $this->belongsToMany(Book::class, 'favorites');
}

}
