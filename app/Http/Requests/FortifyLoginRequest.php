<?php

namespace App\Http\Requests;

use App\Rules\CloudflareTurnstile;
use Illuminate\Contracts\Validation\ValidationRule;
use Laravel\Fortify\Http\Requests\LoginRequest;

class FortifyLoginRequest extends LoginRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'captcha' => ['required', new CloudflareTurnstile],
        ]);
    }
}
