<?php

namespace App\Services\SMS;

interface SMS
{
    public function send(string $to, string $message): void;
}
