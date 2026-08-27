<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Driver extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('drivers') ?: null;
    }

    protected $fillable = [
        'name',
        'employee_id',
        'license_number',
        'phone',
        'email',
        'address',
        'status',
    ];

    public function buses()
    {
        return $this->belongsToMany(Bus::class)->withTimestamps();
    }
}
