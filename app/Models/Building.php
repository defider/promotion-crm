<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'region_id',
        'postcode',
        'district',
        'locality',
        'street',
        'building_number',
    ];

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
