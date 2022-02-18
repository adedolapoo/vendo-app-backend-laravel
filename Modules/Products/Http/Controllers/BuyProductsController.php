<?php

namespace Modules\Products\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Products\Http\Requests\BuyFormRequest;
use Modules\Products\Repositories\ProductInterface as Repository;
use Modules\Users\Repositories\UserInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class BuyProductsController extends BaseController
{
    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Deposit For user
     *
     * @param BuyFormRequest $request
     * @return JsonResponse
     */
    public function buy(BuyFormRequest $request)
    {
        try {
            $items = $request->get('products');
            $total_amount = 0;
            $products_output_array = [];

            //get the buyer's current deposit
            $user_deposit = $request->user()->deposit;
            if(empty($user_deposit)) throw new BadRequestException(__("Whoops! You dont have any coins deposited to make purchases, deposit coins now"));

            //begin transaction
            DB::beginTransaction();

            foreach ($items as $item) {
                try {
                    $product = $this->repository->byId($item['id']);

                    //Only perform operation if the quantity supplied is not greater than the amount available
                    if ($product->amount_available > $item['quantity']) {
                        $amount = $item['quantity'] * $product->cost;
                        $total_amount = $total_amount + $amount;

                        //Update the product amount available
                        $this->repository->update([
                            'amount_available' => $product->amount_available - $item['quantity']
                        ],$product);

                        //Save the product to the list of products to be returned to the user
                        $products_output_array[] = [
                            'id' => $product->id,
                            'name' => $product->name,
                            'cost' => $product->cost,
                            'quantity' => $item['quantity']
                        ];
                    }

                } catch (\Exception $e) {
                    throw new BadRequestException(__('Bad request, please check and try again'));
                }
            }

            if(empty($total_amount))
                throw new BadRequestException(__('Whoops! something went wrong, re-select products and try again'));

            //get the change left from the user deposit and the total
            $change_left = $user_deposit - $total_amount;

            //Check if the total is not more than the user deposit
            if ($change_left < 0)
                throw new BadRequestException(__('The total price of the selected products cannot be more than your deposit'));

            //Reset the user's deposit to zero since he is collecting the change
            app(UserInterface::class)->update(['deposit'=>0],$request->user());

            //commit the changes to the db
            DB::commit();

            //Return products, total and change back to the user
            return $this->respondWithArray([
                'success' => true,
                'data' => [
                    'products' => $products_output_array,
                    'deposit' => $user_deposit,
                    'total_amount' => $total_amount,
                    'change' => $this->_getChangeArray($change_left)
                ]
            ]);
        } catch (\Exception $e) {
            //Rollback db changes when there is error
            DB::rollBack();

            return $this->exceptionResponse($e);
        }
    }

    /**
     * Get the change in array from the change left
     *
     * @param $amount
     * @return array
     */
    private function _getChangeArray($amount)
    {
        //get the available coins
        $coins = config('users.deposit_range');

        //sort the coin by the highest first
        rsort($coins);

        $coins_output = [];

        // count coins using a greedy approach, we keep the change to ourselves, yippy!
        for ($i = 0; $i < count($coins); $i++) {
            if ($amount >= $coins[$i]) {
                $counter_int = 0;
                $counter = intval($amount / $coins[$i]);
                while ($counter_int < $counter) {
                    $coins_output[] = $coins[$i];
                    $counter_int++;
                };
                //$amount = $amount - ($counter * $coins[$i]);
                $amount = $amount % $coins[$i];
            }
        }

        return array_reverse($coins_output);
    }
}
