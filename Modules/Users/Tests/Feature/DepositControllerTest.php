<?php

namespace Modules\Users\Tests\Feature;

use Laravel\Sanctum\Sanctum;
use Modules\Core\Tests\BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Users\Entities\Sanctum\User;

class DepositControllerTest extends BaseTestCase
{
    /**
     * Test that user with buyer role can deposit
     *
     * @return void
     */
    public function test_user_with_buyer_role_can_deposit()
    {
        $response = $this->actingAsBuyer()->withoutExceptionHandling()->post('/deposit', [
            'deposit' => 20
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test that user with buyer role can deposit
     *
     * @return void
     */
    public function test_user_with_seller_role_cannot_deposit()
    {
       $this->actingAsSeller()->post('/deposit')->assertUnauthorized();
    }
}
