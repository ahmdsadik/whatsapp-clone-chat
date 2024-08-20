<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class StoryPrivacyUser extends Pivot
{
    public $timestamps = false;

    protected $table = 'story_privacy_contacts';

    protected $fillable = [
        'user_id',
        'story_id',
    ];

    #################### Relations ####################

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }
}
