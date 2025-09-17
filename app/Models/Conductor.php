<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Conductor extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'employee_id',
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