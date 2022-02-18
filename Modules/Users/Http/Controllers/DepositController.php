<?php namespace Modules\Users\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Users\Http\Requests\DepositFormRequest;
use Modules\Users\Http\Requests\FormEditRequest;
use Modules\Users\Http\Requests\FormRequest;
use Modules\Users\Repositories\UserInterface as Repository;

class DepositController extends BaseController
{
    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Deposit For user
     *
     * @param DepositFormRequest $request
     * @return JsonResponse
     */
    public function deposit(DepositFormRequest $request)
    {
        try {
            $user_deposit = $request->get('deposit');

            $user = $this->repository->update([
                'id' => $request->user()->id,
                'deposit' => $user_deposit
            ]);

            return $this->respondWithItem($user);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * Deposit For user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function reset(Request $request)
    {
        try {
            $user = $this->repository->update([
                'id' => $request->user()->id,
                'deposit' => 0
            ]);

            return $this->respondWithItem($user);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
