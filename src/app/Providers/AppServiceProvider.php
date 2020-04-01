<?php

namespace App\Providers;

use App\Domain\Models\Event\ConnpassEventRepositoryInterface;
use App\Domain\Models\Event\DoorkeeperEventRepositoryInterface;
use App\Domain\Models\Event\EventRepositoryInterface;
use App\Domain\Models\Event\EventTypeRepositoryInterface;
use App\Domain\Models\Prefecture\PrefectureRepositoryInterface;
use App\Repositories\API\ConnpassEventApiRepository;
use App\Repositories\API\DoorkeeperEventApiRepository;
use App\Repositories\EventRepository;
use App\Repositories\EventTypeRepository;
use App\Repositories\PrefectureRepository;
use App\UseCases\FetchConnpassEvents\FetchConnpassEventsUseCase;
use App\UseCases\FetchConnpassEvents\FetchConnpassEventsUseCaseInterface;
use App\UseCases\FetchDoorkeeperEvents\FetchDoorkeeperEventsUseCase;
use App\UseCases\FetchDoorkeeperEvents\FetchDoorkeeperEventsUseCaseInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(EventRepositoryInterface::class, EventRepository::class);
        $this->app->singleton(PrefectureRepositoryInterface::class, PrefectureRepository::class);
        $this->app->singleton(EventTypeRepositoryInterface::class, EventTypeRepository::class);

        $this->app->singleton(ConnpassEventRepositoryInterface::class, ConnpassEventApiRepository::class);
        $this->app->singleton(DoorkeeperEventRepositoryInterface::class, DoorkeeperEventApiRepository::class);

        $this->app->bind(FetchConnpassEventsUseCaseInterface::class, FetchConnpassEventsUseCase::class);
        $this->app->bind(FetchDoorkeeperEventsUseCaseInterface::class, FetchDoorkeeperEventsUseCase::class);
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
