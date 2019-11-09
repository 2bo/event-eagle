<?php

namespace App\Repositories;

use App\Models\Event;
use Illuminate\Support\Facades\DB;

class EventRepository
{
    public function getModelClass(): string
    {
        return Event::class;
    }

    public function getNewEvents(): array
    {
        $events = DB::table('events')
            ->orderBy('started_at', 'desc')
            ->limit(30)
            ->get()
            ->toArray();
        return $events;
    }
}
