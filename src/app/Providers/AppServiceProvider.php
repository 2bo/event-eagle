<?php

namespace App\Providers;

use App\Domain\Models\Event\ConnpassEventRepositoryInterface;
use App\Domain\Models\Event\DoorkeeperEventRepositoryInterface;
use App\Domain\Models\Event\EventRepositoryInterface;
use App\Domain\Models\Event\EventTypeRepositoryInterface;
use App\Domain\Models\Prefecture\PrefectureRepositoryInterface;
use App\QueryServices\EventQueryService;
use App\QueryServices\EventQueryServiceInterface;
use App\Repositories\API\ConnpassEventApiRepository;
use App\Repositories\API\DoorkeeperEventApiRepository;
use App\Repositories\EventRepository;
use App\Repositories\EventTypeRepository;
use App\Repositories\PrefectureRepository;
use App\UseCases\FetchConnpassEvents\FetchConnpassEventsUseCase;
use App\UseCases\FetchConnpassEvents\FetchConnpassEventsUseCaseInterface;
use App\UseCases\FetchDoorkeeperEvents\FetchDoorkeeperEventsUseCase;
use App\UseCases\FetchDoorkeeperEvents\FetchDoorkeeperEventsUseCaseInterface;
use App\UseCases\GetEventTypeConditions\GetEventTypeConditionsUseCase;
use App\UseCases\GetEventTypeConditions\GetEventTypeConditionsUseCaseInterface;
use App\UseCases\GetPlaceConditions\GetPlaceConditionsUseCase;
use App\UseCases\GetPlaceConditions\GetPlaceConditionsUseCaseInterface;
use App\UseCases\SearchEvents\SearchEventsUseCase;
use App\UseCases\SearchEvents\SearchEventsUseCaseInterface;
use App\UseCases\ShowEventDetail\ShowEventDetailUseCase;
use App\UseCases\ShowEventDetail\ShowEventDetailUseCaseInterface;
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
        $this->app->singleton(EventQueryServiceInterface::class, EventQueryService::class);

        $this->app->singleton(ConnpassEventRepositoryInterface::class, ConnpassEventApiRepository::class);
        $this->app->singleton(DoorkeeperEventRepositoryInterface::class, DoorkeeperEventApiRepository::class);

        $this->app->bind(FetchConnpassEventsUseCaseInterface::class, FetchConnpassEventsUseCase::class);
        $this->app->bind(FetchDoorkeeperEventsUseCaseInterface::class, FetchDoorkeeperEventsUseCase::class);
        $this->app->bind(GetPlaceConditionsUseCaseInterface::class, GetPlaceConditionsUseCase::class);
        $this->app->bind(GetEventTypeConditionsUseCaseInterface::class, GetEventTypeConditionsUseCase::class);
        $this->app->bind(SearchEventsUseCaseInterface::class, SearchEventsUseCase::class);
        $this->app->bind(ShowEventDetailUseCaseInterface::class, ShowEventDetailUseCase::class);
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
