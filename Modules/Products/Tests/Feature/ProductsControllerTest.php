<?php

namespace Modules\Products\Tests\Feature;

use Modules\Core\Tests\BaseTestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductsControllerTest extends BaseTestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetAllProducts()
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
    }
}
