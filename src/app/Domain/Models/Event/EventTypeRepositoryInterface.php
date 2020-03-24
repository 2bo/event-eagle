<?php


namespace App\Domain\Models\Event;


interface EventTypeRepositoryInterface
{
    public function all(): array;
}
