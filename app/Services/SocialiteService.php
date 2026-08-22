<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\SocialRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Random\Randomizer;

class SocialiteService
{
    public function __construct(
        protected SocialRepository $socialRepository,
        protected UserRepository $userRepository,
    ) {}

    public function authenticate(string $provider, SocialiteUser $socialite, ?User $user): void
    {
        $social = [
            'provider' => $provider,
            'name' => $socialite->getName(),
            'email' => $socialite->getEmail(),
        ];

        if ($user) {
            if ($user->passwordless) {
                throw new Exception('Please create password first before you can manage linking social accounts.');
            }

            $socialByProviderNameAndEmail = $this->socialRepository->findByProviderNameAndEmail($provider, $socialite->getEmail());

            if ($socialByProviderNameAndEmail) {
                if ($user->id == $socialByProviderNameAndEmail->user_id) {
                    $socialByProviderNameAndEmail->delete();

                    return;
                }

                throw new Exception('Failed to link or unlink social account in your account.');
            }

            $socialByProviderNameAndUserId = $this->socialRepository->findByProviderNameAndUserId($provider, $user->id);

            if ($socialByProviderNameAndUserId) {
                throw new Exception('Failed to link or unlink social account in your account.');
            }

            $social['user_id'] = $user->id;

            $this->socialRepository->updateOrCreate($social, ['provider_id' => $socialite->getId()]);

            return;
        }

        $loginUsingSocialAccount = $this->socialRepository->findByProviderId($socialite->getId());

        if ($loginUsingSocialAccount) {
            $user = $this->userRepository->findById($loginUsingSocialAccount->user_id);

            Auth::loginUsingId($user->id, true);

            Session::regenerate();

            return;
        }

        $findUserByEmail = $this->userRepository->findByEmail($socialite->getEmail());

        if ($findUserByEmail) {
            throw new Exception('Your '.$provider." account isn't connected to your profile yet.");
        }

        $randomizer = new Randomizer;

        $user = [
            'name' => $socialite->getName(),
            'username' => strtolower(trim(explode('@', $socialite->email)[0])).'_'.$randomizer->getInt(1000, 9999),
            'avatar' => 'https://gravatar.com/avatar/'.hash('sha256', strtolower(trim($socialite->getEmail()))),
            'passwordless' => true,
        ];

        $user = $this->userRepository->updateOrCreate($user, ['email' => $socialite->getEmail()]);

        $user->forceFill([
            'email_verified_at' => Carbon::now(),
        ])->save();

        $social['user_id'] = $user->id;

        $this->socialRepository->updateOrCreate($social, ['provider_id' => $socialite->getId()]);

        Auth::loginUsingId($user->id, true);

        Session::regenerate();
    }
}
