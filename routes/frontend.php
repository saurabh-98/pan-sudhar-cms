<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ExecutiveLoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\NewsController;

/*
|--------------------------------------------------------------------------
| FRONTEND ROUTES
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [HomeController::class, 'index']
)->name('home');

Route::get(
    '/page/{slug}',
    [HomeController::class, 'page']
)->name('page.show');

Route::get(
    '/gallery/details',
    [HomeController::class, 'gallery']
)->name('gallery.view');

/*
|--------------------------------------------------------------------------
| SECURE FILE VIEW ROUTE
|--------------------------------------------------------------------------
|
| Works On:
| - Localhost
| - VPS
| - cPanel
| - Vercel
|
| Uses:
| public/uploads
|
*/

Route::get(

    '/uploads/{path}',

    function ($path) {

        /*
        |--------------------------------------------------------------------------
        | CLEAN PATH
        |--------------------------------------------------------------------------
        */

        $path = ltrim($path, '/');

        /*
        |--------------------------------------------------------------------------
        | BLOCK DIRECTORY TRAVERSAL
        |--------------------------------------------------------------------------
        */

        if (

            str_contains($path, '..')

            ||

            str_contains($path, './')

            ||

            str_contains($path, '.\\')

        ) {

            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | FULL PATH
        |--------------------------------------------------------------------------
        */

        $fullPath =

            public_path(

                'uploads/' . $path

            );

        /*
        |--------------------------------------------------------------------------
        | FILE EXISTS
        |--------------------------------------------------------------------------
        */

        if (!File::exists($fullPath)) {

            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | MIME TYPE
        |--------------------------------------------------------------------------
        */

        $mime = File::mimeType($fullPath);

        /*
        |--------------------------------------------------------------------------
        | ALLOWED MIME TYPES
        |--------------------------------------------------------------------------
        */

        $allowedMimes = [

            'image/jpeg',

            'image/jpg',

            'image/png',

            'image/webp',

            'application/pdf'

        ];

        if (

            !$mime

            ||

            !in_array(

                $mime,

                $allowedMimes

            )

        ) {

            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | RETURN FILE
        |--------------------------------------------------------------------------
        */

        return Response::file(

            $fullPath,

            [

                'Cache-Control' =>

                    'public, max-age=86400',

                'X-Content-Type-Options' =>

                    'nosniff'

            ]

        );

    }

)->where('path', '.*');

/*
|--------------------------------------------------------------------------
| TEMP FILE VIEW ROUTE
|--------------------------------------------------------------------------
|
| Used For:
| - Preview Session Files
| - Temporary Uploads
| - Vercel Runtime Files
|
*/

Route::get(

    '/temp-file/{path}',

    function ($path) {

        /*
        |--------------------------------------------------------------------------
        | CLEAN PATH
        |--------------------------------------------------------------------------
        */

        $path = ltrim($path, '/');

        /*
        |--------------------------------------------------------------------------
        | BLOCK DIRECTORY TRAVERSAL
        |--------------------------------------------------------------------------
        */

        if (

            str_contains($path, '..')

            ||

            str_contains($path, './')

            ||

            str_contains($path, '.\\')

        ) {

            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | VERCEL
        |--------------------------------------------------------------------------
        */

        if (is_vercel()) {

            $fullPath =

                sys_get_temp_dir()

                . DIRECTORY_SEPARATOR

                . $path;
        }

        /*
        |--------------------------------------------------------------------------
        | LOCALHOST / VPS / CPANEL
        |--------------------------------------------------------------------------
        */

        else {

            $fullPath = storage_path(

                'app/public/' . $path

            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILE EXISTS
        |--------------------------------------------------------------------------
        */

        if (!File::exists($fullPath)) {

            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | MIME TYPE
        |--------------------------------------------------------------------------
        */

        $mime = File::mimeType($fullPath);

        /*
        |--------------------------------------------------------------------------
        | ALLOWED MIME TYPES
        |--------------------------------------------------------------------------
        */

        $allowedMimes = [

            'image/jpeg',

            'image/jpg',

            'image/png',

            'image/webp',

            'application/pdf'

        ];

        if (

            !$mime

            ||

            !in_array(

                $mime,

                $allowedMimes

            )

        ) {

            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | RETURN FILE
        |--------------------------------------------------------------------------
        */

        return Response::file(

            $fullPath,

            [

                'Cache-Control' =>

                    'public, max-age=86400',

                'X-Content-Type-Options' =>

                    'nosniff'

            ]

        );

    }

)->where('path', '.*');


/*
|--------------------------------------------------------------------------
| EXECUTIVE AUTH
|--------------------------------------------------------------------------
*/

Route::controller(
    ExecutiveLoginController::class
)->group(function () {

    Route::get(
        '/executive/login',
        'showLogin'
    )->name('executive.login');

    Route::post(
        '/executive/login',
        'login'
    )->name('executive.login.submit');

    Route::post(
        '/executive/logout',
        'logout'
    )->name('executive.logout');

});