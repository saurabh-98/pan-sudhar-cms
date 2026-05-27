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
*/

Route::get(

    '/temp-file/{path}',

    function ($path) {

        $path = ltrim($path, '/');

        $fullPath =

            sys_get_temp_dir()

            . DIRECTORY_SEPARATOR

            . $path;

        if (!File::exists($fullPath)) {

            abort(404);
        }

        return Response::file($fullPath);

    }

)->where('path', '.*');