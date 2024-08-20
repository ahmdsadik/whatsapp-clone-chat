<?php

namespace App\Models;

use App\Observers\LinkedDeviceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\PersonalAccessToken;

#[ObservedBy(LinkedDeviceObserver::class)]
class LinkedDevice extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'token_id',
        'device_name',
        'channel_name',
        'linked_at',
    ];

    protected function casts(): array
    {
        return [
            'linked_at' => 'datetime',
        ];
    }

    #################### Relations ####################

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function token(): BelongsTo
    {
        return $this->belongsTo(PersonalAccessToken::class, 'token_id');
    }
}



