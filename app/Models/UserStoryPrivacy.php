<?php

namespace App\Models;

use App\Enums\StoryPrivacy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserStoryPrivacy extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'privacy',
    ];

    protected function casts(): array
    {
        return [
            'privacy' => StoryPrivacy::class
        ];
    }

    #################### Relations ####################

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_story_privacy_contacts', 'user_story_privacy_id', 'user_id', 'id', 'id');
    }
}
