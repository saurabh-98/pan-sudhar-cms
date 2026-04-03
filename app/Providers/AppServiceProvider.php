<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\NavigationMenuService;
use App\Services\FooterService; // ✅ ADD THIS
use App\Models\Setting;
use App\Models\PopupOffer;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(
        NavigationMenuService $navService,
        FooterService $footerService // ✅ ADD THIS
    )
    {
        view()->composer('*', function ($view) use ($navService, $footerService) {

            // 🔹 Navigation Menus
            $menus = $navService->getActiveMenus();

            // 🔹 Settings
            $settings = Setting::pluck('value', 'key');

            // 🔥 POPUP
            $popup = PopupOffer::where('is_active', 1)
                ->where(fn($q) => $q->whereNull('start_at')->orWhere('start_at','<=',now()))
                ->where(fn($q) => $q->whereNull('end_at')->orWhere('end_at','>=',now()))
                ->latest()
                ->first();

            // 🔥 FOOTER DATA
            $footer = $footerService->getFooterData();

            // 🔥 SHARE ALL DATA
            $view->with([
                'navMenus' => $menus,
                'settings' => $settings,
                'popup' => $popup,

                // ✅ FOOTER FIX
                'links' => $footer['links'],
                'socials' => $footer['socials'],
            ]);

        });
    }
}