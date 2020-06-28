<?php


namespace App\UseCases\ShowEventDetail;


use App\Domain\Models\Event\Event;

class ShowEventDetailOutputData
{
    private $event;

    /**
     * ShowEventDetailOutputData constructor.
     * @param $events
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }

}
