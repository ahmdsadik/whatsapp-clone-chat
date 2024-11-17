<?php

namespace App\Services\OTP;

interface OTP
{
    /**
     * Generate OTP
     */
    public function generate(string $identifier, mixed $type, int $length, mixed $expire_time): mixed;

    /**
     * Validate OTP
     *
     * @param [type] $otp
     */
    public function validate(string $identifier, $otp): mixed;
}
