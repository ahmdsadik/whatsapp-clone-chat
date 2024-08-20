<?php

namespace App\DTO;

use Illuminate\Foundation\Http\FormRequest;

class LinkedDeviceDTO extends BaseDTO
{
    public function __construct(
        public string $device_name,
        public string $channel_name,
    )
    {
    }

    public static function fromApiFormRequest(FormRequest $request): self
    {
        return new self(
            $request->validated('device_name'),
            $request->validated('channel_name')
        );
    }
}
