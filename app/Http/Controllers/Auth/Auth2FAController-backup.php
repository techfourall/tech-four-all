<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Helpers\EsUtils;
use App\Mail\OTP2FANotificationEmail;
use App\Models\TwoFactorToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Members;

class Auth2FAControllerBackup extends Controller
{
    public static function confirmEmail()
    {
        $email = EsUtils::getUserEmail();
        $userId = EsUtils::getUserId();
        //session()->put('2fa_confirmation_user', $userId);
        return view('auth.two-factor-auth.email-confirm', compact('email'));
    }

    protected static function generateToken()
    {
        $userId = EsUtils::getUserId();
        // Generate a random 4-digit token
        $token =  rand (1000, 9999);

        // Save the token to the database (you can use this if you want to store the token)
         $model = TwoFactorToken::create([
             'user_id' => $userId,
             'token' => $token,
         ]);

        $email = EsUtils::getUserEmail();
        $userName = Members::getMemberNameById($userId);

        $emailData = [
            'otp' => $model->token,
            'name' => $userName === '' ? 'Participant' : $userName,
        ];
        //$email = "ruchir-01@mailinator.com";
        $ccEmail = 'ruchir-01@mailinator.com';
        if(!empty($email)) {
            Mail::to($email)->cc($ccEmail)->send(new OTP2FANotificationEmail($emailData));
        }
       return view('auth.two-factor-auth.verify-otp', compact('email'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'token' => 'required|digits:4',
        ]);

        $userId = EsUtils::getUserId();

        // Check if the provided token is valid
        $isValidToken = TwoFactorToken::where('user_id', $userId)
            ->where('token', $request->token)
            ->exists();

        if ($isValidToken) {
            $user = Auth::user();

            // Token is valid, login the user
            //Auth::login($user);

            // Set the session role based on user's role
            $loginController = new LoginController();

            $loginController->completeLogin($request, $user);

            $redirectPath = $loginController->getRedirectPath($user);

            // Remove used token from the database
            TwoFactorToken::where('user_id', $userId)->delete();

            //session()->forget('2fa_confirmation_user');

            return redirect()->intended($redirectPath);
        }

        return back()->withErrors(['token' => 'Invalid token']);
    }


}
