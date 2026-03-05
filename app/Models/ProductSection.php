<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductSection extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'display_order',
        'is_active',
        'max_products',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_section_items', 'section_id', 'product_id')
            ->withPivot('display_order')
            ->withTimestamps()
            ->orderBy('product_section_items.display_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}
