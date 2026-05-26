<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Services\NavigationMenuService;
use App\Services\FooterService;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(
        NavigationMenuService $navService,
        FooterService $footerService
    ): void {

        /*
        |--------------------------------------------------------------------------
        | QR CODE BACKEND FIX
        |--------------------------------------------------------------------------
        */

        Config::set('qr-code.image_backend', 'gd');

        /*
        |--------------------------------------------------------------------------
        | GLOBAL VIEW COMPOSER
        |--------------------------------------------------------------------------
        */

        view()->composer('*', function ($view) use ($navService, $footerService) {

            try {

                /*
                |--------------------------------------------------------------------------
                | NAVIGATION MENUS
                |--------------------------------------------------------------------------
                */

                $menus = $navService->getActiveMenus();

                /*
                |--------------------------------------------------------------------------
                | SETTINGS
                |--------------------------------------------------------------------------
                */

                $settings = Setting::pluck('value', 'key');

                /*
                |--------------------------------------------------------------------------
                | FOOTER DATA
                |--------------------------------------------------------------------------
                */

                $footer = $footerService->getFooterData();

            } catch (\Throwable $e) {

                /*
                |--------------------------------------------------------------------------
                | LOG ERROR FOR DEBUGGING
                |--------------------------------------------------------------------------
                */

                Log::error('AppServiceProvider Error: ' . $e->getMessage());

                /*
                |--------------------------------------------------------------------------
                | FALLBACK EMPTY DATA
                |--------------------------------------------------------------------------
                */

                $menus = collect();

                $settings = collect();

                $footer = [
                    'links'   => [],
                    'socials' => [],
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | SHARE DATA GLOBALLY
            |--------------------------------------------------------------------------
            */

            $view->with([

                /*
                |--------------------------------------------------------------------------
                | HEADER
                |--------------------------------------------------------------------------
                */

                'navMenus' => $menus,

                /*
                |--------------------------------------------------------------------------
                | SETTINGS
                |--------------------------------------------------------------------------
                */

                'settings' => $settings,

                /*
                |--------------------------------------------------------------------------
                | FOOTER
                |--------------------------------------------------------------------------
                */

                'links'    => $footer['links'] ?? [],
                'socials'  => $footer['socials'] ?? [],

            ]);
        });
    }
}