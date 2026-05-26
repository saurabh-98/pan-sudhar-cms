<?php

namespace App\Repositories;

use App\Models\Banner;
use App\Models\Feature;
use App\Models\News;

// OPTIONAL (only if you use them)
use App\Models\Category;
use App\Models\Menu;
use App\Models\Offer;
use App\Models\Campaign;

// ERP MODELS
use App\Models\Gallery;
use App\Models\Notice;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;

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
       NEWS / BLOG
    ========================= */
    public function getNews()
    {
        return News::where('is_active', 1)
            ->latest()
            ->take(6)
            ->get();
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
        return 0;
    }

    
}