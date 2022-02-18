<?php namespace Modules\Users\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Users\Http\Requests\FormEditRequest;
use Modules\Users\Http\Requests\FormRequest;
use Modules\Users\Repositories\UserInterface as Repository;
use Modules\Users\Transformers\UserCollection;
use Modules\Users\Transformers\UserResource;

class UsersController extends BaseController
{

    /**
     * @var
     */
    protected $transformer = UserResource::class;

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * List of all products
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $users = $this->repository->allPaginated();

            return $this->respondWithCollection($users);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * Store a newly created product to db
     *
     * @param FormRequest $request
     * @return JsonResponse|UserResource
     */
    public function store(FormRequest $request)
    {
        try {
            $user = $this->repository->create($request->all());

            return $this->respondWithItem($user);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * Update
     *
     * @param int $id
     * @return JsonResponse|UserResource
     */
    public function show($id)
    {
        try {
            $user = $this->repository->find($id);

            return $this->respondWithItem($user);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * Update the a given user identified by ID
     *
     * @param FormEditRequest $request
     * @param int $id
     * @return JsonResponse|UserResource
     */
    public function update(FormEditRequest $request, $id)
    {
        try {
            $data = $request->all();
            $data['id'] = $id;
            $user = $this->repository->update($data);

            return $this->respondWithItem($user);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * Remove the specified user from db.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->repository->delete($id);

            return $this->respondOK('Deleted');
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
