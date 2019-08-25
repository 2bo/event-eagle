<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    protected $guarded = [
        'id'
    ];

    public function tags()
    {
        $this->belongsToMany(Tag::class);
    }
}
