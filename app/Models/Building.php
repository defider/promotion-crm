<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    public function apartments()
    {
        return $this->hasMany(Apartment::class);
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_number');
    }
}