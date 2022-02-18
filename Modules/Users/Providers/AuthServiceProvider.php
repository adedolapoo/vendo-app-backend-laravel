<?php

namespace Modules\Users\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Modules\Users\Entities\Sanctum\User;
use Modules\Users\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
       User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //define a seller role
        Gate::define('isSeller', function(User $user) {
            return $user->role == 'seller';
        });

        //define a user role
        Gate::define('isBuyer', function(User $user) {
            return $user->role == 'buyer';
        });

        //define a user role
        Gate::define('isAdmin', function(User $user) {
            return $user->role == 'admin';
        });

    }
}
