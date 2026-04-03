<?php
namespace App\Repositories;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;

class DashboardRepository
{
    public function getCounts()
    {
        return [
            'categories' => Category::count(),
            'menus' => Menu::count(),
            'orders' => Order::count(),
        ];
    }

    public function getSliderCount()
    {
        return class_exists(\App\Models\Slider::class)
            ? \App\Models\Slider::count()
            : 0;
    }

    public function getRevenue()
    {
        return Order::sum('total');
    }

    public function getOrderStats()
    {
        return [
            'pending' => Order::where('status', 'pending')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
        ];
    }
}