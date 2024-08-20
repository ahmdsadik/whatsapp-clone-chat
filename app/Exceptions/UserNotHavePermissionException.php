<?php

namespace App\Exceptions;

use Exception;

class UserNotHavePermissionException extends Exception
{
    protected $message = 'User does not have permission to this action';
}
