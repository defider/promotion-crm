<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Apartment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'building_id',
        'number',
        'reaction_id',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Apartment $apartment) {
            if ($apartment->reaction_id == null || 1) {
                $apartment->reaction_time = null;
            } else {
                $apartment->reaction_time = now();
            }
        });

        static::updating(function (Apartment $apartment) {
            if ($apartment->reaction_id == 1) {
                $apartment->reaction_time = null;
            } else {
                $apartment->reaction_time = now();
            }
        });
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function reaction(): BelongsTo
    {
        return $this->belongsTo(Reaction::class, 'reaction_id', 'number');
    }
}
