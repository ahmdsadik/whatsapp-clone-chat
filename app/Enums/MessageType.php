<?php

namespace App\Enums;

use App\Traits\EnumHelpers;

enum MessageType: string
{
    use EnumHelpers;

    case TEXT = 'text';
    case VIDEO_TEXT = 'video_text';
    case IMAGE_TEXT = 'image_text';
    case AUDIO = 'audio';
    case DOCUMENT = 'document';
    case CONTACT = 'contact';
    case LINK = 'link';
    case PHONE = 'phone';
    case DELETED = 'deleted';
}

