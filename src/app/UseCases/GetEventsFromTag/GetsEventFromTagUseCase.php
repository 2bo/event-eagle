<?php


namespace App\UseCases\GetEventsFromTag;


use App\QueryServices\GetEventsFromTagQueryServiceInterface;

class GetsEventFromTagUseCase implements GetEventsFromTagUseCaseInterface
{

    private $queryService;

    /**
     * GetsEventFromTagUseCase constructor.
     */
    public function __construct(GetEventsFromTagQueryServiceInterface $queryService)
    {
        $this->queryService = $queryService;
    }

    public function handle(GetEventsFromTagInputData $input): GetEventsFromTagOutputData
    {
        $from = $input->getFrom() ?: new \DateTime('first day of this month');
        $to = $input->getTo() ?: (clone $from)->modify('+3 month');
        $paginateResult = $this->queryService->getEventsFromTag($input->getTagUrlName(), $from, $to, $input->getPage());
        return new GetEventsFromTagOutputData($paginateResult);
    }
}
