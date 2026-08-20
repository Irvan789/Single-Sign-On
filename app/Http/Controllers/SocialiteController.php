<?php

namespace App\Http\Controllers;

use App\Models\Social;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    private ?User $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function redirect(string $provider, Request $request): RedirectResponse
    {
        $this->createSession($request);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider, Request $request): RedirectResponse
    {
        try {
            $socialite = Socialite::driver($provider)->user();

            if ($this->user) {

                if ($this->user->passwordless) {
                    throw new Exception('Please create password first before you can manage linking social accounts.');
                }

                $socialAccountById = Social::firstWhere('provider_id', $socialite->id);

                if ($socialAccountById) {
                    $socialAccountById->delete();

                    return $this->redirectBack($request);
                }

                $socialAccountByEmail = Social::firstWhere(['provider' => $provider, 'email' => $socialite->email]);

                if ($socialAccountByEmail) {
                    throw new Exception('Can\'t unlink social account with different email address.');
                }

                $socialAccountsByUserId = Social::firstWhere(['provider' => $provider, 'user_id' => $this->user->id]);

                if ($socialAccountsByUserId) {
                    throw new Exception('Can\'t unlink social account with different email address.');
                }

                $socialData = [
                    'user_id' => $this->user->id,
                    'provider' => $provider,
                    'name' => $socialite->name,
                    'email' => $socialite->email,
                ];

                $this->updateOrCreateSocialAccount($socialData, id: $socialite->id);

                return $this->redirectBack($request);
            }

            $loginUsingSocialAccount = Social::firstWhere('provider_id', $socialite->id);

            if ($loginUsingSocialAccount) {
                $user = User::find($loginUsingSocialAccount->user_id);

                Auth::loginUsingId($user->id, true);

                Session::regenerate();

                return $this->redirectBack($request);
            }

            $findUserByEmail = User::firstWhere('email', $socialite->email);

            if (! $findUserByEmail) {
                $userData = [
                    'name' => $socialite->name,
                    'username' => strtolower(trim(explode('@', $socialite->email)[0])).Str::random(5),
                    'avatar' => 'https://gravatar.com/avatar/'.hash('sha256', strtolower(trim($socialite->email))),
                    'passwordless' => true,
                ];

                $user = $this->updateOrCreateUserByEmail($userData, $socialite->email);

                $user->forceFill([
                    'email_verified_at' => Carbon::now(),
                ])->save();

                $socialData = [
                    'provider' => $provider,
                    'user_id' => $user->id,
                    'name' => $socialite->name,
                    'email' => $socialite->email,
                ];

                $this->updateOrCreateSocialAccount($socialData, id: $socialite->id);

                Auth::loginUsingId($user->id, true);

                Session::regenerate();

                return $this->redirectBack($request);
            }

            throw new Exception('Your '.$provider." account isn't connected to your profile yet.");
        } catch (Exception $error) {
            return redirect()->route($this->user ? 'profile' : 'login')
                ->with('notify', [
                    'type' => 'error',
                    'message' => $error->getMessage(),
                ]);
        }
    }

    private function updateOrCreateSocialAccount(array $data, ?string $id = null, ?string $email = null): Social
    {
        if (! $id && ! $email) {
            throw new Exception('Invalid request data');
        }

        return Social::updateOrCreate(
            $id ? ['provider_id' => $id]
                : ['email' => $email],
            $data
        );
    }

    private function updateOrCreateUserByEmail(array $data, string $email): User
    {
        return User::updateOrCreate(
            ['email' => $email],
            $data
        );
    }

    private function createSession(Request $request): void
    {
        $isFromProfile = $request->header('referer') == route('profile');
        session()->put('url.socialite', route($isFromProfile ? 'profile' : 'home'));
    }

    private function redirectBack(Request $request): RedirectResponse
    {
        $intended = $request->session()->get('url.intended');

        if ($intended && $intended != route('oauth.manage.clients')) {
            return redirect()->to($intended);
        }

        $socialiteUrl = $request->session()->get('url.socialite');

        if ($socialiteUrl) {
            $request->session()->flush();

            return redirect()->to($socialiteUrl);
        }

        return redirect()->route('profile');
    }
}
