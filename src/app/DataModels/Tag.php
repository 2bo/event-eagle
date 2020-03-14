<?php

namespace App\DataModels;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = ['id'];

    public function events()
    {
        return $this->belongsToMany('App\Models\Event')->withTimestamps();
    }
}
