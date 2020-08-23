<?php


namespace App\UseCases\GetPlaceConditions;


use App\Domain\Services\SearchConditionService;

class GetPlaceConditionsUseCase implements GetPlaceConditionsUseCaseInterface
{
    private $searchConditionService;

    public function __construct(SearchConditionService $searchConditionService)
    {
        $this->searchConditionService = $searchConditionService;
    }

    public function handle(): GetPlaceConditionsOutputData
    {
        $conditions = $this->searchConditionService->getPlaceConditions();
        return new GetPlaceConditionsOutputData($conditions);
    }
}
