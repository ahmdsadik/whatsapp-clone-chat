<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvalidChannelLinkException extends Exception
{
    protected $message = 'Invalid channel link';
}
