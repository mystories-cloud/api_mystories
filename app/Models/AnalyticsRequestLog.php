<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsRequestLog extends Model
{
    protected $connection = 'analytics_connection';
    
    public $fillable = [
        'url',
        'method',
        'body',
        'method',
        'exception'
    ];


}
