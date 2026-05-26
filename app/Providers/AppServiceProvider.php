<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config; // ✅ ADD THIS
use App\Services\NavigationMenuService;
use App\Services\FooterService;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(
        NavigationMenuService $navService,
        FooterService $footerService
    )
    {
        /* ================= 🔥 FIX QR CODE (NO IMAGICK ERROR) ================= */
        Config::set('qr-code.image_backend', 'gd');

        view()->composer('*', function ($view) use ($navService, $footerService) {

            // 🔹 Navigation Menus
            $menus = $navService->getActiveMenus();

            // 🔹 Settings
            $settings = Setting::pluck('value', 'key');

           

            // 🔹 Footer Data
            $footer = $footerService->getFooterData();

            // 🔥 SHARE ALL DATA
            $view->with([
                'navMenus' => $menus,
                'settings' => $settings,

                // ✅ Footer
                'links'   => $footer['links'],
                'socials' => $footer['socials'],
            ]);
        });
    }
}