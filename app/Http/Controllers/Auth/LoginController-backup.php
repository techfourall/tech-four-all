<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\HConst;
use App\Http\Controllers\Auth\Auth2FAController;

class LoginControllerBackup extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Display the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate the login data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log in the user
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication was successful
            $user = Auth::user();

            $auth2FA = true;
            if ($auth2FA) {
                Auth2FAController::confirmEmail($user);
            }

            // Check the user's role and redirect accordingly

            if ($user->hasRole(HConst::SESSION_ROLE_MEMBER)) {
                $request->session()->put(HConst::SESSION_ROLE, HConst::SESSION_ROLE_MEMBER);
                $redirectPath = '/meetings'; // Redirect to the employee's dashboard route
            } elseif ($user->hasRole(HConst::SESSION_ROLE_ADMIN)) {
                $request->session()->put(HConst::SESSION_ROLE, HConst::SESSION_ROLE_ADMIN);
                $redirectPath = '/admin/members'; // Redirect to the admin's dashboard route
            } else {
                $redirectPath = '/'; // Add a default path if the role is not recognized
            }



            return redirect()->intended($redirectPath);
        }

        // Authentication failed
        return back()->withErrors(['email' => 'Invalid credentials']);
    }


    // Log the user out
    public function logout(Request $request)
    {
        // Logout the authenticated user using the Auth facade
        Auth::logout();

        // Destroy the entire session
        $request->session()->flush();
        // Optionally, you can flash a success message to the session
//        $request->session()->flash('success', 'You have been logged out successfully.');

        // Redirect the user to the desired page (e.g., the home page)
        return redirect('/login')->with('status', 'You have been logged out successfully.');
    }
}
