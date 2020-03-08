<?php


namespace App\UseCases;


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
