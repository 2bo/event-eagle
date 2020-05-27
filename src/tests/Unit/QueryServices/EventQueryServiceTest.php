<?php

namespace Tests\Unit\QueryServices;

use App\Domain\Models\Event\ConnpassEvent;
use App\Domain\Models\Event\Event;
use App\Domain\Models\Prefecture\PrefectureId;
use App\QueryServices\EventQueryService;
use App\Repositories\EventRepository;
use App\Repositories\EventTypeRepository;
use App\Repositories\PrefectureRepository;
use App\Repositories\TagRepository;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class EventQueryServiceTest extends TestCase
{
    use RefreshDatabase;
    static private $isDbInitialized = false;
    static private $queryService;
    static private $eventRepository;
    static private $typeRepository;
    static private $tagRepository;
    static private $prefRepository;


    public function setUp(): void
    {
        parent::setUp();
        if (!self::$isDbInitialized) {
            self::initializeDb();
        }
    }

    private static function initializeDb()
    {
        Artisan::call('db:seed');
        self::$isDbInitialized = true;
    }

    public static function setUpBeforeClass()
    {
        self::$queryService = new EventQueryService();
        self::$eventRepository = new EventRepository();
        self::$typeRepository = new EventTypeRepository();
        self::$tagRepository = new TagRepository();
        self::$prefRepository = new PrefectureRepository();
    }

    public function testSelect()
    {
        //テストデータの作成
        $types = self::$typeRepository->all();
        $tags = [self::$tagRepository->findByName('PHP'), self::$tagRepository->findByName('Laravel')];
        $startedAt = new DateTime();
        $endedAt = new DateTime();
        $eventCreatedAt = new DateTime();
        $eventUpdatedAt = new DateTime();
        $prefectureId = new PrefectureId(2);

        $event = new Event(null, ConnpassEvent::SITE_NAME_CONNPASS, 1,
            'title', 'catch',
            'description', 'example.com/event/1',
            $prefectureId, 'address',
            'place', 1.1, 2.2,
            $startedAt, $endedAt,
            10, 5,
            3, 4,
            'owner_nickname', 'owner_twitter_id',
            'owner_display_name', 6,
            $eventCreatedAt, $eventUpdatedAt, true, $types, $tags);
        self::$eventRepository->updateOrCreateEvent($event);

        //検索
        $result = self::$queryService->searchEvent();
        //1件目
        $data = ($result->getData())[0];

        //件数とページ
        self::assertEquals(1, $result->getTotal());
        self::assertEquals(1, $result->getCurrentPage());
        self::assertEquals(1, $result->getLastPage());
        //取得項目のチェック
        self::assertIsArray($result->getData());
        self::assertEquals(1, $data->id);
        self::assertEquals(1, $data->event_id);
        self::assertEquals(ConnpassEvent::SITE_NAME_CONNPASS, $data->site_name);
        self::assertEquals('title', $data->title);
        self::assertEquals('catch', $data->catch);
        self::assertEquals('example.com/event/1', $data->event_url);
        self::assertEquals('address', $data->address);
        self::assertEquals('place', $data->place);
        self::assertEquals(1.1, $data->lat);
        self::assertEquals(2.2, $data->lon);
        self::assertEquals($startedAt->format('Y-m-d H:i:s'), $data->started_at);
        self::assertEquals($endedAt->format('Y-m-d H:i:s'), $data->ended_at);
        self::assertEquals(10, $data->limit);
        self::assertEquals(5, $data->participants);
        self::assertEquals(3, $data->waiting);
        self::assertEquals('owner_nickname', $data->owner_nickname);
        self::assertEquals('owner_twitter_id', $data->owner_twitter_id);
        self::assertEquals('owner_display_name', $data->owner_display_name);
        self::assertEquals($eventCreatedAt->format('Y-m-d H:i:s'), $data->created_at);
        self::assertEquals($eventUpdatedAt->format('Y-m-d H:i:s'), $data->updated_at);
        self::assertEquals(1, $data->is_online);

        //正解となる都道府県名
        $prefecture = self::$prefRepository->findById($prefectureId);
        self::assertEquals($prefecture->getName(), $data->prefecture_name);

        //正解となるタイプの名前
        $typeNames = array_map(function ($type) {
            return $type->getName();
        }, $types);
        //イベントタイプの検索結果データはカンマ区切りで連結されている
        self::assertEmpty(array_diff($typeNames, explode(',', $data->types)));
        self::assertEquals(count($typeNames), count(explode(',', $data->types)));

        //正解となるタグの名前
        $tagNames = array_map(function ($tag) {
            return $tag->getName();
        }, $tags);
        //タグの検索結果データはカンマ区切りで連結されている
        self::assertEmpty(array_diff($tagNames, explode(',', $data->tags)));
        self::assertEquals(count($tagNames), count(explode(',', $data->tags)));
    }
}
