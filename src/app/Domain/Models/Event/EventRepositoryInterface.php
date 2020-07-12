<?php

namespace App\Domain\Models\Event;

interface EventRepositoryInterface
{
    public function findAll(): array;

    public function updateOrCreateEvent(Event $event): Event;

    public function findById(int $id): ?Event;
}
