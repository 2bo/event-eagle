<?php


namespace App\QueryServices;


interface GetEventsFromTagQueryServiceInterface
{
    public function getEventsFromTag(string $tagUrlName, \DateTime $from = null, \DateTime $to = null): PaginateResult;
}
