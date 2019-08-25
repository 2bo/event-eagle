<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function workshops()
    {
        return $this->belongsToMany(Workshop::class);
    }
}
