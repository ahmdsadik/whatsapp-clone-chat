<?php

namespace App\Actions;

use App\Models\Story;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class ProcessStoryMediaAction
{
    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function execute(Story $story): void
    {

        // TODO:: Optimize Image

        $story->addMediaFromRequest('media')
            ->toMediaCollection('media');
    }
}
