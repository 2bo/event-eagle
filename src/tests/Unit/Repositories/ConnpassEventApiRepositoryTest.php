<?php

namespace Tests\Repositories\Unit;

use App\Domain\Models\Event\ConnpassEvent;
use Tests\TestCase;
use App\Repositories\API\ConnpassEventApiRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConnpassEventApiRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testFetchEvent()
    {
        $repository = new ConnpassEventApiRepository();

        $dateTime = new \DateTime('2020-02-01');
        $ym = $dateTime->format('Ym');
        $events = $repository->fetchEvents($ym, 1);
        $resultsAvailable = $repository->fetchResultsAvailable($ym);
        $firstEvent = $events[0];

        //取得件数が正しいことを確認
        self::assertEquals($resultsAvailable, count($events));
        self::assertIsInt($firstEvent->getEventId());
        self::assertEquals($firstEvent->getSiteName(), ConnpassEvent::SITE_NAME_CONNPASS);
        self::assertEquals($ym, $firstEvent->getStartedAt()->format('Ym'));
    }
}
