<?php

namespace App\Http\Controllers;

use App\Models\Social;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class SocialProvider extends Controller
{
    public mixed $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function google(Request $request): RedirectResponse
    {
        $this->createSession($request, 'google');

        return Socialite::driver('google')->redirect();
    }

    public function github(Request $request): RedirectResponse
    {
        $this->createSession($request, 'github');

        return Socialite::driver('github')->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        try {
            $socialite = Socialite::driver($provider)->user();

            if ($this->user) {
                if ($this->user->passwordless) {
                    throw new Exception('Please create password first before you can manage linking social accounts.');
                }

                $socialAccountById = Social::getUserBySocialAccountId($provider, $socialite->id);

                if ($socialAccountById->first()) {
                    $socialAccountById->delete();

                    Session::regenerate();

                    return $this->redirectBack();
                }

                $socialAccountByEmail = Social::getUserBySocialAccoutEmail($provider, $socialite->email);

                if ($socialAccountByEmail->first()) {
                    throw new Exception('Can\'t unlink social account with different email address.');
                }

                $socialAccontsByUserId = Social::getUserBySocialUserId($provider, $this->user->id);

                if ($socialAccontsByUserId->first()) {
                    throw new Exception('Can\'t unlink social account with different email address.');
                }

                $socialData = [
                    'user_id' => $this->user->id,
                    'provider' => $provider,
                    'name' => $socialite->name,
                    'email' => $socialite->email
                ];

                $this->updateOrCreateSocialAccount($socialData, id: $socialite->id);

                Session::regenerate();

                return $this->redirectBack();
            }

            $loginUsingSocialAccount = Social::getUserBySocialAccountsId($provider, $socialite->id)->first();

            if ($loginUsingSocialAccount) {
                $user = User::where('id', $loginUsingSocialAccount->user_id)->first();

                Auth::loginUsingId($user->id, true);

                Session::regenerate();

                return $this->redirectBack();
            }

            $findUserByEmail = User::where('email', $socialite->email)->first();

            if (!$findUserByEmail) {
                $userData = [
                    'name' => $socialite->name,
                    'username' => 'u' . Carbon::now()->setMicrosecond(0)->timestamp,
                    'avatar' => 'https://gravatar.com/avatar/' . hash('sha256', strtolower(trim($socialite->email))),
                    'passwordless' => true
                ];

                $user = $this->updateOrCreateUserByEmail($userData, $socialite->email);

                $user
                    ->forceFill([
                        'email_verified_at' => Carbon::now()
                    ])
                    ->save();

                $socialData = [
                    'provider' => $provider,
                    'user_id' => $user->id,
                    'name' => $socialite->name,
                    'email' => $socialite->email
                ];

                $this->updateOrCreateSocialAccount($socialData, id: $socialite->id);

                Auth::loginUsingId($user->id, true);

                Session::regenerate();

                return $this->redirectBack();
            }

            throw new Exception('Your ' . $provider . " account isn't connected to your profile yet.");
        } catch (Exception $error) {
            Session::flash('error', $error->getMessage());

            return Redirect::route($this->user ? 'profile' : 'login');
        }
    }

    private function updateOrCreateSocialAccount(array $data, ?string $id = null, ?string $email = null): mixed
    {
        if (!$id && !$email) {
            throw new Exception('Invalid request data');
        }

        return Social::updateOrCreate(
            $id
                ? [
                    'provider_id' => $id
                ]
                : [
                    'email' => $email
                ],
            $data
        );
    }

    private function updateOrCreateUserByEmail(array $data, string $email): User
    {
        return User::updateOrCreate(
            [
                'email' => $email
            ],
            $data
        );
    }

    private function createSession(Request $request): void
    {
        $isFromProfile = $request->header('referer') == route('profile');
        Session::put('url.socialite', route($isFromProfile ? 'profile' : 'home'));
    }

    private function redirectBack(): RedirectResponse
    {
        $intended = Session::get('url.intended');

        if ($intended && $intended != route('passport.home')) {
            return Redirect::to($intended);
        }

        $socialite = Session::get('url.socialite');

        if ($socialite) {
            return Redirect::to($socialite);
        }

        return Redirect::route('profile');
    }
}
