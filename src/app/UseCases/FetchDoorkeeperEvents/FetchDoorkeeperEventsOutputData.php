<?php


namespace App\UseCases\FetchDoorkeeperEvents;


class FetchDoorkeeperEventsOutputData
{
    private $numOfEvents;

    public function __construct(int $numOfEvents)
    {
        $this->numOfEvents = $numOfEvents;
    }

    /**
     * @return int
     */
    public function getNumOfEvents(): int
    {
        return $this->numOfEvents;
    }
}
