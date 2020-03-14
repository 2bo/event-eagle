<?php


namespace App\UseCases\FetchConnpassEvents;


class FetchConnpassEventsOutputData
{
    private $numOfEvents;

    public function __construct(int $count)
    {
        $this->numOfEvents = $count;
    }

    /**
     * @return int
     */
    public function getNumOfEvents()
    {
        return $this->numOfEvents;
    }

}
