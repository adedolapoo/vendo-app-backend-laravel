<?php namespace Modules\Users\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Users\Http\Requests\LoginFormRequest;
use Modules\Users\Http\Requests\RegisterFormRequest;
use Modules\Users\Repositories\AuthenticationInterface;
use Modules\Users\Repositories\UserInterface as Repository;
use Illuminate\Http\Request;
use Modules\Users\Transformers\UserResource;

class AuthController extends BaseController
{

    /**
     * @var
     */
    protected $repository;

    /**
     * @var
     */
    private $auth;

    public function __construct(Repository $repository, AuthenticationInterface $auth)
    {
        parent::__construct($repository);
        $this->auth = $auth;
    }

    /**
     * Login a user
     *
     * @param LoginFormRequest $request
     * @return JsonResponse|UserResource
     */
    public function login(LoginFormRequest $request)
    {
        try {
            //Get the user and token
            [$user, $token] = $this->auth->login($request->safe()->all());

            return (new UserResource($user))->additional([
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * @param RegisterFormRequest $request
     * @return JsonResponse
     */
    public function register(RegisterFormRequest $request)
    {
        try {
            $user = $this->auth->register($request->all());

            return $this->respondWithItem($user);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function currentUser(Request $request)
    {
        try {
            return $this->respondWithItem($request->user());
        } catch (\Exception $e) {
            return $this->errorUnauthorized($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logoutAllDevices(Request $request)
    {
        try {
            //Delete all user tokens created
            $request->user()->tokens()->delete();

            return $this->respondOK();
        } catch (\Exception $e) {
            return $this->errorUnauthorized($e->getMessage());
        }
    }

}
