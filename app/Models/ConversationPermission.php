<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversationPermission extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'conversation_id',
        'edit_group_settings',
        'send_messages',
        'add_other_members',
    ];

    protected function casts(): array
    {
        return [
            'conversation_id' => 'bool',
            'edit_group_settings' => 'bool',
            'send_messages' => 'bool',
            'add_other_members' => 'bool',
        ];
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }
}
