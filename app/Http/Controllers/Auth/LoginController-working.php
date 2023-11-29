<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\HConst;

class LoginControllerWorking extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        session()->forget('url.intended');
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($this->is2FAEnabled($user)) {
                // User has 2FA enabled, redirect to 2FA confirmation
                return redirect()->intended('/2fa/confirm');
            }

            $this->completeLogin($request, $user);

            return redirect()->intended($this->getRedirectPath($user));
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    protected function is2FAEnabled($user)
    {
        // Add logic to determine if 2FA is enabled for the user
        return true; // Replace with your actual 2FA check
    }

    public function completeLogin(Request $request, $user)
    {
        Auth::login($user);
        $this->setSessionRole($request, $user);
        $request->session()->regenerate();
    }

    // Add this method to set the session role // Also used by Auth2FAController
    public function setSessionRole(Request $request, $user)
    {
        if ($user->hasRole(HConst::SESSION_ROLE_MEMBER)) {
            $request->session()->put(HConst::SESSION_ROLE, HConst::SESSION_ROLE_MEMBER);
        } elseif ($user->hasRole(HConst::SESSION_ROLE_ADMIN)) {
            $request->session()->put(HConst::SESSION_ROLE, HConst::SESSION_ROLE_ADMIN);
        } else {
            $request->session()->put(HConst::SESSION_ROLE, ''); // Set default role if not recognized
        }
    }

    public function getRedirectPath($user)
    {
        if ($user->hasRole(HConst::SESSION_ROLE_MEMBER)) {
            return '/meetings';
        } elseif ($user->hasRole(HConst::SESSION_ROLE_ADMIN)) {
            return '/admin/members';
        }

        return '/';
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();
        return redirect('/login')->with('status', 'You have been logged out successfully.');
    }
}
