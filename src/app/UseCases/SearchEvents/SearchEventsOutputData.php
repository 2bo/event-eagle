<?php


namespace App\UseCases\SearchEvents;

use App\QueryServices\PaginateResult;

class SearchEventsOutputData
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
