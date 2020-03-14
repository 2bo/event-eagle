<?php


namespace App\UseCases\FetchConnpassEvents;

use App\Domain\Models\Event\EventRepositoryInterface;
use App\Domain\Services\PrefectureService;
use App\Domain\Models\Event\ConnpassEventRepositoryInterface;

class FetchConnpassEventsUseCase implements FetchConnpassEventsUseCaseInterface
{
    private $connpassEventRepository;
    private $prefectureService;
    private $eventRepository;

    public function __construct(ConnpassEventRepositoryInterface $connpassEventRepository,
                                PrefectureService $prefectureService,
                                EventRepositoryInterface $eventRepository)
    {
        $this->connpassEventRepository = $connpassEventRepository;
        $this->prefectureService = $prefectureService;
        $this->eventRepository = $eventRepository;
    }

    public function handle(FetchConnpassEventsInputData $input)
    {
        $events = $this->connpassEventRepository->fetchEvents($input->getYearMonth());
        foreach ($events as $event) {
            $prefecture = $this->prefectureService->getPrefecture($event->getAddress(), $event->getLat(), $event->getLon());
            if ($prefecture) {
                $event->updatePrefectureId($prefecture->getId());
            }
            $this->eventRepository->updateOrCreateEvent($event);
        }
        return new FetchConnpassEventsOutputData(count($events));
    }
}
