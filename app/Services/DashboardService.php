<?php
namespace App\Services;

use App\Repositories\DashboardRepository;

class DashboardService
{
    protected $repo;

    public function __construct(DashboardRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getDashboardData()
    {
        $counts = $this->repo->getCounts();
        $stats = $this->repo->getOrderStats();

        return [
            'categories' => $counts['categories'],
            'menus' => $counts['menus'],
            'orders' => $counts['orders'],
            'sliders' => $this->repo->getSliderCount(),
            'totalRevenue' => $this->repo->getRevenue(),
            'pending' => $stats['pending'],
            'delivered' => $stats['delivered'],
        ];
    }
}