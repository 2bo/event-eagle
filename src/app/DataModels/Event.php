<?php

namespace App\DataModels;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public const DOORKEEPER = 'doorkeeper';

    protected $guarded = [
        'id'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }
}
