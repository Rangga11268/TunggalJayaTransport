<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Bus extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'plate_number',
        'bus_type',
        'bus_category',
        'capacity',
        'description',
        'status',
        'year',
    ];

    protected $appends = ['image_url'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function charterBookings()
    {
        return $this->hasMany(CharterBooking::class, 'assigned_bus_id');
    }

    public function drivers()
    {
        return $this->belongsToMany(Driver::class)->withTimestamps();
    }

    public function conductors()
    {
        return $this->belongsToMany(Conductor::class)->withTimestamps();
    }

    public function getImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('buses');
    }
}
