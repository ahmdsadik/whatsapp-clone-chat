<?php

namespace App\Services\OTP;

interface OTP
{
    public function generate(string $identifier, mixed $type, int $length, mixed $expire_time): mixed;

    public function validate(string $identifier, $otp): mixed;
}
