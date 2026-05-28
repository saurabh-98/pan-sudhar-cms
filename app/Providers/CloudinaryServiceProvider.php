<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider as BaseProvider;

class CloudinaryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(
            BaseProvider::class
        );
    }

    public function boot(): void
    {
        //
    }
}

