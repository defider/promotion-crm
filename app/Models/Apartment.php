<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'reaction_time',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function reaction()
    {
        return $this->belongsTo(Reaction::class, 'reaction_id', 'reaction_number');
    }
}
