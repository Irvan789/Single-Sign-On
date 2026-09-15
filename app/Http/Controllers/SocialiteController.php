<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocialiteRequest;
use App\Models\User;
use App\Services\SocialiteService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SocialiteController extends Controller
{
    public function __construct(
        protected ?User $user,
        protected SocialiteService $socialiteService,
        protected UserService $userService
    ) {
        $this->user = Auth::user();
    }

    public function redirect(SocialiteRequest $request): RedirectResponse
    {
        $isFromProfile = $request->header('referer') == route('profile');

        $passportAuthorizations = strcasecmp(strtok(session()->get('url.intended'), '?'), route('passport.authorizations.authorize'));

        session()->put('url.intended',
            session()->has('url.intended') && $passportAuthorizations === 0
               ? session()->get('url.intended')
               : route($isFromProfile ? 'profile' : 'home')
        );

        return $request->redirect();
    }

    public function callback(string $provider, SocialiteRequest $request): RedirectResponse
    {
        try {
            $socialite = $request->authenticate();

            $this->socialiteService->authenticate($provider, $socialite, $this->user);

            return $this->redirectBack();
        } catch (Exception $exception) {
            return redirect()->route($this->user ? 'profile' : 'login')
                ->with('notify-session', [
                    'type' => 'error',
                    'message' => $exception->getMessage() ?? 'Something went wrong!',
                ]);
        }
    }

    private function redirectBack(): RedirectResponse
    {
        $intended = session()->get('url.intended');

        Log::info('Social Redirect Back: '.session()->get('url.intended'));

        if ($intended) {
            session()->flush();

            return redirect()->to($intended);
        }

        return redirect()->route('profile');
    }
}
