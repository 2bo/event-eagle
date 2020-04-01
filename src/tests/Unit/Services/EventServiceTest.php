<?php

namespace Tests\Unit\Services;

use App\Domain\Models\Event\EventService;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class EventServiceTest extends TestCase
{
    private static $isDbInitialized = false;

    protected function setUp(): void
    {
        parent::setUp();
        if (!self::$isDbInitialized) {
            self::initializeDB();
        }
    }

    private static function initializeDb()
    {
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed --class=EventTypeSeeder');
        self::$isDbInitialized = true;
    }


    public function testGetEventTypesFrom()
    {
        $service = app(EventService::class);
        $eventTypes = $service->getEventTypesFrom('ライトニングトーク', 'Conference', '輪読会');
        self::assertEquals(3, count($eventTypes));
    }
}
