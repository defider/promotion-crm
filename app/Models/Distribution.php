<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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
        'began_at',
        'ended_at',
    ];

    protected $casts = [
        'began_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function apartments(): HasManyThrough
    {
        return $this->hasManyThrough(
            Apartment::class,
            Building::class,
            'id',
            'building_id',
            'building_id',
            'id'
        );
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function leaflet(): BelongsTo
    {
        return $this->belongsTo(Leaflet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
