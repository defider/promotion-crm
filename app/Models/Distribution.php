<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'building_id',
        'leaflet_id',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Distribution $distribution) {
            $distribution->began_at = now();
        });
    }

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
