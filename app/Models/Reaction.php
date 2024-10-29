<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reaction_number',
        'title',
    ];

    public function apartment()
    {
        return $this->hasOne(Reaction::class, 'reaction_id', 'reaction_number');
    }
}
