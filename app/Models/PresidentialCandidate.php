<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresidentialCandidate extends Model
{
    protected $fillable = [
        'full_name',
        'date_of_birth',
        'place_of_birth',
        'political_party',
        'national_id',
        'region',
        'email',
        'phone',
        'photo',
    ];
}
