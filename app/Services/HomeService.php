<?php

namespace App\Services;

use App\Repositories\HomeRepository;
use Illuminate\Support\Facades\Cache;

class HomeService
{
    protected $homeRepository;

    public function __construct(HomeRepository $homeRepository)
    {
        $this->homeRepository = $homeRepository;
    }

    public function getHomeData()
    {
        return Cache::remember('home_page_data', now()->addMinutes(10), function () {

            return [
                'heroes'       => $this->homeRepository->getHeroes(),
                'campaigns'   => $this->homeRepository->getCampaigns(),
                'categories' => $this->homeRepository->getCategories(),
                'menus'      => $this->homeRepository->getMenus(),
                'features'   => $this->homeRepository->getFeatures(),
                'news'       => $this->homeRepository->getNews(),
                'offers'     => $this->homeRepository->getOffers(),
                'chef'       => $this->homeRepository->getChef(),
                'delivery'   => $this->homeRepository->getDelivery(),
            ];

        });
    }
}