<?php

namespace App\DataModels;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = ['id'];

    public function events()
    {
        return $this->belongsToMany(Event::class)->withTimestamps();
    }

    public function toDomainModel(): \App\Domain\Models\Event\Tag
    {
        return new \App\Domain\Models\Event\Tag($this->id, $this->name, $this->pattern, $this->icon_url);
    }

}
