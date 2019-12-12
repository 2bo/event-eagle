<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\UseCases\FetchAtndEventUseCaseInterface;
use App\UseCases\FetchAtndEventUseCase;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FetchAtndEventUseCaseInterface::class, FetchAtndEventUseCase::class);
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
