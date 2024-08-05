<?php

namespace App\Http\Requests\Story;

use Illuminate\Foundation\Http\FormRequest;

class DeleteStoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->story?->user_id === auth()->id();
    }

    public function rules(): array
    {
        return [

        ];
    }
}
