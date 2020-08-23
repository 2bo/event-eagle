<?php


namespace App\Domain\Models\Event;


interface DoorkeeperEventRepositoryInterface
{
    public function fetchEvents(\DateTime $since, \DateTime $until, int $page = 1): array;
}
