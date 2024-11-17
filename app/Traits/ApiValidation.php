<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

trait ApiValidation
{
    use ApiResponse;

    /**
     * Handle a failed validation attempt.
     *
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $response = $this->validationErrorResponse($validator->errors()->all());
        throw new ValidationException($validator, $response);
    }
}
