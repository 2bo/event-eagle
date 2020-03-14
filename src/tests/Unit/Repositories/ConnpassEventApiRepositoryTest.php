<?php

namespace Tests\Repositories\Unit;

use App\Domain\Models\Event\ConnpassEvent;
use Tests\TestCase;
use App\Repositories\API\ConnpassEventApiRepository;

class ConnpassEventApiRepositoryTest extends TestCase
{
    private static $connpassEventRepository;

    public static function setUpBeforeClass()
    {
        self::$connpassEventRepository = new ConnpassEventApiRepository();
    }

    public function testFetchEvent()
    {
        $dateTime = new \DateTime('2020-02-01');
        $ym = $dateTime->format('Ym');
        $events = self::$connpassEventRepository->fetchEvents($ym, 1);
        $resultsAvailable = self::$connpassEventRepository->fetchResultsAvailable($ym);
        $firstEvent = $events[0];

        //取得件数が正しいことを確認
        self::assertEquals($resultsAvailable, count($events));
        self::assertIsInt($firstEvent->getEventId());
        self::assertEquals($firstEvent->getSiteName(), ConnpassEvent::SITE_NAME_CONNPASS);
        self::assertEquals($ym, $firstEvent->getStartedAt()->format('Ym'));
    }
}
