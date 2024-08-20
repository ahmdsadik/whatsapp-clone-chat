<?php

namespace App\Enums;

enum ConversationPermission: string
{
    case EDIT_GROUP_SETTINGS = 'edit_group_settings';
    case SEND_MESSAGES = 'send_messages';
    case ADD_OTHER_PARTICIPANTS = 'add_other_members';
}
