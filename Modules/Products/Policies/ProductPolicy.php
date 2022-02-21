<?php

namespace Modules\Products\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Products\Entities\Product;
use Modules\Users\Entities\Sanctum\User;

class ProductPolicy
{
    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function update(User $user, Product $product)
    {
        return optional($user)->id === $product->seller_id;
    }

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function delete(User $user, Product $product)
    {
        return optional($user)->id === $product->seller_id;
    }
}
