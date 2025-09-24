<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadingGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'name',
        'description',
        'cover_image_path',
        'category',
        'max_members',
        'is_public',
        'is_active',
        'rules',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_active' => 'boolean',
        'rules' => 'array',
    ];

    /**
     * Get the creator of this reading group.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get all members of this reading group.
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'reading_group_members')
                    ->withPivot(['role', 'joined_at'])
                    ->withTimestamps();
    }

    /**
     * Get admins of this reading group.
     */
    public function admins()
    {
        return $this->members()->wherePivot('role', 'admin');
    }

    /**
     * Get moderators of this reading group.
     */
    public function moderators()
    {
        return $this->members()->wherePivot('role', 'moderator');
    }

    /**
     * Get regular members of this reading group.
     */
    public function regularMembers()
    {
        return $this->members()->wherePivot('role', 'member');
    }

    /**
     * Check if user is a member of this group.
     */
    public function hasMember($userId)
    {
        return $this->members()->where('user_id', $userId)->exists();
    }

    /**
     * Check if user is admin of this group.
     */
    public function isAdmin($userId)
    {
        return $this->members()->where('user_id', $userId)->wherePivot('role', 'admin')->exists();
    }

    /**
     * Check if user is moderator of this group.
     */
    public function isModerator($userId)
    {
        return $this->members()->where('user_id', $userId)->wherePivot('role', 'moderator')->exists();
    }

    /**
     * Check if user can moderate this group.
     */
    public function canModerate($userId)
    {
        return $this->isAdmin($userId) || $this->isModerator($userId);
    }

    /**
     * Get member count.
     */
    public function getMemberCountAttribute()
    {
        return $this->members()->count();
    }

    /**
     * Check if group is full.
     */
    public function isFull()
    {
        return $this->member_count >= $this->max_members;
    }

    /**
     * Scope for public groups.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for active groups.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for category.
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get discussions for this reading group.
     */
    public function discussions()
    {
        return $this->hasMany(Discussion::class, 'reading_group_id');
    }

    /**
     * Get formatted rules.
     */
    public function getFormattedRulesAttribute()
    {
        return $this->rules ? implode("\n", $this->rules) : '';
    }
}