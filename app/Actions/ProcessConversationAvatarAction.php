<?php

namespace App\Actions;

use App\Models\Conversation;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class ProcessConversationAvatarAction
{
    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function execute(Conversation $conversation): void
    {
        $conversation->addMediaFromRequest('avatar')
            ->toMediaCollection('avatar');
    }
}
