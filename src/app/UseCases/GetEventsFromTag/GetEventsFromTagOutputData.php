<?php


namespace App\UseCases\GetEventsFromTag;


use App\QueryServices\PaginateResult;

class GetEventsFromTagOutputData
{
    private $paginateResult;

    public function __construct(PaginateResult $paginateResult)
    {
        $this->paginateResult = $paginateResult;
    }

    /**
     * @return PaginateResult
     */
    public function getPaginateResult(): PaginateResult
    {
        return $this->paginateResult;
    }

}
