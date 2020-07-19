<?php


namespace App\UseCases\GetEventsFromTag;


class GetEventsFromTagInputData
{
    private $tagUrlName;
    private $from;
    private $to;

    /**
     * GetEventsFromTagInputData constructor.
     * @param $tagUrlName
     */
    public function __construct(string $tagUrlName, ?\DateTime $from = null, ?\DateTime $to = null)
    {
        $this->tagUrlName = $tagUrlName;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return string
     */
    public function getTagUrlName(): string
    {
        return $this->tagUrlName;
    }

    /**
     * @return \DateTime|null
     */
    public function getFrom(): ?\DateTime
    {
        return $this->from;
    }

    /**
     * @return \DateTime|null
     */
    public function getTo(): ?\DateTime
    {
        return $this->to;
    }

}
