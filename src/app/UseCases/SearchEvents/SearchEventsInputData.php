<?php


namespace App\UseCases\SearchEvents;


use DateTime;

class SearchEventsInputData
{
    private $from;
    private $to;
    private $keywords;
    private $prefectures;
    private $isOnline;
    private $types;
    private $page;

    public function __construct(?DateTime $from, ?DateTime $to, ?string $keywords, ?array $prefectures,
                                ?array $types, ?bool $isOnline, int $page)
    {
        $this->from = $from;
        $this->to = $to;
        $this->keywords = $keywords;
        $this->prefectures = $prefectures;
        $this->types = $types;
        $this->isOnline = $isOnline;
        $this->page = $page;
    }

    /**
     * @return DateTime|null
     */
    public function getFrom(): ?DateTime
    {
        return $this->from;
    }

    /**
     * @return DateTime|null
     */
    public function getTo(): ?DateTime
    {
        return $this->to;
    }

    /**
     * @return string|null
     */
    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    /**
     * @return array|null
     */
    public function getPrefectures(): ?array
    {
        return $this->prefectures;
    }

    /**
     * @return bool|null
     */
    public function isOnline(): ?bool
    {
        return $this->isOnline;
    }

    /**
     * @return array|null
     */
    public function getTypes(): ?array
    {
        return $this->types;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

}
