<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

use App\Services\NavigationMenuService;
use App\Services\FooterService;

use App\Models\Setting;
use App\Models\Module;
use App\Models\RetailerModuleAccess;

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
        | FORCE HTTPS ON PRODUCTION
        |--------------------------------------------------------------------------
        */

        if (app()->environment('production')) {

            URL::forceScheme('https');
        }

        /*
        |--------------------------------------------------------------------------
        | QR CODE FIX
        |--------------------------------------------------------------------------
        */

        Config::set(
            'qr-code.image_backend',
            'gd'
        );

        /*
        |--------------------------------------------------------------------------
        | GLOBAL VIEW DATA
        |--------------------------------------------------------------------------
        */

        view()->composer('*', function ($view) use (
            $navService,
            $footerService
        ) {

            /*
            |--------------------------------------------------------------------------
            | WEBSITE MENUS
            |--------------------------------------------------------------------------
            */

            $menus = $navService
                ->getActiveMenus();

            /*
            |--------------------------------------------------------------------------
            | SETTINGS
            |--------------------------------------------------------------------------
            */

            $settings = Setting::pluck(
                'value',
                'key'
            );

            /*
            |--------------------------------------------------------------------------
            | FOOTER
            |--------------------------------------------------------------------------
            */

            $footer = $footerService
                ->getFooterData();

            /*
            |--------------------------------------------------------------------------
            | RETAILER MODULES
            |--------------------------------------------------------------------------
            */

            $retailerMenus = collect();

            if (
                auth()->check()
                &&
                auth()->user()->hasRole('retailer')
            ) {

                $retailerMenus = Module::query()

                    ->whereNull('parent_id')

                    ->where('status', 1)

                    ->where(function ($query) {

                        $query

                            ->whereHas(
                                'retailerAccess',
                                function ($q) {

                                    $q->where(
                                        'retailer_id',
                                        auth()->id()
                                    );
                                }
                            )

                            ->orWhereHas(
                                'children.retailerAccess',
                                function ($q) {

                                    $q->where(
                                        'retailer_id',
                                        auth()->id()
                                    );
                                }
                            );
                    })

                    ->with([

                        'children' => function ($query) {

                            $query

                                ->where(
                                    'status',
                                    1
                                )

                                ->whereHas(
                                    'retailerAccess',
                                    function ($q) {

                                        $q->where(
                                            'retailer_id',
                                            auth()->id()
                                        );
                                    }
                                )

                                ->orderBy(
                                    'sort_order'
                                )

                                ->orderBy(
                                    'name'
                                );
                        }

                    ])

                    ->orderBy(
                        'sort_order'
                    )

                    ->orderBy(
                        'name'
                    )

                    ->get();
            }

            /*
            |--------------------------------------------------------------------------
            | SHARE DATA
            |--------------------------------------------------------------------------
            */

            $view->with([

                /*
                |--------------------------------------------------------------------------
                | WEBSITE
                |--------------------------------------------------------------------------
                */

                'navMenus' => $menus,

                'settings' => $settings,

                /*
                |--------------------------------------------------------------------------
                | FOOTER
                |--------------------------------------------------------------------------
                */

                'links' => $footer['links'],

                'socials' => $footer['socials'],

                /*
                |--------------------------------------------------------------------------
                | RETAILER SIDEBAR
                |--------------------------------------------------------------------------
                */

                'retailerMenus' => $retailerMenus,

            ]);
        });
    }
}
