<?php


namespace App\Domain\Models\Event;


interface ConnpassEventRepositoryInterface
{
    public function fetchEvents(string $ym, int $start = 1): array;
}
