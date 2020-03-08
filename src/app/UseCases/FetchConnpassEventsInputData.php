<?php

namespace App\UseCases;


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
