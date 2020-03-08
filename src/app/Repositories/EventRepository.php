<?php

namespace App\Repositories;

use App\Models\Event as EventDataModel;
use App\Domain\Event\Event;
use Illuminate\Support\Facades\DB;
use App\Services\PrefectureService;
use Carbon\Carbon;

class EventRepository
{
    public function getModelClass(): string
    {
        return EventDataModel::class;
    }

    // FIXME Domain/Eventを返すように変更
    public function getNewEvents(): array
    {
        $events = DB::table('events')
            ->orderBy('started_at', 'desc')
            ->limit(30)
            ->get()
            ->toArray();
        return $events;
    }

    public function updateOrCreateEvent(Event $event): Event
    {
        $eventDataModel = EventDataModel::updateOrCreate(
            [
                'site_name' => $event->getSiteName(),
                'event_id' => $event->getEventId()
            ],
            [
                'title' => $event->getTitle(),
                'catch' => $event->getCatch(),
                'description' => $event->getDescription(),
                'prefecture_id' => $event->getPrefectureId(),
                'started_at' => $event->getStartedAt(),
                'ended_at' => $event->getEndedAt(),
                'event_url' => $event->getEventUrl(),
                'limit' => $event->getLimit(),
                'address' => $event->getAddress(),
                'place' => $event->getPlace(),
                'lat' => $event->getLat(),
                'lon' => $event->getLon(),
                'owner_id' => $event->getOwnerId(),
                'owner_nickname' => $event->getOwnerNickname(),
                'owner_twitter_id' => $event->getOwnerTwitterId(),
                'owner_display_name' => $event->getOwnerDisplayName(),
                'group_id' => $event->getGroupId(),
                'participants' => $event->getParticipants(),
                'waiting' => $event->getWaiting(),
                'event_created_at' => $event->getEventCreatedAt(),
                'event_updated_at' => $event->getEventUpdatedAt(),
            ]
        );
        $event->setId($eventDataModel->id);
        return $event;
    }

    // FIXME このメソッドを廃止する
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
