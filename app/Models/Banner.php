<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'button_text',
        'image',
        'type',
        'is_active'
    ];

    protected $casts = [
        'image' => 'array', // ✅ THIS LINE FIXES EVERYTHING
    ];
}