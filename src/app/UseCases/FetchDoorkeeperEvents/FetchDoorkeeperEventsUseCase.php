<?php


namespace App\UseCases\FetchDoorkeeperEvents;


use App\Domain\Models\Event\DoorkeeperEventRepositoryInterface;
use App\Domain\Models\Event\EventRepositoryInterface;
use App\Domain\Services\PrefectureService;

class FetchDoorkeeperEventsUseCase implements FetchDoorkeeperEventsUseCaseInterface
{
    private $doorkeeperEventRepository;
    private $eventRepository;
    private $prefectureService;

    public function __construct(EventRepositoryInterface $eventRepository,
                                DoorkeeperEventRepositoryInterface $doorkeeperEventRepository,
                                PrefectureService $prefectureService)
    {
        $this->eventRepository = $eventRepository;
        $this->doorkeeperEventRepository = $doorkeeperEventRepository;
        $this->prefectureService = $prefectureService;
    }

    public function handle(FetchDoorkeeperEventsInputData $input): FetchDoorkeeperEventsOutputData
    {
        $events = $this->doorkeeperEventRepository->fetchEvents($input->getSince(), $input->getUntil());

        foreach ($events as $event) {
            $prefecture = $this->prefectureService->getPrefecture($event->getAddress(), $event->getLat(), $event->getLon());

            if ($prefecture) {
                $event->updatePrefectureId($prefecture->getId());
            }

            $this->eventRepository->updateOrCreateEvent($event);
        }
        return new FetchDoorkeeperEventsOutputData(count($events));
    }
}
