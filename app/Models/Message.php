<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_email',
        'message',
        'is_admin_reply',
        'replied_by',
        'is_read',
    ];

    protected $casts = [
        'is_admin_reply' => 'boolean',
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function repliedBy()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeUserMessages($query)
    {
        return $query->where('is_admin_reply', false);
    }

    public function scopeAdminReplies($query)
    {
        return $query->where('is_admin_reply', true);
    }
}
