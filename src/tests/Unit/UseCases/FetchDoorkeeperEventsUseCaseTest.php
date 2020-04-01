<?php

namespace Tests\Unit\UseCases;

use App\Domain\Models\Event\DoorkeeperEvent;
use App\Domain\Models\Event\DoorkeeperEventRepositoryInterface;
use App\Repositories\API\DoorkeeperEventApiRepository;
use App\Repositories\EventRepository;
use App\Repositories\EventTypeRepository;
use App\UseCases\FetchDoorkeeperEvents\FetchDoorkeeperEventsInputData;
use App\UseCases\FetchDoorkeeperEvents\FetchDoorkeeperEventsUseCase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Mockery;

class FetchDoorkeeperEventsUseCaseTest extends TestCase
{
    static private $isDbInitialized = false;

    public function setUp(): void
    {
        parent::setUp();
        if (!self::$isDbInitialized) {
            self::initializeDb();
        }
    }

    private static function initializeDb()
    {
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
        self::$isDbInitialized = true;
    }

    public function testHandle()
    {
        // Mockに差し替え
        $mockEvents = $this->getDoorkeeperApiRepositoryMockReturnData();
        $doorkeeperApiRepoMock = Mockery::mock(DoorkeeperEventApiRepository::class);
        $doorkeeperApiRepoMock->shouldReceive('fetchEvents')->andReturn($mockEvents);
        $this->app->singleton(DoorkeeperEventRepositoryInterface::class, function () use ($doorkeeperApiRepoMock) {
            return $doorkeeperApiRepoMock;
        });

        $input = new FetchDoorkeeperEventsInputData(new \DateTime('2020-03-01'), new \DateTime('2020-04-01'));
        $useCase = app(FetchDoorkeeperEventsUseCase::class);
        $output = $useCase->handle($input);

        $eventsRepository = new EventRepository();
        $events = $eventsRepository->findAll();
        self::assertEquals(count($mockEvents), $output->getNumOfEvents());
        self::assertEquals(count($mockEvents), count($events));

        self::assertTrue($events[0]->isOnline());
        self::assertNotNull($events[0]->getPrefectureId());
        self::assertEquals(8, count($events[0]->getTypes()));
    }

    private function getDoorkeeperApiRepositoryMockReturnData(): array
    {
        $events = [];
        $typeRepository = new EventTypeRepository();
        $types = $typeRepository->all();

        for ($i = 0; $i < 3; $i++) {
            $events[] = new DoorkeeperEvent(
                null,
                $i + 1,
                'titleオンライン' . $i,
                new \DateTime('2020-01-01 10:00:00'),
                new \DateTime('2020-01-01 12:00:00'),
                'venue_name' . $i,
                '東京都' . $i,
                0.1,
                0.2,
                10,
                new \DateTime(),
                new \DateTime(),
                $i,
                'description' . $i,
                'public_url' . $i,
                5,
                5,
                null,
                $types
            );
        }
        return $events;
    }
}
