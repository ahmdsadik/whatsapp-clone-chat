<?php

namespace App\Exceptions;

use Exception;

class InvalidOTP extends Exception
{
    protected $message = 'Invalid OTP';
}
