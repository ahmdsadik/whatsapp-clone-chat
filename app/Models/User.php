<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, HasApiTokens, InteractsWithMedia;

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
        'fcm_token'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fcm_token' => 'encrypted'
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

    #################### Relations ####################

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function registeredContacts(): HasMany
    {
        return $this->hasMany(Contact::class)->has('registeredUser');
    }

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class, 'participants', 'user_id', 'conversation_id')
            ->withPivot(['role', 'join_at'])
            ->as('info')
            ->using(Participant::class);
    }

    public function linkedDevices(): HasMany
    {
        return $this->hasMany(LinkedDevice::class, 'user_id');
    }

    public function stories(): HasMany
    {
        return $this->hasMany(Story::class, 'user_id');
    }
}
