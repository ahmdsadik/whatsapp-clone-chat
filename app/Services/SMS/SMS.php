<?php

namespace App\Services\SMS;

interface SMS
{
    /**
     * Send SMS
     */
    public function send(string $to, string $message): void;
}
