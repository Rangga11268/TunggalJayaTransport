<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Bus extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'plate_number',
        'bus_type',
        'capacity',
        'description',
        'status',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }

    public function drivers()
    {
        return $this->belongsToMany(Driver::class)->withTimestamps();
    }

    public function conductors()
    {
        return $this->belongsToMany(Conductor::class)->withTimestamps();
    }
}
