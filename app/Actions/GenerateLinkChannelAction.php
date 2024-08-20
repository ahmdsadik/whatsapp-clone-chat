<?php

namespace App\Actions;

use Carbon\Carbon;
use Random\RandomException;

class GenerateLinkChannelAction
{
    /**
     * Generate a channel for a minute
     *
     * @throws RandomException
     */
    public function execute(): string
    {
        $random_bytes = bin2hex(random_bytes(16));

        $timestamp = Carbon::now()->addMinute()->timestamp;

        return "{$random_bytes}_{$timestamp}";
    }
}
