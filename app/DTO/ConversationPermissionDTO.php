<?php

namespace App\DTO;

use App\DTO\BaseDTO;

class ConversationPermissionDTO extends BaseDTO
{
    public function __construct(
        public bool $edit_group_settings,
        public bool $send_messages,
        public bool $add_other_members,
    ) {}

    public static function fromFormRequest(
        bool $edit_group_settings,
        bool $send_messages,
        bool $add_other_members
    ): self {
        return new self($edit_group_settings, $send_messages, $add_other_members);
    }
}
