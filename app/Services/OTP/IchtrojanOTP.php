<?php

namespace App\Services\OTP;

use App\Services\OTP\OTP as OTPInterface;
use Ichtrojan\Otp\Otp;

class IchtrojanOTP implements OTPInterface
{
    protected Otp $otp;

    public function __construct()
    {
        $this->otp = new Otp();
    }

    /**
     * Generate OTP
     * @throws \Exception
     */
    public function generate(string $identifier, mixed $type = 'numeric', int $length = 6, mixed $expire_time = 10): mixed
    {
        return $this->otp->generate($identifier, $type, $length, $expire_time)?->token;
    }

    /**
     * Validate OTP
     *
     * @param [type] $identifier
     * @param [type] $otp
     * @return mixed
     */
    public function validate($identifier, $otp): mixed
    {
        return $this->otp->validate($identifier, $otp)?->status;
    }
}
