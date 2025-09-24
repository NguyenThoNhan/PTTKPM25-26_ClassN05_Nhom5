<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
     protected $fillable = ['title', 'description', 'location', 'start_time', 'end_time', 'max_attendees', 'user_id'];
    protected $casts = ['start_time' => 'datetime', 'end_time' => 'datetime'];

    // Sự kiện được tạo bởi 1 admin
    public function creator() { return $this->belongsTo(User::class, 'user_id'); }

    // Sự kiện có nhiều người đăng ký
    public function attendees() { return $this->belongsToMany(User::class, 'event_registrations'); }

    // Lấy tất cả các bản ghi đăng ký
    public function registrations() { return $this->hasMany(EventRegistration::class); }
}
