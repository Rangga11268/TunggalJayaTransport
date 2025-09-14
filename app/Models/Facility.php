<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'description',
    ];

    public function buses()
    {
        return $this->belongsToMany(Bus::class);
    }
}
