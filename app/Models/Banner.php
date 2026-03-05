<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle', 
        'description',
        'image',
        'link_url',
        'link_text',
        'position',
        'is_active',
        'background_color',
        'text_color',
        'banner_type',
        'page'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }

    public function scopeForPage($query, $page)
    {
        return $query->where(function($q) use ($page) {
            $q->where('page', $page)->orWhere('page', 'all');
        });
    }
}