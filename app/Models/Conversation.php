<?php

namespace App\Models;

use App\Enums\ConversationType;
use App\Observers\ConversationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[ObservedBy([ConversationObserver::class])]
class Conversation extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes, HasUuids;

    public $incrementing = false;

    protected $fillable = [
        'label',
        'created_by',
        'type',
        'limit',
    ];

    protected function casts(): array
    {
        return [
            'type' => ConversationType::class,
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->useDisk('group_avatar')
            ->singleFile();
    }

    public function getAvatarAttribute(): string
    {
        return $this->getFirstMediaUrl('avatar') ?? '';
    }

    #################### Relations ####################

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'participants', 'conversation_id', 'user_id')
            ->withPivot(['role', 'join_at'])
            ->as('participants')
            ->using(Participant::class);
    }
}
