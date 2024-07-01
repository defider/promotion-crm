<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    public function buildings()
    {
        return $this->hasMany(Building::class, 'region_id', 'region_number');
    }
}
