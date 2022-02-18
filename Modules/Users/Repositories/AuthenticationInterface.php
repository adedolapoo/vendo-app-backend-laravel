<?php

namespace Modules\Users\Repositories;


use Modules\Core\Repositories\RepositoryInterface;

interface AuthenticationInterface
{
    /**
     * Authenticate a user
     * @param  array $credentials
     * @param  bool  $remember    Remember the user
     * @return mixed
     */
    public function login(array $credentials, $remember = false);

    /**
     * Register a new user.
     * @param  array $user
     * @return bool
     */
    public function register(array $user);

    /**
     * Log the user out of the application.
     * @return mixed
     */
    public function logout();
}
