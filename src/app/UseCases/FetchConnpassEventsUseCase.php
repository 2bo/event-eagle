<?php


namespace App\UseCases;


use App\Repositories\EventRepository;
use App\Services\PrefectureService;

class FetchConnpassEventsUseCase implements FetchConnpassEventsUseCaseInterface
{
    private $connpassEventRepository;
    //FIXME interface化する
    private $prefectureService;
    private $eventRepository;

    public function __construct(ConnpassEventRepositoryInterface $connpassEventRepository,
                                PrefectureService $prefectureService,
                                EventRepository $eventRepository)
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
            $event->updatePrefectureId($prefecture);
            $this->eventRepository->updateOrCreateEvent($event);
        }
        return  new FetchConnpassEventsOutputData(count($events));
    }
}
