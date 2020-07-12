<?php

namespace Tests\Repositories\Unit;

use App\DataModels\Event as EventDataModel;
use App\Domain\Models\Event\ConnpassEvent;
use App\Domain\Models\Event\Event;
use App\Domain\Models\Event\EventType;
use App\Domain\Models\Prefecture\PrefectureId;
use App\Repositories\EventRepository;
use App\Repositories\EventTypeRepository;
use App\Repositories\TagRepository;
use DateTime;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class EventRepositoryTest extends TestCase
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


    public function testUpdateOrCreateEvent()
    {
        $siteName = 'site_name';
        $eventId = 1;
        $title = 'title';
        $catch = 'catch';
        $description = 'description';
        $eventUrl = 'event_url';
        $prefectureId = new PrefectureId(10);
        $address = 'address';
        $place = 'place';
        $lat = 0.1;
        $lon = 0.2;
        $startedAt = new DateTime('2020-01-01 00:00:00');
        $endedAt = new DateTime('2020-01-01 10:00:00');
        $limit = 2;
        $participants = 3;
        $waiting = 4;
        $owner_id = 100;
        $owner_nickname = 'owner_nickname';
        $owner_twitter_id = 'owner_twitter_id';
        $owner_display_name = 'owner_display_name';
        $group_id = 1000;
        $eventCreatedAt = new DateTime('2020-01-01 15:00:00');
        $eventUpdatedAt = new DateTime('2020-01-01 20:00:00');
        $isOnline = true;

        $typeRepository = new EventTypeRepository();
        $types = [
            $typeRepository->findById(EventType::MOKUMOKU),
            $typeRepository->findById(EventType::HANDS_ON),
        ];

        $tagsRepository = new TagRepository();
        $tags = [
            $tagsRepository->findByName('PHP'),
            $tagsRepository->findByName('Laravel'),
        ];

        $event = new Event(
            null,
            $siteName,
            $eventId,
            $title,
            $catch,
            $description,
            $eventUrl,
            $prefectureId,
            $address,
            $place,
            $lat,
            $lon,
            $startedAt,
            $endedAt,
            $limit,
            $participants,
            $waiting,
            $owner_id,
            $owner_nickname,
            $owner_twitter_id,
            $owner_display_name,
            $group_id,
            $eventCreatedAt,
            $eventUpdatedAt,
            $isOnline,
            $types,
            $tags
        );

        $eventRepository = new EventRepository();
        $eventRepository->updateOrCreateEvent($event);
        $eventDataModel = EventDataModel::latest()->first();

        self::assertEquals($siteName, $eventDataModel->site_name);
        self::assertEquals($eventId, $eventDataModel->event_id);
        self::assertEquals($title, $eventDataModel->title);
        self::assertEquals($catch, $eventDataModel->catch);
        self::assertEquals($description, $eventDataModel->description);
        self::assertEquals($eventUrl, $eventDataModel->event_url);
        self::assertEquals($prefectureId->value(), $eventDataModel->prefecture_id);
        self::assertEquals($address, $eventDataModel->address);
        self::assertEquals($place, $eventDataModel->place);
        self::assertEquals($lat, $eventDataModel->lat);
        self::assertEquals($lon, $eventDataModel->lon);
        self::assertEquals($startedAt->format('Y-m-d H:i:s'), $eventDataModel->started_at);
        self::assertEquals($endedAt->format('Y-m-d H:i:s'), $eventDataModel->ended_at);
        self::assertEquals($limit, $eventDataModel->limit);
        self::assertEquals($participants, $eventDataModel->participants);
        self::assertEquals($waiting, $eventDataModel->waiting);
        self::assertEquals($owner_id, $eventDataModel->owner_id);
        self::assertEquals($owner_nickname, $eventDataModel->owner_nickname);
        self::assertEquals($owner_twitter_id, $eventDataModel->owner_twitter_id);
        self::assertEquals($owner_display_name, $eventDataModel->owner_display_name);
        self::assertEquals($group_id, $eventDataModel->group_id);
        self::assertEquals($eventCreatedAt->format('Y-m-d H:i:s'), $eventDataModel->event_created_at);
        self::assertEquals($eventUpdatedAt->format('Y-m-d H:i:s'), $eventDataModel->event_updated_at);
        self::assertEquals($isOnline, $eventDataModel->is_online);
        self::assertEquals(count($types), count($eventDataModel->types()->get()));
        self::assertEquals(count($tags), count($eventDataModel->tags()->get()));
    }

    public function testFindById()
    {
        $siteName = ConnpassEvent::SITE_NAME_CONNPASS;
        $eventId = 10;
        $title = 'title';
        $catch = 'catch';
        $description = 'description';
        $eventUrl = 'https://example.com';
        $prefectureId = new PrefectureId(10);
        $address = 'address';
        $place = 'place';
        $lat = 0.1;
        $lon = 0.2;
        $startedAt = new DateTime('2020-01-01 00:00:00');
        $endedAt = new DateTime('2020-01-01 10:00:00');
        $limit = 20;
        $participants = 30;
        $waiting = 40;
        $owner_id = 100;
        $owner_nickname = 'owner_nickname';
        $owner_twitter_id = 'owner_twitter_id';
        $owner_display_name = 'owner_display_name';
        $group_id = 1000;
        $eventCreatedAt = new DateTime('2020-01-01 15:00:00');
        $eventUpdatedAt = new DateTime('2020-01-01 20:00:00');
        $isOnline = true;

        $typeRepository = new EventTypeRepository();
        $types = [
            $typeRepository->findById(EventType::MOKUMOKU),
            $typeRepository->findById(EventType::HANDS_ON),
        ];

        $tagsRepository = new TagRepository();
        $tags = [
            $tagsRepository->findByName('PHP'),
            $tagsRepository->findByName('Laravel'),
        ];

        $event = new Event(
            null,
            $siteName,
            $eventId,
            $title,
            $catch,
            $description,
            $eventUrl,
            $prefectureId,
            $address,
            $place,
            $lat,
            $lon,
            $startedAt,
            $endedAt,
            $limit,
            $participants,
            $waiting,
            $owner_id,
            $owner_nickname,
            $owner_twitter_id,
            $owner_display_name,
            $group_id,
            $eventCreatedAt,
            $eventUpdatedAt,
            $isOnline,
            $types,
            $tags
        );

        $repository = new EventRepository();
        $repository->updateOrCreateEvent($event);
        $latestId = (\DB::Table('events')->orderBy('id', 'desc')->first())->id;
        $event = $repository->findById($latestId);

        self::assertInstanceOf(Event::class, $event);
        self::assertEquals($latestId, $event->getId());
        self::assertEquals($siteName, $event->getSiteName());
        self::assertEquals($eventId, $event->getEventId());
        self::assertEquals($title, $event->getTitle());
        self::assertEquals($catch, $event->getCatch());
        self::assertEquals($description, $event->getDescription());
        self::assertEquals($eventUrl, $event->getEventUrl());
        self::assertEquals($prefectureId->value(), $event->getPrefectureId()->value());
        self::assertEquals($address, $event->getAddress());
        self::assertEquals($place, $event->getPlace());
        self::assertEquals($lat, $event->getLat());
        self::assertEquals($lon, $event->getLon());
        self::assertEquals($startedAt, $event->getStartedAt());
        self::assertEquals($endedAt, $event->getEndedAt());
        self::assertEquals($limit, $event->getLimit());
        self::assertEquals($participants, $event->getParticipants());
        self::assertEquals($waiting, $event->getWaiting());
        self::assertEquals($owner_id, $event->getOwnerId());
        self::assertEquals($owner_nickname, $event->getOwnerNickname());
        self::assertEquals($owner_twitter_id, $event->getOwnerTwitterId());
        self::assertEquals($owner_display_name, $event->getOwnerDisplayName());
        self::assertEquals($group_id, $event->getGroupId());
        self::assertEquals($eventCreatedAt, $event->getEventCreatedAt());
        self::assertEquals($eventUpdatedAt, $event->getEventUpdatedAt());
        self::assertEquals($isOnline, $event->isOnline());
        self::assertEquals($types, $event->getTypes());
        self::assertEquals($tags, $event->getTags());
    }

    public function testFindByIdWhenNotFound()
    {
        $repository = new EventRepository();
        $event = $repository->findById(-1);
        self::assertNull($event);
    }
}
