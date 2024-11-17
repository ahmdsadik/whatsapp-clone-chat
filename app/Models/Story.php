<?php

namespace App\Models;

use App\Enums\StoryPrivacy;
use App\Enums\StoryType;
use App\Observers\StoryObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[ObservedBy(StoryObserver::class)]
class Story extends Model implements HasMedia
{
    use HasUuids, InteractsWithMedia;

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'type',
        'privacy',
        'duration',
        'text',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'type' => StoryType::class,
            'privacy' => StoryPrivacy::class,
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('media')
            ->useDisk('story_media')
            ->singleFile();
    }

    public function getMediaFileAttribute(): ?\Spatie\MediaLibrary\MediaCollections\Models\Media
    {
        return $this->getFirstMedia('media');
    }

    //################### Relations ####################

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function privacyUsers(): HasMany
    {
        return $this->hasMany(StoryPrivacyUser::class, 'story_id');
    }

    public function hasViews(): HasMany
    {
        return $this->hasMany(StoryView::class, 'story_id');
    }

    public function viewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'story_views', 'story_id', 'user_id')
            ->withPivot(['viewed_at'])
            ->as('info')
            ->using(StoryView::class);
    }

    //    public function privacyContacts(): HasMany
    //    {
    //        return $this->hasMany();
    //    }

    public function privacyContacts(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'story_privacy_contacts', 'story_id', 'user_id');
    }
}
