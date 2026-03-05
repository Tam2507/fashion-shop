<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
    protected $table = 'about_page';

    protected $fillable = [
        'title',
        'intro',
        'vision',
        'mission',
        'core_values',
        'image_1',
        'image_2',
        'image_3',
    ];
}
