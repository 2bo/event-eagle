<?php


namespace App\Domain\Models\Event;


class EventService
{
    private $eventTypeRepository;
    private $allEventTypes;

    public function __construct(EventTypeRepositoryInterface $repository)
    {
        $this->eventTypeRepository = $repository;
    }

    public function getEventTypesFrom(string $title = null, string $catch = null, string $description = null): array
    {
        if (!$title && !$catch && !$description) {
            return [];
        }

        if (!$this->allEventTypes) {
            $this->allEventTypes = $this->eventTypeRepository->all();
        }

        $eventTypes = [];
        $targets = [$title, $catch, $description];
        foreach ($this->allEventTypes as $eventType) {
            $needle = $eventType->getNeedle();
            foreach ($targets as $target) {
                if (preg_match("/{$needle}/i", $target)) {
                    $eventTypes[] = $eventType;
                    break;
                }
            }
        }
        return $eventTypes;
    }

}
