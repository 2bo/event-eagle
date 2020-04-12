<?php


namespace App\Domain\Services;


use App\Domain\Models\Event\EventTypeRepositoryInterface;
use App\Domain\Models\Prefecture\PrefectureRepositoryInterface;

class SearchConditionService
{
    private $prefectureRepository;
    private $eventTypeRepository;

    public function __construct(PrefectureRepositoryInterface $prefectureRepository, EventTypeRepositoryInterface $eventTypeRepository)
    {
        $this->prefectureRepository = $prefectureRepository;
        $this->eventTypeRepository = $eventTypeRepository;
    }

    public function getPlaceConditions()
    {
        $prefectures = $this->prefectureRepository->findAll();
        $placeConditions = [];

        foreach ($prefectures as $prefecture) {
            $placeConditions[] = [
                'id' => $prefecture->getId()->value(),
                'name' => $prefecture->getName(),
            ];
        }
        $placeConditions[] = [
            'id' => 'online',
            'name' => 'オンライン'
        ];
        return $placeConditions;
    }

    public function getEventTypeConditions()
    {
        $eventTypes = $this->eventTypeRepository->all();
        $eventTypeConditions = [];
        foreach ($eventTypes as $eventType) {
            $eventTypeConditions[] = [
                'id' => $eventType->getId(),
                'name' => $eventType->getName(),
            ];
        }
        return $eventTypeConditions;
    }
}
