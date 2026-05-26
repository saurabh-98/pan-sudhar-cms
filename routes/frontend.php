<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\NewsController;

/*
|--------------------------------------------------------------------------
| FRONTEND ROUTES
|--------------------------------------------------------------------------

*/


Route::get('/', function () {
    return 'Laravel Working on Vercel';
});

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

