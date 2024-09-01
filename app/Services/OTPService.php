<?php

namespace App\Services;

use App\Exceptions\InvalidOTP;
use App\Models\User;
use App\Services\OTP\OTP;
use App\Services\SMS\SMS;

readonly class OTPService
{
    public function __construct(
        private SMS $sms,
        private OTP $otp,
    )
    {
    }

    private function generateOtp($identifier): string
    {
        return $this->otp->generate($identifier);
    }

    public function verifyOTP(string $identifier, string $otp): array
    {

        $user = User::firstWhere('mobile_number', $identifier);

        if (!$this->otp->validate($identifier, $otp)) {
            throw new InvalidOTP();
        }

        $token = $user->createToken('Laravel Password Grant Client')->plainTextToken;

        return [$user, $token];
    }

    public function sendOTP(string $to): void
    {
        $otp = $this->generateOtp($to);

        $this->sms->send($to, $otp);
    }
}