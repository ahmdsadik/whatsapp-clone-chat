<?php

namespace App\DTO;

use Illuminate\Foundation\Http\FormRequest;

class LinkedDeviceDTO extends BaseDTO
{
    public function __construct(
        public string $device_name,
        public string $channel_name,
    ) {}

    public static function fromApiFormRequest(
        string $device_name,
        string $channel_name
    ): self {
        return new self(
            $device_name,
            $channel_name
        );
    }
}
