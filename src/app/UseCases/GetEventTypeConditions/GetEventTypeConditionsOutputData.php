<?php


namespace App\UseCases\GetEventTypeConditions;


class GetEventTypeConditionsOutputData
{
    private $eventTypeConditions;

    public function __construct(array $eventTypeConditions)
    {
        $this->eventTypeConditions = $eventTypeConditions;
    }

    /**
     * @return mixed
     */
    public function getEventTypeConditions(): array
    {
        return $this->eventTypeConditions;
    }

}
