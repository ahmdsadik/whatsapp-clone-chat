<?php

namespace App\Services\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomPathGenerator implements PathGenerator
{
    /**
     * Get path for media
     *
     * @param Media $media
     * @return string
     */
    public function getPath(Media $media): string
    {
        return md5($media->id  . config('app.key')) . '/';
    }

    /**
     * Get path for conversions
     *
     * @param Media $media
     * @return string
     */
    public function getPathForConversions(Media $media): string
    {
        return md5($media->id  . config('app.key')) . '/conversions/';
    }

    /**
     * Get path for responsive images
     *
     * @param Media $media
     * @return string
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return md5($media->id  . config('app.key')) . '/responsive-images/';
    }
}
