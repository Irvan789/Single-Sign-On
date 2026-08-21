<?php

namespace App\Concerns;

use Illuminate\Contracts\Validation\ValidationRule;

trait PassportValidationRules
{
    /**
     * @return array<string, array<int, ValidationRule|array<mixed>|string>>
     */
    protected function passportRules(): array
    {
        return [
            'name' => ['required', 'string'],
            'callbacks' => ['required', 'array'],
            'callbacks.*' => ['required', 'string', 'url'],
        ];
    }
}
