<?php namespace Modules\Users\Services;

use Modules\Users\Repositories\AuthenticationInterface as GuardContract;
use Modules\Users\Repositories\UserInterface;
use Tymon\JWTAuth\Contracts\Providers\Auth;

class JWTSentinel implements Auth
{
    protected $auth;
    /**
     * @var UserInterface
     */
    private $user;

    /**
     * Constructor.
     *
     * @param GuardContract $auth
     * @param UserInterface $user
     */
    public function __construct(GuardContract $auth, UserInterface $user)
    {
        $this->auth = $auth;
        $this->user = $user;
    }

    /**
     * Check a user's credentials.
     *
     * @param  array  $credentials
     *
     * @return bool
     */
    public function byCredentials(array $credentials)
    {
        return $this->auth->login($credentials);
    }

    /**
     * Authenticate a user via the id.
     *
     * @param  mixed  $id
     *
     * @return bool
     */
    public function byId($id)
    {
        return $this->auth->byId($id);
    }

    /**
     * Get the currently authenticated user.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->auth->check();
    }
}
