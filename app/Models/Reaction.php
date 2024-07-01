<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;

    public function apartment()
    {
        return $this->hasOne(Reaction::class, 'reaction_id', 'reaction_number');
    }
}
