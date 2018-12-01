<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Carbon;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Socialite;

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

    const FACEBOOK = 'facebook';
    const GOOGLE = 'google';
    const GITHUB = 'github';
    const TWITTER = 'twitter';

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * GOOGLE
     */
    public function googleLogin()
    {
        return $this->providerLogin(self::GOOGLE);
    }

    public function googleCallback()
    {
        return $this->providerCallback(self::GOOGLE);
    }

    /**
     * FACEBOOK
     */
    public function facebookLogin()
    {
        return $this->providerLogin(self::FACEBOOK);
    }

    public function facebookCallback()
    {
        return $this->providerCallback(self::FACEBOOK);
    }

    /**
     * Redirect the user to the provider authentication page.
     *
     * @return Response
     */
    public function providerLogin($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.
     *
     * @return Response
     */
    public function providerCallback($provider)
    {
        $social_user = Socialite::driver($provider)->user();
        $user = $this->createOrGetUser($social_user, $provider);
        auth(backpack_guard_name())->login($user);

        return redirect()->to($this->redirectTo);
    }

    public function createOrGetUser(ProviderUser $providerUser, $provider)
    {
        $account = SocialAccount::whereProvider($provider)
            ->whereProviderUserId($providerUser->getId())
            ->first();

        $avatar = $providerUser->getAvatar();

        switch ($provider) {
            case self::FACEBOOK:
                $avatar = str_replace('normal', 'large', $avatar);
                $avatar .= '&type=square';
                break;
            case self::GOOGLE:
                $avatar = str_replace('?sz=50', '', $avatar);
                break;
        }

        if ($account) {
            $account->updated_at = Carbon::now();
            $account->token = $providerUser->token;
            $account->save();

            $account->user->avatar = $avatar;
            $account->user->save();

            return $account->user;
        }

        $account = new SocialAccount([
            'provider' => $provider,
            'provider_user_id' => $providerUser->getId(),
            'token' => $providerUser->token,
        ]);

        $user = User::whereEmail($providerUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'email' => $providerUser->getEmail(),
                'name' => $providerUser->getName(),
                'avatar' => $avatar,
                'password' => bcrypt(\Hash::make(uniqid())),
                'created_at' => Carbon::now(),
            ]);
        } else {
            $user->avatar = $avatar;
            $user->save();
        }

        $account->user()->associate($user);
        $account->save();

        return $user;
    }
}
