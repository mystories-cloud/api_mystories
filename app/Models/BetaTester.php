<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BetaTester extends Model
{
    public $fillable = [
        'name',
        'email',
        'phone',
        'date',
        'time'
    ];
}
