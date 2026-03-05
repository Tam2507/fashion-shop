<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FooterSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_description',
        'address',
        'phone',
        'email',
        'hotline',
        'business_license',
        'social_facebook',
        'social_instagram',
        'social_youtube',
        'social_tiktok',
        'payment_methods',
        'working_hours',
        'copyright_text'
    ];

    protected $casts = [
        'payment_methods' => 'array',
    ];

    public static function getSettings()
    {
        return self::first() ?? new self();
    }
}