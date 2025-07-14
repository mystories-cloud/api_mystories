<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRegistrationLog extends Model
{
    use HasFactory;

    const KEYS = ['tenant', 'verified', 'subscription', 'payment', 'job', 'migration', 'database', 'seed', 'records', 'domain', 'progress_page', 'completion_email', 'completed'];

    public $fillable = [
        'user_id',
        'key',
        'value',
        'log'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
