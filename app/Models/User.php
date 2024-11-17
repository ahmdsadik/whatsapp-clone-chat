<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\StoryPrivacy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, HasUuids, InteractsWithMedia, Notifiable;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'about',
        'mobile_number',
        'fcm_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'fcm_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fcm_token' => 'encrypted',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->useDisk('users_avatar')
            ->singleFile();
    }

    public function getAvatarAttribute(): string
    {
        return $this->getFirstMediaUrl('avatar') ?? '';
    }

    //################### Relations ####################

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function registeredContacts(): HasMany
    {
        return $this->hasMany(Contact::class)->has('registeredUser');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'created_by')
            ->orderByDesc('created_at');
    }

    public function linkedDevices(): HasMany
    {
        return $this->hasMany(LinkedDevice::class, 'user_id');
    }

    public function stories(): HasMany
    {
        return $this->hasMany(Story::class, 'user_id');
    }

    public function authorizedStories(): HasMany
    {
        return $this->hasMany(Story::class, 'user_id')
            ->where(function (Builder $builder) {
                $builder->where('privacy', StoryPrivacy::ALL_CONTACTS)
                    ->orWhere(function (Builder $builder) {
                        $builder->where('privacy', StoryPrivacy::ALL_CONTACTS_EXCEPT)
                            ->whereDoesntHave('privacyUsers', function (Builder $builder) {
                                $builder->where('user_id', auth()->id());
                            });
                    })
                    ->orWhere(function (Builder $builder) {
                        $builder->where('privacy', StoryPrivacy::ONLY_CONTACTS)
                            ->whereHas('privacyUsers', function (Builder $builder) {
                                $builder->where('user_id', auth()->id());
                            });
                    });
            })
            ->orderBy('created_at')
            ->with('media');
    }

    public function storiesPrivacy(): HasMany
    {
        return $this->hasMany(UserStoryPrivacy::class, 'user_id');
    }
}
