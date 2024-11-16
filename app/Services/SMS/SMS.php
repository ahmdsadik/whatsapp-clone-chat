<?php

namespace App\Services\SMS;

interface SMS
{
    /**
     * Send SMS
     *
     * @param string $to
     * @param string $message
     * @return void
     */
    public function send(string $to, string $message): void;
}
