<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(

    basePath: dirname(__DIR__)

)

->withRouting(


    commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',

    health: '/up',

    then: function () {

        /*
        |--------------------------------------------------------------------------
        | FRONTEND ROUTES
        |--------------------------------------------------------------------------
        */

        Route::middleware('web')

            ->group(
                base_path('routes/frontend.php')
            );

        /*
        |--------------------------------------------------------------------------
        | AUTH ROUTES
        |--------------------------------------------------------------------------
        */

        Route::middleware('web')

            ->group(
                base_path('routes/auth.php')
            );

       
        /*
        |--------------------------------------------------------------------------
        | ADMIN ROUTES
        |--------------------------------------------------------------------------
        */

        Route::middleware('web')

            ->prefix('admin')

            ->name('admin.')

            ->group(
                base_path('routes/admin.php')
            );

        /*
        |--------------------------------------------------------------------------
        | RETAILER ROUTES
        |--------------------------------------------------------------------------
        */

        Route::middleware('web')

            ->prefix('retailer')

            ->name('retailer.')

            ->group(
                base_path('routes/retailer.php')
            );

       

       

        

      

    }

)

->withMiddleware(function (Middleware $middleware): void {

    $middleware->redirectGuestsTo(function ($request) {

        if ($request->is('retailer/*')) {
            return route('retailer.login');
        }

        if ($request->is('executive/*')) {
            return route('executive.login');
        }

        if ($request->is('admin/*')) {
            return route('login');
        }

        return route('login');
    });

    $middleware->alias([

        'admin' =>
            \App\Http\Middleware\AdminMiddleware::class,

        'role' =>
            \Spatie\Permission\Middleware\RoleMiddleware::class,

        'permission' =>
            \Spatie\Permission\Middleware\PermissionMiddleware::class,

        'role_or_permission' =>
            \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,

    ]);

    $middleware->group('api', [

        \Illuminate\Routing\Middleware\SubstituteBindings::class,

    ]);

})

->withExceptions(function (Exceptions $exceptions): void {

    //

})

->create();