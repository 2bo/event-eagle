<?php

namespace App\DataModels;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [
        'id'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function types()
    {
        return $this->belongsToMany(EventType::class)->withTimestamps();
    }
}
