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

    /**
     * Convert duration from minutes to hours format
     * @return string
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) {
            return 'N/A';
        }

        // Convert minutes to hours and minutes
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$minutes}m";
        }
    }
}
