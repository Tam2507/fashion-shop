<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    protected $table = 'contact_info';

    protected $fillable = [
        'address',
        'city',
        'country',
        'hotline',
        'phone',
        'working_hours',
        'email',
        'support_email',
        'weekday_hours',
        'weekend_hours',
        'holiday_note',
        'map_embed_url',
    ];
}
