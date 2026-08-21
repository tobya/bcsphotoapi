<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\PhotoController;
use App\Http\Responses\PhotoApiResponseV5;
use App\Http\Controllers\PhotoControllerv2;
use Illuminate\Contracts\Routing\ResponseFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // i cant get his to work.
    //   $this->app->when(PhotoControllerv2::class)
    //         ->needs(ResponseFactory::class)
    //         ->give(PhotoApiResponseV5::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
