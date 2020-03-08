<?php

namespace App\Providers;

use App\Repositories\API\ConnpassEventRepository;
use App\Repositories\EventRepository;
use App\Services\PrefectureService;
use App\UseCases\FetchConnpassEventsUseCase;
use App\UseCases\FetchConnpassEventsUseCaseInterface;
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
        $this->app->bind(FetchConnpassEventsUseCaseInterface::class, function () {
            $connpassEventRepository = new ConnpassEventRepository();
            $prefectureService = new PrefectureService();
            $eventRepository = new EventRepository();
            return new FetchConnpassEventsUseCase($connpassEventRepository,$prefectureService,$eventRepository);
        });
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
