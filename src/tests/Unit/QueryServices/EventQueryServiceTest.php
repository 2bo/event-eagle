<?php

namespace Tests\Unit\QueryServices;

use App\Domain\Models\Event\ConnpassEvent;
use App\Domain\Models\Event\Event;
use App\Domain\Models\Event\EventType;
use App\Domain\Models\Prefecture\PrefectureId;
use App\QueryServices\EventQueryService;
use App\Repositories\EventRepository;
use App\Repositories\EventTypeRepository;
use App\Repositories\PrefectureRepository;
use App\Repositories\TagRepository;
use DateTime;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class EventQueryServiceTest extends TestCase
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
        self::$queryService = new EventQueryService();
        self::$eventRepository = new EventRepository();
        self::$typeRepository = new EventTypeRepository();
        self::$tagRepository = new TagRepository();
        self::$prefRepository = new PrefectureRepository();
    }

    // 検索結果の取得項目のテスト
    public function testSelect()
    {
        //テストデータの作成
        //タイプ
        $typeMokumoku = self::$typeRepository->findById(EventType::MOKUMOKU);
        $typeConference = self::$typeRepository->findById(EventType::CONFERENCE);
        $types = [$typeMokumoku, $typeConference];
        //タグ
        $tagPhp = self::$tagRepository->findByName('php');
        $tagLaravel = self::$tagRepository->findByName('Laravel');
        $tags = [$tagPhp, $tagLaravel];

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

        //正解となるタイプ
        $expectedType = [
            ['id' => $typeMokumoku->getId(), 'name' => $typeMokumoku->getName()],
            ['id' => $typeConference->getId(), 'name' => $typeConference->getName()],
        ];
        self::assertEquals($expectedType, $data->types);
        //正解となるタグ
        $expectedTag = [
            ['id' => $tagLaravel->getId(), 'name' => $tagLaravel->getName(), 'url_name' => $tagLaravel->getUrlName()],
            ['id' => $tagPhp->getId(), 'name' => $tagPhp->getName(), 'url_name' => $tagPhp->getUrlName()],
        ];
        self::assertEquals($expectedTag, $data->tags);
    }

    public function testPrefectureCondition()
    {
        $startedAt = new DateTime();
        $endedAt = new DateTime();
        //テスト用レコードの作成
        //判定用に$prefecture_idと同じ値をevent_idに設定
        for ($i = 0; $i < 3; $i++) {
            $prefectureId = new PrefectureId($i + 1);
            $event = new Event(null, '', $i + 1,
                null, null, null, null, $prefectureId,
                null, null, null, null, $startedAt, $endedAt);
            self::$eventRepository->updateOrCreateEvent($event);
        }
        //一部のデータに合致する検索条件
        $prefectureCondition = ['1', '3'];
        $result = self::$queryService->searchEvent($startedAt, null, null, $prefectureCondition);
        $data = $result->getData();
        self::assertEquals(count($prefectureCondition), $result->getTotal());
        self::assertEquals(count($prefectureCondition), count($data));
        // prefecture_idによるソートはしていないので、prefecture_idの集合に差分がないか確認
        $resultEventIds = array_map(function ($record) {
            return $record->event_id;
        }, $data);
        self::assertEmpty(array_diff($prefectureCondition, $resultEventIds));

        // 全てのデータに合致しない検索条件
        $prefectureCondition = ['4'];
        $result = self::$queryService->searchEvent($startedAt, null, null, $prefectureCondition);
        self::assertEquals(0, $result->getTotal());
        self::assertEmpty($result->getData());

        // 検索条件がnull
        $prefectureCondition = null;
        $result = self::$queryService->searchEvent($startedAt, null, null, $prefectureCondition);
        self::assertEquals(3, $result->getTotal());
        self::assertEquals(3, count($result->getData()));

        // 検索条件がからの配列
        $prefectureCondition = [];
        $result = self::$queryService->searchEvent($startedAt, null, null, $prefectureCondition);
        self::assertEquals(3, $result->getTotal());
        self::assertEquals(3, count($result->getData()));
    }

    public function testFromCondition()
    {
        $start = new DateTime('2020-01-01');
        for ($i = 0; $i < 3; $i++) {
            $event = new Event(null, '', $i, null, null, null, null,
                null, null, null, null, null, $start);
            self::$eventRepository->updateOrCreateEvent($event);
            $start->modify('+1 day');
        }

        $from = new DateTime('2020-01-02');
        $result = self::$queryService->searchEvent($from);
        self::assertEquals(2, $result->getTotal());
        foreach ($result->getData() as $data) {
            self::assertTrue($from <= new DateTime($data->started_at));
        }
    }

    public function testToCondition()
    {
        $start = new DateTime('2020-01-01');
        for ($i = 0; $i < 3; $i++) {
            $event = new Event(null, '', $i, null, null, null, null,
                null, null, null, null, null, $start);
            self::$eventRepository->updateOrCreateEvent($event);
            $start->modify('+1 day');
        }

        $to = new DateTime('2020-01-02');
        $result = self::$queryService->searchEvent(null, $to);
        self::assertEquals(2, $result->getTotal());
        foreach ($result->getData() as $data) {
            self::assertTrue($to >= new DateTime($data->started_at));
        }
    }

    public function testFreeTextCondition()
    {
        $eventId = 1;
        //title
        $event = new Event(null, '', $eventId++, 'PHPとLaravel');
        self::$eventRepository->updateOrCreateEvent($event);
        $result = self::$queryService->searchEvent(null, null, 'Laravel');
        self::assertEquals(1, count($result->getData()));

        //catch
        $event = new Event(null, '', $eventId++, null, 'PHPとSymfony');
        self::$eventRepository->updateOrCreateEvent($event);
        $result = self::$queryService->searchEvent(null, null, 'Symfony');
        self::assertEquals(1, count($result->getData()));

        //description
        $event = new Event(null, '', $eventId++, null, null, 'PHPとCakePHP');
        self::$eventRepository->updateOrCreateEvent($event);
        $result = self::$queryService->searchEvent(null, null, 'CakePHP');
        self::assertEquals(1, count($result->getData()));

        //address
        $event = new Event(null, '', $eventId++, null, null, null, null, null, '東京都渋谷区');
        self::$eventRepository->updateOrCreateEvent($event);
        $result = self::$queryService->searchEvent(null, null, '渋谷区');
        self::assertEquals(1, count($result->getData()));

        //place
        $event = new Event(null, '', $eventId++, null, null, null, null, null, null, '六本木ヒルズ10F');
        self::$eventRepository->updateOrCreateEvent($event);
        $result = self::$queryService->searchEvent(null, null, '六本木ヒルズ');
        self::assertEquals(1, count($result->getData()));

        //スペース区切りで複数条件の検索
        $result = self::$queryService->searchEvent(null, null, 'Laravel Symfony CakePHP 渋谷区');
        self::assertEquals(4, count($result->getData()));

    }

    public function testIsOnlineCondition()
    {
        $titles = ['オンライン', 'オフライン'];
        foreach ($titles as $index => $title) {
            $event = new Event(null, '', $index, $title);
            $event->updateIsOnline();
            self::$eventRepository->updateOrCreateEvent($event);
        }
        //オンラインの検索
        $result = self::$queryService->searchEvent(null, null, null, null, null, true);
        self::assertEquals(1, $result->getTotal());
        self::assertEquals('オンライン', ($result->getData()[0]->title));
        //オフラインの検索
        $result = self::$queryService->searchEvent(null, null, null, null, null, false);
        self::assertEquals(1, $result->getTotal());
        self::assertEquals('オフライン', ($result->getData()[0]->title));
    }

    public function testTypeCondition()
    {
        $types = [];
        $types[] = new EventType(EventType::LT);
        $types[] = new EventType(EventType::MOKUMOKU);
        $event = new Event(null, '', 1, null, null, null, null,
            null, null, null, null, null, null, null, null,
            null, null, null, null, null, null,
            null, null, null, false, $types);

        //一致するタイプとしないものを両方含む条件
        self::$eventRepository->updateOrCreateEvent($event);
        $result = self::$queryService->searchEvent(null, null, null, null, [EventType::LT, EventType::READING]);
        self::assertEquals(1, count($result->getData()));
        //一致しない条件
        $result = self::$queryService->searchEvent(null, null, null, null, [EventType::CONFERENCE, EventType::READING]);
        self::assertEquals(0, count($result->getData()));
    }

    public function testPrefectureAndIsOnlineCondition()
    {
        // 北海道　オンライン
        $prefectureId = new PrefectureId(1);
        $event = new Event(null, '', 1, null, null, null, null,
            $prefectureId, null, null, null, null, null, null, null,
            null, null, null, null, null, null,
            null, null, null, true);
        self::$eventRepository->updateOrCreateEvent($event);

        // 青森 オフライン
        $prefectureId = new PrefectureId(2);
        $event = new Event(null, '', 2, null, null, null, null,
            $prefectureId, null, null, null, null, null, null, null,
            null, null, null, null, null, null,
            null, null, null, false);
        self::$eventRepository->updateOrCreateEvent($event);

        // 青森 オンラインを条件に検索 場所とオンラインはOR条件で検索する
        $result = self::$queryService->searchEvent(null, null, null, [2], null, true);
        self::assertEquals(2, count($result->getData()));
    }

    public function testAllCondition()
    {
        $eventId = 1;
        $title = 'PHPとLaravel';
        $prefectureId = new PrefectureId(1);
        $startedAt = new DateTime('2020-06-20 18:00:00');
        $endedAt = new DateTime('2020-06-20 20:00:00');
        $types = [];
        $types[] = new EventType(EventType::LT);
        $types[] = new EventType(EventType::MOKUMOKU);
        $isOnline = true;
        $event = new Event(null, '', $eventId++, $title, null, null, null,
            $prefectureId, null, null, null, null, $startedAt, $endedAt, null,
            null, null, null, null, null, null,
            null, null, null, $isOnline, $types);
        self::$eventRepository->updateOrCreateEvent($event);

        // titleだけ変えたイベント
        $title = 'RubyとRails';
        $event = new Event(null, '', $eventId, $title, null, null, null,
            $prefectureId, null, null, null, null, $startedAt, $endedAt, null,
            null, null, null, null, null, null,
            null, null, null, $isOnline, $types);
        self::$eventRepository->updateOrCreateEvent($event);

        $result = self::$queryService->searchEvent($startedAt, $endedAt, $title, [$prefectureId->value()], [EventType::LT], $isOnline);
        self::assertEquals(1, count($result->getData()));
    }

    public function testPager()
    {
        $numOfEvents = 10;
        $perPage = 5;
        for ($i = 0; $i < $numOfEvents; $i++) {
            self::$eventRepository->updateOrCreateEvent(new Event(null, '', $i + 1, null,
                null, null, null, null, null, null, null,
                null, new DateTime('+ ' . $i . ' day')));
        }

        //1ページ目
        $result = self::$queryService->searchEvent(null, null, null, null, null, null, 1, $perPage);
        self::assertEquals($numOfEvents, $result->getTotal());
        self::assertEquals(1, $result->getCurrentPage());
        self::assertEquals(2, $result->getLastPage());
        $eventIds = array_map(function ($data) {
            return $data->event_id;
        }, $result->getData());
        self::assertEquals([1, 2, 3, 4, 5], $eventIds);

        //2ページ目
        $result = self::$queryService->searchEvent(null, null, null, null, null, null, 2, $perPage);
        self::assertEquals($numOfEvents, $result->getTotal());
        self::assertEquals(2, $result->getCurrentPage());
        self::assertEquals(2, $result->getLastPage());
        $eventIds = array_map(function ($data) {
            return $data->event_id;
        }, $result->getData());
        self::assertEquals([6, 7, 8, 9, 10], $eventIds);
    }

    public function testOrder()
    {
        $numOfEvents = 3;
        for ($i = 0; $i < $numOfEvents; $i++) {
            self::$eventRepository->updateOrCreateEvent(new Event(null, '', $i + 1, null,
                null, null, null, null, null, null, null,
                null, new DateTime('- ' . $i . ' day')));
        }
        $result = self::$queryService->searchEvent();
        $eventIds = array_map(function ($data) {
            return $data->event_id;
        }, $result->getData());
        self::assertEquals([3, 2, 1], $eventIds);
    }
}
