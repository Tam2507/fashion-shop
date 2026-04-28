<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewReply extends Model
{
    protected $fillable = ['review_id', 'user_id', 'guest_name', 'comment'];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAuthorNameAttribute(): string
    {
        return $this->user?->name ?? $this->guest_name ?? 'Ẩn danh';
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->user?->is_admin ?? false;
    }
}
