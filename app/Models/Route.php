<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = [
        'name',
        'origin',
        'destination',
        'origin_lat',
        'origin_lng',
        'destination_lat',
        'destination_lng',
        'waypoints',
        'distance',
        'duration',
        'description',
    ];

    protected $casts = [
        'waypoints' => 'array',
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

    /**
     * Get all coordinates for the route including origin, waypoints, and destination
     * @return array
     */
    public function getAllCoordinatesAttribute()
    {
        $coordinates = [];
        
        // Add origin
        if ($this->origin_lat && $this->origin_lng) {
            $coordinates[] = [
                'lat' => (float) $this->origin_lat,
                'lng' => (float) $this->origin_lng
            ];
        }
        
        // Add waypoints
        if ($this->waypoints && is_array($this->waypoints)) {
            foreach ($this->waypoints as $waypoint) {
                if (isset($waypoint['lat']) && isset($waypoint['lng'])) {
                    $coordinates[] = [
                        'lat' => (float) $waypoint['lat'],
                        'lng' => (float) $waypoint['lng']
                    ];
                }
            }
        }
        
        // Add destination
        if ($this->destination_lat && $this->destination_lng) {
            $coordinates[] = [
                'lat' => (float) $this->destination_lat,
                'lng' => (float) $this->destination_lng
            ];
        }
        
        return $coordinates;
    }
}
