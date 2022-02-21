<?php namespace Modules\Users\Repositories\Sanctum;

use Illuminate\Support\Facades\Auth;
use Modules\Users\Repositories\AuthenticationInterface;
use Modules\Users\Repositories\UserInterface;

class SanctumAuthentication implements AuthenticationInterface
{

    /**
     * Authenticate a user
     * @param  array $credentials
     * @param  bool $remember Remember the user
     * @return mixed
     * @throws \Exception
     */
    public function login(array $credentials, $remember = false)
    {
        $user = [];
        if(Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $user = Auth::user();
        }

        if (empty($user)) throw new \Exception('Invalid login or password.');

        //check to confirm if a user currently has a token
        if($user->tokens()->count()){
           // throw new \Exception('There is already an active session using your account');
        }

        $token = $user->createToken('VendoApp')->plainTextToken;

        return [$user,$token];
    }

    /**
     * Register a new user.
     * @param  array $data
     * @return bool
     */
    public function register(array $data)
    {
        return app(UserInterface::class)->create((array) $data);
    }

    /**
     * Log the user out of the application.
     * @return bool
     */
    public function logout()
    {
        return Sentinel::logout();
    }
}
