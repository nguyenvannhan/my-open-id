<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Traits\Auth\ThrottlesLogins;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ThrottlesLogins;

    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ], true)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);

        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $request->wantsJson()
                    ? new JsonResponse([], 200)
                    : redirect()->intended(url()->previous());
    }

    protected function sendFailedLoginResponse()
    {
        throw ValidationException::withMessages([
            'login' => [trans('auth.failed')],
        ]);
    }

    protected function username(): string 
    {
        return 'email';
    }

    public function logout(Request $request): RedirectResponse|JsonResponse
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $request->wantsJson()
        ? new JsonResponse([], 200)
        : redirect()->route('login');
    }
}
