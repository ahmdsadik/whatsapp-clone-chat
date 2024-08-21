<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Message extends Model implements HasMedia
{
    use InteractsWithMedia, HasUuids;

    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'conversation_id',
        'text',
        'type',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime'
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('media')
            ->useDisk('messages');
    }

    #################### Relations ####################

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function views(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'message_views', 'message_id', 'user_id')
            ->orderBy('created_at');
    }
}
