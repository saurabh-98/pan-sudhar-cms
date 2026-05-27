<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use App\Http\Controllers\HomeController;
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
| TEMP FILE VIEW ROUTE
|--------------------------------------------------------------------------
|
| Compatible With:
| - Localhost
| - VPS
| - cPanel
| - Vercel
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
        | FULL PATH
        |--------------------------------------------------------------------------
        */

        $fullPath =

            sys_get_temp_dir()

            . DIRECTORY_SEPARATOR

            . $path;

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
        | RETURN FILE RESPONSE
        |--------------------------------------------------------------------------
        */

        return Response::file(

            $fullPath,

            [

                'Cache-Control' =>

                    'no-store, no-cache, must-revalidate, max-age=0',

                'Pragma' =>
                    'no-cache',

                'Expires' =>
                    'Sat, 01 Jan 2000 00:00:00 GMT'

            ]

        );

    }

)->where('path', '.*');