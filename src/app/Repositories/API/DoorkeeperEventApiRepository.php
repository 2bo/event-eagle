<?php


namespace App\Repositories\API;


use App\ApiClients\ApiClient;
use App\Domain\Models\Event\DoorkeeperEvent;
use App\Domain\Models\Event\DoorkeeperEventRepositoryInterface;
use App\Domain\Models\Event\EventService;

class DoorkeeperEventApiRepository implements DoorkeeperEventRepositoryInterface
{
    private const URL = 'https://api.doorkeeper.jp/events';
    private const DELAY = 1000.0;
    private const LOCALE_JA = 'ja';
    private const LOCALE_EN = 'en';
    private const SORT_PUBLISHED_AT = 'published_at';
    private const SORT_STARTS_AT = 'starts_at';
    private const SORT_UPDATED_AT = 'updated_at';
    private const EXPAND_GROUP = 'group';

    private $apiToken;
    private $eventService;

    public function __construct()
    {
        $this->apiToken = config('env.doorkeeper_api_token');
        $this->eventService = app(EventService::class);
    }

    public function fetchEvents(\DateTime $since, \DateTime $until, int $page = 1): array
    {
        $events = $this->fetchJsonFromApi($since, $until);
        $domainEvents = [];
        foreach ($events as $event) {
            $domainEvents[] = $this->makeDoorkeeperEvent($event['event']);
        }

        for ($p = $page; !empty($events); $p++) {
            $events = $this->fetchJsonFromApi($since, $until, $p);
            foreach ($events as $event) {
                $domainEvents[] = $this->makeDoorkeeperEvent($event['event']);
            }
        }

        return $domainEvents;
    }

    private function fetchJsonFromApi(\DateTime $since, \DateTime $until, int $page = 1,
                                      string $sort = self::SORT_STARTS_AT, string $locale = self::LOCALE_JA)
    {
        $utc = new \DateTimeZone('UTC');
        $sinceInUTC = $since->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        $untilInUTC = $until->setTimezone($utc)->format('Y-m-d\TH:i:s\Z');
        $params = [
            'since' => $sinceInUTC,
            'until' => $untilInUTC,
            'page' => $page,
            'sort' => $sort,
            'locale' => $locale
        ];
        $headers = ['Authorization' => "Bearer {$this->apiToken}"];

        $client = new ApiClient();
        $response = $client->get(self::URL, $params, self::DELAY, $headers);
        $events = json_decode($response, true);

        return $events;
    }

    private function makeDoorkeeperEvent($eventJson): DoorkeeperEvent
    {
        $eventTypes = $this->eventService->getEventTypesFrom($eventJson['title'], null, $eventJson['description']);

        return new DoorkeeperEvent(
            null,
            $eventJson['id'],
            $eventJson['title'],
            $this->utcToJst($eventJson['starts_at']),
            $this->utcToJst($eventJson['ends_at']),
            $eventJson['venue_name'],
            $eventJson['address'],
            $eventJson['lat'],
            $eventJson['long'],
            $eventJson['ticket_limit'],
            $this->utcToJst($eventJson['published_at']),
            $this->utcToJst($eventJson['updated_at']),
            $eventJson['group'],
            $eventJson['description'],
            $eventJson['public_url'],
            $eventJson['participants'],
            $eventJson['waitlisted'],
            null,
            $eventTypes
        );
    }

    private function utcToJst(?string $utc): ?\DateTime
    {
        if (!$utc) {
            return null;
        }
        // YYYY-MM-DDThh:mm:ss.000Z
        if (!preg_match('/^[\d]{4}\-[\d]{2}\-[\d]{2}T[\d]{2}:[\d]{2}:[\d]{2}\.[\d]{3}Z$/', $utc)) {
            throw new \InvalidArgumentException('Not UTC DateTime format');
        };
        $utcDateTime = new \DateTime($utc);
        $jstDateTime = $utcDateTime->setTimezone(new \DateTimeZone('Asia/Tokyo'));
        return $jstDateTime;
    }


}
