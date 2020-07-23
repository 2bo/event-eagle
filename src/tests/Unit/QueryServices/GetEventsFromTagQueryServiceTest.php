<?php

namespace Tests\Unit\QueryServices;

use App\Domain\Models\Event\ConnpassEvent;
use App\Domain\Models\Event\Event;
use App\Domain\Models\Event\EventType;
use App\Domain\Models\Prefecture\PrefectureId;
use App\QueryServices\GetEventsFromTagQueryService;
use App\QueryServices\PaginateResult;
use App\Repositories\EventRepository;
use App\Repositories\EventTypeRepository;
use App\Repositories\PrefectureRepository;
use App\Repositories\TagRepository;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class GetEventsFromTagQueryServiceTest extends TestCase
{

    static private $queryService;
    static private $eventRepository;
    static private $typeRepository;
    static private $tagRepository;
    static private $prefRepository;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh'); //RefreshDatabase traitが正常動作しないため追加
        Artisan::call('db:seed');
    }

    public static function setUpBeforeClass()
    {
        self::$queryService = new GetEventsFromTagQueryService();
        self::$eventRepository = new EventRepository();
        self::$typeRepository = new EventTypeRepository();
        self::$tagRepository = new TagRepository();
        self::$prefRepository = new PrefectureRepository();
    }


    public function testGetEventsFromTagSelect()
    {
        //テストデータ
        $siteName = ConnpassEvent::SITE_NAME_CONNPASS;
        $eventId = 1;
        $title = 'title';
        $catch = 'catch';
        $description = 'description';
        $eventUrl = 'https://event.example.com';
        $prefectureId = new PrefectureId(2);
        $address = 'address';
        $place = 'place';
        $lat = '3.3';
        $lon = '4.4';
        $startedAt = new \DateTime('+ 3 hour');
        $endedAt = new \DateTime('+ 6 hour');
        $limit = 30;
        $participants = 25;
        $waiting = 5;
        $ownerId = 6;
        $ownerNickname = 'owner_nickname';
        $ownerDisplayName = 'owner_display_name';
        $ownerTwitterId = 'owner_twitter_id';
        $groupId = 7;
        $eventCreatedAt = new \DateTime('- 2 hour');
        $eventUpdatedAt = new \DateTime('- 1 hour');
        $isOnline = true;
        //タイプ
        $typeMokumoku = self::$typeRepository->findById(EventType::MOKUMOKU);
        $typeConference = self::$typeRepository->findById(EventType::CONFERENCE);
        $types = [$typeMokumoku, $typeConference];
        //タグ
        $tagPhp = self::$tagRepository->findByName('php');
        $tagLaravel = self::$tagRepository->findByName('Laravel');
        $tags = [$tagPhp, $tagLaravel];

        $event = new Event(null, $siteName, $eventId, $title, $catch, $description, $eventUrl,
            $prefectureId, $address, $place, $lat, $lon, $startedAt, $endedAt, $limit, $participants,
            $waiting, $ownerId, $ownerNickname, $ownerTwitterId, $ownerDisplayName, $groupId, $eventCreatedAt,
            $eventUpdatedAt, $isOnline, $types, $tags);
        self::$eventRepository->updateOrCreateEvent($event);

        //検索条件
        $from = new \DateTime();
        $to = new \DateTime('+ 1 month');
        $paginateResult = self::$queryService->getEventsFromTag('php', $from, $to);
        $data = $paginateResult->getData();

        //テスト
        self::assertInstanceOf(PaginateResult::class, $paginateResult);
        self::assertEquals($siteName, $data[0]->site_name);
        self::assertEquals($title, $data[0]->title);
        self::assertEquals($catch, $data[0]->catch);
        self::assertEquals($eventUrl, $data[0]->event_url);
        self::assertEquals($address, $data[0]->address);
        self::assertEquals($place, $data[0]->place);
        self::assertEquals($lat, $data[0]->lat);
        self::assertEquals($lon, $data[0]->lon);
        self::assertEquals($startedAt->format('Y-m-d H:i:s'), $data[0]->started_at);
        self::assertEquals($endedAt->format('Y-m-d H:i:s'), $data[0]->ended_at);
        self::assertEquals($limit, $data[0]->limit);
        self::assertEquals($participants, $data[0]->participants);
        self::assertEquals($waiting, $data[0]->waiting);
        self::assertEquals($ownerNickname, $data[0]->owner_nickname);
        self::assertEquals($ownerTwitterId, $data[0]->owner_twitter_id);
        self::assertEquals($ownerDisplayName, $data[0]->owner_display_name);
        self::assertEquals($eventCreatedAt->format('Y-m-d H:i:s'), $data[0]->created_at);
        self::assertEquals($eventUpdatedAt->format('Y-m-d H:i:s'), $data[0]->updated_at);
        self::assertEquals($isOnline, $data[0]->is_online);
        $expectedPrefecture = self::$prefRepository->findById($prefectureId);
        self::assertEquals($expectedPrefecture->getName(), $data[0]->prefecture_name);
        $expectedType = [
            ['id' => $typeMokumoku->getId(), 'name' => $typeMokumoku->getName()],
            ['id' => $typeConference->getId(), 'name' => $typeConference->getName()],
        ];
        self::assertEquals($expectedType, $data[0]->types);
        $expectedTag = [
            ['id' => $tagLaravel->getId(), 'name' => $tagLaravel->getName(), 'url_name' => $tagLaravel->getUrlName()],
            ['id' => $tagPhp->getId(), 'name' => $tagPhp->getName(), 'url_name' => $tagPhp->getUrlName()],
        ];
        self::assertEquals($expectedTag, $data[0]->tags);
    }
}
