<?php


namespace App\QueryServices;

use DateTime;

interface EventQueryServiceInterface
{
    public function searchEvent(?DateTime $from = null, ?DateTime $to = null, ?string $freeText = null, ?array $prefectures = null, ?array $types = null, ?bool $isOnline = null,
                                int $page = 1, int $perPage = 15): PaginateResult;
}
