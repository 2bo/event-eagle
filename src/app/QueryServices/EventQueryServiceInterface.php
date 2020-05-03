<?php


namespace App\QueryServices;


interface EventQueryServiceInterface
{
    public function searchEvent(?string $freeText = null, ?array $prefectures = null, ?array $types = null, ?bool $isOnline = null,
                                int $page = 1, int $perPage = 15): PaginateResult;
}
