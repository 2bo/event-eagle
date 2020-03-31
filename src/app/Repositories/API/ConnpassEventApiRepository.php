<?php

namespace App\Repositories\API;

use App\Domain\Models\Event\ConnpassEvent;
use App\Domain\Models\Event\ConnpassEventRepositoryInterface;
use App\ApiClients\ApiClient;
use App\Domain\Models\Event\EventService;
use DateTime;

class ConnpassEventApiRepository implements ConnpassEventRepositoryInterface
{
    private $eventService;

    public function __construct()
    {
        $this->eventService = app(EventService::class);
    }

    const URL = "https://connpass.com/api/v1/event/";
    const DELAY = 5000.0;
    const COUNT = 100; //1回のAPIアクセスで取得するイベント件数
    const ORDER_UPDATED = 1; //更新日時順
    const ORDER_START = 2; //開催日時順
    const ORDER_NEW = 3; //新着順

    public function fetchEvents(string $ym, int $start = 1): array
    {
        $jsonArray = $this->fetchJsonFromApi($ym, $start);
        $resultsAvailable = $jsonArray['results_available'];
        $events = $jsonArray['events'];
        $domainEvents = [];

        foreach ($events as $event) {
            $domainEvents[] = $this->makeConnpassEvent($event);
        }

        for ($i = $start + self::COUNT; $i < $resultsAvailable; $i += self::COUNT) {
            $jsonArray = $this->fetchJsonFromApi($ym, $i);
            $events = $jsonArray['events'];
            foreach ($events as $event) {
                $domainEvents[] = $this->makeConnpassEvent($event);
            }
        }

        return $domainEvents;
    }

    public function fetchResultsAvailable(string $ym): int
    {
        $jsonArray = $this->fetchJsonFromApi($ym);
        return $jsonArray['results_available'];
    }

    private function fetchJsonFromApi(string $ym, int $start = 1, int $order = self::ORDER_NEW): array
    {
        $client = new ApiClient();
        $query = ['ym' => $ym, 'start' => $start, 'order' => $order, 'count' => self::COUNT];
        $res = $client->get(self::URL, $query, self::DELAY);
        return json_decode($res, true);
    }

    private function makeConnpassEvent($eventJson): ConnpassEvent
    {
        $eventTypes = $this->eventService->getEventTypesFrom($eventJson['title'], $eventJson['catch'], $eventJson['description']);

        return new ConnpassEvent(
            null,
            $eventJson['event_id'],
            $eventJson['title'],
            $eventJson['catch'],
            $eventJson['description'],
            $eventJson['event_url'],
            null,
            $eventJson['address'],
            $eventJson['place'],
            $eventJson['lat'],
            $eventJson['lon'],
            new DateTime($eventJson['started_at']),
            new DateTime($eventJson['ended_at']),
            $eventJson['limit'],
            $eventJson['accepted'],
            $eventJson['waiting'],
            $eventJson['owner_id'],
            $eventJson['owner_nickname'],
            $eventJson['owner_display_name'],
            $eventJson['series']['id'],
            new DateTime($eventJson['updated_at']),
            $eventTypes
        );
    }
}
