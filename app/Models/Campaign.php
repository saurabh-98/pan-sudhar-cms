<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'tag',
        'title',
        'description',
        'price',
        'image',
        'is_active'
    ];
}