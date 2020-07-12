<?php


namespace App\Domain\Models\Event;


interface EventTypeRepositoryInterface
{
    public function all(): array;

    public function findById(int $id): ?EventType;
}
