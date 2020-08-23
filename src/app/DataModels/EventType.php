<?php

namespace App\DataModels;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    public function events()
    {
        return $this->belongsToMany(Event::class)->withTimestamps();
    }

    public function toDomainModel(): \App\Domain\Models\Event\EventType
    {
        return new \App\Domain\Models\Event\EventType($this->id, $this->name, $this->needle);
    }
}
