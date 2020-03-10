<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use Illuminate\Support\Facades\Auth;

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

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $gUser = Socialite::driver('google')->stateless()->user();
        $user = User::where('email', $gUser->email)->first();
        if($user == null) {
            $user = $this->createUserByGoogle($gUser);
        }
        Auth::login($user, true);
        return redirect('/');
    }

    public function createUserByGoogle($gUser)
    {
        $user = User::create([
            'name' => $gUser->name,
            'email' => $gUser->email,
            'password' => \Hash::make(uniqid()),
        ]);
        return $user;
    }
}
