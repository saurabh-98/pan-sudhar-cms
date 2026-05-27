<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

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
        /*
        |--------------------------------------------------------------------------
        | FORCE HTTPS ON VERCEL / PRODUCTION
        |--------------------------------------------------------------------------
        */

        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        /*
        |--------------------------------------------------------------------------
        | FIX QR CODE (NO IMAGICK ERROR)
        |--------------------------------------------------------------------------
        */

        Config::set('qr-code.image_backend', 'gd');

        /*
        |--------------------------------------------------------------------------
        | SHARE GLOBAL VIEW DATA
        |--------------------------------------------------------------------------
        */

        view()->composer('*', function ($view) use ($navService, $footerService) {

            // Navigation Menus
            $menus = $navService->getActiveMenus();

            // Settings
            $settings = Setting::pluck('value', 'key');

            // Footer Data
            $footer = $footerService->getFooterData();

            // Share Data
            $view->with([
                'navMenus' => $menus,
                'settings' => $settings,

                // Footer
                'links'   => $footer['links'],
                'socials' => $footer['socials'],
            ]);
        });
    }
}