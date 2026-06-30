<?php

namespace App\Repositories;

use App\Models\Banner;
use App\Models\Feature;
use App\Models\News;


// ERP MODELS
use App\Models\Gallery;
use App\Models\Notice;

class HomeRepository
{
    /* =========================
       HERO BANNERS (SLIDER)
    ========================= */
    public function getHeroes()
    {
        return Banner::where('type', 'hero')
            ->where('is_active', 1)
            ->latest()
            ->get();
    }

    /* =========================
       FEATURES
    ========================= */
    public function getFeatures()
    {
        return 0;
    }

    
    /* =========================
       ERP ADDITIONS
    ========================= */

    public function getGallery()
    {
        return Gallery::latest()->take(8)->get();
    }

    public function getNotices()
    {
        return Notice::latest()->take(5)->get();
    }

    
}