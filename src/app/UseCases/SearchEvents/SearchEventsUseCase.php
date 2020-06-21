<?php


namespace App\UseCases\SearchEvents;


use App\QueryServices\EventQueryServiceInterface;
use DateTime;

class SearchEventsUseCase implements SearchEventsUseCaseInterface
{
    private $queryService;

    public function __construct(EventQueryServiceInterface $queryService)
    {
        $this->queryService = $queryService;
    }

    public function handle(SearchEventsInputData $input): SearchEventsOutputData
    {
        $from = $input->getFrom() ?: new DateTime();
        $to = $input->getTo() ?: (clone $from)->modify('+3 month');
        $paginateResult = $this->queryService->searchEvent(
            $from,
            $to,
            $input->getKeywords(),
            $input->getPrefectures(),
            $input->getTypes(),
            $input->isOnline(),
            $input->getPage(),
            );
        return new SearchEventsOutputData($paginateResult);
    }
}
