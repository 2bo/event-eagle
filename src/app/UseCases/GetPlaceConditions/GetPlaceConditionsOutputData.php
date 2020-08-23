<?php


namespace App\UseCases\GetPlaceConditions;


class GetPlaceConditionsOutputData
{
    private $placeConditions;

    public function __construct(array $placeConditions)
    {
        $this->placeConditions = $placeConditions;
    }

    /**
     * @return mixed
     */
    public function getPlaceConditions(): array
    {
        return $this->placeConditions;
    }

}
