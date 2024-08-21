<?php

namespace App\Actions;

use App\Models\Message;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\FileAdder;

class ProcessMessageMediaAction
{
    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function execute(Message $message): void
    {
        $message->addMultipleMediaFromRequest(['media'])
            ->each(function (FileAdder $fileAdder) {
                $fileAdder->toMediaCollection('media');
            });
    }
}
