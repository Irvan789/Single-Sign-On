<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Contracts\User;
use Laravel\Socialite\Socialite;
use Laravel\Socialite\Two\AbstractProvider;

class SocialiteRequest extends FormRequest
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
        return [
            //
        ];
    }

    public function redirect(): RedirectResponse
    {
        /** @var AbstractProvider $socialite */
        $socialite = Socialite::driver($this->provider);

        return $socialite->with([
            'prompt' => 'consent',
        ])->redirect();
    }

    public function authenticate(): User
    {
        $socialite = Socialite::driver($this->provider)->user();

        return $socialite;
    }
}
