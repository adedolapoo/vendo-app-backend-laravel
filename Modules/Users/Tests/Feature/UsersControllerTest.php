<?php

namespace Modules\Users\Tests\Feature;

use Modules\Core\Tests\BaseTestCase;
use Modules\Users\Entities\Sanctum\User;

class UsersControllerTest extends BaseTestCase
{
    public function test_get_all_users()
    {
        $response = $this->actingAsSeller()->get('/users');

        $response->assertStatus(200);
    }

    public function test_create_user()
    {
        $response = $this->actingAsSeller()->withoutExceptionHandling()->post("/users",[
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'confirm_password' => 'password',
        ]);

        $response->assertStatus(201);
    }

    public function test_get_user()
    {
        $test_user = User::factory()->create();
        $response = $this->actingAsSeller()->get("/users/{$test_user->id}",);

        $response->assertStatus(200);
    }

    public function test_update_user()
    {
        $test_user = User::factory()->create();
        $response = $this->actingAsSeller()->patch("/users/{$test_user->id}",$test_user->getAttributes());

        $response->assertStatus(200);
    }

    public function test_delete_user()
    {
        $test_user = User::factory()->create();
        $response = $this->actingAsSeller()->delete("/users/{$test_user->id}");

        $response->assertOk();
    }

    public function test_buyer_cannot_get_all_users()
    {
        $this->actingAsBuyer()->get("/users")->assertUnauthorized();
    }

    public function test_buyer_cannot_create_user()
    {
        $this->actingAsBuyer()->post("/users")->assertUnauthorized();
    }

    public function test_buyer_cannot_update_user()
    {
        $test_user = User::factory()->create();
        $this->actingAsBuyer()->patch("/users/{$test_user->id}")->assertUnauthorized();
    }

    public function test_buyer_cannot_delete_user()
    {
        $test_user = User::factory()->create();
        $this->actingAsBuyer()->delete("/users/{$test_user->id}")->assertUnauthorized();
    }
}
