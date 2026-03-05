<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'path', 'alt', 'position', 'color'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Get full URL for image
    public function getUrlAttribute()
    {
        return Storage::url($this->path);
    }

    // Scope: Filter by color
    public function scopeForColor($query, $color)
    {
        return $query->where('color', $color);
    }

    // Scope: Images without color assignment
    public function scopeUnassigned($query)
    {
        return $query->whereNull('color');
    }
}
