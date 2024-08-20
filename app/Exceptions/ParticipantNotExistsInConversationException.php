<?php

namespace App\Exceptions;

use Exception;

class ParticipantNotExistsInConversationException extends Exception
{
    protected $message = 'Participants not Exists in this conversation';
}
