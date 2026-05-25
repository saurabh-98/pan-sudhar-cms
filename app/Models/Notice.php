<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = [
        'title',
        'description',
        'publish_date',
        'expiry_date'
    ];

    protected $casts = [
        'publish_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where(function($q){
            $q->whereNull('expiry_date')
              ->orWhere('expiry_date', '>=', now());
        });
    }

    public function isActive()
    {
        return is_null($this->expiry_date) || $this->expiry_date >= now();
    }
}