<?php

namespace App\UseCases\FetchConnpassEvents;


class FetchConnpassEventsInputData
{
    private $yearMonth;

    public function __construct(string $yearMonth)
    {
        $this->yearMonth = $yearMonth;
    }

    /**
     * @return string|null
     */
    public function getYearMonth(): ?string
    {
        return $this->yearMonth;
    }

}
