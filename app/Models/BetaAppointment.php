<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BetaAppointment extends Model
{
    public $fillable = [
        'name',
        'email',
        'phone',
        'date',
        'time'
    ];
}
