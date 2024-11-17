<?php

namespace App\Exceptions;

use Exception;

class InvalidChannelLinkException extends Exception
{
    protected $message = 'Invalid channel link';
}
