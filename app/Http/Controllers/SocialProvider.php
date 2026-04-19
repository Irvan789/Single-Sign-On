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
        $this->session($request, 'google');

        return Socialite::driver('google')->redirect();
    }

    public function github(Request $request): RedirectResponse
    {
        $this->session($request, 'github');

        return Socialite::driver('github')->redirect();
    }

    public function callback(string $provider, Request $request): RedirectResponse
    {
        try {
            $socialite = Socialite::driver($provider)->user();

            if ($this->user) {
                if ($this->user->passwordless) {
                    throw new Exception('Please create password first before you can manage linking social accounts.');
                }

                $userSocialAccount = Social::where([
                    'user_id' => $this->user->id,
                    'provider' => $provider,
                    'provider_id' => (string) $socialite->id
                ]);

                if ($userSocialAccount->first()) {
                    $userSocialAccount->delete();

                    Session::regenerate();

                    return $this->redirect();
                }

                $findSocialAccountByEmail = Social::where([
                    'provider' => $provider,
                    'email' => $socialite->email
                ])->first();

                if ($findSocialAccountByEmail) {
                    throw new Exception('Error while processing your request');
                }

                $findSocialAccountByUserId = Social::where([
                    'provider' => $provider,
                    'user_id' => $this->user->id
                ])->first();

                if ($findSocialAccountByUserId) {
                    throw new Exception('Error while processing your request');
                }

                $userData = [
                    'user_id' => $this->user->id,
                    'provider' => $provider,
                    'name' => $socialite->name,
                    'email' => $socialite->email
                ];

                $this->store((string) $socialite->id, $userData);

                Session::regenerate();

                return $this->redirect();
            }

            $loginUsingSocialAccount = Social::where([
                'provider' => $provider,
                'provider_id' => (string) $socialite->id
            ])->first();

            if ($loginUsingSocialAccount) {
                $user = User::where('id', $loginUsingSocialAccount->user_id)->first();

                Auth::loginUsingId($user->id, true);

                Session::regenerate();

                return $this->redirect();
            }

            $userExist = User::where('email', $socialite->email)->first();

            if (!$userExist) {
                $user = User::updateOrCreate(
                    [
                        'email' => $socialite->email
                    ],
                    [
                        'name' => $socialite->name,
                        'username' => 'u' . Carbon::now()->setMicrosecond(0)->timestamp,
                        'avatar' =>
                            'https://gravatar.com/avatar/' . hash('sha256', strtolower(trim($socialite->email))),
                        'passwordless' => true
                    ]
                );

                $user
                    ->forceFill([
                        'email_verified_at' => Carbon::now()
                    ])
                    ->save();

                $data = [
                    'provider' => $provider,
                    'user_id' => $user->id,
                    'name' => $socialite->name,
                    'email' => $socialite->email
                ];

                $this->store((string) $socialite->id, $data);

                Auth::loginUsingId($user->id, true);

                Session::regenerate();

                return $this->redirect();
            }

            throw new Exception('Your ' . $provider . " account isn't connected to your profile yet");
        } catch (Exception $error) {
            Session::flash('error', $error->getMessage());

            return Redirect::route($this->user ? 'profile' : 'login');
        }
    }

    private function store(string $id, array $data): void
    {
        Social::updateOrCreate(
            [
                'provider_id' => $id
            ],
            $data
        );
    }

    private function session(Request $request): void
    {
        if ($request->header('referer') == route('profile')) {
            Session::put('url.socialite', route('profile'));
        } else {
            Session::put('url.socialite', route('home'));
        }
    }

    private function redirect(): RedirectResponse
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
