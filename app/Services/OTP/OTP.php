<?php

namespace App\Services\OTP;

interface OTP
{
    /**
     * Generate OTP
     *
     * @param string $identifier
     * @param mixed $type
     * @param integer $length
     * @param mixed $expire_time
     * @return mixed
     */
    public function generate(string $identifier, mixed $type, int $length, mixed $expire_time): mixed;

    /**
     * Validate OTP
     *
     * @param string $identifier
     * @param [type] $otp
     * @return mixed
     */
    public function validate(string $identifier, $otp): mixed;
}
