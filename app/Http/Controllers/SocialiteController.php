<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocialiteRequest;
use App\Models\User;
use App\Services\SocialService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class SocialiteController extends Controller
{
    public function __construct(
        protected ?User $user,
        protected SocialService $socialService,
        protected UserService $userService
    ) {
        $this->user = Auth::user();
    }

    public function redirect(SocialiteRequest $request): RedirectResponse
    {
        $isFromProfile = $request->header('referer') == route('profile');

        session()->put('url.intended', route($isFromProfile ? 'profile' : 'home'));

        return $request->redirect();
    }

    public function callback(string $provider, SocialiteRequest $request): RedirectResponse
    {
        try {
            $socialite = $request->authenticate();

            $this->socialService->authenticate($provider, $socialite, $this->user);

            return $this->redirectBack();
        } catch (Exception $exception) {
            return redirect()->route($this->user ? 'profile' : 'login')
                ->with('notify-session', [
                    'type' => 'error',
                    'message' => $exception->getMessage(),
                ]);
        }
    }

    private function redirectBack(): RedirectResponse
    {
        $intended = session()->get('url.intended');

        if ($intended) {
            session()->flush();

            return redirect()->to($intended);
        }

        return redirect()->route('profile');
    }
}
