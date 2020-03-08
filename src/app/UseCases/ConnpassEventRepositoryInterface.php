<?php


namespace App\UseCases;


interface ConnpassEventRepositoryInterface
{
    public function fetchEvents(string $ym, int $start = 1): array;
}
