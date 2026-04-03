<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopupOffer extends Model
{
    protected $fillable = [
        'title','image','description','is_active','start_at','end_at'
    ];
}