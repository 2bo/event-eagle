<?php


namespace App\Repositories\API;


use App\ApiClients\ApiClient;
use App\Domain\Models\Event\DoorkeeperEvent;
use App\Domain\Models\Event\DoorkeeperEventRepositoryInterface;

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

    public function __construct()
    {
        $this->apiToken = config('env.doorkeeper_api_token');
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
        return new DoorkeeperEvent(
            null,
            $eventJson['id'],
            $eventJson['title'],
            $eventJson['starts_at'] ? new \DateTime($eventJson['starts_at']) : null,
            $eventJson['ends_at'] ? new \DateTime($eventJson['ends_at']) : null,
            $eventJson['venue_name'],
            $eventJson['address'],
            $eventJson['lat'],
            $eventJson['long'],
            $eventJson['ticket_limit'],
            $eventJson['published_at'] ? new \DateTime($eventJson['published_at']) : null,
            $eventJson['updated_at'] ? new \DateTime($eventJson['updated_at']) : null,
            $eventJson['group'],
            $eventJson['description'],
            $eventJson['public_url'],
            $eventJson['participants'],
            $eventJson['waitlisted'],
            null
        );
    }


}
