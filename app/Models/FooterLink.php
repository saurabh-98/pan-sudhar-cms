<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterLink extends Model
{
    protected $fillable = [
        'section',
        'name',
        'url',
        'sort_order'
    ];
}