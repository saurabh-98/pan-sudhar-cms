<?php

namespace App\Repositories;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Offer;
use App\Models\Campaign;
use App\Models\Feature;
use App\Models\News;

class HomeRepository
{
    public function getHeroes()
    {
        return Banner::where('type', 'hero')
            ->where('is_active', 1)
            ->latest()
            ->get(); 
    }
    public function getCampaigns()
    {
        return Campaign::where('is_active', 1)->latest()->get();
    }

    public function getCategories()
    {
        return Category::latest()->get();
    }

    public function getMenus()
    {
        return Menu::latest()->take(8)->get();
    }

    public function getFeatures()
    {
        return Feature::where('is_active', 1)->get();
    }

    public function getNews()
    {
        return News::where('is_active', 1)
            ->latest()
            ->take(6)
            ->get();
    }

    public function getOffers()
    {
        return Offer::where('is_active', 1)->get();
    }

    /* OPTIONAL (SAFE FALLBACK) */

    public function getChef()
    {
        return Menu::latest()->first();
    }

    public function getDelivery()
    {
        return null; // or create table later
    }
}