<?php

namespace Modules\Core\Tests;


use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Arr;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\Sanctum;
use Modules\Users\Entities\Sanctum\User;
use Tests\TestCase;

abstract class BaseTestCase extends TestCase
{
    use RefreshDatabase;

    public function runDatabaseMigrations()
    {
        $this->artisan('migrate:fresh', $this->migrateFreshUsing());

        $this->app[Kernel::class]->setArtisan(null);

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');

            RefreshDatabaseState::$migrated = false;
        });
    }

    /**
     * @param UserContract $user
     * @param null $driver
     * @return UserContract|BaseTestCase
     */
    public function actingAs(UserContract $user, $driver = null)
    {
        Sanctum::actingAs($user,['*']);

        return $this;
    }

    /**
     * Set the auth user to have buyer role
     *
     * @return $this|UserContract
     */
    public function actingAsBuyer()
    {
        $user = User::factory()->create();

        return $this->actingAs($user);
    }

    /**
     * Set the auth user to have seller role
     *
     * @return $this|UserContract
     */
    public function actingAsSeller()
    {
        $user = User::factory()->create(['role'=>'seller']);

        return $this->actingAs($user);
    }

}
