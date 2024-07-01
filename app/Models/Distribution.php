<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory;

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function leaflet()
    {
        return $this->belongsTo(Leaflet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
