<?php

namespace App\Repositories;

use App\Models\Event;
use Illuminate\Support\Facades\DB;
use App\Services\PrefectureService;
use Carbon\Carbon;

class EventRepository
{
    public function getModelClass(): string
    {
        return Event::class;
    }

    public function getNewEvents(): array
    {
        $events = DB::table('events')
            ->orderBy('started_at', 'desc')
            ->limit(30)
            ->get()
            ->toArray();
        return $events;
    }

    public function updateOCreateAtndEventsFromAPIResult(array $eventJson)
    {
        foreach ($eventJson as $record) {
            $event = $record['event'];

            $prefecture = $event['address'] ? PrefectureService::getPrefectureFromAddress($event['address']) : null;
            if (!$prefecture && $event['lat'] && $event['lon']) {
                $prefecture = PrefectureService::getPrefectureFromCoordinates($event['lat'], $event['lon']);
            }

            Event::updateOrCreate(
                [
                    'site_name' => 'atnd',
                    'event_id' => $event['event_id']
                ],
                [
                    'title' => $event['title'],
                    'catch' => $event['catch'],
                    'description' => $event['description'],
                    'prefecture_id' => $prefecture ? $prefecture->id : null,
                    'started_at' => $this->formatTimeToMySqlDateTime($event['started_at']),
                    'ended_at' => $this->formatTimeToMySqlDateTime($event['ended_at']),
                    'event_url' => $event['url'],
                    'limit' => $event['limit'],
                    'address' => $event['address'],
                    'place' => $event['place'],
                    'lat' => $event['lat'],
                    'lon' => $event['lon'],
                    'owner_id' => $event['owner_id'],
                    'owner_nickname' => $event['owner_nickname'],
                    'owner_twitter_id' => $event['owner_twitter_id'],
                    'participants' => $event['accepted'],
                    'waiting' => $event['waiting'],
                    'event_updated_at' => $this->formatTimeToMySqlDateTime($event['updated_at']),
                ]
            );
        }
    }

    private function formatTimeToMySqlDateTime($date = '')
    {
        if (!$date) {
            return null;
        }

        $carbon = new Carbon($date);
        return $carbon->setTimezone('JST')->format('Y-m-d H:i:s');
    }


}
