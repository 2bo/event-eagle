<?php


namespace App\UseCases\GetEventTypeConditions;


use App\Domain\Services\SearchConditionService;

class GetEventTypeConditionsUseCase implements GetEventTypeConditionsUseCaseInterface
{
    private $searchConditionService;

    public function __construct(SearchConditionService $searchConditionService)
    {
        $this->searchConditionService = $searchConditionService;
    }

    public function handle(): GetEventTypeConditionsOutputData
    {
        $conditions = $this->searchConditionService->getEventTypeConditions();
        return new GetEventTypeConditionsOutputData($conditions);
    }
}
