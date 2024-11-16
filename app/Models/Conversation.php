<?php

namespace App\Models;

use App\Enums\ConversationPermission as ConversationPermissionEnum;
use App\Enums\ConversationType;
use App\Enums\ParticipantRole;
use App\Observers\ConversationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'description',
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
            ->useDisk('conversations_avatar')
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
        return $this->belongsToMany(User::class, 'participants', 'conversation_id', 'user_id', 'id', 'id')
            ->withPivot(['role', 'join_at'])
            ->as('info')
            ->using(Participant::class)
            ->orderBy('role');
    }

    public function hasParticipants(): HasMany
    {
        return $this->hasMany(Participant::class, 'conversation_id');
    }

    public function permissions(): HasOne
    {
        return $this->hasOne(ConversationPermission::class, 'conversation_id');
    }

    public function previousParticipants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_previous_participants', 'conversation_id', 'user_id', 'id', 'id')
            ->withPivot(['left_at'])
            ->as('info')
            ->withCasts(['left_at' => 'datetime']);
    }

    public function oldestParticipant(): HasOne
    {
        return $this->hasOne(Participant::class, 'conversation_id')
            ->where('role', ParticipantRole::MEMBER)
            ->orderBy('join_at');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'conversation_id')
            ->orderBy('created_at');
    }

    public function lastMessage(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    /**
     * Check if a given user is the owner of the  conversation
     *
     * @param $user_id
     * @return bool
     */
    public function isOwner($user_id): bool
    {
        return $this->created_by === $user_id;
    }

    /**
     * Check if a given user is admin or owner in conversation
     *
     * @param $user_id
     * @return bool
     */
    public function isAdmin($user_id): bool
    {
        return $this->hasParticipants()
            ->where(function (Builder $query) {
                $query->where('role', ParticipantRole::OWNER)
                    ->orWhere('role', ParticipantRole::ADMIN);
            })
            ->where('user_id', $user_id)
            ->exists();
    }

    /**
     * Check if a given users are admins in conversation
     *
     * @param $users_ids
     * @return bool
     */
    public function areAdmins($users_ids): bool
    {
        return $this->hasParticipants()
                ->where(function (Builder $query) {
                    $query->where('role', ParticipantRole::ADMIN);
                })
                ->whereIn('user_id', $users_ids)
                ->count() === count($users_ids);
    }

    /**
     * Check if Conversation has participants with role admin
     *
     * @return bool
     */
    public function hasAdmins(): bool
    {
        return $this->hasParticipants()
                ->where(function (Builder $query) {
                    $query->where('role', ParticipantRole::ADMIN);
                })->count() > 0;
    }

    /**
     * Check if these ids of participants are in the conversation participants
     *
     * @param array $participant_ids
     * @return bool
     */
    public function isParticipant(array $participant_ids): bool
    {
        return $this->hasParticipants()
                ->whereIn('user_id', $participant_ids)
                ->count() === count($participant_ids);
    }

    /**
     * Check if user can give specific role
     *
     * @param $participant_id
     * @param ParticipantRole $role
     * @return bool
     */
    public function userCanAssignRole($participant_id, ParticipantRole $role): bool
    {
        return $this->hasParticipants()
            ->where('user_id', $participant_id)
            ->where('role', '<=', $role->value)
            ->exists();
    }

    /**
     * Check if the conversation allowing specific permission
     *
     * @param ConversationPermissionEnum $permission
     * @return bool
     */
    public function isAllowing(ConversationPermissionEnum $permission): bool
    {
        if ($this->type === ConversationType::ONE_TO_ONE) {
            return true;
        }

        return $this->permissions()->where($permission->value, true)->exists();
    }

    /**
     * Make a given participant admin
     *
     * @param $participant_id
     * @return void
     */
    public function makeAdmin($participant_id): void
    {
        $this->participants()->updateExistingPivot($participant_id,
            [
                'role' => ParticipantRole::ADMIN
            ]);
    }
}
