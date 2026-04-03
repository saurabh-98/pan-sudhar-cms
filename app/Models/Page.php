<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
        'show_in_navbar',
        'position'
    ];

    /* =========================
       AUTO SLUG GENERATION
    ========================= */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });

        static::updating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    /* =========================
       STATUS ACCESSOR
    ========================= */
    public function getStatusLabelAttribute()
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    /* =========================
       SCOPE (ONLY ACTIVE)
    ========================= */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}