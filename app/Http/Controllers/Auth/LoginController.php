<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
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

    protected $tokenResult;

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $attempt = $this->guard()->attempt($this->credentials($request));

        if ($attempt) {
            $user = $this->guard()->user();

            $token = $user->createToken('Web App');

            $this->tokenResult = $token;

            return true;
        }

        return false;
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        $token = (string) $this->tokenResult->accessToken;

        return response()->json([
            'token'      => $token,
            'token_type' => 'bearer',
            'expires_at' => Carbon::parse($this->tokenResult->token->expires_at)->toDateTimeString(),
        ], Response::HTTP_OK);
    }
}
