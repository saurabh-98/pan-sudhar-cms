<?php

namespace App\Services;

use App\Repositories\HomeRepository;

class HomeService
{
    protected $homeRepository;

    public function __construct(HomeRepository $homeRepository)
    {
        $this->homeRepository = $homeRepository;
    }

    public function getHomePageData()
    {
        return [
            // 🎯 HERO SLIDER
            'heroes'   => $this->homeRepository->getHeroes(),

            // ⭐ FEATURES
            'features' => $this->homeRepository->getFeatures(),

            // 🖼️ GALLERY
            'gallery'  => $this->homeRepository->getGallery(),

          

            // 📰 NEWS (optional)
            'news'     => $this->homeRepository->getNews(),

           
        ];
    }
}