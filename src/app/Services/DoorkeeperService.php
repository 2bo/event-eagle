<?php


namespace App\Services;


use App\Models\Event;
use Carbon\Carbon;

class DoorkeeperService
{
    private const EVENT_API_URL = 'https://api.doorkeeper.jp/events';

    static public function fetchEventsFromAPI(array $params, bool $isAllPage)
    {
        if ($isAllPage) {
            for ($p = 1; true; $p++) {
                $params['page'] = $p;
                $headers = ['Authorization' => 'Bearer ' . config('env.doorkeeper_api_token')];
                $jsonArray = ApiClient::getJsonArray(self::EVENT_API_URL, $params, 1000.0, $headers);
                if (empty($jsonArray)) {
                    break;
                }
                self::updateOCreateEventsFromAPIResult($jsonArray);
            }

        } else {
            $jsonArray = ApiClient::getJsonArray(self::EVENT_API_URL, $params, 1000.0);
            self::updateOCreateEventsFromAPIResult($jsonArray);
        }
    }

    static private function updateOCreateEventsFromAPIResult(array $eventJson)
    {
        foreach ($eventJson as $record) {
            $event = $record['event'];

            $prefecture = $event['address'] ? PrefectureService::getPrefectureFromAddress($event['address']) : null;
            if (!$prefecture && $event['lat'] && $event['long']) {
                $prefecture = PrefectureService::getPrefectureFromCoordinates($event['lat'], $event['long']);
            }

            Event::updateOrCreate(
                [
                    'site_name' => Event::DOORKEEPER,
                    'event_id' => $event['id']
                ],
                [
                    'title' => $event['title'],
                    'description' => $event['description'],
                    'event_url' => $event['public_url'],
                    'prefecture_id' => $prefecture ? $prefecture->id : null,
                    'address' => $event['address'],
                    'place' => $event['venue_name'],
                    'lat' => $event['lat'],
                    'lon' => $event['long'],
                    'started_at' => self::formatUtcTimeToMySqlDateTime($event['starts_at']),
                    'ended_at' => self::formatUtcTimeToMySqlDateTime($event['ends_at']),
                    'limit' => $event['ticket_limit'],
                    'participants' => $event['participants'],
                    'waiting' => $event['waitlisted'],
                    'group_id' => $event['group'],
                    'event_created_at' => self::formatUtcTimeToMySqlDateTime($event['published_at']),
                    'event_updated_at' => self::formatUtcTimeToMySqlDateTime($event['updated_at']),
                ]
            );
        }
    }

    static private function formatUtcTimeToMySqlDateTime(string $date)
    {
        $carbon = new Carbon($date);
        return $carbon->setTimezone('JST')->format('Y-m-d H:i:s');
    }
}

