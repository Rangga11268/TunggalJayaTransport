<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = [
        'name',
        'origin',
        'destination',
        'distance',
        'duration',
        'description',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
