<?php

namespace App\Repositories;

use App\Domain\Models\Event\EventRepositoryInterface;
use App\DataModels\Event as EventDataModel;
use App\Domain\Models\Event\Event;
use App\Domain\Models\Event\EventType;
use App\Domain\Models\Prefecture\PrefectureId;
use Illuminate\Support\Facades\DB;

class EventRepository implements EventRepositoryInterface
{

    public function findAll(): array
    {
        $events = [];
        $dataModels = EventDataModel::with('types')->get();
        foreach ($dataModels as $dataModel) {
            $events[] = $this->convertDataModelToEntity($dataModel);
        }
        return $events;
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
        $dataModel = EventDataModel::updateOrCreate(
            [
                'site_name' => $event->getSiteName(),
                'event_id' => $event->getEventId()
            ],
            [
                'title' => $event->getTitle(),
                'catch' => $event->getCatch(),
                'description' => $event->getDescription(),
                'prefecture_id' => $event->getPrefectureId() ? $event->getPrefectureId()->value() : null,
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
                'is_online' => $event->isOnline(),
            ]
        );

        $typeIds = [];
        $types = $event->getTypes();
        foreach ($types as $type) {
            $typeIds[] = $type->getId();
        }
        $dataModel->types()->sync($typeIds);
        return $event;
    }

    private function convertDataModelToEntity(EventDataModel $eventDataModel): Event
    {
        $types = [];
        foreach ($eventDataModel->types as $type) {
            $types[] = new EventType($type->id, $type->name, $type->needle);
        }

        $event = new Event(
            $eventDataModel->id,
            $eventDataModel->site_name,
            $eventDataModel->event_id,
            $eventDataModel->title,
            $eventDataModel->catch,
            $eventDataModel->description,
            $eventDataModel->event_url,
            $eventDataModel->prefecture_id ? new PrefectureId($eventDataModel->prefecture_id) : null,
            $eventDataModel->address,
            $eventDataModel->place,
            $eventDataModel->lat,
            $eventDataModel->lon,
            $eventDataModel->startd_at ? new \DateTime($eventDataModel->started_at) : null,
            $eventDataModel->ended_at ? new \DateTime($eventDataModel->ended_at) : null,
            $eventDataModel->limit,
            $eventDataModel->participants,
            $eventDataModel->waiting,
            $eventDataModel->owner_id,
            $eventDataModel->owner_nickname,
            $eventDataModel->owner_twitter_id,
            $eventDataModel->owner_display_name,
            $eventDataModel->group_id,
            $eventDataModel->created_at ? new \DateTime($eventDataModel->event_created_at) : null,
            $eventDataModel->startd_at ? new \DateTime($eventDataModel->event_updated_at) : null,
            $eventDataModel->is_online ? true : false,
            $types
        );
        return $event;
    }

}
