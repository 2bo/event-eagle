<?php

namespace App\Providers;

use App\Domain\Models\Event\ConnpassEventRepositoryInterface;
use App\Domain\Models\Event\EventRepositoryInterface;
use App\Domain\Models\Prefecture\PrefectureRepositoryInterface;
use App\Repositories\API\ConnpassEventApiRepository;
use App\Repositories\EventRepository;
use App\Repositories\PrefectureRepository;
use App\UseCases\FetchConnpassEvents\FetchConnpassEventsUseCase;
use App\UseCases\FetchConnpassEvents\FetchConnpassEventsUseCaseInterface;
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
        $this->app->bind(PrefectureRepositoryInterface::class, PrefectureRepository::class);
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        $this->app->bind(ConnpassEventRepositoryInterface::class, ConnpassEventApiRepository::class);

        $this->app->bind(FetchAtndEventUseCaseInterface::class, FetchAtndEventUseCase::class);
        $this->app->bind(FetchConnpassEventsUseCaseInterface::class, FetchConnpassEventsUseCase::class);
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
