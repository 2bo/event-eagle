<?php


namespace App\UseCases\FetchDoorkeeperEvents;


class FetchDoorkeeperEventsInputData
{
    private $since;
    private $until;

    public function __construct(\DateTime $since, \DateTime $until)
    {
        $this->since = $since;
        $this->until = $until;
    }

    /**
     * @return \DateTime
     */
    public function getSince(): \DateTime
    {
        return $this->since;
    }

    /**
     * @return \DateTime
     */
    public function getUntil(): \DateTime
    {
        return $this->until;
    }
}
