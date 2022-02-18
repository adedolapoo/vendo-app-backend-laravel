<?php

namespace Modules\Products\Tests\Feature;

use Modules\Core\Tests\BaseTestCase;
use Modules\Products\Entities\Product;
use Modules\Users\Entities\Sanctum\User;

class BuyProductsControllerTest extends BaseTestCase
{
    /**
     * Test to confirm user with buyer role can buy products
     *
     * @return void
     */
    public function test_user_with_buyer_role_can_buy()
    {
        $buyer = User::factory()->create(['deposit' => 100]);

        //create two different products
        Product::factory()->count(2)->create(['cost' => 10]);

        $response = $this->actingAs($buyer)->withoutExceptionHandling()->post('/buy', [
            'products' => [
                ['id' => 1, 'quantity' => 3],
                ['id' => 2, 'quantity' => 2]
            ]
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'deposit' => 100,
                    'total_amount' => 50,
                    'change' => [50]
                ]
            ]);
    }

    /**
     * Test to confirm user with buyer role can buy products
     *
     * @return void
     */
    public function test_user_with_seller_role_cannot_buy()
    {
        $this->actingAsSeller()->post('/buy')->assertUnauthorized();
    }
}
