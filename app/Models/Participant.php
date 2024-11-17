<?php

namespace App\Models;

use App\Enums\ParticipantRole;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Participant extends Pivot
{
    public $timestamps = false;

    protected $table = 'participants';

    protected $fillable = [
        'conversation_id',
        'user_id',
        'role',
        'join_at',
    ];

    protected function casts(): array
    {
        return [
            'join_at' => 'datetime',
            'role' => ParticipantRole::class,
        ];
    }

    //################### Relations ####################

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
