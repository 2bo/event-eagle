<?php

namespace Tests\Unit\Services;

use App\Domain\Services\SearchConditionService;
use App\Repositories\EventTypeRepository;
use App\Repositories\PrefectureRepository;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SearchConditionServiceTest extends TestCase
{

    private $service;
    private static $isDbInitialized = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(SearchConditionService::class);
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

    public function testGetEventTypeConditions()
    {
        $eventTypeConditions = $this->service->getEventTypeConditions();
        $repository = new EventTypeRepository();
        $allEvents = $repository->all();

        self::assertEquals(count($allEvents), count($eventTypeConditions));
        foreach ($eventTypeConditions as $condition) {
            self::assertArrayHasKey('value', $condition);
            self::assertArrayHasKey('text', $condition);
        }
    }

    public function testGetPlaceCondition()
    {
        $placeConditions = $this->service->getPlaceConditions();
        $repository = new PrefectureRepository();
        $allPrefectures = $repository->findAll();
        //都道府県+オンライン
        self::assertEquals(count($allPrefectures) + 1, count($placeConditions));
        foreach ($placeConditions as $condition) {
            self::assertArrayHasKey('value', $condition);
            self::assertArrayHasKey('text', $condition);
        }
    }
}
