<?php


namespace App\UseCases\SearchEvents;


use App\QueryServices\EventQueryServiceInterface;

class SearchEventsUseCase implements SearchEventsUseCaseInterface
{
    private $queryService;

    public function __construct(EventQueryServiceInterface $queryService)
    {
        $this->queryService = $queryService;
    }

    public function handle(SearchEventsInputData $input): SearchEventsOutputData
    {
        $paginateResult = $this->queryService->searchEvent(
            $input->getKeywords(),
            $input->getPrefectures(),
            $input->getTypes(),
            $input->isOnline(),
            $input->getPage(),
        );
        return new SearchEventsOutputData($paginateResult);
    }
}
