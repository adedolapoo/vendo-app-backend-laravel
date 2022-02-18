<?php namespace Modules\Users\Repositories\Sentinel;

use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Modules\Users\Events\UserHasActivatedAccount;
use Modules\Users\Repositories\AuthenticationInterface;
use Modules\Users\Repositories\UserInterface;

class SentinelAuthentication implements AuthenticationInterface
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
        if(!empty($credentials['user'])){
            $user = Sentinel::login($credentials['user']);
        }else{
            $user = Sentinel::forceAuthenticate($credentials, $remember);
        }

        if (empty($user)) throw new \Exception('Invalid login or password.');

        return $user;
    }

    /**
     * Register a new user.
     * @param  array $user
     * @return bool
     */
    public function register(array $user)
    {
        return app(UserInterface::class)->create((array) $user);
    }

    /**
     * Assign a role to the given user.
     * @param  \Modules\Users\Repositories\UserInterface $user
     * @param  \Modules\Users\Repositories\RoleInterface $role
     * @return mixed
     */
    public function assignRole($user, $role)
    {
        return $role->users()->attach($user);
    }

    /**
     * Log the user out of the application.
     * @return bool
     */
    public function logout()
    {
        return Sentinel::logout();
    }

    /**
     * Activate the given used id
     * @param  int    $userId
     * @param  string $code
     * @return mixed
     */
    public function activate($userId, $code)
    {
        $user = Sentinel::findById($userId);

        $success = Activation::complete($user, $code);
        if ($success) {
            event(new UserHasActivatedAccount($user));
        }

        return $success;
    }

    /**
     * Create an activation code for the given user
     * @param  \Modules\Users\Repositories\UserInterface $user
     * @return mixed
     */
    public function createActivation($user)
    {
        return Activation::create($user)->code;
    }

    /**
     * Create a reminders code for the given user
     * @param  \Modules\Users\Repositories\UserInterface $user
     * @return mixed
     */
    public function createReminderCode($user)
    {
        $reminder = Reminder::exists($user) ?: Reminder::create($user);

        return $reminder->code;
    }

    /**
     * Completes the reset password process
     * @param $user
     * @param  string $code
     * @param  string $password
     * @return bool
     */
    public function completeResetPassword($user, $code, $password)
    {
        return Reminder::complete($user, $code, $password);
    }

    /**
     * Determines if the current user has access to given permission
     * @param $permission
     * @return bool
     */
    public function hasAccess($permission)
    {
        if (! Sentinel::check()) {
            return false;
        }

        return Sentinel::hasAccess($permission);
    }

    /**
     * Check if the user is logged in
     * @return mixed
     */
    public function check()
    {
        return Sentinel::forceCheck();
    }

    /**
     * Get the ID for the currently authenticated user
     * @return int
     */
    public function id()
    {
        if (! $user = $this->check()) {
            return;
        }

        return $user->id;
    }

    public function byId($id)
    {
        try {
            $user = Sentinel::findById($id);
            Sentinel::login($user);
            return $user;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getUser()
    {
        try {
            Sentinel::getUser();
        } catch (\Exception $e) {
            return false;
        }
    }
}
