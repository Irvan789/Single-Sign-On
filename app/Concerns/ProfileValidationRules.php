<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Validation\Rule;

trait ProfileValidationRules
{
    /**
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function profileRules(?string $userId = null): array
    {
        return [
            'name' => $this->nameRules(),
            'username' => $this->usernameRules($userId),
            'email' => $this->emailRules($userId)
        ];
    }

    /**
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function profileRulesErrorMessage(): array
    {
        return [
            'name.regex' => 'The name field must only contain letters, numbers, and spaces.',
            'username.regex' => 'The username field must only contain letters, numbers, and underscores.'
        ];
    }

    /**
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function nameRules(): array
    {
        return ['required', 'string', 'regex:/^[\pL\pN\s]+$/', 'max:50'];
    }

    /**
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function usernameRules(?string $userId = null): array
    {
        return [
            'required',
            'string',
            'lowercase',
            'regex:/^[a-z0-9_]+$/',
            'max:50',
            $userId === null 
                ? Rule::unique(User::class) 
                : Rule::unique(User::class)->ignore($userId)
        ];
    }

    /**
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function emailRules(?string $userId = null): array
    {
        return [
            'required',
            'string',
            'lowercase',
            'email',
            'max:50',
            $userId === null 
                ? Rule::unique(User::class) 
                : Rule::unique(User::class)->ignore($userId)
        ];
    }
}
