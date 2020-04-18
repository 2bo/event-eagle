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

        $placeConditions[] = [
            'value' => 'online',
            'text' => 'オンライン'
        ];

        foreach ($prefectures as $prefecture) {
            $placeConditions[] = [
                'value' => $prefecture->getId()->value(),
                'text' => $prefecture->getName(),
            ];
        }
        return $placeConditions;
    }

    public function getEventTypeConditions()
    {
        $eventTypes = $this->eventTypeRepository->all();
        $eventTypeConditions = [];
        foreach ($eventTypes as $eventType) {
            $eventTypeConditions[] = [
                'value' => $eventType->getId(),
                'text' => $eventType->getName(),
            ];
        }
        return $eventTypeConditions;
    }
}
