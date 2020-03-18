<?php

namespace Tests\Unit\Repositories;

use App\Domain\Models\Event\DoorkeeperEvent;
use App\Repositories\API\DoorkeeperEventApiRepository;
use Tests\TestCase;

class DoorkeeperEventApiRepositoryTest extends TestCase
{

    public function testFetchEvents()
    {
        $repository = new DoorkeeperEventApiRepository();
        $since = new \DateTime('2020-03-01 00:00:00');
        $until = new \DateTime('2020-03-31 23:59:59');
        $events = $repository->fetchEvents($since, $until);
        $firstEvents = $events['0'];

        self::assertEquals(DoorkeeperEvent::class, get_class($firstEvents));
        self::assertIsInt($firstEvents->getEventId());
        self::assertNotNull($firstEvents->getTitle());
        self::assertEquals(DoorkeeperEvent::SITE_NAME_DOORKEEPER, $firstEvents->getSiteName());
        self::assertStringContainsString('doorkeeper', $firstEvents->getEventUrl());
        self::assertGreaterThan($since->getTimestamp(), $firstEvents->getStartedAt()->getTimestamp());
        self::assertLessThan($until->getTimestamp(), $firstEvents->getEndedAt()->getTimestamp());
    }
}
